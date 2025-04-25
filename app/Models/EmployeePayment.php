<?php

namespace App\Models;

use App\Models\Default\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Carbon;

class EmployeePayment extends Model
{
    protected $fillable = [
        'employee_id',
        'account_id',
        'payment_date',
        'payment_date_end',
        'basic_salary_per_session', // gaji pokok per sesi
        'amount', // total gaji yang dibayarkan dari , (gaji pokok * jumlah sesi) + (jumlah orang tiap sesi * fee per orang tiap sesi)
        'description',
    ];

    protected $appends = ['periode_text'];

    public function account()
    {
        return $this->belongsTo(Account::class)->withTrashed();
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class)->withTrashed();
    }

    public function items()
    {
        return $this->hasMany(EmployeePaymentItem::class);
    }

    public function periodeText(): Attribute
    {
        return Attribute::make(
            get: fn() => Carbon::parse($this->payment_date)->format('d/m/Y') . ' - ' . Carbon::parse($this->payment_date_end)->format('d/m/Y'),
        );
    }
}
