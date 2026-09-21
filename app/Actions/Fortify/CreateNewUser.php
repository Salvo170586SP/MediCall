<?php

namespace App\Actions\Fortify;

use App\Concerns\PasswordValidationRules;
use App\Concerns\ProfileValidationRules;
use App\Jobs\GeneratePatientCodeItemJob;
use App\Models\GuestDetail;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Spatie\Permission\Models\Role;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules, ProfileValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            ...$this->profileRules(),
            'phone' => ['nullable', 'string', 'max:255'],
            'tax_id_code' => ['required', 'string', 'max:255'],
            'birth_city' => ['required', 'string', 'max:255'],
            'birth_date' => ['nullable', 'date'],
            'gender' => ['nullable', 'string', 'max:255'],
            'password' => $this->passwordRules(),
        ])->validate();

        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => $input['password'],
        ]);

        Role::firstOrCreate(['name' => 'Guest']);
        $user->assignRole('Guest');

        GuestDetail::create([
            'guest_id' => $user->id,
            'phone' => $input['phone'] ?? null,
            'tax_id_code' => $input['tax_id_code'],
            'birth_city' => $input['birth_city'],
            'birth_date' => $input['birth_date'] ?? null,
            'gender' => $input['gender'] ?? null,
        ]);

        GeneratePatientCodeItemJob::dispatch($user->id);

        return $user;
    }
}
