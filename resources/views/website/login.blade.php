@php
$settingInfo = App\Http\Controllers\webController::settings();
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>@if(app()->getLocale()=="en") {{$settingInfo->name_en}} @else {{$settingInfo->name_ar}} @endif | {{__('webMessage.signin')}}</title>
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
			<li>{{__('webMessage.signin')}}</li>
		</ul>
	</div>
</div>
<div id="tt-pageContent">
	<div class="container-indent">
		<div class="container">
			<h1 class="tt-title-subpages noborder">{{__('webMessage.alreadyregistered')}}</h1>
			<div class="tt-login-form">
            @if(session('session_msg'))
            <div class="alert-success">{{session('session_msg')}}</div>
            @endif
				<div class="row">
					<div class="col-xs-12 col-md-6">
						<div class="tt-item">
							<h2 class="tt-title">{{__('webMessage.newcustomer')}}</h2>
							<p>@if(app()->getLocale()=="en") {{$settingInfo->note_for_new_customer_en}} @else {{$settingInfo->note_for_new_customer_ar}} @endif</p>
							<div class="form-group">
								<a href="{{url(app()->getLocale().'/register')}}" class="btn btn-top btn-border">{{__('webMessage.createanaccount')}}</a>
							</div>
						</div>
					</div>
   @php
   use Illuminate\Support\Facades\Cookie;
   @endphp
					<div class="col-xs-12 col-md-6">
						<div class="tt-item">
							<h2 class="tt-title">{{__('webMessage.signin')}}</h2>
                            {{__('webMessage.ifyouhaveanaccountwithus')}}
							<div class="form-default form-top">
                
                
								<form id="customer_login_form" method="post" action="{{route('loginform',['locale'=>app()->getLocale()])}}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
									<div class="form-group">
									<label for="login_username">{{__('webMessage.username_or_email')}}*</label>
									<input type="text" name="login_username"  class="form-control @if($errors->has('login_username')) error @endif" id="login_username" placeholder="{{__('webMessage.enter_username_or_email')}}" autcomplete="off" value="@if(Cookie::get('xlogin_username')) {{Cookie::get('xlogin_username')}} @elseif(old('login_username')) {{old('login_username')}} @endif">
                                    @if($errors->has('login_username'))
                                    <label id="login_username" class="error" for="login_username">{{ $errors->first('login_username') }}</label>
                                    @endif
									</div>
									<div class="form-group">
									<label for="login_password">{{__('webMessage.password_login_txt')}}*</label>
									<input type="password" name="login_password"  class="form-control @if($errors->has('login_password')) error @endif" id="login_password" placeholder="{{__('webMessage.enter_password')}}" autcomplete="off"  value="@if(Cookie::get('xlogin_password')) {{Cookie::get('xlogin_password')}} @elseif(old('login_password')) {{old('login_password')}} @endif">
                                    @if($errors->has('login_password'))
                                    <label id="login_password" class="error" for="login_username">{{ $errors->first('login_password') }}</label>
                                    @endif
									</div>
                                    
                                    <div class="form-group">
                                    <div class="checkbox-group">
									<input type="checkbox" id="remember_me" name="remember_me" @if(Cookie::get('xremember_me')) checked @endif  value="1">
									<label for="remember_me"><span class="check"></span><span class="box"></span>&nbsp;{{__('webMessage.remember_me_txt')}}</label>
								    </div>
									</div>
									<div class="row">
										<div class="col-auto @if(app()->getLocale()=="en") mr-auto @else align-self-end @endif">
											<div class="form-group">
												<button class="btn btn-border datalayerLogin" type="submit">{{__('webMessage.login')}}</button>
											</div>
										</div>
										<div class="col-auto @if(app()->getLocale()=="ar") mr-auto @else align-self-end @endif ">
											<div class="form-group">
												<ul class="additional-links">
													<li><a href="{{url(app()->getLocale().'/password/reset')}}">{{__('webMessage.forgot_password_txt')}}</a></li>
												</ul>
											</div>
										</div>
									</div>
                                    @if(session('session_msg'))
                                    <div class="alert-success">{{session('session_msg')}}</div>
                                    @endif
                                    @if(session('session_msg_error'))
                                    <div class="alert-danger">{{session('session_msg_error')}}</div>
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
<script>
$(document).on("click",".datalayerLogin",function(){

gtag("event", "login", {
  method: "Website",
  username:$("#login_username").val()
});

});
</script>
</body>
</html>