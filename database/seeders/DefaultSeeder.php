<?php

namespace Database\Seeders;

use App\Constants\PermissionConstant;
use App\Constants\SettingConstant;
use App\Models\Account;
use App\Models\Default\Permission;
use App\Models\Default\Role;
use App\Models\Default\Setting;
use App\Models\Default\User;
use App\Models\Member;
use App\Models\MemberCategory;
use App\Models\Subject;
use App\Models\TrainingTime;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DefaultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (SettingConstant::all() as $setting) {
            Setting::insert(['id' => Str::ulid(), ...$setting]);
        }

        foreach (PermissionConstant::all() as $permission) {
            Permission::insert(['id' => Str::ulid(), ...$permission]);
        }

        $role = Role::create(['name' => 'admin']);

        $permissions = Permission::all();
        foreach ($permissions as $permission) {
            $role->rolePermissions()->create(['permission_id' => $permission->id]);
        }

        User::create([
            'name' => 'Super Administrator',
            'email' => 'root@admin.com',
            'password' => bcrypt('password'),
        ]);

        User::create([
            'name' => 'Administator',
            'email' => 'admin@admin.com',
            'password' => bcrypt('password'),
            'role_id' => $role->id,
        ]);

        foreach (['Baru', 'Lama'] as $cat) {
            MemberCategory::create(['name' => $cat]);
        }

        foreach (['QRIS', 'Cash', 'Transfer'] as $method) {
            Account::create([
                'name' =>  $method
            ]);
        }

        foreach (['15:00', '17:00'] as $t) {
            TrainingTime::create(['name' => $t]);
        }

        $this->subjects();

        Member::create([
            'member_category_id' => MemberCategory::inRandomOrder()->first()->id,
            'name' => 'Budiman Nagasawa',
            'phone' => '0812345678910',
        ]);

        Member::create([
            'member_category_id' => MemberCategory::inRandomOrder()->first()->id,
            'name' => 'Yatmo Ilku Subandino',
            'phone' => '0812345678911',
        ]);
    }

    private function subjects()
    {
        $subjects = [
            [
                'name' => 'MUAYTHAI - REGULER',
                'employee_fee_per_person' => '15000'
            ],
            [
                'name' => 'BOXING - REGULER',
                'employee_fee_per_person' => '15000'
            ],
            [
                'name' => 'MUAYTHAI - PRIVATE',
                'employee_fee_per_person' => '75000'
            ],
            [
                'name' => 'GYM',
                'employee_fee_per_person' => '0'
            ]
        ];

        foreach ($subjects as $s) {
            Subject::create($s);
        }
    }
}
