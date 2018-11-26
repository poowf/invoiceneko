@extends("layouts.default", ['page_title' => 'Invoice Neko'])

@section("head")
    <style>
    </style>
@stop

@section("content")
        <div class="banner-anchor"></div>
        <div class="banner">
            <div class="banner-description">
                <span>Verify your email address. Didn't receive a verification email? Click the Resend button to get a new one.</span>
            </div>
            <div class="banner-action right">
                <a href="#!" class="banner-close waves-effect waves-red btn-flat">Dismiss</a>
                <a href="#!" class="waves-effect waves-green btn-flat">Resend Verification Email</a>
            </div>
        </div>
        <div class="row pall30" style="background-color: #585454; clear: right;">
            <div class="mini-container">
                <div class="col s12 l6">
                    <div class="hero-left-wrapper">
                        <img src="{{ asset('assets/img/avatar.png') }}" class="hero-logo-image">
                        <h2 class="hero-header white-text no-margin">Invoice Neko</h2>
                        <p class="hero-description flow-tex white-text mtop20">Invoice Neko is a self-hosted open sourced invoicing system built on a modern backend with a focus on delivering a good user experience throughout the application</p>
                        <a href="{{ route('start') }}" class="btn btn-large btn-theme mtop10">Start Here</a>
                    </div>
                </div>
                <div class="col s12 l6 mtop30 center desktop-only">
                    <img src="{{ asset('assets/svg/front.svg') }}" class="hero-right-image">
                </div>
            </div>
        </div>
        <div class="mini-container">
            <div class="row">
                <div class="col s12 m4">
                    <div class="card-panel center">
                        <img src="{{ asset('assets/svg/invoice.svg') }}" height="200">
                        <h5>Invoice</h5>
                        <p>Create Invoices on the go or at your desk, with the item templating system, you can create an invoice in less than a minute</p>
                    </div>
                </div>
                <div class="col s12 m4">
                    <div class="card-panel center">
                        <img src="{{ asset('assets/svg/payment.svg') }}" height="200">
                        <h5>Payment</h5>
                        <p>Received a Payment for an Invoice? Log the Payment and it will keep track of how much is left or if the invoice has been paid in full</p>
                    </div>
                </div>
                <div class="col s12 m4">
                    <div class="card-panel center">
                        <img src="{{ asset('assets/svg/receipt.svg') }}" height="200">
                        <h5>Receipt</h5>
                        <p>Generate Receipts for the Invoices that have been paid in full to send over to your customer/client for their bookkeeping</p>
                    </div>
                </div>
            </div>
        </div>
@stop

@section("scripts")
    <script type="text/javascript">
        "use strict";
        $(function() {

            $('body').on('click', '.banner-close', function() {
                let $el = $(this).parent().parent('.banner').removeAttr('style').addClass('close');
                let $elNext = $el.next().css('margin-top', '0');

            });

            // moveScroller();
            //
            // function moveScroller() {
            //     var $anchor = $(".banner-anchor");
            //     var $scroller = $('.banner');
            //
            //     var move = function() {
            //         var st = $(window).scrollTop();
            //         var ot = $anchor.offset().top;
            //         if(st > ot) {
            //             $scroller.css({
            //                 position: "fixed",
            //                 top: "0px"
            //             });
            //         } else {
            //             $scroller.css({
            //                 position: "relative",
            //                 top: ""
            //             });
            //         }
            //     };
            //     $(window).scroll(move);
            //     move();
            // }

            $(window).scroll(function(e){
                let $el = $('.banner');
                let $elNext = $el.next();

                if (!$el.hasClass('close') && $(this).scrollTop() > 80){
                    $el.css('position', 'fixed');
                    $el.css('transform', 'translateY(-80px)');
                    $el.css('z-index', '1');
                    $elNext.css('margin-top', ($el.height() - ($(this).scrollTop() - 80)) + 'px');
                    // $el.css('transform', 'translateY(' + (80 - $(this).scrollTop())  + 'px)');
                }
                if (!$el.hasClass('close') && $(this).scrollTop() < 80){
                    $el.css('position', 'relative');
                    $el.css('transform', 'translateY(0)');
                    $el.css('z-index', '-1');
                    $elNext.css('margin-top', '0');
                    // $el.css('transform', 'translateY(' + (0 - $(this).scrollTop())  + 'px)');
                }
            });
        });
    </script>
@stop