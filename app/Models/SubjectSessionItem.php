<?php

namespace App\Models;

use App\Models\Default\Model;

class SubjectSessionItem extends Model
{
    protected $fillable = [
        'session_id',
        'member_id',
        'membership_id',
    ];

    public function subjectSession()
    {
        return $this->belongsTo(SubjectSession::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function membership()
    {
        return $this->belongsTo(Membership::class);
    }
}
