@include('layouts.header', ['title' => 'Новое уведомление'])
@include ('layouts.flashMessage')
<h2 class="col-12">
    Создать новое уведомление
</h2>
@include('layouts.alertErrors')
<form class="col-8 d-block" action="/service" method="POST">
    {{ csrf_field() }}
    <div class="form-group">
        <label for="title">Заголовок уведомления</label>
        <input type="text" class="form-control" name="title" id="inputHeader" value="{{ trim(old('title')) }}" placeholder="Обязательное поле">
        <small id="headerHelp" class="form-text text-muted">Не более 100 символов</small>
    </div>
    <div class="form-group">
        <label for="text">Текст уведомления</label>
        <textarea class="col-12 form-control" rows="10" name="text" id="text" placeholder="Обязательное поле">{{ trim(old('text')) }}</textarea>
        <small id="textHelp" class="form-text text-muted">Не более 255 символов</small>
    </div> 
    <input type="submit" class="btn btn-primary" value="Submit">
</form>
@include('layouts.sidebar')    
@include ('layouts.footer')