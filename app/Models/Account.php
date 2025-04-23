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

    public function items()
    {
        return $this->hasMany(Journal::class, 'account_id');
    }
}
