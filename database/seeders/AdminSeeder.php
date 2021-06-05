<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = Role::query()->create([
            'title' => 'admin'
        ]);

        $admin->permissions()->attach(Permission::all());

        $adminUser = User::query()->create([
            'role_id' => $admin->id,
            'name' => 'admin user',
            'email' => 'admin@user.com',
            'password' => bcrypt(12345),
        ]);
    }
}
