@php
$settingInfo = App\Http\Controllers\webController::settings();
if(!empty(app()->getLocale())){ $strLang = app()->getLocale();}else{$strLang="en";}

if(!empty($singleInfo['seo_description_'.$strLang])){
$seo_description = $singleInfo['seo_description_'.$strLang];
}else{
$seo_description = $settingInfo['seo_description_'.$strLang];
}
if(!empty($singleInfo['seo_keywords_'.$strLang])){
$seo_keywords = $singleInfo['seo_keywords_'.$strLang];
}else{
$seo_keywords = $settingInfo['seo_keywords_'.$strLang];
}
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>@if(app()->getLocale()=="en") {{$settingInfo->name_en}} @else {{$settingInfo->name_ar}} @endif | {{__('webMessage.bundles.Bundle')}}</title>
<meta name="description" content="{{$seo_description}}" />
<meta name="abstract"    content="{{$seo_description}}">
<meta name="keywords"    content="{{$seo_keywords}}" />
<meta name="Copyright"   content="{{$settingInfo->name_en}}, Kuwait Copyright 2020 - {{date('Y')}}" />
<META NAME="Geography"   CONTENT="@if(app()->getLocale()=="en") {{$settingInfo->address_en}} @else {{$settingInfo->address_ar}} @endif">
@if($settingInfo->extra_meta_tags) {!!$settingInfo->extra_meta_tags!!} @endif
@if($settingInfo->favicon)
<link rel="icon" href="{{url('uploads/logo/'.$settingInfo->favicon)}}">
@endif
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
@include("website.includes.css")
<link rel="stylesheet" href="{{ url('assets/css/bundle.css') }}">
<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
	showSubCat = "0";
</script>
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
			<li>{{__('webMessage.bundles.Bundle')}}</li>
		</ul>
	</div>
</div>
<div id="tt-pageContent">
	<div class="container-indent">
		<div class="container">
			<h1 class="tt-title-subpages noborder">{{ __('webMessage.categories') }}</h1>

			<div class="container-indent mb-30">
				<div class="container container-fluid-custom-mobile-padding">
					<div class="row tt-img-box-listing">
						@forelse($categories as $category)
						<div class="col-6 col-sm-4 col-md-2" onclick="changeBundleTab({{$category->id}});">
							<a href="#tt-pageContent" class="tt-img-box pad_3">
								<img src="{!! url('uploads/bundle_category/'.$category->image) !!}" alt="{{ $category['name_'.app()->getLocale()] }}">
							</a>
							<div class="tt-description text-center"><h2 class="tt-title mt-10 font-13"><a href="#tt-pageContent">{{ $category['name_'.app()->getLocale()] }}</a></h2></div>
						</div>
						@empty
						@endforelse
					</div>
				</div>
			</div>


			@php
				$tempOrderQuantity = App\BundleCategories::allTempOrderProductQuantity();
				$tempOrderId = App\BundleCategories::allTempOrderProductId();
			@endphp
			@forelse($categories as $category)
			<div class="tt-wishlist-box allSubCategory" id="category_{{$category->id}}" @if(!$loop->first) style="display: none;" @endif>
				<div class="tt-wishlist-list">
					@forelse($category->childs as $subCategory)
						<div class="tt-item my_border">
							<div class="tt-col-description" style="min-width: 250px;">
								<div class="tt-img">
									<img src="{!! url('uploads/bundle_category/'.$subCategory->image) !!}" alt="{{ $subCategory['name_'.app()->getLocale()] }}">
									<div class="tt-description text-center"><h2 class="tt-title mt-10"><a href="#">{{ $subCategory['name_'.app()->getLocale()] }}</a></h2></div>
								</div>
							</div>
							<div class="list_proall" style="padding: 10px;">
								@forelse($subCategory->allTempOrderProduct() as $allproducts)
									@forelse($allproducts->allproducts as $product)
										<div class="list_pro">
											<img src="{!! url('uploads/product/thumb/'.$product->products->image) !!}" alt="{{ $product->products['title_'.app()->getLocale()] }}">
											<p title="{{$product->products['title_'.app()->getLocale()]}}">
												{{ str_limit($product->products['title_'.app()->getLocale()] , 30) }}
											</p>
											<p class="m-3 text-primary">
												{{ $tempOrderQuantity[$product->products->id] ?? "" }}X
												<i id="{{ $tempOrderId[$product->products->id] ?? "" }}" class="icon-02 deleteFromTemp" style="    cursor: pointer;"></i>
											</p>
										</div>
									@empty
									@endforelse
								@empty
								@endforelse
{{--								@forelse($subCategory->allproducts()->with('products')->limit(4)->get() as $product)--}}
{{--									<div class="list_pro"><img src="{{url('assets/images/loader.svg')}}" data-src="{!! url('uploads/product/thumb/'.$product->products->image) !!}" alt="{{ $product->products['title_'.app()->getLocale()] }}"><p title="{{$product->products['title_'.app()->getLocale()]}}">{{ str_limit($product->products['title_'.app()->getLocale()] , 35) }}</p></div>--}}
{{--								@empty--}}
{{--								@endforelse--}}
							</div>
							<div class="tt-col-btn">
								<div class="tt-btn-addtocart w-160" onclick="openBundleProducts({{ $subCategory->id }})">{{ __('webMessage.bundles.ADDorUPDATE') }}</div>
							</div>
						</div>
					@empty
					@endforelse
				</div>
			</div>
			@empty
			@endforelse

			<div class="tt-wishlist-box" id="js-wishlist-removeitem">
				<div class="tt-wishlist-list">
					<div class="tt-shopcart-col" style="margin:0;">
						<div class="row">
							<div class="col-md-12 col-lg-12">
								<div class="tt-shopcart-box tt-boredr-large">
									<table class="tt-shopcart-table01">
										@php
											$tempOrdersCount = App\Http\Controllers\webCartController::countTempOrders();
                                            $tempOrders = App\Http\Controllers\webCartController::loadTempOrders();
                                            $subTotalAmount = 0;
                                            $bundleDiscount =  App\Http\Controllers\webCartController::loadTempOrdersBundleDiscount($tempOrders);
										@endphp
										@foreach ($tempOrders as $tempOrder)
											@php
												$subTotalAmount += $tempOrder->quantity * $tempOrder->unit_price;
											@endphp
										@endforeach
										<tbody>
										<tr>
											<th>{{ __('webMessage.subtotal') }}</th>
											<td class="subtotalJS">{{ round($subTotalAmount, 3) }} {{ __('webMessage.kd') }}</td>
										</tr>
										</tbody>
										<tfoot>
										<tr>
											<th>{{ __('webMessage.bundles.BundleDiscount') }}</th>
											<td class="BundleDiscountJS">{{ round($bundleDiscount, 3) }} {{ __('webMessage.kd') }}</td>
										</tr>
										</tfoot>
									</table>
									<a href="{{ url(app()->getLocale() . '/checkout') }}" class="btn btn-lg"><span class="icon icon-check_circle"></span>{{ __('webMessage.checkout') }}</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

		</div>
	</div>
</div>

<!-- modal (quickViewModal) -->
<div class="modal  fade"  id="ModalquickView" tabindex="-1" role="dialog" aria-label="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content ">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="icon icon-clear"></span></button>
			</div>
			<div class="modal-body">
				<div class="tt-modal-quickview desctope">
					<div class="row" id="bundleProducts">

					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal  fade"  id="modalDefaultBox" tabindex="-1" role="dialog" aria-label="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content ">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="icon icon-clear"></span></button>
			</div>
			<div class="modal-body">
				<span id="spancartbox"></span>
			</div>
		</div>
	</div>
</div>
<!--footer-->
@include("website.includes.footer")

<!-- modal (AddToCartProduct) -->
@include("website.includes.addtocart_modal")

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
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

<script>
	var isBundle = true ;
	var bundleLang = '{{ app()->getLocale()  }}';
</script>
<script src="{{url('assets/js/gulfweb.js')}}"></script>
<script>
gtag('event', 'screen_view', {
  'screen_name' : '{{__('webMessage.bundles.Bundle')}}'
});

</script>
</body>
</html>