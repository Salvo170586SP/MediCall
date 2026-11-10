<?php

namespace App\Models;

use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['guest_id', 'code_item', 'img_url', 'birth_city', 'birth_date', 'tax_id_code', 'gender', 'phone', 'medical_history', 'blood_type', 'allergies', 'is_active', 'notifications'])]
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
            'birth_date' => 'date',
            'is_active' => 'boolean',
            'notifications' => 'boolean',
        ];
    }

    // RELATIONS
    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'guest_id');
    }

    // FUNCTIONS
    public function formatDate(DateTimeInterface|string|null $data): string
    {
        if (blank($data)) {
            return '-';
        }

        return Carbon::parse($data)->translatedFormat('d F Y');
    }
}
