@extends("layouts.default", ['page_title' => 'Company | Roles'])

@section("head")
    <style>
        #request-container {
            margin: 0px;
        }
    </style>
@stop

@section("content")
    <div class="container">
        <div class="row">
            <div class="col s6">
                <h3>Roles</h3>
            </div>
            <div class="col s6 right mtop30">
                @if($roles->isNotEmpty())
                    @can('owner', \App\Models\Company::class)
                        <a href="{{ route('company.roles.create', [ 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ]) }}" class="btn btn-link waves-effect waves-dark">Add Role</a>
                    @endcan
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col s12 m3 xl2">
                @include("partials/sidenav-company")
            </div>
            <div class="col s12 m9 xl10">
                <div id="role-container" class="row">
                    @if($roles->isNotEmpty())
                        <div class="card-panel flex">
                            <table id="requests-table" class="responsive-table striped">
                                <thead>
                                    <tr>
                                        <th>Role Name</th>
                                        @can('owner', \App\Models\Company::class)
                                            <th>Action</th>
                                        @endcan
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($roles as $key => $role)
                                        <tr class="single-request-row">
                                            <td>{{ $role->title }}</td>
                                            @can('owner', \App\Models\Company::class)
                                                <td>
                                                    @if($role->name != 'global-administrator')
                                                    <a href="{{ route('company.roles.edit', [ 'role' => $role->name, 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ] ) }}" class="tooltipped" data-position="top" data-delay="50" data-tooltip="Edit Role"><i class="material-icons">mode_edit</i></a>
                                                    <a href="#" data-id="{{ $role->title }}" class="role-delete-btn tooltipped" data-position="top" data-delay="50" data-tooltip="Delete Role"><i class="material-icons">delete</i></a>
                                                    @endif
                                                </td>
                                            @endcan
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="row">
                                <div class="col s12 center">
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
            <p>Delete Role?</p>
        </div>
        <div class="modal-footer">
            <form id="delete-role-form" method="post" class="null-form">
                {{ method_field('DELETE') }}
                {{ csrf_field() }}
                <button class="modal-action waves-effect black-text waves-green btn-flat btn-deletemodal role-confirm-delete-btn" type="submit">Delete</button>
            </form>
            <a href="javascript:;" class=" modal-action modal-close waves-effect black-text waves-red btn-flat btn-deletemodal">Cancel</a>
        </div>
    </div>
@stop

@section("scripts")
    <script type="text/javascript">
        "use strict";
        $(function() {
            Unicorn.initConfirmationTrigger('#role-container', '.role-delete-btn', '{{ \App\Library\Poowf\Unicorn::getCompanyKey() }}', 'company/roles', 'destroy', '#delete-confirmation', '#delete-role-form');
        });
    </script>
@stop