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
                <h3>Create Client</h3>
            </div>
        </div>
        <div class="row">
            <div class="col s12">
                <form id="create-client" method="post" enctype="multipart/form-data">
                    <div class="card-panel">
                        <div class="row">
                            <div class="input-field col s12 m6">
                                <input id="companyname" name="companyname" type="text" data-parsley-required="true" data-parsley-trigger="change" data-parsley-minlength="4" value="{{ old('companyname') }}" placeholder="Client Company Name">
                                <label for="companyname" class="label-validation">Company Name</label>
                            </div>
                            <div class="input-field col s12 m6">
                                <input id="nickname" name="nickname" type="text" data-parsley-required="true" data-parsley-trigger="change" value="{{ old('nickname') }}" placeholder="Client Nickname">
                                <label for="nickname" class="label-validation">Company Nickname</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="address" name="address" type="text" data-parsley-required="true" data-parsley-trigger="change" data-parsley-minlength="4" value="{{ old('address') }}" placeholder="Client Address">
                                <label for="address" class="label-validation">Company Address</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="address_second" name="address_second" type="text" data-parsley-required="true" data-parsley-trigger="change" data-parsley-minlength="4" value="{{ old('address_second') }}" placeholder="Client Address Second">
                                <label for="address_second" class="label-validation">Company Address Second</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="zipcode" name="zipcode" type="text" data-parsley-required="true" data-parsley-trigger="change" data-parsley-minlength="4" value="{{ old('zipcode') }}" placeholder="Client Zipcode">
                                <label for="zipcode" class="label-validation">Company Zipcode</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="country" name="country" type="text" data-parsley-required="true" data-parsley-trigger="change" data-parsley-minlength="4" value="{{ old('country') }}" placeholder="Client Country">
                                <label for="country" class="label-validation">Company Country</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="crn" name="crn" type="text" data-parsley-required="true" data-parsley-trigger="change" data-parsley-minlength="4" data-parsley-pattern="/^[a-zA-Z0-9\-_]{0,40}$/" value="{{ old('crn') }}" placeholder="Client Company Registration Number">
                                <label for="crn" class="label-validation">Company Registration Number</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12 m2">
                                <input id="contactsalutation" name="contactsalutation" type="text" data-parsley-required="true" data-parsley-trigger="change" data-parsley-minlength="4" value="{{ old('contactsalutation') }}" placeholder="Client Contact Salutation">
                                <label for="contactsalutation" class="label-validation">Contact Salutation</label>
                            </div>
                            <div class="input-field col s12 m5">
                                <input id="contactfirstname" name="contactfirstname" type="text" data-parsley-required="true" data-parsley-trigger="change" data-parsley-minlength="4" value="{{ old('contactfirstname') }}" placeholder="Client Contact First Name">
                                <label for="contactfirstname" class="label-validation">Contact First Name</label>
                            </div>
                            <div class="input-field col s12 m5">
                                <input id="contactlastname" name="contactlastname" type="text" data-parsley-required="true" data-parsley-trigger="change" data-parsley-minlength="4" value="{{ old('contactlastname') }}" placeholder="Client Contact Last Name">
                                <label for="contactlastname" class="label-validation">Contact Last Name</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12 m6">
                                <input id="fphone" name="fphone" type="text" data-parsley-required="true" data-parsley-trigger="change" data-parsley-pattern="^[\d\+\-\.\(\)\/\s]*$" data-parsley-phone-format="#fphone" value="{{ old('contactphone') }}">
                                <input id="contactphone" name="contactphone" class="form-control" type="hidden" data-parsley-required="true" data-parsley-trigger="change" data-parsley-pattern="^[\d\+\-\.\(\)\/\s]*$">
                                <label for="fphone" class="manual-validation">Phone</label>
                            </div>
                            <div class="input-field col s12 m6">
                                <input id="contactemail" name="contactemail" type="email" data-parsley-required="true" data-parsley-trigger="change" value="{{ old('contactemail') }}" placeholder="Client Contact Email">
                                <label for="contactemail" class="label-validation">Contact Email</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            {{ csrf_field() }}
                            <button class="btn waves-effect waves-light col s12 m2 offset-m10" type="submit" name="action">Create</button>
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

            $('#create-client').parsley({
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
                    $("#contactphone").val($("#fphone").intlTelInput("getNumber"));
                });
        });
    </script>
@stop