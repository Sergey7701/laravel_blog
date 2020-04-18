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
        $admin        = new Role();
        $admin->name  = 'Administrator';
        $admin->slug  = 'administrator';
        $admin->save();
        $editor       = new Role();
        $editor->name = 'Editor';
        $editor->slug = 'editor';
        $editor->save();
    }
}
