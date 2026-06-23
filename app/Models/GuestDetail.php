<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['guest_id', 'code_item', 'img_url', 'birth_city', 'birth_date', 'tax_id_code', 'gender', 'phone', 'medical_history', 'blood_type', 'allergies'])]
class GuestDetail extends Model
{

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'birth_date' => 'date'
        ];
    }

    //RELATIONS
    public function user()
    {
        return $this->belongsTo(User::class, 'guest_id');
    }

    //FUNCTIONS
    public function formatDate($data)
    {
        Carbon::setLocale('it');
        return Carbon::parse($data)->translatedFormat('d F Y');
    }
}
