<?php

namespace App\Models;

use App\Models\Default\Model;

class PackageItem extends Model
{
    protected $fillable = [
        'packge_id',
        'subject_id',
    ];
}
