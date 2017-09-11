@extends("layouts/default")

@section("head")
    <title>Invoice Plz</title>
    <style>
    </style>
@stop

@section("content")
    <div class="container">
        <div class="row">
            <div class="col s12">
                <h3>Payment Details</h3>
            </div>
        </div>
        <div class="row">
            <div class="col s12">
                <div class="card-panel">
                    <dt>Payment Amount</dt>
                    <dd>${{ $payment->amount or '-' }}</dd>
                    <dt>Payment Date</dt>
                    <dd>{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $payment->receiveddate)->format('j F, Y') }}</dd>
                    <dt>Payment Mode</dt>
                    <dd>{{ $payment->mode or '-' }}</dd>
                    <dt>Payment Notes</dt>
                    <dd>{{ $payment->notes or '-' }}</dd>
                    <dt>Company Name</dt>
                    <dd>{{ $payment->client->companyname }}</dd>
                    <dt>Company Address</dt>
                    <dd>{{ $payment->client->address }}</dd>
                    <dt>Company Nickname</dt>
                    <dd>{{ $payment->client->nickname or '' }}</dd>
                    <dt>Company Registration Number</dt>
                    <dd>{{ $payment->client->crn }}
                    </dl>
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