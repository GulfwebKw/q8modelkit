@php
$settingInfo = App\Http\Controllers\webController::settings();
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>@if(app()->getLocale()=="en") {{$settingInfo->name_en}} @else {{$settingInfo->name_ar}} @endif | @if(request()->token) {{__('webMessage.resetforgotpassword')}} @else {{__('webMessage.sendfplink')}} @endif</title>
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
<link rel="stylesheet" href="{{url('assets/css/theme.css')}}">
<link rel="stylesheet" href="{{url('assets/css/gulfweb.css')}}">
@if(app()->getLocale()=="ar")
<link href="{{url('assets/css/rtl.css')}}" rel="stylesheet">
@endif
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
			<li><a href="{{url('/')}}">{{__('webMessage.home')}}</a></li>
			<li>@if(request()->token) {{__('webMessage.resetforgotpassword')}} @else {{__('webMessage.sendfplink')}} @endif</li>
		</ul>
	</div>
</div>
<div id="tt-pageContent">
	<div class="container-indent">
		<div class="container">
			<h1 class="tt-title-subpages noborder">@if(request()->token) {{__('webMessage.resetforgotpassword')}} @else {{__('webMessage.sendfplink')}} @endif</h1>
			<div class="tt-login-form">
				<div class="row justify-content-center">
					<div class="col-md-8 col-lg-6">
						<div class="tt-item">
							
							<div class="form-default">
                             @if(request()->token)
                <form method="post" class="fpass-validation-active" id="fpass-form-main-form" action="{{route('password.token',['locale'=>app()->getLocale(),'token'=>request()->token])}}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                   <div class="form-group">
										<label for="email">{{__('webMessage.email')}}*</label>
										<input type="email" name="email" class="form-control @if($errors->has('email')) error @endif" id="email" placeholder="{{__('webMessage.enter_email')}}"  value="{{old('email')}}">
                                        @if($errors->has('email'))
                                    <label class="error" for="email">{{ $errors->first('email') }}</label>
                                    @endif
									</div>
                                    <div class="form-group">
										<label for="new_password">{{__('webMessage.newpassword')}}*</label>
										<input type="password" name="new_password" class="form-control @if($errors->has('new_password')) error @endif" id="new_password" placeholder="{{__('webMessage.enter_new_password')}}"  value="{{old('new_password')}}">
                                        @if($errors->has('new_password'))
                                    <label class="error" for="new_password">{{ $errors->first('new_password') }}</label>
                                    @endif
									</div>
                                    <div class="form-group">
										<label for="confirm_password">{{__('webMessage.confirmpassword')}}*</label>
										<input type="password" name="confirm_password" class="form-control @if($errors->has('confirm_password')) error @endif" id="confirm_password" placeholder="{{__('webMessage.enter_confirm_password')}}"  value="{{old('confirm_password')}}">
                                        @if($errors->has('confirm_password'))
                                    <label class="error" for="confirm_password">{{ $errors->first('confirm_password') }}</label>
                                    @endif
									</div>
                                    
									<div class="row">
										<div class="col-auto">
											<div class="form-group">
												<button class="btn btn-border" type="submit">{{__('webMessage.save_changes')}}</button>
											</div>
										</div>
										<div class="col-auto align-self-center">
											<div class="form-group">
												<ul class="additional-links">
													<li>{{__('webMessage.or')}} <a href="{{url('/login')}}">{{__('webMessage.returntosignin')}}</a></li>
												</ul>
											</div>
										</div>
									</div>
                </form>
                             @else
								<form method="post" class="fpass-validation-active" id="fpass-form-main-form" action="{{route('password.email',['locale'=>app()->getLocale()])}}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <div class="form-group">
										<label for="email">{{__('webMessage.email')}}*</label>
										<input type="email" name="email" class="form-control @if($errors->has('email')) error @endif" id="email" placeholder="{{__('webMessage.enter_email')}}"  value="{{old('email')}}">
                                        @if($errors->has('email'))
                                    <label class="error" for="email">{{ $errors->first('email') }}</label>
                                    @endif
									</div>
                                    
									<div class="row">
										<div class="col-auto">
											<div class="form-group">
												<button class="btn btn-border" type="submit">{{__('webMessage.send_link_btn')}}</button>
											</div>
										</div>
										<div class="col-auto align-self-center">
											<div class="form-group">
												<ul class="additional-links">
													<li>{{__('webMessage.or')}} <a href="{{url('/login')}}">{{__('webMessage.returntosignin')}}</a></li>
												</ul>
											</div>
										</div>
									</div>
                @if(session('session_msg'))
                <div class="alert alert-success">{{session('session_msg')}}</div>
                @endif
								</form>
                                @endif
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
</body>
</html>