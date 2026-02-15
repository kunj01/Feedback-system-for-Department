<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    use HasFactory;

    protected $fillable = [
        'semester',
        'branch',
        'division_number',
        'name',
        'is_active',
    ];

    protected $casts = [
        'semester' => 'integer',
        'division_number' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Get batches for this division
     */
    public function batches()
    {
        return $this->hasMany(Batch::class);
    }

    /**
     * Get students for this division
     */
    public function students()
    {
        return $this->hasMany(Student::class);
    }

    /**
     * Get timetable entries for this division
     */
    public function timetableEntries()
    {
        return $this->hasMany(Timetable::class);
    }

    /**
     * Scope to filter by semester
     */
    public function scopeBySemester($query, $semester)
    {
        return $query->where('semester', $semester);
    }

    /**
     * Scope to filter by branch
     */
    public function scopeByBranch($query, $branch)
    {
        return $query->where('branch', $branch);
    }

    /**
     * Scope to filter active divisions
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Boot method to auto-generate name
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($division) {
            if (!$division->name) {
                $division->name = "{$division->semester}-{$division->branch}-{$division->division_number}";
            }
        });
    }
}
