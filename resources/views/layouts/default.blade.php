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

        $(document).on('click', '#toast-container .toast', function() {
            $(this).fadeOut(function(){
                $(this).remove();
            });
        });

        @if(session()->has('flash_notification'))
            @foreach (session('flash_notification', collect())->toArray() as $message)
                @if ($message['overlay'])
                    @include('flash::modal', [
                        'modalClass' => 'flash-modal',
                        'title'      => $message['title'],
                        'body'       => $message['message']
                    ])
                @else
                    Materialize.toast('{!! $message['message'] !!}', '5000', '{{ $message['level'] }}') // 5000 is the duration of the toast, replace with text for unlimited duration
                @endif
            @endforeach
        {{ session()->forget('flash_notification') }}
        @endif
        $(".button-collapse").sideNav();
    });
</script>
</body>
</html>