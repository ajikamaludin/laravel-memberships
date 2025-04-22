<?php

namespace App\Models;

use App\Models\Default\Model;

class BundleItem extends Model
{
    protected $fillable = [
        'bundle_id',
        'subject_id',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
