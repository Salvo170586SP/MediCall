<?php

namespace App\Enums;

enum DocumentType: string
{
    case IdentityCard = 'identity_card';
    case TaxCode = 'tax_code';
    case Prescription = 'prescription';
    case MedicalRecord = 'medical_record';
    case Photo = 'photo';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::IdentityCard => "Carta d'identità",
            self::TaxCode => 'Codice fiscale',
            self::Prescription => 'Ricetta medica',
            self::MedicalRecord => 'Referto medico',
            self::Photo => 'Fotografia',
            self::Other => 'Altro',
        };
    }
}
