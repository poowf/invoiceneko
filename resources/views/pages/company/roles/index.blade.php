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
@stop

@section("scripts")
    <script type="text/javascript">
        "use strict";
        $(function() {
        });
    </script>
@stop