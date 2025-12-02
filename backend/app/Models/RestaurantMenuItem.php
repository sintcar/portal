<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RestaurantMenuItem extends Model
{
    use HasFactory;

    protected $table = 'restaurant_menu';

    protected $fillable = [
        'restaurant_id',
        'name',
        'description',
        'price',
        'is_available',
        'tags',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_available' => 'bool',
        'tags' => 'array',
    ];

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function scopeAvailable(Builder $query): Builder
    {
        return $query->where('is_available', true);
    }
}
