<aside class='col-3 blog-sidebar'>
    <h3>Облако тегов:</h3>
@php
    $tags = $tagsCloud;
@endphp
<p class="col-12">
    @if($tags->isNotEmpty())
        @foreach ($tags as $tag)
        <a class="text-primary" href="/posts/tags/{{ $tag->getRouteKey() }}/">
            {{ $tag->getRouteKey() }}
            </a>
        @endforeach
    @endif
</p>
</aside>