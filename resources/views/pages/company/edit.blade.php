@extends("layouts/default")

@section("head")
    <title>{{ config('app.name') }}</title>
    <link href="{{ mix('/assets/css/intlTelInput.css') }}" rel="stylesheet" type="text/css">
    <style>
        .logo-display-container, .smlogo-display-container {
            display: inline-block;
        }

        .logo-display-container img, .smlogo-display-container img {
            max-height: 100px;
            max-width: 250px;
            margin-top: 15px;

            object-fit: cover;
            object-position: center right;
        }

        span.text-content {
            width: 300px;
            padding: 10px 0;
            margin-top: 15px;
            background: rgba(0,0,0,0.5);
            color: white;
            cursor: pointer;
            display: table;
            position: absolute;
            top: 0;
            opacity: 0;
            -webkit-transition: opacity 500ms;
            -moz-transition: opacity 500ms;
            -o-transition: opacity 500ms;
            transition: opacity 500ms;
        }

        span.text-content span {
            display: table-cell;
            text-align: center;
            vertical-align: middle;
        }

        .logo-display-container:hover span.text-content, .smlogo-display-container:hover span.text-content {
            opacity: 1;
        }
    </style>
@stop

@section("content")
    <div class="container">
        <div class="row">
            <div class="col s12">
                <h3>Company</h3>
            </div>
        </div>
        <div class="row">
            <div class="col s12 m3 xl2">
                @include("partials/sidenav")
            </div>
            <div class="col s12 m9 xl10">
                <form id="edit-company" method="post" enctype="multipart/form-data">
                    <div class="card-panel">
                        <div class="row">
                            <div class="logo-container input-field col s12">
                                <label for="logo-display" class="label-validation">Logo</label>
                                <div class="logo-display-container tooltipped" data-position="top" data-delay="50" data-tooltip="Recommended Size: 210 (W) x 110 (H) with White Background (Optional)">
                                    <img id="logo-display" src="{{ \App\Library\Poowf\Unicorn::getStorageFile($company->logo, [210, 110]) }}" height="100">
                                    <span class="text-content"><span id="logo-upload">Change?</span></span>
                                </div>
                                <input id="logo" name="logo" type="file" accept="image/*" style="display: none;" data-maxsize="10M">
                            </div>
                        </div>
                        <div class="row">
                            <div class="smlogo-container input-field col s12">
                                <label for="smlogo-display" class="label-validation">Small Logo</label>
                                <div class="smlogo-display-container tooltipped" data-position="top" data-delay="50" data-tooltip="Recommended Size: 80 (W) x 80 (H) with White Background (Optional)">
                                    <img id="smlogo-display" src="{{ \App\Library\Poowf\Unicorn::getStorageFile($company->smlogo, [80,80]) }}" height="100">
                                    <span class="text-content"><span id="smlogo-upload">Change?</span></span>
                                </div>
                                <input id="smlogo" name="smlogo" type="file" accept="image/*" style="display: none;" data-maxsize="10M">
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="name" name="name" type="text" data-parsley-required="true" data-parsley-trigger="change" data-parsley-minlength="4" value="{{ $company->name or '' }}" placeholder="Company Name">
                                <label for="name" class="label-validation">Company Name</label>
                                <span class="helper-text"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="crn" name="crn" type="text" data-parsley-required="true" data-parsley-trigger="change" data-parsley-minlength="4" data-parsley-pattern="/^[a-zA-Z0-9\-_]{0,40}$/" value="{{ $company->crn or '' }}" placeholder="Company Registration Number">
                                <label for="crn" class="label-validation">Company Registration Number</label>
                                <span class="helper-text"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="email" name="email" type="email" data-parsley-required="true" data-parsley-trigger="change" value="{{ $company->email or '' }}" placeholder="Company Email">
                                <label for="email" class="label-validation">Company Email</label>
                                <span class="helper-text"></span>
                            </div>
                        </div>
                        <div class="row pbtm20">
                            <div class="input-field col s12">
                                <input id="phone" name="phone" type="text" data-parsley-required="false" data-parsley-trigger="change" data-parsley-pattern="^[\d\+\-\.\(\)\/\s]*$" data-parsley-phone-format="#phone" value="{{ $company->phone or '' }}">
                                <label for="phone" class="label-validation">Phone</label>
                                <span class="helper-text"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            {{ method_field('PATCH') }}
                            {{ csrf_field() }}
                            <button class="btn waves-effect waves-light col s12 m3 offset-m9" type="submit" name="action">Update</button>
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
            $('#logo-upload').click(function(){
                $('#logo').click();
            });

            $('#smlogo-upload').click(function(){
                $('#smlogo').click();
            });

            $("#logo").on("change", function()
            {
                var files = !!this.files ? this.files : [];
                if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support

                if (/^image/.test( files[0].type)){ // only image file
                    var reader = new FileReader(); // instance of the FileReader
                    reader.readAsDataURL(files[0]); // read the local file

                    reader.onloadend = function(){ // set image data as background of div
                        $("#logo-display").attr("src", this.result);
                    }
                }
            });

            $("#smlogo").on("change", function()
            {
                var files = !!this.files ? this.files : [];
                if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support

                if (/^image/.test( files[0].type)){ // only image file
                    var reader = new FileReader(); // instance of the FileReader
                    reader.readAsDataURL(files[0]); // read the local file

                    reader.onloadend = function(){ // set image data as background of div
                        $("#smlogo-display").attr("src", this.result);
                    }
                }
            });

            $("#phone").intlTelInput({
                initialCountry: "sg",
                utilsScript: "/assets/js/utils.js"
            });

            $("#phone").focusin(function() {
                $(this).parent().siblings('.label-validation').addClass('theme-text');
            });

            $("#phone").focusout(function() {
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

            $('#edit-company').parsley({
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
                        velem.$element.parent('').siblings('label').removeClass('invalid').addClass('valid');
                    }
                    else if (velem.$element.is('#phone'))
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
                    else if (velem.$element.is('#phone'))
                    {
                        velem.$element.parent('').siblings('span.helper-text').removeClass('valid').addClass('invalid');
                        velem.$element.parent('').siblings('span.helper-text').attr('data-error', window.Parsley.getErrorMessage(velem.validationResult[0].assert));
                    }
                });
        });
    </script>
@stop