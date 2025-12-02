<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RolePermission extends Model
{
    use HasFactory;

    protected $fillable = [
        'role_id',
        'permission',
        'is_allowed',
    ];

    protected $casts = [
        'is_allowed' => 'bool',
    ];

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }
}
