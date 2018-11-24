@extends("layouts.default", ['page_title' => 'Company | Invite | Create'])

@section("head")
    <link href="{{ mix('/assets/css/selectize.css') }}" rel="stylesheet" type="text/css">
    <style>
    </style>
@stop

@section("content")
    <div class="container">
        <div class="row">
            <div class="col s12">
                <h3>Invite User</h3>
            </div>
        </div>
        <div class="row">
            <div class="col s12 m3 xl2">
                @include("partials/sidenav-company")
            </div>
            <div class="col s12 m9 xl10">
                <form id="company-invite-user" method="post" enctype="multipart/form-data">
                    <div class="card-panel">
                        <div class="row">
                            <div class="col s12">
                                <h6>Company: {{ app('request')->route('company')->name }}</h6>
                            </div>
                        </div>
                        @for($i = 0; $i < 5; $i++)
                            <div class="row">
                                <div class="input-field col s12 l6">
                                    <input id="email_{{ $i }}" name="email[]" type="email" data-parsley-required="@if($i == 0){{ 'true' }}@else{{ 'false' }}@endif" data-parsley-trigger="change" value="{{ old('email') }}" placeholder="Email">
                                    <label for="email" class="label-validation">Email</label>
                                    <span class="helper-text"></span>
                                </div>
                                <div class="input-field col s12 l6">
                                    <select id="roles_{{ $i }}" name="roles[{{ $i }}][]" class="role-selector" data-parsley-required="@if($i == 0){{ 'true' }}@else{{ 'false' }}@endif" data-parsley-trigger="change" placeholder="Roles" multiple>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->name }}" @if(old('roles') == $role->name) selected @endif>{{ $role->title }}</option>
                                        @endforeach
                                    </select>
                                    <label for="roles" class="label-validation">Roles</label>
                                    <span class="helper-text"></span>
                                </div>
                            </div>
                        @endfor

                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            {{ csrf_field() }}
                            <button class="btn waves-effect waves-light col s12 m3 offset-m9" type="submit" name="action">Invite</button>
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
            Unicorn.initSelectize('.role-selector');
            Unicorn.initParsleyValidation('#company-invite-user');
        });
    </script>
@stop