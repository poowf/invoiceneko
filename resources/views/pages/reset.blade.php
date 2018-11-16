@extends("layouts.default", ['page_title' => 'Reset Password'])

@section("head")
    <style>
    </style>
@stop

@section("content")
    <div class="wrap v-center">
        <div class="container content-main-authentication">
            <div class="login-container">
                <div class="card-panel">
                    <div id="output"></div>
                    <div class="avatar">
                        <img src="{{ asset('assets/img/avatar.png') }}">
                    </div>
                    <hr>
                    <div class="form-box">
                        <form id="reset" method="post">
                            <div class="row">
                                <div class="input-field col s12">
                                    <input id="email" name="email" type="email" data-parsley-required="true" data-parsley-trigger="change">
                                    <label for="email" class="label-validation">Email</label>
                                    <span class="helper-text"></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-field col s12">
                                    <input id="password" name="password" type="password" data-parsley-required="true" data-parsley-trigger="change">
                                    <label for="password" class="label-validation">Password</label>
                                    <span class="helper-text"></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-field col s12">
                                    <input id="password_confirmation" name="password_confirmation" type="password" data-parsley-required="true" data-parsley-trigger="change">
                                    <label for="password" class="label-validation">Password Confirmation</label>
                                    <span class="helper-text"></span>
                                </div>
                            </div>
                            <input type="hidden" name="token" value="{{ $token }}">
                            {{ csrf_field() }}
                            <button class="btn btn-theme waves-effect waves-light full-width" type="submit" name="action">Reset Password</button>
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
            Unicorn.initParsleyValidation('#reset');
        });
    </script>
@stop