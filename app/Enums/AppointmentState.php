<?php

namespace App\Enums;

enum AppointmentState: string
{
    case Pending = 'pending';
    case Confirmed = 'confirmed';
    case NoShow = 'no_show';
    case Completed = 'completed';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::Completed => "Completato",
            self::NoShow => 'n/d',
            self::Confirmed => 'Confermato',
            self::Pending => 'In attesa',
            self::Cancelled => 'Cancellato',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Completed => "bg-sky-100 text-sky-700",
            self::NoShow => 'bg-gray-100 text-gray-700',
            self::Confirmed => 'bg-green-100 text-green-700',
            self::Pending => 'bg-yellow-100 text-yellow-700',
            self::Cancelled => 'bg-red-100 text-red-700',
        };
    }
}
