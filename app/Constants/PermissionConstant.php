<?php

namespace App\Constants;

use Illuminate\Support\Facades\Route;

class PermissionConstant
{
    const LIST = [
        ['label' => 'View Dashboard', 'name' => 'view-dashboard'],

        ['label' => 'Create User', 'name' => 'create-user'],
        ['label' => 'Update User', 'name' => 'update-user'],
        ['label' => 'View User', 'name' => 'view-user'],
        ['label' => 'Delete User', 'name' => 'delete-user'],

        ['label' => 'Create Role', 'name' => 'create-role'],
        ['label' => 'Update Role', 'name' => 'update-role'],
        ['label' => 'View Role', 'name' => 'view-role'],
        ['label' => 'Delete Role', 'name' => 'delete-role'],

        ['label' => 'View Setting', 'name' => 'view-setting'],

        // #Add New Permission Below!
        ['label' => 'Delete Gaji Karyawan', 'name' => 'delete-employee-payment'],
        ['label' => 'Update Gaji Karyawan', 'name' => 'update-employee-payment'],
        ['label' => 'Create Gaji Karyawan', 'name' => 'create-employee-payment'],
        ['label' => 'View Gaji Karyawan', 'name' => 'view-employee-payment'],

        ['label' => 'Delete Keuangan', 'name' => 'delete-journal'],
        ['label' => 'Update Keuangan', 'name' => 'update-journal'],
        ['label' => 'Create Keuangan', 'name' => 'create-journal'],
        ['label' => 'View Keuangan', 'name' => 'view-journal'],

        ['label' => 'Delete Waktu Sesi', 'name' => 'delete-training-time'],
        ['label' => 'Update Waktu Sesi', 'name' => 'update-training-time'],
        ['label' => 'Create Waktu Sesi', 'name' => 'create-training-time'],
        ['label' => 'View Waktu Sesi', 'name' => 'view-training-time'],

        ['label' => 'Delete Sesi Kelas', 'name' => 'delete-subject-session'],
        ['label' => 'Update Sesi Kelas', 'name' => 'update-subject-session'],
        ['label' => 'Create Sesi Kelas', 'name' => 'create-subject-session'],
        ['label' => 'View Sesi Kelas', 'name' => 'view-subject-session'],

        ['label' => 'Delete Absen Gym', 'name' => 'delete-open-session'],
        ['label' => 'Create Absen Gym', 'name' => 'create-open-session'],
        ['label' => 'View Absen Gym', 'name' => 'view-open-session'],

        ['label' => 'View Membership', 'name' => 'view-membership'],

        ['label' => 'Delete Transaksi', 'name' => 'delete-transaction'],
        ['label' => 'Update Transaksi', 'name' => 'update-transaction'],
        ['label' => 'Create Transaksi', 'name' => 'create-transaction'],
        ['label' => 'View Transaksi', 'name' => 'view-transaction'],

        ['label' => 'Delete Kategori Member', 'name' => 'delete-member-category'],
        ['label' => 'Update Kategori Member', 'name' => 'update-member-category'],
        ['label' => 'Create Kategori Member', 'name' => 'create-member-category'],
        ['label' => 'View Kategori Member', 'name' => 'view-member-category'],

        ['label' => 'Delete Member', 'name' => 'delete-member'],
        ['label' => 'Update Member', 'name' => 'update-member'],
        ['label' => 'Create Member', 'name' => 'create-member'],
        ['label' => 'View Member', 'name' => 'view-member'],

        ['label' => 'Delete Karyawan', 'name' => 'delete-employee'],
        ['label' => 'Update Karyawan', 'name' => 'update-employee'],
        ['label' => 'Create Karyawan', 'name' => 'create-employee'],
        ['label' => 'View Karyawan', 'name' => 'view-employee'],

        ['label' => 'Delete Akun', 'name' => 'delete-account'],
        ['label' => 'Update Akun', 'name' => 'update-account'],
        ['label' => 'Create Akun', 'name' => 'create-account'],
        ['label' => 'View Akun', 'name' => 'view-account'],

        ['label' => 'Delete Paket', 'name' => 'delete-bundle'],
        ['label' => 'Update Paket', 'name' => 'update-bundle'],
        ['label' => 'Create Paket', 'name' => 'create-bundle'],
        ['label' => 'View Paket', 'name' => 'view-bundle'],

        ['label' => 'Delete Kelas', 'name' => 'delete-subject'],
        ['label' => 'Update Kelas', 'name' => 'update-subject'],
        ['label' => 'Create Kelas', 'name' => 'create-subject'],
        ['label' => 'View Kelas', 'name' => 'view-subject'],


    ];

    public static function all()
    {
        return array_merge(self::LIST,  self::modules());
    }

    private static function modules()
    {
        $permissions = [];

        if (Route::has('shortlink.link.index')) {
            $permissions[] = ['label' => 'View Shortlink', 'name' => 'view-shortlink'];
        }

        if (Route::has('custom-form.forms.index')) {
            $permissions = array_merge($permissions, [
                ['label' => 'Create Custom Form', 'name' => 'create-custom-form'],
                ['label' => 'Update Custom Form', 'name' => 'update-custom-form'],
                ['label' => 'View Custom Form', 'name' => 'view-custom-form'],
                ['label' => 'Delete Custom Form', 'name' => 'delete-custom-form'],

                ['label' => 'Create Custom Form Record', 'name' => 'create-custom-form-record'],
                ['label' => 'Update Custom Form Record', 'name' => 'update-custom-form-record'],
                ['label' => 'View Custom Form Record', 'name' => 'view-custom-form-record'],
                ['label' => 'Delete Custom Form Record', 'name' => 'delete-custom-form-record'],
            ]);
        }

        return $permissions;
    }
}
