<?php

namespace App\Models;

use App\Models\Default\Model;

class Package extends Model
{
    protected $fillable = [
        'name',
        'session_quote',
        'active_period_days',
        'price',
    ];
}
