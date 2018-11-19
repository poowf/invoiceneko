@extends("layouts.default", ['page_title' => 'Client | View'])

@section("head")
    <style>
    </style>
@stop

@section("content")
    <div class="container">
        <div class="row">
            <div class="col s6">
                <h3>Client Details</h3>
            </div>
            <div class="col s6 right">
                @can('create', \App\Models\Invoice::class)
                <a href="{{ route('client.invoice.create', [ 'client' => $client->id ]) }}" class="btn btn-link waves-effect waves-dark mtop30">Create Invoice</a>
                @endcan
            </div>
        </div>
        <div class="row">
            <div class="col s12">
                <div class="card-panel">
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
                        <dd>{{ $client->crn ?? '-' }}
                        <dt>Contact Name</dt>
                        <dd>{{ $client->contactname ?? '-' }}</dd>
                        <dt>Contact Email</dt>
                        <dd>{{ $client->contactemail ?? '-' }}</dd>
                        <dt>Contact Phone</dt>
                        <dd>{{ $client->contactphone ?? '-' }}</dd>
                    </dl>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col s12">
                <div class="card-panel flex">
                    <table id="invoice-container" class="responsive-table striped">
                        <thead>
                        <tr>
                            <th>Invoice ID</th>
                            <th>Date</th>
                            <th>Due Date</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($invoices as $key => $invoice)
                            <tr>
                                <td>{{ $invoice->nice_invoice_id }}</td>
                                <td>{{ $invoice->date->format('d F, Y') }}</td>
                                <td>{{ $invoice->duedate->format('d F, Y') }}</td>
                                <td>${{ $invoice->totalmoneyformat }}</td>
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
                                    <a href="{{ route('invoice.show', [ 'invoice' => $invoice->id ] ) }}"><i class="material-icons">remove_red_eye</i></a>
                                    @if(!$invoice->isLocked())<a href="{{ route('invoice.edit', [ 'invoice' => $invoice->id ] ) }}"><i class="material-icons">mode_edit</i></a>@endif
                                    <a href="#" data-id="{{ $invoice->id }}" class="invoice-delete-btn"><i class="material-icons">delete</i></a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
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
            Unicorn.initConfirmationTrigger('#invoice-container', '.invoice-delete-btn', 'invoice', 'destroy', '#delete-confirmation', '#delete-invoice-form');
        });
    </script>
@stop