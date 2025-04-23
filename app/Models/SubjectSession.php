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

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function trainingTime()
    {
        return $this->belongsTo(TrainingTime::class);
    }

    public function items()
    {
        return $this->hasMany(SubjectSessionItem::class, 'session_id');
    }
}
