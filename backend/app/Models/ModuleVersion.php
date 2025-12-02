<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ModuleVersion extends Model
{
    use HasFactory;

    protected $fillable = [
        'module_id',
        'version',
        'changelog',
        'released_at',
        'is_stable',
    ];

    protected $casts = [
        'released_at' => 'datetime',
        'is_stable' => 'bool',
    ];

    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }

    public function updateLogs(): HasMany
    {
        return $this->hasMany(UpdateLog::class);
    }

    public function scopeStable(Builder $query): Builder
    {
        return $query->where('is_stable', true);
    }
}
