@php
use Illuminate\Support\Facades\Session;
$settingInfo = App\Http\Controllers\webController::settings();
use Illuminate\Support\Facades\Cookie;
if (app()->getLocale() == 'en') {
    $strLang = 'en';
} else {
    $strLang = 'ar';
}
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>@if (app()->getLocale() == 'en') {{ $settingInfo->name_en }} @else {{ $settingInfo->name_ar }} @endif | {{ __('webMessage.brands') }} | {{ $brandInfo['title_' . $strLang] }}</title>
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
    @if (!empty($brandInfo->image))
        <script type="application/ld+json">
            {
                "@context": "https://schema.org",
                "@type": "Organization",
                "url": "{{ url(app()->getLocale() . '/') }}",
                "logo": "{{ url('uploads/brand/' . $brandInfo->image) }}"
            }
        </script>
    @endif

</head>

<body>
    <!--preloader -->
    @include("website.includes.preloader")
    <!--end preloader -->
    <!--header -->
    @include("website.includes.header")
    <!--end header -->
    <div class="tt-breadcrumb">
        <div class="container">
            <ul>
                <li><a href="{{ url(app()->getLocale() . '/') }}">{{ __('webMessage.home') }}</a></li>
                <li><a href="javascript:;">{{ __('webMessage.brands') }}</a></li>
                <li><a href="javascript:;">{{ $brandInfo['title_' . $strLang] }}</a></li>
            </ul>
        </div>
    </div>
    <div id="tt-pageContent">
        <div class="container-indent">
            <div class="container container-fluid-custom-mobile-padding">
                <div class="row">

                    <div class="col-md-12">
                        <div class="content-indent">
                            <div class="tt-filters-options desctop-no-sidebar">
                                <h1 class="tt-title">
                                    {{ $brandInfo['title_' . $strLang] }} <span
                                        class="tt-title-total">({{ count($brandProductLists) }})</span>
                                </h1>
                                <!--<div class="tt-btn-toggle">
        <a href="javascript:;">{{ strtoupper(__('webMessage.filter')) }}</a>
       </div>-->

                                <div class="tt-sort">
                                    <select name="brand_sort_by" id="brand_sort_by" mykey="brand">
                                        <option value="">{{ __('webMessage.latestitems') }}</option>
                                        <option value="popular" @if (session('brandbrand_sort_by') == 'popular') selected @endif>
                                            {{ __('webMessage.mostpopular') }}</option>
                                        <option value="max-price" @if (session('brandbrand_sort_by') == 'max-price') selected @endif>
                                            {{ __('webMessage.max_price') }}</option>
                                        <option value="min-price" @if (session('brandbrand_sort_by') == 'min-price') selected @endif>
                                            {{ __('webMessage.min_price') }}</option>
                                        <option value="a-z" @if (session('brandbrand_sort_by') == 'a-z') selected @endif>{{ __('webMessage.atoz') }}
                                        </option>
                                        <option value="z-a" @if (session('brandbrand_sort_by') == 'z-a') selected @endif>{{ __('webMessage.ztoa') }}
                                        </option>
                                    </select>
                                    <select name="brand_per_page" id="brand_per_page" mykey="brand">
                                        <option value="12" @if (session('brandbrand_per_page') == '12') selected @endif>{{ __('webMessage.show') }}
                                        </option>
                                        <option value="24" @if (session('brandbrand_per_page') == '24') selected @endif>24</option>
                                        <option value="48" @if (session('brandbrand_per_page') == '48') selected @endif>48</option>
                                        <option value="96" @if (session('brandbrand_per_page') == '96') selected @endif>96</option>
                                    </select>
                                </div>
                                <div class="tt-quantity">
                                    <a href="#" class="tt-col-one" data-value="tt-col-one"></a>
                                    <a href="#" class="tt-col-two" data-value="tt-col-two"></a>
                                    <a href="#" class="tt-col-three" data-value="tt-col-three"></a>
                                    <a href="#" class="tt-col-four" data-value="tt-col-four"></a>
                                    <a href="#" class="tt-col-six" data-value="tt-col-six"></a>
                                </div>
                            </div>
                            @if (!empty($brandProductLists) && count($brandProductLists) > 0)
                                <div class="tt-product-listing row">
                                    @php $tagsDetails=''; @endphp
                                    @foreach ($brandProductLists as $brandProductList)
                                        @php
                                            $isStock = App\Http\Controllers\webCartController::IsAvailableQuantity($brandProductList->id);
                                            $tagsDetails = App\Http\Controllers\webCartController::getTagsName($brandProductList->tags_en, $brandProductList->tags_ar);
                                            $checkBrandDiscount = App\Http\Controllers\webController::checkBrandDiscount($brandProductList->brand_id);
                                            $brandDiscountedPrice = App\Http\Controllers\webController::calByBrandDiscount($brandProductList->brand_id, $brandProductList->retail_price);
                                            
                                        @endphp
                                        <div class="col-6 col-md-4 col-lg-3 tt-col-item">
                                            <div class="tt-product thumbprod-center">
                                                <div class="tt-image-box">
                                                    <a href="javascript:;" class="tt-btn-quickview loadquickviewmodal"
                                                        data-tooltip="{{ __('webMessage.quickview') }}"
                                                        {{ __('webMessage.align') }}
                                                        id="{{ $brandProductList->id }}"></a>
                                                    <a href="javascript:;" class="tt-btn-wishlist addtowishlistquick"
                                                        data-tooltip="{{ __('webMessage.addtowishlist') }}"
                                                        {{ __('webMessage.align') }}
                                                        id="{{ $brandProductList->id }}"></a>
                                                    <a
                                                        href="{{ url(app()->getLocale() . '/' . 'directdetails/' . $brandProductList->id . '/' . $brandProductList->slug) }}">
                                                        <span class="tt-img"><img
                                                                src="{{ url('assets/images/loader.svg') }}"
                                                                data-src="@if ($brandProductList->image) {{ url('uploads/product/thumb/' . $brandProductList->image) }} @else {{ url('uploads/no-image.png') }} @endif" alt="@if (app()->getLocale() == 'en') {{ $brandProductList->title_en }} @else {{ $brandProductList->title_ar }} @endif"></span>
                                                        @if ($brandProductList->rollover_image)
                                                            <span class="tt-img-roll-over"><img
                                                                    src="{{ url('assets/images/loader.svg') }}"
                                                                    data-src="@if ($brandProductList->rollover_image) {{ url('uploads/product/thumb/' . $brandProductList->rollover_image) }} @else {{ url('uploads/no-image.png') }} @endif" alt="@if (app()->getLocale() == 'en') {{ $brandProductList->title_en }} @else {{ $brandProductList->title_ar }} @endif"></span>
                                                        @endif
                                                        <span class="tt-label-location">@if (empty($isStock))<span class="tt-label-sale">{{ __('webMessage.outofstock') }}</span>@endif
                                                            @if (!empty($brandProductList->caption_en) && $strLang == 'en')
                                                                <span class="tt-label"
                                                                    style="background-color:{{ $brandProductList->caption_color }};color:#fff;border-radius:5px;font-size:12px;padding:3px;">{{ $brandProductList->caption_en }}</span>
                                                            @else
                                                                <span class="tt-label"
                                                                    style="background-color:{{ $brandProductList->caption_color }};color:#fff;border-radius:5px;font-size:12px;padding:3px;">{{ $brandProductList->caption_ar }}
                                                                </span>
                                                            @endif
                                                        </span>
                                                    </a>
                                                    <!--countdown-->
                                                    @if (!empty($brandProductList->countdown_datetime) && strtotime($brandProductList->countdown_datetime) > strtotime(date('Y-m-d')))
                                                        <div class="tt-countdown_box">
                                                            <div class="tt-countdown_inner">
                                                                <div class="tt-countdown"
                                                                    data-date="{{ $brandProductList->countdown_datetime }}"
                                                                    data-year="{{ __('webMessage.Yrs') }}"
                                                                    data-month="{{ __('webMessage.Mths') }}"
                                                                    data-week="{{ __('webMessage.Wk') }}"
                                                                    data-day="{{ __('webMessage.Day') }}"
                                                                    data-hour="{{ __('webMessage.Hrs') }}"
                                                                    data-minute="{{ __('webMessage.Min') }}"
                                                                    data-second="{{ __('webMessage.Sec') }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    <!--end countdown-->
                                                </div>
                                                <div class="tt-description">
                                                    <span id="responseMsg-{{ $brandProductList->id }}"></span>

                                                    <h2 class="tt-title"><a
                                                            href="{{ url(app()->getLocale() . '/' . 'directdetails/' . $brandProductList->id . '/' . $brandProductList->slug) }}">{!! Common::getLangString($brandProductList->title_en, $brandProductList->title_ar) !!}{!! Common::getLangStringExtra($brandProductList->extra_title_en, $brandProductList->extra_title_ar) !!}</a>
                                                    </h2>
                                                    @if (!empty($tagsDetails)){!! $tagsDetails !!}@endif
                                                    <div class="tt-price">
                                                        @if (!empty($brandProductList->countdown_datetime) && strtotime($brandProductList->countdown_datetime) > strtotime(date('Y-m-d')))
                                                            <span
                                                                class="new-price price_red">{{ $brandProductList->countdown_price }}
                                                                {{ __('webMessage.kd') }}</span>
                                                            <input type="hidden"
                                                                id="pixel_price_{{ $brandProductList->id }}"
                                                                value="{{ $brandProductList->countdown_price }}">
                                                            <span
                                                                class="old-price price_black">{{ $brandProductList->retail_price }}
                                                                {{ __('webMessage.kd') }}</span>

                                                        @elseif ($checkBrandDiscount)
                                                            <span
                                                                class="new-price price_red">{{ $brandDiscountedPrice->price }}
                                                                {{ __('webMessage.kd') }}</span>
                                                            <input type="hidden"
                                                                id="pixel_price_{{ $brandProductList->id }}"
                                                                value="{{ $brandDiscountedPrice->price }}">
                                                            <span
                                                                class="old-price price_black">{{ $brandDiscountedPrice->oldPrice }}
                                                                {{ __('webMessage.kd') }}</span>
                                                        @else
                                                            <span
                                                                class="new-price @if ($brandProductList->old_price) price_red @endif">{{ $brandProductList->retail_price }}
                                                                {{ __('webMessage.kd') }}</span>
                                                            <input type="hidden"
                                                                id="pixel_price_{{ $brandProductList->id }}"
                                                                value="{{ $brandProductList->retail_price }}">
                                                            @if (!empty($brandProductList->old_price))
                                                                <span
                                                                    class="old-price price_black">{{ $brandProductList->old_price }}
                                                                    {{ __('webMessage.kd') }}</span>
                                                            @endif
                                                        @endif
                                                    </div>
                                                    <div class="tt-product-inside-hover">
                                                        <div class="tt-row-btn">
                                                            @if ($brandProductList->is_attribute)
                                                                <a href="{{ url(app()->getLocale() . '/' . 'directdetails/' . $brandProductList->id . '/' . $brandProductList->slug) }}"
                                                                    class="tt-btn-addtocart thumbprod-button-bg"
                                                                    id="{{ $brandProductList->id }}">{{ __('webMessage.details') }}</a>
                                                            @else
                                                                @if (!empty($isStock))
                                                                    @if ($brandProductList->is_active == '2')
                                                                        <a href="javascript:;"
                                                                            class="tt-btn-addtocart thumbprod-button-bg addtocartsingle addToCartPixelButton"
                                                                            id="{{ $brandProductList->id }}">{{ __('webMessage.preorder') }}</a>
                                                                    @else
                                                                        <a href="javascript:;"
                                                                            class="tt-btn-addtocart thumbprod-button-bg addtocartsingle addToCartPixelButton"
                                                                            id="{{ $brandProductList->id }}">{{ __('webMessage.addtocart_btn') }}</a>
                                                                    @endif
                                                                @endif
                                                            @endif
                                                        </div>
                                                        <div class="tt-row-btn">
                                                            <a href="javascript:;"
                                                                class="tt-btn-quickview loadquickviewmodal"
                                                                id="{{ $brandProductList->id }}"></a>

                                                            <a href="javascript:;"
                                                                class="tt-btn-wishlist addtowishlistquick"
                                                                id="{{ $brandProductList->id }}"></a>

                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="text-center tt_product_showmore">
                                    {!! $brandProductLists->links() !!}
                                </div>
                            @else
                                <div class="text-center tt_product_showmore">
                                    {{ __('webMessage.norecordfound') }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--footer-->
    @include("website.includes.footer")


    <!-- modal (quickViewModal) -->
    @include("website.includes.quickview_modal")
    <!--default modal -->
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

    @if ($settingInfo->theme != 17)
        <script defer src="{{ url('hakum_assets/js/bundle.js') }}"></script>
    @endif
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
            'screen_name': 'Brands'
        });
    </script>
</body>

</html>
