<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Speaker extends Model
{
    protected $fillable = [
        'name',
        'email',
        'venue',
        'department',
        'date',
        'time',
        'created_by',
        'approval_status',
        'approved_by',
        'approved_at',
        'faculty_approval_status',
        'faculty_approved_by',
        'faculty_approved_at',
        'feedback_token',
        'feedback_submitted'
    ];

    protected $casts = [
        'date' => 'date',
        'time' => 'datetime',
        'approved_at' => 'datetime',
        'faculty_approved_at' => 'datetime',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function facultyApprover(): BelongsTo
    {
        return $this->belongsTo(User::class, 'faculty_approved_by');
    }

    public function feedback()
    {
        return $this->hasOne(SpeakerFeedback::class);
    }
}
