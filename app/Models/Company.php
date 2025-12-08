<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'address',
        'contact_person',
        'contact_email',
        'website',
        'notes',
    ];

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function placements()
    {
        return $this->hasMany(StudentPlacement::class);
    }
}
