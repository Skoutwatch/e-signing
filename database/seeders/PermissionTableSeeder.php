<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = Permission::defaultPermissions();

        foreach ($permissions as $perms) {
            Permission::firstOrCreate(['name' => $perms]);
        }

        $roles_array = explode(',', 'User,Notary,Admin,Account');

        foreach ($roles_array as $role) {
            $role = Role::Create(['name' => trim($role)]);

            if ($role->name == 'Admin') {
                $role->syncPermissions(Permission::all());
            }

            if ($role->name == 'User') {
            }

            $this->createUser($role);
        }
    }

    private function createUser($role)
    {
        if ($role->name == 'Notary') {
            $user = User::Create([
                'first_name' => 'Manuel',
                'last_name' => 'Manuel',
                'email' => 'manuel@tonote.com',
                'password' => 'password',
                'role' => $role->name,
            ]);
            $user->assignRole($role->name);
        }

        if ($role->name == 'Admin') {
            $user = User::Create([
                'first_name' => 'Admin',
                'last_name' => 'Admin',
                'email' => 'admin@tonote.com',
                'password' => 'password',
                'role' => $role->name,
            ]);
            $user->assignRole($role->name);
        }

        if ($role->name == 'User') {
            $user = User::Create([
                'first_name' => 'User',
                'last_name' => 'Manuel',
                'email' => 'user@tonote.com',
                'password' => 'password',
                'role' => $role->name,
            ]);
            $user->assignRole($role->name);
        }
    }
}
