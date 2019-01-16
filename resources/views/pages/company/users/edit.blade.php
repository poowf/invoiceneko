@extends("layouts.default", ['page_title' => 'Company | Users | Edit'])

@section("head")
    <link href="{{ asset(mix('/assets/css/intlTelInput.css')) }}" rel="stylesheet" type="text/css">
    <link href="{{ asset(mix('/assets/css/selectize.css')) }}" rel="stylesheet" type="text/css">
    <style>
    </style>
@stop

@section("content")
    <div class="container">
        <div class="row">
            <div class="col s12">
                <h3>Edit User</h3>
            </div>
        </div>
        <div class="row">
            <div class="col s12 m3 xl2">
                @include("partials/sidenav-company")
            </div>
            <div class="col s12 m9 xl10">
                <form id="company-edit-user" method="post" enctype="multipart/form-data">
                    <div class="card-panel">
                        <div class="row">
                            <div class="col s12">
                                <h6>Username: {{ $user->username }}</h6>
                                <h6>Name: {{ $user->full_name }}</h6>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <select id="roles" name="roles[]" data-parsley-required="true" data-parsley-trigger="change" placeholder="Roles" multiple>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->name }}" @if($userRoles->isNotEmpty()) @foreach($userRoles as $userRole) @if($userRole == $role->name) selected @endif @endforeach @endif>{{ $role->title }}</option>
                                    @endforeach
                                </select>
                                <label for="roles" class="label-validation">Roles</label>
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
    <script type="text/javascript">
        "use strict";
        $(function() {
            Unicorn.initSelectize('#roles');
            Unicorn.initParsleyValidation('#company-edit-user');
        });
    </script>
@stop