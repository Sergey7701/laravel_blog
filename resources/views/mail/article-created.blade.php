@component('mail::message')
# Introduction

The article "{{ $article->header }}" is created

@component('mail::button', ['url' => env('app.url').'/posts/'.$article->slug])
View article
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
