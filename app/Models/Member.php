<?php

namespace App\Models;

use App\Models\Default\Model;

class Member extends Model
{
    protected $fillable = [
        'member_category_id',
        'code',
        'name',
        'gender',
        'phone',
        'photo',
        'description',
    ];
}
