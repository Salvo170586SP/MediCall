<?php

namespace App\Models;

use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    /** @return BelongsTo<User, $this> */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'guest_id');
    }

    // -- Scope --

    // filtra per tipo di parametro, es. ->ofType('heart_rate')
    /** @param Builder<HealthParameter> $query
     * @return Builder<HealthParameter>
     */
    public function scopeOfType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    // filtra per sorgente, es. ->fromSource('wearable')
    /** @param Builder<HealthParameter> $query
     * @return Builder<HealthParameter>
     */
    public function scopeFromSource(Builder $query, string $source): Builder
    {
        return $query->where('source', $source);
    }

    // ultimi N giorni, es. ->recentDays(30)
    /** @param Builder<HealthParameter> $query
     * @return Builder<HealthParameter>
     */
    public function scopeRecentDays(Builder $query, int $days = 30): Builder
    {
        return $query->where('measured_at', '>=', now()->subDays($days));
    }

    // ordina dalla misurazione più recente
    /** @param Builder<HealthParameter> $query
     * @return Builder<HealthParameter>
     */
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

    public function formatDate(DateTimeInterface|string|null $data): string
    {
        if (blank($data)) {
            return '-';
        }

        return Carbon::parse($data)->translatedFormat('d F Y');
    }
}
