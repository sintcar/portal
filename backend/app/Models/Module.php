<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Module extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'bool',
    ];

    public function versions(): HasMany
    {
        return $this->hasMany(ModuleVersion::class);
    }

    public function licenses(): HasMany
    {
        return $this->hasMany(License::class);
    }

    public function updateLogs(): HasManyThrough
    {
        return $this->hasManyThrough(UpdateLog::class, ModuleVersion::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
