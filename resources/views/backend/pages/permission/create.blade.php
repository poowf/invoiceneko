@extends("backend.layouts.default")

@section("head")
    @include("backend/partials/form-styles")
@stop

@section("content")
    <div class="page-head">
        <h2 class="page-head-title">Create New Permission</h2>
        <ol class="breadcrumb page-head-nav">
            <li><a href="{{ route('backend.main') }}">Home</a></li>
            <li><a href="#">Permissions</a></li>
            <li class="active">Create New Permission</li>
        </ol>
    </div>
    <div class="main-content container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-default panel-border-color panel-border-color-primary">
                    <div class="panel-heading panel-heading-divider">Fill up the form below to create a new Permission<span
                                class="panel-subtitle"></span>
                    </div>
                    <div class="panel-body">
                        <form id="create-form" method="post" class="form-horizontal" enctype="multipart/form-data">
                            <div class="form-group xs-mt-10">
                                <label for="label" class="col-sm-2 control-label">Name</label>
                                <div class="col-sm-10">
                                    <input id="name" name="name" type="text" placeholder="Enter name"
                                           value="{{ old('name') }}" class="form-control" data-parsley-required
                                           data-parsley-trigger="change">
                                </div>
                            </div>

                            <div class="form-group xs-mt-10">
                                <label for="label" class="col-sm-2 control-label">Label</label>
                                <div class="col-sm-10">
                                    <input id="label" name="label" type="text" placeholder="Enter label"
                                           value="{{ old('label') }}" class="form-control" data-parsley-required
                                           data-parsley-trigger="change">
                                </div>
                            </div>

                            <div class="row xs-pt-15">
                                <div class="col-xs-6 col-xs-offset-6">
                                    <p class="text-right">
                                        {{ csrf_field() }}
                                        <button type="submit" class="btn btn-space btn-primary">Submit</button>
                                        <button type="reset" class="btn btn-space btn-default">Reset</button>
                                    </p>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section("scripts")
    @include("backend/partials/form-scripts")
    <script>
        "use strict";
        $(function () {
            App.formElements();
            $('#create-form').parsley().on('field:validated', function () {
                var ok = $('.parsley-error').length === 0;
                $('.bs-callout-info').toggleClass('hidden', !ok);
                $('.bs-callout-warning').toggleClass('hidden', ok);
            })
        });
    </script>
@stop