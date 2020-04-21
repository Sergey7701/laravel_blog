@include ('layouts.header', ['title' => $article->header])
@include ('layouts.flashMessage')
<div class="row col-12">
    @role('administrator')
        @if (Auth::user()->can('manage-articles'))
            <a class="mb-3 mr-5 text-info" href="/admin/posts">На главную для администраторов</a>
        @else 
            <a class="mb-3 mr-5" href="/">На главную</a>
        @endif
    @endcan
    @role('administrator')
        @if (Auth::user()->can('manage-articles'))
            <a class="ml-3 mb-3 text-info" href="/admin/posts/{{$article->slug}}/edit">Управлять статьёй</a>
        @endif
    @else
        @can('update', $article)
        <a class="mb-3" href="/posts/{{$article->slug}}/edit">Редактировать</a>
        @endcan
    @endcan    

</div>
<div class="row col-9">
    <div class="blog-post">
        <h2 class="blog-post-title">
            {{$article->header}}
        </h2>
        @include('layouts.tags', ['badgeStyle' => 'badge badge-info'])
        <p>
            {{$article->text}}
        </p>
    </div>
</div>
@include ('layouts.sidebar')
@include ('layouts.footer')
