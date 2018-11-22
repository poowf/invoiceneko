@extends("layouts.default", ['page_title' => 'Company | Roles | Create'])

@section("head")
    <style>
        {{-- TODO: Probably need to do some A/B Testing for the UI on this page --}}

        .all-permission-selector {
            cursor: pointer;
        }

        .all-permission-selector:hover {
            background-color: #299a9a;
            color: #ffffff;
        }

        .all-permission-selector h6 {
            margin: 10px 0;
        }

        .switch.all-permissions {
            text-align: right;
        }

        .switch label .lever
        {
            margin-left: 0;
        }
    </style>
@stop

@section("content")
    <div class="container">
        <div class="row">
            <div class="col s12">
                <h3>Edit Role</h3>
            </div>
        </div>
        <div class="row">
            <div class="col s12">
                <form id="edit-role" method="post" enctype="multipart/form-data">
                    <div class="card-panel">
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="title" name="title" type="text" data-parsley-required="true" data-parsley-trigger="change" data-parsley-minlength="4" value="{{ $role->title }}" placeholder="Name">
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
                                    <div class="col s12 mtop20 all-permission-selector tooltipped" data-permission-type="{{ str_slug(strtolower($permissions[$i]->type)) }}" data-position="top" data-delay="50" data-tooltip="Click here to turn on all permissions">
                                        <h6>{{ $permissions[$i]->type }}</h6>
                                        {{--<div class="switch all-permissions mtop10">--}}
                                        {{--<label>--}}
                                        {{--<input id="manage-{{ str_slug(strtolower($permissions[$i]->type)) }}" name="permissions[]" class="manage-{{ str_slug(strtolower($permissions[$i]->type)) }}" type="checkbox">--}}
                                        {{--<span class="lever"></span>--}}
                                        {{--Allow All--}}
                                        {{--</label>--}}
                                        {{--</div>--}}
                                    </div>

                                @endif
                                <div class="input-field col s6 m3">
                                    <label for="permissions-{{ $permissions[$i]->name }}" class="label-validation">{{ $permissions[$i]->title }}</label>
                                    <div class="switch mtop20">
                                        <label>
                                            <input id="permissions-{{ $permissions[$i]->name }}" name="permissions[]" data-permission-type="{{ str_slug(strtolower($permissions[$i]->type)) }}" type="checkbox" value="{{ $permissions[$i]->name }}" @foreach($rolePermissions as $rolePermission) @if(($permissions[$i]->name . $permissions[$i]->entity_type) == ($rolePermission->name . $rolePermission->entity_type)) checked @endif @endforeach>
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
            $('#edit-role').on('click', '.all-permission-selector', function (event) {
                let type = $(this).attr('data-permission-type');

                if($('input[data-permission-type=' + type + ']').prop('checked'))
                {
                    $('input[data-permission-type=' + type + ']').prop('checked', false);
                }
                else
                {
                    $('input[data-permission-type=' + type + ']').prop('checked', true);
                }
            });

            $('#edit-role').on('click', 'input[id^=permissions-update]', function (event) {
                let type = $(this).attr('data-permission-type');
                if(!$('input[id=permissions-view-' + type +']').prop('checked'))
                {
                    $('input[id=permissions-view-' + type +']').prop('checked', true);
                }
            });

            $('#edit-role').on('click', 'input[id^=permissions-create]', function (event) {
                let type = $(this).attr('data-permission-type');
                if(!$('input[id=permissions-view-' + type +']').prop('checked'))
                {
                    $('input[id=permissions-view-' + type +']').prop('checked', true);
                }
            });

            $('#edit-role').on('click', 'input[id^=permissions-delete]', function (event) {
                let type = $(this).attr('data-permission-type');
                if(!$('input[id=permissions-view-' + type +']').prop('checked'))
                {
                    $('input[id=permissions-view-' + type +']').prop('checked', true);
                }
            });

            Unicorn.initParsleyValidation('#edit-role');
        });
    </script>
@stop