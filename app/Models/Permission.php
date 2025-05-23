<?php

namespace App\Models;

use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    protected $fillable = [
        'name',
        'guard_name',
        'team_id', // Add team_id to fillable
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}