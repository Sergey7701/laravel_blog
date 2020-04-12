@php
    $tags = $article->tags ?? collect();
    $badgeStyle = $badgeStyle ?? '';
@endphp
<p class="col-12">
    @if($tags->isNotEmpty())
        @foreach ($tags as $tag)
            <span class='{{ $badgeStyle}}'>
            {{ $tag->name }}
            </span>
        @endforeach
    @endif
</p>