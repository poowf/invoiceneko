@extends("layouts.default", ['page_title' => 'Company | Users'])

@section("head")
    <title>{{ config('app.name') }}</title>
    <style>
        #user-container {
            margin: 0px;
        }
    </style>
@stop

@section("content")
    <div class="container">
        <div class="row">
            <div class="col s6">
                <h3>Users</h3>
            </div>
            <div class="col s6 right mtop30">
                @if($users->isNotEmpty())
                    @can('owner', \App\Models\Company::class)
                        <a href="{{ route('company.users.create') }}" class="btn btn-link waves-effect waves-dark">Add User</a>
                    @endcan
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col s12 m3 xl2">
                @include("partials/sidenav-company")
            </div>
            <div class="col s12 m9 xl10">
                <div id="user-container" class="row">
                    @if($users->isNotEmpty())
                        <div class="card-panel flex">
                            <table id="users-table" class="responsive-table striped">
                                <thead>
                                    <tr>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>Full Name</th>
                                        <th>Phone</th>
                                        <th>Status</th>
                                        @can('owner', \App\Models\Company::class)
                                            <th>Action</th>
                                        @endcan
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($users as $key => $user)
                                        <tr class="single-user-row">
                                            <td>{{ $user->username }}  @if($user->owns($company))<span class="alt-badge mini-padding success">Owner</span>
                                                @endif</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->full_name }}</td>
                                            <td>{{ $user->phone }}</td>
                                            <td>{{ $user->statusText() }}</td>
                                            @can('owner', \App\Models\Company::class)
                                                <td>
                                                    <a href="{{ route('company.users.edit', [ 'user' => $user->id ] ) }}" class="tooltipped" data-position="top" data-delay="50" data-tooltip="Edit User"><i class="material-icons">mode_edit</i></a>
                                                    @if($user->id != auth()->user()->id)
                                                    <a href="" data-id="{{ $user->id }}"  class="tooltipped user-delete-btn" data-position="top" data-delay="50" data-tooltip="Remove User"><i class="material-icons">remove_circle</i></a>
                                                    @endif
                                                </td>
                                            @endcan

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="row">
                                <div class="col s12 center">
                                    {!! $users->appends(Request::except("page"))->links('partials.pagination') !!}
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="card-panel center">
                            <i class="material-icons" style="font-size: 3em; color: grey;">sentiment_dissatisfied</i>
                            <p style="font-size: 15px; font-weight: 400; color: grey;">There's nothing here</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div id="delete-confirmation" class="modal">
        <div class="modal-content">
            <p>Delete User?</p>
        </div>
        <div class="modal-footer">
            <form id="delete-user-form" method="post" class="null-form">
                {{ method_field('DELETE') }}
                {{ csrf_field() }}
                <button class="modal-action waves-effect black-text waves-green btn-flat btn-deletemodal user-confirm-delete-btn" type="submit">Delete</button>
            </form>
            <a href="javascript:;" class=" modal-action modal-close waves-effect black-text waves-red btn-flat btn-deletemodal">Cancel</a>
        </div>
    </div>
@stop

@section("scripts")
    <script type="text/javascript">
        "use strict";
        $(function() {
            $('.modal').modal();

            $('#user-container').on('click', '.user-delete-btn', function (event) {
                event.preventDefault();
                var userid = $(this).attr('data-id');
                $('#delete-user-form').attr('action', '/company/users/' + userid + '/destroy');
                $('#delete-confirmation').modal('open');
            });
        });
    </script>
@stop