@extends("backend.layouts.default")

@section("head")
    @include("backend/partials/form-styles")
    <link rel="stylesheet" href="{{ asset('assets/admin/css/parsley.css') }}" type="text/css"/>
@stop

@section("content")
    <div class="page-head">
        <h2 class="page-head-title">Create New User</h2>
        <ol class="breadcrumb page-head-nav">
            <li><a href="{{ route('backend.main') }}">Home</a></li>
            <li><a href="{{ route('backend.user.index') }}">Users</a></li>
            <li class="active">Create New User</li>
        </ol>
    </div>
    <div class="main-content container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-default panel-border-color panel-border-color-primary">
                    <div class="panel-heading panel-heading-divider">Fill up the form below to create a new User<span
                                class="panel-subtitle"></span>
                    </div>
                    <div class="panel-body">
                        <form id="create-form" method="post" class="form-horizontal">
                            <div class="form-group xs-mt-10">
                                <label for="name" class="col-sm-2 control-label">Username</label>
                                <div class="col-sm-10">
                                    <input id="username" name="username" type="text" placeholder="Enter username"
                                           value="{{ old('username') }}" class="form-control" data-parsley-required
                                           data-parsley-trigger="change">
                                </div>
                            </div>

                            <div class="form-group xs-mt-10">
                                <label for="email" class="col-sm-2 control-label">Email</label>
                                <div class="col-sm-10">
                                    <input id="email" name="email" type="text" placeholder="Enter email"
                                           class="form-control" value="{{ old('email') }}" data-parsley-required
                                           data-parsley-trigger="change">
                                </div>
                            </div>

                            <div class="form-group xs-mt-10">
                                <label for="password" class="col-sm-2 control-label">Password</label>
                                <div class="col-sm-10">
                                    <input id="password" name="password" type="password" placeholder="Enter password"
                                           class="form-control" value="{{ old('password') }}" data-parsley-required
                                           data-parsley-trigger="change">
                                </div>
                            </div>

                            <div class="form-group xs-mt-10">
                                <label for="phone" class="col-sm-2 control-label">Phone</label>
                                <div class="col-sm-10">
                                    <input id="phone" name="phone" type="text" placeholder="Enter phone"
                                           class="form-control" value="{{ old('phone') }}" data-parsley-required
                                           data-parsley-trigger="change">
                                </div>
                            </div>

                            <div class="form-group xs-mt-10">
                                <label for="full_name" class="col-sm-2 control-label">Full Name</label>
                                <div class="col-sm-10">
                                    <input id="full_name" name="full_name" type="text" placeholder="Enter full name"
                                           class="form-control" value="{{ old('full_name') }}" data-parsley-required
                                           data-parsley-trigger="change">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="gender" class="col-sm-2 control-label">Gender</label>
                                <div class="col-sm-10">
                                    <div class="be-radio-icon inline">
                                        <input id="gender-male" name="gender" type="radio" value="male" @if(old('gender') == "male") checked @endif>
                                        <label for="gender-male"><span class="mdi mdi-gender-male"></span></label>
                                    </div>
                                    <div class="be-radio-icon inline">
                                        <input id="gender-female" name="gender" type="radio" value="female" @if(old('gender') == "female") checked @endif>
                                        <label for="gender-female"><span class="mdi mdi-gender-female"></span></label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group xs-mt-10">
                                <label for="roles" class="col-sm-2 control-label">Roles</label>
                                <div class="col-sm-10">
                                    <select multiple id="roles" name="roles[]" class="form-control select2" data-parsley-required
                                            data-parsley-trigger="change">
                                        @foreach($roles as $key => $role)
                                            <option value="{{ $role->id }}"
                                                    @if(old('roles'))
                                                        @foreach(old('roles') as $oldRole)
                                                            @if($oldRole == $role->id) selected @endif
                                                        @endforeach
                                                    @endif
                                            >{{ $role->label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group xs-mt-10">
                                <label for="status" class="col-sm-2 control-label">Status</label>
                                <div class="col-sm-10">
                                    <select id="status" name="status" class="form-control" data-parsley-required
                                            data-parsley-trigger="change">
                                        <option value="" disabled selected>Choose your option</option>
                                        <option value="{{ \App\Models\User::STATUS_ACTIVE }}" @if(old('status') == \App\Models\User::STATUS_ACTIVE) selected @endif>Active</option> {{-- Remember to add parentheses on the equivalent value otherwise it will be evaluated as boolean values --}}
                                        <option value="{{ \App\Models\User::STATUS_BANNED }}" @if(old('status') == \App\Models\User::STATUS_BANNED) selected @endif>Banned</option>
                                    </select>
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