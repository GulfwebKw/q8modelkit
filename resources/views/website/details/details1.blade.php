@php
$settingInfo = App\Http\Controllers\webController::settings();
if(!empty(app()->getLocale())){ 
$strLang = app()->getLocale();
}else{
$strLang="en";
}

$oalign = $strLang=='en'?'right':'left';

if(!empty($productDetails['seo_description_'.$strLang])){
$seo_description = $productDetails['seo_description_'.$strLang];
}else{
$seo_description = $settingInfo['seo_description_'.$strLang];
}
if(!empty($productDetails['seo_keywords_'.$strLang])){
$seo_keywords = $productDetails['seo_keywords_'.$strLang];
}else{
$seo_keywords = $settingInfo['seo_keywords_'.$strLang];
}
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>
@if(app()->getLocale()=="en" && !empty($settingInfo->name_en)) 
{{$settingInfo->name_en}} 
@elseif(app()->getLocale()=="ar" && !empty($settingInfo->name_ar))
{{$settingInfo->name_ar}} 
@endif | @if(app()->getLocale()=="en" && !empty($productDetails->title_en)) {{$productDetails->title_en}} @elseif(app()->getLocale()=="ar" && !empty($productDetails->title_ar)) {{$productDetails->title_ar}} @endif</title>
<meta name="description" content="{{$seo_description}}" />
<meta name="abstract" content="{{$seo_description}}">
<meta name="keywords" content="{{$seo_keywords}}" />
<meta name="Copyright" content="@if(!empty($settingInfo->name_en)){{$settingInfo->name_en}}@endif, Kuwait Copyright 2020 - {{date('Y')}}" />
<META NAME="Geography" CONTENT="@if(app()->getLocale()=="en" && !empty($settingInfo->address_en)) {{$settingInfo->address_en}} @elseif(app()->getLocale()=="ar" && !empty($settingInfo->address_ar)) {{$settingInfo->address_ar}} @endif">
@if(!empty($settingInfo->extra_meta_tags)) {!!$settingInfo->extra_meta_tags!!} @endif
@if($settingInfo->favicon)
<link rel="icon" href="{{url('uploads/logo/'.$settingInfo->favicon)}}">
@endif
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    @include("website.includes.css")
    <!--facebook meta tags -->
    @if(!empty($settingInfo->og_title))
    <meta property="og:title" content="@if(!empty($productDetails['title_'.$strLang])){{$productDetails['title_'.$strLang]}}@endif">
    @endif
    @if(!empty($settingInfo->og_description))
    <meta property="og:description" content="@if(!empty($productDetails['details_'.$strLang])){!!$productDetails['title_'.$strLang]!!}@endif">
    @endif
    @if(!empty($settingInfo->og_url))
    <meta property="og:url" content="{{url(app()->getLocale().'/directdetails/'.$productDetails->id.'/'.$productDetails->slug)}}">
    @endif
    @if(!empty($settingInfo->og_image))
    <meta property="og:image" content="{{url('uploads/product/'.$productDetails->image)}}">
    @if(!empty($prodGalleries))
    @foreach($prodGalleries as $gallery)
    <meta property="og:image" content="{{url('uploads/product/'.$gallery->image)}}">
    @endforeach
    @endif
    @endif
                                    
    @if(!empty($settingInfo->og_brand))
    @if(!empty($brandDetails['title_'.$strLang]))
    <meta property="product:brand" content="{{$brandDetails['title_'.$strLang]}}">
    @endif
    @endif
    @if(!empty($settingInfo->og_availability))
    @if($availableQty>0)
    <meta property="product:availability" content="in stock">
    @endif
    @endif
    @if(!empty($settingInfo->og_condition))
    <meta property="product:condition" content="new">
    @endif
    @if(!empty($settingInfo->og_amount))
    @if(!empty($productDetails->countdown_datetime) && strtotime($productDetails->countdown_datetime)>strtotime(date('Y-m-d')))
    <meta property="product:price:amount" content="{{round($productDetails->countdown_price,3)}}">
    <meta property="product:sale_price_dates:start" content="{{date('Y-m-d')}}">
    <meta property="product:sale_price_dates:end" content="{{$productDetails->countdown_datetime}}">
    @else
    <meta property="product:price:amount" content="{{round($productDetails->retail_price,3)}}">
    @endif
    @endif
    @if(!empty($settingInfo->og_currency))
    <meta property="product:price:currency" content="KWD">
    @endif
    @if(!empty($settingInfo->og_retailer_item_id))
    <meta property="product:retailer_item_id" content="{{$productDetails->item_code}}">
    @endif
    @if(!empty($settingInfo->og_title))
    <meta property="product:item_group_id" content="{{$productDetails->item_code}}">
    @endif
    <!--end FB tags -->
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <style>.g-recaptcha {transform:scale(0.90);transform-origin:0 0;}</style>
   @php 
   if(!empty($productDetails->countdown_datetime) && strtotime($productDetails->countdown_datetime)>strtotime(date('Y-m-d'))){
   $gprice = round($productDetails->countdown_price,3);
   }else{
   $gprice = round($productDetails->retail_price,3);
   }
   @endphp
    
   
</head>
<body>
<!--preloader -->
@include("website.includes.preloader")
<!--end preloader -->
<!--header -->
@include("website.includes.header")
<!--end header -->
@php
$catTreeName =App\Http\Controllers\webController::getCatTreeNameByPid($productDetails->id);
$checkBrandDiscount = App\Http\Controllers\webController::checkBrandDiscount($productDetails->brand_id);
$brandDiscountedPrice = App\Http\Controllers\webController::calByBrandDiscount($productDetails->brand_id, $productDetails->retail_price);
@endphp
<div class="tt-breadcrumb">
	<div class="container">
		<ul itemscope itemtype="https://schema.org/BreadcrumbList">
			<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
            <a itemprop="item" href="{{url(app()->getLocale().'/')}}"><span itemprop="name">{{__('webMessage.home')}}</span></a>
            <meta itemprop="position" content="1" />
            </li>
            {!!$catTreeName!!}
			<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a href="javascript:;"><span itemprop="name">@if(app()->getLocale()=="en") {{$productDetails->title_en}} @else {{$productDetails->title_ar}} @endif</span></a><meta itemprop="position" content="4" /></li>
		</ul>
	</div>
</div>
<div id="tt-pageContent" itemtype="http://schema.org/Product" itemscope>
<meta itemprop="mpn" content="{{$productDetails->item_code}}" />
<meta itemprop="name" content="@if(app()->getLocale()=="en" && !empty($productDetails->title_en)) {{$productDetails->title_en}} @elseif(app()->getLocale()=="ar" && !empty($productDetails->title_ar)) {{$productDetails->title_ar}} @endif" />

<!-- product details -->
<div class="container-indent">
		<!-- mobile product slider  -->
		<div class="tt-mobile-product-slider visible-xs arrow-location-center slick-animated-show-js">
            @if($productDetails->image)
			<div><img id="displaym-{{$productDetails->id}}" src="{{url('uploads/product/'.$productDetails->image)}}" alt=""><link itemprop="image" href="{{url('uploads/product/'.$productDetails->image)}}" /></div>
            @else
            <div><img id="displaym-{{$productDetails->id}}" src="{{url('uploads/no-image.png')}}" alt=""></div>
            @endif
            @if(!empty($prodGalleries))
            @foreach($prodGalleries as $gallery)
			<div><img src="{{url('uploads/product/'.$gallery->image)}}" alt=""><link itemprop="image" href="{{url('uploads/product/'.$gallery->image)}}" /></div>
            @endforeach
            @endif
			@if($productDetails->youtube_url_id)
			<div>
				<div class="embed-responsive embed-responsive-16by9">
					<iframe class="embed-responsive-item" src="https://www.youtube.com/embed/{{$productDetails->youtube_url_id}}" allowfullscreen></iframe>
				</div>
			</div>
            @endif
		</div>
        
		<!-- /mobile product slider  -->
		<div class="container container-fluid-mobile">
			<div class="row">
				<div class="col-6 hidden-xs">
					<div class="tt-product-vertical-layout">
						<div class="tt-product-single-img">
                         @if($productDetails->is_active=='2')
                         <span class="tt-label-location-float">
						 <span class="tt-label-new">{{__('webMessage.preorder')}}</span>
						 </span>
                         @endif
							 <div>
                                @if($productDetails->image)
								<img id="displayd-{{$productDetails->id}}"   class="zoom-product" src="{{url('uploads/product/'.$productDetails->image)}}" data-zoom-image="{{url('uploads/product/'.$productDetails->image)}}" alt="">
                                @else
                                <img id="displayd-{{$productDetails->id}}"   class="zoom-product" src="{{url('uploads/no-image.png')}}" data-zoom-image="{{url('uploads/no-image.png')}}" alt="">
                                @endif
								<button class="tt-btn-zomm tt-top-right"><i class="icon-f-86"></i></button>
							</div>
						</div>
						<div class="tt-product-single-carousel-vertical">
							<ul id="smallGallery" class="tt-slick-button-vertical slick-animated-show-js">
                                @if($productDetails->image)
								<li><a id="displaya-{{$productDetails->id}}" class="zoomGalleryActive" href="javascript:;" data-image="{{url('uploads/product/'.$productDetails->image)}}" data-zoom-image="{{url('uploads/product/'.$productDetails->image)}}"><img src="{{url('uploads/product/thumb/'.$productDetails->image)}}" alt="" id="displayt-{{$productDetails->id}}"></a></li>
                                @endif
                                @if(!empty($prodGalleries))
                                @foreach($prodGalleries as $gallery)
                                <li><a href="javascript:;" data-image="{{url('uploads/product/'.$gallery->image)}}" data-zoom-image="{{url('uploads/product/'.$gallery->image)}}"><img src="{{url('uploads/product/thumb/'.$gallery->image)}}" alt=""></a></li>
                                @endforeach
                                @endif
								@if($productDetails->youtube_url_id)
								<li>
									<div class="video-link-product" data-toggle="modal" data-type="youtube" data-target="#modalVideoProduct" data-value="https://www.youtube.com/embed/{{$productDetails->youtube_url_id}}">
										<img src="{{url('assets/images/product/product-small-empty.png')}}" alt="" >
										<div>
											<i class="icon-f-32"></i>
										</div>
									</div>
								</li>
								@endif
							</ul>
						</div>
                         
					</div>
                 
				</div>
				<div class="col-6">
					<div class="tt-product-single-info" >
                          @if($settingInfo->theme==2 || $settingInfo->theme==12)
                          @if(!empty($brandDetails->image))
                          <div style="text-align:{{$oalign}};">
                          <a href="{{url(app()->getLocale().'/brands/'.$brandDetails->slug)}}">
                          <img style="max-height:100px;max-width:100px;" src="{{url('uploads/brand/thumb/'.$brandDetails->image)}}">
                          </a>
                          </div>
                          @elseif($strLang=="en" && !empty($brandDetails->title_en))
                          <div style="text-align:{{$oalign}};">
                          <a href="{{url(app()->getLocale().'/brands/'.$brandDetails->slug)}}">
                          {{$brandDetails->title_en}}
                          </a>
                          </div>
                          @elseif($strLang=="ar" && !empty($brandDetails->title_ar))
                          <div style="text-align:{{$oalign}};">
                          <a href="{{url('brands/'.$brandDetails->slug)}}">
                          {{$brandDetails->title_ar}}
                          </a>
                          </div>
                          @endif
                          @endif
                         
                         @if(!empty($productDetails->caption_en) || !empty($productDetails->caption_ar))
						 <div class="tt-wrapper">
							<div class="tt-label">
							    <span class="tt-label-location">
                                   @if(!empty($productDetails->caption_en) && $strLang=="en")
                                   <span class="tt-label-sale" style="background-color:{{$productDetails['caption_color']}};">{{$productDetails->caption_en}}</span>
                                   @endif
                                   @if(!empty($productDetails->caption_ar) && $strLang=="ar")
                                   <span class="tt-label-sale" style="background-color:{{$productDetails['caption_color']}};">{{$productDetails->caption_ar}}</span>
                                   @endif
								</span>
							</div>
						</div>
                        @endif
						<div class="tt-add-info">
							<ul> 
                                @if(!empty($productDetails->item_code))
								<li><span>{{__('webMessage.item_code')}}:</span> {{$productDetails->item_code}}</li>
                                @endif
                                @if(!empty($productDetails->sku_no))
								<li><span>{{__('webMessage.sku_no')}}:</span> {{$productDetails->sku_no}}</li>
                                <meta itemprop="sku" content="{{$productDetails->sku_no}}" />
                                @endif
                                @if($availableQty && ($availableQty>0 && $availableQty<=3))
                                @if($availableQty && $availableQty>0)
								<li><span>{{__('webMessage.availability')}}:</span> <span id="display_qty">{{$availableQty}}</span> <font color="#009900">{{__('webMessage.instock')}}</font></li>
                                @else
                                <li><span>{{__('webMessage.availability')}}:</span> <span id="display_qty">0</span> <font color="#ff0000">{{__('webMessage.outofstock')}}</font></li>
                                @endif
                                @endif
                                @if(!empty($brandDetails['title_'.$strLang]) && $settingInfo->theme<>2 && $settingInfo->theme<>12)
                                <li itemprop="brand" itemtype="http://schema.org/Brand" itemscope><span>{{__('webMessage.brand')}}:</span> <a href="{{url(app()->getLocale().'/brands/'.$brandDetails->slug)}}">@if(!empty($brandDetails->image)) <img style="max-height:100px;max-width:100px;" src="{{url('uploads/brand/thumb/'.$brandDetails->image)}}"> @else {{$brandDetails['title_'.$strLang]}} @endif</a>
                                <meta itemprop="name" content="{{$brandDetails['title_'.$strLang]}}" />
                                </li>
                                @endif
                                
                                @if(!empty($tagsDetails))
								<li>{!!$tagsDetails!!}</li>
                                @endif
                                
                                
							</ul>
						</div>
						<h1 class="tt-title">@if(app()->getLocale()=="en") {{$productDetails->title_en}} @else {{$productDetails->title_ar}} @endif</h1>
						<div class="tt-price">
                        @if(!empty($productDetails->countdown_datetime) && strtotime($productDetails->countdown_datetime)>strtotime(date('Y-m-d')))
                        <span class="new-price @if($productDetails->old_price) price_red @endif"><span id="">{{round($productDetails->countdown_price,3)}}</span>{{__('webMessage.kd')}}</span>
                        <span  class="old-price price_black"  id="oldprices"><small><span id="">{{round($productDetails->retail_price,3)}}</span>{{__('webMessage.kd')}}</small></span>
                           <div itemprop="offers" itemtype="http://schema.org/Offer" itemscope>
                                <link itemprop="url" href="{{url(app()->getLocale().'/directdetails/'.$productDetails->id.'/'.$productDetails->slug)}}" />
                                <meta itemprop="availability" content="https://schema.org/InStock" />
                                <meta itemprop="priceCurrency" content="KWD" />
                                <meta itemprop="itemCondition" content="https://schema.org/New" />
                                <meta itemprop="price" content="{{round($productDetails->countdown_price,3)}}" />
                                <meta itemprop="priceValidUntil" content="{{date('Y-m-d',strtotime(date('Y-m-d').'+10 days'))}}" />
                            </div>
                            <meta itemprop="sell_on_google_price" content="{{round($productDetails->retail_price,3)}} KWD" />
                            <meta itemprop="sell_on_google_sale_price" content="{{round($productDetails->countdown_price,3)}} KWD" />  
                            @elseif ($checkBrandDiscount)
                            <span class="{{$brandDiscountedPrice->oldPrice?'price_red':''}} new-price"><span id="">{{$brandDiscountedPrice->price}}</span>{{__('webMessage.kd')}}</span>
                            <span  class="old-price price_black"  id="oldprices"><small><span id="">{{$brandDiscountedPrice->oldPrice}}</span>{{__('webMessage.kd')}}</small></span>
                               <div itemprop="offers" itemtype="http://schema.org/Offer" itemscope>
                                    <link itemprop="url" href="{{url(app()->getLocale().'/directdetails/'.$productDetails->id.'/'.$productDetails->slug)}}" />
                                    <meta itemprop="availability" content="https://schema.org/InStock" />
                                    <meta itemprop="priceCurrency" content="KWD" />
                                    <meta itemprop="itemCondition" content="https://schema.org/New" />
                                    <meta itemprop="price" content="{{round($productDetails->countdown_price,3)}}" />
                                    <meta itemprop="priceValidUntil" content="{{date('Y-m-d',strtotime(date('Y-m-d').'+10 days'))}}" />
                                </div>
                                <meta itemprop="sell_on_google_price" content="{{round($productDetails->retail_price,3)}} KWD" />
                                <meta itemprop="sell_on_google_sale_price" content="{{round($productDetails->countdown_price,3)}} KWD" />  
                            @else
							<span class="new-price {{$productDetails->old_price?'price_red':'' }}"><span id="display_price">{{round($productDetails->retail_price,3)}}</span>{{__('webMessage.kd')}}</span>
                            @if($productDetails->old_price)
							<span class="old-price price_black"  id="oldprices" ><small><span id="display_oldprice">{{round($productDetails->old_price,3)}}</span>{{__('webMessage.kd')}}</small></span>
                            <meta itemprop="sell_on_google_price" content="{{round($productDetails->old_price,3)}} KWD" />
                            <meta itemprop="sell_on_google_sale_price" content="{{round($productDetails->retail_price,3)}} KWD" />
                            @else
                            <meta itemprop="sell_on_google_price" content="{{round($productDetails->retail_price,3)}} KWD" />
                            @endif
                            
                            <div itemprop="offers" itemtype="http://schema.org/Offer" itemscope>
                                <link itemprop="url" href="{{url(app()->getLocale().'/directdetails/'.$productDetails->id.'/'.$productDetails->slug)}}" />
                                <meta itemprop="availability" content="https://schema.org/InStock" />
                                <meta itemprop="priceCurrency" content="KWD" />
                                <meta itemprop="itemCondition" content="https://schema.org/New" />
                                <meta itemprop="price" content="{{round($productDetails->retail_price,3)}}" />
                                <meta itemprop="priceValidUntil" content="{{date('Y-m-d',strtotime(date('Y-m-d').'+10 days'))}}" />
                            </div>
                            
                            
                         @endif
                         
						</div>
                        
                        @if($productDetails->customizable)
                            <div>
                                <a class="btn btn-primary" href="{{url('/product/'.$productDetails->id.'/customize')}}" target="_blank">
                                    {{__('webMessage.customize_this_product')}}
                                </a>
                            </div>
                        @endif


                        @php
                        $ratingsproducts = App\Http\Controllers\webController::getProductRatings($productDetails->id);
                        @endphp
						<div class="tt-review">
							<div class="tt-rating">
								{!!$ratingsproducts!!}
							</div>
							<a href="#showreviews">({{count($ReviewsLists)}} {{__('webMessage.customerreview')}})</a>
						</div>
						@if(!empty($productDetails->countdown_datetime) && strtotime($productDetails->countdown_datetime)>strtotime(date('Y-m-d')))
						<div class="tt-wrapper">
							<div class="tt-countdown_box_02">
								<div class="tt-countdown_inner">
									<div class="tt-countdown"
										data-date="{{$productDetails->countdown_datetime}}"
										data-year="Yrs"
										data-month="Mths"
										data-week="Wk"
										data-day="Day"
										data-hour="Hrs"
										data-minute="Min"
										data-second="Sec"></div>
								</div>
							</div>
                         
						</div>
                        @endif
                        <form name="addtocartDetailsForm" id="addtocartDetailsForm" method="POST" action="{{route('addtocartDetails',app()->getLocale())}}" enctype="multipart/form-data">
                        
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="product_id" id="product_id" value="{{$productDetails->id}}">
                        
                        @if(!empty($productDetails->countdown_datetime) && strtotime($productDetails->countdown_datetime)>strtotime(date('Y-m-d')))
                        <input type="hidden" name="price" id="unit_price" value="{{$productDetails->countdown_price}}">
                        @else
                        <input type="hidden" name="price" id="unit_price" value="{{$productDetails->retail_price}}">
                        @endif
                        <!--attribute -->
                        @if(!empty($productDetails->is_attribute) && $availableQty>0)
                        <div class="tt-swatches-container">
                        <img id="loader-gif" src="{{url('assets/images/loader.svg')}}" style="position:absolute;margin-left:30%;display:none;margin-top:-40px;">
                        <!--product options -->
                        @if(!empty($productoptions) && count($productoptions)>0)
                        @foreach($productoptions as $productoption)
                        <input type="hidden" name="option_id[]" id="option_id_{{$productoption->id}}" value="{{$productoption->id}}">
                        
                        <!--check custom option for size/color - 1,2,3-->
                        @if($productoption->custom_option_id==1)
                        <input type="hidden" name="option_sc" id="option_sc_{{$productoption->id}}" value="{{$productoption->custom_option_id}}">
                        @php
                        $SizeAttributes = App\Http\Controllers\webCartController::getSizeByCustomIdProductId($productoption->custom_option_id,$productDetails->id);
                        @endphp
                        <!--size-->
                        @if(!empty($SizeAttributes) && count($SizeAttributes)>0)
							<div class="tt-wrapper">
								<div class="tt-title-options">{{__('webMessage.size')}}*:</div>
									<div class="form-group">
										<select class="form-control size_attr" name="size_attr" id="size_attr_{{$productDetails->id}}">
                                            <option value="0">{{__('webMessage.choosesize')}}</option>
											@foreach($SizeAttributes as $SizeAttribute)
                                            @php if($strLang=="en"){ $sizeName = $SizeAttribute->title_en;}else{$sizeName = $SizeAttribute->title_ar;}@endphp
                                            <option value="{{$SizeAttribute->size_id}}">{{$sizeName}}</option>
                                            @endforeach
										</select>
									</div>
							</div>
                         @endif
                        <!--size end -->
                        @elseif($productoption->custom_option_id==2)
                        <input type="hidden" name="option_sc" id="option_sc_{{$productoption->id}}" value="{{$productoption->custom_option_id}}">
                        @php
                        $ColorAttributes = App\Http\Controllers\webCartController::getColorByCustomIdProductId($productoption->custom_option_id,$productDetails->id);
                        @endphp
                        <!--color-->
                        @if(!empty($ColorAttributes) && count($ColorAttributes)>0)
                        <input type="hidden" name="is_color" id="is_color" value="1">
                        <input type="hidden" name="color_attr" id="color_attr" value="">
                            <span id="color_box">
							<div class="tt-wrapper">
								<div class="tt-title-options">{{__('webMessage.texture')}}:</div>
								<ul class="tt-options-swatch options-large">
									@foreach($ColorAttributes as $ColorAttribute)
                                    @php
                                    if($ColorAttribute->color_code){$colorcode=$ColorAttribute->color_code;}else{$colorcode='none';}
                                    @endphp
                                  
                                    @if(!empty($ColorAttribute->image))
                                    <li id="li-{{$ColorAttribute->color_id}}">
                                    <a class="options-color"  href="javascript:;" id="{{$ColorAttribute->color_id}}">
										<span class="swatch-img">
											<img src="{{url('uploads/color/thumb/'.$ColorAttribute->image)}}" alt="">
										</span>
										<span class="swatch-label color-black"></span>
									</a>
                                    </li>
                                    @else
                                    <li id="li-{{$ColorAttribute->color_id}}"><a href="javascript:;" class="options-color" style="background-color:{{$colorcode}};" id="{{$ColorAttribute->color_id}}" ></a></li>
                                    @endif
                                    @endforeach
								</ul>
                                <br clear="all">
							</div>
                            </span>
                            <br clear="all">
							@endif
                             
                        <!--color end -->
                        @elseif($productoption->custom_option_id==3)
                        <input type="hidden" name="option_sc" id="option_sc_{{$productoption->id}}" value="{{$productoption->custom_option_id}}">
                        <!--size & color-->
                        @php
                        $SizeAttributes = App\Http\Controllers\webCartController::getSizeByCustomIdProductId($productoption->custom_option_id,$productDetails->id);
                        @endphp
                        <!--size-->
                        @if(!empty($SizeAttributes) && count($SizeAttributes)>0)
							<div class="tt-wrapper">
								<div class="tt-title-options">{{__('webMessage.size')}}:</div>
									<div class="form-group">
										<select class="form-control size_attr" name="size_attr" id="size_attr_{{$productDetails->id}}">
                                            <option value="0">{{__('webMessage.choosesize')}}</option>
											@foreach($SizeAttributes as $SizeAttribute)
                                            @php if($strLang=="en"){ $sizeName = $SizeAttribute->title_en;}else{$sizeName = $SizeAttribute->title_ar;}@endphp
                                            <option value="{{$SizeAttribute->size_id}}">{{$sizeName}}</option>
                                            @endforeach
										</select>
									</div>
							</div>
                         @endif
                         
                         @php
                        $ColorAttributes = App\Http\Controllers\webCartController::getColorByCustomIdProductId($productoption->custom_option_id,$productDetails->id);
                        @endphp
                        <!--color-->
                        @if(!empty($ColorAttributes) && count($ColorAttributes)>0)
                        <input type="hidden" name="is_color" id="is_color" value="1">
                        <input type="hidden" name="color_attr" id="color_attr" value="">
                            <span id="color_box">
							<div class="tt-wrapper">
								<div class="tt-title-options">{{__('webMessage.texture')}}:</div>
								<ul class="tt-options-swatch options-large">
									@foreach($ColorAttributes as $ColorAttribute)
                                    @php
                                    if($ColorAttribute->color_code){$colorcode=$ColorAttribute->color_code;}else{$colorcode='none';}
                                    @endphp
                                  
                                    @if(!empty($ColorAttribute->image))
                                    <li>
                                    <a class="options-color"  href="javascript:;" id="{{$ColorAttribute->color_id}}">
										<span class="swatch-img">
											<img src="{{url('uploads/color/thumb/'.$ColorAttribute->image)}}" alt="">
										</span>
										<span class="swatch-label color-black"></span>
									</a>
                                    </li>
                                    @else
                                    <li><a href="javascript:;" class="options-color" style="background-color:{{$colorcode}};" id="{{$ColorAttribute->color_id}}" ></a></li>
                                    @endif
                                    @endforeach
								</ul>
                                <br clear="all">
							   </div>
                            </span>
                             <br clear="all">
							@endif
                       
                        <!--size & color end -->
                        @else
                        <!--optiona details-->
                        @php
                        $customOptions = App\Http\Controllers\webCartController::getCustomOptions($productoption->custom_option_id,$productDetails->id);
                        
                        @endphp
                        
                        <!--radio box -->
                        @if(!empty($customOptions['CustomOptionName']) && $customOptions['CustomOptionType']=="radio")
                        <div class="tt-wrapper">
						<div class="tt-title-options">{{$customOptions['CustomOptionName']}} @if(!empty($productoption->is_required))*@endif:</div>
						<ul class="optionradio">
                        @if(!empty($customOptions['childs']) && count($customOptions['childs'])>0)
                        @php $is_cadd_txt=''; @endphp
                        @foreach($customOptions['childs'] as $child)
                        @php
                        if(!empty($child->retail_price) && !empty($child->is_price_add) && $child->is_price_add==1){
                        $is_cadd="+";

                        $is_cadd_txt=$is_cadd.' '.$child->retail_price.' '.trans('webMessage.kd');
                        }else if(!empty($child->retail_price) && !empty($child->is_price_add) && $child->is_price_add==2){
                        $is_cadd="-";
                        $is_cadd_txt=$is_cadd.' '.$child->retail_price.' '.trans('webMessage.kd');
                        }else if(!empty($child->retail_price) && empty($child->is_price_add)){
                        $is_cadd="";
                        $is_cadd_txt=$child->retail_price.' '.trans('webMessage.kd');
                        }else{
                        $is_cadd="";
                        $is_cadd_txt="";
                        }
                        
                        $option_value_name = $strLang=="en"?$child->option_value_name_en:$child->option_value_name_ar;
                        @endphp
                        <li><label for="option-{{$productDetails->id}}-{{$productoption->custom_option_id}}-{{$child->id}}"><input class="checkOptionPrice" type="radio" name="option-{{$productDetails->id}}-{{$productoption->custom_option_id}}" id="option-{{$productDetails->id}}-{{$productoption->custom_option_id}}-{{$child->id}}" value="{{$child->id}}">&nbsp;{{$option_value_name}}({{$is_cadd_txt}})</label></li>
                        @endforeach
                        @endif
                        </ul>
                        </div>     
                        @endif   
                        <!--end radio box -->
                        <!--check box -->
                        @if(!empty($customOptions['CustomOptionName']) && $customOptions['CustomOptionType']=="checkbox")
                        <div class="tt-wrapper">
						<div class="tt-title-options">{{$customOptions['CustomOptionName']}}@if(!empty($productoption->is_required))*@endif:</div>
						<ul class="optionradio">
                        @if(!empty($customOptions['childs']) && count($customOptions['childs'])>0)
                        @php $is_cadd_txt=''; @endphp
                        @foreach($customOptions['childs'] as $child)
                        
                        @php
                        if(!empty($child->retail_price) && !empty($child->is_price_add) && $child->is_price_add==1){
                        $is_cadd="+";
                        $is_cadd_txt=$is_cadd.' '.$child->retail_price.' '.trans('webMessage.kd');
                        }else if(!empty($child->retail_price) && !empty($child->is_price_add) && $child->is_price_add==2){
                        $is_cadd="-";
                        $is_cadd_txt=$is_cadd.' '.$child->retail_price.' '.trans('webMessage.kd');
                        }else if(!empty($child->retail_price) && empty($child->is_price_add)){
                        $is_cadd="";
                        $is_cadd_txt=$child->retail_price.' '.trans('webMessage.kd');
                        }else{
                        $is_cadd="";
                        $is_cadd_txt="";
                        }
                        $option_value_name = $strLang=="en"?$child->option_value_name_en:$child->option_value_name_ar;
                        @endphp
                        <li><label for="checkbox-{{$productDetails->id}}-{{$productoption->custom_option_id}}-{{$child->id}}"><input class="checkOptionPricechk" type="checkbox" name="checkbox-{{$productDetails->id}}-{{$productoption->custom_option_id}}[]" id="checkbox-{{$productDetails->id}}-{{$productoption->custom_option_id}}-{{$child->id}}" value="{{$child->id}}">&nbsp;{{$option_value_name}}({{$is_cadd_txt}})</label></li>
                        @endforeach
                        @endif
                        </ul>
                        </div>     
                        @endif   
                        <!--end check box -->
                        
                        <!--select box -->
                        @if(!empty($customOptions['CustomOptionName']) && $customOptions['CustomOptionType']=="select")
                        <div class="tt-wrapper">
						<div class="tt-title-options">{{$customOptions['CustomOptionName']}}@if(!empty($productoption->is_required))*@endif:</div>
						<div class="form-group">
						<select class="form-control choose_select_options" name="select-{{$productDetails->id}}-{{$productoption->custom_option_id}}" id="select-{{$productDetails->id}}-{{$productoption->custom_option_id}}" @if(!empty($productoption->is_required)) seelcted @endif>
                        @if(empty($productoption->is_required))
                        <option value="0">---</option>
                        @endif
                        @if(!empty($customOptions['childs']) && count($customOptions['childs'])>0)
                        @php $is_cadd_txt=''; @endphp
                        @foreach($customOptions['childs'] as $child)
                        
                        @php
                        if(!empty($child->retail_price) && !empty($child->is_price_add) && $child->is_price_add==1){
                        $is_cadd="+";
                        $is_cadd_txt=$is_cadd.' '.$child->retail_price.' '.trans('webMessage.kd');
                        }else if(!empty($child->retail_price) && !empty($child->is_price_add) && $child->is_price_add==2){
                        $is_cadd="-";
                        $is_cadd_txt=$is_cadd.' '.$child->retail_price.' '.trans('webMessage.kd');
                        }else if(!empty($child->retail_price) && empty($child->is_price_add)){
                        $is_cadd="";
                        $is_cadd_txt=$child->retail_price.' '.trans('webMessage.kd');
                        }else{
                        $is_cadd="";
                        $is_cadd_txt="";
                        }
                        $option_value_name = $strLang=="en"?$child->option_value_name_en:$child->option_value_name_ar;
                        @endphp
                        <option value="select-{{$productDetails->id}}-{{$productoption->custom_option_id}}-{{$child->id}}">{{$option_value_name}}({{$is_cadd_txt}})</option>
                        @endforeach
                        @endif
                        </select>
                        </div>  
                        </div>     
                        @endif   
                        <!--end select box -->
                        <!--datetime -->
                        @if(!empty($customOptions['CustomOptionName']) && $customOptions['CustomOptionType']=="datetime")
                        <div class="tt-wrapper">
						<div class="tt-title-options">{{$customOptions['CustomOptionName']}}@if(!empty($productoption->is_required))*@endif:</div>
						<div class="form-group">
						<input type="text" class="form-control datetimepicker" name="datetime-{{$productDetails->id}}-{{$productoption->custom_option_id}}" id="datetime-{{$productDetails->id}}-{{$productoption->custom_option_id}}" @if(!empty($productoption->is_required)) required @endif>
                        </div>  
                        </div>  
                        @endif 
                        <!--datetime-->
                        <!--file -->
                        @if(!empty($customOptions['CustomOptionName']) && $customOptions['CustomOptionType']=="file")
                        <div class="tt-wrapper">
						<div class="tt-title-options">{{$customOptions['CustomOptionName']}}@if(!empty($productoption->is_required))*@endif:</div>
						<div class="form-group">
						<input type="file" accept="image/*" class="form-control" name="file-{{$productDetails->id}}-{{$productoption->custom_option_id}}" id="file-{{$productDetails->id}}-{{$productoption->custom_option_id}}" @if(!empty($productoption->is_required)) required @endif>
                        </div>  
                        </div>  
                        @endif 
                        <!--file-->
                        <!--file -->
                        @if(!empty($customOptions['CustomOptionName']) && $customOptions['CustomOptionType']=="textarea")
                        <div class="tt-wrapper">
						<div class="tt-title-options">{{$customOptions['CustomOptionName']}}@if(!empty($productoption->is_required))*@endif:</div>
						<div class="form-group">
						<textarea  class="form-control" name="textarea-{{$productDetails->id}}-{{$productoption->custom_option_id}}" id="textarea-{{$productDetails->id}}-{{$productoption->custom_option_id}}" @if(!empty($productoption->is_required)) required @endif></textarea>
                        </div>  
                        </div>  
                        @endif 
                        <!--file-->
                        <!--optiona details end -->
                        @endif
                        @endforeach
                        @endif
                        <!--end options -->    
                        </div>                 
                        @endif
                        <!--end attribute -->
                        
                        
                        
                        @if($availableQty>0)
						<div class="tt-wrapper">
							<div class="tt-row-custom-01">
								<div class="col-item">
									<div class="tt-input-counter style-01">
										<span class="minus-btn" id="{{$productDetails->id}}"></span>
										<input type="text" value="1" size="{{$availableQty}}" name="quantity_attr" id="quantity_attr">
										<span class="plus-btn" id="{{$productDetails->id}}"></span>
									</div>
								</div>
								<div class="col-item">
                                @if($productDetails->is_active==2)
						        <button type="submit" class="btn btn-lg"  id="details_cartbtn"><img id="loader-details-gif" src="{{url('assets/images/loader.svg')}}" style="position:absolute;margin-left:2%;display:none;margin-top:-1px;"><i class="icon-f-39"></i>{{__('webMessage.preorder')}}</button>
								@else
                                <button type="submit" class="btn btn-lg"  id="details_cartbtn"><img id="loader-details-gif" src="{{url('assets/images/loader.svg')}}" style="position:absolute;margin-left:2%;display:none;margin-top:-1px;"><i class="icon-f-39"></i>{{__('webMessage.addtocart_btn')}}</button>
                                @endif
                                </div>
							</div>
						</div>
                        @else
                        @php
                        if($productDetails->is_active==2){
                        $txtBtn = trans('webMessage.preorder');
                        }else{
                        $txtBtn = trans('webMessage.backorder');
                        }
                        @endphp
                        <div class="tt-wrapper">
                                <div class="row">
                                <div class="col-lg-4">
                                <div class="form-group">
								<label for="inquiry_name" class="control-label">{{__('webMessage.name')}} </label>
								<input type="text" class="form-control" id="inquiry_name" name="inquiry_name" placeholder="{{__('webMessage.enter_name')}}">
                                </div>
                                </div>
                                <div class="col-lg-4">
                                <div class="form-group">
								<label for="inquiry_email" class="control-label">{{__('webMessage.email')}} *</label>
								<input type="email" class="form-control" id="inquiry_email" name="inquiry_email" placeholder="{{__('webMessage.enter_email')}}">
                                </div>
                                </div>
                                <div class="col-lg-4">
                                <div class="form-group">
								<label for="inquiry_mobile" class="control-label">{{__('webMessage.mobile')}} *</label>
								<input type="email" class="form-control" id="inquiry_mobile" name="inquiry_mobile" placeholder="{{__('webMessage.enter_mobile')}}">
                                </div>
                                </div>
                                </div>
								<div class="row">
                                <div class="col-lg-12">
								<div class="form-group">
								<label for="inquiry_message" class="control-label">{{__('webMessage.message')}} </label>
								<textarea class="form-control"  id="inquiry_message" name="inquiry_message" placeholder="{{__('webMessage.write_some_text')}}" rows="4"></textarea>                </div>
                               <div class="form-group"><button type="button" class="btn btn-lg btncartInquiry"  id="{{$productDetails->id}}"><i class="icon-f-39"></i>{{$txtBtn}}</button><img width="40" src="{{url('assets/images/ajax-loader.gif')}}" id="loading-gif" style="display:none;"></div>
                               </div>
                               </div>  
                                         
						</div>
                        @endif
                        </form>
						<div class="tt-wrapper">
                        <div class="row">
                        @if(!empty($productDetails->attachfile))
                        <div class="col-3"><a class="btn-link" target="_blank" href="{{url('uploads/product/'.$productDetails->attachfile)}}"><i class="icon-e-39"></i>{{strtoupper(__('webMessage.catalogue'))}}</a></div>
                        @endif
                         <div class="col-6">
							<ul class="tt-list-btn">
								<li><a class="btn-link addtowishlist" href="javascript:;" id="{{$productDetails->id}}"><i class="icon-n-072"></i>{{strtoupper(__('webMessage.addtowishlist'))}}</a></li>
							</ul>
                           </div>
                           <div class="col-6" align="right">
						   
                           @php
                           if(app()->getLocale()=="en"){
                           $text = $productDetails->title_en;
                           }else{
                           $text = $productDetails->title_ar;
                           }
                           $url   = url(app()->getLocale().'/details/'.$productDetails->id.'/'.$productDetails->slug);
                           $image = url('/uploads/product/'.$productDetails->image);
                           $facebook_Share  = App\Http\Controllers\webController::createSocialLinks("facebook",$url,$text);
                           $twitter_Share   = App\Http\Controllers\webController::createSocialLinks("twitter",$url,$text);
                           $google_Share    = App\Http\Controllers\webController::createSocialLinks("googleplus",$url,$text);
                           $pinterest_Share = App\Http\Controllers\webController::createSocialLinks("pinterest",$url,$text,$image);
                           $whatsapp_Share = App\Http\Controllers\webController::createSocialLinks("whatsapp",$url,$text,$image);
                           
                           
                           @endphp
                            <ul class="tt-social-icon">
                                <li>{{strtoupper(trans('webMessage.share'))}}</li>
                                <li><a class="icon-g-64 shareDataLayer" id="{{$productDetails->id}}" target="_blank" href="{{$facebook_Share}}" title="{{trans('webMessage.facebook')}}"></a></li>
                                <li><a class="icon-h-58 shareDataLayer" id="{{$productDetails->id}}" target="_blank" href="{{$twitter_Share}}" title="{{trans('webMessage.twitter')}}"></a></li>
                                <li><a class="icon-g-66 shareDataLayer" id="{{$productDetails->id}}" target="_blank" href="{{$google_Share}}" title="{{trans('webMessage.google')}}"></a></li>
                                <li><a class="icon-g-70 shareDataLayer" id="{{$productDetails->id}}" target="_blank" href="{{$pinterest_Share}}" title="{{trans('webMessage.pinterest')}}"></a></li>
                                <li><a class="icon-h-12 shareDataLayer" id="{{$productDetails->id}}" target="_blank" href="{{$whatsapp_Share}}" title="{{trans('webMessage.whatsapp')}}"></a></li>
                            </ul>
                           </div>
                           </div> 
						</div>
                        <span id="quickresponse"></span>
						
						<!--details start -->
						@if(!empty($productDetails['details_'.$strLang]) && strlen($productDetails['details_'.$strLang])>30)
                       
						<div class="tt-collapse-block">
							<div class="tt-item active">
								<div class="tt-collapse-title">{{strtoupper(__('webMessage.description'))}}</div>
								<div class="tt-collapse-content" itemprop="description" content="@if(app()->getLocale()=="en") {!!strip_tags($productDetails->details_en)!!} @else {!!strip_tags($productDetails->details_ar)!!} @endif">
									@if(app()->getLocale()=="en") {!!$productDetails->details_en!!} @else {!!$productDetails->details_ar!!} @endif
                                    
								</div>
							</div>							
						</div>
						@endif
						<!--details end -->
						<!--warranty -->
                        @if(!empty($productDetails['warranty']))
                        @php
                        $warrantyDetails = App\Http\Controllers\webController::getWarrantyDetails($productDetails['warranty']);
                        @endphp
						<div class="tt-collapse-block">
							<div class="tt-item active">
								<div class="tt-collapse-title">{{strtoupper(__('webMessage.warranty'))}}</div>
								<div class="tt-collapse-content" style="display: blocks;">
                                    <p><strong>@if(app()->getLocale()=="en" && !empty($warrantyDetails->title_en)) {!!$warrantyDetails->title_en!!} @elseif(app()->getLocale()=="ar" && $warrantyDetails->title_ar) {!!$warrantyDetails->title_ar!!} @endif</strong></p>
									@if(app()->getLocale()=="en" && !empty($warrantyDetails->details_en)) {!!$warrantyDetails->details_en!!} @elseif(app()->getLocale()=="ar" && !empty($warrantyDetails->details_ar)) {!!$warrantyDetails->details_ar!!} @endif
								</div>
							</div>							
						</div>
						@endif
                        <!--warrant end -->
					</div>
				</div>
			</div>
            
      
            <!--description -->
            
 @if(!empty($settingInfo->is_review_active))           
            
 <div class="container-indent">
                       <div class="tt-collapse-block">
							<div class="tt-item active" id="showreviews">
								<div class="tt-collapse-title">{{strtoupper(__('webMessage.reviews'))}}({{count($ReviewsLists)}})</div>
								<div class="tt-collapse-content">
									<div class="tt-review-block">
                                        @php $k=0; @endphp
                                        @if(!empty($ReviewsLists) && count($ReviewsLists)>0)
										<div class="tt-review-comments">
                                        @php
                                        $agRating = 0;
                                        $k=0;
                                        @endphp
                                        @foreach($ReviewsLists as $ReviewsList)
                                        @php
                                        if($ReviewsList->customer_id){
                                        $customerDetails = App\Http\Controllers\webController::getCustomerDetails($ReviewsList->customer_id);
                                        $reviewRatings = App\Http\Controllers\webController::getRatings($ReviewsList->ratings);
                                        }
                                        $agRating+=$ReviewsList->ratings;
                                        @endphp
                                        
											<div class="tt-item" itemprop="review" itemtype="http://schema.org/Review" itemscope>
												<div class="tt-avatar">
                                                    @if(!empty($customerDetails) && $customerDetails->image)
													<a href="javascript:;"><img src="{{url('uploads/customers/thumb/'.$customerDetails->image)}}" alt=""></a>
                                                    @else
                                                    <a href="javascript:;"><img src="{{url('assets/images/product/single/review-comments-img-01.jpg')}}" alt=""></a>
                                                    @endif
												</div>
												<div class="tt-content">
													<div class="tt-rating" itemprop="reviewRating" itemtype="http://schema.org/Rating" itemscope>
														@if(!empty($reviewRatings)) {!!$reviewRatings!!} @endif
                                                        <meta itemprop="ratingValue" content="{{$ReviewsList->ratings}}" />
                                                        <meta itemprop="bestRating" content="5" />
													</div>
													<div class="tt-comments-info" itemprop="author" itemtype="http://schema.org/Person" itemscope>
														<span class="username" itemprop="name" content="{{$ReviewsList->name}}">{{__('webMessage.by')}} <span>{{$ReviewsList->name}}</span></span>
														<span class="time">{{__('webMessage.on')}} {{ \Carbon\Carbon::parse($ReviewsList->created_at)->diffForHumans() }}</span>
													</div>
													<div class="tt-comments-title">Very Good!</div>
													<p>
														{!!$ReviewsList->message!!}
													</p>
												</div>
											</div>
                                            @php $k++; @endphp
                                            @endforeach
                                            @endif
										</div>
                                        @php
                                        if(isset($k) && empty($k)){$k=1;}
                                        
                                        if(empty($agRating)){$agRating=1;}
                                        $avrgRat = !empty($k)?($agRating/$k):1;
                                        @endphp
                                        <div itemprop="aggregateRating" itemtype="http://schema.org/AggregateRating" itemscope>
                                        <meta itemprop="reviewCount" content="{{$k}}" />
                                        <meta itemprop="ratingValue" content="{{$avrgRat}}" />
                                        </div>
                                        
										<div class="tt-review-form">
                                            @if(count($ReviewsLists)==0)
											<div class="tt-message-info">
												{{__('webMessage.bethefirstreview')}} <strong><span>“{{$text}}”</span></strong>
											</div>
                                            @endif
											<p>{{__('webMessage.reviewnote')}}</p>
											<br>
											<form class="form-default" name="reviewform" id="reviewform" method="post" action="{{url(app()->getLocale().'/details/'.request()->id.'/'.request()->slug)}}">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <input type="hidden" name="product_id" value="{{$productDetails->id}}">
                                            <div class="form-group">
													<label for="ratings" class="control-label">{{__('webMessage.ratings')}} *</label>
													<select name="ratings" id="ratings" class="form-default">
                                                    <option value="5" @if(old('ratings')=="5") selected @endif>{{__('webMessage.superexcellent')}}</option>
                                                    <option value="4.5" @if(old('ratings')=="4.5") selected @endif>{{__('webMessage.excellent')}}</option>
                                                    <option value="4" @if(old('ratings')=="4") selected @endif>{{__('webMessage.verygood')}}</option>
                                                    <option value="3.5" @if(old('ratings')=="3.5") selected @endif>{{__('webMessage.good')}}</option>
                                                    <option value="3" @if(old('ratings')=="3") selected @endif>{{__('webMessage.poor')}}</option>
                                                    <option value="2.5" @if(old('ratings')=="2.5") selected @endif>{{__('webMessage.verypoor')}}</option>
                                                    <option value="2" @if(old('ratings')=="2") selected @endif>{{__('webMessage.notbad')}}</option>
                                                    <option value="1.5" @if(old('ratings')=="1.5") selected @endif>{{__('webMessage.bad')}}</option>
                                                    <option value="1" @if(old('ratings')=="1") selected @endif>{{__('webMessage.verybad')}}</option>
                                                    </select>
                                                    @if($errors->has('ratings'))
                                                    <label id="ratings-error" class="error" for="ratings">{{ $errors->first('ratings') }}</label>
                                                    @endif
												</div>
												<div class="form-group">
													<label for="name" class="control-label">{{__('webMessage.name')}} *</label>
													<input type="text" class="form-control" id="name" name="name" placeholder="{{__('webMessage.enter_name')}}">
                                                    @if($errors->has('name'))
                                                    <label id="name-error" class="error" for="name">{{ $errors->first('name') }}</label>
                                                    @endif
												</div>
												<div class="form-group">
													<label for="email" class="control-label">{{__('webMessage.email')}} *</label>
													<input type="email" class="form-control" id="email" name="email" placeholder="{{__('webMessage.enter_email')}}">
                                                    @if($errors->has('email'))
                                                    <label id="email-error" class="error" for="email">{{ $errors->first('email') }}</label>
                                                    @endif
												</div>
												<div class="form-group">
													<label for="message" class="control-label">{{__('webMessage.yourreview')}} *</label>
													<textarea class="form-control"  id="message" name="message" placeholder="{{__('webMessage.writeyourreview')}}" rows="8"></textarea>
                                                    @if($errors->has('message'))
                                                    <label id="message-error" class="error" for="message">{{ $errors->first('message') }}</label>
                                                    @endif
												</div>
                                                <div class="form-group">
                                                <div class="g-recaptcha" data-sitekey="6LeMueQUAAAAAJ-ZUe9ZqGK3pma9VwbeoaYDgJte"></div>
                                                @if($errors->has('recaptchaError'))
                                                <label id="message-error" class="error" for="message">{{ $errors->first('recaptchaError') }}</label>
                                                @endif
                                               </div>
												<div class="form-group">
													<button type="submit" class="btn">{{__('webMessage.sendnow')}}</button>
												</div>
                                                 @if(session('session_msg'))
                                    <div class="alert-success">{{session('session_msg')}}</div>
                                    @endif
											</form>
										</div>
									</div>
								</div>
							</div>
						</div>
    </div>
    @endif
		</div>
	</div>
    
<!-- end product details -->	
@if(!empty($relatedProducts) && count($relatedProducts)>0)

<!--related products -->
<div class="container-indent">
		<div class="container container-fluid-custom-mobile-padding">
			<div class="tt-block-title text-left">
				<h3 class="tt-title-small">{{trans('webMessage.related_product')}}</h3>
			</div>
				<div class="tt-carousel-products row arrow-location-right-top tt-alignment-img tt-layout-product-item slick-animated-show-js">
               @foreach($relatedProducts as $relatedProduct)
               @php
               if(!empty($relatedProduct->image)){
               $imageUrl = url('uploads/product/'.$relatedProduct->image);
               }else{
               $imageUrl = url('uploads/no-image.png');
               }
               $isStock = App\Http\Controllers\webCartController::IsAvailableQuantity($relatedProduct->id);
               @endphp
				<div class="col-2 col-md-4 col-lg-3">
					<div class="tt-product thumbprod-center">
						<div class="tt-image-box">
							<a href="javascript:;" class="tt-btn-wishlist addtowishlistquick" data-tooltip="{{__('webMessage.addtowishlist')}}" {{__('webMessage.align')}} id="{{$relatedProduct->id}}"></a>
							<a href="{{url(app()->getLocale().'/directdetails/'.$relatedProduct->id.'/'.$relatedProduct->slug)}}">
								<span class="tt-img"><img src="{{url('assets/images/loader.svg')}}" data-src="{{$imageUrl}}" alt=""></span>
                                @if($relatedProduct->rollover_image)
								<span class="tt-img-roll-over"><img src="{{url('assets/images/loader.svg')}}" data-src="{{url('uploads/product/'.$relatedProduct->rollover_image)}}" alt=""></span>@endif
							</a>
						</div>
						<div class="tt-description">
							<span id="responseMsg-{{$relatedProduct->id}}"></span> 
                            
							<h2 class="tt-title"><a href="{{url(app()->getLocale().'/directdetails/'.$relatedProduct->id.'/'.$relatedProduct->slug)}}">@if(app()->getLocale()=="en") {{$relatedProduct->title_en}} @else {{$relatedProduct->title_ar}} @endif</a></h2>
							<div class="tt-price">
							@if(!empty($relatedProduct->countdown_datetime) && strtotime($relatedProduct->countdown_datetime)>strtotime(date('Y-m-d'))) 	                            <span class="new-price">{{$relatedProduct->countdown_price}} {{__('webMessage.kd')}}</span>
                            @else
                            <span class="new-price">{{$relatedProduct->retail_price}} {{__('webMessage.kd')}}</span>
						    @if(!empty($relatedProduct->old_price))
							<span class="old-price">{{$relatedProduct->old_price}} {{__('webMessage.kd')}}</span>
							@endif
                            @endif
							</div>
							<div class="tt-product-inside-hover">
								<div class="tt-row-btn">
                                    @if($relatedProduct->is_attribute)
                                    <a href="{{url(app()->getLocale().'/directdetails/'.$relatedProduct->id.'/'.$relatedProduct->slug)}}" class="tt-btn-addtocart thumbprod-button-bg" id="{{$relatedProduct->id}}">{{__('webMessage.details')}}</a>
                                    @else
                                    @if(!empty($isStock))
									@if($relatedProduct->is_active=='2')
									<a href="javascript:;" class="tt-btn-addtocart thumbprod-button-bg addtocartsingle" id="{{$relatedProduct->id}}">{{__('webMessage.preorder')}}</a>
                                    @else
                                    <a href="javascript:;" class="tt-btn-addtocart thumbprod-button-bg addtocartsingle" id="{{$relatedProduct->id}}">{{__('webMessage.addtocart_btn')}}</a>
                                    @endif
									@endif
                                    @endif
								</div>
                                <div class="tt-row-btn">
									<a href="javascript:;" class="tt-btn-quickview loadquickviewmodal"  id="{{$relatedProduct->id}}"></a>
									<a href="javascript:;"  class="tt-btn-wishlist addtowishlistquick" id="{{$relatedProduct->id}}"></a>
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
<!-- end related products -->
</div>
<!--footer-->
@include("website.includes.footer")

<!-- modal (AddToCartProduct) -->
@include("website.includes.addtocart_modal")

<div class="modal fade"  id="modalVideoProduct" tabindex="-1" role="dialog" aria-label="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-video">
		<div class="modal-content ">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="icon icon-clear"></span></button>
			</div>
			<div class="modal-body">
				<div class="modal-video-content">

				</div>
			</div>
		</div>
	</div>
</div>
@if(!empty($settingInfo->facebook_pixel))
<script>
	fbq('track', 'ViewContent', {
	  content_ids: ['{{$productDetails->id}}'],
	  content_type: 'product'
	});

 </script>
@endif    
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script src="{{url('assets/external/bootstrap/js/bootstrap.min.js')}}"></script>
<script src="{{url('assets/external/elevatezoom/jquery.elevatezoom.js')}}"></script>
<script src="{{url('assets/external/slick/slick.min.js')}}"></script>
<script src="{{url('assets/external/perfect-scrollbar/perfect-scrollbar.min.js')}}"></script>
<script src="{{url('assets/external/panelmenu/panelmenu.js')}}"></script>
<script src="{{url('assets/external/countdown/jquery.plugin.min.js')}}"></script>
<script src="{{url('assets/external/countdown/jquery.countdown.min.js')}}"></script>
<script src="{{url('assets/external/magnific-popup/jquery.magnific-popup.min.js')}}"></script>
<script src="{{url('assets/external/lazyLoad/lazyload.min.js')}}"></script>
<script src="{{url('assets/js/main.js')}}"></script>
<script defer src="{{url('hakum_assets/js/bundle.js')}}"></script>

<script src="{{url('assets/js/gulfweb.js')}}"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<!--recaptcha-->
<script src='https://www.google.com/recaptcha/api.js'></script>
<script src="{{url('assets/datetime/jquery.datetimepicker.full.js')}}"></script>
<script>

$(document).ready(function(){
    $(".datetimepicker" ).datetimepicker({
        format: 'Y-m-d h:i a',
		minDate:'{{date("Y-m-d")}}',
        onShow: function () {
            this.setOptions({
                maxDate:$(this).val()?$(this).val():false,
                maxTime:$(this).val()?$(this).val():false
            });
        }
  }).attr('readonly', 'readonly');
 }); 
 
 
 gtag("event", "view_item", {
  currency: "KWD",
  value: {{$productDetails->retail_price}},
  items: [
    {
      item_id: "{{$productDetails->id}}",
      item_name: "{{$productDetails->title_en}}",
      affiliation: "",
      coupon: "",
      currency: "KWD",
      discount: "",
      index: 5,
      item_brand: "{{$brandDetails->title_en??''}}",
      item_category: "{{$catTreeName}}",
      price: {{$productDetails->retail_price}},
      quantity: 1
    }
  ]
});


</script>
</body>
</html>