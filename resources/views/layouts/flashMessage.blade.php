@if(session()->has('message'))
<div class="alert alert-{{ session('message_type') }} col-12">
    {{ session('message') }}
</div>
@endif