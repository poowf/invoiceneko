@extends("layouts.default", ['page_title' => 'Payment | View'])

@section("head")
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
                    <dl>
                        <dt>Payment Amount</dt>
                        <dd>${{ $payment->moneyformat ?? '-' }}</dd>
                        <dt>Payment Date</dt>
                        <dd>{{ $payment->receiveddate->format('d F, Y') }}</dd>
                        <dt>Payment Mode</dt>
                        <dd>{{ $payment->mode ?? '-' }}</dd>
                        <dt>Payment Notes</dt>
                        <dd>{{ $payment->notes ?? '-' }}</dd>
                        <dt>Company Name</dt>
                        <dd>{{ $payment->getClient()->companyname ?? '-' }}</dd>
                        <dt>Company Block</dt>
                        <dd>{{ $payment->getClient()->block ?? '-' }}</dd>
                        <dt>Company Street</dt>
                        <dd>{{ $payment->getClient()->street ?? '-' }}</dd>
                        <dt>Company Unit Number</dt>
                        <dd>{{ $payment->getClient()->unitnumber ?? '-' }}</dd>
                        <dt>Company Postal Code</dt>
                        <dd>{{ $payment->getClient()->postalcode ?? '-' }}</dd>
                        <dt>Company Nickname</dt>
                        <dd>{{ $payment->getClient()->nickname ?? '-' }}</dd>
                        <dt>Company Registration Number</dt>
                        <dd>{{ $payment->getClient()->crn ?? '-' }}</dd>
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