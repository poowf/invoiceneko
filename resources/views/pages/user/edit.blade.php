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
                <h3>Settings</h3>
            </div>
        </div>
        <div class="row">
            <div class="col s12 m3 xl2">
                @include("partials/sidenav")
            </div>
            <div class="col s12 m9 xl10">
                <form id="edit-profile" method="post" enctype="multipart/form-data">
                    <div class="card-panel">
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="name" name="name" type="text" data-parsley-required="true" data-parsley-trigger="change" data-parsley-minlength="4" value="{{ $user->name or '' }}" placeholder="Name">
                                <label for="name" class="label-validation">Name</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="username" name="username" type="text" data-parsley-required="true" data-parsley-trigger="change" data-parsley-minlength="4" data-parsley-pattern="/^[a-zA-Z0-9\-_]{0,40}$/" value="{{ $user->username or '' }}" placeholder="Username">
                                <label for="username" class="label-validation">Username</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="email" name="email" type="email" data-parsley-required="true" data-parsley-trigger="change" value="{{ $user->email or '' }}" placeholder="Email">
                                <label for="email" class="label-validation">Email</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="password" name="password" type="password" data-parsley-required="true" data-parsley-trigger="change">
                                <label for="password" class="label-validation">Existing Password</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="newpassword" name="newpassword" type="password" data-parsley-trigger="change">
                                <label for="newpassword" class="label-validation">New Password</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="newpassword_confirmation" name="newpassword_confirmation" type="password" data-parsley-trigger="change" data-parsley-equalto="#newpassword">
                                <label for="newpassword_confirmation" class="label-validation">New Password Confirmation</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            {{ method_field('PATCH') }}
                            {{ csrf_field() }}
                            <button class="btn waves-effect waves-light col s12 m2 offset-m10" type="submit" name="action">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section("scripts")
    <script type="text/javascript">
        "use strict";
        $(function() {
            $('#edit-profile').parsley({
                successClass: 'valid',
                errorClass: 'invalid',
                errorsContainer: function (velem) {
                    var $errelem = velem.$element.siblings('label');
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
            })
            .on('form:submit', function(velem) {
            });
        });
    </script>
@stop