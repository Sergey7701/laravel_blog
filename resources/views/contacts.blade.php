@include('layouts.header', ['title' => 'Контакты'])
<div class="row">
    <h2 class="col-12">
        Контакты
    </h2>
    <p class="col-12">
        <strong>Телефон:</strong> 123-456-789
    </p>
    <p class="col-12">
        <strong>Email:</strong> admin@example.com
    </p>
</div>
@include('layouts.alertErrors')
@if(isset($success))
<div class="col-12 alert alert-success">
    Сообщение отправлено
</div>
@endif
<form class="col-8 d-block" action="" method="POST">
    <legend class="legend-color-guide">Обратная связь</legend>
    {{ csrf_field() }}
    <div class="form-group">
        <label for="email">Ваш Email</label>
        <input type="text" class="form-control" name="email" id="inputHeader" value="{{ trim(old('email')) }}" placeholder="Обязательное поле">
        <small id="headerHelp" class="form-text text-muted">Действующий Email</small>
    </div>
    <div class="form-group">
        <label for="message">Сообщение</label>
        <textarea class="form-control" name="message" id="message" value="{{ trim(old('message')) }}" placeholder="Обязательное поле"></textarea>
        <small id="headerHelp" class="form-text text-muted">Ваше сообщение для нас</small>
    </div>
    <input type="submit" class="btn btn-primary" value="Submit">
</form>
@include ('layouts.footer')