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
