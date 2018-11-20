@extends("layouts.default", ['page_title' => 'Old Invoice | View'])

@section("head")
    <style>
    </style>
@stop

@section("content")
    <div class="container">
        <div class="row">
            <div class="col s6 mtop30 right">
                <a class="btn btn-lg btn-default" href="{{ route('invoice.old.download', [ 'invoice' => $invoice->id, 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ] ) }}">
                    Save PDF
                </a>
                <a class="btn btn-lg btn-default" href="{{ route('invoice.old.printview', [ 'invoice' => $invoice->id, 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ] ) }}">
                    Print
                </a>
            </div>
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