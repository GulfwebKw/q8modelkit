@php
$settingInfo = App\Http\Controllers\webController::settings();
if(!empty(Auth::guard('webs')->user()->is_seller)){
$userType=1;
}else{
$userType=0;
}
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>@if(app()->getLocale()=="en") {{$settingInfo->name_en}} @else {{$settingInfo->name_ar}} @endif | {{__('webMessage.myorders')}}</title>
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
			<li>{{__('webMessage.myorders')}}</li>
		</ul>
	</div>
</div>
<div id="tt-pageContent">
	<div class="container-indent">
		<div class="container">
			<h1 class="tt-title-subpages noborder">{{__('webMessage.myorders')}}</h1>
            @if(!empty($myorderLists) && count($myorderLists)>0)
            <!--order search box start -->
            <form name="myordersSearch_form" id="myordersSearch_form" method="get" action="{{url(app()->getLocale().'/myorders')}}">
            <div class="row">
            <div class="col-lg-4 col-sm-4 col-md-4">
            <div class="form-group">
            <input type="text" name="q"  class="form-control" id="q" placeholder="{{__('webMessage.search_here')}}" autcomplete="off" value="@if(Request()->q){{Request()->q}}@endif">
            </div>
            </div>
            <div class="col-lg-4 col-sm-4 col-md-4">
            <div class="form-group">
            <input type="text" name="filter_date"  class="form-control" id="filter_date" placeholder="{{__('webMessage.date')}}" autcomplete="off" value="@if(Request()->filter_date){{Request()->filter_date}}@endif">
            </div>
            </div>
            <div class="col-lg-4 col-sm-4 col-md-4">
            <div class="form-group">
            <button class="btn btn-border" type="submit">{{__('webMessage.search_btn')}}</button>
            </div>
            </div>
            </div>
            </form>
            <!--order search box end -->
            <p>&nbsp;</p>
			<div class="tt-wishlist-box" id="js-wishlist-removeitem">
            
                <span id="responseMsgOrder"></span>
				<div class="tt-wishlist-list">
                @foreach($myorderLists as $myorderList)
                    @php
                    $getProperties = App\Http\Controllers\webCartController::getMyOrdersProperties($myorderList->id);
                    $sellerDetails = App\Http\Controllers\AdminCustomersController::getCustomerDetails($myorderList->customer_id);
                    @endphp
					<div class="tt-item" id="orderdiv{{$myorderList->id}}">
						<div class="tt-col-title">
							<b>{{__('webMessage.orderid')}} : </b>{{$myorderList->order_id}}
                            <br>
                            <b>{{__('webMessage.name')}} : </b>{{$myorderList->name}}
                            @if($myorderList->mobile)<br><b>{{__('webMessage.mobile')}} : </b>{{$myorderList->mobile}}@endif
                            @if(!empty($sellerDetails) && !empty($sellerDetails->name) && !empty($userType))
                            <br>Seller : {{$sellerDetails->name}}
                           @endif
						</div>
                        
                     
                        <div class="tt-col-title">
							<b>{{__('webMessage.paymentmethod')}} : </b>{{$myorderList->pay_mode}}
							(@if(!empty($myorderList->is_paid)) <font color='#009900'>{{trans('webMessage.paid')}}</font> @else <font color='#ff0000'>{{trans('webMessage.notpaid')}}</font> @endif)
                            <br>
                            <b>{{__('webMessage.order_status')}} : </b><span class="{{$myorderList->order_status}}">{{__('webMessage.'.$myorderList->order_status)}}</span>
                            <br>
                            <b>{{__('webMessage.date')}} : </b>{{$myorderList->created_at}}
							
						</div>
                        <div class="tt-col-title">
							<b>{{__('webMessage.delivery_date')}} : </b>{{$myorderList->delivery_date}}
                            <br>
                            <b>{{__('webMessage.grandtotal')}} : </b>{{__('webMessage.'.$settingInfo->base_currency)}} {{number_format($getProperties['totalAmt'],3)}}
                            {{-- @if(!empty($getProperties['totalAmt']))
                            <br>
                            <b>{{__('webMessage.grandtotal')}} : </b>{{__('webMessage.usd')}} {{number_format($getProperties['totalAmt_dollar'],2)}}
                            @endif --}}
						</div>
						<div class="tt-col-btn">
							<a class="btn-link" href="{{url(app()->getLocale().'/orderdetails/'.$myorderList->order_id)}}"><i class="icon-f-73"></i>{{__('webMessage.details')}}</a>
							<a class="btn-link removemyorder" id="{{$myorderList->id}}" href="javascript:;"><i class="icon-h-02"></i>{{__('webMessage.remove')}}</a>
						</div>
					</div>
                  @endforeach  
                  <div>{{$myorderLists->links()}}</div>					
				</div>
                
			</div>
                @else
                <div class="tt-wishlist-list text-center">{{__('webMessage.norecordfound')}}</div>
                @endif
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
@if ($settingInfo->theme != 17)
<script defer src="{{ url('hakum_assets/js/bundle.js') }}"></script>
@endif
<script src="{{url('assets/js/gulfweb.js')}}"></script>

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
  $(function() {
    $("#filter_date").datepicker({dateFormat:"yy-mm-dd"});
  });
</script>
</body>
</html>