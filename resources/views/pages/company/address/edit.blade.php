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
                @include("partials/sidenav")
            </div>
            <div class="col s12 m9 xl10">
                <form id="edit-address" method="post" enctype="multipart/form-data">
                    <div class="card-panel">
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="block" name="block" type="text" placeholder="Block" value="{{ old('block') }}" data-parsley-required="true" data-parsley-trigger="change">
                                <label for="block" class="label-validation">Block</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="street" name="street" type="text" placeholder="Street" value="{{ old('street') }}" data-parsley-required="true" data-parsley-trigger="change">
                                <label for="street" class="label-validation">Street</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="unitnumber" name="unitnumber" type="text" placeholder="Unit Number" value="{{ old('unitnumber') }}" data-parsley-required="true" data-parsley-trigger="change">
                                <label for="unitnumber" class="label-validation">Unit Number</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="postalcode" name="postalcode" type="number" placeholder="Postal Code" value="{{ old('postalcode') }}" data-parsley-required="true" data-parsley-trigger="change" data-parsley-minlength="6" data-parsley-maxlength="6">
                                <label for="postalcode" class="label-validation">Postal Code</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col s12">
                                <label id="rbtn-label" class="rbtn-label" for="gender">Building Type</label>
                                <p class="rbtn">
                                    <input id="buildingtype-residential" name="buildingtype" type="radio" value="{{ \App\Models\CompanyAddress::BUILDINGTYPE_RESIDENTIAL }}"  data-parsley-required="true" data-parsley-trigger="change" @if(old('buildingtype') == \App\Models\CompanyAddress::BUILDINGTYPE_RESIDENTIAL) checked @endif>
                                    <label for="buildingtype-residential">Residential</label>
                                </p>
                                <p class="rbtn">
                                    <input id="buildingtype-business" name="buildingtype" type="radio" value="{{ \App\Models\CompanyAddress::BUILDINGTYPE_BUSINESS }}" @if(old('buildingtype') == \App\Models\CompanyAddress::BUILDINGTYPE_BUSINESS) checked @endif>
                                    <label for="buildingtype-business">Business</label>
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
            $('#edit-address').parsley({
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