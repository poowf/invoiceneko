@extends("layouts.default", ['page_title' => 'Invoice | Archived'])

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
        #invoice-container {
            margin: 0px;
        }
    </style>
@stop

@section("content")
    <div class="wide-container">
        <div class="row">
            <div class="col s6">
                <h3>Archived Invoices</h3>
            </div>

            <div class="col s6 right mtop30">
            </div>
        </div>
        <div class="row">
            <div class="col s12">
                <div class="card-panel" style="padding: 2px;">
                    <input id="search-input" class="card-input" name="search-input" type="search" placeholder="Search">
                </div>

                <div id="invoice-container" class="row">
                    <div class="card-panel" >
                        <table id="invoices-table" class="responsive-table striped">
                            <thead>
                                <tr>
                                    <th>Invoice ID</th>
                                    <th>Amount</th>
                                    <th>Due Date</th>
                                    <th>Client Name</th>
                                    <th>Client Phone</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($invoices as $key => $invoice)
                                    <tr class="single-invoice-row">
                                        <td>{{ $invoice->nice_invoice_id }}</td>
                                        <td>${{ $invoice->totalmoneyformat  }}</td>
                                        <td>{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $invoice->duedate)->format('j F, Y') }}</td>
                                        <td>{{ $invoice->client->companyname }}</td>
                                        <td>{{ $invoice->client->contactphone }}</td>
                                        <td>
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
                                        </td>
                                        <td>
                                            <a href="{{ route('invoice.show', [ 'invoice' => $invoice->id ] ) }}" class="tooltipped" data-position="top" data-delay="50" data-tooltip="View Invoice"><i class="material-icons">remove_red_eye</i></a>
                                            <a href="{{ route('invoice.edit', [ 'invoice' => $invoice->id ] ) }}" class="tooltipped" data-position="top" data-delay="50" data-tooltip="Edit Invoice"><i class="material-icons">mode_edit</i></a>
                                            <a href="#" data-id="{{ $invoice->id }}" class="invoice-delete-btn tooltipped" data-position="top" data-delay="50" data-tooltip="Delete Invoice"><i class="material-icons">delete</i></a>
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
            <p>Delete Invoice?</p>
        </div>
        <div class="modal-footer">
            <form id="delete-invoice-form" method="post" class="null-form">
                {{ method_field('DELETE') }}
                {{ csrf_field() }}
                <button class="modal-action waves-effect black-text waves-green btn-flat btn-deletemodal invoice-confirm-delete-btn" type="submit">Delete</button>
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
            Unicorn.initConfirmationTrigger('#invoice-container', '.invoice-delete-btn', 'invoice', 'destroy', '#delete-confirmation', '#delete-invoice-form');
            Unicorn.initPageSearch('#search-input', '#invoice-container .single-invoice-row');
        });
    </script>
@stop