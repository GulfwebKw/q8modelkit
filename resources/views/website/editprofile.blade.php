@php
$settingInfo = App\Http\Controllers\webController::settings();
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>@if(app()->getLocale()=="en") {{$settingInfo->name_en}} @else {{$settingInfo->name_ar}} @endif | {{__('webMessage.editprofile')}}</title>
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
			<li>{{__('webMessage.editprofile')}}</li>
		</ul>
	</div>
</div>
<div id="tt-pageContent">
	<div class="container-indent">
		<div class="container">
			<h1 class="tt-title-subpages noborder">{{__('webMessage.editprofile')}}</h1>
			<div class="tt-login-form">
				<div class="row justify-content-center">
					<div class="col-md-8 col-lg-6">
						<div class="tt-item">
							
							<div class="form-default">
								<form id="customer_reg_form" method="post" action="{{route('editprofileSave',['locale'=>app()->getLocale()])}}" enctype="multipart/form-data">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <h2 class="tt-title">{{__('webMessage.personal_information')}}</h2>
									<div class="form-group">
										<label for="name">{{__('webMessage.name')}}*</label>
										<input type="text" name="name" class="form-control @if($errors->has('name')) error @endif" id="name" placeholder="{{__('webMessage.enter_name')}}" value="@if(Auth::guard('webs')->user()->name) {{Auth::guard('webs')->user()->name}} @else {{old('name')}} @endif">
                                    @if($errors->has('name'))
                                    <label class="error" for="name">{{ $errors->first('name') }}</label>
                                    @endif
									</div>
                                    <div class="form-group">
										<label for="email">{{__('webMessage.email')}}*</label>
										<input type="email" name="email" class="form-control @if($errors->has('email')) error @endif" id="email" placeholder="{{__('webMessage.enter_email')}}"  value="@if(Auth::guard('webs')->user()->email) {{Auth::guard('webs')->user()->email}} @else {{old('email')}} @endif">
                                        @if($errors->has('email'))
                                    <label class="error" for="email">{{ $errors->first('email') }}</label>
                                    @endif
									</div>
                                    <div class="form-group">
										<label for="mobile">{{__('webMessage.mobile')}}*</label>
										<input type="text" name="mobile" class="form-control @if($errors->has('mobile')) error @endif" id="mobile" placeholder="{{__('webMessage.enter_mobile')}}"  value="@if(Auth::guard('webs')->user()->mobile) {{Auth::guard('webs')->user()->mobile}} @else {{old('mobile')}} @endif">
                                        @if($errors->has('mobile'))
                                    <label class="error" for="mobile">{{ $errors->first('mobile') }}</label>
                                    @endif
									</div>
                                    <div class="form-group">
										<label for="image">{{__('webMessage.image')}}</label> @if(Auth::guard('webs')->user()->image) <img src="{{url('uploads/customers/thumb/'.Auth::guard('webs')->user()->image)}}" width="30" class="float-right"> @endif
										<input type="file" name="image" class="form-control @if($errors->has('image')) error @endif" id="image">
                                        @if($errors->has('image'))
                                        <label class="error" for="image">{{ $errors->first('image') }}</label>
                                        @endif
									</div>
                                    <h2 class="tt-title">{{__('webMessage.login_information')}}</h2>
									
									<div class="form-group">
										<label for="username">{{__('webMessage.username')}}*</label>
										<input type="text" name="username" class="form-control @if($errors->has('username')) error @endif" id="username" placeholder="{{__('webMessage.enter_username')}}"  value="@if(Auth::guard('webs')->user()->username) {{Auth::guard('webs')->user()->username}} @else {{old('username')}} @endif">
                                        @if($errors->has('username'))
                                    <label class="error" for="username">{{ $errors->first('username') }}</label>
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