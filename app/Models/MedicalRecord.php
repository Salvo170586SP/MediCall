<?php

namespace App\Models;

use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

#[Fillable(['guest_id', 'doctor_id', 'appointment_id', 'visit_date', 'diagnosis', 'icd_codes', 'prescription', 'instructions'])]
class MedicalRecord extends Model
{
    protected function casts(): array
    {
        return [
            'visit_date' => 'date',
            'icd_codes' => 'array',
        ];
    }

    // -- Relazioni --

    /** @return BelongsTo<User, $this> */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'guest_id');
    }

    /** @return BelongsTo<User, $this> */
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    /** @return BelongsTo<Appointment, $this> */
    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    /**
     * @return MorphOne<Document, $this>
     */
    public function medicalRecordDocument(): MorphOne
    {
        return $this->morphOne(Document::class, 'documentable');
    }

    // -- Scope --

    // solo i record di un medico specifico
    /** @param Builder<MedicalRecord> $query
     * @return Builder<MedicalRecord>
     */
    public function scopeByDoctor(Builder $query, int $doctorId): Builder
    {
        return $query->where('doctor_id', $doctorId);
    }

    public function formatDate(DateTimeInterface|string|null $data): string
    {
        if (blank($data)) {
            return '-';
        }

        return Carbon::parse($data)->translatedFormat('d F Y');
    }
}
