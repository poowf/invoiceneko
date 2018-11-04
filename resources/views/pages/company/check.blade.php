@extends("layouts.default", ['page_title' => 'Company | Check'])

@section("head")
    <style>
    </style>
@stop

@section("content")
    <div class="container">
        <div class="row">
            <div class="col s12">
                <h4>Check for your company</h4>
                <form id="check-company" method="post" enctype="multipart/form-data">
                    <div class="card-panel">
                        <p style="font-size: 1.2em;">Key in your work email:</p>
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