<?php

use Illuminate\Database\Seeder;
use App\Role;

class RoleSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            'name' => 'Administrator',
            'slug' => 'administrator',
        ]);
         Role::create([
            'name' => 'Editor',
            'slug' => 'editor',
        ]);
    }
}
