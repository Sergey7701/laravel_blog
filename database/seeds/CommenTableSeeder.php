<?php

use App\Comment;
use App\Entry;
use App\User;
use Illuminate\Database\Seeder;

class CommentTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       if (User::count() && Entry::count()){
           factory(Comment::class, 300)->create();
       }   
    }
}
