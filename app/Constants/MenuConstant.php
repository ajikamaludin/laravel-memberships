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
                'name' => 'Absen Sesi Kelas',
                'show' => true,
                'icon' => 'HiOutlineClock',
                'route' => route('subject-sessions.index'),
                'active' => 'subject-sessions.*',
                'permission' => 'view-subject-session',
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
                'name' => 'Gaji Karyawan',
                'show' => true,
                'icon' => 'HiDocumentCurrencyDollar',
                'route' => route('employee-payments.index'),
                'active' => 'employee-payments.*',
                'permission' => 'view-employee-payment',
            ],
            [
                'name' => 'Keuangan ( KAS )',
                'show' => true,
                'icon' => 'HiDocumentCurrencyDollar',
                'route' => route('journals.index'),
                'active' => 'journals.*',
                'permission' => 'view-journal',
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
                        'name' => 'Waktu Sesi',
                        'show' => true,
                        'route' => route('training-times.index'),
                        'active' => 'training-times.*',
                        'permission' => 'view-training-time',
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
                'name' => 'Laporan',
                'show' => true,
                'icon' => 'HiClipboardDocumentList',
                'items' => [
                    [
                        'name' => 'Member Datang',
                        'show' => true,
                        'route' => route('reports.member-sessions'),
                        'active' => 'reports.member-sessions',
                        'permission' => 'view-report',
                    ],
                    [
                        'name' => 'Akumulasi Member Datang Per Hari',
                        'show' => true,
                        'route' => route('reports.member_accumulate_per_day'),
                        'active' => 'reports.member_accumulate_per_day',
                        'permission' => 'view-report',
                    ],
                    [
                        'name' => 'Absen Member Datang Per Kelas & Sesi',
                        'show' => true,
                        'route' => route('reports.member_accumulate_session'),
                        'active' => 'reports.member_accumulate_session',
                        'permission' => 'view-report',
                    ],
                    [
                        'name' => 'Akumulasi Total Sesi Kelas Per Coach',
                        'show' => true,
                        'route' => route('reports.employee_accumulate_session'),
                        'active' => 'reports.employee_accumulate_session',
                        'permission' => 'view-report',
                    ],
                    [
                        'name' => 'Akumulasi Total Fee Per Coach',
                        'show' => true,
                        'route' => route('reports.employee_accumulate_fee'),
                        'active' => 'reports.employee_accumulate_fee',
                        'permission' => 'view-report',
                    ],
                    [
                        'name' => 'Fee Coach',
                        'show' => true,
                        'route' => route('reports.employee_fee'),
                        'active' => 'reports.employee_fee',
                        'permission' => 'view-report',
                    ],
                    [
                        'name' => 'Laba/Rugi',
                        'show' => true,
                        'route' => route('reports.balance_sheet'),
                        'active' => 'reports.balance_sheet',
                        'permission' => 'view-report',
                    ],
                ],
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
