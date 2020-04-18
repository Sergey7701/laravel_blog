<?php

use Illuminate\Database\Seeder;

class ArticleTagTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\Models\Article::all()->map(function ($article) {
            $j = (int) rand(1, 5);
            for ($i = 0; $i < $j; ++$i) {
                $tagArr[] = (App\Tag::orderByRaw("RAND()")->first())->id;
            }
            $article->tags()->sync($tagArr);
        });
    }
}
