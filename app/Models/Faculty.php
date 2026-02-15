<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Faculty extends Model
{
    use HasFactory;

    protected $fillable = [
        'faculty_name',
        'short_code',
        'email',
        'contact',
        'department',
        'designation',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get timetable entries for this faculty
     */
    public function timetableEntries()
    {
        return $this->hasMany(Timetable::class);
    }

    /**
     * Scope to filter active faculty
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
