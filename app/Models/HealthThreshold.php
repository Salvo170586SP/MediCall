<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Query\Builder;

#[Fillable(['guest_id', 'doctor_by', 'parameter_type', 'min_value', 'max_value', 'severity', 'notify_doctor', 'notify_guest'])]
class HealthThreshold extends Model
{
    protected function casts(): array
    {
        return [
            'min_value' => 'decimal:2',
            'max_value' => 'decimal:2',
            'notify_doctor' => 'boolean',
            'notify_guest' => 'boolean',
        ];
    }

    // -- Relazioni --

    public function patient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'guest_id');
    }

    // il medico o admin che ha impostato la soglia
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_by');
    }

    // -- Scope --

    // filtra per tipo di parametro, es. ->forType('heart_rate')
    public function scopeForType(Builder $query, string $type): Builder
    {
        return $query->where('parameter_type', $type);
    }

    // solo le soglie critiche
    public function scopeCritical(Builder $query): Builder
    {
        return $query->where('severity', 'critical');
    }

    // solo quelle che notificano il medico
    public function scopeNotifyingDoctor(Builder $query): Builder
    {
        return $query->where('notify_doctor', true);
    }

    // -- Helpers --

    // controlla se un valore supera questa soglia
    public function isExceededBy(float $value): bool
    {
        $belowMin = $this->min_value !== null && $value < $this->min_value;
        $aboveMax = $this->max_value !== null && $value > $this->max_value;

        return $belowMin || $aboveMax;
    }

    // restituisce 'low' o 'high' a seconda di quale soglia è stata superata
    public function exceedanceDirection(float $value): ?string
    {
        if ($this->min_value !== null && $value < $this->min_value) {
            return 'low';
        }

        if ($this->max_value !== null && $value > $this->max_value) {
            return 'high';
        }

        return null;
    }
}
