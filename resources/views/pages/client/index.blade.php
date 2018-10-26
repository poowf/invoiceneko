@extends("layouts/default")

@section("head")
    <title>{{ config('app.name') }}</title>
    <style>
        .single-client-card .card-content {
            height: 130px;
            overflow: hidden;
        }

        .single-client-card .card-content .card-title {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            -webkit-transition: 2s;
            -moz-transition: 2s;
            transition: 2s;
            -webkit-transition-timing-function: linear;
            -moz-transition-timing-function: linear;
            transition-timing-function: linear;
            max-height: 32px;
        }

        .single-client-card .card-content .card-title:hover {
            margin-left: -50%;
        }
    </style>
@stop

@section("content")
    <div class="wide-container">
        <div class="row">
            <div class="col s6">
                <h3>Clients</h3>
            </div>

            <div class="col s6 right mtop30">
                <a href="{{ route('client.create') }}" class="btn waves-effect waves-dark">Create</a>
            </div>
        </div>
        <div class="row">
            <div class="col s12">
                <div class="card-panel" style="padding: 2px;">
                    <input id="search-input" class="card-input" name="search-input" type="text" placeholder="Search">
                </div>
            </div>
        </div>
        <div id="client-container" class="">
            @if($clients)
                <div class="row">
                    <div class="col s12">
                        @foreach($clients as $key => $client)
                            <div class="col s12 m4 l4 xl3 single-client-card">
                                <div class="card">
                                    <div class="card-image waves-effect waves-block waves-light">
                                        <img class="activator" src="{{ \App\Library\Poowf\Unicorn::getStorageFile($client->logo, [250,250]) }}">
                                    </div>
                                    <div class="card-content">
                                        <span class="card-title activator grey-text text-darken-4">@if($client->nickname) {{ $client->nickname }} @else {{ $client->companyname }} @endif</span><i class="material-icons right">more_vert</i>
                                        <p><a href="#">{{ $client->companyname }}</a></p>
                                    </div>
                                    <div class="card-reveal">
                                        <span class="card-title grey-text text-darken-4">Company Details<i class="material-icons right">close</i></span>
                                        <dl>
                                            <dt>Name</dt>
                                            <dd>{{ $client->companyname }}</dd>
                                            <dt>Registration Number</dt>
                                            <dd>{{ $client->crn }}</dd>
                                            <dt>Contact Name</dt>
                                            <dd>{{ $client->contactname }}</dd>
                                            <dt>Contact Email</dt>
                                            <dd>{{ $client->contactemail }}</dd>
                                            <dt>Contact Phone</dt>
                                            <dd>{{ $client->contactphone }}</dd>
                                        </dl>
                                        <span class="card-title grey-text text-darken-4 mtop20">Actions</span>
                                        <a href="{{ route('client.show', [ 'client' => $client ] ) }}" class="btn btn-theme full-width mbth5">More Info</a>
                                        <a href="{{ route('client.edit', [ 'client' => $client ] ) }}" class="btn btn-theme full-width mbth5">Edit</a>
                                        <a href="#" data-id="{{ $client->id }}" class="btn btn-theme client-delete-btn full-width mbth5">Delete</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

            @endif
        </div>
    </div>

    <div id="delete-confirmation" class="modal">
        <div class="modal-content">
            <p>Delete Client?</p>
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
            var context = $('#client-container .single-client-card');

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