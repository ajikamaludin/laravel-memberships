<?php

namespace App\Models;

use App\Models\Default\Model;

class OpenSession extends Model
{
    protected $fillable = [
        'subject_id',
        'member_id',
        'membership_id',
        'session_date',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function membership()
    {
        return $this->belongsTo(Membership::class);
    }
}
