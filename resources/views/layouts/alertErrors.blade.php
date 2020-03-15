@if(count($errors))
<ul class="alert alert-danger col-12">
    @foreach ($errors->all() as $key=>$error)
    <li>
        {{ $error }}
    </li>
    @endforeach
</ul>
@endif