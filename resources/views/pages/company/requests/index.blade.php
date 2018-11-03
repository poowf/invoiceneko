@extends("layouts.default", ['page_title' => 'Company | User Requests'])

@section("head")
    <title>{{ config('app.name') }}</title>
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
                <h3>Requests</h3>
            </div>
            <div class="col s6 right mtop30">
            </div>
        </div>
        <div class="row">
            <div class="col s12 m3 xl2">
                @include("partials/sidenav-company")
            </div>
            <div class="col s12 m9 xl10">
                <div id="request-container" class="row">
                    @if($requests->isNotEmpty())
                        <div class="card-panel flex">
                            <table id="requests-table" class="responsive-table striped">
                                <thead>
                                    <tr>
                                        <th>Full Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Status</th>
                                        @can('owner', \App\Models\Company::class)
                                            <th>Action</th>
                                        @endcan
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($requests as $key => $request)
                                        <tr class="single-request-row">
                                            <td>{{ $request->full_name }}</td>
                                            <td>{{ $request->email }}</td>
                                            <td>{{ $request->phone }}</td>
                                            <td>{{ $request->statusText() }}</td>
                                            @can('owner', \App\Models\Company::class)
                                                <td>
                                                    <form method="post" action="{{ route('company.requests.approve', [ 'companyuserrequest' => $request->id ] ) }}" class="null-form tooltipped" data-position="top" data-delay="50" data-tooltip="Approve User">
                                                        {{ csrf_field() }}
                                                        <button class="null-btn" type="submit"><i class="material-icons">check</i></button>
                                                    </form>
                                                    <form method="post" action="{{ route('company.requests.reject', [ 'companyuserrequest' => $request->id ] ) }}" class="null-form tooltipped" data-position="top" data-delay="50" data-tooltip="Reject User">
                                                        {{ csrf_field() }}
                                                        <button class="null-btn" type="submit"><i class="material-icons">close</i></button>
                                                    </form>
                                                </td>
                                            @endcan

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="row">
                                <div class="col s12 center">
                                    {!! $requests->appends(Request::except("page"))->links('partials.pagination') !!}
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