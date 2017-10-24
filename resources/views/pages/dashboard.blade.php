@extends("layouts/default")

@section("head")
    <title>{{ config('app.name') }}</title>
    <style>
    </style>
@stop

@section("content")
    <div class="mini-container">
        <div class="row mtop30">
            <div class="col s12">
                <h2>Dashboard</h2>
            </div>
            <div class="col s12 m4">
                <h3>Welcome</h3>
                <div class="card-panel">
                    Hello {{ $user->name or '' }}
                </div>
            </div>
            <div class="col s12 m8">
                <h3>Overdue Invoices</h3>
                <div class="card-panel flex">
                    <table class="responsive-table">
                        <thead>
                        <tr>
                            <th>Invoice #</th>
                            <th>Client Company</th>
                            <th>Amount</th>
                            <th>Due Date</th>
                            <th>Actions</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($overdueinvoices as $invoice)
                            <tr>
                                <td>{{ $invoice->nice_invoice_id }}</td>
                                <td>{{ $invoice->client->companyname }}</td>
                                <td>{{ $invoice->calculatetotal() }}</td>
                                <td>{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $invoice->duedate)->format('j F, Y') }}</td>
                                <td>
                                    <a href="{{ route('invoice.show', [ 'invoice' => $invoice->id ])  }}" class="btn waves-effect waves-light">View</a>
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