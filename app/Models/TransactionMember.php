<?php

namespace App\Models;

use App\Models\Default\Model;

class TransactionMember extends Model
{
    protected $fillable = [
        'member_id',
        'account_id',
        'transaction_date',
        'amount',
        'discount',
    ];
}
