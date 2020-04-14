@component('mail::message')
# Introduction

The article "{{ $article->header }}" was deleted.

@component('mail::button', ['url' => env('app.url').'/posts/'])
View other articles
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
