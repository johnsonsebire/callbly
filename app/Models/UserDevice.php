<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDevice extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'device_id',
        'device_name',
        'device_token',
        'platform',
        'biometric_enabled',
        'notification_enabled',
        'notification_settings',
        'last_authenticated_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'biometric_enabled' => 'boolean',
        'notification_enabled' => 'boolean',
        'notification_settings' => 'array',
        'last_authenticated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the device.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
