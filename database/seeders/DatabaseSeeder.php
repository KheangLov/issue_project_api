<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::firstOrCreate([
            'name' => 'superadmin',
            'guard_name' => 'api',
        ]);
        $admin = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'api',
        ]);
        Role::firstOrCreate([
            'name' => 'user',
            'guard_name' => 'api',
        ]);
        collect([
            'roles',
            'permissions',
            'users',
            'curriculum_vitaes',
        ])->each(function ($v) use ($role) {
            $roleId = $role->id;
            collect(['create', 'read', 'update', 'delete'])
                ->each(function ($a) use ($v, $roleId) {
                    $permission = Permission::firstOrCreate([
                        'name' => "$v $a",
                        'guard_name' => 'api',
                    ]);
                    $permission->roles()->syncWithoutDetaching([$roleId]);
                });
        });
        $user = User::firstOrCreate(
            [
                'email' => 'superadmin@admin.com',
            ],
            [
                'name' => 'superadmin',
                'password' => 'not4youbro',
            ]
        );
        $user->roles()->syncWithoutDetaching([$role->id]);
        $userAdmin = User::firstOrCreate(
            [
                'email' => 'admin@admin.com',
            ],
            [
                'name' => 'admin',
                'password' => 'not4youbro',
            ]
        );
        $userAdmin->roles()->syncWithoutDetaching([$admin->id]);
        $this->call(AddressSeeder::class);
    }
}
