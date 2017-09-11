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
                <h3>Create Company</h3>
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
                                <input id="slug" name="slug" type="text" data-parsley-required="true" data-parsley-trigger="change" value="{{ old('slug') }}">
                                <label for="slug" class="label-validation">Slug</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="crn" name="crn" type="text" data-parsley-required="true" data-parsley-trigger="change" data-parsley-minlength="6">
                                <label for="crn" class="label-validation">Registration Number</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <div class="file-field input-field">
                                    <div class="btn btn-link">
                                        <span>File</span>
                                        <input id="logo" name="logo" type="file" accept="image/*" data-maxsize="10M"/>
                                    </div>
                                    <div class="file-path-wrapper">
                                        <input id="logofp" name="logofp" class="file-path validate" type="text" data-parsley-required="true" data-parsley-fileuploaded="true" data-parsley-trigger="change" placeholder="Company Logo"/>
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
                                    <div class="btn btn-link">
                                        <span>File</span>
                                        <input id="smlogo" name="smlogo" type="file" accept="image/*" data-maxsize="10M"/>
                                    </div>
                                    <div class="file-path-wrapper">
                                        <input id="smlogofp" name="smlogofp" class="file-path validate" type="text" data-parsley-required="true" data-parsley-fileuploaded="true" data-parsley-trigger="change" placeholder="Small Company Logo"/>
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
                            <button class="btn waves-effect waves-light col s2 offset-s10" type="submit" name="action">Create</button>
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
        });
    </script>
@stop