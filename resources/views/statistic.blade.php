<?php

use App\Models\Article;
use App\News;
use App\Tag;

?>
@include ('layouts.header', ['title' => 'Статистика сайта'])
<h3>Статистика сайта:</h3>
<p class="col-12">
    Всего опубликованных записей: {{ DB::table('entries')->wherePublish(1)->count() }}
</p>    
<p class="col-12 mr-1">
    - из них статей: {{ DB::table('entries')->wherePublish(1)->whereEntryableType(Article::class)->count() }}
</p>  
<p class="col-12 mr-1">
    - из них новостей: {{ DB::table('entries')->wherePublish(1)->whereEntryableType(News::class)->count() }}
</p>
<p class="col-12">
    Самый длинный текст у статьи: {{ DB::table('articles')->selectRaw(' max(length(`text`)) ')->pluck("max(length(`text`))")->toArray()[0] }}
</p>    
<p class="col-12">
    Самый длинный текст у новости: {{ DB::table('news')->selectRaw(' max(length(`text`)) ')->pluck("max(length(`text`))")->toArray()[0] }} 
</p>
<p class="col-12">
    Самый короткий текст у статьи: {{ DB::table('articles')->selectRaw(' min(length(`text`)) ')->pluck("min(length(`text`))")->toArray()[0] }}
</p>    
<p class="col-12">
    Самый короткий текст у новости: {{ DB::table('news')->selectRaw(' min(length(`text`)) ')->pluck("min(length(`text`))")->toArray()[0] }} 
</p>
<p class="col-12">
    Всего зарегистрированных пользователей: {{ DB::table('users')->count() }}
</p>
<p class="col-12 mr-1">
    - из них опубликовали хотя бы одну запись: {{ DB::table('users')->whereIn('id', 
            array_merge(
                DB::table('news')->wherePublish(1)->pluck('author_id')->toArray(), 
                DB::table('articles')->wherePublish(1)->pluck('author_id')->toArray()
            )
        )->count() }}
</p>
<p class="col-12 ">
    Самый пишущий автор: {{ DB::table('users')->where('id', 
                            DB::table('articles')
                                ->wherePublish(1)
                                ->pluck('author_id')
                                ->merge(
                            DB::table('news')
                                ->wherePublish(1)
                                ->pluck('author_id'))
                                ->countBy()
                                ->sortDesc()
                                ->keys()
                                ->first()
                )->first()->name }}
</p>
<p class="col-12 mr-1">
    - у него записей: {{
                            DB::table('articles')
                                ->wherePublish(1)
                                ->pluck('author_id')
                                ->merge(
                            DB::table('news')
                                ->wherePublish(1)
                                ->pluck('author_id'))
                                ->countBy()
                                ->sortDesc()
                            ->first()               
                    }}
</p>
<p class="col-12 ">
    Всего комментариев: {{ DB::table('comments')->count() }}
</p>
@php
    $entry = App\Entry::where('id',
                DB::table('comments')
                ->pluck('entry_id')
                ->countBy()
                ->sortDesc()           
                ->keys()
                ->first()
                )
                    ->first();
    $prefix = ($entry->entryable_type === 'App\News') ? 'news' : 'posts';
@endphp
<p class="col-12 ">
    Самая комментируемая запись: <a href="{{ '/'.$prefix.'/'.$entry->entryable->slug }}">{{ $entry->entryable->header }}</a>
</p>
<p class="col-12 mr-1">
    - у неё комментариев: {{ $entry->comments->count() }}</a>
</p>
<p class="col-12 ">
    Самый активный комментатор: {{
        DB::table('users')->where('id', 
            DB::table('comments')
                ->pluck('author_id')
                ->countBy()
                ->sortDesc()
                ->keys()
                ->first()
                )
            ->first()
            ->name
    }}
</p>
<p class="col-12 mr-1">
    - у него комментариев: {{
           DB::table('comments')
                ->pluck('author_id')
                ->countBy()
                ->sortDesc()
                ->first()     
    }}
</p>
@php
    if (App\Version::all()->count()) {
        $article = Article::wherePublish(1)->whereId(
            DB::table('versions')
            ->pluck('article_id')
            ->countBy()
            ->sortDesc()           
            ->keys()
       )->first()
@endphp
<p class="col-12">
    Самая часто меняемая статья: <a href="/post/{{ $article->slug }}">{{ $article->header}}</a>
</p>
@php
    } else {
@endphp      
    <p class="col-12">
        Самая часто меняемая статья: пока статьи не редактировались
    </p>
@php
    }
@endphp  
@php
    if (App\VersionNews::all()->count()) {
        $news = News::wherePublish(1)->whereId(
            DB::table('version_news')
            ->pluck('news_id')
            ->countBy()
            ->sortDesc()           
            ->keys()
       )->first()
@endphp
<p class="col-12">
    Самая часто меняемая новость: <a href="/news/{{ $news->slug }}">{{ $news->header}}</a>
</p>
@php
    } else {
@endphp      
    <p class="col-12">
        Самая часто меняемая новость: пока новости не редактировались
    </p>
@php
    }
@endphp  
<p class="col-12">
    Тегов на сайте: {{ DB::table('tags')->count() }}
</p>  
<p class="col-12 mr-1">
    - из них используются: {{ Tag::tagsCloud()->count() }}
</p>  
@include ('layouts.footer')