<?php

use App\Models\Article;
use App\News;
use App\Tag;
use Illuminate\Database\Seeder;

class EntryTagTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Article::all()->map(function ($article) {
            $j = (int) rand(1, 5);
            for ($i = 0; $i < $j; ++$i) {
                $tagArr[] = (Tag::inRandomOrder()->first())->id;
            }
            $article->tags()->sync($tagArr);
        });
        News::all()->map(function ($news) {
            $j = (int) rand(1, 5);
            for ($i = 0; $i < $j; ++$i) {
                $tagArr[] = (Tag::inRandomOrder()->first())->id;
            }
            $news->tags()->sync($tagArr);
        });
    }
}
