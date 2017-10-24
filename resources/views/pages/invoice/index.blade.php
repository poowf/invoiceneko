@extends("layouts/default")

@section("head")
    <title>{{ config('app.name') }}</title>
    <style>
    </style>
@stop

@section("content")
    <div class="mini-container">
        <div class="row">
            <div class="col s6">
                <h3>Invoices</h3>
            </div>

            <div class="col s6 right mtop30">
                <a href="{{ route('invoice.create') }}" class="btn waves-effect waves-red">Create</a>
            </div>
        </div>
        <div class="row">
            <div class="col s12">
                <div class="card-panel flex">
                    <table id="invoice-container" class="responsive-table striped">
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
                            <tr>
                                <td>{{ $invoice->nice_invoice_id }}</td>
                                <td>${{ $invoice->calculatetotal()  }}</td>
                                <td>{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $invoice->duedate)->format('j F, Y') }}</td>
                                <td>{{ $invoice->client->companyname }}</td>
                                <td>{{ $invoice->client->contactphone }}</td>
                                <td>
                                    @if ($invoice->status == 0)
                                        <span class="alt-badge error">{{ $invoice->statustext() }}</span>
                                    @elseif ($invoice->status == 1)
                                        <span class="alt-badge success">{{ $invoice->statustext() }}</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('invoice.show', [ 'invoice' => $invoice->id ] ) }}"><i class="material-icons">remove_red_eye</i></a>
                                    <a href="{{ route('invoice.edit', [ 'invoice' => $invoice->id ] ) }}"><i class="material-icons">mode_edit</i></a>
                                    <a href="{{ route('invoice.history.show', [ 'invoice' => $invoice->id ] ) }}"><i class="material-icons">history</i></a>
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
            $('.modal').modal();

            $('#invoice-container').on('click', '.invoice-delete-btn', function (event) {
                event.preventDefault();
                var invoiceid = $(this).attr('data-id');
                $('#delete-invoice-form').attr('action', '/invoice/' + invoiceid + '/destroy');
                $('#delete-confirmation').modal('open');
            });
        });
    </script>
@stop