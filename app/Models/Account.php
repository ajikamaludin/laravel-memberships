<?php

namespace App\Models;

use App\Models\Default\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

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

    public function calculateBalanceAmount(): Attribute
    {
        return Attribute::make(get: function () {
            $income = $this->items()->where('type', Journal::TYPE_IN)->where('deleted_at', null)->sum('amount');
            $outcome = $this->items()->where('type', Journal::TYPE_OUT)->where('deleted_at', null)->sum('amount');

            return $income - $outcome;
        });
    }
}
