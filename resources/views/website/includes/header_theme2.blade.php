@php
if(!empty(app()->getLocale())){ $strLang = app()->getLocale();}else{$strLang="en";}
@endphp
<!--theme2 start -->
<header id="tt-header">
@if($settingInfo['top_header_note_'.$strLang])
<div class="topheadernote">{!!$settingInfo['top_header_note_'.$strLang]!!}</div>
@endif

    @php
    $mobileMenusTrees = App\Categories::CategoriesTree();
	$mobilebrandMenus = App\Http\Controllers\webController::BrandsList();
    @endphp
    
	<!-- tt-mobile menu -->
	<nav class="panel-menu mobile-main-menu">
		<ul>
         <li><a href="{{url('/')}}">{{trans('webMessage.home')}}</a></li>
         @if(!empty($mobileMenusTrees))
        @each('website.includes.mobilemenu', $mobileMenusTrees, 'category', 'website.includes.nothing')
        @endif 
        @if(!empty($settingInfo->is_brand_active) && !empty($mobilebrandMenus) && count($mobilebrandMenus)>0)
        <li><a href="javascript:;">{{__('webMessage.brands')}}</a>
        <ul>
        @foreach($mobilebrandMenus as $brandMenu)
        <li>
        <a href="{{url('/brands/'.$brandMenu->slug)}}">@if(app()->getLocale()=="en") {{$brandMenu->title_en}} @else {{$brandMenu->title_ar}} @endif</a>
        @if(!empty($brandMenu->image) && !empty($settingInfo->is_brand_image_name) && $settingInfo->is_brand_image_name=='image')
        <img src="{{url('uploads/brand/thumb/'.$brandMenu->image)}}" style="max-width:40px;max-height:40px;float:right;margin-top:-40px;"/>
        @endif
        </li>
        @endforeach
        </ul>
        </li>
        @endif
         
         <li><a href="{{url('/offers')}}">{{__('webMessage.offers')}}</a></li>
         <li><a href="{{url('/page/about-us')}}">{{trans('webMessage.aboutus')}}</a></li>
         <li><a href="{{url('/contactus')}}">{{trans('webMessage.contactus')}}</a></li>
		@if ((new \App\bundleSetting())->is_active )
			<li><a
						href="{{ route('webBundle', [app()->getLocale()]) }}">{{ __('webMessage.bundles.Bundle') }}</a>
			</li>
		@endif
         @if($settingInfo->is_lang==1)
         @if(app()->getLocale()=='ar')
		 <li><a href="{{ url('locale/en') }}">English</a></li>
         @else
         <li class="arabic"><a href="{{ url('locale/ar') }}">العربية</a></li>
         @endif
         @endif
		</ul>
		<div class="mm-navbtn-names">
			<div class="mm-closebtn">{{__('webMessage.close')}}</div>
			<div class="mm-backbtn">{{__('webMessage.back')}}</div>
		</div>
	</nav>
    
	<!-- tt-mobile menu -->
	<!--<nav class="panel-menu mobile-caterorie-menu">
	<ul>
    <li><a href="{{url('/')}}">{{__('webMessage.home')}}</a></li>
    
									
	 </ul>
		<div class="mm-navbtn-names">
			<div class="mm-closebtn">{{__('webMessage.close')}}</div>
			<div class="mm-backbtn">{{__('webMessage.back')}}</div>
		</div>
	</nav>-->
	
	<!-- tt-mobile-header -->
	<div class="tt-mobile-header">
       <!--
		<div class="container-fluid">
                   
			        <div class="header-tel-info">
                        @if($settingInfo->phone) 
						<div class="tt-item"><i class="icon-h-35"></i>&nbsp;{{$settingInfo->phone}}</div>
                        @endif
                        @if(app()->getLocale()=="ar" && $settingInfo->office_hours_ar)
						<div class="tt-item"><i class="icon-h-31"></i>&nbsp;{{$settingInfo->office_hours_ar}}</div>
                        @elseif(app()->getLocale()=="en" && $settingInfo->office_hours_en)
                        <div class="tt-item"><i class="icon-h-31"></i>&nbsp;{{$settingInfo->office_hours_en}}</div>
                        @endif
					</div>
                    
		</div>
        -->
		<div class="container-fluid tt-top-line">
			<div class="tt-header-row">
				<div class="tt-mobile-parent-menu">
					<div class="tt-menu-toggle stylization-02" id="js-menu-toggle">
						<i class="icon-03"></i>
					</div>
				</div>
                <!--
				<div class="tt-mobile-parent-menu-categories tt-parent-box">
					<button class="tt-categories-toggle">
						<i class="icon-categories"></i>
					</button>
				</div>
                -->
				<!-- search -->
				<div class="tt-mobile-parent-search tt-parent-box"></div>
				<!-- /search -->
				<!-- cart -->
				<!--<div class="tt-mobile-parent-cart tt-parent-box"></div>-->
				<!-- /cart -->
				<!-- account -->
				<div class="tt-mobile-parent-account tt-parent-box"></div>
				<!-- /account -->
                <!-- language -->
                <div class="tt-mobile-parent-multi tt-parent-box"></div>
                <!-- / language -->
			</div>
		</div>
		<div class="container-fluid tt-top-line">
			<div class="row">
				<div class="tt-logo-container">
					<!-- mobile logo -->
					@if($settingInfo->logo)
					<a class="tt-logo tt-logo-alignment" href="{{url('/')}}"><img class="tt-retina" src="{{url('uploads/logo/'.$settingInfo->logo)}}" alt="@if(app()->getLocale()=="en") {{$settingInfo->name_en}} @else {{$settingInfo->name_ar}} @endif" title="@if(app()->getLocale()=="en") {{$settingInfo->name_en}} @else {{$settingInfo->name_ar}} @endif"></a>
                    @endif
					<!-- /mobile logo -->
				</div>
			</div>
		</div>
	</div>
	<!-- tt-desktop-header -->
	<div class="tt-desktop-header headerunderline">
		<div class="container">
			<div class="tt-header-holder">
				<div class="tt-col-obj tt-obj-logo">
					<!-- logo -->
					@if($settingInfo->logo)
					<a class="tt-logo tt-logo-alignment" href="{{url('/')}}"><img class="tt-retina" src="{{url('uploads/logo/'.$settingInfo->logo)}}" alt="@if(app()->getLocale()=="en") {{$settingInfo->name_en}} @else {{$settingInfo->name_ar}} @endif" title="@if(app()->getLocale()=="en") {{$settingInfo->name_en}} @else {{$settingInfo->name_ar}} @endif" style="max-width:200px;"></a>
                    @endif
					<!-- /logo -->
				</div>
				<div class="tt-col-obj tt-obj-search">
					<div class="tt-search-type2">
			 			<!-- tt-search -->
						<form name="topsearchform1" id="topsearchform1" method="get" action="{{url('/search')}}">
							<i class="icon-f-85"></i>
							<input type="text" class="tt-search-input" name="sq" id="search_keyname" placeholder="{{__('webMessage.searchproducts')}}" value="@if(Request()->sq){{Request()->sq}}@endif">
							<button type="submit" id="search_btns" class="tt-btn-search">{{__('webMessage.search')}}</button>
							<div class="search-results tt-is-include">
							<p><span id="search_child_results"></span></p>
							<button id="viewallsearchresult" type="button" class="tt-view-all">{{__('webMessage.viewallproducts')}}</button>
							</div>
						</form>
						<!-- /tt-search -->
					</div>
				</div>
                
                <!--            
				<div class="tt-col-obj obj-move-right">
					<div class="header-tel-info">
                        @if($settingInfo->phone) 
						<div class="tt-item"><i class="icon-h-35"></i>&nbsp;{{$settingInfo->phone}}</div>
                        @endif
                        @if(app()->getLocale()=="ar" && $settingInfo->office_hours_ar)
						<div class="tt-item"><i class="icon-h-31"></i>&nbsp;{{$settingInfo->office_hours_ar}}</div>
                        @elseif(app()->getLocale()=="en" && $settingInfo->office_hours_en)
                        <div class="tt-item"><i class="icon-h-31"></i>&nbsp;{{$settingInfo->office_hours_en}}</div>
                        @endif
					</div>
				</div>
                -->
			</div>
		</div>
    @php
    $desktopMenusTrees = App\Categories::CategoriesTree();
    $brandMenus = App\Http\Controllers\webController::BrandsList();
    @endphp
    
		<div class="container small-header">
			<div class="tt-header-holder">
				<div class="tt-col-obj tt-obj-menu-categories tt-desctop-parent-menu-categories">
					<div class="tt-menu-categories">
						<button class="tt-dropdown-toggle">{{__('webMessage.categories')}}</button>
						<div class="tt-dropdown-menu">
							<nav>
								<ul>
                                   @if(!empty($desktopMenusTrees) && count($desktopMenusTrees)>0)
                                   @php $t=0; @endphp
                                   @foreach($desktopMenusTrees as $desktopMenusTree)
                                   @if(!empty($desktopMenusTree->childs) && count($desktopMenusTree->childs)>0)
									<li>
                                        <a href="{{url('/products/'.$desktopMenusTree->id.'/'.$desktopMenusTree->friendly_url)}}">
                                        <span>@if(app()->getLocale()=="en") {{$desktopMenusTree->name_en}} @else {{$desktopMenusTree->name_ar}} @endif</span>
                                        </a>
										<div class="dropdown-menu @if(!empty($desktopMenusTree->is_full_width)) size-md @else size-lg @endif" style="top:{{$t}}px;">
									       <div class="dropdown-menu-wrapper" >
									       		<div class="row">
													<div class="col-12">
														<div class="row tt-col-list">
                                                         @foreach($desktopMenusTree->childs as $childCategory)
															<div class="col-sm-4">
                                                                <a href="{{url('/products/'.$childCategory->id.'/'.$childCategory->friendly_url)}}" class="tt-title-submenu">@if(app()->getLocale()=="en") {{$childCategory->name_en}} @else {{$childCategory->name_ar}} @endif </a>
																@if(!empty($childCategory->childs) && count($childCategory->childs)>0)
                                                            
															<ul class="tt-megamenu-submenu">
                                                                @foreach($childCategory->childs as $subchildCategory)
																<li><a href="{{url('/products/'.$subchildCategory->id.'/'.$subchildCategory->friendly_url)}}">@if(app()->getLocale()=="en") {{$subchildCategory->name_en}} @else {{$subchildCategory->name_ar}} @endif</a></li>
                                                                @endforeach
															</ul>
                                                            @endif 
															</div>
                                                          @endforeach  
														</div>
													</div>
												</div>
									       </div>
									    </div>
									</li>
									@else
                                    <li class="dropdown @if(!empty($desktopMenusTree->is_highlighted)) deals @endif"><a href="{{url('/products/'.$desktopMenusTree->id.'/'.$desktopMenusTree->friendly_url)}}">
                                    @if(app()->getLocale()=="en") {{$desktopMenusTree->name_en}} @else {{$desktopMenusTree->name_ar}} @endif</a></li>
                                    @endif
                                    @php $t+=40; @endphp
                                    @endforeach
									@endif
								</ul>
							</nav>
						</div>
					</div>
				</div>
				<div class="tt-col-obj tt-obj-menu">
					<!-- tt-menu -->
					<div class="tt-desctop-parent-menu tt-parent-box">
						<div class="tt-desctop-menu">
							<nav>
								<ul>
									<li class="dropdown megamenu selected"><a href="{{url('/')}}">{{trans('webMessage.home')}}</a></li>
									<li class="dropdown megamenu"><a href="{{url('/page/about-us')}}">{{trans('webMessage.aboutus')}}</a></li>
									<li class="dropdown tt-megamenu-col-01"><a href="{{url('/contactus')}}">{{trans('webMessage.contactus')}}</a></li>
                                     @if(!empty($settingInfo->is_brand_active) && !empty($brandMenus) && count($brandMenus)>0)
                                    <li class="dropdown megamenu">
                                    <a href="javascript:;">{{__('webMessage.brands')}}</a>
										<div class="dropdown-menu">
                                                 <div class="row tt-col-list">
												    @foreach($brandMenus as $brandMenu)
                                                    <div class="col-sm-2">
													<a href="{{url('/brands/'.$brandMenu->slug)}}">@if(!empty($brandMenu->image) && $settingInfo->is_brand_image_name=='image')
                                                    <img src="{{url('uploads/brand/thumb/'.$brandMenu->image)}}" style="max-width:100px;max-height:100px;"/>
                                                    @else
                                                    @if(app()->getLocale()=="en") {{$brandMenu->title_en}} @else {{$brandMenu->title_ar}} @endif
                                                    @endif</a></div>
                                                    @endforeach
													</div>  
                                         </div>
                                         </li>
                                    @endif
									
                                    <li class="dropdown"><a href="{{url('/offers')}}">{{__('webMessage.offers')}}</a></li>
                                    
								</ul>
							</nav>

						</div>
					</div>
					<!-- /tt-menu -->
				</div>
				<div class="tt-col-obj tt-obj-options obj-move-right">
					<!-- tt-search -->
					<div class="tt-desctop-parent-search tt-parent-box tt-obj-desktop-hidden">
						<div class="tt-search tt-dropdown-obj">
							<button class="tt-dropdown-toggle" data-tooltip="Search" data-tposition="bottom">
								<i class="icon-h-04"></i>
							</button>
							<div class="tt-dropdown-menu">
								<div class="container">
									  <form name="topsearchform2" id="topsearchform2" method="get" action="{{url('/search')}}">
										<div class="tt-col">
											<input type="text" class="tt-search-input" name="sq" id="search_keyname_pop" placeholder="{{__('webMessage.searchproducts')}}" value="@if(Request()->sq){{Request()->sq}}@endif">
											<button name="" id="search_btns_pop" class="tt-btn-search" type="submit"></button>
										</div>
										<div class="tt-col">
											<button name="close_btn" id="close_btn" value="{{__('webMessage.close')}}" class="tt-btn-close icon-g-80"></button>
										</div>
										<div class="tt-info-text">
											{{__('webMessage.whatareyoulookingfor')}}
										</div>
										<div class="search-results">
                                            <p>
											<span id="search_child_results_pop"></span>
                                            </p>
											<button id="viewallsearchresult_pop" type="button" class="tt-view-all">{{__('webMessage.viewallproducts')}}</button>
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
								<span class="tt-badge-cart"><span id="tt-badge-cart">{{$tempOrdersCount}}</span></span>
							</button>
							<div class="tt-dropdown-menu">
								<div class="tt-mobile-add">
									<h6 class="tt-title">{{__('webMessage.shoppingcart')}}</h6>
									<button class="tt-close">{{__('webMessage.close')}}</button>
								</div>
								<div class="tt-dropdown-inner">
									<div class="tt-cart-layout" id="TempOrderBoxDiv"> 
                                       @if(empty($tempOrders) || count($tempOrders)==0)
										<!-- layout emty cart -->
										<a href="javascript:;" class="tt-cart-empty">
											<i class="icon-f-39"></i>
											<p>{{__('webMessage.yourcartisempty')}}</p>
										</a>
                                       @else 
                                          
										<div class="tt-cart-content">
                                           
											<div class="tt-cart-list">
                                            @php 
                                            $subTotalAmount =0;
                                            $attrtxt='';$t=1;
                                            @endphp
                                            @foreach($tempOrders as $tempOrder)
                                            @php
                                            $prodDetails = App\Http\Controllers\webCartController::getProductDetails($tempOrder->product_id);
                                            if($prodDetails->image){
                                            $prodImage = url('uploads/product/thumb/'.$prodDetails->image);
                                            }else{
                                            $prodImage = url('uploads/no-image.png');
                                            }
                                            
                                            $subTotalAmount+=($tempOrder->quantity*$tempOrder->unit_price);
                                            if(!empty($tempOrder->size_id)){
                                            $sizeName = App\Http\Controllers\webCartController::sizeNameStatic($tempOrder->size_id,$strLang);
                                            $attrtxt .='<li>'.__('webMessage.size').': '.$sizeName.'</li>';
                                            }
                                            if(!empty($tempOrder->color_id)){
                                            $colorName = App\Http\Controllers\webCartController::colorNameStatic($tempOrder->color_id,$strLang);
                                            $attrtxt .='<li>'.__('webMessage.color').': '.$colorName.'</li>';
                                            $colorImageDetails = App\Http\Controllers\webCartController::getColorImage($tempOrder->product_id,$tempOrder->color_id);
                                            if(!empty($colorImageDetails->color_image)){
                                            $prodImage = url('uploads/color/thumb/'.$colorImageDetails->color_image);
                                            }
                                            }
											$optionsDetailstxt = App\Http\Controllers\webCartController::getOptionsDtails($tempOrder->id);
											
                                            @endphp
												<div class="tt-item" @if($t>3)style="display:none;"@endif >
													<a href="{{url('directdetails/'.$prodDetails->id.'/'.$prodDetails->slug)}}">
														<div class="tt-item-img">
															<img src="{{url('assets/images/loader.svg')}}" data-src="{{$prodImage}}" alt="@if(app()->getLocale()=='en') {{$prodDetails->title_en}} @else {{$prodDetails->title_ar}} @endif">
														</div>
														<div class="tt-item-descriptions">
															<h2 class="tt-title">@if(app()->getLocale()=="en") {{$prodDetails->title_en}} @else {{$prodDetails->title_ar}} @endif</h2>
															<ul class="tt-add-info">
																{!!$attrtxt!!}
																{!!$optionsDetailstxt!!}
															</ul>
															<div class="tt-quantity">{{$tempOrder->quantity}} X</div> <div class="tt-price">{{$tempOrder->unit_price}} {{__('webMessage.kd')}} </div>
														</div>
													</a>
													<div class="tt-item-close">
														<a href="javascript:;" id="{{$tempOrder->id}}" class="tt-btn-close deleteFromTemp"></a>
													</div>
												</div>
                                            @php $attrtxt='';$t++;@endphp    
											@endforeach	
											
											@if($t>3)
											<div class="tt-item" align="center"><a href="{{url('/cart')}}">{{trans('webMessage.viewall')}}(+{{$t-4}})</a></div>
											@endif
											</div>
											<div class="tt-cart-total-row">
												@php
													$bundleDiscount =  App\Http\Controllers\webCartController::loadTempOrdersBundleDiscount($tempOrders);
                                                    $subTotalAmount = $subTotalAmount - $bundleDiscount;
												@endphp
												@if ( $bundleDiscount > 0 )
													<div class="tt-cart-total-title">{{ __('webMessage.bundles.BundleDiscount') }}:
													</div>
													<div class="tt-cart-total-price" style="color: #FF0000;"> {{ round($bundleDiscount, 3) }}
														{{ __('webMessage.kd') }}</div>
											</div><div class="tt-cart-total-row" style="margin-top: 0px;padding-top: 10px;border-top: 0px;">
												@endif
												<div class="tt-cart-total-title">{{__('webMessage.subtotal')}}:</div>
												<div class="tt-cart-total-price"> {{round($subTotalAmount,3)}} {{__('webMessage.kd')}}</div>
											</div>
											<div class="tt-cart-btn">
												<div class="tt-item">
													<a href="{{url('/checkout')}}" class="btn">{{__('webMessage.checkout')}}</a>
												</div>
												<div class="tt-item">
													<a href="{{url('/cart')}}" class="btn-link-02 tt-hidden-mobile">{{__('webMessage.viewcart')}}</a>
													<a href="{{url('/cart')}}" class="btn btn-border tt-hidden-desctope">{{__('webMessage.viewcart')}}</a>
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
							<button class="tt-dropdown-toggle"  data-tooltip="{{__('webMessage.myaccount')}}" data-tposition="bottom"><i class="icon-f-94"></i></button>
							<div class="tt-dropdown-menu">
								<div class="tt-mobile-add">
									<button class="tt-close">{{__('webMessage.close')}}</button>
								</div>
								<div class="tt-dropdown-inner">
									<ul>
									   
                                        @if(!empty(Auth::guard('webs')->user()->id))
                                        <li><a href="{{url('/account')}}"><i class="icon-f-94"></i>{{__('webMessage.dashboard')}}</a></li>
									    <li><a href="{{url('/changepass')}}"><i class="icon-g-40"></i>{{__('webMessage.changepassword')}}</a></li>
									    <li><a href="{{url('/editprofile')}}"><i class="icon-01"></i>{{__('webMessage.editprofile')}}</a></li>
                                        <li><a href="{{url('/myorders')}}"><i class="icon-f-68"></i>{{__('webMessage.myorders')}}</a></li>
                                        <li><a href="{{url('/wishlist')}}"><i class="icon-n-072"></i>{{__('webMessage.wishlists')}}</a></li>
                                        <li><a href="javascript:void(0);" onclick="event.preventDefault();document.getElementById('logout-forms').submit();" target="_blank"><i class="icon-f-77"></i>{{__('webMessage.signout')}}</a></li>
                                        <form id="logout-forms" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        </form>
                                        @else
									    <li><a href="{{url('/login')}}"><i class="icon-f-76"></i>{{__('webMessage.signin')}}</a></li>
									    <li><a href="{{url('/register')}}"><i class="icon-f-94"></i>{{__('webMessage.signup')}}</a></li>
                                        @endif
									</ul>
								</div>
							</div>
						</div>
					</div>
					<!-- /tt-account -->
                    <div class="tt-desctop-parent-multi tt-parent-box">
						<div class="tt-multi-obj tt-dropdown-obj">
                            @if($settingInfo->is_lang==1)
                             @if(app()->getLocale()=='ar')
							<button onclick="javascript:window.location.href='{{ url('locale/en') }}'" class="languageButton" data-tooltip="{{__('webMessage.english')}}">E</button>
                            @else
                            <button onclick="javascript:window.location.href='{{ url('locale/ar') }}'" class="languageButton" data-tooltip="{{__('webMessage.arabic')}}">ع</button>
                            @endif
                            @endif
						</div>
					</div>
                    
                    
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
				<div class="tt-stuck-parent-search tt-parent-box"></div>
				<div class="tt-stuck-parent-cart tt-parent-box mobilehide"></div>
				<div class="tt-stuck-parent-account tt-parent-box"></div>
                <div class="tt-stuck-parent-multi tt-parent-box"></div>
			</div>
		</div>
	</div>
</header>