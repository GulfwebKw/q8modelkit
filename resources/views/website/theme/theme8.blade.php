@php
$settingInfo = App\Http\Controllers\webController::settings();
@endphp
<!DOCTYPE html>
<html lang="{{app()->getLocale()}}">
<head>
	<meta charset="utf-8">
	<title>@if(app()->getLocale()=="en") {{$settingInfo->name_en}} @else {{$settingInfo->name_ar}} @endif</title>
    <meta name="description" content="@if(app()->getLocale()=="en") {{$settingInfo->seo_description_en}} @else {{$settingInfo->seo_description_ar}} @endif" />
    <meta name="abstract"    content="@if(app()->getLocale()=="en") {{$settingInfo->seo_description_en}} @else {{$settingInfo->seo_description_ar}} @endif">
    <meta name="keywords"    content="@if(app()->getLocale()=="en") {{$settingInfo->seo_keywords_en}} @else {{$settingInfo->seo_keywords_ar}} @endif" />
    <meta name="Copyright"   content="{{$settingInfo->name_en}}, Kuwait Copyright 2020 - {{date('Y')}}" />
    <META NAME="Geography"   CONTENT="@if(app()->getLocale()=="en") {{$settingInfo->address_en}} @else {{$settingInfo->address_ar}} @endif">
    @if($settingInfo->extra_meta_tags) {!!$settingInfo->extra_meta_tags!!} @endif
    @if($settingInfo->favicon)
    <link rel="icon" href="{{url('uploads/logo/'.$settingInfo->favicon)}}">
    @endif
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    @include("website.includes.css")
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!--web push notification -->
    <script type='text/javascript' src='https://www.gstatic.com/firebasejs/7.4.0/firebase-app.js'></script>
    <script type='text/javascript' src='https://www.gstatic.com/firebasejs/7.4.0/firebase-messaging.js'></script>
    <script type='text/javascript' src='https://www.gstatic.com/firebasejs/7.4.0/firebase-analytics.js'></script>
    <script type='text/javascript' src='https://code.jquery.com/jquery-3.3.1.js'></script>
    <script src="{{url('assets/webpush/webpush.js')}}"></script>
    @if($settingInfo->logo)
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Organization",
      "url": "{{url('/'.app()->getLocale())}}",
      "logo": "{{url('uploads/logo/'.$settingInfo->logo)}}"
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
<!--load theme start -->
@php
$homesetions = App\Http\Controllers\webController::getSections();
if(!empty(app()->getLocale())){ $strLang = app()->getLocale();}else{$strLang="en";}
@endphp

@if(!empty($homesetions))
@foreach($homesetions as $homesetion)
@if($homesetion->section_type=="static" && $homesetion->key_name=="shop_by_category")
@include("website.includes.shop_by_categories_scrolling",['link'=>$homesetion->link])
@elseif($homesetion->section_type=="static" && $homesetion->key_name=="latest_product")
@include("website.includes.latestproducts",['link'=>$homesetion->link])
@elseif($homesetion->section_type=="static" && $homesetion->key_name=="favorite_brands")
@include("website.includes.shop_by_brands_scrolling",['link'=>$homesetion->link])
@elseif($homesetion->section_type=="static" && $homesetion->key_name=="shop_by_brands")
@include("website.includes.best_seller_by_brands",['link'=>$homesetion->link])
@elseif($homesetion->section_type=="static" && $homesetion->key_name=="banner")
@include("website.includes.banner",['link'=>$homesetion->link])
@elseif($homesetion->section_type=="static" && $homesetion->key_name=="short_text_boxes")
@include("website.includes.homeshorttext",['link'=>$homesetion->link])
@else
@php
$homesetionsprods = App\Http\Controllers\webController::getSectionsProducts($homesetion->id);
@endphp
@if(!empty($homesetionsprods) && count($homesetionsprods)>0)
<div class="container-indent">
		<div class="container container-fluid-custom-mobile-padding">
			<div class="tt-block-title">
				<h1 class="tt-title noborder"><a @if($settingInfo->theme==7) style="color:#000; text-decoration:none !important;" @endif href="{{!empty($homesetion->link)?$homesetion->link:url(app()->getLocale().'/allsections/'.$homesetion->id.'/'.$homesetion->slug)}}">@if(app()->getLocale()=="en"){{strtoupper($homesetion->title_en)}}@else{{$homesetion->title_ar}}@endif</a></h1>
              <div class="tt-description">&nbsp;</div>
			</div>
			<div class="tt-carousel-products row arrow-location-tab arrow-location-tab01 tt-alignment-img tt-collection-listing slick-animated-show-js">
            @php $tagsDetails=''; @endphp
				@foreach($homesetionsprods as $homesetionsprod)
				@php
				$isStock = App\Http\Controllers\webCartController::IsAvailableQuantity($homesetionsprod->id);
                $tagsDetails = App\Http\Controllers\webCartController::getTagsName($homesetionsprod->tags_en,$homesetionsprod->tags_ar);
				@endphp
                <div class="col-2 col-md-4 col-lg-3">
                    <div class="tt-product thumbprod-center">
						<div class="tt-image-box">
							<a href="javascript:;" class="tt-btn-quickview loadquickviewmodal" data-tooltip="{{__('webMessage.quickview')}}" data-tposition="{{__('webMessage.align')}}" id="{{$homesetionsprod->id}}"></a>
							<a href="javascript:;" class="tt-btn-wishlist addtowishlistquick" data-tooltip="{{__('webMessage.addtowishlist')}}" {{__('webMessage.align')}} id="{{$homesetionsprod->id}}"></a>
							<a href="{{url(app()->getLocale().'/directdetails/'.$homesetionsprod->id.'/'.$homesetionsprod->slug)}}">
								<span class="tt-img"><img src="{{url('assets/images/loader.svg')}}" data-src="@if($homesetionsprod->image) {{url('uploads/product/thumb/'.$homesetionsprod->image)}} @else {{url('uploads/no-image.png')}} @endif" alt="@if(app()->getLocale()=='en') {{$homesetionsprod->title_en}} @else {{$homesetionsprod->title_ar}} @endif"></span>
                                @if($homesetionsprod->rollover_image) 
								<span class="tt-img-roll-over"><img src="{{url('assets/images/loader.svg')}}" data-src="@if($homesetionsprod->rollover_image) {{url('uploads/product/thumb/'.$homesetionsprod->rollover_image)}} @else {{url('uploads/no-image.png')}} @endif" alt="@if(app()->getLocale()=='en') {{$homesetionsprod->title_en}} @else {{$homesetionsprod->title_ar}} @endif"></span>
                                @endif
								<span class="tt-label-location">
								@if(empty($isStock))<span class="tt-label-sale" style="background-color:#000000;color:#ffffff;">{{__('webMessage.outofstock')}}</span>@endif
								@if(!empty($homesetionsprod->caption_en))<span class="tt-label-sale" style="background-color:{{$homesetionsprod['caption_color']}};">{{$homesetionsprod['caption_'.$strLang]}}</span>@endif
								</span>
							</a>
                          <!--countdown-->
                         @if(!empty($homesetionsprod->countdown_datetime) && strtotime($homesetionsprod->countdown_datetime)>strtotime(date('Y-m-d'))) 
                         <div class="tt-countdown_box">
								<div class="tt-countdown_inner">
									<div class="tt-countdown" 
                                    data-date="{{$homesetionsprod->countdown_datetime}}" 
                                    data-year="{{__('webMessage.Yrs')}}" 
                                    data-month="{{__('webMessage.Mths')}}" 
                                    data-week="{{__('webMessage.Wk')}}" 
                                    data-day="{{__('webMessage.Day')}}" 
                                    data-hour="{{__('webMessage.Hrs')}}" 
                                    data-minute="{{__('webMessage.Min')}}" 
                                    data-second="{{__('webMessage.Sec')}}">
                                    </div>
								</div>
						 </div>
                         @endif  
                         <!--end countdown-->
						</div>
						<div class="tt-description">
                        <span id="responseMsg-{{$homesetionsprod->id}}"></span> 
							<h2 class="tt-title" style="min-height:70px;"><a href="{{url(app()->getLocale().'/directdetails/'.$homesetionsprod->id.'/'.$homesetionsprod->slug)}}">@if(app()->getLocale()=="en") {{$homesetionsprod->title_en}} @else {{$homesetionsprod->title_ar}} @endif</a></h2>
                            @if(!empty($tagsDetails)){!!$tagsDetails!!}@endif
							<div class="tt-price">
							@if(!empty($homesetionsprod->countdown_datetime) && strtotime($homesetionsprod->countdown_datetime)>strtotime(date('Y-m-d'))) 	 
							<span class="new-price price_red">{{$homesetionsprod->countdown_price}} {{__('webMessage.kd')}}</span>
                            <input type="hidden" id="pixel_price_{{$homesetionsprod->id}}" value="{{$homesetionsprod->countdown_price}}">
                            <span class="old-price price_black">{{$homesetionsprod->retail_price}} {{__('webMessage.kd')}}</span>
                            @else
                            <span class="new-price @if($homesetionsprod->old_price) price_red @endif">{{$homesetionsprod->retail_price}} {{__('webMessage.kd')}}</span>
                            <input type="hidden" id="pixel_price_{{$homesetionsprod->id}}" value="{{$homesetionsprod->retail_price}}">
						    @if(!empty($homesetionsprod->old_price))
							<span class="old-price price_black">{{$homesetionsprod->old_price}} {{__('webMessage.kd')}}</span>
							@endif
                            @endif
							</div>
							<div class="tt-product-inside-hover">
								<div class="tt-row-btn">
                                    @if($homesetionsprod->is_attribute)
                                    <a href="{{url(app()->getLocale().'/directdetails/'.$homesetionsprod->id.'/'.$homesetionsprod->slug)}}" class="tt-btn-addtocart thumbprod-button-bg" id="{{$homesetionsprod->id}}">{{__('webMessage.details')}}</a>
                                    @else
									@if(!empty($isStock))
                                    @if($homesetionsprod->is_active=='2')
                                    <a href="javascript:;" class="tt-btn-addtocart thumbprod-button-bg addtocartsingle @if(!empty($settingInfo->facebook_pixel)) addToCartPixelButton @endif" id="{{$homesetionsprod->id}}">{{__('webMessage.preorder')}}</a>
                                    @else
									<a href="javascript:;" class="tt-btn-addtocart thumbprod-button-bg addtocartsingle @if(!empty($settingInfo->facebook_pixel)) addToCartPixelButton @endif" id="{{$homesetionsprod->id}}">{{__('webMessage.addtocart_btn')}}</a>
                                    @endif
                                    @endif
									@endif
								</div>
								<div class="tt-row-btn">
									<a href="javascript:;" class="tt-btn-quickview loadquickviewmodal"  id="{{$homesetionsprod->id}}"></a>
									<a href="javascript:;"  class="tt-btn-wishlist addtowishlistquick" id="{{$homesetionsprod->id}}"></a>
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
 @endforeach   
 @endif
<!--end load theme -->
</div>
<!--footer -->
@include("website.includes.footer")	
<!-- modal (AddToCartProduct) -->
@include("website.includes.addtocart_modal")
<!-- modal (quickViewModal) -->
@include("website.includes.quickview_modal")


<script src="{{url('assets/external/jquery/jquery.min.js')}}"></script>
<script src="{{url('assets/external/bootstrap/js/bootstrap.min.js')}}"></script>
<script src="{{url('assets/external/slick/slick.min.js')}}"></script>
<script src="{{url('assets/external/perfect-scrollbar/perfect-scrollbar.min.js')}}"></script>
<script src="{{url('assets/external/panelmenu/panelmenu.js')}}"></script>
<script src="{{url('assets/external/instafeed/instafeed.min.js')}}"></script>
<script src="{{url('assets/external/countdown/jquery.plugin.min.js')}}"></script>
<script src="{{url('assets/external/countdown/jquery.countdown.min.js')}}"></script>
<script src="{{url('assets/external/rs-plugin/js/jquery.themepunch.tools.min.js')}}"></script>
<script src="{{url('assets/external/rs-plugin/js/jquery.themepunch.revolution.min.js')}}"></script>
<script src="{{url('assets/external/lazyLoad/lazyload.min.js')}}"></script>
<script src="{{url('assets/js/main.js')}}"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>

<script defer src="{{url('hakum_assets/js/bundle.js')}}"></script>
<script src="{{url('assets/js/gulfweb.js')}}"></script>
@if(!empty($settingInfo->facebook_pixel))
<script type="text/javascript">
  $( '.addToCartPixelButton' ).click(function() {
    var id = $(this).attr("id");
	var price = $("#pixel_price_"+id).val();
    fbq('track', 'AddToCart', {
      content_ids: [id],
      content_type: 'product',
      value: price,
      currency: 'USD' 
    });  
  });
</script>
@endif
@if(!empty($settingInfo->is_home_popup))
<script>
       $(document).ready(function(){
          $("#homemodalbox").modal('show'); 
       }); 
</script>  
@endif
</body>
</html>