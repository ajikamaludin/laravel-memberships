<?php

namespace App\Http\Controllers\Default;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Default\Role;
use App\Models\Default\User;
use App\Models\Member;
use App\Models\Membership;
use Illuminate\Http\Request;


class GeneralController extends Controller
{
    public function index(Request $request)
    {
        return inertia('dashboard', [
            'user_count' => User::count(),
            'role_count' => Role::count(),
            'member_count' => Member::count(),
            'membership_count' => Membership::count(),
            'accounts' => Account::all(),
        ]);
    }

    public function maintance()
    {
        return inertia('maintance');
    }
}
