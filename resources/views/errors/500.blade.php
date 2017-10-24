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
                    <h2>Error 500 - Server Error</h2>
                    <div class="error-description">A catastrophic failure has happened, we are on it!</div>
                    <div class="error-goback-text">Would you like to go home?</div>
                    <div class="error-goback-button"><a href="{{ route('main') }}" class="btn btn-xl btn-primary btn-link">Let's go
                            home</a></div>
                    <div class="footer">&copy; 2017 Chipd</div>
                </div>
            </div>
        </div>
    </div>
@stop

@section("scripts")
    <script type="text/javascript">
        "use strict";
        $(function() {
        });
    </script>
@stop