@extends("layouts.default", ['page_title' => 'Invoice Neko'])

@section("head")
    <style>
    </style>
@stop

@section("content")
    <div class="container">
        <div class="row mtop30">
            <div class="col s12 m6">
                <div class="card center pbtm20">
                    <div class="card-header theme-color-secondary">
                        <p>Latest Stable</p>
                        <p class="version">v1.0.0</p>
                    </div>
                    <div class="card-content">
                        <p>Latest stable release for Invoice Neko</p>
                        <p>SHA256:</p>
                        <p>SHA512:</p>
                        <a href="//github.com/poowf/invoiceneko" class="btn btn-large btn-link">Download</a>
                    </div>
                </div>
            </div>
            <div class="col s12 m6">
                <div class="card center">
                    <div class="card-header theme-color-secondary">
                        <p>Latest Stable</p>
                        <p class="version">master</p>
                    </div>
                    <div class="card-content">
                        <p>Latest rolling release</p>
                        <a href="//github.com/poowf/invoiceneko" class="btn btn-large btn-link">Download</a>
                    </div>
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