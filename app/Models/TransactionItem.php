<?php

namespace App\Models;

use App\Models\Default\Model;

class TransactionItem extends Model
{
    protected $fillable = [
        'transaction_id',
        'bundle_id',
        'name',
        'price',
    ];

    public function bundle()
    {
        return $this->belongsTo(Bundle::class)->withTrashed();
    }
}
