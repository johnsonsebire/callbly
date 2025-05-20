<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SmsTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'content',
        'description'
    ];

    /**
     * Get the user that owns the template.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}