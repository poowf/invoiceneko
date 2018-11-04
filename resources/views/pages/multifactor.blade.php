@extends("layouts.default", ['page_title' => 'Enter Multifactor Code'])

@section("head")
    <style>
    </style>
@stop

@section("content")
    <div class="wrap v-center">
        <div class="container content-main-authentication">
            <div class="login-container z-depth-1">
                <div class="avatar">
                    <img src="{{ asset('assets/img/avatar.png') }}" width="150" height="150">
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
            Unicorn.initParsleyValidation('#multifactor-auth');
        });
    </script>
@stop