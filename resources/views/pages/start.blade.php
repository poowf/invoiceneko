@extends("layouts.default", ['page_title' => 'Start'])

@section("head")
    <link href="{{ mix('/assets/css/intlTelInput.css') }}" rel="stylesheet" type="text/css">

    <style>
        .btn-text {
            text-decoration: none;
            color: #fff;
            background-color: #26a69a;
            text-align: center;
            letter-spacing: .5px;
            -webkit-transition: background-color .2s ease-out;
            transition: background-color .2s ease-out;
            cursor: pointer;
            line-height: 80px;
            font-size: 20px;
            padding: 40px 0px;
            text-transform: uppercase;
        }
    </style>
@stop

@section("content")
    <div class="container">
        <div class="row">
            <div class="col s12">
                <h3>Start Here</h3>
            </div>
        </div>
        <div class="row">
            <div class="col s12 m6">
                <a href="{{ route('company.show_check') }}">
                    <div class="card-panel light-blue lighten-1 btn-text">
                        <i class="mdi mdi-48px mdi-account-multiple-plus"></i><br>
                        Join a Company
                    </div>
                </a>
            </div>
            <div class="col s12 m6">
                <a href="{{ route('user.create') }}">
                    <div class="card-panel deep-orange lighten-1 btn-text">
                        <i class="mdi mdi-48px mdi-office-building"></i><br>
                        Create a new Company
                    </div>
                </a>
            </div>
        </div>
    </div>
@stop

@section("scripts")
    <script type="text/javascript" src="{{ mix('/assets/js/intlTelInput.js') }}"></script>

    <script type="text/javascript">
        "use strict";
        $(function() {
        });
    </script>
@stop