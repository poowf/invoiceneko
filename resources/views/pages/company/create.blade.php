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
                <h3>Create Company</h3>
            </div>
        </div>
        <div class="row">
            <div class="col s12">
                <form id="create-company" method="post" enctype="multipart/form-data">
                    <div class="card-panel">
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="name" name="name" type="text" data-parsley-required="true" data-parsley-trigger="change" data-parsley-minlength="4" value="{{ old('name') }}" placeholder="Company Name">
                                <label for="name" class="label-validation">Company Name</label>
                                <span class="helper-text"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="crn" name="crn" type="text" data-parsley-required="true" data-parsley-trigger="change" data-parsley-minlength="4" data-parsley-pattern="/^[a-zA-Z0-9\-_]{0,40}$/" value="{{ old('crn') }}" placeholder="Company Registration Number">
                                <label for="crn" class="label-validation">Company Registration Number</label>
                                <span class="helper-text"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="email" name="email" type="email" data-parsley-required="true" data-parsley-trigger="change" value="{{ old('email') }}" placeholder="Company Email">
                                <label for="email" class="label-validation">Company Email</label>
                                <span class="helper-text"></span>
                            </div>
                        </div>
                        <div class="row pbtm20">
                            <div class="input-field col s12">
                                <input id="phone" name="phone" type="text" data-parsley-required="true" data-parsley-trigger="change" data-parsley-pattern="^[\d\+\-\.\(\)\/\s]*$" data-parsley-phone-format="#phone" value="{{ old('phone') }}">
                                <label for="phone" class="manual-validation">Company Phone</label>
                                <span class="helper-text"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <div class="file-field input-field">
                                    <div class="btn btn-link tooltipped" data-position="left" data-delay="50" data-tooltip="Recommended Size: 210 (W) x 110 (H) with White Background (Optional)">
                                        <span>File</span>
                                        <input id="logo" name="logo" type="file" accept="image/*" data-maxsize="10M"/>
                                    </div>
                                    <div class="file-path-wrapper">
                                        <input id="logofp" name="logofp" class="file-path validate" type="text" data-parsley-required="false" data-parsley-fileuploaded="true" data-parsley-trigger="change" placeholder="Company Logo"/>
                                    </div>
                                </div>
                                <label for="logo" class="label-validation">
                                    Logo
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <div class="file-field input-field">
                                    <div class="btn btn-link tooltipped" data-position="left" data-delay="50" data-tooltip="Recommended Size: 80 (W) x 80 (H) with White Background (Optional)">
                                        <span>File</span>
                                        <input id="smlogo" name="smlogo" type="file" accept="image/*" data-maxsize="10M"/>
                                    </div>
                                    <div class="file-path-wrapper">
                                        <input id="smlogofp" name="smlogofp" class="file-path validate" type="text" data-parsley-required="false" data-parsley-fileuploaded="true" data-parsley-trigger="change" placeholder="Small Company Logo"/>
                                    </div>
                                </div>
                                <label for="smlogo" class="label-validation">
                                    Small Logo
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            {{ csrf_field() }}
                            <button class="btn waves-effect waves-light col s12 m3 offset-m9" type="submit" name="action">Create</button>
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

            $('#create-company').parsley({
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
                    if (velem.$element.is('#phone'))
                    {
                        velem.$element.parent('').siblings('label').removeClass('invalid').addClass('valid');
                    }
                })
                .on('field:error', function(velem) {
                    if (velem.$element.is('#phone'))
                    {
                        velem.$element.parent('').siblings('span.helper-text').removeClass('valid').addClass('invalid');
                        velem.$element.parent('').siblings('span.helper-text').attr('data-error', window.Parsley.getErrorMessage(velem.validationResult[0].assert));
                    }
                });
        });
    </script>
@stop