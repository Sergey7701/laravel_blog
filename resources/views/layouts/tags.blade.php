@php
    $tags = $article->tags ?? collect();
    $badgeStyle = $badgeStyle ?? '';
@endphp
<p class="col-9">
    @if($tags->isNotEmpty())
        @foreach ($tags as $tag)
            @if(strlen(trim($tag)) > 1)
                <span class='{{ $badgeStyle }}'>
                    {{ $tag->name }}
                </span>
            @endif
        @endforeach
    @endif
</p>