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
        Validator::make($input, [
            ...$this->profileRules(),
            'phone' => ['nullable', 'string', 'max:20'],
            'license_number' => ['required', 'string', 'max:255', 'unique:doctor_details,license_number'],
            'city' => ['nullable', 'string', 'max:255'],
            'specialty' => ['required', 'string', 'max:255'],
            'sub_specialty' => ['nullable', 'string', 'max:255'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'password' => $this->passwordRules(),
        ])->validate();

        $user = DB::transaction(function () use ($input): User {
            $user = User::create([
                'name' => $input['name'],
                'email' => $input['email'],
                'password' => $input['password'],
            ]);

            Role::firstOrCreate(['name' => 'Doctor']);
            $user->assignRole('Doctor');

            DoctorDetail::create([
                'doctor_id' => $user->id,
                'license_number' => $input['license_number'],
                'city' => $input['city'] ?? null,
                'phone' => $input['phone'] ?? null,
                'specialty' => $input['specialty'],
                'sub_specialty' => $input['sub_specialty'] ?? null,
                'bio' => $input['bio'] ?? null,
                'available_hours' => [
                    'mon' => ['', ''],
                    'tue' => ['', ''],
                    'wed' => ['', ''],
                    'thu' => ['', ''],
                    'fri' => ['', ''],
                    'sat' => ['', ''],
                    'sun' => ['', ''],
                ],
            ]);

            return $user;
        });

        GenerateDoctorCodeItemJob::dispatch($user->id)->afterCommit();

        return $user;
    }
}
