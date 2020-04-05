@include('layouts.header', ['title' => $article->header])
<a class="mb-3 mr-5" href="/">На главную</a>
@if(Auth::check() && Auth::id() === $article->author_id)
    <a class="mb-3" href="/posts/{{$article->slug}}/edit">Редактировать</a>
@endif    
<div class="blog-post col-12">
    <h2 class="blog-post-title">
        {{$article->header}}
    </h2>
    <p>
        {{$article->text}}
    </p>
</div>

@include ('layouts.footer')