<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'roll_no',
        'registration_no',
        'dob',
        'gender',
        'father_name',
        'mother_name',
        'address',
        'contact',
        'email',
        'department_id',
        'course',
        'batch',
        'cgpa',
        'academic_details',
        'training_status',
    ];

    protected function casts(): array
    {
        return [
            'dob' => 'date',
            'batch' => 'integer',
            'cgpa' => 'decimal:2',
            'academic_details' => 'array',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_students')
            ->withPivot('assigned_on', 'role_in_project')
            ->withTimestamps();
    }

    public function placements()
    {
        return $this->hasMany(StudentPlacement::class);
    }

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class);
    }

    public function reports()
    {
        return $this->hasMany(ReportLog::class);
    }
}
