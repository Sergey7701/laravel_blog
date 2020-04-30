<form action=" {{ $entry->entry->id }}/comment" method="POST">
    {{ csrf_field() }} 
    <textarea class="col-12" name="text" placeholder="Ваш комментарий">{{ old('text') }}</textarea>
    <input type="submit">
</form>
@foreach ($comments as $comment)
    <p class="col-12">
        {{ $comment->text }}
    </p>    
    <p class="col-12">
        <small>{{ $comment->author->name }}</small>
    </p>    
@endforeach  
{{ $comments->links() }}