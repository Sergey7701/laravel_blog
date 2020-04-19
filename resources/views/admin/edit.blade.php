@include('layouts.header', ['title' => 'Редактировать статью'])
@include ('layouts.adminHeader')
<div class="row col-9">
@role('administrator')
    @role('administrator')
        @if (Auth::user()->can('manage-articles'))
            <a class="mb-3 ml-5 text-info" href="/admin/posts">На главную для администраторов</a>
        @endif
    @endcan
        @if (Auth::user()->can('manage-articles'))
            <h2 class="col-12">
                Редактировать статью <span style="color: lightgrey">{{ $article->slug }}</span>
            </h2>
            @include('layouts.tags', ['badgeStyle' => 'badge badge-info'])
            @include('layouts.alertErrors')
            <form class="col-12 d-block" action="/admin/posts/{{ $article->slug }}" method="POST">
                {{ csrf_field() }}
                @method('PATCH')
                <div class="form-group">
                    <label for="header">Заголовок статьи</label>
                    <input type="text" class="form-control" name="header" id="inputHeader" value="{{ trim(old('header', $article->header)) }}" placeholder="Обязательное поле">
                    <small id="headerHelp" class="form-text text-muted">Не более 100 символов</small>
                </div>
                <div class="form-group">
                    <label for="description">Краткое описание статьи</label>
                    <textarea class="col-12 form-control" rows="10" name="description" id="description" placeholder="Обязательное поле">{{ trim(old('description', $article->description)) }}</textarea>
                    <small id="descriptionHelp" class="form-text text-muted">Не более 255 символов</small>
                </div>
                <div class="form-group">
                    <label class="d-block" for="text">Текст статьи</label>
                    <textarea class="col-12 form-control" rows="20" name="text" id="text" placeholder="Обязательное поле">{{ trim(old('text', $article->text)) }}</textarea>
                </div>
                <div class="form-group">
                    <label class="form-check-label" for="publish">Теги</label>
                    <input type="text" class="form-control" name="tags" id="inputTags" value="{{ trim(old('tags',$article->tags->pluck('name')->implode(', '))) }}">
                </div>
                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" name="publish" id="publish" {{ old('publish', $article->publish) ? 'checked' : '' }}>
                    <label class="form-check-label" for="publish">Опубликовать</label>
                </div>
                <input type="submit" class="btn btn-primary" value="Submit">
            </form>
            <form class="col-8 d-block mt-5" action="/admin/posts/{{ $article->slug }}" method="POST">
                {{ csrf_field() }}
                @method('DELETE')
                <input type="submit" class="btn btn-danger" value="Delete">
            </form>
    @endif
@endcan
</div>
@include ('layouts.sidebar')
@include ('layouts.footer')