@php
$brandBestSellers = App\Http\Controllers\webController::BestSellerBrandsList();
@endphp

@if (!empty($settingInfo->is_brand_active) && !empty($brandBestSellers) && count($brandBestSellers) > 0)
    <div class="container-indent">
        <div class="container container-fluid-custom-mobile-padding">
            <div class="tt-block-title">
                @if ($settingInfo->theme == 2)
                    <h3 class="tt-title tt-title-span"><span><a
                                href="{{ !empty($link) ? $link : '#' }}">{{ strtoupper(trans('webMessage.bestsellerof')) }}</a></span>
                    </h3>
                @elseif ($settingInfo->theme == 17)
                        <h1 class="tt-title tt-title-span"><span><a
                                    style="margin:20px 0; color:#f29a33; font-weight:bold; font-size:25px;"
                                    href="{{ !empty($link) ? $link : '#' }}">{{ strtoupper(trans('webMessage.bestsellerof')) }}</a></span>
                        </h1>
                    @else
                        <h1 class="tt-title noborder "><a
                                href="{{ !empty($link) ? $link : '#' }}">{{ strtoupper(trans('webMessage.bestsellerof')) }}</a>
                        </h1>
                    @endif
                    <div class="tt-description">&nbsp;</div>
                    <br clear="all" /><br clear="all" />
            </div>

            <div class="tt-tab-wrapper text-center">
                <ul class="nav nav-tabs tt-tabs-default text-center" role="tablist">
                    @foreach ($brandBestSellers as $key => $brandBestSeller)


                        <li class="nav-item">
                            <a class="nav-link  @if ($key == 0) active @endif" data-toggle="tab"
                                href="#tt-tab01-{{ $brandBestSeller->id }}">
                                @if (app()->getLocale() == 'en' && !empty($brandBestSeller->title_en))
                                {{ strtoupper($brandBestSeller->title_en) }} @elseif(app()->getLocale() == 'ar' && !empty($brandBestSeller->title_ar))
                                    {{ $brandBestSeller->title_ar }} @endif
                            </a>
                        </li>
                    @endforeach
                </ul>
                <div class="tab-content">
                    @foreach ($brandBestSellers as $key => $brandBestSellery)
                        <div class="tab-pane @if ($key == 0) active @endif" id="tt-tab01-{{ $brandBestSellery->id }}">
                            <div class="row arrow-location-tab tt-layout-product-item">
                                @php
                                    $homesetionsprods = App\Http\Controllers\webController::getBrandsProducts($brandBestSellery->id);
                                @endphp
                                @if (!empty($homesetionsprods) && count($homesetionsprods) > 0)
                                    @php $tagsDetails=''; @endphp
                                    @foreach ($homesetionsprods as $homesetionsprod)
                                        @php
                                            $isStock = App\Http\Controllers\webCartController::IsAvailableQuantity($homesetionsprod->id);
                                            $tagsDetails = App\Http\Controllers\webCartController::getTagsName($homesetionsprod->tags_en, $homesetionsprod->tags_ar);
                                            $checkBrandDiscount = App\Http\Controllers\webController::checkBrandDiscount($homesetionsprod->brand_id);
                                            $brandDiscountedPrice = App\Http\Controllers\webController::calByBrandDiscount($homesetionsprod->brand_id, $homesetionsprod->retail_price);
                                        @endphp
                                        <div class="col-6 col-md-4 col-lg-3">
                                            <div class="tt-product thumbprod-center">
                                                <div class="tt-image-box">
                                                    <a href="javascript:;" class="tt-btn-quickview loadquickviewmodal"
                                                        data-tooltip="{{ __('webMessage.quickview') }}"
                                                        data-tposition="{{ __('webMessage.align') }}"
                                                        id="{{ $homesetionsprod->id }}"></a>
                                                    <a href="javascript:;" class="tt-btn-wishlist addtowishlistquick"
                                                        data-tooltip="{{ __('webMessage.addtowishlist') }}"
                                                        {{ __('webMessage.align') }}
                                                        id="{{ $homesetionsprod->id }}"></a>
                                                    <a
                                                        href="{{ url(app()->getLocale() . '/' . 'directdetails/' . $homesetionsprod->id . '/' . $homesetionsprod->slug) }}">
                                                        <span class="tt-img"><img
                                                                src="{{ url('assets/images/loader.svg') }}"
                                                                data-src="   @if ($homesetionsprod->image)
                                                            {{ url('uploads/product/thumb/' . $homesetionsprod->image) }}
                                                        @else {{ url('uploads/no-image.png') }} @endif" alt="
                                                            @if (app()->getLocale() == 'en')
                                                            {{ $homesetionsprod->title_en }} @else
                                                                {{ $homesetionsprod->title_ar }} @endif">
                                                        </span>
                                                        @if ($homesetionsprod->rollover_image)
                                                            <span class="tt-img-roll-over"><img
                                                                    src="{{ url('assets/images/loader.svg') }}"
                                                                    data-src="   @if ($homesetionsprod->rollover_image)
                                                                {{ url('uploads/product/thumb/' . $homesetionsprod->rollover_image) }}
                                                            @else {{ url('uploads/no-image.png') }} @endif" alt="
                                                                @if (app()->getLocale() == 'en')
                                                                {{ $homesetionsprod->title_en }} @else
                                                                    {{ $homesetionsprod->title_ar }} @endif">
                                                            </span>
                                                        @endif
                                                        <span class="tt-label-location">
                                                            @if (empty($isStock))<span
                                                                    class="tt-label-sale">{{ __('webMessage.outofstock') }}</span>
                                                            @endif
                                                            @if (!empty($homesetionsprod->caption_en))<span
                                                                    class="tt-label-sale"
                                                                    style="background-color:{{ $homesetionsprod['caption_color'] }};">{{ $homesetionsprod['caption_' . $strLang] }}</span>
                                                            @endif
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

                                                    <h2 class="tt-title tt-title-custom"><a
                                                            href="{{ url(app()->getLocale() . '/' . 'directdetails/' . $homesetionsprod->id . '/' . $homesetionsprod->slug) }}">
                                                            @if (app()->getLocale() == 'en')
                                                                {{ $homesetionsprod->title_en }}@else{{ $homesetionsprod->title_ar }}
                                                            @endif
                                                        </a></h2>
                                                    @if (!empty($tagsDetails))
                                                        {!! $tagsDetails !!}@endif
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
                                                        @elseif ($checkBrandDiscount)
                                                            <span
                                                                class="new-price price_red">{{ $brandDiscountedPrice->price }}
                                                                {{ __('webMessage.kd') }}</span>
                                                            <input type="hidden"
                                                                id="pixel_price_{{ $homesetionsprod->id }}"
                                                                value="{{ $brandDiscountedPrice->price }}">
                                                            <span
                                                                class="old-price price_black">{{ $brandDiscountedPrice->oldPrice }}
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
                                                                <a href="{{ url(app()->getLocale() . '/' . 'directdetails/' . $homesetionsprod->id . '/' . $homesetionsprod->slug) }}"
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
