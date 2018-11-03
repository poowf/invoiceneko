@extends("layouts.default", ['page_title' => 'Company | Owner'])

@section("head")
    <link href="{{ mix('/assets/css/selectize.css') }}" rel="stylesheet" type="text/css">
    <style>
    </style>
@stop

@section("content")
    <div class="container">
        <div class="row">
            <div class="col s12">
                <h3>Owner</h3>
            </div>
        </div>
        <div class="row">
            <div class="col s12 m3 xl2">
                @include("partials/sidenav-company")
            </div>
            <div class="col s12 m9 xl10">
                @if($users->isNotEmpty())
                    <div class="card-panel">
                        <h6>Current Owner</h6>
                        <dl>
                            <dt>Username</dt>
                            <dd>{{ $owner->username }}</dd>
                            <dt>Email</dt>
                            <dd>{{ $owner->email ?? '-' }}</dd>
                            <dt>Full Name</dt>
                            <dd>{{ $owner->full_name ?? '-' }}</dd>
                            <dt>Phone</dt>
                            <dd>{{ $owner->phone ?? '-' }}</dd>
                        </dl>
                    </div>
                    @can('update', $owner->company)
                        <form id="edit-profile" method="post" enctype="multipart/form-data">
                            <div class="card-panel flex">
                                <div class="input-field col s12">
                                    <select id="user_id" name="user_id" class="user-list-selector selectize-custom" data-parsley-required="true" data-parsley-trigger="change">
                                        <option disabled="" selected="selected" value="">Pick a new Owner</option>
                                    </select>
                                    <label for="user_id" class="label-validation">Owner</label>
                                    <span class="helper-text"></span>
                                </div>
                            </div>
                            <div id="owner-modification-panel" class="card-panel flex hide">
                                <div class="col s12">
                                    <h3 class="no-margin">New Company Owner</h3>
                                    <dl>
                                        <dt>Username</dt>
                                        <dd><span class="red-text">{{ $owner->username }}</span> to <span id="new-username" class="green-text text-lighten-1 text-bold"></span></dd>
                                        <dt>Email</dt>
                                        <dd><span class="red-text">{{ $owner->email ?? '-' }}</span> to <span id="new-email" class="green-text text-lighten-1 text-bold"></span></dd>
                                        <dt>Full Name</dt>
                                        <dd><span class="red-text">{{ $owner->full_name ?? '-' }}</span> to <span id="new-fullname" class="green-text text-lighten-1 text-bold"></span></dd>
                                        <dt>Phone</dt>
                                        <dd><span class="red-text">{{ $owner->phone ?? '-' }}</span> to <span id="new-phone" class="green-text text-lighten-1 text-bold"></span></dd>
                                    </dl>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-field col s12">
                                    {{ method_field('PATCH') }}
                                    {{ csrf_field() }}
                                    <button class="btn waves-effect waves-light col s12 m3 offset-m9" type="submit" name="action">Update</button>
                                </div>
                            </div>
                        </form>
                    @endcan
                @else
                    <div class="card-panel center">
                        <i class="material-icons" style="font-size: 3em; color: grey;">sentiment_dissatisfied</i>
                        <p style="font-size: 15px; font-weight: 400; color: grey;">There's nothing here</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@stop

@section("scripts")
    @if($users->isNotEmpty())
        @can('update', $owner->company)
            <script type="text/javascript">
            "use strict";
            $(function() {
                //Explicit selection otherwise will change the select into a multiple select if only selecting by css class
                $('select#user_id').selectize({
                    create: false,
                    dataAttr: 'data-id',
                    valueField: 'id',
                    labelField: 'name',
                    searchField: ['name'],
                    options: [
                            @foreach($users as $user) @if(!$user->owns($company)){ id:'{{ $user->id }}', name:'{{ $user->full_name }}' },@endif @endforeach
                    ]
                });

                function retrieveUser(user_id, callback) {
                    if (typeof user_id !== typeof undefined && user_id !== false) {
                        $.ajax({
                            type: "GET",
                            url: "/user/" + user_id +"/retrieve",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        })
                            .done(function(data) {
                                callback(data);
                            })
                            .fail(function(jqXHR, textStatus) {
                                console.log(jqXHR);
                                console.log(textStatus);
                            })
                            .always(function() {
                                console.log("finish");
                            });
                    }
                }

                function displayOwnerModificationPanel(user) {
                    $('#new-username').html(user.username);
                    $('#new-email').html(user.email);
                    $('#new-fullname').html(user.full_name);
                    $('#new-phone').html(user.phone);
                    $('#owner-modification-panel').removeClass('hide');
                }

                $('#edit-profile').on('change', '#user_id', function (event) {
                    retrieveUser($(this).val(), displayOwnerModificationPanel);
                });

                $('#edit-profile').parsley({
                    successClass: 'valid',
                    errorClass: 'invalid',
                    errorsContainer: function (velem) {
                        let $errelem = velem.$element.siblings('span.helper-text');
                        $errelem.attr('data-error', window.Parsley.getErrorMessage(velem.validationResult[0].assert));
                        return true;
                    },
                    errorsWrapper: '',
                    errorTemplate: ''
                })
                    .on('field:validated', function(velem) {

                    })
                    .on('field:success', function(velem) {
                        if (velem.$element.is(':radio'))
                        {
                            velem.$element.parentsUntil('.row').find('span.helper-text').removeClass('invalid').addClass('valid');
                        }
                        else if (velem.$element.is('#phone'))
                        {
                            velem.$element.parent('').siblings('label').removeClass('invalid').addClass('valid');
                        }
                    })
                    .on('field:error', function(velem) {
                        if (velem.$element.is(':radio'))
                        {
                            velem.$element.parentsUntil('.row').find('span.helper-text').removeClass('valid').addClass('invalid');
                            velem.$element.parentsUntil('.row').find('span.helper-text').attr('data-error', window.Parsley.getErrorMessage(velem.validationResult[0].assert));
                        }
                        else if (velem.$element.is('#phone'))
                        {
                            velem.$element.parent('').siblings('span.helper-text').removeClass('valid').addClass('invalid');
                            velem.$element.parent('').siblings('span.helper-text').attr('data-error', window.Parsley.getErrorMessage(velem.validationResult[0].assert));
                        }
                    })
                    .on('form:submit', function(velem) {
                        $("#phone").val($("#phone").intlTelInput("getNumber"));
                    });
            });
            </script>
        @endcan
    @endif
@stop