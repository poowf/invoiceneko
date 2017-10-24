@extends("backend/layouts/default")

@section("head")
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/backend/lib/datatables/css/dataTables.bootstrap.min.css') }}"/>
    <style>
    </style>
@stop

@section("content")
    <div class="page-head">
        <h2 class="page-head-title">Roles</h2>
        <ol class="breadcrumb page-head-nav">
            <li><a href="{{ route('backend.main') }}">Home</a></li>
            <li><a href="#">Roles</a></li>
            <li class="active">View</li>
        </ol>
    </div>
    <div class="main-content container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-default panel-table">
                    <div class="panel-heading">Roles
                        <div class="tools dropdown"><span class="icon mdi mdi-download"></span><a href="#" type="button" data-toggle="dropdown" class="dropdown-toggle"><span class="icon mdi mdi-dots-vertical"></span></a>
                            <ul role="menu" class="dropdown-menu pull-right">
                                <li><a href="#">Action</a></li>
                                <li><a href="#">Another action</a></li>
                                <li><a href="#">Something else here</a></li>
                                <li class="divider"></li>
                                <li><a href="#">Separated link</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="panel-body">
                        <table id="role-table" class="table table-striped table-hover table-fw-widget">
                            <thead>
                            <tr>
                                <th>Label</th>
                                <th>Name</th>
                                <th class="actions">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($roles as $key => $role)
                                <tr class="gradeX" role="row">
                                    <td class="center">{{ $role->name }}</td>
                                    <td class="center">{{ $role->label }}</td>
                                    <td class="actions center">
                                        <a href="{{ route('backend.role.edit', [ 'role' => $role->id ]) }}" class="icon"><i class="mdi mdi-pen"></i></a>
                                        <form method="post" action="{{ route('backend.role.destroy', [ 'role' => $role->id ]) }}" class="icon inline">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE')}}
                                            <button type="submit" class="designless-btn"><i class="mdi mdi-delete"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section("scripts")
    <script src="{{ asset('assets/backend/lib/datatables/js/jquery.dataTables.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/backend/lib/datatables/js/dataTables.bootstrap.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/backend/lib/datatables/plugins/buttons/js/dataTables.buttons.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        "use strict";
        $(function() {
            $.extend( true, $.fn.dataTable.defaults, {
                dom:
                "<'row be-datatable-header'<'col-sm-6'l><'col-sm-6'f>>" +
                "<'row be-datatable-body'<'col-sm-12'tr>>" +
                "<'row be-datatable-footer'<'col-sm-5'i><'col-sm-7'p>>"
            } );

            $('#role-table').dataTable( {
                "order": [[ 6, "desc" ]]
            } );
        });
    </script>
@stop