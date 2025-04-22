<?php

namespace App\Constants;

use Illuminate\Support\Facades\Route;

class MenuConstant
{
    public static function all()
    {
        $menu = [
            [
                'name' => 'Dashboard',
                'show' => true,
                'icon' => 'HiChartPie',
                'route' => route('dashboard'),
                'active' => 'dashboard',
                'permission' => 'view-dashboard',
            ],

            [
                'name' => 'Transaksi',
                'show' => true,
                'icon' => 'HiCreditCard',
                'route' => route('transactions.index'),
                'active' => 'transactions.*',
                'permission' => 'view-transaction',
            ],
            [
                'name' => 'Membership',
                'show' => true,
                'icon' => 'HiUser',
                'route' => route('memberships.index'),
                'active' => 'memberships.*',
                'permission' => 'view-membership',
            ],

            [
                'name' => 'Absen Gym',
                'show' => true,
                'icon' => 'HiClipboardDocumentList',
                'route' => route('open-sessions.index'),
                'active' => 'open-sessions.*',
                'permission' => 'view-open-session',
            ],
            [
                'name' => 'Master',
                'show' => true,
                'icon' => 'HiClipboardDocumentList',
                'items' => [
                    [
                        'name' => 'Kelas',
                        'show' => true,
                        'route' => route('subjects.index'),
                        'active' => 'subjects.*',
                        'permission' => 'view-subject',
                    ],
                    [
                        'name' => 'Paket',
                        'show' => true,
                        'route' => route('bundles.index'),
                        'active' => 'bundles.*',
                        'permission' => 'view-bundle',
                    ],
                    [
                        'name' => 'Akun',
                        'show' => true,
                        'route' => route('accounts.index'),
                        'active' => 'accounts.*',
                        'permission' => 'view-account',
                    ],
                    [
                        'name' => 'Karyawan',
                        'show' => true,
                        'route' => route('employees.index'),
                        'active' => 'employees.*',
                        'permission' => 'view-employee',
                    ],
                    [
                        'name' => 'Kategori Member',
                        'show' => true,
                        'route' => route('member-categories.index'),
                        'active' => 'member-categories.*',
                        'permission' => 'view-member-category',
                    ],
                    [
                        'name' => 'Member',
                        'show' => true,
                        'route' => route('members.index'),
                        'active' => 'members.*',
                        'permission' => 'view-member',
                    ],
                ]
            ],
            [
                'name' => 'Setting',
                'show' => true,
                'icon' => 'HiCog',
                'items' => [
                    [
                        'name' => 'Umum',
                        'show' => true,
                        'route' => route('setting.index'),
                        'active' => 'setting.index',
                        'permission' => 'view-setting',
                    ],
                    [
                        'name' => 'Roles',
                        'show' => true,
                        'route' => route('roles.index'),
                        'active' => 'roles.*',
                        'permission' => 'view-role',
                    ],
                    [
                        'name' => 'Users',
                        'show' => true,
                        'route' => route('user.index'),
                        'active' => 'user.index',
                        'permission' => 'view-user',
                    ],
                ]
            ],

            // # Add Generated Menu Here!

        ];

        return $menu;
    }

    public static function handle()
    {
        return self::all();
    }
}
