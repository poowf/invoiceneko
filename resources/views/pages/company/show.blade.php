@extends("layouts.default", ['page_title' => 'Company | View'])

@section("head")
    <style>
    </style>
@stop

@section("content")
    <div class="container">
        <div class="row">
            <div class="col s12">
                <h3>Company</h3>
            </div>
        </div>
        <div class="row">
            <div class="col s12 m3 xl2">
                @include("partials/sidenav-company")
            </div>
            <div class="col s12 m9 xl10">
                <div class="card-panel">
                    <dl>
                        <dt>Company Name</dt>
                        <dd>{{ $company->name }}</dd>
                        <dt>Company Domain</dt>
                        <dd>{{ $company->domain_name ?? '-' }}</dd>
                        <dt>Company Registration Number</dt>
                        <dd>{{ $company->crn ?? '-' }}</dd>
                        <dt>Company Email</dt>
                        <dd>{{ $company->email ?? '-' }}</dd>
                        <dt>Company Phone</dt>
                        <dd>{{ $company->phone ?? '-' }}</dd>
                        <dt>Company Logo</dt>
                        <dd><img src="@if($company){{ \App\Library\Poowf\Unicorn::getStorageFile($company->logo, [420, 220]) }}@else{!! 'https://via.placeholder.com/420x220' !!}@endif" class="responsive-img"></dd>
                        <dt>Company Small Logo</dt>
                        <dd><img src="@if($company){{ \App\Library\Poowf\Unicorn::getStorageFile($company->smlogo, [200, 200]) }}@else{!! 'https://via.placeholder.com/200x200' !!}@endif" class="responsive-img"></dd>
                    </dl>
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