<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{$title ?? ''}}</title>
        @include ('layouts.styles')
    </head>

    <body>

        @include ('layouts.nav')
        <main role="main" class="container">
            <div class='row pt-3 col-12'>
