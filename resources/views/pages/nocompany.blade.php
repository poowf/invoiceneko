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
                <h2>You have not completed your company information</h2>
                <div class="error-description">You need to fill in your company information before continuing</div>
                <div class="error-goback-text">Maybe you should fill in your company information first?</div>
                <div class="error-goback-button mbtm30"><a href="{{ route('company.edit') }}" class="btn btn-xl btn-primary btn-link">Fill in Information</a></div>
                <div class="footer">&copy; {{ date('Y') }} {{ config('app.name') }}</div>
            </div>
        </div>
    </div>
</div>
@stop

@section("scripts")
@stop