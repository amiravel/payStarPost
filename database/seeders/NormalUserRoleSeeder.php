<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class NormalUserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**
         * @var Role $normalUser
         */
        $normalUser = Role::query()->create([
            'title' => 'normal user'
        ]);

        $permission = Permission::query()->whereTitle('read-post')->first();

        $normalUser->permissions()->attach($permission);
    }
}
