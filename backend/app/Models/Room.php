<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_id',
        'room_category_id',
        'number',
        'name',
        'floor',
        'description',
        'capacity',
        'price',
        'status',
        'is_active',
        'attributes',
    ];

    protected $casts = [
        'floor' => 'int',
        'capacity' => 'int',
        'price' => 'decimal:2',
        'is_active' => 'bool',
        'attributes' => 'array',
    ];

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(RoomCategory::class, 'room_category_id');
    }

    public function spaSchedule(): HasMany
    {
        return $this->hasMany(SpaSchedule::class);
    }

    public function spaProcedures(): BelongsToMany
    {
        return $this->belongsToMany(SpaProcedure::class, 'spa_schedule');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
