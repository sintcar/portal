<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SpaSchedule extends Model
{
    use HasFactory;

    protected $table = 'spa_schedule';

    protected $fillable = [
        'spa_procedure_id',
        'room_id',
        'staff_name',
        'scheduled_at',
        'status',
        'notes',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
    ];

    public function procedure(): BelongsTo
    {
        return $this->belongsTo(SpaProcedure::class, 'spa_procedure_id');
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->where('scheduled_at', '>=', now());
    }
}
