<div class="blog-post col-9">
    <h2 class="blog-post-title">
        <a href="/news/{{$news->slug}}">
            {{$news->header}}
            @include('layouts.publishStatus')
        </a>
    </h2>
    @include ('layouts.newsBadge')
    @php
    @endphp    
    @include('layouts.tags', [
    'entry' => $news,
    'badgeStyle' => 'badge badge-info',
    ])
    <p class="">
        <small> {{$news->created_at}}</small>
    </p>
    <p class="">
        <small> Автор: {{ $news->author->name}}</small>
    </p>
</div>