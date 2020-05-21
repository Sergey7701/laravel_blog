@include('layouts.header', ['title' => 'История изменений статьи'])
@include ('layouts.adminHeader')
@include ('layouts.flashMessage')
<h2>
    История изменения статьи 
    @if (!empty($versions[0]))
        <span style="color: lightgrey"> {{ $versions[0]->article->slug }}</span>
    @endif    
</h2>
@if (!empty($versions[0]))   
    <h4 class="col-12">
        <a href="/posts/{{$versions[0]->article->slug}}"> К статье </a>
    </h4>
@endif    
@if (count($versions))
<div class="row col-9">
    @foreach($versions as $version)
    <div class="blog-post col-9 " style="border-bottom: 2px solid black">
        <p> <span class="text-white bg-info">Редактировалось:</span>
            {{ $version->editor() }}, {{ $version->created_at->diffForHumans() }}
        </p>
        @if ($version->header !== $version->old_header)   
            <p class="blog-post-text text-info">Заголовок:</p>
            <p class="blog-post-text text-primary">
                <span class="bg-info text-white mr-1"> Стало: </span> 
                {{$version->header}}
            </p>
            <p class="blog-post-text text-secondary">
                <span class="bg-secondary text-white mr-1"> Было:&nbsp; </span> 
                {{$version->old_header}}
            </p>
        @else
            <p class="blog-post-text text-secondary">Заголовок не менялся</p>
        @endif
        @if ((string)$version->tags !== (string) $version->old_tags)   
            <p class="blog-post-text text-info">Теги:</p>
            <p class="blog-post-text text-primary">
                <span class="bg-info text-white mr-1"> Стало: </span> 
                {{$version->tags}}
            </p>
            <p class="blog-post-text text-secondary">
                <span class="bg-secondary text-white mr-1"> Было:&nbsp; </span> 
                {{$version->old_tags}}
            </p>
        @else
            <p class="blog-post-text text-secondary">Теги не менялись</p>
        @endif
        @if ($version->description !== $version->old_description)   
            <p class="blog-post-text text-info">Описание:</p>
            <p class="blog-post-text text-primary">
                <span class="bg-info text-white mr-1"> Стало: </span> 
                {{$version->description}}
            </p>
            <p class="blog-post-text text-secondary">
                <span class="bg-secondary text-white mr-1"> Было:&nbsp; </span> 
                {{$version->old_description}}
            </p>
        @else
            <p class="blog-post-text text-secondary">Описание не менялось</p>
        @endif
        @if ($version->text !== $version->old_text)   
            <p class="blog-post-text text-info">Текст:</p>
            <p class="blog-post-text text-primary">
                <span class="bg-info text-white mr-1"> Стало: </span> 
                {{$version->text}}
            </p>
            <p class="blog-post-text text-secondary">
                <span class="bg-secondary text-white mr-1"> Было:&nbsp; </span> 
                {{$version->old_text}}
            </p>
        @else
            <p class="blog-post-text text-secondary">Текст не менялся</p>
        @endif
        @if ($version->publish !== $version->old_publish)   
            <p class="blog-post-text text-info">Опубликовано:</p>
            <p class="blog-post-text text-primary">
                <span class="bg-info text-white mr-1"> Стало: </span> 
                {{ $version->publish ? 'Да' : 'Нет' }}
            </p>
            <p class="blog-post-text text-secondary">
                <span class="bg-secondary text-white mr-1"> Было:&nbsp; </span> 
                {{ $version->old_publish ? 'Да' : 'Нет' }}
            </p>
        @else
            <p class="blog-post-text text-secondary">Статус публикации не менялся</p>
        @endif
        <p> <span class="text-white bg-secondary">Создано:</span>
            {{ $version->article->author->name}}, {{ $version->article->created_at->diffForHumans() }}
        </p>
    </div>
    @endforeach
</div>
@include('layouts.sidebar')    
@endif
{{ $versions->links() }}
@include ('layouts.footer')