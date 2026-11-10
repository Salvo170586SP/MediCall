<?php

namespace App\Actions\Fortify;

use App\Concerns\PasswordValidationRules;
use App\Concerns\ProfileValidationRules;
use App\Jobs\GenerateDoctorCodeItemJob;
use App\Models\DoctorDetail;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class CreateNewDoctor
{
    use PasswordValidationRules, ProfileValidationRules;

    /**
     * Validate and create a newly registered doctor.
     *
     * @param  array<string, mixed>  $input
     */
    public function create(array $input): User
    {
        $days = ['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'];

        $validator = Validator::make($input, [
            ...$this->profileRules(),
            'email_confirmation' => ['required', 'email', 'same:email'],
            'phone' => ['nullable', 'string', 'max:20'],
            'license_number' => ['required', 'string', 'max:255', 'unique:doctor_details,license_number'],
            'city' => ['nullable', 'string', 'max:255'],
            'specialty' => ['required', 'string', 'max:255'],
            'sub_specialty' => ['nullable', 'string', 'max:255'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'password' => $this->passwordRules(),
            'available_hours' => ['nullable', 'array:'.implode(',', $days)],
            'available_hours.*' => ['array:0,1', 'size:2'],
            'available_hours.*.0' => ['nullable', 'date_format:H:i'],
            'available_hours.*.1' => ['nullable', 'date_format:H:i'],
        ]);

        $validator->after(function ($validator) use ($input, $days): void {
            foreach ($days as $day) {
                $dayHours = data_get($input, "available_hours.{$day}");
                $start = is_array($dayHours) ? ($dayHours[0] ?? null) : null;
                $end = is_array($dayHours) ? ($dayHours[1] ?? null) : null;

                if (filled($start) !== filled($end)) {
                    $validator->errors()->add(
                        "available_hours.{$day}",
                        __('Inserisci sia l’orario di inizio che quello di fine, oppure lascia vuoti entrambi.')
                    );

                    continue;
                }

                if (
                    is_string($start)
                    && is_string($end)
                    && preg_match('/^\d{2}:\d{2}$/', $start) === 1
                    && preg_match('/^\d{2}:\d{2}$/', $end) === 1
                    && $end <= $start
                ) {
                    $validator->errors()->add(
                        "available_hours.{$day}",
                        __('L’orario di fine deve essere successivo a quello di inizio.')
                    );
                }
            }
        });

        $validated = $validator->validate();

        $availableHours = collect($days)
            ->mapWithKeys(fn (string $day): array => [
                $day => [
                    $validated['available_hours'][$day][0] ?? '',
                    $validated['available_hours'][$day][1] ?? '',
                ],
            ])
            ->all();

        $user = DB::transaction(function () use ($validated, $availableHours): User {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => $validated['password'],
            ]);

            Role::firstOrCreate(['name' => 'Doctor']);
            $user->assignRole('Doctor');

            DoctorDetail::create([
                'doctor_id' => $user->id,
                'license_number' => $validated['license_number'],
                'city' => $validated['city'] ?? null,
                'phone' => $validated['phone'] ?? null,
                'specialty' => $validated['specialty'],
                'sub_specialty' => $validated['sub_specialty'] ?? null,
                'bio' => $validated['bio'] ?? null,
                'available_hours' => $availableHours,
            ]);

            return $user;
        });

        GenerateDoctorCodeItemJob::dispatch($user->id)->afterCommit();

        return $user;
    }
}
