<?php

namespace App\Enums;

enum AppointmentType: string
{
    case Video = "video";
    case DoctorSurgery = "doctor_surgery";
    case Home = "home";

    public function label(): string
    {
        return match ($this) {
            self::Video => "Video",
            self::DoctorSurgery => "Studio",
            self::Home => "Domicilio",
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Video => "bg-sky-100 text-sky-700",
            self::DoctorSurgery => "bg-gray-100 text-gray-700",
            self::Home => "bg-green-100 text-green-700",
        };
    }
}
