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
                    @if($user->twofa_timestamp)
                        <form id="security" method="post" enctype="multipart/form-data">
                            <div class="row">
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
                            </div>
                        </form>

                        <form id="regenerate-2fa-codes" method="post" enctype="multipart/form-data" action="{{ route('user.multifactor.regenerate_codes') }}">
                            <div class="row">
                                <label for="multifactor-auth" class="label-validation">Multifactor Authentication</label>
                                <div class="input-field col s12">
                                    {{ csrf_field() }}
                                    <button class="btn waves-effect waves-light" type="submit" name="action">Regenerate Backup Codes</button>
                                </div>
                            </div>
                        </form>
                    @else
                    <form id="enable-2fa" method="post" enctype="multipart/form-data" action="{{ route('user.multifactor.start') }}">
                        <div class="row">
                            <div class="input-field col s12">
                                <label for="multifactor-auth" class="label-validation">Multifactor Authentication</label>
                                {{ csrf_field() }}
                                <button class="btn waves-effect waves-light mtop20" type="submit" name="action">Enable Multifactor Auth</button>
                            </div>
                        </div>
                    </form>
                    @endif
                </div>
            </div>
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
            <div class="modal-content">
                <p>Disable Multifactor Authentication?</p>
            </div>
            <div class="modal-footer">
                <form id="disable-user-form" method="post" class="null-form" action="{{ route('user.multifactor.destroy') }}">
                    {{ method_field('DELETE') }}
                    {{ csrf_field() }}
                    <button class="modal-action waves-effect black-text waves-green btn-flat btn-disablemodal user-confirm-disable-btn" type="submit">Disable</button>
                </form>
                <a href="javascript:;" class=" modal-action modal-close waves-effect black-text waves-red btn-flat btn-disablemodal">Cancel</a>
            </div>
        </div>
    @endif
@stop

@section("scripts")
    <script type="text/javascript">
        "use strict";
        $(function() {
            @if(session()->has('codes'))
                $('#recovery-codes').modal('open');
            @endif
            @if($user->twofa_timestamp)
            $('#security').on('change', '#multifactor-auth', function (event) {
                if(!$(this).is(':checked'))
                {
                    event.preventDefault();
                    $('#disable-user-form').attr('action', "{{ route('user.multifactor.destroy') }}");
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