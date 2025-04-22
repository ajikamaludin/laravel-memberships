<?php

namespace App\Models;

use App\Models\Default\Model;

class Bundle extends Model
{
    protected $fillable = [
        'name',
        'session_quote',
        'active_period_days',
        'price',
    ];

    public function items()
    {
        return $this->hasMany(BundleItem::class);
    }
}
