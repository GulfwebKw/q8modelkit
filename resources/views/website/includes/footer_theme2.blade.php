
<!-- fixed footer menu -->
	<nav class="mobile-bottom-nav">
	<div class="mobile-bottom-nav__item ">
       <a href="/">
		<div class="mobile-bottom-nav__item-content @if(!empty(Request::route()->getName()) && Request::route()->getName()=='home') mobile-bottom-nav__item--active @endif">
			<i class="material-icons">home</i>
			{{trans('webMessage.home')}}
		</div>
       </a> 		
	</div>
	<div class="mobile-bottom-nav__item " >
       <a href="/categories">		
		<div class="mobile-bottom-nav__item-content @if(!empty(Request::route()->getName()) && Request::route()->getName()=='categories') mobile-bottom-nav__item--active @endif">
			<i class="material-icons">ac_unit</i>
			{{trans('webMessage.categories')}}
		</div>
        </a>
	</div>
	<div class="mobile-bottom-nav__item ">
       <a href="/wishlist">
		<div class="mobile-bottom-nav__item-content @if(!empty(Request::route()->getName()) && Request::route()->getName()=='wishlist') mobile-bottom-nav__item--active @endif">
			<i class="material-icons">pages</i>
			{{trans('webMessage.wishlist')}}
		</div>	
       </a> 	
	</div>
	
	<div class="mobile-bottom-nav__item ">
      <a href="/checkout">
		<div class="mobile-bottom-nav__item-content @if(!empty(Request::route()->getName()) && Request::route()->getName()=='checkout') mobile-bottom-nav__item--active @endif">
			<i class="material-icons">shopping_cart</i>
			{{trans('webMessage.checkout')}}
		</div>
      </a>  		
	</div>
	</nav>
@php
$footerMenusTrees   = App\Categories::CategoriesTree();
$footerAboutDetails = App\Http\Controllers\webController::singlePageDetails(4);
$privacy_details    = App\Http\Controllers\webController::singlePageDetails(2);
$terms_details      = App\Http\Controllers\webController::singlePageDetails(3);
$about_details      = App\Http\Controllers\webController::singlePageDetails(1);
@endphp
@if($settingInfo->social_whatsapp)
@if(!empty($settingInfo->is_float_whatsapp))
<a href="https://api.whatsapp.com/send?phone={{$settingInfo->social_whatsapp}}&text={{__('webMessage.whatsappsharetext')}}"  class="float shareDataLayer" title="{{trans('webMessage.whatsapp')}}" id="{{ $whatsAppNumber }}" target="_blank"><img width="60" height="60" src="data:image/png;base64,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"/></a>
@endif
@endif
<footer class="tt-offset-20 f-mobile-dark">
	<div class="tt-footer-default tt-color-scheme-02">
		<div class="container">
			<div class="row">
				<div class="col-12 col-md-9">
					<div class="tt-newsletter-layout-01">
						<div class="tt-newsletter">
							<div class="tt-mobile-collapse">
								<h4 class="tt-collapse-title">
									{{strtoupper(__('webMessage.newslettersignup'))}}
								</h4>
								<div class="tt-collapse-content">
									
                                    <form id="newsletterformtxt" name="newsletterformtxt" class="form-inline form-default" method="post" novalidate="novalidate">
									<div class="form-group">
										<input type="text" name="newsletter_email" id="newsletter_email" class="form-control" placeholder="{{__('webMessage.enter_email')}}"><span id="newslettermsg"></span>
                                        
										<button type="button" class="btn" id="subscribeBtn">{{__('webMessage.subscribe')}}</button>
									</div>
								</form>
								</div>
							</div>
						</div>
					</div>
				</div>
                
				<div class="col-md-auto">
                    <h4 class="my_title">{{trans('webMessage.followus')}}</h4>
					<ul class="tt-social-icon">
						@if($settingInfo->social_facebook)
						<li><a title="{{__('webMessage.facebook')}}" class="icon-g-64 " target="_blank" href="{{$settingInfo->social_facebook}}"></a></li>
                        @endif
                        @if($settingInfo->social_twitter)
						<li><a title="{{__('webMessage.twitter')}}" class="icon-h-58 " target="_blank" href="{{$settingInfo->social_twitter}}"></a></li>
                        @endif
                        @if($settingInfo->social_instagram)
						<li><a title="{{__('webMessage.instagram')}}" class="icon-g-67 " target="_blank" href="{{$settingInfo->social_instagram}}"></a></li>                        @endif
                        @if($settingInfo->social_linkedin)
						<li><a title="{{__('webMessage.linkedin')}}" class="icon-g-68 " target="_blank" href="{{$settingInfo->social_linkedin}}"></a></li>
                        @endif
                        @if($settingInfo->social_youtube)
						<li><a title="{{__('webMessage.youtube')}}" class="icon-g-76 " target="_blank" href="{{$settingInfo->social_youtube}}"></a></li>
                        @endif
					</ul>
				</div>
			</div>
		</div>
	</div>
	<div class="tt-footer-col tt-color-scheme-01">
		<div class="container">
			<div class="row">
                <!--
				<div class="col-md-6 col-lg-2 col-xl-3">
					<div class="tt-mobile-collapse">
						 @if(!empty($footerMenusTrees) && count($footerMenusTrees)>0)
						<h4 class="tt-collapse-title">
							{{strtoupper(__('webMessage.categories'))}}
						</h4>
						<div class="tt-collapse-content">
							<ul class="tt-list">
                              @foreach($footerMenusTrees as $footerMenusTree)
								<li><a href="{{url('/products/'.$footerMenusTree->id.'/'.$footerMenusTree->friendly_url)}}">@if(app()->getLocale()=="en") {{$footerMenusTree->name_en}} @else {{$footerMenusTree->name_ar}} @endif</a></li>
                               @endforeach 
							</ul>
						</div>
                        @endif
					</div>
				</div>
                -->
				<div class="col-md-4 col-lg-4 col-xl-4">
					<div class="tt-mobile-collapse">
						<h4 class="tt-collapse-title">
							{{strtoupper(__('webMessage.importantlinks'))}}
						</h4>
						<div class="tt-collapse-content">
							<ul class="tt-list">
							    @if(!empty($about_details->slug))<li><a href="{{url('/page/'.$about_details->slug)}}">{{__('webMessage.aboutus')}}</a></li>@endif
								<!--<li><a href="{{url('/contactus')}}">{{__('webMessage.contactus')}}</a></li>-->
								
                                @if(!empty($privacy_details->slug))<li><a href="{{url('/page/'.$privacy_details->slug)}}">{{__('webMessage.privacypolicy')}}</a></li>@endif
                                @if(!empty($terms_details->slug))<li><a href="{{url('/page/'.$terms_details->slug)}}">{{__('webMessage.termsconditions')}}</a></li>@endif
                                <li><a href="{{url('/faq')}}">{{__('webMessage.faq')}}</a></li>
                                @if($settingInfo->supplier_registration == 1)
									<li><a href="{{url('/supplier-registration')}}">{{__('webMessage.supplier_registration')}}</a></li>
								@endif
							</ul>
						</div>
					</div>
				</div>
                <div class="col-md-4 col-lg-4 col-xl-4">
					<div class="tt-mobile-collapse">
                        <h4 class="tt-collapse-title">
							{{strtoupper(__('webMessage.myaccount'))}}
						</h4>
						<div class="tt-collapse-content">
							<ul class="tt-list">
								@if(!empty(Auth::guard('webs')->user()->id))
								<li><a href="{{url('/dashboard')}}">{{__('webMessage.dashboard')}}</a></li>
                                <li><a href="{{url('/changepass')}}">{{__('webMessage.changepassword')}}</a></li>
                                <li><a href="{{url('/editprofile')}}">{{__('webMessage.editprofile')}}</a></li>
                                <li><a href="{{url('/myorders')}}">{{__('webMessage.myorders')}}</a></li>
                                <li><a href="{{url('/wishlist')}}">{{__('webMessage.wishlist')}}</a></li>
								@else
								<li><a href="{{url('/register')}}">{{__('webMessage.signup')}}</a></li>
								<li><a href="{{url('/login')}}">{{__('webMessage.signin')}}</a></li>
								@endif
							</ul>
						</div>
					</div>
				</div>
				<!--<div class="col-md-6 col-lg-4 col-xl-3">
					<div class="tt-mobile-collapse">
						<h4 class="tt-collapse-title">
							{{strtoupper(__('webMessage.aboutus'))}}
						</h4>
						<div class="tt-collapse-content">
							<p>
                            @if(app()->getLocale()=="ar" && !empty($footerAboutDetails->details_ar))
                            {!!$footerAboutDetails->details_ar!!}
                            @endif
                            @if(app()->getLocale()=="en" && !empty($footerAboutDetails->details_en))
                            {!!$footerAboutDetails->details_en!!}
                            @endif
							</p>
						</div>
					</div>
				</div>-->
				<div class="col-md-4 col-lg-4 col-xl-4">
					<div class="tt-newsletter">
						<div class="tt-mobile-collapse">
						<h4 class="tt-collapse-title">
							{{strtoupper(__('webMessage.contactus'))}}
						</h4>
						<div class="tt-collapse-content">
							<address>
                                @if(app()->getLocale()=="ar" && $settingInfo->address_ar)
								<p><span>{{__('webMessage.address')}}:</span> {{$settingInfo->address_ar}}</p>
                                @endif
                                @if(app()->getLocale()=="en" && $settingInfo->address_en)
								<p><span>{{__('webMessage.address')}}:</span> {{$settingInfo->address_en}}</p>
                                @endif
                                @if($settingInfo->phone)
								<p><span>{{__('webMessage.phone')}}:</span> {{$settingInfo->phone}}</p>
                                @endif
                                @if(app()->getLocale()=="ar" && $settingInfo->office_hours_ar)
                                <p><span>{{__('webMessage.officehours')}}:</span> {{$settingInfo->office_hours_ar}}</p>
                                @elseif(app()->getLocale()=="en" && $settingInfo->office_hours_en)
                                <p><span>{{__('webMessage.officehours')}}:</span> {{$settingInfo->office_hours_en}}</p>
                                @endif
                                @if($settingInfo->email)
								<p><span>{{__('webMessage.email')}}:</span> <a target="_blank" href="mailto:{{$settingInfo->email}}">{{$settingInfo->email}}</a></p>
                                @endif
							</address>
						</div>
					</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="tt-footer-custom">
		<div class="container">
			<div class="tt-row">
				<div class="tt-col-left">
					<div class="tt-col-item tt-logo-col">
						<!-- logo -->
                        @if($settingInfo->footerlogo)
						<a class="tt-logo tt-logo-alignment" href="{{url('/')}}"><img src="{{url('uploads/logo/'.$settingInfo->footerlogo)}}" title="@if(app()->getLocale()=="en") {{$settingInfo->name_en}} @else {{$settingInfo->name_ar}} @endif" alt="@if(app()->getLocale()=="en") {{$settingInfo->name_en}} @else {{$settingInfo->name_ar}} @endif"></a>
                        @endif
						<!-- /logo -->
					</div>
					<div class="tt-col-item">
						<!-- copyright -->
						<div class="tt-box-copyright">
						 @if($settingInfo->copyrights_en && app()->getLocale()=="en"){!!$settingInfo->copyrights_en!!}@endif
                         @if($settingInfo->copyrights_ar && app()->getLocale()=="ar"){!!$settingInfo->copyrights_ar!!}@endif
						</div>
						<!-- /copyright -->
					</div>
				</div>
				<div class="tt-col-right">
					<div class="tt-col-item">
					    @if(!empty($settingInfo->payments))
                        @php
                        $payments = explode(",",$settingInfo->payments);
                        @endphp
						<ul class="tt-payment-list">
						    @foreach($payments as $payment)
							<li><a href="javascript:;"><img src="{{url('uploads/paymenticons/'.strtolower($payment).'.png')}}" height="30" alt=""></a></li>
							@endforeach
						</ul>
						@endif
					</div>
				</div>
			</div>
		</div>
	</div>
</footer>

<script id="rendered-js" >
var navItems = document.querySelectorAll(".mobile-bottom-nav__item");
navItems.forEach(function (e, i) {
  e.addEventListener("click", function (e) {
    navItems.forEach(function (e2, i2) {
      e2.classList.remove("mobile-bottom-nav__item--active");
    });
    this.classList.add("mobile-bottom-nav__item--active");
  });
});
</script>