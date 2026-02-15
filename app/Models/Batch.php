<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    use HasFactory;

    protected $fillable = [
        'division_id',
        'batch_name',
        'description',
        'is_active',
    ];

    protected $casts = [
        'division_id' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Get the division that owns this batch
     */
    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    /**
     * Get students in this batch
     */
    public function students()
    {
        return $this->hasMany(Student::class);
    }

    /**
     * Get timetable entries for this batch
     */
    public function timetableEntries()
    {
        return $this->hasMany(Timetable::class);
    }

    /**
     * Get teachers assigned to this batch
     */
    public function teachers()
    {
        return $this->belongsToMany(Teacher::class, 'batch_teacher')
            ->withPivot('subject_id', 'type', 'notes')
            ->withTimestamps();
    }

    /**
     * Scope to filter active batches
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get full batch identifier (e.g., "4-IT-2 (A1)")
     */
    public function getFullNameAttribute()
    {
        return $this->division->name . ' (' . $this->batch_name . ')';
    }
}
