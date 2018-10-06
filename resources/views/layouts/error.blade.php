<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="UTF-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="{{ mix('/assets/css/core.css') }}" rel="stylesheet" type="text/css">
    <style>
        body {
            background: #f5f5f5;
            margin: 0;
            height: 100%;
            color: #2C2C2C;
            font-family: 'Roboto', sans-serif;
            font-weight: 400;
            display: flex;
            min-height: 100vh;
            flex-direction: column;
            word-break: break-word;
        }

        .v-wrap {
            display: table !important;
            min-height: 80vh;
            width: 100%;
        }

        .v-center {
            display: table-cell;
            vertical-align: middle;
            text-align: center;
        }

        .btn {
            border: none;
            border-radius: 2px;
            display: inline-block;
            height: 36px;
            line-height: 36px;
            padding: 0 16px;
            text-transform: uppercase;
            vertical-align: middle;
            -webkit-tap-highlight-color: transparent;
            font-size: 14px;
            outline: 0;
            text-decoration: none;
            color: #fff;
            background-color: #26a69a;
            text-align: center;
            letter-spacing: .5px;
            -webkit-transition: background-color .2s ease-out;
            transition: background-color .2s ease-out;
            cursor: pointer;
        }

        .btn-xl {
            height: 54px;
            line-height: 54px;
            font-size: 15px;
            padding: 0 28px;
        }

        .btn-link {
            background-color: #26a69a !important;
        }

        .btn-link:hover, .btn-link:focus {
            background-color: #26a69a;
            color: #fff;
            box-shadow: 0 5px 11px 0 rgba(200, 200, 200, 0.18), 0 4px 15px 0 rgba(200, 200, 200, 0.15);
        }

        .error-description {
            font-size: 30px;
            font-weight: 300;
            line-height: 32px;
            margin-bottom: 30px;
        }

        .error-goback-text {
            font-size: 22px;
            font-weight: 300;
            margin-bottom: 30px;
            margin-top: 15px;
        }

        .error-goback-button {
            margin-bottom: 30px;
        }
    </style>
    @yield("head")
</head>
<body>

@yield("content")

<script type="text/javascript" src="{{ mix('/assets/js/app.js') }}"></script>

@yield("scripts")

<script>
    "use strict";
    $(function() {
    });
</script>

</body>
</html>