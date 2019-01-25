@extends("layouts.default", ['page_title' => 'Old Invoice | View'])

@section("head")
    <style>
    </style>
@stop

@section("content")
    <div class="container">
        <div class="row">
        </div>
        <div class="row">
            @include('partials/invoice', ['invoice_title' => 'Old Invoice', 'class' => 's12 l10 push-l1'])
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