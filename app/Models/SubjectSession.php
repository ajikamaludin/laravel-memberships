<?php

namespace App\Models;

use App\Models\Default\Model;

class SubjectSession extends Model
{
    protected $fillable = [
        'subject_id',
        'employee_id',
        'training_time_id',
        'session_date',
    ];
}
