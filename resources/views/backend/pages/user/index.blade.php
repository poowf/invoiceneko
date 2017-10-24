@extends("backend/layouts/default")

@section("head")
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/backend/lib/datatables/css/dataTables.bootstrap.min.css') }}"/>
    <style>
    </style>
@stop

@section("content")
    <div class="page-head">
        <h2 class="page-head-title">Users</h2>
        <ol class="breadcrumb page-head-nav">
            <li><a href="{{ route('backend.main') }}">Home</a></li>
            <li><a href="{{ route('backend.user.index') }}">Users</a></li>
            <li class="active">View</li>
        </ol>
    </div>
    <div class="main-content container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-default panel-table">
                    <div class="panel-heading">Users
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
                        <table id="users-table" class="table table-striped table-hover table-fw-widget">
                            <thead>
                            <tr>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Full Name</th>
                                <th>Gender</th>
                                <th>Created At</th>
                                <th>Updated At</th>
                                <th class="actions">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($users as $key => $user)
                                <tr class="gradeX" role="row">
                                    <td class="center">{{ $user->username }}</td>
                                    <td class="center">{{ $user->email }}</td>
                                    <td class="center">{{ $user->phone }}</td>
                                    <td class="center">{{ $user->full_name }}</td>
                                    <td class="center">{{ $user->gender }}</td>
                                    <td class="center">{{ $user->created_at }}</td>
                                    <td class="center">{{ $user->updated_at }}</td>
                                    <td class="actions center">
                                        <a href="{{ route('backend.user.edit', [ 'user' => $user->id ]) }}" class="icon"><i class="mdi mdi-pen"></i></a>
                                        <form method="post" action="{{ route('backend.user.destroy', [ 'user' => $user->id ]) }}" class="icon inline">
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

            $('#users-table').dataTable( {
                "order": [[ 6, "desc" ]]
            } );
        });
    </script>
@stop