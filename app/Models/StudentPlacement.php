<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentPlacement extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'company_id',
        'project_id',
        'offer_date',
        'status',
        'placed_by_charusat',
        'has_offer_letter',
        'package',
        'stipend',
        'position',
        'joining_date',
        'documents',
        'confirmed_final',
        'remarks',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'offer_date' => 'date',
            'joining_date' => 'date',
            'package' => 'decimal:2',
            'stipend' => 'decimal:2',
            'documents' => 'array',
            'confirmed_final' => 'boolean',
            'has_offer_letter' => 'boolean',
        ];
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
