<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\DocumentType;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
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
 * @property-read DoctorDetail|null $doctorDetail
 * @property-read GuestDetail|null $guestDetail
 */
#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'two_factor_secret', 'two_factor_recovery_codes', 'remember_token'])]
class User extends Authenticatable implements PasskeyUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasRoles, Notifiable, PasskeyAuthenticatable, TwoFactorAuthenticatable;

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
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    // RELATIONS
    /** @return HasOne<DoctorDetail, $this> */
    public function doctorDetail(): HasOne
    {
        return $this->hasOne(DoctorDetail::class, 'doctor_id');
    }

    /** @return HasOne<GuestDetail, $this> */
    public function guestDetail(): HasOne
    {
        return $this->hasOne(GuestDetail::class, 'guest_id');
    }

    // parametri
    /** @return HasMany<HealthParameter, $this> */
    public function healthParameters(): HasMany
    {
        return $this->hasMany(HealthParameter::class, 'guest_id');
    }

    // parametri
    /** @return HasMany<HealthThreshold, $this> */
    public function healthThresholds(): HasMany
    {
        return $this->hasMany(HealthThreshold::class, 'guest_id');
    }

    // appuntamenti dal paziente
    /** @return HasMany<Appointment, $this> */
    public function appointmentsPatient(): HasMany
    {
        return $this->hasMany(Appointment::class, 'guest_id');
    }

    // appuntamenti dal dottore
    /** @return HasMany<Appointment, $this> */
    public function appointmentsDoctor(): HasMany
    {
        return $this->hasMany(Appointment::class, 'doctor_id');
    }

    /** @return BelongsToMany<User, $this> */
    public function patients(): BelongsToMany
    {
        return $this->belongsToMany(
            self::class,
            'appointments',
            'doctor_id',
            'guest_id',
        );
    }

    /** @return MorphMany<Document, $this> */
    public function documents(): MorphMany
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    /** @return MorphOne<Document, $this> */
    public function photo(): MorphOne
    {
        return $this->morphOne(Document::class, 'documentable')
            ->where('type', DocumentType::Photo);
    }

    /** @return MorphOne<Document, $this> */
    public function fiscalCode(): MorphOne
    {
        return $this->morphOne(Document::class, 'documentable')
            ->where('type', DocumentType::TaxCode);
    }

    /** @return MorphOne<Document, $this> */
    public function identityCard(): MorphOne
    {
        return $this->morphOne(Document::class, 'documentable')
            ->where('type', DocumentType::IdentityCard);
    }

    // pazienti unici seguiti dal medico
    protected ?int $patientsCountCache = null;

    public function getPatientsCountAttribute(): int
    {
        return $this->patientsCountCache ??= $this->appointmentsDoctor()
            ->distinct('guest_id')
            ->count('guest_id');
    }

    // medici che seguono il paziente
    protected ?int $doctorsCountCache = null;

    public function getDoctorsCountAttribute(): int
    {
        return $this->doctorsCountCache ??= $this->appointmentsPatient()
            ->distinct('doctor_id')
            ->count('doctor_id');
    }

    // cartelle cliniche dove l'utente è il PAZIENTE
    /** @return HasMany<MedicalRecord, $this> */
    public function medicalRecordsAsPatient(): HasMany
    {
        return $this->hasMany(MedicalRecord::class, 'guest_id');
    }

    // cartelle cliniche redatte dall'utente come MEDICO
    /** @return HasMany<MedicalRecord, $this> */
    public function medicalRecordsAsDoctor(): HasMany
    {
        return $this->hasMany(MedicalRecord::class, 'doctor_id');
    }
}
