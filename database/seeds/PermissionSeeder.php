<?php

use Illuminate\Database\Seeder;
use App\Permission;

class PermissionSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create([
            'name' => 'Manage users',
            'slug' => 'manage-users',
        ]);
        Permission::create([
            'name' => 'Manage articles',
            'slug' => 'manage-articles',
        ]);
    }
}
