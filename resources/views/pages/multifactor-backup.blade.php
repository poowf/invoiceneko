@extends("layouts.default", ['page_title' => 'Enter Backup Code'])

@section("head")
    <style>
    </style>
@stop

@section("content")
    <div class="wrap v-center">
        <div class="container content-main-authentication">
            <div class="login-container">
                <div class="card-panel">
                    <div class="hero-logo-container circle">
                        <img src="{{ asset('assets/img/logo.svg') }}" class="hero-logo-image">
                    </div>
                    <hr class="mtop20"><br>
                    <div class="form-box">
                        <form id="multifactor-auth" method="post" action="{{ route('user.multifactor.backup_validate') }}">
                            <div class="input-field col s12">
                                <input id="multifactor-backup-code" name="multifactor-backup-code" type="text" data-parsley-required="true" data-parsley-trigger="change" placeholder="Code">
                                <label for="multifactor-backup-code" class="label-validation">Code</label>
                                <span class="helper-text"></span>
                            </div>
                            <p>Enter your backup code</p>
                            {{ csrf_field() }}
                            <button class="btn btn-link waves-effect waves-light full-width" type="submit" name="action">Verify</button>
                        </form>
                    </div>
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