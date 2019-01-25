@extends("layouts.default", ['page_title' => 'Invoice | Archived'])

@section("head")
    <style>
    </style>
@stop

@section("content")
    <div class="wide-container invoice-wrapper">
        <div class="row">
            <div class="col s6">
                <h3>Archived Invoices</h3>
            </div>
            <div class="col s6 right mtop30">
            </div>
        </div>
        <div class="row">
            <div class="col s12">
                <div class="card-panel search-panel">
                    <input id="search-input" class="card-input" name="search-input" type="search" placeholder="Search">
                </div>

                <div id="invoice-container" class="row mall0">
                    <div class="card-panel flex">
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
                                        <td>{{ $invoice->duedate->format('d F, Y') }}</td>
                                        <td>{{ $invoice->getClient()->companyname }}</td>
                                        <td>{{ $invoice->getClient()->contactphone }}</td>
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
                                            <a href="{{ route('invoice.show', [ 'invoice' => $invoice, 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ] ) }}" class="tooltipped" data-position="top" data-tooltip="View Invoice"><i class="material-icons">remove_red_eye</i></a>
                                            @if(!$invoice->isLocked())<a href="{{ route('invoice.edit', [ 'invoice' => $invoice, 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ] ) }}" class="tooltipped" data-position="top" data-tooltip="Edit Invoice"><i class="material-icons">mode_edit</i></a>@endif
                                            <a href="#" data-id="{{ $invoice->id }}" class="invoice-delete-btn tooltipped" data-position="top" data-tooltip="Delete Invoice"><i class="material-icons">delete</i></a>
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
    <script type="text/javascript">
        "use strict";
        $(function() {
            Unicorn.initConfirmationTrigger('#invoice-container', '.invoice-delete-btn', '{{ \App\Library\Poowf\Unicorn::getCompanyKey() }}', 'invoice', 'destroy', '#delete-confirmation', '#delete-invoice-form');
            Unicorn.initPageSearch('#search-input', '#invoice-container .single-invoice-row');
        });
    </script>
@stop