@extends("layouts.default", ['page_title' => 'Contact'])

@section("head")
    <style type="text/css">
    </style>
@stop

@section("content")
    <div class="row pall30" style="background-color: #585454;">
        <div class="mini-container">
            <div class="col s12">
                <h2 class="hero-header white-text no-margin">Have something to talk to us about?</h2>
                <p class="hero-description flow-text white-text mtop20">Fill in the form below and let us know</p>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row mtop30">
            <div class="card">
                <div class="card-header theme-color-secondary"><p>Contact</p></div>
                <div class="card-content">
                    <form id="contact-form" method="post">
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="name" name="name" type="text" data-parsley-required="true" data-parsley-trigger="change" @auth value="{{ auth()->user()->full_name }}" @endauth @guest value="{{ old('name') }}" @endguest  placeholder="Given Name">
                                <label for="name" class="label-validation">Name</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="email" name="email" type="email" data-parsley-required="true" data-parsley-trigger="change" @auth value="{{ auth()->user()->email }}" @endauth @guest value="{{ old('email') }}" @endguest placeholder="Email Address">
                                <label for="email" class="label-validation">Email</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <textarea id="message" name="message" class="materialize-textarea" data-parsley-required="true" data-parsley-trigger="change" placeholder="Message" data-parsley-id="10"></textarea>
                                <label for="message" class="label-validation active">Message</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                {{ csrf_field() }}
                                <button class="btn btn-theme waves-effect waves-light full-width" type="submit" name="action">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section("scripts")
    <script type="text/javascript">
        "use strict";
        $(function() {
            Unicorn.initParsleyValidation('#contact-form');
        });
    </script>
@stop