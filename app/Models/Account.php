<?php

namespace App\Models;

use App\Models\Default\Model;

class Account extends Model
{
    protected $fillable = [
        'name',
        'balance_amount',
        'balance_start',
    ];
}
