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
                    <h2>You have no clients created</h2>
                    <div class="error-description">You need at least one client to create an quote</div>
                    <div class="error-goback-text">Maybe you should create a client first?</div>
                    <div class="error-goback-button mbtm30"><a href="{{ route('client.create', [ 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ]) }}" class="btn btn-xl btn-primary btn-link">Create a Client</a></div>
                    <div class="footer">&copy; {{ date('Y') }} {{ config('app.name') }}</div>
                </div>
            </div>
        </div>
    </div>
@stop

@section("scripts")
@stop