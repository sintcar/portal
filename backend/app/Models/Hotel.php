<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Hotel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'address',
        'city',
        'country',
        'contact_email',
        'contact_phone',
        'check_in_time',
        'check_out_time',
        'star_rating',
        'amenities',
        'is_active',
    ];

    protected $casts = [
        'check_in_time' => 'datetime:H:i:s',
        'check_out_time' => 'datetime:H:i:s',
        'star_rating' => 'int',
        'amenities' => 'array',
        'is_active' => 'bool',
    ];

    public function settings(): HasOne
    {
        return $this->hasOne(HotelSetting::class);
    }

    public function roomCategories(): HasMany
    {
        return $this->hasMany(RoomCategory::class);
    }

    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }

    public function restaurants(): HasMany
    {
        return $this->hasMany(Restaurant::class);
    }

    public function serviceCategories(): HasMany
    {
        return $this->hasMany(ServiceCategory::class);
    }

    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
    }

    public function spaProcedures(): HasMany
    {
        return $this->hasMany(SpaProcedure::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function news(): HasMany
    {
        return $this->hasMany(News::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
