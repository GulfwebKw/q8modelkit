@php
$settingInfo = App\Http\Controllers\webController::settings();
if(app()->getLocale()=="en"){$strLang="en";}else{$strLang="ar";}
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>@if(app()->getLocale()=="en") {{$settingInfo->name_en}} @else {{$settingInfo->name_ar}} @endif | {{__('webMessage.newaddress')}}</title>
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
			<li><a href="{{url(app()->getLocale().'/account')}}">{{__('webMessage.dashboard')}}</a></li>
			<li>{{__('webMessage.newaddress')}}</li>
		</ul>
	</div>
</div>
<div id="tt-pageContent">
	<div class="container-indent">
		<div class="container">
			<h1 class="tt-title-subpages noborder">{{__('webMessage.newaddress')}}</h1>
			<div class="tt-login-form">
				<div class="row justify-content-center">
					<div class="col-md-12 col-lg-12">
						<div class="tt-item">
							
							<div class="form-default">
                            <div class="row">
                               <div class="col-xs-8 col-md-8 col-lg-8">
                                    
								<form id="customer_reg_form" method="post" action="{{route('addressSave',['locale'=>app()->getLocale()])}}" enctype="multipart/form-data">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                   <div class="row">
                                    <div class="col-xs-12 col-md-6 col-lg-4">
                                    <div class="form-group">
                                    <label for="title">{{__('webMessage.title')}}<font color="#FF0000">*</font></label>
									<input type="text" name="title"  class="form-control" id="title" placeholder="{{__('webMessage.enter_title')}}" autcomplete="off" value="@if(old('title')) {{old('title')}} @endif">
                                    @if($errors->has('title'))
                                    <label id="title-error" class="error" for="title">{{ $errors->first('title') }}</label>
                                    @endif
									</div>
                                    </div>
                                    <div class="col-xs-12 col-md-6 col-lg-4">
                                    <div class="form-group">
                                    <label for="latitude">{{__('webMessage.latitude')}}</label>
									<input type="text" name="latitude"  class="form-control" id="latitude" placeholder="{{__('webMessage.enter_latitude')}}" autcomplete="off" value="@if(old('latitude')) {{old('latitude')}} @endif">
                                    @if($errors->has('latitude'))
                                    <label id="block-error" class="error" for="block">{{ $errors->first('latitude') }}</label>
                                    @endif
									</div>
                                    </div>
                                    <div class="col-xs-12 col-md-6 col-lg-4">
                                    <div class="form-group">
                                    <label for="longitude">{{__('webMessage.longitude')}}</label>
									<input type="text" name="longitude"  class="form-control" id="longitude" placeholder="{{__('webMessage.enter_longitude')}}" autcomplete="off" value="@if(old('longitude')) {{old('longitude')}} @endif">
                                    @if($errors->has('longitude'))
                                    <label id="block-error" class="error" for="block">{{ $errors->first('longitude') }}</label>
                                    @endif
									</div>
                                    </div>
                                    </div>
									@php
                                    $countryid=0;
                                    $countryLists = App\Http\Controllers\webCartController::get_country($countryid);
                                    @endphp       
                                    <div class="row">
                                    <div class="col-xs-12 col-md-6 col-lg-4">
                                    <div class="form-group">
                                    <label for="country">{{__('webMessage.country')}}<font color="#FF0000">*</font></label>
									<select name="country"  class="form-control country_checkout" id="country" >
                                    <option value="0">{{__('webMessage.choosecountry')}}</option>
                                    @if(!empty($countryLists) && count($countryLists)>0)
                                    @foreach($countryLists as $countryList)
                                    <option value="{{$countryList->id}}" @if((!empty(old('country')) && old('country')==$countryList->id)) selected @endif>{{$countryList['name_'.$strLang]}}</option>
                                    @endforeach
                                    @endif
                                    </select>
                                    @if($errors->has('country'))
                                    <label id="country-error" class="error" for="country">{{ $errors->first('country') }}</label>
                                    @endif
									</div>
                                    </div>
                                     
                                    <div class="col-xs-12 col-md-6 col-lg-4">
                                    <div class="form-group">
                                    <label for="state">{{__('webMessage.state')}}<font color="#FF0000">*</font></label>
									<select name="state"  class="form-control state_checkout" id="state_checkout" >
                                    <option value="0">{{__('webMessage.choosestate')}}</option>
                                    @if(!empty(old('country')))
                                    @php
                                    if(!empty(old('country'))){$country_id=old('country');}else{$country_id='';}
                                    $stateLists = App\Http\Controllers\webCartController::get_country($country_id);
                                    @endphp
                                    @foreach($stateLists as $stateList)
                                    <option value="{{$stateList->id}}" @if((!empty(old('state')) && old('state')==$stateList->id)) selected @endif>{{$stateList['name_'.$strLang]}}</option>
                                    @endforeach
                                    @endif
                                    </select>
                                    @if($errors->has('state'))
                                    <label id="state-error" class="error" for="state">{{ $errors->first('state') }}</label>
                                    @endif
                                    </div>
                                    </div>
                                    <div class="col-xs-12 col-md-6 col-lg-4">
                                    <div class="form-group">
                                    <label for="area">{{__('webMessage.area')}}<font color="#FF0000">*</font></label>
									<select name="area"  class="form-control" id="area_checkout" >
                                    <option value="0">{{__('webMessage.choosearea')}}</option>
                                    @if(!empty(old('state')))
                                    @php
                                    if(!empty(old('state'))){$state_id=old('state');}else{$state_id=0;}
                                    $areaLists = App\Http\Controllers\webCartController::get_country($state_id);
                                    @endphp
                                    @foreach($areaLists as $areaList)
                                    <option value="{{$areaList->id}}" @if((!empty(old('area')) && old('area')==$areaList->id)) selected @endif>{{$areaList['name_'.$strLang]}}</option>
                                    @endforeach
                                    @endif
                                    </select>
                                    @if($errors->has('area'))
                                    <label id="area-error" class="error" for="area">{{ $errors->first('area') }}</label>
                                    @endif
                                    </div>
                                    </div>
                                    </div>
                                    <div class="row">
                                    <div class="col-xs-12 col-md-6 col-lg-4">
                                    <div class="form-group">
                                    <label for="block">{{__('webMessage.block')}}<font color="#FF0000">*</font></label>
									<input type="text" name="block"  class="form-control" id="block" placeholder="{{__('webMessage.enter_block')}}" autcomplete="off" value="@if(old('block')) {{old('block')}} @endif">
                                    @if($errors->has('block'))
                                    <label id="block-error" class="error" for="block">{{ $errors->first('block') }}</label>
                                    @endif
									</div>
                                    </div>
                                    <div class="col-xs-12 col-md-6 col-lg-4">
                                    <div class="form-group">
                                    <label for="street">{{__('webMessage.street')}}<font color="#FF0000">*</font></label>
                                    <input type="text" name="street"  class="form-control" id="street" placeholder="{{__('webMessage.enter_street')}}" autcomplete="off" value="@if(old('street')) {{old('street')}} @endif">
                                    @if($errors->has('street'))
                                    <label id="street-error" class="error" for="street">{{ $errors->first('street') }}</label>
                                    @endif
                                    </div>
                                    </div>
                                    <div class="col-xs-12 col-md-6 col-lg-4">
                                    <div class="form-group">
                                    <label for="avenue">{{__('webMessage.avenue')}}</label>
                                    <input type="text" name="avenue"  class="form-control" id="avenue" placeholder="{{__('webMessage.enter_avenue')}}" autcomplete="off" value="@if(old('avenue')) {{old('avenue')}} @endif">
                                    @if($errors->has('avenue'))
                                    <label id="avenue-error" class="error" for="avenue">{{ $errors->first('avenue') }}</label>
                                    @endif
                                    </div>
                                    </div>
                                    </div>
                                    
                                    <div class="row">
                                    <div class="col-xs-12 col-md-6 col-lg-4">
                                    <div class="form-group">
                                    <label for="house">{{__('webMessage.house')}}<font color="#FF0000">*</font></label>
                                    <input type="text" name="house"  class="form-control" id="house" placeholder="{{__('webMessage.enter_house')}}" autcomplete="off" value="@if(old('house')) {{old('house')}} @endif">
                                    @if($errors->has('house'))
                                    <label id="house-error" class="error" for="house">{{ $errors->first('house') }}</label>
                                    @endif
                                    </div>
                                    </div>
                                    <div class="col-xs-12 col-md-6 col-lg-4">
                                    <div class="form-group">
                                    <label for="floor">{{__('webMessage.floor')}}</label>
									<input type="text" name="floor"  class="form-control" id="floor" placeholder="{{__('webMessage.enter_floor')}}" autcomplete="off" value="@if(old('floor')) {{old('floor')}} @endif">
                                    @if($errors->has('floor'))
                                    <label id="floor-error" class="error" for="floor">{{ $errors->first('floor') }}</label>
                                    @endif
									</div>
                                    </div>
                                    <div class="col-xs-12 col-md-6 col-lg-4">
                                    <div class="form-group">
                                    <label for="is_default" style="margin-top:10px;"><input type="checkbox" name="is_default"  id="is_default" autcomplete="off" value="1">&nbsp;{{__('webMessage.default_address')}}</label>
									
									</div>
                                    </div>
                                    
                                    </div>
									
									<div class="row">
										<div class="col-auto">
											<div class="form-group">
												<button class="btn btn-border" type="submit">{{__('webMessage.save')}}</button>
											</div>
										</div>
									</div>
                                    @if(session('session_msg'))
                                    <div class="alert-success">{{session('session_msg')}}</div>
                                    @endif
								</form>
                                </div>
                                <div class="col-xs-4 col-md-4 col-lg-4" align="right">
                                 <div id="mapids" style="width:320px;height:400px;"></div>
                                </div>
                                </div>
							</div>
						</div>
					</div>
				</div>
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

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAZDl2BsDI2qPQ0l-eJp5eVXetkFGkO75E&callback=initMap&libraries=&v=weekly" async ></script>
<script>

<!-- map -->
	  function initMap() { 
      const myLatlng = { lat: 29.3117, lng: 47.4818 };
      const map = new google.maps.Map(document.getElementById("mapids"), {
      zoom: 10,
      center: myLatlng
     });

  // Create the initial InfoWindow.
  let infoWindow = new google.maps.InfoWindow({
    content: "Click the map to get Lat/Lng!",
    position: myLatlng
  });
  infoWindow.open(map);
  // Configure the click listener.
  map.addListener("click", (mapsMouseEvent) => {
    // Close the current InfoWindow.
    infoWindow.close();
    // Create a new InfoWindow.
    infoWindow = new google.maps.InfoWindow({
      position: mapsMouseEvent.latLng,
    });
    infoWindow.setContent(
      JSON.stringify(mapsMouseEvent.latLng.toJSON(), null, 2)
    );
	var obj = $.parseJSON(JSON.stringify(mapsMouseEvent.latLng.toJSON(), null, 2));
	if(obj.lat!=""){
	$("#latitude").val(obj.lat)
	}
	if(obj.lng!=""){
	$("#longitude").val(obj.lng)
	}
    infoWindow.open(map);
  });
}


</script>
</body>
</html>