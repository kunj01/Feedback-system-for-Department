<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'subject_code',
        'subject_name',
        'semester',
        'branch',
        'subject_type',
        'credits',
        'sort_order',
        'description',
        'is_active',
        'has_lab',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'has_lab' => 'boolean',
        'semester' => 'integer',
        'sort_order' => 'integer',
        'credits' => 'integer',
    ];

    /**
     * Get timetable entries for this subject
     */
    public function timetableEntries()
    {
        return $this->hasMany(Timetable::class);
    }

    /**
     * Get all teachers assigned to this subject
     */
    public function teachers()
    {
        return $this->belongsToMany(Teacher::class, 'subject_teacher')
            ->withTimestamps();
    }

    /**
     * Get all lab teachers assigned to this subject
     */
    public function labTeachers()
    {
        return $this->belongsToMany(Teacher::class, 'subject_lab_teacher')
            ->withTimestamps();
    }

    /**
     * Get form assignments for this subject
     */
    public function formAssignments()
    {
        return $this->hasMany(FormAssignment::class);
    }

    /**
     * Scope to filter subjects by semester
     */
    public function scopeBySemester($query, $semester)
    {
        return $query->where('semester', $semester);
    }

    /**
     * Scope to filter active subjects
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to order subjects by sort order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc')->orderBy('name', 'asc');
    }
}
