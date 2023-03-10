<!--start new items display -->
@if ($settingInfo->is_new_item_active == 1)
    @php
        $newitems = App\Http\Controllers\webController::getNewProducts();
        if (!empty(app()->getLocale())) {
            $strLang = app()->getLocale();
        } else {
            $strLang = 'en';
        }
    @endphp
    <div class="container-indent">
        <div class="container container-fluid-custom-mobile-padding">
            <div class="tt-block-title">

                <h1 class="tt-title noborder"><a
                        href="{{ !empty($link) ? $link : '#' }}">{{ strtoupper(trans('webMessage.latestproducts')) }}</a>
                </h1>
                <div class="tt-description">&nbsp;</div>
            </div>
            <div
                class="tt-carousel-products row arrow-location-tab arrow-location-tab01 tt-alignment-img tt-collection-listing slick-animated-show-js">
                @php $tagsDetails=''; @endphp
                @foreach ($newitems as $newitem)
                    @php
                        $isStock = App\Http\Controllers\webCartController::IsAvailableQuantity($newitem->id);
                        $tagsDetails = App\Http\Controllers\webCartController::getTagsName($newitem->tags_en, $newitem->tags_ar);
                        $checkBrandDiscount = App\Http\Controllers\webController::checkBrandDiscount($newitem->brand_id);
                        $brandDiscountedPrice = App\Http\Controllers\webController::calByBrandDiscount($newitem->brand_id, $newitem->retail_price);
                    @endphp
                    <div class="col-2 col-md-4 col-lg-3">
                        <div class="tt-product thumbprod-center">
                            <div class="tt-image-box">
                                <a href="javascript:;" class="tt-btn-quickview loadquickviewmodal"
                                    data-tooltip="{{ __('webMessage.quickview') }}"
                                    data-tposition="{{ __('webMessage.align') }}" id="{{ $newitem->id }}"></a>
                                <a href="javascript:;" class="tt-btn-wishlist addtowishlistquick"
                                    data-tooltip="{{ __('webMessage.addtowishlist') }}" {{ __('webMessage.align') }}
                                    id="{{ $newitem->id }}"></a>
                                <a
                                    href="{{ url(app()->getLocale().'/'.'directdetails/' . $newitem->id . '/' . $newitem->slug ) }}">
                                    <span class="tt-img"><img src="{{ url('assets/images/loader.svg') }}"
                                            data-src="@if ($newitem->image) {{ url('uploads/product/thumb/' . $newitem->image) }} @else {{ url('uploads/no-image.png') }} @endif" alt="@if (app()->getLocale() == 'en') {{ $newitem->title_en }} @else {{ $newitem->title_ar }} @endif"></span>
                                    @if ($newitem->rollover_image)
                                        <span class="tt-img-roll-over"><img
                                                src="{{ url('assets/images/loader.svg') }}"
                                                data-src="@if ($newitem->rollover_image) {{ url('uploads/product/thumb/' . $newitem->rollover_image) }} @else {{ url('uploads/no-image.png') }} @endif" alt="@if (app()->getLocale() == 'en') {{ $newitem->title_en }} @else {{ $newitem->title_ar }} @endif"></span>
                                    @endif
                                    <span class="tt-label-location">
                                        @if (empty($isStock))<span class="tt-label-sale">{{ __('webMessage.outofstock') }}</span>@endif
                                        @if (!empty($newitem->caption_en))<span class="tt-label-sale" style="background-color:{{ $newitem['caption_color'] }};">{{ $newitem['caption_' . $strLang] }}</span>@endif
                                    </span>
                                </a>
                                <!--countdown-->
                                @if (!empty($newitem->countdown_datetime) && strtotime($newitem->countdown_datetime) > strtotime(date('Y-m-d')))
                                    <div class="tt-countdown_box">
                                        <div class="tt-countdown_inner">
                                            <div class="tt-countdown"
                                                data-date="{{ $newitem->countdown_datetime }}"
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
                                <span id="responseMsg-{{ $newitem->id }}"></span>

                                <h2 class="tt-title"><a
                                        href="{{ url(app()->getLocale().'/'.'directdetails/' . $newitem->id . '/' . $newitem->slug) }}">{!! Common::getLangString($newitem->title_en, $newitem->title_ar) !!}{!! Common::getLangStringExtra($newitem->extra_title_en, $newitem->extra_title_ar) !!}</a>
                                </h2>
                                @if (!empty($tagsDetails)){!! $tagsDetails !!}@endif
                                <div class="tt-price">
                                    @if (!empty($newitem->countdown_datetime) && strtotime($newitem->countdown_datetime) > strtotime(date('Y-m-d')))
                                        <span class="new-price price_red">{{ $newitem->countdown_price }}
                                            {{ __('webMessage.kd') }}</span>
                                        <input type="hidden" id="pixel_price_{{ $newitem->id }}"
                                            value="{{ $newitem->countdown_price }}">
                                        <span class="old-price price_black">{{ $newitem->retail_price }}
                                            {{ __('webMessage.kd') }}</span>

                                    @elseif ($checkBrandDiscount)
                                        <span class="new-price price_red">{{ $brandDiscountedPrice->price }}
                                            {{ __('webMessage.kd') }}</span>
                                        <input type="hidden" id="pixel_price_{{ $newitem->id }}"
                                            value="{{ $brandDiscountedPrice->price }}">
                                        <span class="old-price price_black">{{ $brandDiscountedPrice->oldPrice }}
                                            {{ __('webMessage.kd') }}</span>
                                    @else
                                        <span class="new-price @if ($newitem->old_price) price_red @endif">{{ $newitem->retail_price }}
                                            {{ __('webMessage.kd') }}</span>
                                        <input type="hidden" id="pixel_price_{{ $newitem->id }}"
                                            value="{{ $newitem->retail_price }}">
                                        @if (!empty($newitem->old_price))
                                            <span class="old-price price_black">{{ $newitem->old_price }}
                                                {{ __('webMessage.kd') }}</span>
                                        @endif
                                    @endif
                                </div>
                                <div class="tt-product-inside-hover">
                                    <div class="tt-row-btn">
                                        @if ($newitem->is_attribute)
                                            <a href="{{ url(app()->getLocale().'/'.'directdetails/' . $newitem->id . '/' . $newitem->slug) }}"
                                                class="tt-btn-addtocart thumbprod-button-bg"
                                                id="{{ $newitem->id }}">{{ __('webMessage.details') }}</a>
                                        @else
                                            @if (!empty($isStock))
                                                @if ($newitem->is_active == '2')
                                                    <a href="javascript:;"
                                                        class="tt-btn-addtocart thumbprod-button-bg addtocartsingle addToCartPixelButton"
                                                        id="{{ $newitem->id }}">{{ __('webMessage.preorder') }}</a>
                                                @else
                                                    <a href="javascript:;"
                                                        class="tt-btn-addtocart thumbprod-button-bg addtocartsingle addToCartPixelButton"
                                                        id="{{ $newitem->id }}">{{ __('webMessage.addtocart_btn') }}</a>
                                                @endif
                                            @endif
                                        @endif
                                    </div>
                                    <div class="tt-row-btn">
                                        <a href="javascript:;" class="tt-btn-quickview loadquickviewmodal"
                                            id="{{ $newitem->id }}"></a>
                                        <a href="javascript:;" class="tt-btn-wishlist addtowishlistquick"
                                            id="{{ $newitem->id }}"></a>
                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif
<!--end new items display -->
@php
$homesetions = App\Http\Controllers\webController::getSections();
if (!empty(app()->getLocale())) {
    $strLang = app()->getLocale();
} else {
    $strLang = 'en';
}
@endphp

@if ($settingInfo->theme == 1 || $settingInfo->theme == 4 || $settingInfo->theme == 5 || $settingInfo->theme == 6)

    @if (!empty($homesetions))
        @foreach ($homesetions as $homesetion)
            @php
                $homesetionsprods = App\Http\Controllers\webController::getSectionsProducts($homesetion->id);
            @endphp
            @if (!empty($homesetionsprods) && count($homesetionsprods) > 0)
                <div class="container-indent">
                    <div class="container container-fluid-custom-mobile-padding">
                        <div class="tt-block-title">

                            <h1 class="tt-title noborder"><a
                                    href="{{ !empty($homesetion->link) ? $homesetion->link : url(app()->getLocale().'/allsections/' . $homesetion->id . '/' . $homesetion->slug) }}">@if (app()->getLocale() == 'en') {{ strtoupper($homesetion->title_en) }} @else {{ $homesetion->title_ar }} @endif</a>
                            </h1>
                            <div class="tt-description">&nbsp;</div>
                        </div>
                        <div
                            class="tt-carousel-products row arrow-location-tab arrow-location-tab01 tt-alignment-img tt-collection-listing slick-animated-show-js">
                            @foreach ($homesetionsprods as $homesetionsprod)
                                @php
                                    $tagsDetails = App\Http\Controllers\webCartController::getTagsName($homesetionsprod->tags_en, $homesetionsprod->tags_ar);
                                    $isStock = App\Http\Controllers\webCartController::IsAvailableQuantity($homesetionsprod->id);
                                @endphp
                                <div class="col-2 col-md-4 col-lg-3">
                                    <div class="tt-product thumbprod-center">
                                        <div class="tt-image-box">
                                            <a href="javascript:;" class="tt-btn-quickview loadquickviewmodal"
                                                data-tooltip="{{ __('webMessage.quickview') }}"
                                                data-tposition="{{ __('webMessage.align') }}"
                                                id="{{ $homesetionsprod->id }}"></a>
                                            <a href="javascript:;" class="tt-btn-wishlist addtowishlistquick"
                                                data-tooltip="{{ __('webMessage.addtowishlist') }}"
                                                {{ __('webMessage.align') }} id="{{ $homesetionsprod->id }}"></a>
                                            <a
                                                href="{{ url(app()->getLocale().'/'.'directdetails/' . $homesetionsprod->id . '/' . $homesetionsprod->slug ) }}">
                                                <span class="tt-img"><img
                                                        src="{{ url('assets/images/loader.svg') }}"
                                                        data-src="@if ($homesetionsprod->image) {{ url('uploads/product/thumb/' . $homesetionsprod->image) }} @else {{ url('uploads/no-image.png') }} @endif" alt="@if (app()->getLocale() == 'en') {{ $homesetionsprod->title_en }} @else {{ $homesetionsprod->title_ar }} @endif"></span>
                                                @if ($homesetionsprod->rollover_image)
                                                    <span class="tt-img-roll-over"><img
                                                            src="{{ url('assets/images/loader.svg') }}"
                                                            data-src="@if ($homesetionsprod->rollover_image) {{ url('uploads/product/thumb/' . $homesetionsprod->rollover_image) }} @else {{ url('uploads/no-image.png') }} @endif" alt="@if (app()->getLocale() == 'en') {{ $homesetionsprod->title_en }} @else {{ $homesetionsprod->title_ar }} @endif"></span>
                                                @endif
                                                <span class="tt-label-location">
                                                    @if (empty($isStock))<span class="tt-label-sale">{{ __('webMessage.outofstock') }}</span>@endif
                                                    @if (!empty($homesetionsprod->caption_en))<span class="tt-label-sale" style="background-color:{{ $homesetionsprod['caption_color'] }};">{{ $homesetionsprod['caption_' . $strLang] }}</span>@endif
                                                </span>
                                            </a>
                                            <!--countdown-->
                                            @if (!empty($homesetionsprod->countdown_datetime) && strtotime($homesetionsprod->countdown_datetime) > strtotime(date('Y-m-d')))
                                                <div class="tt-countdown_box">
                                                    <div class="tt-countdown_inner">
                                                        <div class="tt-countdown"
                                                            data-date="{{ $homesetionsprod->countdown_datetime }}"
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
                                            <span id="responseMsg-{{ $homesetionsprod->id }}"></span>

                                            <h2 class="tt-title"><a
                                                    href="{{ url(app()->getLocale().'/'.'directdetails/' . $homesetionsprod->id . '/' . $homesetionsprod->slug ) }}">{!! Common::getLangString($homesetionsprod->title_en, $homesetionsprod->title_ar) !!}{!! Common::getLangStringExtra($homesetionsprod->extra_title_en, $homesetionsprod->extra_title_ar) !!}</a>
                                            </h2>
                                            @if (!empty($tagsDetails)){!! $tagsDetails !!}@endif
                                            <div class="tt-price">
                                                @if (!empty($homesetionsprod->countdown_datetime) && strtotime($homesetionsprod->countdown_datetime) > strtotime(date('Y-m-d')))
                                                    <span
                                                        class="new-price price_red">{{ $homesetionsprod->countdown_price }}
                                                        {{ __('webMessage.kd') }}</span>
                                                    <input type="hidden" id="pixel_price_{{ $homesetionsprod->id }}"
                                                        value="{{ $homesetionsprod->countdown_price }}">
                                                    <span
                                                        class="old-price price_black">{{ $homesetionsprod->retail_price }}
                                                        {{ __('webMessage.kd') }}</span>
                                                @else
                                                    <span
                                                        class="new-price @if ($homesetionsprod->old_price) price_red @endif">{{ $homesetionsprod->retail_price }}
                                                        {{ __('webMessage.kd') }}</span>
                                                    <input type="hidden" id="pixel_price_{{ $homesetionsprod->id }}"
                                                        value="{{ $homesetionsprod->retail_price }}">
                                                    @if (!empty($homesetionsprod->old_price))
                                                        <span
                                                            class="old-price price_black">{{ $homesetionsprod->old_price }}
                                                            {{ __('webMessage.kd') }}</span>
                                                    @endif
                                                @endif
                                            </div>
                                            <div class="tt-product-inside-hover">
                                                <div class="tt-row-btn">
                                                    @if ($homesetionsprod->is_attribute)
                                                        <a href="{{ url(app()->getLocale().'/'.'directdetails/' . $homesetionsprod->id . '/' . $homesetionsprod->slug ) }}"
                                                            class="tt-btn-addtocart thumbprod-button-bg"
                                                            id="{{ $homesetionsprod->id }}">{{ __('webMessage.details') }}</a>
                                                    @else
                                                        @if (!empty($isStock))
                                                            @if ($homesetionsprod->is_active == '2')
                                                                <a href="javascript:;"
                                                                    class="tt-btn-addtocart thumbprod-button-bg addtocartsingle addToCartPixelButton"
                                                                    id="{{ $homesetionsprod->id }}">{{ __('webMessage.preorder') }}</a>
                                                            @else
                                                                <a href="javascript:;"
                                                                    class="tt-btn-addtocart thumbprod-button-bg addtocartsingle addToCartPixelButton"
                                                                    id="{{ $homesetionsprod->id }}">{{ __('webMessage.addtocart_btn') }}</a>
                                                            @endif
                                                        @endif
                                                    @endif
                                                </div>
                                                <div class="tt-row-btn">
                                                    <a href="javascript:;" class="tt-btn-quickview loadquickviewmodal"
                                                        id="{{ $homesetionsprod->id }}"></a>
                                                    <a href="javascript:;" class="tt-btn-wishlist addtowishlistquick"
                                                        id="{{ $homesetionsprod->id }}"></a>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    @endif
@endif
@if ($settingInfo->theme == 2 || $settingInfo->theme == 16)
    @if (!empty($homesetions))
        <div class="container-indent">
            <div class="container container-fluid-custom-mobile-padding">
                <div class="tt-tab-wrapper text-center">
                    <ul class="nav nav-tabs tt-tabs-default text-center" role="tablist">
                        @foreach ($homesetions as $key => $homesetion)
                            @php
                                $homesetionsprodsTab = App\Http\Controllers\webController::getSectionsProducts($homesetion->id);
                            @endphp
                            @if (!empty($homesetionsprodsTab) && count($homesetionsprodsTab) > 0)
                                <li class="nav-item">
                                    <a class="nav-link  @if ($key == 0) active @endif" data-toggle="tab" href="#tt-tab01-{{ $homesetion->id }}">@if (app()->getLocale() == 'en' && !empty($homesetion->title_en)) {{ strtoupper($homesetion->title_en) }} @elseif(app()->getLocale()=="ar" && !empty($homesetion->title_ar)) {{ $homesetion->title_ar }} @endif</a>
					</li>
                    @endif
                    @endforeach
				</ul>
				<div class="
                                        tab-content">
                                        @foreach ($homesetions as $key => $homesetion)
                                            <div class="tab-pane @if ($key == 0) active @endif"
                                                id="tt-tab01-{{ $homesetion->id }}">
                                                <div class="row arrow-location-tab tt-layout-product-item">
                                                    @php
                                                        $homesetionsprods = App\Http\Controllers\webController::getSectionsProducts($homesetion->id);
                                                    @endphp
                                                    @if (!empty($homesetionsprods) && count($homesetionsprods) > 0)
                                                        @foreach ($homesetionsprods as $homesetionsprod)
                                                            @php
                                                                $tagsDetails = App\Http\Controllers\webCartController::getTagsName($homesetionsprod->tags_en, $homesetionsprod->tags_ar);
                                                                $isStock = App\Http\Controllers\webCartController::IsAvailableQuantity($homesetionsprod->id);
                                                            @endphp
                                                            <div class="col-6 col-md-4 col-lg-3">
                                                                <div class="tt-product product-nohover">
                                                                    <div class="tt-image-box">
                                                                        <a href="javascript:;"
                                                                            class="tt-btn-quickview loadquickviewmodal"
                                                                            data-tooltip="{{ __('webMessage.quickview') }}"
                                                                            data-tposition="{{ __('webMessage.align') }}"
                                                                            id="{{ $homesetionsprod->id }}"></a>
                                                                        <a href="javascript:;"
                                                                            class="tt-btn-wishlist addtowishlistquick"
                                                                            data-tooltip="{{ __('webMessage.addtowishlist') }}"
                                                                            {{ __('webMessage.align') }}
                                                                            id="{{ $homesetionsprod->id }}"></a>
                                                                        <a
                                                                            href="{{ url(app()->getLocale().'/'.'directdetails/' . $homesetionsprod->id . '/' . $homesetionsprod->slug ) }}">
                                                                            <span class="tt-img"><img
                                                                                    src="{{ url('assets/images/loader.svg') }}"
                                                                                    data-src="@if ($homesetionsprod->image) {{ url('uploads/product/thumb/' . $homesetionsprod->image) }} @else {{ url('uploads/no-image.png') }} @endif" alt="@if (app()->getLocale() == 'en') {{ $homesetionsprod->title_en }} @else {{ $homesetionsprod->title_ar }} @endif"></span>
                                                                            @if ($homesetionsprod->rollover_image)
                                                                                <span class="tt-img-roll-over"><img
                                                                                        src="{{ url('assets/images/loader.svg') }}"
                                                                                        data-src="@if ($homesetionsprod->rollover_image) {{ url('uploads/product/thumb/' . $homesetionsprod->rollover_image) }} @else {{ url('uploads/no-image.png') }} @endif" alt="@if (app()->getLocale() == 'en') {{ $homesetionsprod->title_en }} @else {{ $homesetionsprod->title_ar }} @endif"></span>
                                                                            @endif
                                                                            <span class="tt-label-location">
                                                                                @if (empty($isStock))<span class="tt-label-sale">{{ __('webMessage.outofstock') }}</span>@endif
                                                                                @if (!empty($homesetionsprod->caption_en))<span class="tt-label-sale" style="background-color:{{ $homesetionsprod['caption_color'] }};">{{ $homesetionsprod['caption_' . $strLang] }}</span>@endif
                                                                            </span>
                                                                        </a>
                                                                        <!--countdown-->
                                                                        @if (!empty($homesetionsprod->countdown_datetime) && strtotime($homesetionsprod->countdown_datetime) > strtotime(date('Y-m-d')))
                                                                            <div class="tt-countdown_box">
                                                                                <div class="tt-countdown_inner">
                                                                                    <div class="tt-countdown"
                                                                                        data-date="{{ $homesetionsprod->countdown_datetime }}"
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
                                                                        <span
                                                                            id="responseMsg-{{ $homesetionsprod->id }}"></span>

                                                                        <h2 class="tt-title tt-title-custom"><a
                                                                                href="{{ url(app()->getLocale().'/'.'directdetails/' . $homesetionsprod->id . '/' . $homesetionsprod->slug ) }}">{!! Common::getLangString($homesetionsprod->title_en, $homesetionsprod->title_ar) !!}{!! Common::getLangStringExtra($homesetionsprod->extra_title_en, $homesetionsprod->extra_title_ar) !!}</a>
                                                                        </h2>
                                                                        @if (!empty($tagsDetails)){!! $tagsDetails !!}@endif
                                                                        <div class="tt-price">
                                                                            @if (!empty($homesetionsprod->countdown_datetime) && strtotime($homesetionsprod->countdown_datetime) > strtotime(date('Y-m-d')))
                                                                                <span
                                                                                    class="new-price price_red">{{ $homesetionsprod->countdown_price }}
                                                                                    {{ __('webMessage.kd') }}</span>
                                                                                <input type="hidden"
                                                                                    id="pixel_price_{{ $homesetionsprod->id }}"
                                                                                    value="{{ $homesetionsprod->countdown_price }}">
                                                                                <span
                                                                                    class="old-price price_black">{{ $homesetionsprod->retail_price }}
                                                                                    {{ __('webMessage.kd') }}</span>
                                                                            @else
                                                                                <span
                                                                                    class="new-price @if ($homesetionsprod->old_price) price_red @endif">{{ $homesetionsprod->retail_price }}
                                                                                    {{ __('webMessage.kd') }}</span>
                                                                                <input type="hidden"
                                                                                    id="pixel_price_{{ $homesetionsprod->id }}"
                                                                                    value="{{ $homesetionsprod->retail_price }}">
                                                                                @if (!empty($homesetionsprod->old_price))
                                                                                    <span
                                                                                        class="old-price price_black">{{ $homesetionsprod->old_price }}
                                                                                        {{ __('webMessage.kd') }}</span>
                                                                                @endif
                                                                            @endif
                                                                        </div>
                                                                        <div class="tt-product-inside-hover">
                                                                            <div class="tt-row-btn">
                                                                                @if ($homesetionsprod->is_attribute)
                                                                                    <a href="{{ url(app()->getLocale().'/'.'directdetails/' . $homesetionsprod->id . '/' . $homesetionsprod->slug ) }}"
                                                                                        class="tt-btn-addtocart thumbprod-button-bg"
                                                                                        id="{{ $homesetionsprod->id }}">{{ __('webMessage.details') }}</a>
                                                                                @else
                                                                                    @if (!empty($isStock))
                                                                                        @if ($homesetionsprod->is_active == '2')
                                                                                            <a href="javascript:;"
                                                                                                class="tt-btn-addtocart thumbprod-button-bg addtocartsingle addToCartPixelButton"
                                                                                                id="{{ $homesetionsprod->id }}">{{ __('webMessage.preorder') }}</a>
                                                                                        @else
                                                                                            <a href="javascript:;"
                                                                                                class="tt-btn-addtocart thumbprod-button-bg addtocartsingle addToCartPixelButton"
                                                                                                id="{{ $homesetionsprod->id }}">{{ __('webMessage.addtocart_btn') }}</a>
                                                                                        @endif
                                                                                    @endif
                                                                                @endif
                                                                            </div>
                                                                            <div class="tt-row-btn">
                                                                                <a href="javascript:;"
                                                                                    class="tt-btn-quickview loadquickviewmodal"
                                                                                    id="{{ $homesetionsprod->id }}"></a>
                                                                                <a href="javascript:;"
                                                                                    class="tt-btn-wishlist addtowishlistquick"
                                                                                    id="{{ $homesetionsprod->id }}"></a>
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @endif

                                                </div>
                                            </div>

                                        @endforeach


                </div>
            </div>
        </div>
        </div>
    @endif
@endif
