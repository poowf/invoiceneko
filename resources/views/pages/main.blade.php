@extends("layouts.default", ['page_title' => 'Invoice Neko'])

@section("head")
    <style>
    </style>
@stop

@section("content")
        <div class="row pall30" style="background-color: #585454;">
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
        });
    </script>
@stop