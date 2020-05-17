@include ('layouts.header', ['title' => 'Статистика сайта'])
<h3>Статистика сайта:</h3>
<p class="col-12">
    Всего опубликованных записей: {{ \App\Entry::wherePublish(1)->count() }}
</p>
<p class="col-12 mr-1">
    - из них статей: {{ \App\Entry::wherePublish(1)->whereEntryableType(\App\Models\Article::class)->count() }}
</p>
<p class="col-12 mr-1">
    - из них новостей: {{ \App\Entry::wherePublish(1)->whereEntryableType(\App\News::class)->count() }}
</p>
<p class="col-12">
    Самый длинный текст у статьи: {{ \App\Models\Article::selectRaw('max(length(`text`)) ')->pluck("max(length(`text`))")->toArray()[0] }}
</p>    
<p class="col-12">
    Самый длинный текст у новости: {{ \App\News::selectRaw(' max(length(`text`)) ')->pluck("max(length(`text`))")->toArray()[0] }} 
</p>
<p class="col-12">
    Самый короткий текст у статьи: {{ \App\Models\Article::selectRaw(' min(length(`text`)) ')->pluck("min(length(`text`))")->toArray()[0] }}
</p>    
<p class="col-12">
    Самый короткий текст у новости: {{ \App\News::selectRaw(' min(length(`text`)) ')->pluck("min(length(`text`))")->toArray()[0] }} 
</p>
<p class="col-12">
    Всего зарегистрированных пользователей: {{ \App\User::count() }}
</p>
<?php
    //Для примера, что использование App\Entry удобнее
    //Считаем число авторов
    $mostUsersCount = App\Entry::wherePublish(1)
                       ->distinct('author_id')
                       ->count();
    //Считаем публикации у самого авторского автора и его имя 
    $mostUserResult = DB::table('entries')->wherePublish(1)
                        ->join('users', 'entries.author_id', '=', 'users.id')
                        ->selectRaw('name, COUNT(*) as count_entries') 
                        ->groupBy('name')
                        ->orderBy('count_entries', 'desc')
                        ->first();
    //То же самое, но через отдельные таблицы
    $mostUserResultWithoutEntry = DB::table(
                                      DB::table(
                                          DB::table('articles') 
                                              ->wherePublish(1)
                                              ->join('users', 'articles.author_id', '=', 'users.id')
                                              ->selectRaw('name, COUNT(*) as count_entries') 
                                              ->groupBy('name')
                                              ->orderBy('count_entries', 'desc')
                                              ->union(
                                                  DB::table('news') 
                                                      ->wherePublish(1)
                                                      ->join('users', 'news.author_id', '=', 'users.id')
                                                      ->selectRaw('name, COUNT(*) as count_entries') 
                                                      ->groupBy('name')
                                                      ->orderBy('count_entries', 'desc')
                                                )
                                      ) 
                                      ->selectRaw('name, SUM(count_entries) as total_entries')
                                      ->groupBy('name')    
                                  );   
?>
<p class="col-12 mr-1">
    - из них опубликовали хотя бы одну запись (Entry): {{ $mostUsersCount }}
</p>
<p class="col-12 mr-1">
    - из них опубликовали хотя бы одну запись: {{ $mostUserResultWithoutEntry->count() }}
</p>
<p class="col-12 ">
    Самый пишущий автор (Entry): {{ $mostUserResult->name }}
</p>
<p class="col-12 ">
    Самый пишущий автор: {{ $mostUserResultWithoutEntry->first()->name }}
</p>
<p class="col-12 mr-1">
    - у него записей (Entry): {{ $mostUserResult->count_entries }}
</p>
<p class="col-12 mr-1">
    - у него записей: {{ $mostUserResultWithoutEntry->first()->total_entries }}
</p>
<p class="col-12 ">
    Всего комментариев: {{ \App\Comment::count() }}
</p>
<?php
    $entry = App\Entry::whereId( 
                \App\Comment::join('entries', 'comments.entry_id', '=', 'entries.id')
                    ->wherePublish(1)
                    ->selectRaw('entry_id, COUNT(*) as count_comments') 
                    ->groupBy('entry_id')
                    ->orderBy('count_comments', 'desc')
                    ->first()
                    ->entry_id
                )
                ->first();
    $prefix = ($entry->entryable_type === 'App\News') ? 'news' : 'posts';

?>
<p class="col-12 ">
    Самая комментируемая запись: <a href="{{ '/'.$prefix.'/'.$entry->entryable->slug }}">{{ $entry->entryable->header }}</a>
</p>
<p class="col-12 mr-1">
    - у неё комментариев: {{ $entry->comments->count() }}</a>
</p>
<?php
    $mostCommentator = DB::table('comments')
                          ->join('entries', 'comments.entry_id', '=', 'entries.id')
                          ->wherePublish(1)
                          ->join('users', 'comments.author_id', '=', 'users.id')
                          ->selectRaw('name, comments.author_id, COUNT(*) as count_comments') 
                          ->groupBy('comments.author_id')
                          ->orderBy('count_comments', 'desc')
                          ->first();
?>
<p class="col-12 ">
    Самый активный комментатор: {{ $mostCommentator->name }}
</p>
<p class="col-12 mr-1">
    - у него комментариев: {{ $mostCommentator->count_comments }}
</p>
<?php
$article = \App\Version::join('articles', 'versions.article_id', '=', 'articles.id')
                    ->where('articles.publish', 1)
                    ->selectRaw('versions.article_id, articles.slug, articles.header, COUNT(*) as version_count')
                    ->groupBy('versions.article_id')
                    ->first();
    if ($article) {     
?>
        <p class="col-12">
            Самая часто меняемая статья: <a href="/posts/{{ $article->slug }}">{{ $article->header}}</a>
        </p>
<?php
    } else {
?>
        <p class="col-12">
            Самая часто меняемая статья: пока статьи не редактировались
        </p>
<?php
    }
?>
<?php
    if (App\VersionNews::count() &&
        $news = \App\News::wherePublish(1)->whereId(
            Version\App\News::pluck('news_id')
                ->countBy()
                ->sortDesc()           
                ->keys()
        )->first()) {
?>
        <p class="col-12">
            Самая часто меняемая новость: <a href="/news/{{ $news->slug }}">{{ $news->header}}</a>
        </p>
<?php
    } else {
?>
        <p class="col-12">
            Самая часто меняемая новость: пока новости не редактировались
        </p>
<?php
    }
?>
<p class="col-12">
    Тегов на сайте: {{ \App\Tag::count() }}
</p>  
<p class="col-12 mr-1">
    - из них используются в опубликованных записях и черновиках: {{ 
        \App\Tag::whereHas('articles')->get()
               ->merge(
                    \App\Tag::whereHas('news')->get()
                )->count() }}
</p>  
<p class="col-12 mr-1">
    - из них используются только в опубликованных записях: {{ 
   \App\Tag::whereHas('articles', function ($query) {
                   return $query->wherePublish(1);
                })->get()
               ->merge(
                    \App\Tag::whereHas('news', function ($query) {
                       return $query->wherePublish(1);
                    })->get()
                )->count()
    }}
</p>  
@include ('layouts.footer')