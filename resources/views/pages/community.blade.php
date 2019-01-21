@extends("layouts.default", ['page_title' => 'Invoice Neko'])

@section("head")
    <style>
    </style>
@stop

@section("content")
    <div class="container">
        <div class="row mtop30">
            <div class="col s12 m6">
                <div class="card center pbtm20">
                    <div class="card-header theme-color-secondary">
                        <p>Latest Release</p>
                        <p class="version">{{ $releases->stable->tag_name ?? '-' }}</p>
                    </div>
                    <div class="card-content">
                        @if($releases->stable)
                            <dl class="releases-description-list">
                                <dt>Date:</dt>
                                <dd>{{ ($releases->stable->created_at) ? \Carbon\Carbon::parse($releases->stable->created_at)->format('d M Y') : '-' }}</dd>
                                <dt>Hash:</dt>
                                <dd>{{ str_limit($releases->stable->commit_data->object->sha, 7, '') ?? '-' }}</dd>
                            </dl>
                            <p><a class="alt-badge info tooltipped changelog-badge" data-position="bottom" data-tooltip="{{ $releases->stable->body_html ?? '-' }}">Changelog</a></p>
                            <a href="{{ $releases->stable->html_url ?? 'javascript:;' }}" class="btn btn-large btn-link mtop10">Download</a>
                        @else
                            <i class="material-icons" style="font-size: 3em; color: grey;">sentiment_dissatisfied</i>
                            <p style="font-size: 15px; font-weight: 400; color: grey;">There's no releases</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col s12 m6">
                <div class="card center">
                    <div class="card-header theme-color-secondary">
                        <p>Latest Pre-release</p>
                        <p class="version">{{ $releases->unstable->tag_name ?? '-' }}</p>
                    </div>
                    <div class="card-content">
                        @if($releases->unstable)
                            <dl class="releases-description-list">
                                <dt>Date:</dt>
                                <dd>{{ ($releases->unstable->created_at) ? \Carbon\Carbon::parse($releases->unstable->created_at)->format('d M Y') : '-' }}</dd>
                                <dt>Hash:</dt>
                                <dd>{{ str_limit($releases->unstable->commit_data->object->sha, 7, '') ?? '-' }}</dd>
                            </dl>
                            <p><a class="alt-badge info tooltipped changelog-badge" data-position="bottom" data-tooltip="{{ $releases->unstable->body_html ?? '-' }}">Changelog</a></p>
                            <a href="{{ $releases->unstable->html_url ?? 'javascript:;' }}" class="btn btn-large btn-link mtop10">Download</a>
                        @else
                            <i class="material-icons" style="font-size: 3em; color: grey;">sentiment_dissatisfied</i>
                            <p style="font-size: 15px; font-weight: 400; color: grey;">There's no releases</p>
                        @endif
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