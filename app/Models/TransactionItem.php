<?php

namespace App\Models;

use App\Models\Default\Model;

class TransactionItem extends Model
{
    protected $fillable = [
        'transaction_id',
        'package_id',
        'name',
        'price',
    ];
}
