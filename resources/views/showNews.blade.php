@include ('layouts.header', ['title' => $news->header])
@include ('layouts.flashMessage')
<div class="row col-12">
    @permission('manage-articles')
            <a class="mb-3 mr-5 text-info" href="/admin/posts">На главную для администраторов</a>
    @else 
            <a class="mb-3 mr-5" href="/">На главную</a>
    @endpermission
    @permission('manage-articles')
        <a class="ml-3 mb-3 text-info" href="/admin/news/{{$news->slug}}/edit">Управлять новостью</a>   
    @else
        @if (auth()->id() === $news->author_id)
            <a class="mb-3" href="/news/{{$news->slug}}/edit">Редактировать</a>
        @endif    
    @endpermission
</div>
<div class="row col-9">
    <div class="blog-post">
        <h2 class="blog-post-title">
            {{ $news->header }}
            @include('layouts.publishStatus', ['entry' => $news])
        </h2>
        @include ('layouts.newsBadge')
        <p>
            {{ $news->created_at->diffForHumans() }}
        </p>
        @include('layouts.tags', [
            'entry' => $news,
            'badgeStyle' => 'badge badge-info',
        ])
        @include('layouts.countOfComments', [
            'entry' => $news,
        ])
        <p>
            {{ $news->text }}
        </p>
        @include('layouts.alertErrors')
        @include('layouts.comments', [
            'entry' => $news,
        ])
    </div>
</div>
@include ('layouts.sidebar')
@include ('layouts.footer')
