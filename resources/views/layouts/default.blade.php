<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="UTF-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#00aba9">
    <meta name="theme-color" content="#ffffff">
    <link href="{{ mix('/assets/css/materialize.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ mix('/assets/css/core.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ mix('/assets/css/style.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ mix('/assets/css/trumbowyg.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ mix('/assets/css/materialdesignicons.css') }}" rel="stylesheet" type="text/css">
    <title>{{ config('app.name') }}@if(!isActiveRoute('main')){{ ' | ' }}@endif{{ $page_title ?? '' }} </title>
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

<script type="text/javascript" src="{{ mix('/assets/js/manifest.js') }}"></script>
<script type="text/javascript" src="{{ mix('/assets/js/vendor.js') }}"></script>
<script type="text/javascript" src="{{ mix('/assets/js/app.js') }}"></script>

<script>
    "use strict";
    $(function() {
        $('.collapsible').collapsible();
        $('.sidenav').sidenav();
        $('.tooltipped').tooltip();
        $('.tabs').tabs();
        $('.modal').modal();
        $('.fixed-action-btn').floatingActionButton({
            toolbarEnabled: true
        });

        $('.dropdown-trigger').dropdown({
            coverTrigger: false
        });

        $('#return-to-top').click(function() {      // When arrow is clicked
            $('body,html').animate({
                scrollTop : 0                       // Scroll to top of body
            }, 800);
        });

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
                    {{-- 5000 is the duration of the toast, replace with text for unlimited duration --}}
                    M.toast({ html: "{!! $message['message'] !!}", displayLength: "poowf", classes: "{{ $message['level'] }}"});
                @endif
            @endforeach
            {{ session()->forget('flash_notification') }}
        @endif

        @if (count($errors) > 0)
            @foreach($errors->toArray() as $key => $error)
                $('#{{ $key }}').addClass('invalid');
                $('#{{ $key }}').siblings('span.helper-text').attr('data-error', "{!! $error[0] !!}");
                M.toast({ html: "{!! $error[0] !!}", displayLength: "5000", classes: "error"});
            @endforeach
        @endif
    });
</script>

@yield("scripts")

</body>
</html>