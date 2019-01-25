@extends("layouts.default", ['page_title' => 'Invoice | History'])

@section("head")
    <style>
    </style>
@stop

@section("content")
    <div class="wide-container">
        <div class="row">
            <div class="col s6">
            </div>
            <div class="col s6 mtop30 right">
                @can('update', $invoice)
                <a class="btn btn-lg btn-default" href="{{ route('payment.create', [ 'invoice' => $invoice, 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ] ) }}">
                    Log Payment
                </a>
                @endcan
                <a class="btn btn-lg btn-default" href="{{ route('invoice.download', [ 'invoice' => $invoice, 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ] ) }}">
                    Save PDF
                </a>
                <a class="btn btn-lg btn-default" href="{{ route('invoice.printview', [ 'invoice' => $invoice, 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ] ) }}">
                    Print
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col s12">
                <h3>Current Details</h3>
                <div id="details-panel" class="card-panel">
                    <dl>
                        <dt>Company Name</dt>
                        <dd>{{ $client->companyname ?? '-' }}</dd>
                        <dt>Company Block</dt>
                        <dd>{{ $client->block ?? '-' }}</dd>
                        <dt>Company Street</dt>
                        <dd>{{ $client->street ?? '-' }}</dd>
                        <dt>Company Unit Number</dt>
                        <dd>{{ $client->unitnumber ?? '-' }}</dd>
                        <dt>Company Postal Code</dt>
                        <dd>{{ $client->postalcode ?? '-' }}</dd>
                        <dt>Company Nickname</dt>
                        <dd>{{ $client->nickname ?? '-' }}</dd>
                        <dt>Company Registration Number</dt>
                        <dd>{{ $client->crn ?? '-' }}
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
                    <div class="col s12 m4 l4 xl3 single-history-card">
                        <div class="card single-history">
                            <h6>Date/Time</h6>
                            <p class="mtop20">{{ $history->updated_at->format('d F, Y') }}</p>
                            <p>{{ $history->updated_at->format('h:i:s a') }}</p>
                            <a class="btn btn-link full-width blue-grey lighten-1 waves-effect waves-dark" href="{{ route('invoice.old.show', [ 'oldinvoice' => $history->id, 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ] ) }}">
                                View
                            </a>
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