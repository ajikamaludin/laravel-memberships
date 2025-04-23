<?php

namespace App\Models;

use App\Models\Default\Model;

class Transaction extends Model
{
    protected $fillable = [
        'member_id',
        'account_id',
        'transaction_date',
        'amount',
        'discount',
    ];

    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class)->withTrashed();
    }

    public function account()
    {
        return $this->belongsTo(Account::class)->withTrashed();
    }
}
