<?php

namespace App\Models;

use App\Models\Default\Model;

class EmployeePaymentItem extends Model
{
    protected $fillable = [
        'employee_payment_id',
        'subject_session_id',
        'subject_id',
        'person_amount', // jumlah orang 
        'employee_fee_per_person', // fee per orang
    ];

    public function subjectSession()
    {
        return $this->belongsTo(SubjectSession::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
