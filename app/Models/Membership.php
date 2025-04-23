<?php

namespace App\Models;

use App\Models\Default\Model;

class Membership extends Model
{
    protected $fillable = [
        'transaction_id',
        'member_id',
        'bundle_id',
        'session_quote',
        'session_quote_used',
        'active_period_days',
        'expired_at',
    ];

    public function bundle()
    {
        return $this->belongsTo(Bundle::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class)->withTrashed();
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class)->withTrashed();
    }
}
