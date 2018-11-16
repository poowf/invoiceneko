@extends("layouts.default", ['page_title' => 'Quote | View'])

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
                <a href="#" data-id="{{ $quote->id }}" class="quote-share-btn btn btn-link waves-effect waves-dark">
                    Share
                </a>
                <a class="btn btn-link waves-effect waves-dark" href="{{ route('quote.download', [ 'quote' => $quote->id] ) }}">
                    Save PDF
                </a>
                <a class="btn btn-link waves-effect waves-dark" href="{{ route('quote.printview', [ 'quote' => $quote->id] ) }}">
                    Print
                </a>
            </div>
        </div>
        <div id="quote-action-container" class="row mbtm0 desktop-only">
            <div class="col s12 right">
                <form method="post" action="{{ route('quote.convert', [ 'quote' => $quote->id ] ) }}" class="null-form">
                    {{ csrf_field() }}
                    <button class="btn btn-link waves-effect waves-dark null-btn" type="submit">Convert to Invoice</button>
                </form>
                <form method="post" action="{{ route('quote.duplicate', [ 'quote' => $quote->id ] ) }}" class="null-form">
                    {{ csrf_field() }}
                    <button class="btn blue darken-3 waves-effect waves-dark null-btn" type="submit">Clone</button>
                </form>
                <form method="post" action="{{ route('quote.archive', [ 'quote' => $quote->id ] ) }}" class="null-form">
                    {{ method_field('PATCH') }}
                    {{ csrf_field() }}
                    <button class="btn amber darken-2 waves-effect waves-dark null-btn" type="submit">Archive</button>
                </form>
                <a href="{{ route('quote.edit', [ 'quote' => $quote->id ] ) }}" class="btn light-blue waves-effect waves-dark">
                    Edit
                </a>
                <a href="#" data-id="{{ $quote->id }}" class="quote-delete-btn btn red waves-effect waves-dark">
                    Delete
                </a>
            </div>
        </div>
        <div class="fixed-action-btn toolbar mobile-only">
            <a class="btn-floating btn-large btn-large red">
                <i class="large material-icons">menu</i>
            </a>
            <ul>
                <li class="tooltipped" data-position="top" data-delay="50" data-tooltip="Duplicate Quote">
                    <form method="post" action="{{ route('quote.duplicate', [ 'quote' => $quote->id ] ) }}" class="null-form">
                        {{ csrf_field() }}
                        <button class="btn blue darken-3 waves-effect waves-dark null-btn" type="submit">
                            <i class="material-icons">control_point_duplicate</i>
                        </button>
                    </form>
                </li>
                <li class="tooltipped" data-position="top" data-delay="50" data-tooltip="Archive Quote">
                    <form method="post" action="{{ route('quote.archive', [ 'quote' => $quote->id ] ) }}" class="null-form">
                        {{ method_field('PATCH') }}
                        {{ csrf_field() }}
                        <button class="btn amber darken-2 waves-effect waves-dark null-btn" type="submit">
                            <i class="material-icons">archive</i>
                        </button>
                    </form>
                </li>
                <li class="tooltipped" data-position="top" data-delay="50" data-tooltip="Edit Quote">
                    <a href="{{ route('quote.edit', [ 'quote' => $quote->id ] ) }}" class="btn light-blue waves-effect waves-dark">
                        <i class="material-icons">edit</i>
                    </a>
                </li>
                <li class="tooltipped" data-position="top" data-delay="50" data-tooltip="Delete Quote">
                    <a href="#" data-id="{{ $quote->id }}" class="quote-delete-btn btn red waves-effect waves-dark">
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
                            @if ($quote->status == App\Models\Quote::STATUS_DRAFT)
                                <span class="alt-badge">{{ $quote->statustext() }}</span>
                            @elseif ($quote->status == App\Models\Quote::STATUS_OPEN)
                                <span class="alt-badge warning">{{ $quote->statustext() }}</span>
                            @elseif ($quote->status == App\Models\Quote::STATUS_EXPIRED)
                                <span class="alt-badge error">{{ $quote->statustext() }}</span>
                            @elseif ($quote->status == App\Models\Quote::STATUS_COMPLETED)
                                <span class="alt-badge success">{{ $quote->statustext() }}</span>
                            @endif
                        </dd>
                    </dl>
                </div>
            </div>
            @include('partials/quote')
        </div>
    </div>

    <div id="delete-confirmation" class="modal">
        <div class="modal-content">
            <p>Delete Quote?</p>
        </div>
        <div class="modal-footer">
            <form id="delete-quote-form" method="post" class="null-form">
                {{ method_field('DELETE') }}
                {{ csrf_field() }}
                <button class="modal-action waves-effect black-text waves-green btn-flat btn-deletemodal quote-confirm-delete-btn" type="submit">Delete</button>
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
                <a href="#" data-id="{{ $quote->id }}" class="quote-regenerate-btn btn btn-link waves-effect waves-dark">
                    Regenerate Link
                </a>
            </div>
            <input id="shared-link" type="text" value="{{ route('quote.token', [ 'token' => $quote->share_token] ) }}">
        </div>
    </div>
@stop

@section("scripts")
    <script type="text/javascript">
        "use strict";
        $(function() {

            $('#change-history-container').on('init', function(event, slick, direction){
                let height = $('#change-history-container').outerHeight() + 10;
                // left
            });

            $('#top-action-container').on('click', '.quote-share-btn', function (event) {
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

            $('#shared-details').on('click', '.quote-regenerate-btn', function (event) {
                event.preventDefault();
                getSharedLink();
            });


            $('#quote-action-container').on('click', '.quote-delete-btn', function (event) {
                event.preventDefault();
                let quoteid = $(this).attr('data-id');
                $('#delete-quote-form').attr('action', '/quote/' + quoteid + '/destroy');
                $('#delete-confirmation').modal('open');
            });

            $('.fixed-action-btn').on('click', '.quote-delete-btn', function (event) {
                event.preventDefault();
                let quoteid = $(this).attr('data-id');
                $('#delete-quote-form').attr('action', '/quote/' + quoteid + '/destroy');
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
                    url: "{{ route('quote.share', [ 'quote' => $quote->id]) }}",
                })
                .done(function(data) {
                    $('#shared-link').val("{{ route('quote.token') }}?token=" + data);
                    $('#shared-details').modal('open');
                });
            }
        });
    </script>
@stop