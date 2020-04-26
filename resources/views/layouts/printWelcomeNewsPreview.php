<div class="blog-post col-9">
    <h2 class="blog-post-title">
        <a href="/posts/{{$news->slug}}">
            {{$news->header}}
            @include('layouts.publishStatus', ['entry' => $news])
        </a>
    </h2>
  
    @include('layouts.tags', ['badgeStyle' => 'badge badge-info'])
    <p class="">
        <small> {{$news->created_at}}</small>
    </p>
    <p class="">
        <small> Автор: {{ $news->author->name}}</small>
    </p>
</div>