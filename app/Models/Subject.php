<?php

namespace App\Models;

use App\Models\Default\Model;

class Subject extends Model
{
    protected $fillable = [
        'name',
        'description',
        'employee_fee_per_person',
    ];
}
