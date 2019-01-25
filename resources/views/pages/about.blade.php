@extends("layouts.default", ['page_title' => 'Invoice Neko'])

@section("head")
    <style>
    </style>
@stop

@section("content")
    <div class="row pall30" style="background-color: #585454;">
        <div class="mini-container">
            <div class="col s12 center">
                <img src="{{ asset('assets/img/avatar.png') }}" class="hero-logo-image">
                <h2 class="hero-header white-text no-margin">About Invoice Neko</h2>
                <p class="hero-description flow-text white-text mtop20">Learn more about who we are, what we do and what we'll be doing</p>
            </div>
        </div>
    </div>
    <div class="mini-container">
        <div class="row">
            <div class="col s12">
                <div class="card-panel center">
                    <p>Invoice Neko neko nyan nyan</p>
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