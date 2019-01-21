<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="UTF-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#00aba9">
    <meta name="theme-color" content="#ffffff">
    <link href="{{ asset(mix('/assets/css/materialize.css')) }}" rel="stylesheet" type="text/css">
    <link href="{{ asset(mix('/assets/css/core.css')) }}" rel="stylesheet" type="text/css">
    <link href="{{ asset(mix('/assets/css/style.css')) }}" rel="stylesheet" type="text/css">
    <link href="{{ asset(mix('/assets/css/trumbowyg.css')) }}" rel="stylesheet" type="text/css">
    <link href="{{ asset(mix('/assets/css/materialdesignicons.css')) }}" rel="stylesheet" type="text/css">
    <title>{{ config('app.name') }}@if(!isActiveRoute('main')){{ ' | ' }}@endif{{ $page_title ?? '' }} </title>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ config('app.googleua') }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', '{{ config('app.googleua') }}');
    </script>
    @yield("head")
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>
</head>
<body>
@include("partials/header")
    @if(session()->has('notice'))
        <div class="banner-anchor"></div>
        <div class="banner">
            <div class="banner-description">
                <span>{{ session()->get('notice')['message'] }}</span>
            </div>
            <div class="banner-action right">
                <a href="#!" class="banner-close waves-effect waves-red btn-flat">Dismiss</a>
                <a href="{{ session()->get('notice')['link'] }}" class="waves-effect waves-green btn-flat">{{ session()->get('notice')['link.text'] }}</a>
            </div>
        </div>
    @endif
@yield("content")

@include("partials/footer")

<script type="text/javascript" src="{{ asset(mix('/assets/js/manifest.js')) }}"></script>
<script type="text/javascript" src="{{ asset(mix('/assets/js/vendor.js')) }}"></script>
<script type="text/javascript" src="{{ asset(mix('/assets/js/app.js')) }}"></script>
<script type="text/javascript" src="{{ asset(mix('/assets/js/core.js')) }}"></script>

<script type="text/javascript">
    "use strict";
    $(function() {
        $('.collapsible').collapsible();
        $('.sidenav').sidenav();
        $('.tooltipped').tooltip({
            exitDelay: 0,
            enterDelay: 200
        });
        $('.tabs').tabs();
        $('.modal').modal();
        $('.fixed-action-btn').floatingActionButton({
            toolbarEnabled: true
        });

        $('.dropdown-trigger').dropdown({
            coverTrigger: false
        });

        $('#return-to-top').click(function() {      // When arrow is clicked
            $('body,html').animate({
                scrollTop : 0                       // Scroll to top of body
            }, 800);
        });

        $(document).on('click', '#toast-container .toast', function() {
            $(this).fadeOut(function(){
                $(this).remove();
            });
        });

        @if(session()->has('notice'))
            $('body').on('click', '.banner-close', function() {
                let $el = $(this).parent().parent('.banner').removeAttr('style').addClass('close');
                let $elNext = $el.next().css('margin-top', '0');
            });

            $(window).scroll(function(e){
                let $el = $('.banner');
                let $elNext = $el.next();

                if (!$el.hasClass('close') && $(this).scrollTop() > 80){
                    $el.css('position', 'fixed');
                    $el.css('transform', 'translateY(-80px)');
                    $el.css('z-index', '20');
                    $elNext.css('margin-top', $el.height() + 'px');
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
        @endif

        @if(session()->has('flash_notification'))
            @foreach (session('flash_notification', collect())->toArray() as $message)
                @if ($message['overlay'])
                    @include('flash::modal', [
                        'modalClass' => 'flash-modal',
                        'title'      => $message['title'],
                        'body'       => $message['message']
                    ])
                @else
                    {{-- 5000 is the duration of the toast, replace with text for unlimited duration --}}
                    M.toast({ html: "{!! $message['message'] !!} <i class='material-icons'>clear</i>", displayLength: "6000", classes: "{{ $message['level'] }}"});
                @endif
            @endforeach
            {{ session()->forget('flash_notification') }}
        @endif

        @if (count($errors) > 0)
            @foreach($errors->toArray() as $key => $error)
                $('#{{ $key }}').addClass('invalid');
                $('#{{ $key }}').siblings('span.helper-text').attr('data-error', "{!! $error[0] !!}");
                M.toast({ html: "{!! $error[0] !!}", displayLength: "5000", classes: "error"});
            @endforeach
        @endif
                    
        @if(app()->environment('production'))
            LogRocket.init('{{ config('app.logrocket_token') }}');
        @endif

        @if(auth()->check())
            @if($user = auth()->user())
                LogRocket.identify('{{ $user->id }}', {
                    name: '{{ $user->full_name }}',
                    email: '{{ $user->email }}',

                    // Add your own custom user variables here, ie:
                    subscriptionType: 'pro'
                });
            @endif
        @endif
    });
</script>

<!--Start of Tawk.to Script-->
<script type="text/javascript">
"use strict";
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
    var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
    s1.async=true;
    s1.src='https://embed.tawk.to/{{ config('app.tawkto_embed_key') }}/default';
    s1.charset='UTF-8';
    s1.setAttribute('crossorigin','*');
    s0.parentNode.insertBefore(s1,s0);
})();
</script>
<!--End of Tawk.to Script-->

@yield("scripts")

</body>
</html>