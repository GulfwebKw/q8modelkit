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
    @if (!empty($newitems) && count($newitems) > 0)
        <div class="container-indent">
            <div class="container container-fluid-custom-mobile-padding">
                <div class="tt-block-title">
                    @if ($settingInfo->theme != 2)
                        <h1 class="tt-title noborder"><a
                                href="{{ !empty($link) ? $link : '#' }}">{{ strtoupper(trans('webMessage.latestproducts')) }}</a>
                        </h1>
                    @else
                        <h3 class="tt-title tt-title-span"><span><a
                                    href="{{ !empty($link) ? $link : '#' }}">{{ trans('webMessage.latestproducts') }}</a></span>
                        </h3>
                    @endif

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
                                    <a href="{{ url(app()->getLocale().'/'.'directdetails/' . $newitem->id . '/' . $newitem->slug ) }}">
                                        <span class="tt-img"><img src="{{ url('assets/images/loader.svg') }}"
                                                data-src="@if ($newitem->image) {{ url('uploads/product/thumb/' . $newitem->image) }} @else {{ url('uploads/no-image.png') }} @endif" alt="@if (app()->getLocale() == 'en') {{ $newitem->title_en }} @else {{ $newitem->title_ar }} @endif"></span>
                                        @if ($newitem->rollover_image)
                                            <span class="tt-img-roll-over"><img
                                                    src="{{ url('assets/images/loader.svg') }}"
                                                    data-src="@if ($newitem->rollover_image) {{ url('uploads/product/thumb/' . $newitem->rollover_image) }} @else {{ url('uploads/no-image.png') }} @endif" alt="@if (app()->getLocale() == 'en') {{ $newitem->title_en }} @else {{ $newitem->title_ar }} @endif"></span>
                                        @endif
                                        <span class="tt-label-location">
                                            @if (empty($isStock))<span class="tt-label-sale" style="background-color:#000000;color:#ffffff;">{{ strtoupper(__('webMessage.outofstock')) }}</span>@endif
                                            <span class="tt-label-sale price_new">{{ __('webMessage.new') }}</span>
                                            @if (!empty($newitem->caption_en))<span class="tt-label-sale" style="background-color:{{ $newitem['caption_color'] }};">{{ strtoupper($newitem['caption_' . $strLang]) }}</span>@endif
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

                                    <h2 class="tt-title" style="min-height:50px;"><a
                                            href="{{ url(app()->getLocale().'/'.'directdetails/' . $newitem->id . '/' . $newitem->slug ) }}">{!! Common::getLangString($newitem->title_en, $newitem->title_ar) !!}{!! Common::getLangStringExtra($newitem->extra_title_en, $newitem->extra_title_ar) !!}</a>
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
                                            <span
                                                class="new-price @if ($newitem->old_price) price_red @endif">{{ $newitem->retail_price }}
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
                                                <a href="{{ url(app()->getLocale().'/'.'directdetails/' . $newitem->id . '/' . $newitem->slug ) }}"
                                                    class="tt-btn-addtocart thumbprod-button-bg"
                                                    id="{{ $newitem->id }}">{{ __('webMessage.details') }}</a>
                                            @else
                                                @if (!empty($isStock))
                                                    @if ($newitem->is_active == '2')
                                                        <a href="javascript:;"
                                                            class="tt-btn-addtocart thumbprod-button-bg addtocartsingle @if (!empty($settingInfo->facebook_pixel)) addToCartPixelButton @endif"
                                                            id="{{ $newitem->id }}">{{ __('webMessage.preorder') }}</a>
                                                    @else
                                                        <a href="javascript:;"
                                                            class="tt-btn-addtocart thumbprod-button-bg addtocartsingle @if (!empty($settingInfo->facebook_pixel)) addToCartPixelButton @endif"
                                                            id="{{ $newitem->id }}">{{ __('webMessage.addtocart_btn') }}</a>
                                                    @endif
                                                @endif
                                            @endif
                                        </div>
                                        <div class="tt-row-btn">
                                            <a href="javascript:;" class="tt-btn-quickview loadquickviewmodal"
                                                id="{{ $newitem->id }}"></a>
                                            @if (!empty($isStock))
                                                <a href="javascript:;"
                                                    class="tt-btn-wishlist addtocartsingle @if (!empty($settingInfo->facebook_pixel)) addToCartPixelButton @endif"
                                                    id="{{ $newitem->id }}"></a>
                                            @endif
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
@endif
<!--end new items display -->
