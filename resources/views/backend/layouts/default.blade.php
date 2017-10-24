<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="shortcut icon" href="{{ asset('assets/backend/img/logo-fav.png') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/backend/lib/perfect-scrollbar/css/perfect-scrollbar.min.css') }}"/>
    {{--<link rel="stylesheet" type="text/css" href="{{ asset('assets/backend/lib/material-design-icons/css/material-design-iconic-font.min.css') }}"/>--}}
    <link rel="stylesheet" type="text/css" href="//cdn.materialdesignicons.com/2.0.46/css/materialdesignicons.min.css">
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/backend/lib/jquery.vectormap/jquery-jvectormap-1.2.2.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/backend/lib/jqvmap/jqvmap.min.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/backend/lib/datetimepicker/css/bootstrap-datetimepicker.min.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/backend/lib/jquery.gritter/css/jquery.gritter.css') }}"/>

    <link rel="stylesheet" href="{{ asset('assets/backend/css/style.css') }}" type="text/css"/>
    <link rel="stylesheet" href="{{ asset('assets/backend/css/core.css') }}" type="text/css"/>
    @yield("head")
</head>
<body>
<div class="be-wrapper be-fixed-sidebar">

    @include("backend/partials/header")

    @include("backend/partials/left-sidebar")

    <div class="be-content">
        @yield("content")
    </div>

    @include("backend/partials/right-sidebar")
</div>
<script src="{{ asset('assets/backend/lib/jquery/jquery.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/backend/lib/perfect-scrollbar/js/perfect-scrollbar.jquery.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/backend/js/main.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/backend/lib/bootstrap/dist/js/bootstrap.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/backend/lib/parsley/parsley.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/backend/lib/jquery-flot/jquery.flot.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/backend/lib/jquery-flot/jquery.flot.pie.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/backend/lib/jquery-flot/jquery.flot.resize.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/backend/lib/jquery-flot/plugins/jquery.flot.orderBars.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/backend/lib/jquery-flot/plugins/curvedLines.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/backend/lib/jquery.sparkline/jquery.sparkline.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/backend/lib/countup/countUp.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/backend/lib/jquery-ui/jquery-ui.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/backend/lib/jqvmap/jquery.vmap.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/backend/lib/jqvmap/maps/jquery.vmap.world.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/backend/lib/jquery.gritter/js/jquery.gritter.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/backend/js/app-dashboard.js') }}" type="text/javascript"></script>
<script type="text/javascript">
    "use strict";
    $(function() {
        App.init();
        @if(Session::has('flash_notification.message'))
            $.gritter.add({
                title: 'Notification',
                text: '{{ Session::get('flash_notification.message')}}',
                class_name: 'color {{ Session::get('flash_notification.level')}}'
            });
        @endif
        @if (count($errors) > 0)
            @foreach($errors->toArray() as $key => $error)
                $.gritter.add({
                    title: 'Notification',
                    text: '{!! $error[0] !!}',
                    class_name: 'color danger'
                });
            @endforeach
        @endif
    });
</script>
@yield("scripts")
</body>
</html>