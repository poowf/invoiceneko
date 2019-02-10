@extends("layouts.default", ['page_title' => 'Pricing'])

@section("head")
    <style>
        .pricing-panel {
        }
    </style>
@stop

@section("content")
    <div class="row pall30" style="background-color: #585454;">
        <div class="mini-container">
            <div class="col s12 center">
                <div class="hero-logo-container circle">
                    <img src="{{ asset('assets/img/logo.svg') }}" class="hero-logo-image">
                </div>
                <h2 class="hero-header white-text no-margin">Pricing</h2>
            </div>
        </div>
    </div>
    <div class="mini-container">
        <div class="row">
            <div class="col s12">
                <div class="card-panel center">
                    <i class="material-icons" style="font-size: 3em;">sentiment_very_satisfied</i>
                    <p style="font-size: 15px; font-weight: 400; color: grey;">Invoice Neko is currently free</p>
                </div>
            </div>
            <div class="col s12 m6">
                <a href="#cloud" class="service-type-selector">
                    <div class="card-panel center">
                        <i class="material-icons md-48">cloud</i>
                        <h3>Cloud</h3>
                    </div>
                </a>
            </div>
            <div class="col s12 m6">
                <a href="#self-hosted" class="service-type-selector">
                    <div class="card-panel center">
                        <i class="material-icons md-48">business</i>
                        <h3>Self Hosted</h3>
                    </div>
                </a>
            </div>
        </div>
        <div class="row">
            <div id="cloud">
                <div class="col s12 center">
                    <h3>Try InvoiceNeko</h3>
                </div>
                <div class="col s12">
                    <div class="card-panel center pricing-panel">
                        <h4>Free</h4>
                        <div style="max-width: 200px; margin: 0 auto;">
                            <div class="col s5 no-padding" style="text-align: right;"><h2 style="line-height: 0;">$0</h2></div>
                            <div class="col s7" style="text-align: left;"><p>per user <br> per month</p></div>
                            <a href="{{ route('start') }}" class="btn btn-link">Try it Out</a>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="self-hosted" style="display: none;">
                <div class="col s12 center">
                    <h3>Try InvoiceNeko Self-Hosted</h3>
                </div>
                <div class="col s12">
                    <div class="card-panel center pricing-panel">
                        <h4>Free</h4>
                        <div style="max-width: 200px; margin: 0 auto;">
                            <div class="col s5 no-padding" style="text-align: right;"><h2 style="line-height: 0;">$0</h2></div>
                            <div class="col s7" style="text-align: left;"><p>per user <br> per month</p></div>
                            <a href="{{ route('install') }}" class="btn btn-link">Install</a>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section("scripts")
    <script type="text/javascript">
        "use strict";
        $(function() {
            $('.service-type-selector').on('click', function() {
                event.preventDefault();
                if ($(this).attr('href') == '#cloud') {
                    $('#self-hosted').css('display', 'none');
                } else {
                    $('#cloud').css('display', 'none');
                }

                $($(this).attr('href')).css('display', 'block');
            });
        });
    </script>
@stop