@extends("layouts/default")

@section("head")
    <title>{{ config('app.name') }}</title>
    <link href="{{ mix('/assets/css/intlTelInput.css') }}" rel="stylesheet" type="text/css">

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
                <h4>Check if your company already has an account here</h4>
                <form id="check-company" method="post" enctype="multipart/form-data">
                    <div class="card-panel">
                        <p style="font-size: 1.2em;">Key in your work email:</p>
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="check_email" name="check_email" type="email" data-parsley-required="true" data-parsley-trigger="change" value="{{ old('check_email') }}" placeholder="Email">
                                <label for="check_email" class="label-validation">Email</label>
                                <span class="helper-text"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            {{ csrf_field() }}
                            <button id="check-company-btn" class="btn waves-effect waves-light full-width" type="submit" name="action">Check</button>
                        </div>
                    </div>
                </form>

                <form id="request-access" method="post" enctype="multipart/form-data" class="hide">
                    <h3>Request for an account</h3>
                    <div class="card-panel">
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="request_full_name" name="request_full_name" type="text" data-parsley-required="true" data-parsley-trigger="change" data-parsley-minlength="4" value="{{ old('request_full_name') }}" placeholder="Name">
                                <label for="request_full_name" class="label-validation">Full Name</label>
                                <span class="helper-text"></span>
                            </div>
                        </div>
                        <div class="row pbtm20">
                            <div class="input-field col s12">
                                <input id="request_phone" name="request_phone" type="text" data-parsley-required="true" data-parsley-trigger="change" data-parsley-pattern="^[\d\+\-\.\(\)\/\s]*$" data-parsley-phone-format="#request_phone" value="{{ old('request_phone') }}">
                                <label for="request_phone" class="label-validation">Phone</label>
                                <span class="helper-text"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="request_email" name="request_email" type="email" data-parsley-required="true" data-parsley-trigger="change" value="{{ old('request_email') }}" placeholder="Email">
                                <label for="request_email" class="label-validation">Email</label>
                                <span class="helper-text"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            {{ csrf_field() }}
                            <button class="btn waves-effect waves-light col s12 m3 offset-m9" type="submit" name="action">Request Access</button>
                        </div>
                    </div>
                </form>

                <form id="signup" method="post" enctype="multipart/form-data" class="hide">
                    <div class="card-panel">
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="username" name="username" type="text" data-parsley-required="true" data-parsley-trigger="change" data-parsley-minlength="4" data-parsley-pattern="/^[a-zA-Z0-9\-_]{0,40}$/" value="{{ old('username') }}" placeholder="Username">
                                <label for="username" class="label-validation">Username</label>
                                <span class="helper-text"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="email" name="email" type="email" data-parsley-required="true" data-parsley-trigger="change" value="{{ old('email') }}" placeholder="Email">
                                <label for="email" class="label-validation">Email</label>
                                <span class="helper-text"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="password" name="password" type="password" data-parsley-required="true" data-parsley-trigger="change" data-parsley-minlength="6" placeholder="Password">
                                <label for="password" class="label-validation">Password</label>
                                <span class="helper-text"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="password_confirmation" name="password_confirmation" type="password" data-parsley-required="true" data-parsley-trigger="change" data-parsley-minlength="6" data-parsley-equalto="#password" placeholder="Confirm Password">
                                <label for="password" class="label-validation">Password Confirmation</label>
                                <span class="helper-text"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="full_name" name="full_name" type="text" data-parsley-required="true" data-parsley-trigger="change" data-parsley-minlength="4" value="{{ old('full_name') }}" placeholder="Name">
                                <label for="full_name" class="label-validation">Full Name</label>
                                <span class="helper-text"></span>
                            </div>
                        </div>
                        <div class="row pbtm20">
                            <div class="input-field col s12">
                                <input id="phone" name="phone" type="text" data-parsley-required="true" data-parsley-trigger="change" data-parsley-pattern="^[\d\+\-\.\(\)\/\s]*$" data-parsley-phone-format="#phone" value="{{ old('phone') }}">
                                <label for="phone" class="label-validation">Phone</label>
                                <span class="helper-text"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col s12 left">
                                <label id="rbtn-label" class="rbtn-label" for="gender">Gender</label>
                                <p class="rbtn">
                                    <label for="gender-male">
                                        <input id="gender-male" name="gender" type="radio" value="male" data-parsley-required="true" data-parsley-trigger="change" @if(old('gender') == "male") checked @endif>
                                        <span>Male</span>
                                    </label>
                                </p>
                                <p class="rbtn">
                                    <label for="gender-female">
                                        <input id="gender-female" name="gender" type="radio" value="female" @if(old('gender') == "female") checked @endif>
                                        <span>Female</span>
                                    </label>
                                </p>
                                <span class="helper-text manual-validation"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            {{ csrf_field() }}
                            <button class="btn waves-effect waves-light col s12 m3 offset-m9" type="submit" name="action">Next</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section("scripts")
    <script type="text/javascript" src="{{ mix('/assets/js/intlTelInput.js') }}"></script>

    <script type="text/javascript">
        "use strict";
        $(function() {
            $('#check-company').parsley({
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
                    if (velem.$element.is(':radio'))
                    {
                        velem.$element.parentsUntil('.row').find('span.helper-text').removeClass('invalid').addClass('valid');
                    }
                    else if (velem.$element.is('#phone'))
                    {
                        velem.$element.parent('').siblings('label').removeClass('invalid').addClass('valid');
                    }
                })
                .on('field:error', function(velem) {
                    if (velem.$element.is(':radio'))
                    {
                        velem.$element.parentsUntil('.row').find('span.helper-text').removeClass('valid').addClass('invalid');
                        velem.$element.parentsUntil('.row').find('span.helper-text').attr('data-error', window.Parsley.getErrorMessage(velem.validationResult[0].assert));
                    }
                    else if (velem.$element.is('#phone'))
                    {
                        velem.$element.parent('').siblings('label').removeClass('valid').addClass('invalid');
                        velem.$element.parent('').siblings('label').attr('data-error', window.Parsley.getErrorMessage(velem.validationResult[0].assert));
                    }
                })
                .on('form:submit', function(velem) {
                    $("#phone").val($("#phone").intlTelInput("getNumber"));
                });

            function checkCompany(email, callback) {
                console.log(email);
                if (typeof email !== typeof undefined && email !== false) {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('user.check') }}",
                        data: {
                            'check_email' : email,
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    })
                        .done(function(data) {
                            callback(data);
                        })
                        .fail(function(jqXHR, textStatus) {
                            displaySignup()
                        })
                        .always(function() {
                            console.log("finish");
                        });
                }
            }

            function initSignupForm()
            {
                $("#phone").intlTelInput({
                    initialCountry: "sg",
                    utilsScript: "/assets/js/utils.js"
                });

                $( "#phone" ).focusin(function() {
                    $(this).parent().siblings('.label-validation').addClass('theme-text');
                });

                $( "#phone" ).focusout(function() {
                    $(this).parent().siblings('.label-validation').removeClass('theme-text');
                });

                $('#signup').parsley({
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
                        if (velem.$element.is(':radio'))
                        {
                            velem.$element.parentsUntil('.row').find('span.helper-text').removeClass('invalid').addClass('valid');
                        }
                        else if (velem.$element.is('#phone'))
                        {
                            velem.$element.parent('').siblings('label').removeClass('invalid').addClass('valid');
                        }
                    })
                    .on('field:error', function(velem) {
                        if (velem.$element.is(':radio'))
                        {
                            velem.$element.parentsUntil('.row').find('span.helper-text').removeClass('valid').addClass('invalid');
                            velem.$element.parentsUntil('.row').find('span.helper-text').attr('data-error', window.Parsley.getErrorMessage(velem.validationResult[0].assert));
                        }
                        else if (velem.$element.is('#phone'))
                        {
                            velem.$element.parent('').siblings('label').removeClass('valid').addClass('invalid');
                            velem.$element.parent('').siblings('label').attr('data-error', window.Parsley.getErrorMessage(velem.validationResult[0].assert));
                        }
                    })
                    .on('form:submit', function(velem) {
                        $("#phone").val($("#phone").intlTelInput("getNumber"));
                    });
            }

            function initRequestForm()
            {
                $("#request_phone").intlTelInput({
                    initialCountry: "sg",
                    utilsScript: "/assets/js/utils.js"
                });

                $( "#request_phone" ).focusin(function() {
                    $(this).parent().siblings('.label-validation').addClass('theme-text');
                });

                $( "#request_phone" ).focusout(function() {
                    $(this).parent().siblings('.label-validation').removeClass('theme-text');
                });

                $('#request-access').parsley({
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
                        if (velem.$element.is(':radio'))
                        {
                            velem.$element.parentsUntil('.row').find('span.helper-text').removeClass('invalid').addClass('valid');
                        }
                        else if (velem.$element.is('#phone'))
                        {
                            velem.$element.parent('').siblings('label').removeClass('invalid').addClass('valid');
                        }
                    })
                    .on('field:error', function(velem) {
                        if (velem.$element.is(':radio'))
                        {
                            velem.$element.parentsUntil('.row').find('span.helper-text').removeClass('valid').addClass('invalid');
                            velem.$element.parentsUntil('.row').find('span.helper-text').attr('data-error', window.Parsley.getErrorMessage(velem.validationResult[0].assert));
                        }
                        else if (velem.$element.is('#phone'))
                        {
                            velem.$element.parent('').siblings('label').removeClass('valid').addClass('invalid');
                            velem.$element.parent('').siblings('label').attr('data-error', window.Parsley.getErrorMessage(velem.validationResult[0].assert));
                        }
                    })
                    .on('form:submit', function(velem) {
                        $("#phone").val($("#phone").intlTelInput("getNumber"));
                    });
            }

            function displaySignup() {
                $('#signup').removeClass('hide');
                $('#request-access').addClass('hide');
                initSignupForm();
            }

            function displayCheckCompanyResponse(company) {
                $('#signup').addClass('hide');
                $('#request-access').removeClass('hide');
                initRequestForm();
            }

            $('#check-company').on('click', '#check-company-btn', function (event) {
                event.preventDefault()
                checkCompany($('#check_email').val(), displayCheckCompanyResponse);
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
        });
    </script>
@stop