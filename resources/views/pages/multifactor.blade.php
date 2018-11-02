@extends("layouts.default")

@section("head")
    <title>{{ config('app.name') }} | Sign In</title>
    <style>
    </style>
@stop

@section("content")
    <div class="wrap v-center">
        <div class="container content-main-authentication">
            <div class="login-container z-depth-1">
                <div class="avatar">
                    <img src="{{ asset('assets/img/avatar-alt.png') }}" width="150" height="150">
                </div>
                <hr><br>
                <div class="form-box">
                    <form id="multifactor-auth" method="post" action="{{ route('auth.multifactor.validate') }}">
                        <div class="input-field col s12">
                            <input id="multifactor_code" name="multifactor_code" type="number" data-parsley-required="true" data-parsley-trigger="change" placeholder="Code">
                            <label for="multifactor_code" class="label-validation">Code</label>
                            <span class="helper-text"></span>
                        </div>
                        <p>Forgot your code?<br><a href="{{ route('user.multifactor.backup') }}">Click here to Use a backup code</a></p>
                        {{ csrf_field() }}
                        <button class="btn btn-link waves-effect waves-light full-width" type="submit" name="action">Verify</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section("scripts")
    <script>
        "use strict";
        $(function() {
            $('#multifactor-auth').parsley({
                successClass: 'valid',
                errorClass: 'invalid',
                errorsContainer: function (velem) {
                    let $errelem = velem.$element.siblings('span.helper-text');
                    $errelem.attr('data-error', window.Parsley.getErrorMessage(velem.validationResult[0].assert));
                    return true;
                },
                errorsWrapper: '',
                errorTemplate: ''
            })
                .on('field:validated', function(velem) {

                })
                .on('field:success', function(velem) {

                })
                .on('field:error', function(velem) {

                });
        });
    </script>
@stop