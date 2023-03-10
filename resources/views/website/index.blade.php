@php
$settingInfo = App\Http\Controllers\webController::settings();
@endphp
<!DOCTYPE html>
<html lang={{app()->getLocale()}}>

<head>
    <meta charset="utf-8">
    <title>
    @if (app()->getLocale() == 'en') {{ $settingInfo->name_en }} @else
            {{ $settingInfo->name_ar }} @endif
    </title>
    <meta name="description" content="@if (app()->getLocale() == 'en') {{ $settingInfo->seo_description_en }} @else {{ $settingInfo->seo_description_ar }} @endif" />
    <meta name="abstract" content="@if (app()->getLocale() == 'en') {{ $settingInfo->seo_description_en }} @else {{ $settingInfo->seo_description_ar }} @endif">
    <meta name="keywords" content="@if (app()->getLocale() == 'en') {{ $settingInfo->seo_keywords_en }} @else {{ $settingInfo->seo_keywords_ar }} @endif" />
    <meta name="Copyright" content="{{ $settingInfo->name_en }}, Kuwait Copyright 2020 - {{ date('Y') }}" />
    <META NAME="Geography" CONTENT="@if (app()->getLocale() == 'en') {{ $settingInfo->address_en }} @else {{ $settingInfo->address_ar }} @endif">


    <meta property="og:title" content="    @if (app()->getLocale()=='en')
{{ $settingInfo->name_en }} @else
    {{ $settingInfo->name_ar }} @endif" />
    <meta property="og:description" content="@if (app()->getLocale() == 'en') {{ $settingInfo->seo_description_en }} @else {{ $settingInfo->seo_description_ar }} @endif" />

    <meta property="og:image" content="{{ url('uploads/logo/' . $settingInfo->logo) }}" />


    @if ($settingInfo->extra_meta_tags) {!! $settingInfo->extra_meta_tags !!} @endif
    @if ($settingInfo->favicon)
        <link rel="icon" href="{{ url('uploads/logo/' . $settingInfo->favicon) }}">
    @endif
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    @include("website.includes.css")
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!--web push notification -->
    <script type='text/javascript' src='https://www.gstatic.com/firebasejs/7.4.0/firebase-app.js'></script>
    <script type='text/javascript' src='https://www.gstatic.com/firebasejs/7.4.0/firebase-messaging.js'></script>
    <script type='text/javascript' src='https://www.gstatic.com/firebasejs/7.4.0/firebase-analytics.js'></script>
    <script type='text/javascript' src='https://code.jquery.com/jquery-3.3.1.js'></script>
    <script src="{{ url('assets/webpush/webpush.js') }}"></script>
    @if ($settingInfo->logo)
        <script type="application/ld+json">
            {
                "@context": "https://schema.org",
                "@type": "Organization",
                "url": "{{ url('/'.app()->getLocale()) }}",
                "logo": "{{ url('uploads/logo/' . $settingInfo->logo) }}"
            }
        </script>
    @endif
</head>

<body>

    <!--preloader -->
    @include("website.includes.preloader")
    <!-- top header -->
    @include("website.includes.header")
    <div id="tt-pageContent">
  
        <!--home slideshow -->
        @include("website.includes.slideshow")
        @if ($settingInfo->theme == 1 || $settingInfo->theme == 4 || $settingInfo->theme == 6)
            <!--home banner -->
            @include("website.includes.banner")
            <!--shop by category -->
            @if ($settingInfo->theme == 6))
                @include("website.includes.shop_by_categories_scrolling")
                @include("website.includes.best_seller_by_brands")
            @endif
            <!--home section & items -->
            @include("website.includes.homesection")
            <!--home short note -->
            @include("website.includes.homeshorttext")
        @endif
        @if ($settingInfo->theme == 16)
            @include("website.includes.banner")

            @include("website.includes.homesection")
            @include("website.includes.homeshorttext")
        @endif
        @if ($settingInfo->theme == 2)
            <!--shop by category -->
            @include("website.includes.shop_by_categories")
            <!--home sections -->
            @include("website.includes.homesection")
            <!--fav brands -->
            @include("website.includes.shop_by_brands")
            <!--home short note -->
            @include("website.includes.homeshorttext")
            <!--end short note -->
            <!--banner -->
            @include("website.includes.banner")
        @endif

        @if ($settingInfo->theme == 3)
            <!--home banner -->
            @include("website.includes.banner")
        @endif

    </div>
    <!--footer -->
    @include("website.includes.footer")
    <!-- modal (AddToCartProduct) -->
    @include("website.includes.addtocart_modal")
    <!-- modal (quickViewModal) -->
    @include("website.includes.quickview_modal")


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
    <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>-->

    <script defer src="{{ url('hakum_assets/js/bundle.js') }}"></script>
    <script src="{{ url('assets/js/gulfweb.js') }}"></script>
    @if (!empty($settingInfo->facebook_pixel))
        <script type="text/javascript">
            $('.addToCartPixelButton').click(function() {
                var id = $(this).attr("id");
                var price = $("#pixel_price_" + id).val();
                fbq('track', 'AddToCart', {
                    content_ids: [id],
                    content_type: 'product',
                    value: price,
                    currency: 'USD'
                });
            });
        </script>
    @endif
    <script>
        gtag('event', 'screen_view', {
            'screen_name': 'Home'
        });
    </script>
</body>

</html>
