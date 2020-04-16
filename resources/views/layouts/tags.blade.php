@php
    $tags = $article->tags ?? collect();
    $badgeStyle = $badgeStyle ?? '';
@endphp
<p class="col-12">
    @if($tags->isNotEmpty())
        @foreach ($tags as $tag)
            @if(!empty(trim($tag)))
                <span class='{{ $badgeStyle}}'>
                    {{ $tag->name }}
                </span>
            @endif
        @endforeach
    @endif
</p>