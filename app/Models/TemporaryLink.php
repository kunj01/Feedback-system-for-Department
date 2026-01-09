<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class TemporaryLink extends Model
{
    protected $fillable = [
        'email',
        'token',
        'type',
        'expires_at',
        'used_at',
        'metadata',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used_at' => 'datetime',
        'metadata' => 'array',
    ];

    /**
     * Check if the link has expired
     */
    public function isExpired(): bool
    {
        return $this->expires_at < Carbon::now();
    }

    /**
     * Check if the link has been used
     */
    public function isUsed(): bool
    {
        return $this->used_at !== null;
    }

    /**
     * Check if the link is valid (not expired and not used)
     */
    public function isValid(): bool
    {
        return !$this->isExpired() && !$this->isUsed();
    }

    /**
     * Mark the link as used
     */
    public function markAsUsed(): bool
    {
        $this->used_at = Carbon::now();
        return $this->save();
    }

    /**
     * Scope to get valid links only
     */
    public function scopeValid($query)
    {
        return $query->where('expires_at', '>', Carbon::now())
                     ->whereNull('used_at');
    }

    /**
     * Scope to get expired links
     */
    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<=', Carbon::now());
    }

    /**
     * Scope to get used links
     */
    public function scopeUsed($query)
    {
        return $query->whereNotNull('used_at');
    }

    /**
     * Scope to filter by type
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }
}
