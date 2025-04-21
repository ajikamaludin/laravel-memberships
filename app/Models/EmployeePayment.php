<?php

namespace App\Models;

use App\Models\Default\Model;

class EmployeePayment extends Model
{
    protected $fillable = [
        'employee_id',
        'payment_date',
        'basic_salary_per_session',
    ];
}
