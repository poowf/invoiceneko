@extends("layouts.default", ['page_title' => 'Quote | History'])

@section("head")
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
                <a class="btn btn-lg btn-default" href="{{ route('payment.create', [ 'quote' => $quote->id] ) }}">
                    Log Payment
                </a>
                <a class="btn btn-lg btn-default" href="{{ route('quote.download', [ 'quote' => $quote->id] ) }}">
                    Save PDF
                </a>
                <a class="btn btn-lg btn-default" href="{{ route('quote.printview', [ 'quote' => $quote->id] ) }}">
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
                        <dd>{{ $client->companyname }}</dd>
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
                        <dd>{{ $client->crn }}
                        <dt>Contact Name</dt>
                        <dd>{{ $client->contactname ?? '-' }}</dd>
                        <dt>Contact Email</dt>
                        <dd>{{ $client->contactemail ?? '-' }}</dd>
                        <dt>Contact Phone</dt>
                        <dd>{{ $client->contactphone ?? '-' }}</dd>
                        <dt>Status</dt>
                        <dd>
                            @if ($quote->status == App\Models\Quote::STATUS_DRAFT)
                                <span class="alt-badge">{{ $quote->statustext() }}</span>
                            @elseif ($quote->status == App\Models\Quote::STATUS_OPEN)
                                <span class="alt-badge warning">{{ $quote->statustext() }}</span>
                            @elseif ($quote->status == App\Models\Quote::STATUS_EXPIRED)
                                <span class="alt-badge error">{{ $quote->statustext() }}</span>
                            @elseif ($quote->status == App\Models\Quote::STATUS_COMPLETED)
                                <span class="alt-badge success">{{ $quote->statustext() }}</span>
                            @endif
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
        <div class="row">
            @if($histories->isNotEmpty())
                <div class="col s12">
                    <h3>Quote History</h3>
                </div>
                @foreach($histories as $key => $history)
                    <div class="col s12 m4 l4 xl3 single-history-card">
                        <div class="card single-history">
                            <p>{{ $history->created_at->format('d F, Y, h:i:s a') }}</p>
                            <a class="btn btn-link blue-grey lighten-1 waves-effect waves-dark" href="{{ route('quote.old.show', [ 'oldquote' => $history->id ] ) }}">
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