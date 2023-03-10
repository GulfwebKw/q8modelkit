@php
$settingInfo = App\Http\Controllers\webController::settings();
if(app()->getLocale()=="en"){$strLang="en";}else{$strLang="ar";}
if(!empty(Auth::guard('webs')->user()->is_seller)){$userType=1;}else{$userType=0;}
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>@if(app()->getLocale()=="en") {{$settingInfo->name_en}} @else {{$settingInfo->name_ar}} @endif | {{__('webMessage.shoppingcart')}}</title>
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
			<li>{{__('webMessage.shoppingcart')}}</li>
		</ul>
	</div>
</div>
<div id="tt-pageContent">
	<div class="container-indent">
		<div class="container">
			<h1 class="tt-title-subpages noborder">{{__('webMessage.shoppingcart')}}</h1>
			@if(session('session_msg'))
            <div class="alert-danger">{!!session('session_msg')!!}</div>
            @endif
            @if(!empty($tempOrders) && count($tempOrders)>0)
            <span id="result_reponse_cart"></span>
			<div class="tt-shopcart-table-02">
				<table>
                    <thead class="tt-hidden-mobile">
                    <tr>
                    <th>{{__('webMessage.image')}}</th>
                    <th>{{__('webMessage.details')}}</th>
                    <th>{{__('webMessage.unit_price')}}</th>
                    <th>{{__('webMessage.quantity')}}</th>
                    <th>{{__('webMessage.subtotal')}}</th>
                    <th>{{__('webMessage.remove')}}</th>
                    </tr>
                    </thead>
					<tbody>
                    @php
                    $unitprice=0;
                    $subtotalprice=0;
                    $totalprice=0;
                    $dataLayer = [];
                    @endphp
                    @foreach($tempOrders as $tempOrder)
                    @php
                    $productDetails =App\Http\Controllers\webCartController::getProductDetails($tempOrder->product_id);
                    if($productDetails->image){
                    $prodImage = url('uploads/product/thumb/'.$productDetails->image);
                    }else{
                    $prodImage = url('uploads/no-image.png');
                    }
                    
                    $warrantyTxt='';
                    if(!empty($productDetails->warranty)){
                    $warrantyDetails = App\Http\Controllers\webCartController::getWarrantyDetails($productDetails->warranty);
                    $warrantyTxt     = $strLang=="en"?"<br>".$warrantyDetails->title_en:"<br>".$warrantyDetails->title_ar;
                    }
                                            
                    if(!empty($tempOrder->size_id)){
                    $sizeName =App\Http\Controllers\webCartController::sizeNameStatic($tempOrder->size_id,$strLang);
                    $sizeName = '<li>'.trans('webMessage.size').':'.$sizeName.'</li>';
                    }else{$sizeName='';}
                    if(!empty($tempOrder->color_id)){
                    $colorName =App\Http\Controllers\webCartController::colorNameStatic($tempOrder->color_id,$strLang);
                    $colorName = '<li>'.trans('webMessage.color').':'.$colorName.'</li>';
                    //color image
                    $colorImageDetails = App\Http\Controllers\webCartController::getColorImage($tempOrder->product_id,$tempOrder->color_id);
                    if(!empty($colorImageDetails->color_image)){
                    $prodImage = url('uploads/color/thumb/'.$colorImageDetails->color_image);
                    }
                                            
                    }else{$colorName='';}
					$optionsDetailstxt = App\Http\Controllers\webCartController::getOptionsDtails($tempOrder->id);
                    
                    $unitprice = $tempOrder->unit_price;
                    $subtotalprice = round(($unitprice*$tempOrder->quantity),3);
                    $aquantity = App\Http\Controllers\webCartController::getProductQuantity($tempOrder->product_id,$tempOrder->size_id,$tempOrder->color_id);
                    
                     $dataLayer[] = [
		                    "item_name"     => $productDetails->title_en??'no name',
							"item_id"       => $productDetails->id??'0',
							"price"         => $unitprice,
							"item_brand"    => "",
							"item_category" => "",
							"item_category2"=> "",
							"item_category3"=> "",
							"item_category4"=> "",
							"item_variant"  => $optionsDetailstxt,
							"item_list_name"=> "",
							"item_list_id"  => "",
							"index"         => $tempOrder->id,
							"quantity"      => $tempOrder->quantity??'1',
                            "currency"      => "KWD",
		                    ];
                    @endphp
						<tr id="cart-{{$tempOrder->id}}">
							<td><a href="{{url(app()->getLocale().'/directdetails/'.$productDetails->id.'/'.$productDetails->slug)}}">
								<div class="tt-product-img">
									<img src="{{url('assets/images/loader.svg')}}" data-src="{{$prodImage}}" alt="@if(!empty($productDetails['title_'.$strLang])){{$productDetails['title_'.$strLang]}}@endif">
								</div></a>
							</td>
							<td>
								<h2 class="tt-title">
									<a href="{{url(app()->getLocale().'/directdetails/'.$productDetails->id.'/'.$productDetails->slug)}}">@if(!empty($productDetails['title_'.$strLang])){{$productDetails['title_'.$strLang]}}@endif</a>
								</h2>
								<ul class="tt-list-description">
									{!!$sizeName!!}
									{!!$colorName!!}
									{!!$optionsDetailstxt!!}
                                    {!!$warrantyTxt!!}
								</ul>
								<ul class="tt-list-parameters">
									<li>
										<div class="tt-price">
											 {{$unitprice}}{{__('webMessage.'.$settingInfo->base_currency)}}
										</div>
									</li>
									<li>
										<div class="detach-quantity-mobile"></div>
									</li>
									<li>
										<div class="tt-price subtotal">
											 <span class="subtotal_result{{$tempOrder->id}}">{{$subtotalprice}}</span>{{__('webMessage.'.$settingInfo->base_currency)}}
										</div>
									</li>
								</ul>
							</td>
							<td>
								<div class="tt-price">
									 {{$unitprice}} {{__('webMessage.'.$settingInfo->base_currency)}}
								</div>
							</td>
							<td>
								<div class="detach-quantity-desctope">
									<div class="tt-input-counter style-01">
										<span class="minus-btn" id="{{$tempOrder->id}}"></span>
										<input size="{{$aquantity}}" type="text" value="{{$tempOrder->quantity}}"  name="quantity{{$tempOrder->id}}" id="quantity{{$tempOrder->id}}">
										<span class="plus-btn" id="{{$tempOrder->id}}"></span>
									</div>
								</div>
							</td>
							<td align="center">
								<div class="tt-price subtotal" align="center">
									 <span class="subtotal_result{{$tempOrder->id}}">{{$subtotalprice}}</span> {{__('webMessage.'.$settingInfo->base_currency)}}
								</div>
							</td>
							<td>
								<a href="javascript:;" id="{{$tempOrder->id}}" class="tt-btn-close deleteFromCart"></a>
							</td>
						</tr>
                        @php
                        $totalprice+=$subtotalprice;
                        $productid = $tempOrder->product_id;
                        @endphp
						@endforeach		
                        @php
                        $lastshoppinglink=App\Http\Controllers\webCartController::getShoppingLink($productid);
                        @endphp				
					</tbody>
				</table>

				@php
					$bundleDiscount =  App\Http\Controllers\webCartController::loadTempOrdersBundleDiscount($tempOrders);
					if ( $bundleDiscount > 0 ){
						$totalprice = $totalprice - $bundleDiscount;
					}
				@endphp
				<input type="hidden" name="checkout_totalprice" id="checkout_totalprice" value="{{$totalprice}}">
				<input type="hidden" name="checkout_totalprice" id="checkout_bundleDiscount" value="{{$bundleDiscount}}">
                
				<div class="tt-shopcart-btn">
					<div class="col-left">
						<a class="btn-link" href="{{$lastshoppinglink}}"><i class="icon-e-19"></i>{{strtoupper(__('webMessage.continueshopping'))}}</a>
					</div>
					<div class="col-right">
						<a class="btn-link" href="javascript:;" data-toggle="modal" data-target="#modalCartAlert"><i class="icon-h-02"></i>{{strtoupper(__('webMessage.clearshoppingcart'))}}</a>
					</div>
				</div>
			</div>
			<div class="tt-shopcart-col">
				<div class="row">
					<div class="col-md-6 col-lg-8">
                        <div class="tt-shopcart-box tt-boredr-large">
                                    <div class="tt-collapse-title">{{strtoupper(__('webMessage.checkdeliverycharges'))}}</div>
                                    @php
                                    $areaid=!empty(Cookie::get('area'))?Cookie::get('area'):'0';
                                    $areatxt = App\Http\Controllers\webCartController::get_kuwait_areas($areaid);
                                    @endphp      
                                    <div class="row">
                                    <div class="col-xs-12 col-md-6 col-lg-2"><label for="area">{{__('webMessage.area')}}</label></div>
                                    <div class="col-xs-12 col-md-6 col-lg-6">
                                    <div class="form-group">
                                    <select name="area"  class="form-control area_checkoutcart" id="area_checkout" >
                                    <option value="0">{{__('webMessage.choosearea')}}</option>
                                    {!!$areatxt!!}
                                    </select>
                                    </div>
                                    </div>
                                    </div>
                                    @if(empty($userType))
                                    <div class="tt-collapse-title">{{strtoupper(__('webMessage.applycoupon'))}}</div>
                                    
                                    <div class="row form-default">
                                    <div class="col-xs-12 col-md-4 col-lg-4">
										<div class="form-group">
										<input style="background-color:#FFFFFF;" type="text" name="coupon_code"  class="form-control" id="coupon_code" placeholder="{{__('webMessage.enter_coupon_code')}}" autcomplete="off" value="@if(Cookie::get('gb_coupon_code')) {{Cookie::get('gb_coupon_code')}} @endif">
										</div>
                                    </div>
                                    <div class="col-xs-12 col-md-4 col-lg-4">
										<button style="margin-top:0;" class="btn btn-border applycouponbtn" type="button">{{__('webMessage.apply')}}</button>
                                    </div>
                                    <div class="col-xs-12 col-md-4 col-lg-4">
										<div class="form-group">
										<span id="result_coupon"></span>
										</div>
                                    </div>
                                    </div>
                                  @endif  
                                    
                        </div>
                    </div>
					<div class="col-md-6 col-lg-4">
						<div class="tt-shopcart-box tt-boredr-large" id='checktotalbox'>
							<table class="tt-shopcart-table01">
								<tbody>
									<tr>
										<th>{{strtoupper(__('webMessage.subtotal'))}}</th>
										<td> <span class="total_result">{{$totalprice}}</span> {{__('webMessage.'.$settingInfo->base_currency)}}</td>
									</tr>

									@if ( $bundleDiscount > 0 )
										<tr>
											<th>{{strtoupper(__('webMessage.bundles.BundleDiscount'))}}</th>
											<td style="color: #FF0000;">-{{$bundleDiscount}} {{__('webMessage.'.$settingInfo->base_currency)}}</td>
										</tr>
									@endif
                                    @if(!empty(Cookie::get('gb_coupon_code')) && empty(Cookie::get('gb_coupon_free'))) 
                                    <tr>
										<th>{{strtoupper(__('webMessage.coupon_discount'))}}</th>
										<td><font color="#FF0000">-{{Cookie::get('gb_coupon_discount_text')}}</font></td>
									</tr>
                                   @php
                                   $totalprice=$totalprice-Cookie::get('gb_coupon_discount');
                                   @endphp 
                                   @endif 
                                   
                                   @if(!empty($settingInfo->is_free_delivery) && $totalprice>=$settingInfo->free_delivery_amount)
                                   <tr>
										<th>{{strtoupper(__('webMessage.delivery_charge'))}}</th>
										<td><font color="#FF0000">{{strtoupper(__('webMessage.free_delivery'))}}</font></td>
									</tr>
                                   @else
                                   
                                   @if(!empty(Cookie::get('gb_coupon_code')) && !empty(Cookie::get('gb_coupon_free')))
                                   <tr>
										<th>{{strtoupper(__('webMessage.coupon_discount'))}}</th>
										<td><font color="#FF0000">{{strtoupper(__('webMessage.free_delivery'))}}</font></td>
									</tr>
                                   @endif
                                   @if((!empty(Cookie::get('area')) || !empty($userAddress->area_id)) && empty(Cookie::get('gb_coupon_free')))
                                   @php
							       if(!empty(Cookie::get('area'))){ $areaid = Cookie::get('area'); }else if(!empty($userAddress->area_id)){ $areaid = $userAddress->area_id; }
                                   $deliveryCharge = App\Http\Controllers\webCartController::get_delivery_charge($areaid);
                                   @endphp
                                    <tr>
										<th>{{strtoupper(__('webMessage.delivery_charge'))}}</th>
										<td>{{$deliveryCharge}} {{__('webMessage.'.$settingInfo->base_currency)}}</td>
									</tr>
                                    @php
                                   $totalprice=$totalprice+$deliveryCharge;
                                   @endphp
                                   @endif
                                   @endif


								</tbody>
								<tfoot>
									<tr>
										<th>{{strtoupper(__('webMessage.grandtotal'))}}</th>
										<td><span class="total_result">{{$totalprice}}</span> {{__('webMessage.'.$settingInfo->base_currency)}}</td>
									</tr>
								</tfoot>
							</table>
							<a href="{{url(app()->getLocale().'/checkout')}}" class="btn btn-lg"><span class="icon icon-check_circle"></span>{{strtoupper(__('webMessage.proceedtocheckout'))}}</a>
						</div>
					</div>
				</div>
			</div>
            @else
            <div align="center"><p>{{__('webMessage.yourcartisempty')}}</p></div>
            @endif
		</div>
	</div>
</div>
<!--footer-->
@include("website.includes.footer")
<!--show alert box -->
<div class="modal  fade"  id="modalCartAlert" tabindex="-1" role="dialog" aria-label="modalCartAlert" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content ">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="icon icon-clear"></span></button>
			</div>
			<div class="modal-body">
				<div class="tt-modal-addtocart mobile">
					<div class="tt-modal-messages">
						{{__('webMessage.areyousuretoremove')}}
					</div>
			        <a href="javascript:;" class="btn-link removemycart">{{__('webMessage.remove')}}</a>
				</div>
                {{__('webMessage.areyousuretoremove')}}
                <a href="javascript:;" class="btn-link removemycart">{{__('webMessage.remove')}}</a>
			</div>
		</div>
	</div>
</div>

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
@if ($settingInfo->theme != 17)
<script defer src="{{ url('hakum_assets/js/bundle.js') }}"></script>
@endif
<script src="{{url('assets/js/gulfweb.js')}}"></script>

<script>
$(document).ready(function(){
BASE_URL='';
$(".plus-btn").click(function(){
 var id = $(this).attr("id");
 var quantity = $("#quantity"+id).val();
    $.ajax({
	 type: "GET",
	 url: BASE_URL+"/ajax_change_cart_quantity",
	 data: "type=a&id="+id+"&quantity="+quantity,
	 dataType: "json",
	 cache: false,
	 processData:false,
	 success: function(msg){
	 if(msg.status=="200"){
	 $(".subtotal_result"+id).html(msg.subtotal);
	 $(".total_result").html(msg.total);
	 //$("#result_reponse_cart").html(msg.message);
	 if(msg.status==200){
	 toastr.success(msg.message);
	 }else{
	 toastr.error(msg.message);
	 }
	 }else{
	 //$("#result_reponse_cart").html(msg.message);
	 toastr.error(msg.message);
	 }
	 },
	 error: function(msg){
	 //$("#result_reponse_cart").html('<div class="alert-danger">Something was wrong</div>');
	 toastr.error('Oops! Something went wrong while processing');	 
	 } 
	 });  
 });
 
 //
 $(".minus-btn").click(function(){
 var id = $(this).attr("id");
 var quantity = $("#quantity"+id).val();
    $.ajax({
	 type: "GET",
	 url: BASE_URL+"/ajax_change_cart_quantity",
	 data: "type=m&id="+id+"&quantity="+quantity,
	 dataType: "json",
	 cache: false,
	 processData:false,
	 success: function(msg){
	 if(msg.status=="200"){
	 $(".subtotal_result"+id).html(msg.subtotal);
	 $(".total_result").html(msg.total);
	 //$("#result_reponse_cart").html(msg.message);
	  if(msg.status==200){
	 toastr.success(msg.message);
	 }else{
	 toastr.error(msg.message);
	 }
	 }else{
	 //$("#result_reponse_cart").html(msg.message);
	 toastr.error(msg.message);
	 }
	 },
	 error: function(msg){
	 //$("#result_reponse_cart").html('<div class="alert-danger">Something was wrong</div>');
	 toastr.error('Oops! Something went wrong while processing');	 
	 } 
	 });  
 });
 
});
@if(!empty($dataLayer))
<!--view cart -->
gtag("event", "view_cart", {
  currency: "KWD",
  value: {{number_format($totalprice,2)}},
  items: {!! json_encode($dataLayer) !!}
});
@endif
</script>
<script>
gtag('event', 'screen_view', {
  'screen_name' : 'Shopping Cart'
});
</script>
</body>
</html>