<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    protected $fillable = [
        'name',
        'guard_name',
        'team_id',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($role) {
            if (!isset($role->team_id) && auth()->check()) {
                $role->team_id = auth()->user()->current_team_id;
            }
        });
    }
}