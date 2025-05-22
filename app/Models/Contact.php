<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Contact extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'phone_number',
        'email',
        'date_of_birth',
        'company',
        'notes',
        'custom_fields',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'custom_fields' => 'json',
        'date_of_birth' => 'date',
    ];

    /**
     * Get the user that owns the contact.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the contact groups this contact belongs to.
     */
    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(ContactGroup::class, 'contact_group_members');
    }

    /**
     * Format the phone number to ensure it's in the correct international format.
     *
     * @param string $value
     * @return void
     */
    public function setPhoneNumberAttribute($value)
    {
        // Remove any non-numeric characters
        $cleaned = preg_replace('/[^0-9]/', '', $value);
        
        // If it starts with a plus sign in the original value, add it back
        if (str_starts_with($value, '+')) {
            $this->attributes['phone_number'] = '+' . $cleaned;
            return;
        }
        
        // If it already starts with a country code (e.g. 233), keep it as is
        if (strlen($cleaned) >= 11 && preg_match('/^(233|234|235|1|44)/', $cleaned)) {
            $this->attributes['phone_number'] = $cleaned;
            return;
        }
        
        // Otherwise assume Ghana and add the country code
        // Remove leading zeros if they exist
        $cleaned = ltrim($cleaned, '0');
        $this->attributes['phone_number'] = '233' . $cleaned;
    }

    /**
     * Get the full name of the contact.
     *
     * @return string
     */
    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}