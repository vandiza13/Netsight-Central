<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class License extends Model
{
    protected $fillable = [
        'customer_name',
        'license_key',
        'target_domain',
        'target_ip',
        'max_routers',
        'status',
        'last_ping_ip',
        'last_ping_at',
        'expires_at',
    ];

    protected $casts = [
        'last_ping_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    /**
     * Determine if the license is expired based on current time.
     */
    public function getIsExpiredAttribute(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }
}
