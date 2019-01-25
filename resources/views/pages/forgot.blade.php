@extends("layouts.default", ['page_title' => 'Forgot Password'])

@section("head")
    <style>
    </style>
@stop

@section("content")
    <div class="wrap v-center">
        <div class="container content-main-authentication">
            <div class="login-container">
                <div class="card-panel">
                    <div class="avatar">
                        <img src="{{ asset('assets/img/avatar.png') }}">
                    </div>
                    <hr>
                    <div class="form-box">
                        <form id="forgot" method="post">
                            <div class="row">
                                <div class="input-field col s12">
                                    <input id="email" name="email" type="email" data-parsley-required="true" data-parsley-trigger="change">
                                    <label for="email" class="label-validation">Email</label>
                                </div>
                            </div>
                            {{ csrf_field() }}
                            <button class="btn btn-theme waves-effect waves-light full-width" type="submit" name="action">Send Reset Link</button>
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
            Unicorn.initParsleyValidation('#forgot');
        });
    </script>
@stop