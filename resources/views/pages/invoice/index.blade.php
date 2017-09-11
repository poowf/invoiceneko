@extends("layouts/default")

@section("head")
    <title>Invoice Plz</title>
    <style>
    </style>
@stop

@section("content")
    <div class="container">
        <div class="row">
            <div class="col s12">
                <h3>Invoices</h3>
            </div>
        </div>
        <div class="row">
            <div class="col s12">
                <div class="card-panel">
                    <table class="responsive-table striped">
                        <thead>
                        <tr>
                            <th>Invoice ID</th>
                            <th>Date</th>
                            <th>Due Date</th>
                            <th>Client Name</th>
                            <th>Client Email</th>
                            <th>Client Phone</th>
                            <th>Action</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($invoices as $key => $invoice)
                            <tr>
                                <td>{{ $invoice->invoiceid }}</td>
                                <td>{{ $invoice->date }}</td>
                                <td>{{ $invoice->duedate }}</td>
                                <td>{{ $invoice->client->contactname }}</td>
                                <td>{{ $invoice->client->contactemail }}</td>
                                <td>{{ $invoice->client->contactphone }}</td>
                                <td>
                                    <a href="{{ route('invoice.show', [ 'invoice' => $invoice->id ] ) }}"><i class="material-icons">open_in_new</i></a>
                                    <a href="{{ route('invoice.edit', [ 'invoice' => $invoice->id ] ) }}"><i class="material-icons">mode_edit</i></a>
                                    <form method="post" action="{{ route('invoice.destroy', [ 'invoice' => $invoice->id ] ) }}" class="null-form">
                                        {{ method_field('DELETE') }}
                                        {{ csrf_field() }}
                                        <button class="null-btn" type="submit"><i class="material-icons">delete</i></button>
                                    </form>
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