<?php

namespace App\Models;

use App\Enums\DocumentType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Query\Builder;

#[Fillable(['guest_id', 'doctor_id', 'appointment_id', 'visit_date', 'diagnosis', 'icd_codes', 'prescription', 'instructions', 'follow_up_date'])]
class MedicalRecord extends Model
{
    protected function casts(): array
    {
        return [
            'visit_date' => 'date',
            'follow_up_date' => 'date',
            'icd_codes' => 'array',
        ];
    }

    // -- Relazioni --

    public function patient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'guest_id');
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    /**
     * @return MorphOne<Document, $this>
     */
    public function medicalRecordDocument(): MorphOne
    {
        return $this->MorphOne(Document::class, 'documentable')
            ->whereIn('type', [
                DocumentType::MedicalRecord,
            ]);
    }

    /**
     * @return MorphOne<Document, $this>
     */
    public function prescriptionDocument(): MorphOne
    {
        return $this->morphOne(Document::class, 'documentable')
            ->where('type', DocumentType::Prescription);
    }

    // -- Scope --

    // solo i record di un medico specifico
    public function scopeByDoctor(Builder $query, int $doctorId): Builder
    {
        return $query->where('doctor_id', $doctorId);
    }

    public function formatDate($data)
    {
        Carbon::setLocale('it');

        return Carbon::parse($data)->translatedFormat('d F Y');
    }
}
