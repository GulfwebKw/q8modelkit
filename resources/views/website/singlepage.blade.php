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
<title>@if(app()->getLocale()=="en" && !empty($settingInfo->name_en)) {{$settingInfo->name_en}} @elseif(app()->getLocale()=="ar" && !empty($settingInfo->name_ar)) {{$settingInfo->name_ar}} @endif | @if(app()->getLocale()=="en" && !empty($singleInfo->title_en)) {{$singleInfo->title_en}} @elseif(app()->getLocale()=="ar" && !empty($singleInfo->title_ar)) {{$singleInfo->title_ar}} @endif</title>
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
			<li>@if(app()->getLocale()=="en" && !empty($singleInfo->title_en)) {{$singleInfo->title_en}} @elseif(app()->getLocale()=="ar" && !empty($singleInfo->title_ar)) {{$singleInfo->title_ar}} @endif</li>
		</ul>
	</div>
</div>
<div id="tt-pageContent">
	<div class="container-indent">
		<div class="container" style="min-height:800px;">
			<h1 class="tt-title-subpages noborder">@if(app()->getLocale()=="en" && !empty($singleInfo->title_en)) {{$singleInfo->title_en}} @elseif(app()->getLocale()=="ar" && !empty($singleInfo->title_ar)) {{$singleInfo->title_ar}} @endif</h1>
			<div class="tt-login-form">
				<div class="row">
					<div class="col-xs-12 col-md-12">
						<div class="tt-item">
							@if(app()->getLocale()=="en" && !empty($singleInfo->details_en)) {!!$singleInfo->details_en!!} @elseif(app()->getLocale()=="ar" && !empty($singleInfo->details_ar)) {!!$singleInfo->details_ar!!} @endif							
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>

@if ($settingInfo->theme != 17)
<script defer src="{{ url('hakum_assets/js/bundle.js') }}"></script>
@endif
<script src="{{url('assets/js/gulfweb.js')}}"></script>
<script>
gtag('event', 'screen_view', {
  'screen_name' : 'About Us'
});
</script>
</body>
</html>