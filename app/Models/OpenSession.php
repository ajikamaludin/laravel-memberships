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
}
