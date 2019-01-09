@extends("layouts.default", ['page_title' => 'User | Security'])

@section("head")
    <style>
    </style>
@stop

@section("content")
    <div class="container">
        <div class="row">
            <div class="col s12">
                <h3>Security</h3>
            </div>
        </div>
        <div class="row">
            <div class="col s12 m3 xl2">
                @include("partials/sidenav-user")
            </div>
            <div class="col s12 m9 xl10">
                <div class="card-panel">
                    <div class="row">
                        <div class="col s12">
                            @if($user->twofa_timestamp)
                                <form id="security" method="post" enctype="multipart/form-data">
                                    <label for="multifactor-auth" class="label-validation">Multifactor Authentication</label>
                                    <div class="switch">
                                        <label>
                                            Off
                                            <input id="multifactor-auth" name="multifactor-auth" type="checkbox" checked>
                                            <span class="lever"></span>
                                            On
                                        </label>
                                        <span class="helper-text"></span>
                                    </div>
                                </form>
                                <form id="regenerate-2fa-codes" method="post" enctype="multipart/form-data" action="{{ route('user.multifactor.regenerate_codes', [ 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ]) }}" class="mtop30">
                                    <div class="input-field">
                                        <label for="multifactor-auth" class="label-validation">Multifactor Authentication</label>
                                        {{ csrf_field() }}
                                        <button class="btn btn-link waves-effect waves-light null-btn mtop10" type="submit" name="action">Regenerate Backup Codes</button>
                                    </div>
                                </form>
                            @else
                                <form id="enable-2fa" method="post" enctype="multipart/form-data" action="{{ route('user.multifactor.start', [ 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ]) }}" class="null-form">
                                    <div class="input-field">
                                        <label for="multifactor-auth" class="label-validation">Multifactor Authentication</label>
                                        {{ csrf_field() }}
                                        <button class="btn btn-link waves-effect waves-light null-btn mtop10" type="submit" name="action">Enable Multifactor Auth</button>
                                    </div>
                                </form>
                            @endif
                        </div>
                        <div class="col s12 mtop20">
                            <h6>Sessions</h6>
                            <ul id="session-container" class="collection">
                                @foreach($sessions as $session)
                                <li class="collection-item avatar">
                                    <i class="material-icons circle blue-grey">@if($session->isPhone()){{ 'smartphone' }}@else{{ 'computer' }}@endif</i>
                                    <span class="title">{{ $session->platform_name }} @if(session()->getId() == $session->id)<span class="alt-badge info mleft15">{{ 'Current Session' }}</span>@endif</span>
                                    <p>
                                        {{ $session->ip_address }}<br>
                                        {{ $session->last_activity }}
                                    </p>
                                    @if(session()->getId() != $session->id)<a href="#" data-id="{{ $session->id }}" class="secondary-content session-delete-btn tooltipped" data-position="top" data-tooltip="Clear Session"><i class="material-icons">clear</i></a>@endif
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="delete-confirmation" class="modal">
        <div class="modal-content">
            <p>Clear Session?</p>
        </div>
        <div class="modal-footer">
            <form id="delete-session-form" method="post" class="null-form">
                {{ method_field('DELETE') }}
                {{ csrf_field() }}
                <button class="modal-action waves-effect black-text waves-green btn-flat btn-deletemodal session-confirm-delete-btn" type="submit">Yes</button>
            </form>
            <a href="javascript:;" class=" modal-action modal-close waves-effect black-text waves-red btn-flat btn-deletemodal">No</a>
        </div>
    </div>
    @if(session()->has('codes'))
        @if($codes = session()->get('codes'))
            <div id="recovery-codes" class="modal mini-modal center">
                <div class="modal-title pall10 theme-color white-text">
                    <h5>Recovery Codes</h5>
                </div>
                <div class="modal-content">
                    <ul class="collection">
                        @foreach($codes as $code)
                            <li class="collection-item">{{ $code }}</li>
                        @endforeach
                    </ul>
                </div>
                <div class="modal-footer">
                    <a href="javascript:;" class=" modal-action modal-close waves-effect black-text waves-red btn-flat btn-disablemodal">Close</a>
                </div>
            </div>
        @endif
    @endif
    @if($user->twofa_timestamp)
        <div id="disable-confirmation" class="modal mini-modal">
            <form id="disable-user-form" method="post" class="null-form" action="{{ route('user.multifactor.destroy', [ 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ]) }}">
                <div class="modal-content">
                    <p>Disable Multifactor Authentication?</p>
                    <div class="row">
                        <div class="input-field col s12">
                            <input id="multifactor_code" name="multifactor_code" type="number" data-parsley-required="true" data-parsley-trigger="change" placeholder="Code">
                            <label for="multifactor_code" class="label-validation">Code</label>
                            <span class="helper-text"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                        {{ method_field('DELETE') }}
                        {{ csrf_field() }}
                        <button class="modal-action waves-effect black-text waves-green btn-flat btn-disablemodal user-confirm-disable-btn" type="submit">Disable</button>
                    <a href="javascript:;" class=" modal-action modal-close waves-effect black-text waves-red btn-flat btn-disablemodal">Cancel</a>
                </div>
            </form>
        </div>
    @endif
@stop

@section("scripts")
    <script type="text/javascript">
        "use strict";
        $(function() {
            Unicorn.initConfirmationTrigger('#session-container', '.session-delete-btn', '{{ \App\Library\Poowf\Unicorn::getCompanyKey() }}', 'user/session', 'destroy', '#delete-confirmation', '#delete-session-form');

            @if(session()->has('codes'))
                $('#recovery-codes').modal('open');
            @endif
            @if($user->twofa_timestamp)
            $('#security').on('change', '#multifactor-auth', function (event) {
                if(!$(this).is(':checked'))
                {
                    event.preventDefault();
                    $('#disable-user-form').attr('action', "{{ route('user.multifactor.destroy', [ 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ]) }}");
                    $('#disable-confirmation').modal('open');
                }
                else
                {
                    location.reload();
                }
            });

            $('#disable-confirmation').on('click', '.modal-close', function (event) {
                $('#multifactor-auth').prop('checked', true);
            });
            @endif
        });
    </script>
@stop