@php
$settingInfo = App\Http\Controllers\webController::settings();
if(app()->getLocale()=="en"){$strLang="en";}else{$strLang="ar";}
use Illuminate\Support\Facades\Cookie;
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>@if(app()->getLocale()=="en") {{$settingInfo->name_en}} @else {{$settingInfo->name_ar}} @endif | {{__('webMessage.orderdetails')}}</title>
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
            <li><a href="{{url(app()->getLocale().'/myorders')}}">{{__('webMessage.myorders')}}</a></li>
			<li>{{__('webMessage.orderdetails')}}</li>
            <li>{{Request()->orderid}}</li>
		</ul>
	</div>
</div>
<div id="tt-pageContent">
	<div class="container-indent">
		<div class="container">
			<h1 class="tt-title-subpages noborder">{{__('webMessage.orderdetails')}} <a target="_blank" href="{{url(app()->getLocale().'/order-print/'.Request()->orderid)}}" class="btn-link float-right"><i class="icon-g-55"></i>{{__('webMessage.print')}}</a></h1>
            @if(!empty($orderLists) && count($orderLists)>0)
            <div class="tt-collapse-block">
            @if(session('session_msg'))
            <div class="alert-success">{{session('session_msg')}}</div>
            @endif
			@if(session('session_msg_error'))
            <div class="alert-danger">{{session('session_msg_error')}}</div>
            @endif
            
            
            
             <!-- order history -->
                          @if(!empty($trackLists) && count($trackLists)>0)
                           <div class="row" style="border:1px #CCCCCC solid;margin-top:20px;padding:20px;border-radius:5px;">
                             <div class="col-lg-12">
								<h4 class="tt-collapse-title">{{strtoupper(__('webMessage.trackhistory'))}}</h4>
								
                                
                                  @foreach($trackLists as $trackList)
                                    <div class="row" @if(empty($trackList->is_seen)) style="font-weight:bold; color:#0066FF;" @endif>
                                    <div class="col-xs-12 col-md-2 col-lg-2"><div class="form-group"><h4 class="tt-title"><a href="javascript:;">{{$trackList->details_date}}</a></h4></div></div>
                                    <div class="col-xs-12 col-md-10 col-lg-10"><div class="form-group">@if(app()->getLocale()=="en") {!!$trackList->details_en!!} @else {!!$trackList->details_ar!!} @endif</div></div>
                                        
                                        
                                    </div>
                                    @php
                                    $u=App\Http\Controllers\webCartController::updateSeendStatus($trackList->id);
                                    @endphp
                                    @endforeach
                                
                                </div>
                           </div>   
                           @endif  
                           <!--end order history -->
                           
                           
			          <!-- order status details -->
                      
						<div class="row" style="border:1px #CCCCCC solid;margin-top:20px;padding:20px;border-radius:5px;">
                                    <div class="col-xs-12 col-md-3 col-lg-2">
                                    <div class="form-group">
                                    <label for="orderid">{{__('webMessage.orderid')}} : @if(!empty($orderDetails->order_id)) {{$orderDetails->order_id}}@else -- @endif</label>
									</div>
                                    </div>
                                    <div class="col-xs-12 col-md-3 col-lg-2">
                                    <div class="form-group">
                                    <label for="paymentmethod">{{__('webMessage.paymentmethod')}} : @if(!empty($orderDetails->pay_mode)) {{__($orderDetails->pay_mode)}}@else -- @endif</label>
                                    </div>
                                    </div>
                                    <div class="col-xs-12 col-md-3 col-lg-2">
                                    <div class="form-group">
                                    <label for="is_paid">{{__('webMessage.payment_status')}} : @if(!empty($orderDetails->is_paid)) <font color="#009900">{{strtoupper(__('webMessage.paid'))}}</font>@else <font color="#FF0000">{{strtoupper(__('webMessage.notpaid'))}}</font> @endif</label>
                                    </div>
                                    </div>
                                    @php 
									 if(!empty($orderDetails->order_status) && $orderDetails->order_status=='completed'){$color='#009900';}else{$color='#ff0000';}
									 @endphp
                                    <div class="col-xs-12 col-md-3 col-lg-2">
                                    <div class="form-group">
                                    <label for="order_status">{{__('webMessage.order_status')}} : <font color="{{$color}}">{{strtoupper(__('webMessage.'.$orderDetails->order_status))}}</font></label>
                                    </div>
                                    </div>
                                    <div class="col-xs-12 col-md-3 col-lg-2">
                                    <div class="form-group">
                                    <label for="date">{{__('webMessage.date')}} : {{$orderDetails->created_at}}</label>
                                    </div>
                                    </div>
                                    @if(!empty($orderDetails->delivery_time_en) && !empty($orderDetails->delivery_time_ar))
                                    <div class="col-xs-12 col-md-3 col-lg-2">
                                    <div class="form-group">
                                    <label for="date">{{__('webMessage.deliverytime')}} : {{$strLang=="en"?$orderDetails->delivery_time_en:$orderDetails->delivery_time_ar}}</label>
                                    </div>
                                    </div>
                                    @endif
                        </div>		
					  
                      <!-- order status details end -->
                           
                            <!--shopping cart start -->
                <div class="row" style="border:1px #CCCCCC solid;margin-top:20px;padding:20px;border-radius:5px;">
                    <div class="col-lg-12">
                        
                  <div class="tt-shopcart-table-02">
				<table>
                    <thead class="tt-hidden-mobile">
                    <tr>
                    <th style="border-top:1px solid #fff;">{{__('webMessage.image')}}</th>
                    <th style="border-top:1px solid #fff;">{{__('webMessage.details')}}</th>
                    <th style="border-top:1px solid #fff;">{{__('webMessage.unit_price')}}</th>
                    <th style="border-top:1px solid #fff;">{{__('webMessage.quantity')}}</th>
                    <th style="border-top:1px solid #fff;">{{__('webMessage.subtotal')}}</th>
                    </tr>
                    </thead>
					<tbody>
                    @if(!empty($orderLists) && count($orderLists)>0)
                    @php
                    $unitprice=0;
                    $subtotalprice=0;
                    $totalprice=0;
                    @endphp
                    @foreach($orderLists as $orderList)
                    @php
                    $productDetails =App\Http\Controllers\webCartController::getProductDetails($orderList->product_id);
                    
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
                    
                    
                    if(!empty($orderList->size_id)){
                    $sizeName =App\Http\Controllers\webCartController::sizeNameStatic($orderList->size_id,$strLang);
                    $sizeName = '<li>'.trans('webMessage.size').':'.$sizeName.'</li>';
                    }else{$sizeName='';}
                    if(!empty($orderList->color_id)){
                    $colorName =App\Http\Controllers\webCartController::colorNameStatic($orderList->color_id,$strLang);
                    $colorName = '<li>'.trans('webMessage.color').':'.$colorName.'</li>';
                    //color image
                    $colorImageDetails = App\Http\Controllers\webCartController::getColorImage($orderList->product_id,$orderList->color_id);
                    if(!empty($colorImageDetails->color_image)){
                    $prodImage = url('uploads/color/thumb/'.$colorImageDetails->color_image);
                    }
                    }else{$colorName='';}
					$optionsdetails = App\Http\Controllers\webCartController::getOptionsDtailsOrder($orderList->id);
					
                    $unitprice = $orderList->unit_price;
                    $subtotalprice = $unitprice*$orderList->quantity;
                    
                    @endphp
						<tr id="cart-{{$orderList->id}}">
							<td>
                            @if(!empty($productDetails->id))
                            <a href="{{url(app()->getLocale().'/directdetails/'.$productDetails->id.'/'.$productDetails->slug)}}">
								<div class="tt-product-img">
									<img src="{{url('assets/images/loader.svg')}}" data-src="{{$prodImage}}" alt="@if(!empty($productDetails['title_'.$strLang])){{$productDetails['title_'.$strLang]}}@endif">
								</div></a>
                                @else
                                <div class="tt-product-img">
									<img src="{{url('assets/images/loader.svg')}}" data-src="{{url('uploads/no-image.png')}}" alt="@if(!empty($productDetails['title_'.$strLang])){{$productDetails['title_'.$strLang]}}@endif">
								</div>
                                @endif
							</td>
							<td>
								<h2 class="tt-title">
                                @if(!empty($productDetails->id))
									<a href="{{url(app()->getLocale().'/directdetails/'.$productDetails->id.'/'.$productDetails->slug)}}">@if(!empty($productDetails['title_'.$strLang])){{$productDetails['title_'.$strLang]}}@endif</a>
                                    @endif
								</h2>
								<ul class="tt-list-description">
									{!!$sizeName!!}
									{!!$colorName!!}
									{!!$optionsdetails!!}
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
											{{__('webMessage.'.$settingInfo->base_currency)}} <span class="subtotal_result{{$orderList->id}}">{{$subtotalprice}}</span>
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
										{{$orderList->quantity}}
									</div>
								</div>
							</td>
							<td align="center">
								<div class="tt-price subtotal" align="center">
									{{__('webMessage.'.$settingInfo->base_currency)}} <span class="subtotal_result{{$orderList->id}}">{{$subtotalprice}}</span>
								</div>
							</td>
							
						</tr>
                        @php
                        $totalprice+=$subtotalprice;
                        @endphp
						@endforeach	
                        @endif
                        					
					</tbody>
				</table>
				
			</div>
            <div class="tt-shopcart-col">
				<div class="row">
					<div class="col-md-6 col-lg-8">
			
			@if(!empty($orderDetails->order_id))
			
			@php
            $TransDetails = App\Http\Controllers\webCartController::TransDetails($orderDetails->order_id);	
			
			@endphp	
            @if(!empty($TransDetails->id))					
						<div class="tt-shopcart-box">
							<h4 class="tt-collapse-title">{{strtoupper(trans('webMessage.transactiondetails'))}}</h4>
                            <br><br>
                            
							    <div class="row">
                                @if(!empty($TransDetails['payment_id']))
					            <div class="col-lg-6">
							    <div class="form-group">
									<label>{{trans('webMessage.paymentid')}} : {{$TransDetails['payment_id']}}</label>
								</div>
								</div>
                                @endif
                                @if(!empty($TransDetails['trackid']))
								<div class="col-lg-6">
							    <div class="form-group">
									<label>{{trans('webMessage.trackid')}} : {{$TransDetails['trackid']}} </label>
								</div>
								</div>
                                @endif
                                @if(!empty($TransDetails['tranid']))
								<div class="col-lg-6">
							    <div class="form-group">
									<label>{{trans('webMessage.transid')}} : {{$TransDetails['tranid']}} </label>
								</div>
								</div>
						
                                @endif
                                @if(!empty($TransDetails['paypal_cart']))
								<div class="col-lg-6">
							    <div class="form-group">
									<label>{{trans('webMessage.transid')}} : {{$TransDetails['paypal_cart']}} </label>
								</div>
								</div>
						
                                @endif
								@php 
                                if(!empty($TransDetails['presult']) && $TransDetails['presult']=='CAPTURED'){$color='#009900';}else{$color='#ff0000';}
                                @endphp
						
					            <div class="col-lg-6">
							    <div class="form-group">
									<label>{{trans('webMessage.result')}} : @if(!empty($TransDetails['presult'])) <font color="{{$color}}"> {{$TransDetails['presult']}} </font> @endif</label>
								</div>
								</div>
                                @if(!empty($TransDetails['udf2']))
								<div class="col-lg-6">
							    <div class="form-group">
									<label>{{trans('webMessage.amount')}} :  {{$TransDetails['udf2'].' '.trans('webMessage.kd')}}</label>
								</div>
								</div>
                                 @endif
                                 @if(!empty($TransDetails['amt_dollar']))
								<div class="col-lg-6">
							    <div class="form-group">
									<label>{{trans('webMessage.amount')}} : <font color="#009900">{{trans('webMessage.usd').''.$TransDetails['amt_dollar']}}</font></label>
								</div>
								</div>
                                 @endif
								<div class="col-lg-6">
							    <div class="form-group">
									<label>{{trans('webMessage.date')}} : @if(!empty($TransDetails['created_at'])) {{$TransDetails['created_at']}} @endif</label>
								</div>
								</div>
								</div>
								
						</div>
						
			         @endif
					 @endif
					</div>
					<div class="col-md-6 col-lg-4">
						<div class="tt-shopcart-box tt-boredr-large">
							<table class="tt-shopcart-table01">
								<tbody>
									<tr>
										<th>{{strtoupper(__('webMessage.subtotal'))}}</th>
										<td>{{__('webMessage.'.$settingInfo->base_currency)}} {{$totalprice}}</td>
									</tr>
                                    @if(!empty($orderDetails->bundle_discount))
                                    <tr>
										<th>{{strtoupper(__('webMessage.bundles.BundleDiscount'))}}</th>
										<td><font color="#FF0000">-{{__('webMessage.'.$settingInfo->base_currency)}} {{$orderDetails->bundle_discount}}</font></td>
									</tr>
                                    @php
                                    $totalprice=$totalprice-$orderDetails->bundle_discount;
                                    @endphp 
                                    @endif
                                    @if(!empty($orderDetails->seller_discount))
                                    <tr>
										<th>{{strtoupper(__('webMessage.seller_discount'))}}</th>
										<td><font color="#FF0000">-{{__('webMessage.'.$settingInfo->base_currency)}} {{$orderDetails->seller_discount}}</font></td>
									</tr>
                                    @php
                                    $totalprice=$totalprice-$orderDetails->seller_discount;
                                    @endphp
                                    @endif
                                   @if(!empty($orderDetails->coupon_code) && empty($orderDetails->coupon_free)) 
                                    <tr>
										<th>{{strtoupper(__('webMessage.coupon_discount'))}}</th>
										<td><font color="#FF0000">-{{__('webMessage.'.$settingInfo->base_currency)}} {{$orderDetails->coupon_amount}}</font></td>
									</tr>
                                   @php
                                   $totalprice=$totalprice-$orderDetails['coupon_amount'];
                                   @endphp 
                                   @endif 
                                   @if(!empty($orderDetails['coupon_code']) && !empty($orderDetails['coupon_free']))
                                   <tr>
										<th>{{strtoupper(__('webMessage.coupon_discount'))}}</th>
										<td><font color="#FF0000">{{strtoupper(__('webMessage.free_delivery'))}}</font></td>
									</tr>
                                   @endif
                                   @if(empty($orderDetails->delivery_charges))
                                   <tr>
										<th>{{strtoupper(__('webMessage.delivery_charge'))}}</th>
										<td><font color="#FF0000">{{strtoupper(__('webMessage.free_delivery'))}}</font></td>
									</tr>
                                   @endif
                                   
                                   @if(!empty($orderDetails['delivery_charges']) && empty($orderDetails['coupon_free']))
                                   @php
                                   $deliveryCharge = $orderDetails['delivery_charges'];
                                   @endphp
                                    <tr>
										<th>{{strtoupper(__('webMessage.delivery_charge'))}}</th>
										<td>{{__('webMessage.'.$settingInfo->base_currency)}} {{$deliveryCharge}}</td>
									</tr>
                                    @php
                                   $totalprice=$totalprice+$deliveryCharge;
                                   @endphp
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
                            <!-- end shopping cart -->
                            
				<div class="row" style="border:1px #CCCCCC solid;margin-top:20px;padding:20px;border-radius:5px;">
                 <div class="col-lg-12">
                 <div class="row">
                                    <div class="col-xs-12 col-md-6 col-lg-4">
                                    <div class="form-group">
                                    <label for="name">{{__('webMessage.name')}} : @if($orderDetails->name) {{$orderDetails->name}}@else -- @endif</label>
									</div>
                                    </div>
                                    <div class="col-xs-12 col-md-6 col-lg-4">
                                    <div class="form-group">
                                    <label for="email">{{__('webMessage.email')}} : @if($orderDetails->email) {{$orderDetails->email}}@else -- @endif</label>
                                    </div>
                                    </div>
                                    <div class="col-xs-12 col-md-6 col-lg-4">
                                    <div class="form-group">
                                    <label for="mobile">{{__('webMessage.mobile')}} : @if($orderDetails->mobile) {{$orderDetails->mobile}}@else -- @endif</label>
                                    </div>
                                    </div>
                                    </div>
                                    
                                    @php
                                    $countryInfo = App\Http\Controllers\webCartController::get_csa_info($orderDetails->country_id);
                                    $stateInfo   = App\Http\Controllers\webCartController::get_csa_info($orderDetails->state_id);
                                    $areaInfo    = App\Http\Controllers\webCartController::get_csa_info($orderDetails->area_id);
                                    @endphp       
                                    <div class="row">
                                    <div class="col-xs-12 col-md-6 col-lg-4">
                                    <div class="form-group">
                                    <label for="country">{{__('webMessage.country')}} : @if($orderDetails->country_id) {{$countryInfo['name_'.$strLang]}}@else -- @endif</label>
									</div>
                                    </div>
                                     
                                    <div class="col-xs-12 col-md-6 col-lg-4">
                                    <div class="form-group">
                                    <label for="state">{{__('webMessage.state')}} : @if($orderDetails->state_id) {{$stateInfo['name_'.$strLang]}}@else -- @endif</label>
									</div>
                                    </div>
                                    <div class="col-xs-12 col-md-6 col-lg-4">
                                    <div class="form-group">
                                    <label for="area">{{__('webMessage.area')}} : @if($orderDetails->area_id) {{$areaInfo['name_'.$strLang]}} @endif</label>
									</div>
                                    </div>
                                    </div>
                                    
                                    <div class="row">
                                    <div class="col-xs-12 col-md-6 col-lg-4">
                                    <div class="form-group">
                                    <label for="block">{{__('webMessage.block')}} : @if($orderDetails->block) {{$orderDetails['block']}}@else -- @endif</label>
									</div>
                                    </div>
                                    <div class="col-xs-12 col-md-6 col-lg-4">
                                    <div class="form-group">
                                    <label for="street">{{__('webMessage.street')}} : @if($orderDetails->street) {{$orderDetails['street']}}@else -- @endif</label>
                                    </div>
                                    </div>
                                    <div class="col-xs-12 col-md-6 col-lg-4">
                                    <div class="form-group">
                                    <label for="avenue">{{__('webMessage.avenue')}} : @if($orderDetails->avenue) {{$orderDetails['avenue']}}@else -- @endif</label>
                                    </div>
                                    </div>
                                    </div>
                                    
                                    <div class="row">
                                    <div class="col-xs-12 col-md-6 col-lg-4">
                                    <div class="form-group">
                                    <label for="house">{{__('webMessage.house')}} : @if($orderDetails['house']) {{$orderDetails['house']}} @else -- @endif</label>
                                    </div>
                                    </div>
                                    <div class="col-xs-12 col-md-6 col-lg-4">
                                    <div class="form-group">
                                    <label for="floor">{{__('webMessage.floor')}} : @if($orderDetails['floor']) {{$orderDetails['floor']}} @else -- @endif</label>
									</div>
                                    </div>
                                    
                                    <div class="col-xs-12 col-md-6 col-lg-4">
                                    <div class="form-group">
                                    <label for="landmark">{{__('webMessage.landmark')}} : @if($orderDetails->landmark) {{$orderDetails['landmark']}} @else -- @endif</label>
                                    </div>
                                    </div>
                                    </div>
                                    
                 </div>
                </div>			
	     </div>
            @else
            <div align="center"><p>{{__('webMessage.norecordfound')}}</p></div>
            @endif
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
<script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

@if ($settingInfo->theme != 17)
<script defer src="{{ url('hakum_assets/js/bundle.js') }}"></script>
@endif
<script src="{{url('assets/js/gulfweb.js')}}"></script>

</body>
</html>