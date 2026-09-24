<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Query\Builder;

#[Fillable(['guest_id', 'doctor_id', 'scheduled_at', 'duration', 'type', 'status', 'reason', 'notes', 'cancellation_reason', 'cancelled_by', 'reminder_sent_at'])]
class Appointment extends Model
{
    protected function casts(): array
    {
        return [
            'scheduled_at'     => 'datetime',
            'reminder_sent_at' => 'datetime',
            'duration'         => 'integer',
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

    // chi ha cancellato l'appuntamento (paziente o medico)
    public function cancelledBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }



    // -- Scope --

    // appuntamenti futuri non cancellati
    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->where('scheduled_at', '>', now())
            ->whereIn('status', ['pending', 'confirmed']);
    }

    // appuntamenti passati
    public function scopePast(Builder $query): Builder
    {
        return $query->where('scheduled_at', '<', now());
    }

    // per status
    public function scopeWithStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }

    // per tipo
    public function scopeOfType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    public static function typeOptions(): array
    {
        return config('appointments.types', []) ?? [];
    }

    public function typeLabel(): string
    {
        return config("appointments.types.{$this->type}.label") ?? str()->title(str_replace('_', ' ', $this->type ?? ''));
    }

    public function typeClasses(): string
    {
        return config("appointments.types.{$this->type}.classes") ?? 'border-slate-200 bg-slate-100 text-slate-700';
    }

    public function statusLabel(): string
    {
        return config("appointments.status.{$this->status}.label") ?? str()->title(str_replace('_', ' ', $this->status ?? ''));
    }

    public function statusClasses(): string
    {
        return config("appointments.status.{$this->status}.classes") ?? 'border-slate-200 bg-slate-100 text-slate-700';
    }

    // promemoria da inviare — confermati, nelle prossime 24h, senza promemoria già inviato
    public function scopeNeedingReminder(Builder $query): Builder
    {
        return $query->where('status', 'confirmed')
            ->whereBetween('scheduled_at', [now(), now()->addHours(24)])
            ->whereNull('reminder_sent_at');
    }

    // -- Helpers --
    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    // orario di fine appuntamento
    public function endsAt(): \Carbon\Carbon
    {
        return $this->scheduled_at->addMinutes($this->duration);
    }

    public function formatDate($data)
    {
        return ucfirst(Carbon::parse($data)->translatedFormat('l d F Y'));
    }

    public function formatClock($data)
    {
        if (blank($data)) {
            return '-';
        }

        return Carbon::parse($data)->translatedFormat('H:i');
    }
}
