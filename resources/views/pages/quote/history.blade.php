@extends("layouts/default")

@section("head")
    <title>{{ config('app.name') }}</title>
    <style>
        :root .card.single-history {
            overflow: hidden;
            margin: 0px 20px;
            padding: 35px;
            text-align: center;
        }
    </style>
@stop

@section("content")
    <div class="mini-container">
        <div class="row">
            <div class="col s6">
            </div>
            <div class="col s6 mtop30 right">
                <a class="btn btn-lg btn-default" href="{{ route('payment.create', [ 'invoice' => $invoice->id] ) }}">
                    Log Payment
                </a>
                <a class="btn btn-lg btn-default" href="{{ route('invoice.download', [ 'invoice' => $invoice->id] ) }}">
                    Save PDF
                </a>
                <a class="btn btn-lg btn-default" href="{{ route('invoice.printview', [ 'invoice' => $invoice->id] ) }}">
                    Print
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col s12">
                <h3>Current Details</h3>
                <div id="details-panel" class="card-panel">
                    <dt>Company Name</dt>
                    <dd>{{ $client->companyname }}</dd>
                    <dt>Company Address</dt>
                    <dd>{{ $client->address }}</dd>
                    <dt>Company Nickname</dt>
                    <dd>{{ $client->nickname ?? '' }}</dd>
                    <dt>Company Registration Number</dt>
                    <dd>{{ $client->crn }}
                    <dt>Contact Name</dt>
                    <dd>{{ $client->contactname ?? '-' }}</dd>
                    <dt>Contact Email</dt>
                    <dd>{{ $client->contactemail ?? '-' }}</dd>
                    <dt>Contact Phone</dt>
                    <dd>{{ $client->contactphone ?? '-' }}</dd>
                    <dt>Status</dt>
                    <dd>
                        @if ($invoice->status == App\Models\Invoice::STATUS_OVERDUE)
                            <span class="alt-badge error">{{ $invoice->statustext() }}</span>
                        @elseif ($invoice->status == App\Models\Invoice::STATUS_DRAFT)
                            <span class="alt-badge">{{ $invoice->statustext() }}</span>
                        @elseif ($invoice->status == App\Models\Invoice::STATUS_OPEN)
                            <span class="alt-badge warning">{{ $invoice->statustext() }}</span>
                        @elseif ($invoice->status == App\Models\Invoice::STATUS_CLOSED)
                            <span class="alt-badge success">{{ $invoice->statustext() }}</span>
                        @elseif ($invoice->status == App\Models\Invoice::STATUS_ARCHIVED)
                            <span class="alt-badge grey">{{ $invoice->statustext() }}</span>
                        @elseif ($invoice->status == App\Models\Invoice::STATUS_WRITTENOFF)
                            <span class="alt-badge grey">{{ $invoice->statustext() }}</span>
                        @endif
                    </dd>
                    </dl>
                </div>
            </div>
        </div>
        <div class="row">
            @if($histories->isNotEmpty())
                <div class="col s12">
                    <h3>Invoice History</h3>
                </div>
                @foreach($histories as $key => $history)
                    <div class="col s12 m4">
                        <div class="single-history-wrapper">
                            <div class="card single-history">
                                <p>{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $history->created_at)->format('j F, Y, h:i:s a') }}</p>
                                <a href="{{ route('invoice.old.show', [ 'oldinvoice' => $history->id ] ) }}"><i class="material-icons">remove_red_eye</i></a>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="card-panel center">
                    <i class="material-icons" style="font-size: 3em; color: grey;">sentiment_dissatisfied</i>
                    <p style="font-size: 15px; font-weight: 400; color: grey;">There's nothing here</p>
                </div>
            @endif
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