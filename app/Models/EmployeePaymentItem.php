<?php

namespace App\Models;

use App\Models\Default\Model;

class EmployeePaymentItem extends Model
{
    protected $fillable = [
        'employee_payment_id',
        'session_id',
    ];
}
