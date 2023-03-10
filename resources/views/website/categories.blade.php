@php
$settingInfo = App\Http\Controllers\webController::settings();
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>@if (app()->getLocale() == 'en') {{ $settingInfo->name_en }} @else {{ $settingInfo->name_ar }} @endif | {{ __('webMessage.categories') }}</title>
    <meta name="description" content="@if (app()->getLocale() == 'en') {{ $settingInfo->seo_description_en }} @else {{ $settingInfo->seo_description_ar }} @endif" />
    <meta name="abstract" content="@if (app()->getLocale() == 'en') {{ $settingInfo->seo_description_en }} @else {{ $settingInfo->seo_description_ar }} @endif">
    <meta name="keywords" content="@if (app()->getLocale() == 'en') {{ $settingInfo->seo_keywords_en }} @else {{ $settingInfo->seo_keywords_ar }} @endif" />
    <meta name="Copyright" content="{{ $settingInfo->name_en }}, Kuwait Copyright 2020 - {{ date('Y') }}" />
    <META NAME="Geography" CONTENT="@if (app()->getLocale() == 'en') {{ $settingInfo->address_en }} @else {{ $settingInfo->address_ar }} @endif">
    @if ($settingInfo->extra_meta_tags) {!! $settingInfo->extra_meta_tags !!} @endif
    @if ($settingInfo->favicon)
        <link rel="icon" href="{{ url('uploads/logo/' . $settingInfo->favicon) }}">
    @endif
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    @include("website.includes.css")
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- <link href="{{ asset('css/app.css') }}" rel="stylesheet" type="text/css"> --}}
</head>

<body style="background: white;">
    <!--preloader -->
    @include("website.includes.preloader")
    <!--end preloader -->
    <!--header -->
    @include("website.includes.header")
    <!--end header -->
    @if ($settingInfo->theme !== 17)
        <div class="tt-breadcrumb">
            <div class="container">
                <ul>
                    <li><a href="{{ url(app()->getLocale() . '/') }}">{{ __('webMessage.home') }}</a></li>
                    <li>{{ __('webMessage.categories') }}</li>
                </ul>
            </div>
        </div>
    @endif

    @if ($settingInfo->theme !== 17)
        <div id="tt-pageContent">
            <div class="container-indent">
                <div id="app">
                    <categories-component :locale="{{ json_encode(app()->getLocale()) }}"></categories-component>
                </div>
            </div>
        </div>
    @endif

    <!--footer-->
    @include("website.includes.footer")

    <!-- modal (AddToCartProduct) -->
    @include("website.includes.addtocart_modal")

    <script src="{{ url('assets/external/jquery/jquery.min.js') }}"></script>
    <script src="{{ url('assets/external/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ url('assets/external/slick/slick.min.js') }}"></script>
    <script src="{{ url('assets/external/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ url('assets/external/panelmenu/panelmenu.js') }}"></script>
    <script src="{{ url('assets/external/instafeed/instafeed.min.js') }}"></script>
    <script src="{{ url('assets/external/countdown/jquery.plugin.min.js') }}"></script>
    <script src="{{ url('assets/external/countdown/jquery.countdown.min.js') }}"></script>
    <script src="{{ url('assets/external/rs-plugin/js/jquery.themepunch.tools.min.js') }}"></script>
    <script src="{{ url('assets/external/rs-plugin/js/jquery.themepunch.revolution.min.js') }}"></script>
    <script src="{{ url('assets/external/lazyLoad/lazyload.min.js') }}"></script>
    <script src="{{ url('assets/js/main.js') }}"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    {{-- <script defer src="{{ url('hakum_assets/js/bundle.js') }}"></script> --}}
    <script src="{{ url('assets/js/gulfweb.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        gtag('event', 'screen_view', {
            'screen_name': 'categories'
        });
    </script>
</body>

</html>
