@extends("layouts/default")

@section("head")
    <title>{{ config('app.name') }}</title>
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
                <h3>Archived Quotes</h3>
            </div>

            <div class="col s6 right mtop30">
            </div>
        </div>
        <div class="row">
            <div class="col s12">
                <div class="card-panel" style="padding: 2px;">
                    <input id="search-input" class="card-input" name="search-input" type="text" placeholder="Search">
                </div>

                <div id="quote-container" class="row">
                    <div class="card-panel" >
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
                                        <td>{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $quote->duedate)->format('j F, Y') }}</td>
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
                                            <a href="{{ route('quote.show', [ 'quote' => $quote->id ] ) }}" class="tooltipped" data-position="top" data-delay="50" data-tooltip="View Quote"><i class="material-icons">remove_red_eye</i></a>
                                            <a href="{{ route('quote.edit', [ 'quote' => $quote->id ] ) }}" class="tooltipped" data-position="top" data-delay="50" data-tooltip="Edit Quote"><i class="material-icons">mode_edit</i></a>
                                            <a href="#" data-id="{{ $quote->id }}" class="quote-delete-btn tooltipped" data-position="top" data-delay="50" data-tooltip="Delete Quote"><i class="material-icons">delete</i></a>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mark.js/8.11.0/jquery.mark.min.js" integrity="sha256-1iYR6/Bs+CrdUVeCpCmb4JcYVWvvCUEgpSU7HS5xhsY=" crossorigin="anonymous"></script>
    <script type="text/javascript">
        "use strict";
        $(function() {
            $('.modal').modal();

            $('#quote-container').on('click', '.quote-delete-btn', function (event) {
                event.preventDefault();
                var quoteid = $(this).attr('data-id');
                $('#delete-quote-form').attr('action', '/quote/' + quoteid + '/destroy');
                $('#delete-confirmation').modal('open');
            });

            var inputBox = $('#search-input');
            var context = $('#quote-container .single-quote-row');

            inputBox.on("input", function() {
                var term = $(this).val();
                context.unmark().show();
                if (term != "") {
                    console.log(term);
                    context.mark(term, {
                        done: function() {
                            context.not(":has(mark)").hide();
                        }
                    });
                }
            });
        });
    </script>
@stop