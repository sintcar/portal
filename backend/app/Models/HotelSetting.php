<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HotelSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_id',
        'timezone',
        'currency',
        'tax_rate',
        'default_language',
        'booking_policy',
        'cancellation_policy',
        'preferences',
    ];

    protected $casts = [
        'tax_rate' => 'decimal:2',
        'preferences' => 'array',
    ];

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }
}
