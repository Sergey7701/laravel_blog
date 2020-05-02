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
        $editor = Role::where('slug', 'editor')->first();
        $admin  = Role::where('slug', 'administrator')->first();
        User::create([
            'name'     => 'Editor',
            'email'    => 'Editor@example.com',
            'password' => Hash::make('12345678'),
            'role_id'  => $editor->id,
        ]);
        User::create([
            'name'     => 'Admin',
            'email'    => 'admin@example.com',
            'password' => Hash::make('12345678'),
            'role_id'  => $admin->id,
        ]);
        factory(User::class)->create([
            'name'     => 'user1',
            'email'    => 'user1@example.com',
            'password' => Hash::make('12345678'),
        ]);
    }
}
