<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeedbackAssignment extends Model
{
    protected $fillable = [
        'student_id',
        'subject_id',
        'academic_year'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
