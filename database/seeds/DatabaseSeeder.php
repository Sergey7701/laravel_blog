<?php

use App\Comment;
use App\Models\Article;
use App\News;
use App\Permission;
use App\Role;
use App\Tag;
use App\User;
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
        $this->call(RoleSeeder::class);
        $this->call(PermissionSeeder::class);
        factory(Tag::class, 20)->create();
        Permission::where('slug', 'manage-users')->first()->roles()
            ->sync([
                Role::where('slug', 'administrator')->first()->id,
        ]);
        Permission::where('slug', 'manage-articles')->first()->roles()
            ->sync([
                Role::where('slug', 'administrator')->first()->id,
                Role::where('slug', 'editor')->first()->id,
        ]);
        $this->call(UserSeeder::class);
        factory(User::class, 2)->create()->each(function($user) {
            $user->articles()->saveMany(factory(Article::class, (int) rand(10, 20))->make());
            $user->news()->saveMany(factory(News::class, (int) rand(10, 20))->make());
        });
        $this->call(EntryTagTableSeeder::class);
        factory(Comment::class, 300)->create();
    }
}
