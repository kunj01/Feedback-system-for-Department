<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'department',
        'designation',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'subject_teacher');
    }

    public function batches()
    {
        return $this->belongsToMany(Batch::class, 'batch_teacher')
            ->withPivot('subject_id', 'type', 'notes')
            ->withTimestamps();
    }

    public function formAssignments()
    {
        return $this->hasMany(FormAssignment::class);
    }

    // Alias for formAssignments for backward compatibility
    public function assignments()
    {
        return $this->hasMany(FormAssignment::class);
    }
}
