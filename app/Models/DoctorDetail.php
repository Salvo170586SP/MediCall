<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/* #[Fillable(['doctor_id', 'code_item', 'img_url', 'license_number', 'city', 'phone', 'specialty', 'sub_specialty', 'bio', 'available_hours', 'slot_duration', 'is_active', 'notifications'])]
 */

class DoctorDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'code_item',
        'img_url',
        'license_number',
        'city',
        'phone',
        'specialty',
        'sub_specialty',
        'bio',
        'available_hours',
        'slot_duration',
        'is_active',
        'notifications',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'available_hours' => 'array',
            'slot_duration' => 'integer',
            'is_active' => 'boolean',
            'notifications' => 'boolean',
        ];
    }

    // RELATIONS
    public function user()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }
}
