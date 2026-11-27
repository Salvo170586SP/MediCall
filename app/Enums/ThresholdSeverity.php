<?php

namespace App\Enums;

enum ThresholdSeverity: string
{
    case Normal = 'normal';
    case Info = 'info';
    case Warning = 'warning';
    case Critical = 'critical';

    public function label(): string
    {
        return match ($this) {
            self::Normal => "Normale",
            self::Info => 'Info',
            self::Warning => 'Attenzione',
            self::Critical => 'Critico',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Normal => "bg-green-100 text-green-700",
            self::Info => 'bg-gray-100 text-gray-700',
            self::Warning => 'bg-yellow-100 text-yellow-700',
            self::Critical => 'bg-red-100 text-red-700',
        };
    }
}
