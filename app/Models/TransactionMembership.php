<?php

namespace App\Models;

use App\Models\Default\Model;

class TransactionMembership extends Model
{
    protected $fillable = [
        'member_id',
        'account_id',
        'package_id',
        'transaction_date',
        'amount',
        'discount',
        'expired_at',
        'session_quote',
        'session_quote_used',
        'active_period_days',
    ];
}
