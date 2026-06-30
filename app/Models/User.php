<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Laravel\Fortify\Contracts\PasskeyUser;
use Laravel\Fortify\PasskeyAuthenticatable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Spatie\Permission\Traits\HasRoles;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property Carbon|null $two_factor_confirmed_at
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'two_factor_secret', 'two_factor_recovery_codes', 'remember_token'])]
class User extends Authenticatable implements PasskeyUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, PasskeyAuthenticatable, TwoFactorAuthenticatable, HasRoles;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn($word) => Str::substr($word, 0, 1))
            ->implode('');
    }


    //RELATIONS
    public function doctorDetail()
    {
        return $this->hasOne(DoctorDetail::class, 'doctor_id');
    }

    public function guestDetail()
    {
        return $this->hasOne(GuestDetail::class, 'guest_id');
    }

    public function healthParameters(): HasMany
    {
        return $this->hasMany(HealthParameter::class, 'guest_id');
    }

    public function healthThresholds(): HasMany
    {
        return $this->hasMany(HealthThreshold::class, 'guest_id');
    }


    public function appointmentsPatient(): HasMany
    {
        return $this->hasMany(Appointment::class, 'guest_id');
    }

    public function appointmentsDoctor(): HasMany
    {
        return $this->hasMany(Appointment::class, 'doctor_id');
    }

    // pazienti unici seguiti dal medico
    public function patientsDoctor()
    {
        return User::whereIn(
            'id',
            $this->appointmentsDoctor()
                ->distinct('guest_id')
                ->pluck('guest_id')
        );
    }

    // pazienti unici seguiti dal medico
    public function doctorsPatients()
    {
        return User::whereIn(
            'id',
            $this->appointmentsPatient()
                ->distinct('guest_id')
                ->pluck('guest_id')
        );
    }
}
