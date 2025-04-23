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
        return $this->belongsTo(Subject::class)->withTrashed();
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class)->withTrashed();
    }

    public function trainingTime()
    {
        return $this->belongsTo(TrainingTime::class)->withTrashed();
    }

    public function items()
    {
        return $this->hasMany(SubjectSessionItem::class, 'session_id');
    }
}
