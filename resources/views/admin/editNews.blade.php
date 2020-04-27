@include('layouts.header', ['title' => 'Редактировать новость'])
<div class="row col-9">
    @permission('manage-articles')
        <a class="mb-3 ml-5 text-info" href="/admin/posts">На главную для администраторов</a>
        <a class="mb-3 ml-5 text-info" href="/admin/news/{{ $news->slug }}/versions">История изменений статьи</a>
        <h2>
            Редактировать новость<span style="color: lightgrey">{{ $news->slug }}</span>
        </h2>
        @include('layouts.tags')
        @include('layouts.alertErrors')
        <form class="col-12 d-block" action="/admin/news/{{ $news->slug }}" method="POST">
            {{ csrf_field() }}
            @method('PATCH')
            <div class="form-group">
                <label for="header">Заголовок новости</label>
                <input type="text" class="form-control" name="header" id="inputHeader" value="{{ trim(old('header', $news->header)) }}" placeholder="Обязательное поле">
                <small id="headerHelp" class="form-text text-muted">Не более 100 символов</small>
            </div>
            <div class="form-group">
                <label class="d-block" for="text">Текст новости</label>
                <textarea class="col-12 form-control" rows="20" name="text" id="text" placeholder="Обязательное поле">{{ trim(old('text', $news->text)) }}</textarea>
            </div>
            <div class="form-group">
                <label class="form-check-label" for="publish">Теги</label>
                <input type="text" class="form-control" name="tags" id="inputTags" value="{{ trim(old('tags',$news->tags->pluck('name')->implode(', '))) }}">
            </div>
            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" name="publish" id="publish" {{ old('publish', $news->publish) ? 'checked' : '' }}>
                <label class="form-check-label" for="publish">Опубликовать</label>
            </div>
            <input type="submit" class="btn btn-primary" value="Submit">
        </form>
        <form class="col-8 d-block mt-5" action="/admin/news/{{ $news->slug }}" method="POST">
            {{ csrf_field() }}
            @method('DELETE')
            <input type="submit" class="btn btn-danger" value="Delete">
        </form>
    @endpermission    
</div>
@include ('layouts.sidebar')
@include ('layouts.footer')