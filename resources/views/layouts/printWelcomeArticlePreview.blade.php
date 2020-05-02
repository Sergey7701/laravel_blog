<div class="blog-post col-9">
    <h2 class="blog-post-title">
        <a href="/posts/{{$article->slug}}">
            {{$article->header}}
            @include('layouts.publishStatus', ['entry' => $article])
        </a>
    </h2>
   @include('layouts.tags', [
        'entry' => $article,
        'badgeStyle' => 'badge badge-info',
    ])
    @include('layouts.countOfComments', [
        'entry' => $article,
    ])
    <p>
        {{$article->description}}
    </p>
    <p class="">
        <small> {{$article->created_at}}</small>
    </p>
    <p class="">
        <small> Автор: {{ $article->author->name}}</small>
    </p>
</div>