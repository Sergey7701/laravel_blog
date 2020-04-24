@include('layouts.header', ['title' => 'Главная'])
@include ('layouts.adminHeader')
@include ('layouts.flashMessage')
@if (count($articles))
<div class="row col-9">
    @foreach($articles as $article)
    <div class="blog-post col-9">
        <h2 class="blog-post-title">
            <a href="/admin/posts/{{$article->slug}}">
                {{$article->header}}
            </a>
            @include('layouts.publishStatus')
        </h2>
        
        @include('layouts.tags', ['badgeStyle' => 'badge badge-info'])
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
    @endforeach
</div>
@include('layouts.sidebar')    
@endif
{{ $articles->links() }}
@include ('layouts.footer')