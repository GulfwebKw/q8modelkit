@php
$settingInfo = App\Http\Controllers\webController::settings();
if(!empty(app()->getLocale())){ $strLang = app()->getLocale();}else{$strLang="en";}
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>@if(app()->getLocale()=="en") {{$settingInfo->name_en}} @else {{$settingInfo->name_ar}} @endif | {{__('webMessage.wishlist')}}</title>
<meta name="description" content="@if(app()->getLocale()=="en") {{$settingInfo->seo_description_en}} @else {{$settingInfo->seo_description_ar}} @endif" />
<meta name="abstract" content="@if(app()->getLocale()=="en") {{$settingInfo->seo_description_en}} @else {{$settingInfo->seo_description_ar}} @endif">
<meta name="keywords" content="@if(app()->getLocale()=="en") {{$settingInfo->seo_keywords_en}} @else {{$settingInfo->seo_keywords_ar}} @endif" />
<meta name="Copyright" content="{{$settingInfo->name_en}}, Kuwait Copyright 2020 - {{date('Y')}}" />
<META NAME="Geography" CONTENT="@if(app()->getLocale()=="en") {{$settingInfo->address_en}} @else {{$settingInfo->address_ar}} @endif">
@if($settingInfo->extra_meta_tags) {!!$settingInfo->extra_meta_tags!!} @endif
@if($settingInfo->favicon)
<link rel="icon" href="{{url('uploads/logo/'.$settingInfo->favicon)}}">
@endif
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
@include("website.includes.css")
<meta name="csrf-token" content="{{ csrf_token() }}">
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
			<li><a href="{{url(app()->getLocale().'/')}}">{{__('webMessage.home')}}</a></li>
			<li>{{__('webMessage.wishlist')}}</li>
		</ul>
	</div>
</div>
<div id="tt-pageContent">
	<div class="container-indent">
		<div class="container">
			<h1 class="tt-title-subpages noborder">{{__('webMessage.wishlist')}}</h1>
			<div class="tt-wishlist-box" id="js-wishlist-removeitem">
            @if(!empty($wishLists) && count($wishLists)>0)
                <span id="responseMsgwish"></span>
				<div class="tt-wishlist-list">
                @foreach($wishLists as $wishList)
                @php
                $prodDetails = App\Http\Controllers\webCartController::getProductDetails($wishList->product_id);
                @endphp
                @if(!empty($prodDetails->id))
					<div class="tt-item" id="wishdiv{{$wishList->id}}">
						<div class="tt-col-description">
							<div class="tt-img">
                               @if(!empty($prodDetails->image))
								<img src="{{url('uploads/product/thumb/'.$prodDetails->image)}}" alt="">
                                @else
                                <img src="{{url('uploads/no-image.png')}}" alt="">
                                @endif
							</div>
							<div class="tt-description">
								<h2 class="tt-title"><a href="{{url(app()->getLocale().'/directdetails/'.$prodDetails->id.'/'.$prodDetails->slug)}}">@if(app()->getLocale()=="en") {{$prodDetails->title_en}} @else {{$prodDetails->title_ar}} @endif</a></h2>
								<div class="tt-price">
									<span class="new-price"> {{$prodDetails->retail_price}} {{__('webMessage.kd')}}</span>
                                    @if($prodDetails->old_price)
									<span class="old-price"> {{$prodDetails->old_price}} {{__('webMessage.kd')}}</span>
                                    @endif
								</div>
							</div>
						</div>
						<div class="tt-col-btn">
							<a class="btn-link" href="{{url(app()->getLocale().'/directdetails/'.$prodDetails->id.'/'.$prodDetails->slug)}}" data-toggle="modal" data-target="#ModalquickView"><i class="icon-f-73"></i>{{__('webMessage.details')}}</a>
							<a class="btn-link removeitem" id="{{$wishList->id}}" href="javascript:;"><i class="icon-h-02"></i>{{__('webMessage.remove')}}</a>
						</div>
					</div>
                    @endif
                  @endforeach  
                  <div>{{$wishLists->links()}}</div>					
				</div>
                @else
                <div class="tt-wishlist-list">{{__('webMessage.noiteminwishlist')}}</div>
                @endif
			</div>
		</div>
	</div>
</div>
<!--footer-->
@include("website.includes.footer")

<!-- modal (AddToCartProduct) -->
@include("website.includes.addtocart_modal")

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

<script defer src="{{url('hakum_assets/js/bundle.js')}}"></script>
<script src="{{url('assets/js/gulfweb.js')}}"></script>

</body>
</html>