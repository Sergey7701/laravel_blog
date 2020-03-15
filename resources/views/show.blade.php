@include('layouts.header', ['title' => $article->header])
<a class="mb-3" href="/">На главную</a>
<div class="blog-post col-12">
    <h2 class="blog-post-title">
        {{$article->header}}
    </h2>
    <p>
        {{$article->description}}
    </p>
</div>
@include ('layouts.footer')