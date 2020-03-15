@include('layouts.header', ['title' => 'Список обращений'])
<h2 class="mb-3">Список обращений:</h2>
<div class="row col-12 mb-0">
    <div class="row blog-post col-12 text-nowrap mb-0">
        <div class="col-4 border bg-light">
            <strong> Email</strong>
        </div>
        <div class="col-4 border bg-light">
            Сообщение
        </div>
        <div class="col-4 border bg-light">
            Дата
        </div>
    </div>
</div>
@if (count($messages))
<div class="row col-12">
    @foreach($messages as $message)
    <div class="row blog-post col-12 text-nowrap mb-0">
        <div class="col-4 border" style="overflow: auto">
            <strong> {{$message->email}}</strong>
        </div>
        <div class="col-4 border" style="overflow: auto">
            {{$message->message}}
        </div>
        <div class="col-4 border ">
            <small> {{$message->created_at}}</small>
        </div>
    </div>
    @endforeach
</div>
@endif
{{ $messages->links() }}
@include ('layouts.footer')