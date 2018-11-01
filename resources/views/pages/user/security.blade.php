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
                <h3>Security</h3>
            </div>
        </div>
        <div class="row">
            <div class="col s12 m3 xl2">
                @include("partials/sidenav-user")
            </div>
            <div class="col s12 m9 xl10">
                <div class="card-panel">
                    @if($user->twofa_secret)
                        <form id="security" method="post" enctype="multipart/form-data">
                            <div class="row">
                                <label for="multifactor-auth" class="label-validation">Two Factor Authentication</label>
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
                    @else
                    <form id="enable-2fa" method="post" enctype="multipart/form-data" action="{{ route('user.multifactor.start') }}">
                        <div class="row">
                            <label for="multifactor-auth" class="label-validation">Two Factor Authentication</label>
                            <div class="input-field col s12">
                                {{ csrf_field() }}
                                <button class="btn waves-effect waves-light" type="submit" name="action">Enable 2 FA</button>
                            </div>
                        </div>
                    </form>
                    @endif
                    @if($codes)
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div id="recovery-codes" class="modal mini-modal center">
        <div class="modal-title pall10 theme-color">
            Recovery Codes
        </div>
        <div class="pall10 blue-grey lighten-5">
            @foreach($codes as $code)
                <p class="box-shadow">{{ $code }}</p>
            @endforeach
        </div>
        <div class="modal-footer">
            <a href="javascript:;" class=" modal-action modal-close waves-effect black-text waves-red btn-flat btn-disablemodal">Cancel</a>
        </div>
    </div>
    <div id="disable-confirmation" class="modal">
        <div class="modal-content">
            <p>Disable Two Factor Authentication?</p>
        </div>
        <div class="modal-footer">
            <form id="disable-user-form" method="post" class="null-form">
                {{ method_field('DELETE') }}
                {{ csrf_field() }}
                <button class="modal-action waves-effect black-text waves-green btn-flat btn-disablemodal user-confirm-disable-btn" type="submit">Disable</button>
            </form>
            <a href="javascript:;" class=" modal-action modal-close waves-effect black-text waves-red btn-flat btn-disablemodal">Cancel</a>
        </div>
    </div>
@stop

@section("scripts")
    <script type="text/javascript">
        "use strict";
        $(function() {
            @if($codes)
                $('.modal').modal();
                $('#recovery-codes').modal('open');
            @endif
            @if($user->twofa_secret)
            $('.modal').modal();
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