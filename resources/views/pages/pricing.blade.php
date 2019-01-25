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
                <h2 class="hero-header white-text no-margin">Pricing</h2>
            </div>
        </div>
    </div>
    <div class="mini-container">
        <div class="row">
            <div class="col s12">
                <div class="card-panel center">
                    <h3>Invoice Neko is currently free :)</h3>
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