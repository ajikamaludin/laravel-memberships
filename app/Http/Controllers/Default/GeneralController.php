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
        $accounts = Account::all()->map(function ($account) {
            return [
                'name' => $account->name,
                'balance_amount' => $account->calculate_balance_amount,
            ];
        });

        return inertia('dashboard', [
            'user_count' => User::count(),
            'role_count' => Role::count(),
            'member_count' => Member::count(),
            'membership_count' => Membership::count(),
            'accounts' => $accounts,
        ]);
    }

    public function maintance()
    {
        return inertia('maintance');
    }
}
