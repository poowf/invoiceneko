@extends("layouts.default", ['page_title' => 'Quotes'])

@section("head")
    <style>
        .card-panel.tab-panel {
            margin-top: 0;
        }
        .tab {
            background-color: #299a9a;
        }
        .tabs .tab a {
            color: #cbdede;
        }
        .tabs .tab a:hover, .tabs .tab a.active {
            color: #fff;
        }
        .tabs .indicator {
            height: 5px;
            background-color: #FFD264;
        }
        #quote-container {
            margin: 0px;
        }
    </style>
@stop

@section("content")
    <div class="wide-container">
        <div class="row">
            <div class="col s6">
                <h3>Quotes</h3>
            </div>

            <div class="col s6 right mtop30">
                @can('create', \App\Models\Quote::class)
                <a href="{{ route('quote.create', [ 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ]) }}" class="btn btn-link waves-effect waves-dark">Create</a>
                @endcan
                @can('index', \App\Models\Quote::class)
                <a href="{{ route('quote.index.archived', [ 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ]) }}" class="btn btn-link waves-effect waves-dark">Archived Quotes</a>
                @endcan
            </div>
        </div>
        <div class="row">
            <div class="col s12">
                <div class="card-panel search-panel">
                    <input id="search-input" class="card-input" name="search-input" type="search" placeholder="Search">
                </div>

                <div id="quote-container" class="row">
                    <div class="card-panel flex">
                        <table id="quotes-table" class="responsive-table striped">
                            <thead>
                                <tr>
                                    <th>Quote ID</th>
                                    <th>Amount</th>
                                    <th>Due Date</th>
                                    <th>Client Name</th>
                                    <th>Client Phone</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($quotes as $key => $quote)
                                    <tr class="single-quote-row">
                                        <td>{{ $quote->nice_quote_id }}</td>
                                        <td>${{ $quote->totalmoneyformat  }}</td>
                                        <td>{{ $quote->duedate->format('d F, Y') }}</td>
                                        <td>{{ $quote->client->companyname }}</td>
                                        <td>{{ $quote->client->contactphone }}</td>
                                        <td>
                                            @if ($quote->status == App\Models\Quote::STATUS_DRAFT)
                                                <span class="alt-badge">{{ $quote->statustext() }}</span>
                                            @elseif ($quote->status == App\Models\Quote::STATUS_OPEN)
                                                <span class="alt-badge warning">{{ $quote->statustext() }}</span>
                                            @elseif ($quote->status == App\Models\Quote::STATUS_EXPIRED)
                                                <span class="alt-badge error">{{ $quote->statustext() }}</span>
                                            @elseif ($quote->status == App\Models\Quote::STATUS_COMPLETED)
                                                <span class="alt-badge success">{{ $quote->statustext() }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @can('view', $quote)
                                            <a href="{{ route('quote.show', [ 'quote' => $quote->id, 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ] ) }}" class="tooltipped" data-position="top" data-delay="50" data-tooltip="View Quote"><i class="material-icons">remove_red_eye</i></a>
                                            @endcan
                                            @can('update', $quote)
                                            <form method="post" action="{{ route('quote.duplicate', [ 'quote' => $quote->id, 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ] ) }}" class="null-form tooltipped" data-position="top" data-delay="50" data-tooltip="Duplicate Quote">
                                                {{ csrf_field() }}
                                                <button class="null-btn" type="submit"><i class="material-icons">control_point_duplicate</i></button>
                                            </form>
                                            @endcan
                                            @can('update', $quote)
                                            <a href="{{ route('quote.edit', [ 'quote' => $quote->id, 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ] ) }}" class="tooltipped" data-position="top" data-delay="50" data-tooltip="Edit Quote"><i class="material-icons">mode_edit</i></a>
                                            @endcan
                                            @can('delete', $quote)
                                            <a href="#" data-id="{{ $quote->id }}" class="quote-delete-btn tooltipped" data-position="top" data-delay="50" data-tooltip="Delete Quote"><i class="material-icons">delete</i></a>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="delete-confirmation" class="modal">
        <div class="modal-content">
            <p>Delete Quote?</p>
        </div>
        <div class="modal-footer">
            <form id="delete-quote-form" method="post" class="null-form">
                {{ method_field('DELETE') }}
                {{ csrf_field() }}
                <button class="modal-action waves-effect black-text waves-green btn-flat btn-deletemodal quote-confirm-delete-btn" type="submit">Delete</button>
            </form>
            <a href="javascript:;" class=" modal-action modal-close waves-effect black-text waves-red btn-flat btn-deletemodal">Cancel</a>
        </div>
    </div>
@stop

@section("scripts")
    <script type="text/javascript">
        "use strict";
        $(function() {
            Unicorn.initConfirmationTrigger('#quote-container', '.quote-delete-btn', '{{ \App\Library\Poowf\Unicorn::getCompanyKey() }}', 'quote', 'destroy', '#delete-confirmation', '#delete-quote-form');
            Unicorn.initPageSearch('#search-input', '#quote-container .single-quote-row');
        });
    </script>
@stop