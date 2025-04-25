<?php

namespace App\Models;

use App\Models\Default\Model;

class MemberCategory extends Model
{
    protected $fillable = [
        'name',
        'join_fee',
        'description',
    ];
}
