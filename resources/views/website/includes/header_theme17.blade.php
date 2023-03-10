@php
$desktopMenusTrees = App\Categories::CategoriesTree();
$brandMenus = App\Http\Controllers\webController::BrandsList();
$mobileMenusTrees = App\Categories::CategoriesTree();
$mobilebrandMenus = App\Http\Controllers\webController::BrandsList();
$shopcategoriesLists = App\Http\Controllers\webController::getProductCategories(0);
@endphp
<header id="tt-header">


    <!-- tt-mobile menu -->
    <nav class="panel-menu mobile-caterorie-menu">
        <ul>
            <li><a href="{{ url('/') }}">{{ trans('webMessage.home') }}</a></li>
            @if (!empty($mobileMenusTrees))
                @each('website.includes.mobilemenu', $mobileMenusTrees, 'category', 'website.includes.nothing')
            @endif
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
                        @if ((new \App\bundleSetting())->is_active)
                            <li class="dropdown tt-megamenu-col-02"><a
                                    href="{{ route('webBundle', [app()->getLocale()]) }}">{{ __('webMessage.bundles.Bundle') }}</a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif

            <li><a href="{{ url(app()->getLocale() . '/offers') }}">{{ __('webMessage.offers') }}</a></li>
            <li><a href="{{ url(app()->getLocale() . '/page/about-us') }}">{{ trans('webMessage.aboutus') }}</a>
            </li>
            <li><a href="{{ url(app()->getLocale() . '/contactus') }}">{{ trans('webMessage.contactus') }}</a>
            </li>
            @if ((new \App\bundleSetting())->is_active)
                <li><a
                        href="{{ route('webBundle', [app()->getLocale()]) }}">{{ __('webMessage.bundles.Bundle') }}</a>
                </li>
            @endif
            {{-- @if ($settingInfo->is_lang == 1)
                @if (app()->getLocale() == 'ar')
                    <li><a href="{{ url('en/' . substr(Request::path(), 3, strlen(Request::path()))) }}">English</a>
                    </li>
                @else
                    <li class="arabic"><a
                            href="{{ url('ar/' . substr(Request::path(), 3, strlen(Request::path()))) }}">العربية</a>
                    </li>
                @endif
            @endif --}}
        </ul>
        <div class="mm-navbtn-names">
            <div class="mm-closebtn">{{ __('webMessage.close') }}</div>
            <div class="mm-backbtn">{{ __('webMessage.back') }}</div>
        </div>
    </nav>
    <!-- tt-mobile-header -->



    <div class="tt-mobile-header">
        <div class="container-fluid tt-top-line">
            <div class="tt-header-row">
                <div class="tt-mobile-parent-menu-categories tt-parent-box">
                    <button class="tt-categories-toggle">
                        <img src="{{ url('assets/images/icon-svg/menu.svg') }}" alt="" class="desk_sidemenu">
                    </button>
                </div>
                <div class="tt-logo-container">
                    <!-- mobile logo -->
                    <a class="tt-logo tt-logo-alignment" href="{{ url('/') }}"><img
                            src="{{ url('uploads/logo/' . $settingInfo->logo) }}" alt=""></a>
                    <!-- /mobile logo -->
                </div>

                <!-- search -->
                <div class="tt-mobile-parent-search tt-parent-box"></div>
                <!-- /search -->
                <!-- cart -->
                <!-- <div class="tt-mobile-parent-cart tt-parent-box"></div> -->
                <!-- /cart -->
                <!-- account -->
                <div class="tt-mobile-parent-account tt-parent-box"></div>
                <!-- /account -->
            </div>
        </div>
    </div>


    <!-- tt-desktop-header -->
    <div class="tt-desktop-header headerunderline">
        <div class="container small-header">
            <div class="tt-header-holder">
                <div class="tt-col-obj tt-obj-menu-categories tt-desctop-parent-menu-categories">
                    <div class="tt-menu-categories">
                        <button class="tt-dropdown-toggle">
                            <img src="{{ url('assets/images/icon-svg/menu.svg') }}" alt="" class="desk_sidemenu">
                        </button>
                        <div class="tt-dropdown-menu">
                            <nav>
                                <ul>
                                    @if (!empty($desktopMenusTrees) && count($desktopMenusTrees) > 0)
                                        @foreach ($desktopMenusTrees as $desktopMenusTree)
                                            <li>
                                                <a
                                                    href="{{ url(app()->getLocale() . '/products/' . $desktopMenusTree->id . '/' . $desktopMenusTree->friendly_url) }}">
                                                    <span>{{ app()->getLocale() == 'en' ? $desktopMenusTree->name_en : $desktopMenusTree->name_ar }}</span>
                                                </a>
                                                @if (!empty($desktopMenusTree->childs) && count($desktopMenusTree->childs) > 0)
                                                    <div class="dropdown-menu size-md">
                                                        <div class="dropdown-menu-wrapper">
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <div class="row tt-col-list">
                                                                        @foreach ($desktopMenusTree->childs as $childCategory)
                                                                            <div class="col-sm-4">
                                                                                <a class="tt-title-submenu"
                                                                                    href="{{ url(app()->getLocale() . '/products/' . $childCategory->id . '/' . $childCategory->friendly_url) }}">
                                                                                    {{ app()->getLocale() == 'en' ? $childCategory->name_en : $childCategory->name_ar }}
                                                                                </a>

                                                                                @if (!empty($childCategory->childs) && count($childCategory->childs) > 0)
                                                                                    <ul
                                                                                        class="tt-megamenu-submenu mb-2">
                                                                                        @foreach ($childCategory->childs as $subchildCategory)
                                                                                            <li><a
                                                                                                    href="{{ url(app()->getLocale() . '/products/' . $subchildCategory->id . '/' . $subchildCategory->friendly_url) }}">
                                                                                                    {{ app()->getLocale() == 'en' ? $subchildCategory->name_en : $subchildCategory->name_ar }}
                                                                                                </a>
                                                                                            </li>
                                                                                        @endforeach
                                                                                    </ul>
                                                                                @endif
                                                                                {{-- <li>
																				<a href="#">Tops &amp; T-shirts</a>
																				<ul>
																					<li><a href="#">Link Level 1</a>
																					</li>
																					<li>
																						<a href="#">Link Level 1</a>
																						<ul>
																							<li><a href="#">Link
																									Level
																									2</a>
																							</li>
																							<li>
																								<a href="#">Link
																									Level
																									2</a>
																								<ul>
																									<li><a href="#">Link
																											Level
																											3</a>
																									</li>
																									<li><a href="#">Link
																											Level
																											3</a>
																									</li>
																									<li><a href="#">Link
																											Level
																											3</a>
																									</li>
																									<li>
																										<a href="#">Link
																											Level
																											3</a>
																										<ul>
																											<li>
																												<a
																													href="#">Link
																													Level
																													4</a>
																												<ul>
																													<li><a
																															href="#">Link
																															Level
																															5</a>
																													</li>
																													<li><a
																															href="#">Link
																															Level
																															5</a>
																													</li>
																													<li><a
																															href="#">Link
																															Level
																															5</a>
																													</li>
																													<li><a
																															href="#">Link
																															Level
																															5</a>
																													</li>
																													<li><a
																															href="#">Link
																															Level
																															5</a>
																													</li>
																												</ul>
																											</li>
																											<li><a
																													href="#">Link
																													Level
																													4</a>
																											</li>
																										</ul>
																									</li>
																									<li><a href="#">Link
																											Level
																											3</a>
																									</li>
																								</ul>
																							</li>
																							<li><a href="#">Link
																									Level
																									2</a>
																							</li>
																							<li><a href="#">Link
																									Level
																									2</a>
																							</li>
																						</ul>
																					</li>
																					<li><a href="#">Link Level 1</a>
																					</li>
																					<li><a href="#">Link Level 1</a>
																					</li>
																					<li><a href="#">Link Level 1</a>
																					</li>
																				</ul>
																			</li> --}}
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </li>

                                        @endforeach
                                        @if ((new \App\bundleSetting())->is_active)
                                            <li class="dropdown tt-megamenu-col-02"><a
                                                    href="{{ route('webBundle', [app()->getLocale()]) }}">{{ __('webMessage.bundles.Bundle') }}</a>
                                            </li>
                                        @endif
                                </ul>

                                @endif
                            </nav>
                        </div>
                    </div>
                </div>
                <div class="tt-col-obj tt-obj-menu">
                    <!-- tt-menu -->
                    <div class="tt-desctop-parent-menu tt-parent-box">
                        <div class="tt-desctop-menu">
                            <a class="tt-logo tt-logo-alignment" href="{{ url('/' . app()->getLocale()) }}"><img
                                    src="{{ url('uploads/logo/' . $settingInfo->logo) }}" alt=""></a>
                        </div>
                    </div>
                    <!-- /tt-menu -->
                </div>
                <div class="tt-col-obj tt-obj-options obj-move-right">


                    <!-- tt-search -->
                    <div class="tt-desctop-parent-search tt-parent-box">
                        <div class="tt-search tt-dropdown-obj">
                            <button class="tt-dropdown-toggle" data-tooltip="Search" data-tposition="bottom">
                                <i class="icon-h-04"></i>
                            </button>
                            <div class="tt-dropdown-menu">
                                <div class="container">
                                    <form name="topsearchform1" id="topsearchform1" method="get"
                                        action="{{ url(app()->getLocale() . '/search') }}">
                                        <div class="tt-col">
                                            <input type="text" class="tt-search-input" name="sq" id="search_keyname"
                                                placeholder="{{ __('webMessage.searchproducts') }}"
                                                value="@if (Request()->sq){{ Request()->sq }}@endif">
                                            <button name="" id="search_btns" class="tt-btn-search"
                                                type="submit"></button>
                                        </div>
                                        <div class="tt-col">
                                            <button name="close_btn" id="close_btn"
                                                value="{{ __('webMessage.close') }}"
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
                                <img src="{{ url('assets/images/icon-svg/bag.svg') }}" alt="" class="search_ico">
                                <span class="tt-badge-cart"><span
                                        id="tt-badge-cart">{{ $tempOrdersCount }}</span></span>
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
                                                                href="{{ url(app()->getlocale() . '/cart') }}">{{ trans('webMessage.viewall') }}(+{{ $t - 4 }})</a>
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
                                            <a href="{{ url(app()->getlocale() . '/checkout') }}"
                                                class="btn">{{ __('webMessage.checkout') }}</a>
                                        </div>
                                        <div class="tt-item">
                                            <a href="{{ url(app()->getlocale() . '/cart') }}"
                                                class="btn-link-02 tt-hidden-mobile">{{ __('webMessage.viewcart') }}</a>
                                            <a href="{{ url(app()->getlocale() . '/cart') }}"
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
                        data-tposition="bottom"><img src="{{ url('assets/images/icon-svg/user.svg') }}" alt=""
                            class="search_ico"></button>


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
                                                class="icon-g-40"></i>{{ __('webMessage.changepassword') }}</a>
                                    </li>
                                    <li><a href="{{ url(app()->getLocale() . '/editprofile') }}"><i
                                                class="icon-01"></i>{{ __('webMessage.editprofile') }}</a>
                                    </li>
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
        </div>
    </div>
    </div>
    </div>
    <!-- /tt-desktop-header -->

    <!-- stuck nav -->
    <div class="tt-stuck-nav" id="js-tt-stuck-nav">
        <div class="container">
            <div class="tt-header-row ">
                <div class="tt-stuck-desctop-menu-categories"></div>
                <div class="tt-stuck-parent-menu"></div>
                <div class="tt-stuck-mobile-menu-categories"></div>
                <div class="tt-logo-container desk_hide">
                    <!-- mobile logo -->
                    <a class="tt-logo tt-logo-alignment" href="{{ url('/') }}"><img
                            src="{{ url('uploads/logo/' . $settingInfo->logo) }}" alt="" class="loading"
                            data-was-processed="true"></a>
                    <!-- /mobile logo -->
                </div>
                <div class="tt-stuck-parent-search tt-parent-box"></div>
                <div class="tt-stuck-parent-cart tt-parent-box mob_hide"></div>
                <div class="tt-stuck-parent-account tt-parent-box"></div>
            </div>
        </div>
    </div>
</header>


<div class="container {{ substr(Request::path(), 2) == '/categories' ? 'mob_hide' : '' }}">
    <div class="tt-header-holder ">
        <div class="tt-obj-menu obj-aligment-center">
            <!-- tt-menu -->
            <div class="tt-parent-box " id="tt-desctop-parent-menu__icon">
                <div class="tt-desctop-menu my_padding" id="tt-desctop-menu__icon">
                    <nav>
                        <ul>
                            <li class="dropdown">
                                <a href="{{ url(app()->getLocale() . '/product-tag/baby0-3') }}">
                                    <span class="tt-icon">
                                        <img src="{{ url('assets/images/hakumNewAssets/baby.png') }}" alt="">
                                    </span>
                                    <span class="tt-text">Baby</span>
                                </a>
                                {{-- <div class="dropdown-menu">
                                    <div class="row my_scroll">
                                        <div class="col-sm-12">
                                            <div class="row tt-col-list my_category">
                                                <div class="col-4">
                                                    <a href="{{url(app()->getLocale().'/product-tag/baby0-5')}}" class="tt-title-submenu">
                                                        <img src="{{ url('assets/images/icon-svg/0-5.svg') }}" alt="">
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> --}}
                            </li>
                            <li class="dropdown">
                                <a href="#">
                                    <span class="tt-icon">
                                        <img src="{{ url('assets/images/hakumNewAssets/girl.png') }}" alt="">
                                    </span>
                                    <span class="tt-text">Girls</span>
                                </a>
                                <div class="dropdown-menu">
                                    <div class="row my_scroll">
                                        <div class="col-sm-12 ">
                                            <div class="row tt-col-list my_category">
                                                <div class="col-4">
                                                    <a href="{{ url(app()->getLocale() . '/product-tag/girls5+') }}"
                                                        class="tt-title-submenu">
                                                        <img src="{{ url('assets/images/icon-svg/5.svg') }}" alt="">
                                                    </a>
                                                </div>
                                                <div class="col-4">
                                                    <a href="{{ url(app()->getLocale() . '/product-tag/girls8+') }}"
                                                        class="tt-title-submenu">
                                                        <img src="{{ url('assets/images/icon-svg/8.svg') }}" alt="">
                                                    </a>
                                                </div>
                                                <div class="col-4">
                                                    <a href="{{ url(app()->getLocale() . '/product-tag/girls10+') }}"
                                                        class="tt-title-submenu">
                                                        <img src="{{ url('assets/images/icon-svg/10.svg') }}"
                                                            alt="">
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="dropdown">
                                <a href="#">
                                    <span class="tt-icon">
                                        <img src="{{ url('assets/images/hakumNewAssets/boys.png') }}" alt="">
                                    </span>
                                    <span class="tt-text">Boys</span>
                                </a>
                                <div class="dropdown-menu">
                                    <div class="row my_scroll">
                                        <div class="col-sm-12">
                                            <div class="row tt-col-list my_category">
                                                <div class="col-4">
                                                    <a href="{{ url(app()->getLocale() . '/product-tag/boys5+') }}"
                                                        class="tt-title-submenu">
                                                        <img src="{{ url('assets/images/icon-svg/5.svg') }}" alt="">
                                                    </a>
                                                </div>
                                                <div class="col-4">
                                                    <a href="{{ url(app()->getLocale() . '/product-tag/boys8+') }}"
                                                        class="tt-title-submenu">
                                                        <img src="{{ url('assets/images/icon-svg/8.svg') }}" alt="">
                                                    </a>
                                                </div>
                                                <div class="col-4">
                                                    <a href="{{ url(app()->getLocale() . '/product-tag/boys10+') }}"
                                                        class="tt-title-submenu">
                                                        <img src="{{ url('assets/images/icon-svg/10.svg') }}"
                                                            alt="">
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="dropdown">
                                <a href="{{ url(app()->getLocale() . '/product-tag/adults14+') }}">
                                    <span class="tt-icon">
                                        <img src="{{ url('assets/images//hakumNewAssets/adults.png') }}" alt="">
                                    </span>
                                    <span class="tt-text">Adults</span>
                                </a>
                                {{-- <div class="dropdown-menu">
                                    <div class="row my_scroll">
                                        <div class="col-sm-12">
                                            <div class="row tt-col-list my_category">
                                                <div class="col-4">
                                                    <a href="{{url(app()->getLocale().'/product-tag/adults14+')}}" class="tt-title-submenu">
                                                        <img src="{{ url('assets/images/icon-svg/14.svg') }}" alt="">
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> --}}
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
            <!-- /tt-menu -->
        </div>
    </div>
</div>

@if (substr(Request::path(), 2) == '/categories')
    <div class="container">
        <div class="tt-btn-img-list">
            <div class="row mb-3">
                @foreach ($shopcategoriesLists as $key => $shopcategoriesList)
                    @php
                        if ($shopcategoriesList->cimage) {
                            $imagecats = url('uploads/category/' . $shopcategoriesList->cimage);
                        } else {
                            $imagecats = url('uploads/category/no-image.png');
                        }
                        $randomColorShade = rand(1, 6);
                    @endphp
                    <div class="col-6 col-sm-4 col-lg-2">
                        <a href="{{ url(app()->getLocale() . '/products/' . $shopcategoriesList->cid . '/' . $shopcategoriesList->friendly_url) }}"
                            class="tt-btn-info tt-layout-03 tt-btn-info-color0{{  $key <= 6 ? $key + 1 : rand($randomColorShade-1, $randomColorShade) }}">
                            <div class="tt-title"><img src="{{ $imagecats }}" alt=""
                                    height="110"><br />{{ app()->getLocale() == 'en' ? $shopcategoriesList->name_en : $shopcategoriesList->name_ar }}
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@else
    <div class="container-indent {{ substr(Request::path(), 2) !== '/categories' ? 'mob_hide' : '' }} ">
        <div class="container container-fluid-custom-mobile-padding">
            <div class="tt-carousel-products row arrow-location-tab arrow-location-tab01 tt-alignment-img tt-collection-listing slick-animated-show-js mt-0"
                data-item="6">
                @foreach ($shopcategoriesLists as $key => $shopcategoriesList)
                    @php
                        if ($shopcategoriesList->cimage) {
                            $imagecats = url('uploads/category/' . $shopcategoriesList->cimage);
                        } else {
                            $imagecats = url('uploads/category/no-image.png');
                        }
                        $randomColorShade = rand(1, 6);
                    @endphp
                    <div class="col-6 col-sm-4 col-lg-2">
                        <a href="{{ url(app()->getLocale() . '/products/' . $shopcategoriesList->cid . '/' . $shopcategoriesList->friendly_url) }}"
                            class="tt-btn-info tt-layout-03 tt-btn-info-color0{{ $key <= 6 ? $key + 1 : $randomColorShade }}">
                            <div class="tt-title"><img src="{{ $imagecats }}" alt=""
                                    height="110"><br />{{ app()->getLocale() == 'en' ? $shopcategoriesList->name_en : $shopcategoriesList->name_ar }}
                            </div>
                        </a>
                    </div>
                    @if (substr(Request::path(), 2) == '/categories')
                        <div class="col-6 col-sm-4 col-lg-2">
                            <a href="{{ url(app()->getLocale() . '/products/' . $shopcategoriesList->cid . '/' . $shopcategoriesList->friendly_url) }}"
                                class="tt-btn-info tt-layout-03 tt-btn-info-color0{{ $key <= 6 ? $key + 1 : $randomColorShade }}">
                                <div class="tt-title"><img src="{{ $imagecats }}" alt=""
                                        height="110"><br />{{ app()->getLocale() == 'en' ? $shopcategoriesList->name_en : $shopcategoriesList->name_ar }}
                                </div>
                            </a>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
@endif
