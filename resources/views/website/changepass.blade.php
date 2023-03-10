@php
$settingInfo = App\Http\Controllers\webController::settings();
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>@if(app()->getLocale()=="en") {{$settingInfo->name_en}} @else {{$settingInfo->name_ar}} @endif | {{__('webMessage.changepassword')}}</title>
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
			<li>{{__('webMessage.changepassword')}}</li>
		</ul>
	</div>
</div>
<div id="tt-pageContent">
	<div class="container-indent">
		<div class="container">
			<h1 class="tt-title-subpages noborder">{{__('webMessage.changepassword')}}</h1>
			<div class="tt-login-form">
				<div class="row justify-content-center">
					<div class="col-md-8 col-lg-6">
						<div class="tt-item">
							
							<div class="form-default">
								<form id="customer_reg_form" method="post" action="{{route('changepass',['locale'=>app()->getLocale()])}}" enctype="multipart/form-data">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    
									<div class="form-group">
										<label for="oldpassword">{{__('webMessage.oldpassword')}}*</label>
										<input type="password" name="oldpassword" class="form-control @if($errors->has('oldpassword')) error @endif" id="oldpassword" placeholder="{{__('webMessage.enter_oldpassword')}}" value="{{old('oldpassword')}}">
                                    @if($errors->has('oldpassword'))
                                    <label class="error" for="oldpassword">{{ $errors->first('oldpassword') }}</label>
                                    @endif
									</div>
                                    <div class="form-group">
										<label for="newpassword">{{__('webMessage.newpassword')}}*</label>
										<input type="password" name="newpassword" class="form-control @if($errors->has('newpassword')) error @endif" id="newpassword" placeholder="{{__('webMessage.enter_newpassword')}}" value="{{old('newpassword')}}">
                                    @if($errors->has('newpassword'))
                                    <label class="error" for="newpassword">{{ $errors->first('newpassword') }}</label>
                                    @endif
									</div>
                                    <div class="form-group">
										<label for="confirmpassword">{{__('webMessage.confirmpassword')}}*</label>
										<input type="password" name="confirmpassword" class="form-control @if($errors->has('confirmpassword')) error @endif" id="confirmpassword" placeholder="{{__('webMessage.enter_confirmpassword')}}" value="{{old('confirmpassword')}}">
                                    @if($errors->has('confirmpassword'))
                                    <label class="error" for="confirmpassword">{{ $errors->first('confirmpassword') }}</label>
                                    @endif
									</div>
                                    
									<div class="row">
										<div class="col-auto">
											<div class="form-group">
												<button class="btn btn-border" type="submit">{{__('webMessage.save_changes')}}</button>
											</div>
										</div>
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

</body>
</html>