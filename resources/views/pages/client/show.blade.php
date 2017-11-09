@extends("layouts/default")

@section("head")
    <title>{{ config('app.name') }}</title>
    <style>
    </style>
@stop

@section("content")
    <div class="container">
        <div class="row">
            <div class="col s12">
                <h3>Client Details</h3>
            </div>
        </div>
        <div class="row">
            <div class="col s12">
                <div class="card-panel">
                    <dt>Company Name</dt>
                    <dd>{{ $client->companyname }}</dd>
                    <dt>Company Block</dt>
                    <dd>{{ $client->block or '-' }}</dd>
                    <dt>Company Street</dt>
                    <dd>{{ $client->street or '-' }}</dd>
                    <dt>Company Unit Number</dt>
                    <dd>{{ $client->unitnumber or '-' }}</dd>
                    <dt>Company Postal Code</dt>
                    <dd>{{ $client->postalcode or '-' }}</dd>
                    <dt>Company Nickname</dt>
                    <dd>{{ $client->nickname or '' }}</dd>
                    <dt>Company Registration Number</dt>
                    <dd>{{ $client->crn or '-' }}
                    <dt>Contact Name</dt>
                    <dd>{{ $client->contactname or '-' }}</dd>
                    <dt>Contact Email</dt>
                    <dd>{{ $client->contactemail or '-' }}</dd>
                    <dt>Contact Phone</dt>
                    <dd>{{ $client->contactphone or '-' }}</dd>
                    </dl>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col s12">
                <div class="card-panel">
                    <table id="invoice-container" class="responsive-table striped">
                        <thead>
                        <tr>
                            <th>Invoice ID</th>
                            <th>Date</th>
                            <th>Due Date</th>
                            <th>Terms</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($invoices as $key => $invoice)
                            <tr>
                                <td>{{ $invoice->nice_invoice_id }}</td>
                                <td>{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $invoice->date)->format('j F, Y') }}</td>
                                <td>{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $invoice->duedate)->format('j F, Y') }}</td>
                                <td>Net {{ $invoice->netdays }}</td>
                                <td>
                                    @if ($invoice->status == 0)
                                        <span class="alt-badge error">{{ $invoice->statustext() }}</span>
                                    @elseif ($invoice->status == 1)
                                        <span class="alt-badge success">{{ $invoice->statustext() }}</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('invoice.show', [ 'invoice' => $invoice->id ] ) }}"><i class="material-icons">open_in_new</i></a>
                                    <a href="{{ route('invoice.edit', [ 'invoice' => $invoice->id ] ) }}"><i class="material-icons">mode_edit</i></a>
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
@stop

@section("scripts")
    <script type="text/javascript">
        "use strict";
        $(function() {
        });
    </script>
@stop