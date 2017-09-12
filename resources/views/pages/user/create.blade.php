@extends("layouts/default")

@section("head")
    <title>Invoice Plz</title>
    <style>
    </style>
@stop

@section("content")
    <div class="container">
        <div class="row">
            <div class="col s12">
                <h3>Sign Up</h3>
            </div>
        </div>
        <div class="row">
            <div class="col s12">
                <form id="signup" method="post" enctype="multipart/form-data">
                    <div class="card-panel">
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="name" name="name" type="text" data-parsley-required="true" data-parsley-trigger="change" data-parsley-minlength="4" value="{{ old('name') }}">
                                <label for="name" class="label-validation">Name</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="username" name="username" type="text" data-parsley-required="true" data-parsley-trigger="change" data-parsley-minlength="4" data-parsley-pattern="/^[a-zA-Z0-9\-_]{0,40}$/" value="{{ old('username') }}">
                                <label for="username" class="label-validation">Username</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="email" name="email" type="email" data-parsley-required="true" data-parsley-trigger="change" value="{{ old('email') }}">
                                <label for="email" class="label-validation">Email</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="password" name="password" type="password" data-parsley-required="true" data-parsley-trigger="change" data-parsley-minlength="6">
                                <label for="password" class="label-validation">Password</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="password_confirmation" name="password_confirmation" type="password" data-parsley-required="true" data-parsley-trigger="change" data-parsley-minlength="6" data-parsley-equalto="#password">
                                <label for="password" class="label-validation">Password Confirmation</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            {{ csrf_field() }}
                            <button class="btn waves-effect waves-light col s12 m2 offset-m10" type="submit" name="action">Next</button>
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
            $('#signup').parsley({
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

                });
        });
    </script>
@stop