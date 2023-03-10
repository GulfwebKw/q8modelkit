@php
$settingInfo = App\Http\Controllers\webController::settings();
if(app()->getLocale()=="en"){$strLang="en";}else{$strLang="ar";}
use Illuminate\Support\Facades\Cookie;

if(!empty(Auth::guard('webs')->user()->is_seller)){
$userType=1;
}else{
$userType=0;
}
$pixelids =[];
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>@if(app()->getLocale()=="en") {{$settingInfo->name_en}} @else {{$settingInfo->name_ar}} @endif | {{__('webMessage.checkout')}}</title>
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
<meta name="csrf-token" content="{{csrf_token()}}">
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
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
			<li>{{__('webMessage.checkout')}}</li>
		</ul>
	</div>
</div>
<div id="tt-pageContent">
	<div class="container-indent">
		<div class="container">
			<h1 class="tt-title-subpages noborder">{{__('webMessage.checkout')}}</h1>
			<form style="margin-top:0;" id="checkoutform" class="contact-form" method="post" novalidate action="{{route('checkoutconfirmform',['locale'=>app()->getLocale()])}}">
            <input type="hidden" name="_token" value="{{csrf_token()}}">   
            @if(!empty($tempOrders) && count($tempOrders)>0)
            <div class="tt-collapse-block">
		
		    <div class="row">
            <div class="col-lg-12">
            <a style="padding-left:10px;padding-right:10px;background-color: #FFB900;" href="{{url(app()->getLocale().'/cart')}}" class="btn btn-lg float-{{__('webMessage.align')}}"><span class="icon icon-check_circle"></span>{{strtoupper(__('webMessage.backtoshoppingcart'))}}</a>
            <button style="padding-left:10px;padding-right:10px;" type="submit" class="btn btn-lg float-{{__('webMessage.oalign')}}">{{strtoupper(__('webMessage.orderconfirm'))}}</button>
		    </div>
            </div>
             <!--end shopping cart start -->
@if ($errors->any())
     @foreach ($errors->all() as $error)
         <div class="alert alert-danger">{{$error}}</div>
     @endforeach
 @endif
            <div class="row" style="border:1px #CCCCCC solid;margin-top:20px;padding:20px;border-radius:5px;">
            <div class="col-lg-12">
            <h4 class="tt-collapse-title">{{strtoupper(__('webMessage.shoppingcart'))}}</h4>
            <span id="result_reponse_cart"></span>
            
            <div class="tt-shopcart-table-02">
				<table>
                    <thead class="tt-hidden-mobile">
                    <tr>
                    <th style="border-top:1px #FFFFFF solid;">{{__('webMessage.image')}}</th>
                    <th style="border-top:1px #FFFFFF solid;">{{__('webMessage.details')}}</th>
                    <th style="border-top:1px #FFFFFF solid;">{{__('webMessage.unit_price')}}</th>
                    <th style="border-top:1px #FFFFFF solid;">{{__('webMessage.quantity')}}</th>
                    <th style="border-top:1px #FFFFFF solid;">{{__('webMessage.subtotal')}}</th>
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
                    $pixelids[]=$tempOrder->product_id;
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
                    
					$optionsDetails = App\Http\Controllers\webCartController::getOptionsDtails($tempOrder->id);
                    
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
							"item_variant"  => "",
							"item_list_name"=> "",
							"item_list_id"  => "",
							"index"         => $tempOrder->id,
							"quantity"      => $tempOrder->quantity??'1',
                            "currency"      => "KWD",
		                    ];
                            
                            
                    @endphp
						<tr id="cart-{{$tempOrder->id}}" @if(empty($aquantity)) style="background-color:#FF6633;" @endif >
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
									{!!$optionsDetails!!}
                                    {!!$warrantyTxt!!}
								</ul>
								<ul class="tt-list-parameters">
									<li>
										<div class="tt-price">
											{{__('webMessage.'.$settingInfo->base_currency)}} {{$unitprice}}
										</div>
									</li>
									<li>
										<div class="detach-quantity-mobile"></div>
									</li>
									<li>
										<div class="tt-price subtotal">
											{{__('webMessage.'.$settingInfo->base_currency)}} <span class="subtotal_result{{$tempOrder->id}}">{{$subtotalprice}}</span>
										</div>
									</li>
								</ul>
							</td>
							<td>
								<div class="tt-price">
									{{__('webMessage.'.$settingInfo->base_currency)}} {{$unitprice}}
								</div>
							</td>
							<td>
								<div class="detach-quantity-desctope">
									<div class="tt-input-counter style-01">
										{{$tempOrder->quantity}}
									</div>
								</div>
							</td>
							<td align="center">
								<div class="tt-price subtotal" align="center">
									{{__('webMessage.'.$settingInfo->base_currency)}} <span class="subtotal_result{{$tempOrder->id}}">{{$subtotalprice}}</span>
								</div>
							</td>
							
						</tr>
                        @php
                        $totalprice+=$subtotalprice;
                        @endphp
						@endforeach	
                        					
					</tbody>
				</table>
                @php
                    $bundleDiscount =  App\Http\Controllers\webCartController::loadTempOrdersBundleDiscount($tempOrders);
                    if ( $bundleDiscount > 0 ){
                        $totalprice = $totalprice - $bundleDiscount;
                    }
                    $checktotal = $totalprice;
                @endphp
				<input type="hidden" name="checkout_totalprice" id="checkout_totalprice" value="{{$totalprice}}">
                <input type="hidden" name="checkout_totalprice" id="checkout_bundleDiscount" value="{{$bundleDiscount}}">
			</div>
            <div class="tt-shopcart-col">
				<div class="row">
					<div class="col-md-6 col-lg-8">
                    <div class="tt-shopcart-box tt-boredr-large">
                                    @if(!empty($userType))
                                    @php
                                    $cdate = date("Y-m-d");
                                    $defaultDeliveryDate = date("Y-m-d",strtotime($cdate.'+1 day'));
                                    @endphp
                                    <div class="row form-default">
                                    <div class="col-xs-12 col-md-3 col-lg-3">
										<div class="form-group">
                                        <label>{{__('webMessage.delivery_date')}}*</label>
										<input type="text" name="delivery_date"  class="form-control" id="delivery_date" placeholder="{{__('webMessage.enter_delivery_date')}}" autcomplete="off" value="@if(Cookie::get('gb_delivery_date')){{Cookie::get('gb_delivery_date')}}@else{{$defaultDeliveryDate}}@endif">
										</div>
                                    </div>
                                    <div class="col-xs-12 col-md-3 col-lg-3">
										<div class="form-group">
                                        <label>{{__('webMessage.seller_discount')}}*</label>
										<input type="text" name="seller_discount"  class="form-control" id="seller_discount" placeholder="{{__('webMessage.enter_seller_discount')}}" autcomplete="off" value="@if(Cookie::get('gb_seller_discount')){{Cookie::get('gb_seller_discount')}}@endif">
										</div>
                                    </div>
                                    <div class="col-xs-12 col-md-2 col-lg-2">
                                       <div class="form-group">
										<button style="margin-top:24px;" class="btn btn-border applyselletdiscountbtn" type="button">{{__('webMessage.apply')}}</button>
                                        </div>                                        
                                    </div>
                                    <div class="col-xs-12 col-md-4 col-lg-4">
								
										<span id="result_coupon"></span>
									
                                    </div>
                                    </div>
                                    @else
                                    <div class="tt-collapse-title">{{strtoupper(__('webMessage.applycoupon'))}}</div>
                                    
                                    <div class="row form-default">
                                    <div class="col-xs-12 col-md-4 col-lg-4">
										<div class="form-group">
										<input type="text" name="coupon_code"  class="form-control" id="coupon_code" placeholder="{{__('webMessage.enter_coupon_code')}}" autcomplete="off" value="@if(Cookie::get('gb_coupon_code')){{Cookie::get('gb_coupon_code')}}@endif">
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
										<td>{{__('webMessage.'.$settingInfo->base_currency)}} <span class="total_result">{{$totalprice}}</span></td>
									</tr>


                                    @if ( $bundleDiscount > 0 )
                                        <tr>
                                            <th>{{strtoupper(__('webMessage.bundles.BundleDiscount'))}}</th>
                                            <td style="color: #FF0000;">-{{$bundleDiscount}} {{__('webMessage.'.$settingInfo->base_currency)}}</td>
                                        </tr>
                                    @endif
                                   @if(!empty(Cookie::get('gb_seller_discount')))
                                   @php
                                   $totalprice=$totalprice-Cookie::get('gb_seller_discount');
                                   @endphp
                                   <tr>
										<th>{{strtoupper(__('webMessage.seller_discount'))}}</th>
										<td><font color="#FF0000">-{{__('webMessage.'.$settingInfo->base_currency)}} {{Cookie::get('gb_seller_discount')}}</font></td>
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
										<td>{{__('webMessage.'.$settingInfo->base_currency)}} {{$deliveryCharge}}</td>
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
										<td>{{__('webMessage.'.$settingInfo->base_currency)}} <span class="total_result">{{$totalprice}}</span></td>
									</tr>
								</tfoot>
							</table>
						</div>
					</div>
				</div>
			</div>
                   
            </div>
            </div>
            @if(!empty($settingInfo->min_order_amount) && $settingInfo->min_order_amount>$checktotal)
            <div class="row" style="border:1px #CCCCCC solid;margin-top:20px;padding:20px;border-radius:5px; background-color: #FFE7CE">
            <div class="col-lg-12 text-center">
            <h3>{!!trans('webMessage.minimumordermessage')!!} <font color="#FF6600">{{number_format($settingInfo->min_order_amount,3)}} {{trans('webMessage.kd')}}</font></h3>
            <p>
            <a style="background-color: #FFB900;" href="{{url(app()->getLocale().'/cart')}}" class="btn btn-lg btn-info"><span class="icon icon-check_circle"></span>{{strtoupper(__('webMessage.backtoshoppingcart'))}}</a>
            </p>
            </div>
            </div>
            @else
            <!--end shopping cart start -->
            @if(!empty($userType))
            <div class="row" style="border:1px #CCCCCC solid;margin-top:20px;padding:20px;border-radius:5px;">
            <div class="col-lg-12">
            <!--deliveryaddress start-->
            <h4 class="tt-collapse-title">{{strtoupper(__('webMessage.deliveryaddress'))}}</h4>
                                    @if(empty($userType))
                                    @php
                                    $customerAddress = App\Http\Controllers\webCartController::customerAddress();
                                    @endphp
                                    @if(!empty($customerAddress) && count($customerAddress)>0)
                                    <div class="row">
                                    <div class="col-xs-12 col-md-6 col-lg-4">
                                    <div class="form-group">
                                    <select name="myaddress" id="myaddress" class="form-control" >
                                    <option value="0">{{__('webMessage.chooseaddress')}}</option>
                                    @foreach($customerAddress as $custaddress)
                                    <option value="{{$custaddress->id}}" @if((!empty(Cookie::get('address_id')) && $custaddress->id==Cookie::get('address_id')) || (!empty($address->id) && $custaddress->id==$address->id)) selected @endif > {{$custaddress->title}}</option>
                                    @endforeach
                                    </select>
                                    </div>
                                    </div>
                                    </div>
                                    @endif
                                    @endif
									@php
                                    if(!empty($userType)){
                                    $name = 'Customer';
                                    }else if(old('name')){
                                    $name = old('name');
                                    }elseif(!empty($userDetailsCheckout->name)){
                                    $name = $userDetailsCheckout->name;
                                    }elseif(Cookie::get('name')){
                                    $name = Cookie::get('name');
                                    }
                                    @endphp
            
                                    <div class="row">
                                    <div class="col-xs-12 col-md-6 col-lg-4">
                                    <div class="form-group">
                                    <label for="name">{{__('webMessage.name')}}<span style="color:#FF0000">{{$settingInfo->validate_cust_name==1?'*':''}}</span></label>
									<input type="text" name="name"  class="form-control" id="name" placeholder="{{__('webMessage.enter_name')}}" autcomplete="off" value="{{$name}}">
                                    @if($errors->has('name'))
                                    <label id="name-error" class="error" for="name">{{$errors->first('name')}}</label>
                                    @endif
									</div>
                                    </div>
                                    <div class="col-xs-12 col-md-6 col-lg-4">
                                    <div class="form-group">
                                    <label for="email">{{__('webMessage.email')}}</label>
                                    <input type="text" name="email"  class="form-control" id="email" placeholder="{{__('webMessage.enter_email')}}" autcomplete="off" value="@if(old('email')){{old('email')}}@endif">
                                    @if($errors->has('email'))
                                    <label id="email-error" class="error" for="email">{{$errors->first('email')}}</label>
                                    @endif
                                    </div>
                                    </div>
                                    <div class="col-xs-12 col-md-6 col-lg-4">
                                    <div class="form-group">
                                    <label for="mobile">{{__('webMessage.mobile')}} <span style="color:#FF0000">{{$settingInfo->validate_cust_mob==1?'*':''}}</span>          </label>
                                    <input  type="text" name="mobile"  class="form-control" id="mobile" placeholder="{{__('webMessage.enter_mobile')}}" autcomplete="off" value="@if(old('mobile')){{old('mobile')}}@endif">
                                    @if($errors->has('mobile'))
                                    <label id="mobile-error" class="error" for="mobile">{{$errors->first('mobile')}}</label>
                                    @endif
                                    </div>
                                    </div>
                                    </div>
                                    
                                    
                                    @php
                                    $countryid=0;
                                    $countryLists = App\Http\Controllers\webCartController::get_country($countryid);
                                    $defaultcountry=2;
                                    @endphp       
                                    <div class="row">
                                    <div class="col-xs-12 col-md-6 col-lg-4">
                                    <div class="form-group">
                                    <label for="country">{{__('webMessage.country')}}<font color="#FF0000">*</font></label>
									<select name="country"  class="form-control country_checkout_form" id="country" >
                                    <option value="0">{{__('webMessage.choosecountry')}}</option>
                                    @if(!empty($countryLists) && count($countryLists)>0)
                                    @foreach($countryLists as $countryList)
                                    <option value="{{$countryList->id}}" @if((!empty(old('country')) && old('country')==$countryList->id) || (!empty($address->country_id) && $address->country_id==$countryList->id) || (!empty(Cookie::get('country')) && Cookie::get('country')==$countryList->id) || (!empty($defaultcountry) && $defaultcountry==$countryList->id) ) selected @endif >{{$countryList['name_'.$strLang]}}</option>
                                    @endforeach
                                    @endif
                                    </select>
                                    @if($errors->has('country'))
                                    <label id="country-error" class="error" for="country">{{$errors->first('country')}}</label>
                                    @endif
									</div>
                                    </div>
                                    
                                    <div class="col-xs-12 col-md-6 col-lg-4">
                                    <div class="form-group">
                                    <label for="state">{{__('webMessage.state')}}<font color="#FF0000">*</font></label>
									<select name="state"  class="form-control state_checkout_form" id="state" >
                                    <option value="0">{{__('webMessage.choosestate')}}</option>
                                    @if(!empty(Cookie::get('country')) || !empty(old('country')) || !empty($defaultcountry))
                                    @php
                                    if(!empty(old('country'))){$country_id=old('country');}elseif(!empty(Cookie::get('country'))){$country_id=Cookie::get('country');}else{$country_id=$defaultcountry;}
                                    $stateLists = App\Http\Controllers\webCartController::get_country($country_id);
                                    @endphp
                                    @foreach($stateLists as $stateList)
                                    <option value="{{$stateList->id}}" @if((!empty(old('state')) && old('state')==$stateList->id)) ||(!empty(Cookie::get('state')) && Cookie::get('state')==$stateList->id)) selected @endif >{{$stateList['name_'.$strLang]}}</option>
                                    @endforeach
                                    @endif
                                    </select>
                                    @if($errors->has('state'))
                                    <label id="state-error" class="error" for="state">{{$errors->first('state')}}</label>
                                    @endif
                                    </div>
                                    </div>
                                    <div class="col-xs-12 col-md-6 col-lg-4">
                                    <div class="form-group">
                                    <label for="area">{{__('webMessage.area')}}<font color="#FF0000">*</font></label>
									<select name="area"  class="form-control area_checkout" id="area" >
                                    <option value="0">{{__('webMessage.choosearea')}}</option>
                                    @if(!empty(Cookie::get('state')) || !empty(old('state')) || !empty($address->state_id))
                                    @php
                                    if(!empty(old('state'))){$state_id=old('state');}else{$state_id=Cookie::get('state');}
                                    $areaLists = App\Http\Controllers\webCartController::get_country($state_id);
                                    @endphp
                                    @foreach($areaLists as $areaList)
                                    <option value="{{$areaList->id}}" @if((!empty(old('area')) && old('area')==$areaList->id)) ||(!empty(Cookie::get('area')) && Cookie::get('area')==$areaList->id)) selected @endif >{{$areaList['name_'.$strLang]}}</option>
                                    @endforeach
                                    @endif
                                    </select>
                                    @if($errors->has('area'))
                                    <label id="area-error" class="error" for="area">{{$errors->first('area')}}</label>
                                    @endif
                                    </div>
                                    </div>
                                    </div>
                                    
                
                                   <div class="row">
                                    <div class="col-xs-12 col-md-6 col-lg-4">
                                    <div class="form-group">
                                    <label for="block">{{__('webMessage.block')}}</label>
									<input type="text" name="block"  class="form-control" id="block" placeholder="{{__('webMessage.enter_block')}}" autcomplete="off" value="@if(old('block')){{old('block')}}@endif">
                                    @if($errors->has('block'))
                                    <label id="block-error" class="error" for="block">{{$errors->first('block')}}</label>
                                    @endif
									</div>
                                    </div>
                                    <div class="col-xs-12 col-md-6 col-lg-4">
                                    <div class="form-group">
                                    <label for="street">{{__('webMessage.street')}}</label>
                                    <input type="text" name="street"  class="form-control" id="street" placeholder="{{__('webMessage.enter_street')}}" autcomplete="off" value="@if(old('street')){{old('street')}}@endif">
                                    @if($errors->has('street'))
                                    <label id="street-error" class="error" for="street">{{$errors->first('street')}}</label>
                                    @endif
                                    </div>
                                    </div>
                                    <div class="col-xs-12 col-md-6 col-lg-4">
                                    <div class="form-group">
                                    <label for="avenue">{{__('webMessage.avenue')}}</label>
                                    <input type="text" name="avenue"  class="form-control" id="avenue" placeholder="{{__('webMessage.enter_avenue')}}" autcomplete="off" value="@if(old('avenue')){{old('avenue')}}@endif">
                                    @if($errors->has('avenue'))
                                    <label id="avenue-error" class="error" for="avenue">{{$errors->first('avenue')}}</label>
                                    @endif
                                    </div>
                                    </div>
                                    </div>
                
                
                            <div class="row">
                                    <div class="col-xs-12 col-md-6 col-lg-4">
                                    <div class="form-group">
                                    <label for="house">{{__('webMessage.house')}}</label>
                                    <input type="text" name="house"  class="form-control" id="house" placeholder="{{__('webMessage.enter_house')}}" autcomplete="off" value="@if(old('house')){{old('house')}}@endif">
                                    @if($errors->has('house'))
                                    <label id="house-error" class="error" for="house">{{$errors->first('house')}}</label>
                                    @endif
                                    </div>
                                    </div>
                                    <div class="col-xs-12 col-md-6 col-lg-4">
                                    <div class="form-group">
                                    <label for="floor">{{__('webMessage.floor')}}</label>
									<input type="text" name="floor"  class="form-control" id="floor" placeholder="{{__('webMessage.enter_floor')}}" autcomplete="off" value="@if(old('floor')){{old('floor')}}@endif">
                                    @if($errors->has('floor'))
                                    <label id="floor-error" class="error" for="floor">{{$errors->first('floor')}}</label>
                                    @endif
									</div>
                                    </div>
                                    
                                    <div class="col-xs-12 col-md-6 col-lg-4">
                                    <div class="form-group">
                                    <label for="landmark">{{__('webMessage.landmark')}}</label>
                                    <input type="text" name="landmark"  class="form-control" id="landmark" placeholder="{{__('webMessage.enter_landmark')}}" autcomplete="off" value="@if(old('landmark')){{old('landmark')}}@endif">
                                    @if($errors->has('landmark'))
                                    <label id="landmark-error" class="error" for="landmark">{{$errors->first('landmark')}}</label>
                                    @endif
                                    </div>
                                    </div>
                            </div> 
                 					
                                    @php
                                    $deliverytimeslists = App\Http\Controllers\webCartController::listDeliveryTimes();
                                    @endphp
                                    @if(!empty($deliverytimeslists) && count($deliverytimeslists)>0)
                                    <div class="row">
                                    <div class="col-xs-12 col-md-6 col-lg-4">
                                    <div class="form-group">
                                     <label for="delivery_time">{{__('webMessage.deliverytime')}}</label>
                                    <select name="delivery_time" id="delivery_time" class="form-control" >
                                    <option value="0">{{__('webMessage.choosedeliverytimes')}}</option>
                                    @foreach($deliverytimeslists as $deliverytimeslist)
                                    <option value="{{$deliverytimeslist->id}}" >{{$strLang=="en"?$deliverytimeslist->title_en:$deliverytimeslist->title_ar}} </option>
                                    @endforeach
                                    </select>
                                    </div>
                                    </div>
                                    </div>
                                    @endif        
                                    	 
            <!--end deliveryaddress start-->
            </div>
            </div>
            @else
			<div class="row" style="border:1px #CCCCCC solid;margin-top:20px;padding:20px;border-radius:5px;">
            <div class="col-lg-12">
            <!--deliveryaddress start-->
            <h4 class="tt-collapse-title">{{strtoupper(__('webMessage.deliveryaddress'))}}</h4>
                                    @if(empty($userType))
                                    @php
                                    $customerAddress = App\Http\Controllers\webCartController::customerAddress();
                                    @endphp
                                    @if(!empty($customerAddress) && count($customerAddress)>0)
                                    <div class="row">
                                    <div class="col-xs-12 col-md-6 col-lg-4">
                                    <div class="form-group">
                                    <select name="myaddress" id="myaddress" class="form-control" >
                                    <option value="0">{{__('webMessage.chooseaddress')}}</option>
                                    @foreach($customerAddress as $custaddress)
                                    <option value="{{$custaddress->id}}" @if((!empty(Cookie::get('address_id')) && $custaddress->id==Cookie::get('address_id')) || (!empty($address->id) && $custaddress->id==$address->id)) selected @endif >{{$custaddress->title}}</option>
                                    @endforeach
                                    </select>
                                    </div>
                                    </div>
                                    </div>
                                    @endif
                                    @endif
									@php
                                    $name='';
                                    if(!empty($userType)){
                                    $name = 'Customer';
                                    }else if(old('name')){
                                    $name = old('name');
                                    }elseif(!empty($userDetailsCheckout->name)){
                                    $name = $userDetailsCheckout->name;
                                    }elseif(Cookie::get('name')){
                                    $name = Cookie::get('name');
                                    }
                                    @endphp
            
                                    <div class="row">
                                    <div class="col-xs-12 col-md-6 col-lg-4">
                                    <div class="form-group">
                                    <label for="name">{{__('webMessage.name')}}<span style="color:#FF0000">{{$settingInfo->validate_cust_name==1?'*':''}}</span> </label>
									<input type="text" name="name"  class="form-control" id="name" placeholder="{{__('webMessage.enter_name')}}" autcomplete="off" value="{{$name}}">
                                    @if($errors->has('name'))
                                    <label id="name-error" class="error" for="name">{{$errors->first('name')}}</label>
                                    @endif
									</div>
                                    </div>
                                    <div class="col-xs-12 col-md-6 col-lg-4">
                                    <div class="form-group">
                                    <label for="email">{{__('webMessage.email')}}</label>
                                    <input type="text" name="email"  class="form-control" id="email" placeholder="{{__('webMessage.enter_email')}}" autcomplete="off" value="@if(old('email')){{old('email')}}@elseif(!empty($userDetailsCheckout->email)){{$userDetailsCheckout->email}}@elseif(Cookie::get('email')){{Cookie::get('email')}}@endif">
                                    @if($errors->has('email'))
                                    <label id="email-error" class="error" for="email">{{$errors->first('email')}}</label>
                                    @endif
                                    </div>
                                    </div>
                                    <div class="col-xs-12 col-md-6 col-lg-4">
                                    <div class="form-group">
                                    <label for="mobile">{{__('webMessage.mobile')}}   <span style="color:#FF0000">{{$settingInfo->validate_cust_mob==1?'*':''}}</span>    </label>
                                    <input maxlength="8" size="8" type="text" name="mobile"  class="form-control" id="mobile" placeholder="{{__('webMessage.enter_mobile')}}" autcomplete="off" value="@if(old('mobile')){{old('mobile')}}@elseif(!empty($userDetailsCheckout->mobile)){{$userDetailsCheckout->mobile}}@elseif(Cookie::get('mobile')){{Cookie::get('mobile')}}@endif">
                                    @if($errors->has('mobile'))
                                    <label id="mobile-error" class="error" for="mobile">{{$errors->first('mobile')}}</label>
                                    @endif
                                    </div>
                                    </div>
                                    </div>
                                    
                                    
                                    @php
                                    $areaid  = !empty(Cookie::get('area'))?Cookie::get('area'):(!empty($userDetailsCheckout->area)?$userDetailsCheckout->area:'0');
                                    $areatxt = App\Http\Controllers\webCartController::get_kuwait_areas($areaid);
                                    @endphp       
                                    <div class="row">
                                    
                                    <div class="col-xs-12 col-md-6 col-lg-3">
                                    <div class="form-group">
                                    <label for="area">{{__('webMessage.area')}}<span style="color:#FF0000">{{$settingInfo->validate_cust_area==1?'*':''}}</span></label>
									<select name="area"  class="form-control area_checkout" id="area" >
                                     <option selected="true" disabled="disabled">{{__('webMessage.choosearea')}}</option>  
                                        @if ($settingInfo->validate_cust_area!=1)
                                        <option value="0">None</option>
                                        @endif
                                    {!!$areatxt!!}
                                    </select>
                                    @if($errors->has('area'))
                                    <label id="area-error" class="error" for="area">{{$errors->first('area')}}</label>
                                    @endif
                                    </div>
                                    </div>
                                    <div class="col-xs-12 col-md-6 col-lg-3">
                                    <div class="form-group">
                                    <label for="block">{{__('webMessage.block')}}<span style="color:#FF0000">{{$settingInfo->validate_cust_block==1?'*':''}}</span></label>
									<input type="text" name="block"  class="form-control" id="block" placeholder="{{__('webMessage.enter_block')}}" autcomplete="off" value="@if(old('block')){{old('block')}}@elseif(!empty($address->block)){{$address->block}}@elseif(Cookie::get('block')){{Cookie::get('block')}}@endif">
                                    @if($errors->has('block'))
                                    <label id="block-error" class="error" for="block">{{$errors->first('block')}}</label>
                                    @endif
									</div>
                                    </div>
                                    <div class="col-xs-12 col-md-6 col-lg-3">
                                    <div class="form-group">
                                    <label for="street">{{__('webMessage.street')}}<span style="color:#FF0000">{{$settingInfo->validate_cust_st==1?'*':''}}</span></label>
                                    <input type="text" name="street"  class="form-control" id="street" placeholder="{{__('webMessage.enter_street')}}" autcomplete="off" value="@if(old('street')){{old('street')}}@elseif(!empty($address->street)){{$address->street}}@elseif(Cookie::get('street')){{Cookie::get('street')}}@endif">
                                    @if($errors->has('street'))
                                    <label id="street-error" class="error" for="street">{{$errors->first('street')}}</label>
                                    @endif
                                    </div>
                                    </div>
                                    
                                    <div class="col-xs-12 col-md-6 col-lg-3">
                                    <div class="form-group">
                                    <label for="avenue">{{__('webMessage.avenue')}}</label>
                                    <input type="text" name="avenue"  class="form-control" id="avenue" placeholder="{{__('webMessage.enter_avenue')}}" autcomplete="off" value="@if(old('avenue')){{old('avenue')}}@elseif(!empty($address->avenue)){{$address->avenue}}@elseif(Cookie::get('avenue')){{Cookie::get('avenue')}}@endif">
                                    @if($errors->has('avenue'))
                                    <label id="avenue-error" class="error" for="avenue">{{$errors->first('avenue')}}</label>
                                    @endif
                                    </div>
                                    </div>
                                    
                                    </div>
                                    
                
                                   <div class="row">
                                    
                                    <div class="col-xs-12 col-md-6 col-lg-3">
                                    <div class="form-group">
                                    <label for="house">{{__('webMessage.house')}}<span style="color:#FF0000">{{$settingInfo->validate_cust_hno==1?'*':''}}</span></label>
                                    <input type="text" name="house"  class="form-control" id="house" placeholder="{{__('webMessage.enter_house')}}" autcomplete="off" value="@if(old('house')){{old('house')}}@elseif(!empty($address->house)){{$address->house}}@elseif(Cookie::get('house')){{Cookie::get('house')}}@endif">
                                    @if($errors->has('house'))
                                    <label id="house-error" class="error" for="house">{{$errors->first('house')}}</label>
                                    @endif
                                    </div>
                                    </div>
                                    <div class="col-xs-12 col-md-6 col-lg-3">
                                    <div class="form-group">
                                    <label for="floor">{{__('webMessage.floor')}}</label>
									<input type="text" name="floor"  class="form-control" id="floor" placeholder="{{__('webMessage.enter_floor')}}" autcomplete="off" value="@if(old('floor')){{old('floor')}}@elseif(!empty($address->floor)){{$address->floor}}@elseif(Cookie::get('floor')){{Cookie::get('floor')}}@endif">
                                    @if($errors->has('floor'))
                                    <label id="floor-error" class="error" for="floor">{{$errors->first('floor')}}</label>
                                    @endif
									</div>
                                    </div>
                                    
                                    <div class="col-xs-12 col-md-6 col-lg-3">
                                    <div class="form-group">
                                    <label for="landmark">{{__('webMessage.landmark')}}</label>
                                    <input type="text" name="landmark"  class="form-control" id="landmark" placeholder="{{__('webMessage.enter_landmark')}}" autcomplete="off" value="@if(old('landmark')){{old('landmark')}}@elseif(Cookie::get('landmark')){{Cookie::get('landmark')}}@endif">
                                    @if($errors->has('landmark'))
                                    <label id="landmark-error" class="error" for="landmark">{{$errors->first('landmark')}}</label>
                                    @endif
                                    </div>
                                    </div>
                                    @php
                                    $deliverytimeslists = App\Http\Controllers\webCartController::listDeliveryTimes();
                                    @endphp
                                    @if(!empty($deliverytimeslists) && count($deliverytimeslists)>0)
                            
                                    <div class="col-xs-12 col-md-6 col-lg-3">
                                    <div class="form-group">
                                     <label for="delivery_time">{{__('webMessage.deliverytime')}}</label>
                                    <select name="delivery_time" id="delivery_time" class="form-control" >
                                    <option value="0">{{__('webMessage.choosedeliverytimes')}}</option>
                                    @foreach($deliverytimeslists as $deliverytimeslist)
                                    <option value="{{$deliverytimeslist->id}}" >{{$strLang=="en"?$deliverytimeslist->title_en:$deliverytimeslist->title_ar}} </option>
                                    @endforeach
                                    </select>
                                    </div>
                                    </div>
                                
                                    @endif
                            </div> 
                            
                                    
                                    
                         <!--register my account -->
						 @if(empty(Auth::guard('webs')->user()))
						 <div class="row">
                                    <div class="col-xs-12 col-md-6 col-lg-4">
									<div class="form-group"><br clear='all'>
                                    <div class="checkbox-group">
									<input type="checkbox" id="register_me" name="register_me" @if(old('register_me')) checked @endif  value="1">
									<label for="register_me"><span class="check"></span><span class="box"></span>&nbsp;{{__('webMessage.createanaccount')}}</label>
								    </div>
                                    </div>
									</div>
                                    <div class="col-xs-12 col-md-6 col-lg-4">
                                    <div class="form-group"  @if(old('register_me')) style="display:block;" @else id="username_box" @endif >
									<input type="text" name="username"  class="form-control" id="username" placeholder="{{__('webMessage.username')}}" autcomplete="off" value="@if(old('username')){{old('username')}}@endif">
                                    @if($errors->has('username'))
                                    <label id="username-error" class="error" for="username">{{$errors->first('username')}}</label>
                                    @endif
									</div>
                                    </div>
                                    
                                    <div class="col-xs-12 col-md-6 col-lg-4">
                                    <div class="form-group"  @if(old('register_me')) style="display:block;" @else id="password_box" @endif>
                                    <input type="password" name="password"  class="form-control" id="password" placeholder="{{__('webMessage.password')}}" autcomplete="off" value="@if(old('password')){{old('password')}}@endif">
                                    @if($errors->has('password'))
                                    <label id="password-error" class="error" for="password">{{$errors->first('password')}}</label>
                                    @endif
                                    </div>
                                    </div>
                            </div> 
							@endif
                         <!-- end register -->						 
            <!--end deliveryaddress start-->
            </div>
            </div>
            
            @endif			
            <!-- payment start -->
            <div class="row" style="border:1px #CCCCCC solid;margin-top:20px;padding:20px;border-radius:5px;">
            <div class="col-lg-12">
            <h4 class="tt-collapse-title mb-3">{{strtoupper(__('webMessage.paymentmethod'))}}</h4>
                            @if(!empty($settingInfo->payments))
                            @php
                            $payments = explode(",",$settingInfo->payments);
							$p=1;
                            @endphp
                            
                            <div class="row">
                                    @php $paytxt=''; @endphp
                                    @foreach($payments as $payment)
                                    @php
                                    if($payment=='COD'){
                                    $paytxt = trans('webMessage.payment_COD');
                                    }else if($payment=='KNET'){
                                    $paytxt = trans('webMessage.payment_KNET');
                                    }else if($payment=='TPAY'){
                                    $paytxt = trans('webMessage.payment_TPAY');
                                    }else if($payment=='GKNET'){
                                    $paytxt = trans('webMessage.payment_GKNET');
                                    }else if($payment=='GTPAY'){
                                    $paytxt = trans('webMessage.payment_GTPAY');
                                    }else if($payment=='TAH'){
                                    $paytxt = trans('webMessage.payment_TAH');
                                    }else if($payment=='MF'){
                                    $paytxt = trans('webMessage.payment_MF');
                                    }else if($payment=='PAYPAL'){
                                    $paytxt = trans('webMessage.payment_PAYPAL');
                                    }else if($payment=='POSTKNET'){
                                    $paytxt = trans('webMessage.payment_POSTKNET');
                                    }else if($payment=='CS'){
                                    $paytxt = trans('webMessage.payment_CS');
                                    }else if($payment=='Q8LINK'){
                                    $paytxt = trans('webMessage.payment_Q8LINK');
                                    }
                                    @endphp
                                    <div class="col-xs-12 col-md-6 col-lg-4">
                                    <div class="form-group">
                                    <label class="radio" for="{{$payment}}"><input @if($p==1) checked @endif type="radio" name="payment_method"  id="{{$payment}}"  value="{{$payment}}"><span class="outer"><span class="inner"></span></span><img src="{{url('uploads/paymenticons/'.strtolower($payment).'.png')}}" height="30" alt="{{__('webMessage.payment_'.$payment)}}">&nbsp;{{$paytxt}}</label>
									
									</div>
                                    </div>
									@php $p++;@endphp
                                    @endforeach
                                    </div>
                                    
                                    @endif
            </div>
            </div>
            <!--end payment end -->
            
                        <div class="row">
                            <div class="col-lg-12">
                            <a style="padding-left:10px;padding-right:10px;background-color: #FFB900;" href="{{url(app()->getLocale().'/cart')}}" class="btn btn-lg btn-info float-{{__('webMessage.align')}}"><span class="icon icon-check_circle"></span>{{strtoupper(__('webMessage.backtoshoppingcart'))}}</a>
                            <button  style="padding-left:10px;padding-right:10px;"  type="submit" class="confirmcheckbutton btn btn-lg  checkLayer float-{{__('webMessage.oalign')}}">{{strtoupper(__('webMessage.orderconfirm'))}}<img id="loader-details-gif" src="{{url('assets/images/loader.svg')}}" style="position:absolute;margin-left:2%;display:none;margin-top:-1px;"></button>
							</div>
                            </div>
                            
                            
           @endif               
                            
                    
                            
                            
                            
                            
                            
						</div>
            @else
            <div align="center"><p>{{__('webMessage.yourcartisempty')}}</p></div>
            @endif
			</form>
		</div>
	</div>
</div>
<!--footer-->
@include("website.includes.footer")
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
@if ($settingInfo->theme != 17)
<script defer src="{{ url('hakum_assets/js/bundle.js') }}"></script>
@endif
<script src="{{url('assets/js/gulfweb.js')}}"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
  <!--end pixel code -->
  $(function() {
    $("#delivery_date").datepicker({dateFormat:"yy-mm-dd"});
  });
</script>
<script>
@if(Cookie::get('latitude') && Cookie::get('longitude'))
getAddress({{Cookie::get('latitude')}}, {{Cookie::get('longitude')}})
@else
initGeolocation();
@endif
function getAddress(latitude,longitude) {//console.log(latitude,longitude)
    return new Promise(function (resolve, reject) {
        var request = new XMLHttpRequest();
        var method = 'GET';
        var url = 'https://api.tomtom.com/search/2/reverseGeocode/'+latitude+','+longitude+'.json?returnRoadUse=true&key=GhtGgtGebr8bs9J92nDIb6JsLHK0hZpU';
        var async = true;
        request.open(method, url, async);
        request.onreadystatechange = function () {
            if (request.readyState == 4) {
                if (request.status == 200) {
                    var data = JSON.parse(request.responseText);
                    var address = data.addresses[0]['address']['municipality']; //console.log(address);
					if(address!="" || address!=null || address !=''){
					///$("#landmark").val(address);	
					}
                   // resolve(address);
                }
                else {
                    reject(request.status);
                }
            }
        };
        request.send();
    });
};

///end parent jquery
   function initGeolocation()
     {
        if( navigator.geolocation )
        {
           navigator.geolocation.getCurrentPosition( success, fail );
        }
		else
        {
		   console.log("Sorry, your browser does not support geolocation services.");
        }
     }

   function success(position)
     {
		 var BASE_URL ="";
		 if(position.coords.longitude!="" && position.coords.latitude!=""){
		 $.ajax({
		 type: "GET",
		 url: BASE_URL+"/ajax_post_latlong",
		 data: "longitude="+position.coords.longitude+"&latitude="+position.coords.latitude,
		 dataType: "json",
		 cache: false,
		 processData:false,
		 success: function(msg){
		 console.log("Latitude and Longitude are saved");
		 },
		 error: function(msg){
		 console.log("Error found while saving Latitude & Longitude");	 
		 } 
		 });  
		 }
     }

     function fail()
     {
        console.log("Sorry, your browser does not support geolocation services.");
     }
	 
@if(!empty($dataLayer))
$(document).on("click",".checkLayer",function(){
<!--view cart -->
dataLayer.push({ ecommerce: null });  // Clear the previous ecommerce object.
dataLayer.push({
  event: "add_shipping_info",
  ecommerce: {
      value: {{$totalprice}},
      currency: "KWD",
      coupon: "{{$orderDetails->coupon_code??''}}",
	  shipping_tier: "Ground",
      items: {!! json_encode($dataLayer) !!}
  }
});

});
@endif
</script>
<script>
gtag('event', 'screen_view', {
  'screen_name' : 'checkout'
});
</script>
</body>
</html>