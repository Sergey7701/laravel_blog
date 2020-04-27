<?php
    use App\Models\Article;
    use App\News;
?>
@include ('layouts.header', ['title' => 'Главная'])
@include ('layouts.flashMessage')

@if (count($entries))
<div class="row col-9">
    @foreach($entries as $entry)
        @switch ($entry->entryable_type)
            @case (Article::class)
                @include('layouts.printWelcomeArticlePreview', ['article' => $entry->entryable])
                @break
            @case (News::class)
                @include('layouts.printWelcomeNewsPreview', ['news' => $entry->entryable])
                @break
        @endswitch
    @endforeach
</div>
@include ('layouts.sidebar')
@endif
{{ $entries->links() }}
@include ('layouts.footer')