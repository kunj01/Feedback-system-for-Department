<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Timetable extends Model
{
    use HasFactory;

    protected $table = 'timetable';

    protected $fillable = [
        'division_id',
        'day',
        'time_slot',
        'subject_id',
        'faculty_id',
        'room_no',
        'batch_id',
        'is_active',
    ];

    protected $casts = [
        'division_id' => 'integer',
        'subject_id' => 'integer',
        'faculty_id' => 'integer',
        'batch_id' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Get the division for this timetable entry
     */
    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    /**
     * Get the subject for this timetable entry
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Get the faculty for this timetable entry
     */
    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }

    /**
     * Get the batch for this timetable entry (if lab)
     */
    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    /**
     * Scope to filter by division
     */
    public function scopeByDivision($query, $divisionId)
    {
        return $query->where('division_id', $divisionId);
    }

    /**
     * Scope to filter by day
     */
    public function scopeByDay($query, $day)
    {
        return $query->where('day', $day);
    }

    /**
     * Scope to filter active entries
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Check if this is a practical/lab session
     */
    public function isPractical()
    {
        return !is_null($this->batch_id);
    }

    /**
     * Get display text for timetable cell
     */
    public function getDisplayTextAttribute()
    {
        $text = "{$this->subject->subject_code} - {$this->faculty->short_code} - {$this->room_no}";
        
        if ($this->batch) {
            $text .= " - {$this->batch->batch_name}";
        }
        
        return $text;
    }
}
