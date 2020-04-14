@include('layouts.header', ['title' => 'Новая статья'])
<h2 class="col-12">
    Создать новую статью
</h2>
@include('layouts.alertErrors')
<form class="col-8 d-block" action="/posts" method="POST">
    {{ csrf_field() }}
    <div class="form-group">
        <label for="header">Заголовок статьи</label>
        <input type="text" class="form-control" name="header" id="inputHeader" value="{{ trim(old('header')) }}" placeholder="Обязательное поле">
        <small id="headerHelp" class="form-text text-muted">Не более 100 символов</small>
    </div>
    <div class="form-group">
        <label for="description">Краткое описание статьи</label>
        <textarea class="col-12 form-control" rows="10" name="description" id="description" placeholder="Обязательное поле">{{ trim(old('description')) }}</textarea>
        <small id="descriptionHelp" class="form-text text-muted">Не более 255 символов</small>
    </div>
    <div class="form-group">
        <label class="d-block" for="text">Текст статьи</label>
        <textarea class="col-12 form-control" rows="20" name="text" id="text" placeholder="Обязательное поле">{{ trim(old('text')) }}</textarea>
    </div>
    <div class="form-group">
        <label class="form-check-label" for="publish">Теги</label>
        <input type="text" class="form-control" name="tags" id="inputTags" value="{{ trim(old('tags')) }}">
    </div>
    <div class="form-group form-check">
        <input type="checkbox" class="form-check-input" name="publish" id="publish" {{ old('publish') ? 'checked' : '' }}>
        <label class="form-check-label" for="publish">Опубликовать</label>
    </div>
    <input type="submit" class="btn btn-primary" value="Submit">
</form>
@include('layouts.sidebar')    
@include ('layouts.footer')