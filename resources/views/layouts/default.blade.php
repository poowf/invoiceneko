<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="UTF-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="{{ mix('/assets/css/core.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ mix('/assets/css/style.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ mix('/assets/css/selectize.css') }}" rel="stylesheet" type="text/css">
    @yield("head")
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>
</head>
<body>
@include("partials/header")

@yield("content")

@include("partials/footer")

<script type="text/javascript" src="{{ mix('/assets/js/app.js') }}"></script>
@yield("scripts")
<script>
    "use strict";
    $(function() {
        $(".button-collapse").sideNav();
    });
</script>
</body>
</html>