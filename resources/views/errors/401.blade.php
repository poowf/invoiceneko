@extends("layouts/error")

@section("head")
    <title>{{ config('app.name') }}</title>
    <style>
    </style>
@stop

@section("content")
    <div class="container">
        <div class="col l12 m12 s12 center-align">
            <div class="v-wrap">
                <div class="error-container v-center">
                    <h2>Error 401 - Unauthorized</h2>
                    <div class="error-description">Are you sure you have the access rights to this page?</div>
                    <div class="error-goback-text">Would you like to go home?</div>
                    <div class="error-goback-button mbtm30"><a href="{{ route('main') }}" class="btn btn-xl btn-primary btn-link">Let's go home</a></div>
                    <div class="footer">&copy; {{ date('Y') }} {{ config('app.name') }}</div>
                </div>
            </div>
        </div>
    </div>
@stop

@section("scripts")
@stop