<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeamResource extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_id',
        'resource_type',
        'resource_id',
        'is_shared'
    ];

    protected $casts = [
        'is_shared' => 'boolean',
    ];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function resource()
    {
        return match ($this->resource_type) {
            'sender_name' => $this->belongsTo(SenderName::class, 'resource_id'),
            'contact' => $this->belongsTo(Contact::class, 'resource_id'),
            default => null,
        };
    }

    public function scopeShared($query)
    {
        return $query->where('is_shared', true);
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('resource_type', $type);
    }
}