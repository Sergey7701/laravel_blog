<?php

use Illuminate\Database\Seeder;

class CommentEntryTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        App\Models\Article::all()->map(function ($article) {
//            $j = (int) rand(1, 5);
//            for ($i = 0; $i < $j; ++$i) {
//                $tagArr[] = (App\Tag::orderByRaw("RAND()")->first())->id;
//            }
//            $article->tags()->sync($tagArr);
//        });
//        App\News::all()->map(function ($news) {
//            $j = (int) rand(1, 5);
//            for ($i = 0; $i < $j; ++$i) {
//                $tagArr[] = (App\Tag::orderByRaw("RAND()")->first())->id;
//            }
//            $news->tags()->sync($tagArr);
//        });
        App\Entry::all()->map(function($entry){
        });
    }
}
