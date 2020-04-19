<?php

use App\Role;
use App\User;
use App\Permission;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $editor          = Role::where('slug', 'editor')->first();
        $admin           = Role::where('slug', 'administrator')->first();
        $createArticles  = Permission::where('slug', 'create-articles')->first();
        $manageUsers     = Permission::where('slug', 'manage-users')->first();
        $user1           = new User();
        $user1->name     = 'Editor';
        $user1->email    = 'Editor@example.com';
        $user1->password = Hash::make('12345678');
        $user1->save();
        $user1->roles()->attach($editor);
        $user1->permissions()->attach($createArticles);
        $user2           = new User();
        $user2->name     = 'Admin';
        $user2->email    = 'admin@example.com';
        $user2->password = Hash::make('12345678');
        $user2->save();
        $user2->roles()->attach($admin);
        $user2->permissions()->attach($manageUsers);
        
    }
}
