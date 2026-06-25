<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Query\Builder;

#[Fillable(['guest_id', 'type', 'value', 'unit', 'measured_at', 'source', 'device_id', 'notes'])]
class HealthParameter extends Model
{
    protected function casts(): array
    {
        return [
            'value' => 'decimal:2',
            'measured_at' => 'datetime',
        ];
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'guest_id');
    }

    // -- Scope --

    // filtra per tipo di parametro, es. ->ofType('heart_rate')
    public function scopeOfType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    // filtra per sorgente, es. ->fromSource('wearable')
    public function scopeFromSource(Builder $query, string $source): Builder
    {
        return $query->where('source', $source);
    }

    // ultimi N giorni, es. ->recentDays(30)
    public function scopeRecentDays(Builder $query, int $days = 30): Builder
    {
        return $query->where('measured_at', '>=', now()->subDays($days));
    }

    // ordina dalla misurazione più recente
    public function scopeLatestFirst(Builder $query): Builder
    {
        return $query->orderByDesc('measured_at');
    }

    // -- Helpers --

    // restituisce valore e unità insieme, es. "120.50 mmHg"
    public function getFormattedValueAttribute(): string
    {
        return "{$this->value} {$this->unit}";
    }

    public function formatDate($data)
    {
        Carbon::setLocale('it');

        return Carbon::parse($data)->translatedFormat('d F Y');
    }
}
