<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::query()->insert([
            ['title' => 'create-post'],
            ['title' => 'read-post'],
            ['title' => 'update-post'],
            ['title' => 'delete-post'],
        ]);
    }
}
