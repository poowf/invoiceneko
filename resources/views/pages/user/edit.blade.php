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
                <h3>Profile</h3>
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
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="full_name" name="full_name" type="text" data-parsley-required="true" data-parsley-trigger="change" data-parsley-minlength="4" value="{{ $user->full_name or '' }}" placeholder="Name">
                                <label for="full_name" class="label-validation">Full Name</label>
                            </div>
                        </div>
                        <div class="row pbtm20">
                            <div class="input-field col s12">
                                <input id="fphone" name="fphone" type="text" data-parsley-required="true" data-parsley-trigger="change" data-parsley-pattern="^[\d\+\-\.\(\)\/\s]*$" data-parsley-phone-format="#fphone" value="{{ $user->phone }}">
                                <input id="phone" name="phone" class="form-control" type="hidden" data-parsley-required="true" data-parsley-trigger="change" data-parsley-pattern="^[\d\+\-\.\(\)\/\s]*$">
                                <label for="fphone" class="manual-validation">Phone</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col s12 left">
                                <label id="rbtn-label" class="rbtn-label" for="gender">Gender</label>
                                <p class="rbtn">
                                    <input id="gender-male" name="gender" type="radio" value="male" data-parsley-required="true" data-parsley-trigger="change" @if($user->gender == "male") checked @endif>
                                    <label for="gender-male">Male</label>
                                </p>
                                <p class="rbtn">
                                    <input id="gender-female" name="gender" type="radio" value="female" @if($user->gender == "female") checked @endif>
                                    <label for="gender-female">Female</label>
                                </p>
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
            $("#fphone").intlTelInput({
                initialCountry: "sg",
                utilsScript: "/assets/js/utils.js"
            });

            $( "#fphone" ).focusin(function() {
                $(this).parent().siblings('.manual-validation').addClass('black-text');
            });

            $( "#fphone" ).focusout(function() {
                $(this).parent().siblings('.manual-validation').removeClass('black-text');
            });

            window.Parsley
                .addValidator('phoneFormat', {
                    requirementType: 'string',
                    validateString: function(value, elementid) {
                        if($(elementid).intlTelInput("isValidNumber"))
                        {
                            return true;
                        }
                        else
                        {
                            return false;
                        }
                    },
                    messages: {
                        en: 'This is an invalid phone number format'
                    }
                });


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
                if (velem.$element.is(':radio'))
                {
                    velem.$element.parent('').siblings('label').removeClass('invalid').addClass('valid');
                }
                else if (velem.$element.is('#fphone'))
                {
                    velem.$element.parent('').siblings('label').removeClass('invalid').addClass('valid');
                }
            })
            .on('field:error', function(velem) {
                if (velem.$element.is(':radio'))
                {
                    velem.$element.parent('').siblings('label').removeClass('valid').addClass('invalid');
                    velem.$element.parent('').siblings('label').attr('data-error', window.Parsley.getErrorMessage(velem.validationResult[0].assert));
                }
                else if (velem.$element.is('#fphone'))
                {
                    velem.$element.parent('').siblings('label').removeClass('valid').addClass('invalid');
                    velem.$element.parent('').siblings('label').attr('data-error', window.Parsley.getErrorMessage(velem.validationResult[0].assert));
                }
            })
            .on('form:submit', function(velem) {
                $("#phone").val($("#fphone").intlTelInput("getNumber"));
            });
        });
    </script>
@stop