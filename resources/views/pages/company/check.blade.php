@extends("layouts.default", ['page_title' => 'Company | Check'])

@section("head")
    <style>
    </style>
@stop

@section("content")
    <div class="container">
        <div class="row mtop30">
            <div class="col s12">
                <form id="check-company" method="post" enctype="multipart/form-data">
                    <div class="card-panel">
                        <a href="{{ route('user.create', [ 'hasinvite' => 'true' ]) }}" class="btn btn-link">Click here if you have an invite link</a>
                        <h5>Check for your company</h5>
                        <p>We will check if your company has a registered account on {{ config('app.name') }}</p>
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="email" name="email" type="email" data-parsley-required="true" data-parsley-trigger="change" value="{{ old('email') }}" placeholder="Email">
                                <label for="email" class="label-validation">Email</label>
                                <span class="helper-text"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            {{ csrf_field() }}
                            <button id="check-company-btn" class="btn waves-effect waves-light full-width" type="submit" name="action">Check</button>
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
            Unicorn.initParsleyValidation('#check-company');
        });
    </script>
@stop