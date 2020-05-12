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
@php
    $mostUserIds = \App\Models\Article::wherePublish(1)
                    ->pluck('author_id')
                    ->merge(
                        \App\News::wherePublish(1)
                            ->pluck('author_id')
                    )
                   ->countBy();
@endphp
<p class="col-12 mr-1">
    - из них опубликовали хотя бы одну запись: {{ $mostUserIds->count() }}
</p>
<p class="col-12 ">
    Самый пишущий автор: {{ \App\User::whereId($mostUserIds->keys()->first())->first()->name }}
</p>
<p class="col-12 mr-1">
    - у него записей: {{ $mostUserIds->first() }}
</p>
<p class="col-12 ">
    Всего комментариев: {{ \App\Comment::count() }}
</p>
@php
    $entry = \App\Entry::where('id',
        \App\Comment::pluck('entry_id')
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
        \App\User::where('id', 
            \App\Comment::pluck('author_id')
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
           \App\Comment::pluck('author_id')
                ->countBy()
                ->sortDesc()
                ->first()     
    }}
</p>
@php
    if (\App\Version::count()) {
        $article = \App\Models\Article::wherePublish(1)->whereId(
        \App\Version::pluck('article_id')
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
    if (App\VersionNews::count()) {
        $news = \App\News::wherePublish(1)->whereId(
            Version\App\News::pluck('news_id')
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
    Тегов на сайте: {{ \App\Tag::count() }}
</p>  
<p class="col-12 mr-1">
    - из них используются: {{ \App\Tag::tagsCloud()->count() }}
</p>  
@include ('layouts.footer')