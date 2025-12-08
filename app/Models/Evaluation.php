<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'student_id',
        'guide_id',
        'evaluation_date',
        'mode',
        'marks_out_of_15',
        'internal_exam_marks',
        'internal_exam_grade',
        'attendance_percent',
        'remarks',
        'locked',
        'approved_by_head',
        'head_comments',
    ];

    protected function casts(): array
    {
        return [
            'evaluation_date' => 'date',
            'marks_out_of_15' => 'decimal:2',
            'internal_exam_marks' => 'decimal:2',
            'attendance_percent' => 'decimal:2',
            'locked' => 'boolean',
            'approved_by_head' => 'boolean',
        ];
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function guide()
    {
        return $this->belongsTo(User::class, 'guide_id');
    }
}
