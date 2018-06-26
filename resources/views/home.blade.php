<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="shortcut icon" href="{{{ asset('img/branding2.png') }}}">
        <title>Branding</title>
        {!! Html::style('css/app.css') !!}
        <link href="https://transloadit.edgly.net/releases/uppy/v0.25.2/dist/uppy.min.css" rel="stylesheet">
    </head>
    <body>
      <div id="app"></div>
      @include('scripts')
    </body>
</html>
