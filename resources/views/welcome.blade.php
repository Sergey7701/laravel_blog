@include('layouts.header', ['title' => 'Главная'])
@if (count($articles))
<div class="row col-12">
    @foreach($articles as $article)
    <div class="blog-post col-12">
        <h2 class="blog-post-title">
            <a href="/post/{{$article->slug}}">
                {{$article->header}}
            </a>
        </h2>
        <p>
            {{$article->description}}
        </p>
         <p class="">
            <small> {{$article->created_at}}</small>
        </p>
    </div>
    @endforeach
</div>
@endif
{{ $articles->links() }}
@include ('layouts.footer')