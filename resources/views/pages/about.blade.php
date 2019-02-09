@extends("layouts.default", ['page_title' => 'About'])

@section("head")
    <style>
    </style>
@stop

@section("content")
    <div class="row pall30" style="background-color: #585454;">
        <div class="mini-container">
            <div class="col s12 center">
                <div class="hero-logo-container circle">
                    <img src="{{ asset('assets/img/logo.svg') }}" class="hero-logo-image">
                </div>
                <h2 class="hero-header white-text no-margin">About Invoice Neko</h2>
                <p class="hero-description flow-text white-text mtop20">Learn more about who we are, what we do and what we'll be doing</p>
            </div>
        </div>
    </div>
    <div class="mini-container">
        <div class="row">
            <div class="col s12">
                <div class="card-panel center">
                    <p>Invoice Neko's goal is to help start-ups and small businesses get a robust and well-designed invoicing system.</p>
                    <p>Invoice Neko is built by Poowf Labs as an open-source and well designed alternative to all invoicing systems out there</p>
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