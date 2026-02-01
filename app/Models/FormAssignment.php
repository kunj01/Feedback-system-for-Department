<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'form_name',
        'form_title',
        'student_id',
        'assigned_by',
        'status',
        'completed_at',
        'start_date',
        'end_date',
        'grace_period_hours',
        'is_multi_teacher',
        'subject_id',
        'teacher_id',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_multi_teacher' => 'boolean',
    ];

    /**
     * Get the student who was assigned the form.
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the user who assigned the form.
     */
    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    /**
     * Get the subject for multi-teacher feedback.
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Get the teacher for multi-teacher feedback.
     */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    /**
     * Get the form response for this assignment.
     */
    public function formResponse()
    {
        return $this->hasOne(FormResponse::class);
    }

    /**
     * Scope to get pending assignments.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope to get completed assignments.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Mark the assignment as completed.
     */
    public function markAsCompleted()
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);
    }

    /**
     * Check if the form is currently active (within start and end date + grace period).
     */
    public function isActive()
    {
        $now = now();
        
        // If no dates set, consider it always active
        if (!$this->start_date && !$this->end_date) {
            return true;
        }

        // Check if started
        if ($this->start_date && $now->lt($this->start_date)) {
            return false;
        }

        // Check if ended (including grace period)
        if ($this->end_date) {
            $effectiveEndDate = $this->end_date->copy()->addHours($this->grace_period_hours ?? 0);
            if ($now->gt($effectiveEndDate)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check if the form is upcoming.
     */
    public function isUpcoming()
    {
        return $this->start_date && now()->lt($this->start_date);
    }

    /**
     * Check if the form has ended (including grace period).
     */
    public function hasEnded()
    {
        if (!$this->end_date) {
            return false;
        }

        $effectiveEndDate = $this->end_date->copy()->addHours($this->grace_period_hours ?? 0);
        return now()->gt($effectiveEndDate);
    }

    /**
     * Check if the form is in grace period.
     */
    public function isInGracePeriod()
    {
        if (!$this->end_date || !$this->grace_period_hours) {
            return false;
        }

        $now = now();
        return $now->gt($this->end_date) && $now->lte($this->end_date->copy()->addHours($this->grace_period_hours));
    }

    /**
     * Get the status label (Upcoming, Active, Grace Period, Ended).
     */
    public function getStatusLabel()
    {
        if ($this->isUpcoming()) {
            return 'Upcoming';
        }

        if ($this->hasEnded()) {
            return 'Ended';
        }

        if ($this->isInGracePeriod()) {
            return 'Grace Period';
        }

        if ($this->isActive()) {
            return 'Active';
        }

        return 'N/A';
    }

    /**
     * Get the status badge color.
     */
    public function getStatusColor()
    {
        $status = $this->getStatusLabel();
        
        return match($status) {
            'Upcoming' => 'blue',
            'Active' => 'green',
            'Grace Period' => 'yellow',
            'Ended' => 'red',
            default => 'gray',
        };
    }
}
