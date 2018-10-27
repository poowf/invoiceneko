@extends("layouts/default")

@section("head")
    <title>{{ config('app.name') }}</title>
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
                    <dd>${{ $payment->moneyformat ?? '-' }}</dd>
                    <dt>Payment Date</dt>
                    <dd>{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $payment->receiveddate)->format('j F, Y') }}</dd>
                    <dt>Payment Mode</dt>
                    <dd>{{ $payment->mode ?? '-' }}</dd>
                    <dt>Payment Notes</dt>
                    <dd>{{ $payment->notes ?? '-' }}</dd>
                    <dt>Company Name</dt>
                    <dd>{{ $payment->client->companyname }}</dd>
                    <dt>Company Block</dt>
                    <dd>{{ $payment->client->block ?? '-' }}</dd>
                    <dt>Company Street</dt>
                    <dd>{{ $payment->client->street ?? '-' }}</dd>
                    <dt>Company Unit Number</dt>
                    <dd>{{ $payment->client->unitnumber ?? '-' }}</dd>
                    <dt>Company Postal Code</dt>
                    <dd>{{ $payment->client->postalcode ?? '-' }}</dd>
                    <dt>Company Nickname</dt>
                    <dd>{{ $payment->client->nickname ?? '-' }}</dd>
                    <dt>Company Registration Number</dt>
                    <dd>{{ $payment->client->crn }}</dd>
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