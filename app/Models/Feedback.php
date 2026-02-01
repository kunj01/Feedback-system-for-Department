<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $table = 'feedback';
    
    protected $fillable = [
        'student_id',
        'subject_id',
        'faculty_id',
        'responses',
        'overall_rating',
        'comments',
    ];
    
    protected $casts = [
        'responses' => 'array',
        'overall_rating' => 'integer',
    ];
    
    // Relationships
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
