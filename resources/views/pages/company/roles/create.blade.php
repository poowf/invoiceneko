@extends("layouts.default", ['page_title' => 'Company | Roles | Create'])

@section("head")
    <style>
    </style>
@stop

@section("content")
    <div class="container">
        <div class="row">
            <div class="col s12">
                <h3>Create Role</h3>
            </div>
        </div>
        <div class="row">
            <div class="col s12">
                <form id="create-role" method="post" enctype="multipart/form-data">
                    <div class="card-panel">
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="title" name="title" type="text" data-parsley-required="true" data-parsley-trigger="change" data-parsley-minlength="4" value="{{ old('title') }}" placeholder="Name">
                                <label for="title" class="label-validation">Title</label>
                                <span class="helper-text"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col s12">
                                <h6>Permissions</h6>
                            </div>
                        </div>
                        <div class="row">
                            @for($i = 0; $i < $permissions->count(); $i++)
                                @if($i != 0 && $permissions[$i]->type != $permissions[$i - 1]->type)
                                    <div class="col s12 mtop20">
                                        <h6>{{ $permissions[$i]->type }}</h6>
                                    </div>
                                @endif
                                <div class="input-field col s6 m3">
                                    <label for="permissions[]" class="label-validation">{{ $permissions[$i]->title }}</label>
                                    <div class="switch mtop20">
                                        <label>
                                            Deny
                                            <input id="permissions[]" name="permissions[]" type="checkbox" value="{{ $permissions[$i]->name . '-' . $permissions[$i]->type }}">
                                            <span class="lever"></span>
                                            Allow
                                        </label>
                                    </div>
                                </div>
                            @endfor
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            {{ csrf_field() }}
                            <button class="btn waves-effect waves-light col s12 m3 offset-m9" type="submit" name="action">Create</button>
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
            Unicorn.initParsleyValidation('#create-role');
        });
    </script>
@stop