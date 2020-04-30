@include ('layouts.header', ['title' => $article->header])
@include ('layouts.flashMessage')
<div class="row col-12">
    @permission('manage-articles')
        <a class="mb-3 mr-5 text-info" href="/admin/posts">На главную для администраторов</a>
    @else 
        <a class="mb-3 mr-5" href="/">На главную</a>
    @endpermission
    @permission('manage-articles')
        <a class="ml-3 mb-3 text-info" href="/admin/posts/{{$article->slug}}/edit">Управлять статьёй</a>
    @else
        @if (auth()->id() === $article->author_id)
            <a class="mb-3" href="/posts/{{$article->slug}}/edit">Редактировать</a>
        @endif    
    @endpermission
</div>
<div class="row col-9">
    <div class="blog-post">
        <h2 class="blog-post-title">
            {{$article->header}}
            @include('layouts.publishStatus', ['entry' => $article])
        </h2>
        @include('layouts.tags', [
            'entry' => $article,
            'badgeStyle' => 'badge badge-info',
        ])
        @include('layouts.countOfComments', [
            'entry' => $article,
        ])
        <p>
            {{$article->text}}
        </p>
        @include('layouts.alertErrors')
        @include('layouts.comments', [
            'entry' => $article,
        ])
    </div>
</div>
@include ('layouts.sidebar')
@include ('layouts.footer')
