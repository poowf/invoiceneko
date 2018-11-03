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
                <h3>Address</h3>
            </div>
        </div>
        <div class="row">
            <div class="col s12 m3 xl2">
                @include("partials/sidenav-company")
            </div>
            <div class="col s12 m9 xl10">
                <form id="edit-address" method="post" enctype="multipart/form-data">
                    <div class="card-panel">
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="block" name="block" type="text" placeholder="Block" value="{{ $companyaddress->block ?? ''}}" data-parsley-required="true" data-parsley-trigger="change" @if(!$ownedcompany) disabled @endif>
                                <label for="block" class="label-validation">Block</label>
                                <span class="helper-text"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="street" name="street" type="text" placeholder="Street" value="{{ $companyaddress->street ?? ''}}" data-parsley-required="true" data-parsley-trigger="change" @if(!$ownedcompany) disabled @endif>
                                <label for="street" class="label-validation">Street</label>
                                <span class="helper-text"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <i class="mdi mdi-pound prefix-inline"></i>
                                <input id="unitnumber" name="unitnumber" type="text" placeholder="Unit Number" value="{{ $companyaddress->unitnumber ?? ''}}" data-parsley-required="true" data-parsley-trigger="change" @if(!$ownedcompany) disabled @endif>
                                <label for="unitnumber" class="label-validation">Unit Number</label>
                                <span class="helper-text"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="postalcode" name="postalcode" type="number" placeholder="Postal Code" value="{{ $companyaddress->postalcode ?? ''}}" data-parsley-required="true" data-parsley-trigger="change" data-parsley-minlength="6" data-parsley-maxlength="6" @if(!$ownedcompany) disabled @endif>
                                <label for="postalcode" class="label-validation">Postal Code</label>
                                <span class="helper-text"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="radio-field col s12 left">
                                <label id="rbtn-label" class="rbtn-label" for="buildingtype">Building Type</label>
                                <p class="rbtn">
                                    <label for="buildingtype-residential">
                                        <input id="buildingtype-residential" name="buildingtype" type="radio" value="{{ \App\Models\CompanyAddress::BUILDINGTYPE_RESIDENTIAL }}"  data-parsley-required="true" data-parsley-trigger="change" @if($companyaddress) @if($companyaddress->buildingtype == \App\Models\CompanyAddress::BUILDINGTYPE_RESIDENTIAL) checked @endif @endif @if(!$ownedcompany) disabled @endif>
                                        <span>Residential</span>
                                    </label>
                                </p>
                                <p class="rbtn">
                                    <label for="buildingtype-business">
                                        <input id="buildingtype-business" name="buildingtype" type="radio" value="{{ \App\Models\CompanyAddress::BUILDINGTYPE_BUSINESS }}" @if($companyaddress) @if($companyaddress->buildingtype == \App\Models\CompanyAddress::BUILDINGTYPE_BUSINESS) checked @endif @endif @if(!$ownedcompany) disabled @endif>
                                        <span>Business</span>
                                    </label>
                                </p>
                                <span class="helper-text manual-validation"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            {{ method_field('PATCH') }}
                            {{ csrf_field() }}
                            <button class="btn waves-effect waves-light col s12 m3 offset-m9" type="submit" name="action" @if(!$ownedcompany) disabled @endif>Update</button>
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

            @if(!$ownedcompany)
                M.toast({ html: "You need to fill in your company information first", displayLength: "poowf", classes: "error"});
            @endif

            $('#edit-address').parsley({
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
                })
                .on('field:error', function(velem) {
                    if (velem.$element.is(':radio'))
                    {
                        velem.$element.parentsUntil('.row').find('span.helper-text').removeClass('valid').addClass('invalid');
                        velem.$element.parentsUntil('.row').find('span.helper-text').attr('data-error', window.Parsley.getErrorMessage(velem.validationResult[0].assert));
                    }
                })
                .on('form:submit', function(velem) {
                });
        });
    </script>
@stop