<?php

namespace App\Models;

use App\Models\Default\Model;

class Employee extends Model
{
    protected $fillable = [
        'name',
        'position',
        'basic_salary_per_session',
    ];

    public function subjectSessions()
    {
        return $this->hasMany(SubjectSession::class);
    }
}
