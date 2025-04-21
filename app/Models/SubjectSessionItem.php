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
}
