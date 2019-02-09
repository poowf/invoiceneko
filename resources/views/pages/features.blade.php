@extends("layouts.default", ['page_title' => 'Features'])

@section("head")
    <style>
    </style>
@stop

@section("content")
    <div class="row pall30" style="background-color: #585454;">
        <div class="mini-container">
            <div class="col s12 center">
                <div class="hero-logo-container circle">
                    <img src="{{ asset('assets/img/logo.svg') }}" class="hero-logo-image">
                </div>
                <h2 class="hero-header white-text no-margin">Features of Invoice Neko</h2>
                <p class="hero-description flow-text white-text mtop20">Learn more about how Invoice Neko is different and what it can do for you</p>
            </div>
        </div>
    </div>
    <div class="mini-container">
        <div class="row">
            <div class="col s12">
                <h4>Core Features</h4>
            </div>
            <div class="col s12 m6 xl3 single-feature-card">
                <div class="card">
                    <div class="card-image waves-effect waves-block waves-light">
                        <img class="responsive-img" src="{{ asset('assets/img/multi-tenancy.png') }}">
                    </div>
                    <div class="card-content">
                        <span class="card-title grey-text text-darken-4">Multi-tenancy</span>
                        <p>Manage multiple companies within a single application without the need for separate user accounts</p>
                    </div>
                </div>
            </div>

            <div class="col s12 m6 xl3 single-feature-card">
                <div class="card">
                    <div class="card-image waves-effect waves-block waves-light">
                        <img class="responsive-img" src="{{ asset('assets/img/role-based-access-control.png') }}">
                    </div>
                    <div class="card-content">
                        <span class="card-title grey-text text-darken-4">Role based access control</span>
                        <p>Manage access with user roles. Create new roles and customise permissions per role</p>
                    </div>
                </div>
            </div>
            <div class="col s12 m6 xl3 single-feature-card">
                <div class="card">
                    <div class="card-image waves-effect waves-block waves-light">
                        <img class="responsive-img" src="{{ asset('assets/img/mobile-responsive.png') }}">
                    </div>
                    <div class="card-content">
                        <span class="card-title grey-text text-darken-4">Mobile Responsive</span>
                        <p>Site-wide mobile responsiveness for all pages</p>
                    </div>
                </div>
            </div>
            <div class="col s12 m6 xl3 single-feature-card">
                <div class="card">
                    <div class="card-image waves-effect waves-block waves-light">
                        <img class="responsive-img" src="{{ asset('assets/img/item-templates.png') }}">
                    </div>
                    <div class="card-content">
                        <span class="card-title grey-text text-darken-4">Pre-defined Item Templates</span>
                        <p>Easily create invoices with item templates that allow you to pre-populate your commonly used billing items</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col s12">
                <h4>Invoicing</h4>
            </div>
            <div class="col s12 m6 xl3 single-feature-card">
                <div class="card">
                    <div class="card-image waves-effect waves-block waves-light">
                        <img class="responsive-img" src="{{ asset('assets/img/recurring-invoices.png') }}">
                    </div>
                    <div class="card-content">
                        <span class="card-title grey-text text-darken-4">Recurring Invoices</span>
                        <p>Set-up recurring invoices and automatically notify when the invoice is due</p>
                    </div>
                </div>
            </div>
            <div class="col s12 m6 xl3 single-feature-card">
                <div class="card">
                    <div class="card-image waves-effect waves-block waves-light">
                        <img class="responsive-img" src="{{ asset('assets/img/change-history.png') }}">
                    </div>
                    <div class="card-content">
                        <span class="card-title grey-text text-darken-4">Change History/Auditing</span>
                        <p>Retrieve different invoice revisions and never forget what was previously changed</p>
                    </div>
                </div>
            </div>
            <div class="col s12 m6 xl3 single-feature-card">
                <div class="card">
                    <div class="card-image waves-effect waves-block waves-light">
                        <img class="responsive-img" src="{{ asset('assets/img/unique-link.png') }}">
                    </div>
                    <div class="card-content">
                        <span class="card-title grey-text text-darken-4">Share with Unique Link</span>
                        <p>Share the invoice with a unique link</p>
                    </div>
                </div>
            </div>
            <div class="col s12 m6 xl3 single-feature-card">
                <div class="card">
                    <div class="card-image waves-effect waves-block waves-light">
                        <img class="responsive-img" src="{{ asset('assets/img/notifications.png') }}">
                    </div>
                    <div class="card-content">
                        <span class="card-title grey-text text-darken-4">Email Notification Tracking</span>
                        <p>Know when your invoice notification has been opened by your client</p>
                    </div>
                </div>
            </div>
            <div class="col s12 m6 xl3 single-feature-card">
                <div class="card">
                    <div class="card-content">
                        <span class="card-title grey-text text-darken-4">Normal and Ad-hoc Invoices</span>
                        <p>Easily create and send out beautiful invoices of different types</p>
                    </div>
                </div>
            </div>
            <div class="col s12 m6 xl3 single-feature-card">
                <div class="card">
                    <div class="card-content">
                        <span class="card-title grey-text text-darken-4">Send Invoice Notifications</span>
                        <p>Send an email notification to your client for them to view the invoice</p>
                    </div>
                </div>
            </div>
            <div class="col s12 m6 xl3 single-feature-card">
                <div class="card">
                    <div class="card-content">
                        <span class="card-title grey-text text-darken-4">Log Payments</span>
                        <p>Log your payments as you receive them</p>
                    </div>
                </div>
            </div>
            <div class="col s12 m6 xl3 single-feature-card">
                <div class="card">
                    <div class="card-content">
                        <span class="card-title grey-text text-darken-4">Generate Receipts</span>
                        <p>Generate receipts from your invoices</p>
                    </div>
                </div>
            </div>
            <div class="col s12 m6 xl3 single-feature-card">
                <div class="card">
                    <div class="card-content">
                        <span class="card-title grey-text text-darken-4">Duplicate</span>
                        <p>Duplicate your invoices with one click</p>
                    </div>
                </div>
            </div>
            <div class="col s12 m6 xl3 single-feature-card">
                <div class="card">
                    <div class="card-content">
                        <span class="card-title grey-text text-darken-4">Save to PDF/Print</span>
                        <p>One click save to PDF or printing of invoices</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col s12">
                <h4>Quotes</h4>
            </div>
            <div class="col s12 m6 xl3 single-feature-card">
                <div class="card">
                    <div class="card-content">
                        <span class="card-title grey-text text-darken-4">Normal and Ad-hoc Quotes</span>
                        <p>Easily create and send out beautiful quotes of different types</p>
                    </div>
                </div>
            </div>
            <div class="col s12 m6 xl3 single-feature-card">
                <div class="card">
                    <div class="card-content">
                        <span class="card-title grey-text text-darken-4">Convert Quotes to Invoices</span>
                        <p>Easily convert quotes to invoices</p>
                    </div>
                </div>
            </div>
            <div class="col s12 m6 xl3 single-feature-card">
                <div class="card">
                    <div class="card-content">
                        <span class="card-title grey-text text-darken-4">Duplicate</span>
                        <p>Duplicate your quotes with one click</p>
                    </div>
                </div>
            </div>
            <div class="col s12 m6 xl3 single-feature-card">
                <div class="card">
                    <div class="card-content">
                        <span class="card-title grey-text text-darken-4">Save to PDF/Print</span>
                        <p>One click save to PDF or printing of quotes</p>
                    </div>
                </div>
            </div>
            <div class="col s12 m6 xl3 single-feature-card">
                <div class="card">
                    <div class="card-image waves-effect waves-block waves-light">
                        <img class="responsive-img" src="{{ asset('assets/img/unique-link.png') }}">
                    </div>
                    <div class="card-content">
                        <span class="card-title grey-text text-darken-4">Share with Unique Link</span>
                        <p>Share the quote with a unique link</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col s12">
                <h4>Clients</h4>
            </div>
            <div class="col s12 m6 xl3 single-feature-card">
                <div class="card">
                    <div class="card-content">
                        <span class="card-title grey-text text-darken-4">Manage Clients</span>
                        <p>Create and manage client information</p>
                    </div>
                </div>
            </div>
            <div class="col s12 m6 xl3 single-feature-card">
                <div class="card">
                    <div class="card-content">
                        <span class="card-title grey-text text-darken-4">Multiple Recipients for Notification</span>
                        <p>Notify multiple recipients when sending out an invoice notification</p>
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
        });
    </script>
@stop