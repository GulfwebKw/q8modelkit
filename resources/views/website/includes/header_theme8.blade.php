@php
if (!empty(app()->getLocale())) {
    $strLang = app()->getLocale();
} else {
    $strLang = 'en';
}

$privacy_details = App\Http\Controllers\webController::singlePageDetails(2);
$terms_details = App\Http\Controllers\webController::singlePageDetails(3);
$about_details = App\Http\Controllers\webController::singlePageDetails(1);
@endphp
<header id="tt-header">
    <!--
 <div class="tt-color-scheme-01">
  <div class="container-fluid">
   <div class="tt-header-row tt-top-row">
    <div class="tt-col-left">
     <div class="tt-box-info">
      <ul>
       <li>
                            @if ($settingInfo->phone) 
                            <i class="icon-f-93"></i><a href="tel:{{ $settingInfo->phone }}">{{ $settingInfo->phone }}</a>
                            @endif
                            @if (app()->getLocale() == 'ar' && $settingInfo->office_hours_ar)
       <i class="icon-f-92"></i>&nbsp;{{ $settingInfo->office_hours_ar }}
                        @elseif(app()->getLocale() == 'en' && $settingInfo->office_hours_en)
       <i class="icon-f-92"></i>&nbsp;{{ $settingInfo->office_hours_en }}
                            @endif
                            </li>
      </ul>
     </div>
    </div>
    <div class="tt-col-right ml-auto">
     <ul class="tt-social-icon">
     <li><a  href="javascript:;" id="trackmyorder" class="trackorder icon-f-55" title="{{ trans('webMessage.trackorder') }}">&nbsp;<span>{{ __('webMessage.trackorder') }}</span></a></li>
                        @if ($settingInfo->social_facebook)
      <li><a title="{{ __('webMessage.facebook') }}" class="icon-g-64" target="_blank" href="{{ $settingInfo->social_facebook }}"></a></li>
                        @endif
                        @if ($settingInfo->social_twitter)
      <li><a title="{{ __('webMessage.twitter') }}" class="icon-h-58" target="_blank" href="{{ $settingInfo->social_twitter }}"></a></li>
                        @endif
                        @if ($settingInfo->social_instagram)
      <li><a title="{{ __('webMessage.instagram') }}" class="icon-g-67" target="_blank" href="{{ $settingInfo->social_instagram }}"></a></li>
                        @endif
                        @if ($settingInfo->social_linkedin)
      <li><a title="{{ __('webMessage.linkedin') }}" class="icon-g-68" target="_blank" href="{{ $settingInfo->social_linkedin }}"></a></li>
                        @endif
                        @if ($settingInfo->social_youtube)
      <li><a title="{{ __('webMessage.youtube') }}" class="icon-g-76" target="_blank" href="{{ $settingInfo->social_youtube }}"></a></li>
                        @endif
     </ul>
    </div>
   </div>
  </div>
 </div>
   -->
    <!-- tt-mobile menu -->
    @php
        $mobileMenusTrees = App\Categories::CategoriesTree();
        $mobilebrandMenus = App\Http\Controllers\webController::BrandsList();
    @endphp
    <!-- tt-mobile menu -->
    <nav class="panel-menu mobile-main-menu">
        <ul>
            <li><a href="{{ url('/' . app()->getLocale()) }}">{{ __('webMessage.home') }}</a></li>
            @if (!empty($mobileMenusTrees))
                @each('website.includes.mobilemenu', $mobileMenusTrees, 'category', 'website.includes.nothing')
            @endif
            <li class="deals"><a
                    href="{{ url(app()->getLocale() . '/offers') }}">{{ __('webMessage.offers') }}</a></li>

            @if (!empty($settingInfo->is_brand_active) && !empty($mobilebrandMenus) && count($mobilebrandMenus) > 0)
                <li><a href="javascript:;">{{ __('webMessage.brands') }}</a>
                    <ul>
                        @foreach ($mobilebrandMenus as $brandMenu)
                            <li>
                                <a
                                    href="{{ url(app()->getLocale() . '/brands/' . $brandMenu->slug) }}">@if (app()->getLocale() == 'en') {{ $brandMenu->title_en }} @else {{ $brandMenu->title_ar }} @endif</a>
                                @if (!empty($brandMenu->image) && !empty($settingInfo->is_brand_image_name) && $settingInfo->is_brand_image_name == 'image')
                                    <img src="{{ url('uploads/brand/thumb/' . $brandMenu->image) }}"
                                        style="max-width:40px;max-height:40px;float:right;margin-top:-40px;" />
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </li>
            @endif
            <li><a
                    href="{{ url(app()->getLocale() . '/page/' . $about_details->slug) }}">{{ __('webMessage.aboutus') }}</a>
            </li>
            <li><a href="{{ url(app()->getLocale() . '/contactus') }}">{{ __('webMessage.contactus') }}</a></li>
            <li><a
                    href="{{ url(app()->getLocale() . '/page/' . $privacy_details->slug) }}">{{ __('webMessage.privacypolicy') }}</a>
            </li>
            <li><a
                    href="{{ url(app()->getLocale() . '/page/' . $terms_details->slug) }}">{{ __('webMessage.termsconditions') }}</a>
            </li>
            <li><a href="{{ url(app()->getLocale() . '/faq') }}">{{ __('webMessage.faq') }}</a></li>
        </ul>
        <div class="mm-navbtn-names">
            <div class="mm-closebtn">{{ __('webMessage.close') }}</div>
            <div class="mm-backbtn">{{ __('webMessage.back') }}</div>
        </div>
    </nav>
    <!-- tt-mobile-header -->
    <div class="tt-mobile-header">
        <div class="container-fluid">
            <div class="tt-header-row">
                <div class="tt-mobile-parent-menu">
                    <div class="tt-menu-toggle" id="js-menu-toggle">
                        <i class="icon-03"></i>
                    </div>
                </div>
                <!-- search -->
                <div class="tt-mobile-parent-search tt-parent-box"></div>
                <!-- /search -->
                <!-- cart -->
                <div class="tt-mobile-parent-cart tt-parent-box"></div>
                <!-- /cart -->
                <!-- account -->
                <div class="tt-mobile-parent-account tt-parent-box"></div>
                <!-- /account -->
                <!-- currency -->
                <div class="tt-mobile-parent-multi tt-parent-box"></div>
                <!-- /currency -->
            </div>
        </div>
        <div class="container-fluid tt-top-line">
            <div class="row">
                <div class="tt-logo-container">
                    <!-- mobile logo -->
                    @if ($settingInfo->logo)
                        <a class="tt-logo tt-logo-alignment" href="{{ url('/' . app()->getLocale()) }}"><img
                                class="tt-retina" src="{{ url('uploads/logo/' . $settingInfo->logo) }}"
                                alt="@if (app()->getLocale() == 'en') {{ $settingInfo->name_en }} @else {{ $settingInfo->name_ar }} @endif" title="@if (app()->getLocale() == 'en') {{ $settingInfo->name_en }} @else {{ $settingInfo->name_ar }} @endif"></a>
                    @endif
                    <!-- /mobile logo -->
                </div>
            </div>
        </div>
    </div>
    @php
        $desktopMenusTrees = App\Categories::CategoriesTree();
        $brandMenus = App\Http\Controllers\webController::BrandsList();
    @endphp
    <!-- tt-desktop-header -->
    <div class="tt-desktop-header">
        <!--for transparent = tt-header-static -->
        <div class="container">
            <div class="tt-header-holder">
                <div class="tt-col-obj tt-obj-logo">
                    <!-- logo -->
                    @if ($settingInfo->logo)
                        <a class="tt-logo tt-logo-alignment" href="{{ url('/' . app()->getLocale()) }}"><img
                                class="tt-retina" src="{{ url('uploads/logo/' . $settingInfo->logo) }}"
                                alt="@if (app()->getLocale() == 'en') {{ $settingInfo->name_en }} @else {{ $settingInfo->name_ar }} @endif" title="@if (app()->getLocale() == 'en') {{ $settingInfo->name_en }} @else {{ $settingInfo->name_ar }} @endif"></a>
                    @endif
                    <!-- /logo -->
                </div>
                <div class="tt-col-obj tt-obj-menu obj-aligment-center">
                    <!-- tt-menu -->
                    <div class="tt-desctop-parent-menu tt-parent-box">
                        <div class="tt-desctop-menu  tt-hover-03">
                            <nav>
                                <ul>
                                    <li class="dropdown megamenu"><a
                                            href="{{ url('/' . app()->getLocale()) }}">{{ __('webMessage.home') }}</a>
                                    </li>

                                    @if (!empty($desktopMenusTrees) && count($desktopMenusTrees) > 0)
                                        @foreach ($desktopMenusTrees as $desktopMenusTree)
                                            @if (!empty($desktopMenusTree->childs) && count($desktopMenusTree->childs) > 0)
                                                <li class="dropdown @if (empty($desktopMenusTree->is_full_width)) tt-megamenu-col-01 @else megamenu @endif @if (!empty($desktopMenusTree->is_highlighted)) deals @endif"><a href="{{ url(app()->getLocale() . '/products/' . $desktopMenusTree->id . '/' . $desktopMenusTree->friendly_url) }}">@if (app()->getLocale() == 'en') {{ $desktopMenusTree->name_en }} @else {{ $desktopMenusTree->name_ar }} @endif</a>
										<div class="
                                                    dropdown-menu">
                                                    @if (!empty($desktopMenusTree->is_full_width))
                                                        <div class="row">
                                                            <div @if ($desktopMenusTree->is_offer) class="col-sm-9 @else  class="col-sm-12 @endif">
                                                                <div class="row tt-col-list">
                                                                    @foreach ($desktopMenusTree->childs as $childCategory)
                                                                        <div class="col-sm-4">

                                                                            <a href="{{ url(app()->getLocale() . '/products/' . $childCategory->id . '/' . $childCategory->friendly_url) }}"
                                                                                class="tt-title-submenu">@if (app()->getLocale() == 'en') {{ $childCategory->name_en }} @else {{ $childCategory->name_ar }} @endif @if ($childCategory->image)<img  src="{{ url('assets/images/loader.svg') }}" data-src="@if ($childCategory->image) {{ url('uploads/category/thumb/' . $childCategory->image) }} @else {{ url('assets/images/custom/header-category-01.jpg') }} @endif" alt="">@endif</a>
                                                                            @if (!empty($childCategory->childs) && count($childCategory->childs) > 0)

                                                                                <ul class="tt-megamenu-submenu">
                                                                                    @foreach ($childCategory->childs as $subchildCategory)
                                                                                        <li><a
                                                                                                href="{{ url(app()->getLocale() . '/products/' . $subchildCategory->id . '/' . $subchildCategory->friendly_url) }}">@if (app()->getLocale() == 'en') {{ $subchildCategory->name_en }} @else {{ $subchildCategory->name_ar }} @endif</a>
                                                                                        </li>
                                                                                    @endforeach
                                                                                </ul>
                                                                            @endif
                                                                        </div>
                                                                    @endforeach

                                                                </div>
                                                            </div>
                                                            @if ($desktopMenusTree->is_offer)
                                                                <div class="col-sm-3">
                                                                    <div class="tt-offset-7">
                                                                        <a href="@if ($desktopMenusTree->offer_link) {{ $desktopMenusTree->offer_link }} @else javascript:; @endif"
                                                                            class="tt-promo-02">
                                                                            @if ($desktopMenusTree->offer_image)
                                                                                <img src="{{ url('assets/images/loader.svg') }}"
                                                                                    data-src="{{ url('uploads/category/' . $desktopMenusTree->offer_image) }}"
                                                                                    alt="">
                                                                            @endif
                                                                            <div
                                                                                class="tt-description tt-point-h-l tt-point-v-t">
                                                                                <div class="tt-description-wrapper">
                                                                                    <div
                                                                                        class="tt-title-small tt-white-color">
                                                                                        @if (app()->getLocale() == 'en') {{ $desktopMenusTree->title_1_en }} @else {{ $desktopMenusTree->title_1_ar }} @endif</div>
                                                                                    <div
                                                                                        class="tt-title-xlarge tt-white-color">
                                                                                        @if (app()->getLocale() == 'en') {{ $desktopMenusTree->title_2_en }} @else {{ $desktopMenusTree->title_2_ar }} @endif</div>
                                                                                    <p class="tt-white-color">
                                                                                        @if (app()->getLocale() == 'en') {{ $desktopMenusTree->title_3_en }} @else {{ $desktopMenusTree->title_3_ar }} @endif</p>
                                                                                    <span
                                                                                        class="btn-underline tt-obj-bottom tt-white-color">@if (app()->getLocale() == 'en') {{ $desktopMenusTree->title_4_en }} @else {{ $desktopMenusTree->title_4_ar }} @endif</span>
                                                                                </div>
                                                                            </div>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    @else
                                                        <div class="row tt-col-list">
                                                            @foreach ($desktopMenusTree->childs as $childCategory)
                                                                <div class="col">
                                                                    <h6 class="tt-title-submenu"><a
                                                                            href="{{ url(app()->getLocale() . '/products/' . $childCategory->id . '/' . $childCategory->friendly_url) }}">@if (app()->getLocale() == 'en') {{ $childCategory->name_en }} @else {{ $childCategory->name_ar }} @endif</a>
                                                                    </h6>
                                                                    @if (!empty($childCategory->childs) && count($childCategory->childs) > 0)
                                                                        <ul class="tt-megamenu-submenu">
                                                                            @foreach ($childCategory->childs as $subchildCategory)
                                                                                <li><a
                                                                                        href="{{ url(app()->getLocale() . '/products/' . $subchildCategory->id . '/' . $subchildCategory->friendly_url) }}">@if (app()->getLocale() == 'en') {{ $subchildCategory->name_en }} @else {{ $subchildCategory->name_ar }} @endif</a>
                                                                                </li>
                                                                            @endforeach
                                                                        </ul>
                                                                    @endif
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @endif
                        </div>
                        </li>
                    @else
                        <li class="dropdown @if (!empty($desktopMenusTree->is_highlighted)) deals @endif"><a href="{{ url(app()->getLocale() . '/products/' . $desktopMenusTree->id . '/' . $desktopMenusTree->friendly_url) }}">@if (app()->getLocale() == 'en') {{ $desktopMenusTree->name_en }} @else {{ $desktopMenusTree->name_ar }} @endif</a></li>
                                    @endif
                                    @endforeach
									@endif
                                    @if (!empty($settingInfo->is_offer_menu))
                                    <li class="
                            dropdown deals"><a
                                href="{{ url(app()->getLocale() . '/offers') }}">{{ __('webMessage.offers') }}</a></li>
                        @endif
                        @if (!empty($settingInfo->is_brand_active) && !empty($brandMenus) && count($brandMenus) > 0)
                            <li class="dropdown megamenu">
                                <a href="javascript:;">{{ __('webMessage.brands') }}</a>
                                <div class="dropdown-menu">
                                    <div class="row tt-col-list">
                                        @foreach ($brandMenus as $brandMenu)
                                            <div class="col-sm-2">
                                                <a href="{{ url(app()->getLocale() . '/brands/' . $brandMenu->slug) }}">
                                                    @if (!empty($brandMenu->image) && $settingInfo->is_brand_image_name == 'image')
                                                        <img src="{{ url('uploads/brand/thumb/' . $brandMenu->image) }}"
                                                            style="max-width:100px;max-height:100px;" />
                                                    @else
                                                        @if (app()->getLocale() == 'en') {{ $brandMenu->title_en }} @else {{ $brandMenu->title_ar }} @endif
                                                    @endif
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </li>
                        @endif
                        @if (!empty($settingInfo->is_more_menu))
                            <li class="dropdown">
                                <a href="javascript:;">{{ __('webMessage.more') }}</a>
                                <div class="dropdown-menu">
                                    <ul class="tt-megamenu-submenu">
                                        <li><a
                                                href="{{ url(app()->getLocale() . '/page/' . $about_details->slug) }}">{{ __('webMessage.aboutus') }}</a>
                                        </li>
                                        <li><a
                                                href="{{ url(app()->getLocale() . '/contactus') }}">{{ __('webMessage.contactus') }}</a>
                                        </li>
                                        <li><a
                                                href="{{ url(app()->getLocale() . '/page/' . $privacy_details->slug) }}">{{ __('webMessage.privacypolicy') }}</a>
                                        </li>
                                        <li><a
                                                href="{{ url(app()->getLocale() . '/page/' . $terms_details->slug) }}">{{ __('webMessage.termsconditions') }}</a>
                                        </li>
                                        <li><a
                                                href="{{ url(app()->getLocale() . '/faq') }}">{{ __('webMessage.faq') }}</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        @endif

                        @if ((new \App\bundleSetting())->is_active )
                        <li class="dropdown tt-megamenu-col-02"><a
                                    href="{{ route('webBundle', [app()->getLocale()]) }}">{{ __('webMessage.bundles.Bundle') }}</a>
                        </li>
                        @endif

                        </ul>
                        </nav>
                    </div>
                </div>
                <!-- /tt-menu -->
            </div>
            <div class="tt-col-obj tt-obj-options obj-move-right">
                <!-- tt-search -->
                <div class="tt-desctop-parent-search tt-parent-box">
                    <div class="tt-search tt-dropdown-obj">
                        <button class="tt-dropdown-toggle" data-tooltip="{{ __('webMessage.search_txt') }}"
                            data-tposition="bottom">
                            <i class="icon-f-85"></i>
                        </button>
                        <div class="tt-dropdown-menu">
                            <div class="container">
                                <form name="topsearchform1" id="topsearchform1" method="get"
                                    action="{{ url(app()->getLocale() . '/search') }}">
                                    <div class="tt-col">
                                        <input type="text" class="tt-search-input" name="sq" id="search_keyname"
                                            placeholder="{{ __('webMessage.searchproducts') }}"
                                            value="@if (Request()->sq){{ Request()->sq }}@endif">
                                        <button name="" id="search_btns" class="tt-btn-search" type="submit"></button>
                                    </div>
                                    <div class="tt-col">
                                        <button name="close_btn" id="close_btn" value="{{ __('webMessage.close') }}"
                                            class="tt-btn-close icon-g-80"></button>
                                    </div>
                                    <div class="tt-info-text">
                                        {{ __('webMessage.whatareyoulookingfor') }}
                                    </div>
                                    <div class="search-results">
                                        <p>
                                            <span id="search_child_results"></span>
                                        </p>
                                        <button id="viewallsearchresult" type="button"
                                            class="tt-view-all">{{ __('webMessage.viewallproducts') }}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /tt-search -->
                <!-- tt-cart -->
                <div class="tt-desctop-parent-cart tt-parent-box">
                    @php
                        $tempOrdersCount = App\Http\Controllers\webCartController::countTempOrders();
                        $tempOrders = App\Http\Controllers\webCartController::loadTempOrders();
                    @endphp
                    <div class="tt-cart tt-dropdown-obj" data-tooltip="Cart" data-tposition="bottom">
                        <button class="tt-dropdown-toggle">
                            <i class="icon-f-39"></i>
                            <span class="tt-badge-cart"><span id="tt-badge-cart">{{ $tempOrdersCount }}</span></span>
                        </button>
                        <div class="tt-dropdown-menu">
                            <div class="tt-mobile-add">
                                <h6 class="tt-title">{{ __('webMessage.shoppingcart') }}</h6>
                                <button class="tt-close">{{ __('webMessage.close') }}</button>
                            </div>
                            <div class="tt-dropdown-inner">
                                <div class="tt-cart-layout" id="TempOrderBoxDiv">
                                    @if (empty($tempOrders) || count($tempOrders) == 0)
                                        <!-- layout emty cart -->
                                        <a href="javascript:;" class="tt-cart-empty">
                                            <i class="icon-f-39"></i>
                                            <p>{{ __('webMessage.yourcartisempty') }}</p>
                                        </a>
                                    @else

                                        <div class="tt-cart-content">

                                            <div class="tt-cart-list">
                                                @php
                                                    $subTotalAmount = 0;
                                                    $attrtxt = '';
                                                    $t = 1;
                                                @endphp
                                                @foreach ($tempOrders as $tempOrder)
                                                    @php
                                                        $prodDetails = App\Http\Controllers\webCartController::getProductDetails($tempOrder->product_id);
                                                        if ($prodDetails->image) {
                                                            $prodImage = url('uploads/product/thumb/' . $prodDetails->image);
                                                        } else {
                                                            $prodImage = url('uploads/no-image.png');
                                                        }
                                                        
                                                        $subTotalAmount += $tempOrder->quantity * $tempOrder->unit_price;
                                                        if (!empty($tempOrder->size_id)) {
                                                            $sizeName = App\Http\Controllers\webCartController::sizeNameStatic($tempOrder->size_id, $strLang);
                                                            $attrtxt .= '<li>' . __('webMessage.size') . ': ' . $sizeName . '</li>';
                                                        }
                                                        if (!empty($tempOrder->color_id)) {
                                                            $colorName = App\Http\Controllers\webCartController::colorNameStatic($tempOrder->color_id, $strLang);
                                                            $attrtxt .= '<li>' . __('webMessage.color') . ': ' . $colorName . '</li>';
                                                            $colorImageDetails = App\Http\Controllers\webCartController::getColorImage($tempOrder->product_id, $tempOrder->color_id);
                                                            if (!empty($colorImageDetails->color_image)) {
                                                                $prodImage = url('uploads/color/thumb/' . $colorImageDetails->color_image);
                                                            }
                                                        }
                                                        $optionsDetailstxt = App\Http\Controllers\webCartController::getOptionsDtails($tempOrder->id);
                                                        
                                                    @endphp
                                                    <div class="tt-item" @if ($t > 3)style="display:none;"@endif>
                                                        <a
                                                            href="{{ url(app()->getLocale() . '/directdetails/' . $prodDetails->id . '/' . $prodDetails->slug) }}">
                                                            <div class="tt-item-img">
                                                                <img src="{{ url('assets/images/loader.svg') }}"
                                                                    data-src="{{ $prodImage }}"
                                                                    alt="@if (app()->getLocale() == 'en') {{ $prodDetails->title_en }} @else {{ $prodDetails->title_ar }} @endif">
                                                            </div>
                                                            <div class="tt-item-descriptions">
                                                                <h2 class="tt-title">@if (app()->getLocale() == 'en') {{ $prodDetails->title_en }} @else {{ $prodDetails->title_ar }} @endif
                                                                </h2>
                                                                <ul class="tt-add-info">
                                                                    {!! $attrtxt !!}
                                                                    {!! $optionsDetailstxt !!}
                                                                </ul>
                                                                <div class="tt-quantity">
                                                                    {{ $tempOrder->quantity }} X</div>
                                                                <div class="tt-price">
                                                                    {{ $tempOrder->unit_price }}
                                                                    {{ __('webMessage.kd') }} </div>
                                                            </div>
                                                        </a>
                                                        <div class="tt-item-close">
                                                            <a href="javascript:;" id="{{ $tempOrder->id }}"
                                                                class="tt-btn-close deleteFromTemp"></a>
                                                        </div>
                                                    </div>
                                                    @php
                                                        $attrtxt = '';
                                                        $t++;
                                                    @endphp
                                                @endforeach

                                                @if ($t > 3)
                                                    <div class="tt-item" align="center"><a
                                                            href="{{ url(app()->getLocale() . '/cart') }}">{{ trans('webMessage.viewall') }}(+{{ $t - 4 }})</a>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="tt-cart-total-row">
                                                @php
                                                    $bundleDiscount = App\Http\Controllers\webCartController::loadTempOrdersBundleDiscount($tempOrders);
                                                    $subTotalAmount = $subTotalAmount - $bundleDiscount;
                                                @endphp
                                                @if ($bundleDiscount > 0)
                                                    <div class="tt-cart-total-title">
                                                        {{ __('webMessage.bundles.BundleDiscount') }}:
                                                    </div>
                                                    <div class="tt-cart-total-price" style="color: #FF0000;">
                                                        {{ round($bundleDiscount, 3) }}
                                                        {{ __('webMessage.kd') }}</div>
                                            </div>
                                            <div class="tt-cart-total-row"
                                                style="margin-top: 0px;padding-top: 10px;border-top: 0px;">
                                    @endif
                                    <div class="tt-cart-total-title">{{ __('webMessage.subtotal') }}:</div>
                                    <div class="tt-cart-total-price"> {{ round($subTotalAmount, 3) }}
                                        {{ __('webMessage.kd') }}</div>
                                </div>
                                <div class="tt-cart-btn">
                                    <div class="tt-item">
                                        <a href="{{ url(app()->getLocale() . '/checkout') }}"
                                            class="btn">{{ __('webMessage.checkout') }}</a>
                                    </div>
                                    <div class="tt-item">
                                        <a href="{{ url(app()->getLocale() . '/cart') }}"
                                            class="btn-link-02 tt-hidden-mobile">{{ __('webMessage.viewcart') }}</a>
                                        <a href="{{ url(app()->getLocale() . '/cart') }}"
                                            class="btn btn-border tt-hidden-desctope">{{ __('webMessage.viewcart') }}</a>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /tt-cart -->
        <!-- tt-account -->
        <div class="tt-desctop-parent-account tt-parent-box">
            <div class="tt-account tt-dropdown-obj">
                <button class="tt-dropdown-toggle" data-tooltip="{{ __('webMessage.myaccount') }}"
                    data-tposition="bottom"><i class="icon-f-94"></i></button>
                <div class="tt-dropdown-menu">
                    <div class="tt-mobile-add">
                        <button class="tt-close">{{ __('webMessage.close') }}</button>
                    </div>
                    <div class="tt-dropdown-inner">
                        <ul>

                            @if (!empty(Auth::guard('webs')->user()->id))
                                <li><a href="{{ url(app()->getLocale() . '/account') }}"><i
                                            class="icon-f-94"></i>{{ __('webMessage.dashboard') }}</a></li>
                                <li><a href="{{ url(app()->getLocale() . '/changepass') }}"><i
                                            class="icon-g-40"></i>{{ __('webMessage.changepassword') }}</a></li>
                                <li><a href="{{ url(app()->getLocale() . '/editprofile') }}"><i
                                            class="icon-01"></i>{{ __('webMessage.editprofile') }}</a></li>
                                <li><a href="{{ url(app()->getLocale() . '/myorders') }}"><i
                                            class="icon-f-68"></i>{{ __('webMessage.myorders') }}</a></li>
                                <li><a href="{{ url(app()->getLocale() . '/wishlist') }}"><i
                                            class="icon-n-072"></i>{{ __('webMessage.wishlists') }}</a></li>
                                <li><a href="javascript:void(0);"
                                        onclick="event.preventDefault();document.getElementById('logout-forms').submit();"
                                        target="_blank"><i
                                            class="icon-f-77"></i>{{ __('webMessage.signout') }}</a></li>
                                <form id="logout-forms" action="{{ url(app()->getLocale() . '/logout') }}"
                                    method="POST" style="display: none;">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                </form>
                            @else
                                <li><a href="{{ url(app()->getLocale() . '/login') }}"><i
                                            class="icon-f-76"></i>{{ __('webMessage.signin') }}</a></li>
                                <li><a href="{{ url(app()->getLocale() . '/register') }}"><i
                                            class="icon-f-94"></i>{{ __('webMessage.signup') }}</a></li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- /tt-account -->
        <!-- tt-langue and tt-currency -->
        <div class="tt-desctop-parent-multi tt-parent-box">
            <div class="tt-multi-obj tt-dropdown-obj">

                @if ($settingInfo->is_lang == 1)
                    @if (app()->getLocale() == 'ar')
                        <button
                            onclick="javascript:window.location.href='{{ url('en/' . substr(Request::getRequestUri(), 4, strlen(Request::getRequestUri()))) }}'"
                            class="languageButton"
                            data-tooltip="{{ __('webMessage.english') }}">{{ trans('webMessage.english') }}</button>

                    @else
                        <button
                            onclick="javascript:window.location.href='{{ url('ar/' . substr(Request::getRequestUri(), 4, strlen(Request::getRequestUri()))) }}'"
                            class="languageButton"
                            data-tooltip="{{ __('webMessage.arabic') }}">{{ trans('webMessage.arabic') }}</button>
                    @endif

                @endif







            </div>
        </div>
        <!-- /tt-langue and tt-currency -->
    </div>
    </div>
    </div>
    </div>
    <!-- stuck nav -->
    <div class="tt-stuck-nav" id="js-tt-stuck-nav">
        <div class="container">
            <div class="tt-header-row ">
                <!--<div class="tt-stuck-parent-logo">@if ($settingInfo->logo)
     <a class="tt-logo tt-logo-alignment" href="{{ url('/') }}"><img class="stuck_logo" src="{{ url('uploads/logo/' . $settingInfo->logo) }}" alt="@if (app()->getLocale() == 'en') {{ $settingInfo->name_en }} @else {{ $settingInfo->name_ar }} @endif" title="@if (app()->getLocale() == 'en') {{ $settingInfo->name_en }} @else {{ $settingInfo->name_ar }} @endif"></a>
                    @endif
                    </div>-->
                <div class="tt-stuck-parent-menu"></div>
                <div class="tt-stuck-parent-search tt-parent-box"></div>
                <div class="tt-stuck-parent-cart tt-parent-box"></div>
                <div class="tt-stuck-parent-account tt-parent-box"></div>
                <div class="tt-stuck-parent-multi tt-parent-box"></div>
            </div>
        </div>
    </div>
</header>
