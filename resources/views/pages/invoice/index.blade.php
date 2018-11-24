@extends("layouts.default", ['page_title' => 'Invoices'])

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
    </style>
@stop

@section("content")
    <div class="wide-container">
        <div class="row">
            <div class="col s6">
                <h3>Invoices</h3>
            </div>

            <div class="col s6 right mtop30">
                @can('create', \App\Models\Invoice::class)
                <a href="{{ route('invoice.create', [ 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ]) }}" class="btn btn-link waves-effect waves-dark">Create</a>
                <a href="{{ route('invoice.adhoc.create', [ 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ]) }}" class="btn btn-link waves-effect waves-dark">Create Ad-Hoc</a>
                @endcan
                @can('index', \App\Models\Receipt::class)
                    <a href="{{ route('receipt.index', [ 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ]) }}" class="btn btn-link waves-effect waves-dark">Receipts</a>
                @endcan
                @can('index', \App\Models\Invoice::class)
                <a href="{{ route('invoice.index.archived', [ 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ]) }}" class="btn btn-link waves-effect waves-dark">Archived Invoices</a>
                @endcan
            </div>
        </div>
        <div class="row">
            <div class="col s12">
                <div class="card-panel search-panel">
                    <input id="search-input" class="card-input" name="search-input" type="search" placeholder="Search">
                </div>
            </div>
        </div>
        <div id="invoice-container" class="row">
            <div class="col s12">
                <ul class="tabs tabs-fixed-width">
                    <li class="tab col s3"><a class="active" href="#invoice-overdue">Overdue</a></li>
                    <li class="tab col s3"><a href="#invoice-pending">Pending</a></li>
                    <li class="tab col s3"><a href="#invoice-draft">Draft</a></li>
                    <li class="tab col s3"><a href="#invoice-paid">Paid</a></li>
                </ul>
            </div>
            <div id="invoice-overdue" class="col s12">
                <div class="card-panel tab-panel flex">
                    <table id="overdue-container" class="responsive-table striped">
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
                            @if($overdue)
                                @foreach($overdue as $key => $invoice)
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
                                            @can('view', $invoice)
                                            <a href="{{ route('invoice.show', [ 'invoice' => $invoice, 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ] ) }}" class="tooltipped" data-position="top" data-delay="50" data-tooltip="View Invoice"><i class="material-icons">remove_red_eye</i></a>
                                            @endcan
                                            @can('update', $invoice)
                                            @if(!$invoice->isLocked())<a href="@if(is_null($invoice->client_id)){{ route('invoice.adhoc.edit', [ 'invoice' => $invoice, 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ] ) }}@else{{ route('invoice.edit', [ 'invoice' => $invoice, 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ] ) }}@endif" class="tooltipped" data-position="top" data-delay="50" data-tooltip="Edit Invoice"><i class="material-icons">mode_edit</i></a>@endif
                                            @endcan
                                            @can('update', $invoice)
                                            <form method="post" action="{{ route('invoice.duplicate', [ 'invoice' => $invoice, 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ] ) }}" class="null-form tooltipped" data-position="top" data-delay="50" data-tooltip="Duplicate Invoice">
                                                {{ csrf_field() }}
                                                <button class="null-btn" type="submit"><i class="material-icons">control_point_duplicate</i></button>
                                            </form>
                                            @endcan
                                            @can('view', $invoice)
                                            <a href="{{ route('invoice.history.show', [ 'invoice' => $invoice, 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ] ) }}" class="tooltipped" data-position="top" data-delay="50" data-tooltip="Invoice History"><i class="material-icons">history</i></a>
                                            @endcan
                                            @can('delete', $invoice)
                                            <a href="#" data-id="{{ $invoice->id }}" class="invoice-delete-btn tooltipped" data-position="top" data-delay="50" data-tooltip="Delete Invoice"><i class="material-icons">delete</i></a>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <div id="invoice-pending" class="col s12">
                <div class="card-panel tab-panel flex">
                    @if($pending)
                        <table id="pending-container" class="responsive-table striped">
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
                        @foreach($pending as $key => $invoice)
                            <tr class="single-invoice-row">
                                <td>{{ $invoice->nice_invoice_id }}</td>
                                <td>${{ $invoice->totalmoneyformat }}</td>
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
                                    @can('view', $invoice)
                                    <a href="{{ route('invoice.show', [ 'invoice' => $invoice, 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ] ) }}" class="tooltipped" data-position="top" data-delay="50" data-tooltip="View Invoice"><i class="material-icons">remove_red_eye</i></a>
                                    @endcan
                                    @can('update', $invoice)
                                    @if(!$invoice->isLocked())<a href="@if(is_null($invoice->client_id)){{ route('invoice.adhoc.edit', [ 'invoice' => $invoice, 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ] ) }}@else{{ route('invoice.edit', [ 'invoice' => $invoice, 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ] ) }}@endif" class="tooltipped" data-position="top" data-delay="50" data-tooltip="Edit Invoice"><i class="material-icons">mode_edit</i></a>@endif
                                    @endcan
                                    @can('update', $invoice)
                                    <form method="post" action="{{ route('invoice.duplicate', [ 'invoice' => $invoice, 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ] ) }}" class="null-form tooltipped" data-position="top" data-delay="50" data-tooltip="Duplicate Invoice">
                                        {{ csrf_field() }}
                                        <button class="null-btn" type="submit"><i class="material-icons">control_point_duplicate</i></button>
                                    </form>
                                    @endcan
                                    @can('view', $invoice)
                                    <a href="{{ route('invoice.history.show', [ 'invoice' => $invoice, 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ] ) }}" class="tooltipped" data-position="top" data-delay="50" data-tooltip="Invoice History"><i class="material-icons">history</i></a>
                                    @endcan
                                    @can('delete', $invoice)
                                    <a href="#" data-id="{{ $invoice->id }}" class="invoice-delete-btn tooltipped" data-position="top" data-delay="50" data-tooltip="Delete Invoice"><i class="material-icons">delete</i></a>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    @endif
                </div>
            </div>
            <div id="invoice-draft" class="col s12">
                <div class="card-panel tab-panel flex">
                    @if($draft)
                        <table id="draft-container" class="responsive-table striped">
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
                        @foreach($draft as $key => $invoice)
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
                                    @can('view', $invoice)
                                    <a href="{{ route('invoice.show', [ 'invoice' => $invoice, 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ] ) }}" class="tooltipped" data-position="top" data-delay="50" data-tooltip="View Invoice"><i class="material-icons">remove_red_eye</i></a>
                                    @endcan
                                    @can('update', $invoice)
                                    @if(!$invoice->isLocked())<a href="@if(is_null($invoice->client_id)){{ route('invoice.adhoc.edit', [ 'invoice' => $invoice, 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ] ) }}@else{{ route('invoice.edit', [ 'invoice' => $invoice, 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ] ) }}@endif" class="tooltipped" data-position="top" data-delay="50" data-tooltip="Edit Invoice"><i class="material-icons">mode_edit</i></a>@endif
                                    @endcan
                                    @can('update', $invoice)
                                    <form method="post" action="{{ route('invoice.duplicate', [ 'invoice' => $invoice, 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ] ) }}" class="null-form tooltipped" data-position="top" data-delay="50" data-tooltip="Duplicate Invoice">
                                        {{ csrf_field() }}
                                        <button class="null-btn" type="submit"><i class="material-icons">control_point_duplicate</i></button>
                                    </form>
                                    @endcan
                                    @can('view', $invoice)
                                    <a href="{{ route('invoice.history.show', [ 'invoice' => $invoice, 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ] ) }}" class="tooltipped" data-position="top" data-delay="50" data-tooltip="Invoice History"><i class="material-icons">history</i></a>
                                    @endcan
                                    @can('delete', $invoice)
                                    <a href="#" data-id="{{ $invoice->id }}" class="invoice-delete-btn tooltipped" data-position="top" data-delay="50" data-tooltip="Delete Invoice"><i class="material-icons">delete</i></a>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    @endif
                </div>
            </div>
            <div id="invoice-paid" class="col s12">
                <div class="card-panel tab-panel flex">
                    @if($paid)
                        <table id="paid-container" class="responsive-table striped">
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
                        @foreach($paid as $key => $invoice)
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
                                    @can('view', $invoice)
                                    <a href="{{ route('invoice.show', [ 'invoice' => $invoice, 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ] ) }}" class="tooltipped" data-position="top" data-delay="50" data-tooltip="View Invoice"><i class="material-icons">remove_red_eye</i></a>
                                    @endcan
                                    @can('update', $invoice)
                                    @if(!$invoice->isLocked())<a href="@if(is_null($invoice->client_id)){{ route('invoice.adhoc.edit', [ 'invoice' => $invoice, 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ] ) }}@else{{ route('invoice.edit', [ 'invoice' => $invoice, 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ] ) }}@endif" class="tooltipped" data-position="top" data-delay="50" data-tooltip="Edit Invoice"><i class="material-icons">mode_edit</i></a>@endif
                                    @endcan
                                    @can('update', $invoice)
                                    <form method="post" action="{{ route('invoice.duplicate', [ 'invoice' => $invoice, 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ] ) }}" class="null-form tooltipped" data-position="top" data-delay="50" data-tooltip="Duplicate Invoice">
                                        {{ csrf_field() }}
                                        <button class="null-btn" type="submit"><i class="material-icons">control_point_duplicate</i></button>
                                    </form>
                                    @endcan
                                    @can('view', $invoice)
                                    <a href="{{ route('invoice.history.show', [ 'invoice' => $invoice, 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ] ) }}" class="tooltipped" data-position="top" data-delay="50" data-tooltip="Invoice History"><i class="material-icons">history</i></a>
                                    @endcan
                                    @can('delete', $invoice)
                                    <a href="#" data-id="{{ $invoice->id }}" class="invoice-delete-btn tooltipped" data-position="top" data-delay="50" data-tooltip="Delete Invoice"><i class="material-icons">delete</i></a>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    @endif
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
            Unicorn.initConfirmationTrigger('#invoice-container', '.invoice-delete-btn', '{{ \App\Library\Poowf\Unicorn::getCompanyKey() }}', 'invoice', 'destroy', '#delete-confirmation', '#delete-invoice-form');
            Unicorn.initPageSearch('#search-input', '#invoice-container .single-invoice-row');
        });
    </script>
@stop