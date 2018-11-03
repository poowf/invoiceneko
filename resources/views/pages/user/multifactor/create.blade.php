@extends("layouts.default", ['page_title' => 'Multifactor | Activate'])

@section("head")
    <style>
    </style>
@stop

@section("content")
    <div class="container">
        <div class="row">
            <div class="col s12">
                <h3>Security</h3>
            </div>
        </div>
        <div class="row">
            <div class="col s12 m3 xl2">
                @include("partials/sidenav-user")
            </div>
            <div class="col s12 m9 xl10">
                <div class="card-panel center">
                    <div class="visible-print text-center">
                        <p>Scan me and key in the code below to verify that your authenticator application is working correctly</p>
                        <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(350)->generate($twoFactorUrl)) !!}" class="responsive-img">
                    </div>
                    <form id="verify-multifactor" method="post" enctype="multipart/form-data">
                        <div class="input-field col s12">
                            <input id="multifactor_code" name="multifactor_code" type="number" data-parsley-required="true" data-parsley-trigger="change" value="{{ old('multifactor_code') }}" placeholder="Code">
                            <label for="multifactor_code" class="label-validation">Code</label>
                            <span class="helper-text"></span>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                {{ csrf_field() }}
                                <button class="btn btn-large waves-effect waves-light full-width" type="submit" name="action">Verify</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section("scripts")
    <script type="text/javascript">
        "use strict";
        $(function() {
            $('#verify-multifactor').parsley({
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
                })
                .on('field:error', function(velem) {
                })
                .on('form:submit', function(velem) {
                });
        });
    </script>
@stop