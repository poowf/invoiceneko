@extends("layouts.default", ['page_title' => 'Invoice | View'])

@section("head")
    <style>
        :root .card.single-history {
            overflow: hidden;
            margin: 0px 20px;
            padding: 35px;
            text-align: center;
        }

        .single-history-wrapper {
        }

    </style>
    <link href="{{ mix('/assets/css/slick.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ mix('/assets/css/slick-theme.css') }}" rel="stylesheet" type="text/css">
@stop

@section("content")
    <div class="mini-container">
        <div id="top-action-container" class="row desktop-only">
            <div class="col s12 mtop30 right">
                <form method="post" action="{{ route('invoice.send', [ 'invoice' => $invoice->id ] ) }}" class="null-form">
                    {{ csrf_field() }}
                    <button class="btn btn-link waves-effect waves-dark null-btn" type="submit">Send Notification</button>
                </form>
                <a class="btn btn-link waves-effect waves-dark" href="{{ route('payment.create', [ 'invoice' => $invoice->id] ) }}">
                    Log Payment
                </a>
                <a href="#" data-id="{{ $invoice->id }}" class="invoice-share-btn btn btn-link waves-effect waves-dark">
                    Share
                </a>
                <a class="btn btn-link waves-effect waves-dark" href="{{ route('invoice.download', [ 'invoice' => $invoice->id] ) }}">
                    Save PDF
                </a>
                <a class="btn btn-link waves-effect waves-dark" href="{{ route('invoice.printview', [ 'invoice' => $invoice->id] ) }}">
                    Print
                </a>
            </div>
        </div>
        <div id="invoice-action-container" class="row mbtm0 desktop-only">
            <div class="col s12 right">
                <form method="post" action="{{ route('invoice.convert', [ 'invoice' => $invoice->id ] ) }}" class="null-form">
                    {{ csrf_field() }}
                    <button class="btn btn-link waves-effect waves-dark null-btn" type="submit">Convert back to Quote</button>
                </form>
                <form method="post" action="{{ route('invoice.duplicate', [ 'invoice' => $invoice->id ] ) }}" class="null-form">
                    {{ csrf_field() }}
                    <button class="btn blue darken-3 waves-effect waves-dark null-btn" type="submit">Clone</button>
                </form>
                <form method="post" action="{{ route('invoice.writeoff', [ 'invoice' => $invoice->id ] ) }}" class="null-form">
                    {{ method_field('PATCH') }}
                    {{ csrf_field() }}
                    <button class="btn deep-orange darken-2 waves-effect waves-dark null-btn" type="submit">Write Off</button>
                </form>
                <form method="post" action="{{ route('invoice.archive', [ 'invoice' => $invoice->id ] ) }}" class="null-form">
                    {{ method_field('PATCH') }}
                    {{ csrf_field() }}
                    <button class="btn amber darken-2 waves-effect waves-dark null-btn" type="submit">Archive</button>
                </form>
                <a href="{{ route('invoice.edit', [ 'invoice' => $invoice->id ] ) }}" class="btn light-blue waves-effect waves-dark">
                    Edit
                </a>
                <a href="#" data-id="{{ $invoice->id }}" class="invoice-delete-btn btn red waves-effect waves-dark">
                    Delete
                </a>
            </div>
        </div>
        <div class="fixed-action-btn toolbar mobile-only">
            <a class="btn-floating btn-large btn-large red">
                <i class="large material-icons">menu</i>
            </a>
            <ul>
                <li class="tooltipped" data-position="top" data-delay="50" data-tooltip="Log Payment">
                    <a class="btn btn-link waves-effect waves-dark" href="{{ route('payment.create', [ 'invoice' => $invoice->id] ) }}">
                        <i class="material-icons">attach_money</i>
                    </a>
                </li>
                <li class="tooltipped" data-position="top" data-delay="50" data-tooltip="Duplicate Invoice">
                    <form method="post" action="{{ route('invoice.duplicate', [ 'invoice' => $invoice->id ] ) }}" class="null-form">
                        {{ csrf_field() }}
                        <button class="btn blue darken-3 waves-effect waves-dark null-btn" type="submit">
                            <i class="material-icons">control_point_duplicate</i>
                        </button>
                    </form>
                </li>
                <li class="tooltipped" data-position="top" data-delay="50" data-tooltip="Send Notification">
                    <form method="post" action="{{ route('invoice.send', [ 'invoice' => $invoice->id ] ) }}" class="null-form">
                        {{ csrf_field() }}
                        <button class="btn blue-grey waves-effect waves-dark null-btn" type="submit">
                            <i class="material-icons">contact_mail</i>
                        </button>
                    </form>
                </li>
                <li class="tooltipped" data-position="top" data-delay="50" data-tooltip="Archive Invoice">
                    <form method="post" action="{{ route('invoice.archive', [ 'invoice' => $invoice->id ] ) }}" class="null-form">
                        {{ method_field('PATCH') }}
                        {{ csrf_field() }}
                        <button class="btn amber darken-2 waves-effect waves-dark null-btn" type="submit">
                            <i class="material-icons">archive</i>
                        </button>
                    </form>
                </li>
                <li class="tooltipped" data-position="top" data-delay="50" data-tooltip="Edit Invoice">
                    <a href="{{ route('invoice.edit', [ 'invoice' => $invoice->id ] ) }}" class="btn light-blue waves-effect waves-dark">
                        <i class="material-icons">edit</i>
                    </a>
                </li>
                <li class="tooltipped" data-position="top" data-delay="50" data-tooltip="Delete Invoice">
                    <a href="#" data-id="{{ $invoice->id }}" class="invoice-delete-btn btn red waves-effect waves-dark">
                        <i class="material-icons">delete</i>
                    </a>
                </li>
            </ul>
        </div>
        <div class="row">
            <div class="col s12 l4">
                <h3>Details</h3>
                <div id="details-panel" class="card-panel">
                    <dl>
                        <dt>Company Name</dt>
                        <dd>{{ $client->companyname }}</dd>
                        <dt>Company Block</dt>
                        <dd>{{ $client->block ?? '-' }}</dd>
                        <dt>Company Street</dt>
                        <dd>{{ $client->street ?? '-' }}</dd>
                        <dt>Company Unit Number</dt>
                        <dd>{{ $client->unitnumber ?? '-' }}</dd>
                        <dt>Company Postal Code</dt>
                        <dd>{{ $client->postalcode ?? '-' }}</dd>
                        <dt>Company Nickname</dt>
                        <dd>{{ $client->nickname ?? '-' }}</dd>
                        <dt>Company Registration Number</dt>
                        <dd>{{ $client->crn }}
                        <dt>Contact Name</dt>
                        <dd>{{ $client->contactname ?? '-' }}</dd>
                        <dt>Contact Email</dt>
                        <dd>{{ $client->contactemail ?? '-' }}</dd>
                        <dt>Contact Phone</dt>
                        <dd>{{ $client->contactphone ?? '-' }}</dd>
                        <dt>Status</dt>
                        <dd>
                            @if ($invoice->status == App\Models\Invoice::STATUS_OVERDUE)
                                <span class="alt-badge error">{{ $invoice->statustext() }}</span>
                            @elseif ($invoice->status == App\Models\Invoice::STATUS_DRAFT)
                                <span class="alt-badge">{{ $invoice->statustext() }}</span>
                            @elseif ($invoice->status == App\Models\Invoice::STATUS_OPEN)
                                <span class="alt-badge warning">{{ $invoice->statustext() }}</span>
                            @elseif ($invoice->status == App\Models\Invoice::STATUS_CLOSED)
                                <span class="alt-badge success">{{ $invoice->statustext() }}</span>
                            @elseif ($invoice->status == App\Models\Invoice::STATUS_WRITTENOFF)
                                <span class="alt-badge grey">{{ $invoice->statustext() }}</span>
                            @endif
                        </dd>
                    </dl>
                </div>
                <h3>Change History</h3>
                <div id="change-history-container" class="change-history-container">
                    <div class="single-history-wrapper">
                        <div class="card single-history">
                            <h6>Date/Time</h6>
                            <p class="mtop20">{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $invoice->updated_at)->format('j F, Y') }}</p>
                            <p>{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $invoice->updated_at)->format('h:i:s a') }}</p>
                            <span class="alt-badge info mtop20">Current Version</span>
                            <a href="{{ route('invoice.edit', [ 'invoice' => $invoice->id ] ) }}" class="btn btn-theme full-width mtop20">Edit</a>
                        </div>
                    </div>
                    @foreach($histories as $key => $history)
                        <div class="single-history-wrapper">
                            <div class="card single-history">
                                <h6>Date/Time</h6>
                                <p class="mtop20">{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $history->updated_at)->format('j F, Y') }}</p>
                                <p>{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $history->updated_at)->format('h:i:s a') }}</p>
                                <span class="alt-badge warning mtop20">Past Version</span>
                                <a href="{{ route('invoice.old.show', [ 'oldinvoice' => $history->id ] ) }}" class="btn btn-theme full-width mtop20">View</a>
                            </div>
                        </div>
                    @endforeach
                </div>
                <h3>Payment History</h3>
                <div id="payment-history-container" class="payment-history-container">
                    <div class="card-panel flex">
                        <table class="responsive-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Mode</th>
                                    <th>Amount</th>
                                    <th>Percentage of Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($payments as $key => $payment)
                                    <tr>
                                        <td>{{ $payment->date_format }}</td>
                                        <td>{{ $payment->mode }}</td>
                                        <td>S${{ $payment->money_format }}</td>
                                        <td><span class="alt-badge teal lighten-1">{{ $payment->percentage }}%</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col s12 l8">
                <h3>Invoice</h3>
                <iframe src="{{ route('invoice.printview', [ 'invoice' => $invoice->id] ) }}" style="height: 100vh; width: 100%"></iframe>
            </div>
        </div>
    </div>

    <div id="delete-confirmation" class="modal">
        <div class="modal-content">
            <p>Delete Invoice?</p>
        </div>
        <div class="modal-footer">
            <form id="delete-invoice-form" method="post" class="null-form">
                {{ method_field('DELETE') }}
                {{ csrf_field() }}
                <button class="modal-action waves-effect black-text waves-green btn-flat btn-deletemodal invoice-confirm-delete-btn" type="submit">Delete</button>
            </form>
            <a href="javascript:;" class=" modal-action modal-close waves-effect black-text waves-red btn-flat btn-deletemodal">Cancel</a>
        </div>
    </div>

    <div id="shared-details" class="modal">
        <div class="modal-content">
            <div class="left">
                <h5>Share</h5>
            </div>
            <div class="right">
                <a href="#" data-id="{{ $invoice->id }}" class="invoice-regenerate-btn btn btn-link waves-effect waves-dark">
                    Regenerate Link
                </a>
            </div>
            <input id="shared-link" type="text" value="{{ route('invoice.token', [ 'token' => $invoice->share_token] ) }}">
        </div>
    </div>
@stop

@section("scripts")
    <script type="text/javascript">
        "use strict";
        $(function() {

            $('#change-history-container').on('init', function(event, slick, direction){
                let height = $('#details-panel').outerHeight();
                console.log($(this).find('.single-history').css('height', height));
                // left
            });

            $('#top-action-container').on('click', '.invoice-share-btn', function (event) {
                event.preventDefault();
                if($('#shared-link').val() == "")
                {
                    getSharedLink();
                }
                else
                {
                    $('#shared-details').modal('open');
                }
            });

            $('#shared-details').on('click', '.invoice-regenerate-btn', function (event) {
                event.preventDefault();
                getSharedLink();
            });


            $('#invoice-action-container').on('click', '.invoice-delete-btn', function (event) {
                event.preventDefault();
                let invoiceid = $(this).attr('data-id');
                $('#delete-invoice-form').attr('action', '/invoice/' + invoiceid + '/destroy');
                $('#delete-confirmation').modal('open');
            });

            $('.fixed-action-btn').on('click', '.invoice-delete-btn', function (event) {
                event.preventDefault();
                let invoiceid = $(this).attr('data-id');
                $('#delete-invoice-form').attr('action', '/invoice/' + invoiceid + '/destroy');
                $('#delete-confirmation').modal('open');
            });

            $('#change-history-container').slick({
                // normal options...
                infinite: false,
                arrows: false,
                dots: true,
                slidesToShow: 2,
                slidesToScroll: 2,
                adaptiveHeight: false,
                responsive: [
                    {
                        breakpoint: 1750,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1,
                            dots: true
                        }
                    },
                    {
                        breakpoint: 993,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 2,
                            dots: true
                        }
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1
                        }
                    }
                    // You can unslick at a given breakpoint now by adding:
                    // settings: "unslick"
                    // instead of a settings object
                ]
            });

            function getSharedLink()
            {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: "PATCH",
                    url: "{{ route('invoice.share', [ 'invoice' => $invoice->id]) }}",
                })
                .done(function(data) {
                    $('#shared-link').val("{{ route('invoice.token') }}?token=" + data);
                    $('#shared-details').modal('open');
                });
            }
        });
    </script>
@stop