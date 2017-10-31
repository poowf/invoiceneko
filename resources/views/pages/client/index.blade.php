@extends("layouts/default")

@section("head")
    <title>{{ config('app.name') }}</title>
    <style>
        mark {
            background: #fff2c3;
            color: inherit;
            padding: 5px;
        }
    </style>
@stop

@section("content")
    <div class="mini-container">
        <div class="row">
            <div class="col s6">
                <h3>Clients</h3>
            </div>

            <div class="col s6 right mtop30">
                <a href="{{ route('client.create') }}" class="btn waves-effect waves-red">Create</a>
            </div>
        </div>
        <div class="row">
            <div class="col s12">
                <div class="card-panel">
                    <div class="row">
                        <div class="input-field col s12">
                            <input id="search-input" name="search-input" type="text" placeholder="Search">
                            <label for="search-input" class="label-validation">Search</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col s12">
                <div class="card-panel flex">
                    <table id="client-container" class="responsive-table striped">
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
                            @if($clients)
                                @foreach($clients as $key => $client)
                                    <tr class="single-client-row">
                                        <td>{{ $client->companyname }}</td>
                                        <td>{{ $client->crn }}</td>
                                        <td>{{ $client->contactname }}</td>
                                        <td>{{ $client->contactemail }}</td>
                                        <td>{{ $client->contactphone }}</td>
                                        <td>
                                            <a href="{{ route('client.show', [ 'client' => $client ] ) }}"><i class="material-icons">remove_red_eye</i></a>
                                            <a href="{{ route('client.edit', [ 'client' => $client ] ) }}"><i class="material-icons">mode_edit</i></a>
                                            <a href="#" data-id="{{ $client->id }}" class="client-delete-btn"><i class="material-icons">delete</i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
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
            <form id="delete-client-form" method="post" class="null-form">
                {{ method_field('DELETE') }}
                {{ csrf_field() }}
                <button class="modal-action waves-effect black-text waves-green btn-flat btn-deletemodal client-confirm-delete-btn" type="submit">Delete</button>
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

            $('#client-container').on('click', '.client-delete-btn', function (event) {
                event.preventDefault();
                var clientid = $(this).attr('data-id');
                $('#delete-client-form').attr('action', '/client/' + clientid + '/destroy');
                $('#delete-confirmation').modal('open');
            });

            var inputBox = $('#search-input');
            var context = $('#client-container .single-client-row');

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