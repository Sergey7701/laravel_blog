<?php

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
        factory(App\Tag::class, 20)->create();
        factory(\App\User::class)->create([
            'name'     => 'user1',
            'email'    => 'user1@example.com',
            'password' => Hash::make('12345678'),
        ]);
        $this->call(RoleSeeder::class);
        $this->call(PermissionSeeder::class);     
        App\Permission::where('slug', 'manage-users')->first()->roles()
            ->sync([
                App\Role::where('slug', 'administrator')->first()->id,
        ]);
        App\Permission::where('slug', 'manage-articles')->first()->roles()
            ->sync([
                App\Role::where('slug', 'administrator')->first()->id,
                App\Role::where('slug', 'editor')->first()->id,
        ]);
        $this->call(UserSeeder::class);
        factory(App\User::class, 2)->create()->each(function($user) {
            $user->articles()->saveMany(factory(App\Models\Article::class, (int) rand(10, 20))->make());
        });
        $this->call(ArticleTagTableSeeder::class);
    }
}
