<?php

namespace App\Services;

use App\Models\Member;

class MemberService
{
    const PREFIX = 'M';

    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public static function generate_code()
    {
        $fallback = '-';
        $member = Member::orderBy('created_at', 'desc')->first();

        $num = 1;
        if ($member !== null) {
            try {
                $lastnum = explode('-', $member->code);
                $num = is_numeric($lastnum[1]) ? $lastnum[1] + 1 : $fallback;
            } catch (\Exception $e) {
                $num = $fallback;
            }
        }

        $code = self::PREFIX . '-' . formatNumZero($num);

        return $code;
    }
}
