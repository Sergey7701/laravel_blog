@authorized
    <form class="mb-3" action=" {{ $entry->entry->id }}/comment" method="POST">
        {{ csrf_field() }} 
        <textarea class="col-12" name="text" placeholder="Ваш комментарий">{{ old('text') }}</textarea>
        <input type="submit">
    </form>
@else 
    <p class="text-info col-12">
        <a href="/login">Войдите</a> или <a href="/register">зарегистрируйтесь</a>, чтобы оставлять комментарии
    </p>    
@endauthorized
@foreach ($comments as $comment)
    <p class="col-12">
        {{ $comment->text }}
    </p>    
    <p class="col-12">
        <small>{{ $comment->author->name }}, {{ $comment->created_at->diffForHumans() }}</small>
    </p>    
@endforeach  
{{ $comments->links() }}