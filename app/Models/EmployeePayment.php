<?php

namespace App\Models;

use App\Models\Default\Model;

class EmployeePayment extends Model
{
    protected $fillable = [
        'employee_id',
        'account_id',
        'payment_date',
        'basic_salary_per_session', // gaji pokok per sesi
        'amount', // total gaji yang dibayarkan dari , (gaji pokok * jumlah sesi) + (jumlah orang tiap sesi * fee per orang tiap sesi)
        'description',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function items()
    {
        return $this->hasMany(EmployeePaymentItem::class);
    }
}
