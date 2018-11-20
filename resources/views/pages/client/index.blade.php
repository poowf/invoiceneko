@extends("layouts.default", ['page_title' => 'Clients'])

@section("head")
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
                @can('create', \App\Models\Client::class)
                <a href="{{ route('client.create', [ 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ]) }}" class="btn waves-effect waves-dark">Create</a>
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
        <div id="client-container" class="">
            @if($clients)
                <div class="row">
                    <div class="col s12">
                        @foreach($clients as $key => $client)
                            <div class="col s12 m4 l4 xl3 single-client-card">
                                <div class="card">
                                    <div class="card-image waves-effect waves-block waves-light">
                                        <img class="activator responsive-img" src="{{ \App\Library\Poowf\Unicorn::getStorageFile($client->logo, [250,250]) }}">
                                    </div>
                                    <div class="card-content">
                                        <span class="card-title activator grey-text text-darken-4">@if($client->nickname) {{ $client->nickname }} @else {{ $client->companyname }} @endif</span><i class="activator material-icons right" style="cursor: pointer;">more_vert</i>
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
                                        @can('view', $client)
                                        <a href="{{ route('client.show', [ 'client' => $client, 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ] ) }}" class="btn btn-theme full-width mbth5">More Info</a>
                                        @endcan
                                        @can('update', $client)
                                        <a href="{{ route('client.edit', [ 'client' => $client, 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ] ) }}" class="btn btn-theme full-width mbth5">Edit</a>
                                        @endcan
                                        @can('delete', $client)
                                        <a href="#" data-id="{{ $client->id }}" class="btn btn-theme client-delete-btn full-width mbth5">Delete</a>
                                        @endcan
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
    <script type="text/javascript">
        "use strict";
        $(function() {
            Unicorn.initConfirmationTrigger('#client-container', '.client-delete-btn', 'client', 'destroy', '#delete-confirmation', '#delete-client-form');
            Unicorn.initPageSearch('#search-input', '#client-container .single-client-card');
        });
    </script>
@stop