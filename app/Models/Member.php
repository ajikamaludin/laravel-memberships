<?php

namespace App\Models;

use App\Models\Default\Model;
use App\Services\MemberService;

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
        'address',
    ];

    protected static function booted(): void
    {
        static::creating(function (Member $model) {
            $model->code = MemberService::generate_code();
        });
    }

    public function category()
    {
        return $this->belongsTo(MemberCategory::class, 'member_category_id');
    }

    public function memberships()
    {
        return $this->hasMany(Membership::class);
    }
}
