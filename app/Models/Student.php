<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'student_id',
        'enrollment_no',
        'first_name',
        'middle_name',
        'last_name',
        'roll_no',
        'registration_no',
        'dob',
        'gender',
        'father_name',
        'mother_name',
        'address',
        'city',
        'contact',
        'email',
        'personal_email',
        'department_id',
        'division_id',
        'batch_id',
        'semester',
        'branch',
        'course',
        'academic_year',
        'cgpa',
        'ssc_percentage',
        'hsc_percentage',
        'diploma_percentage',
        'btech_cgpa_upto_5th',
        'admission_type',
        'is_eligible',
        'counsellor_name',
        'academic_details',
        'training_status',
    ];

    protected function casts(): array
    {
        return [
            'dob' => 'date',
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

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function batchGroup()
    {
        return $this->belongsTo(Batch::class, 'batch_id');
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
