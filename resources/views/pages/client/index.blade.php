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
                <h3>Clients</h3>
            </div>
        </div>
        <div class="row">
            <div class="col s12">
                <div class="card-panel">
                    <table class="responsive-table striped">
                        <thead>
                        <tr>
                            <th>Company Name</th>
                            <th>Registration Number</th>
                            <th>Contact Name</th>
                            <th>Contact Email</th>
                            <th>Contact Phone</th>
                            <th>Action</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($clients as $key => $client)
                            <tr>
                                <td>{{ $client->companyname }}</td>
                                <td>{{ $client->crn }}</td>
                                <td>{{ $client->contactname }}</td>
                                <td>{{ $client->contactemail }}</td>
                                <td>{{ $client->contactphone }}</td>
                                <td>
                                    <a href="#"><i class="material-icons">open_in_new</i></a>
                                    <a href="#"><i class="material-icons">mode_edit</i></a>
                                    <a href="#"><i class="material-icons">delete</i></a>
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