@php
$footerMenusTrees   = App\Categories::CategoriesTree();
$footerAboutDetails = App\Http\Controllers\webController::singlePageDetails(4);
$privacy_details    = App\Http\Controllers\webController::singlePageDetails(2);
$terms_details      = App\Http\Controllers\webController::singlePageDetails(3);
$about_details      = App\Http\Controllers\webController::singlePageDetails(1);
@endphp

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
					<ul class="tt-social-icon">
						@if($settingInfo->social_facebook)
						<li><a title="{{__('webMessage.facebook')}}" class="icon-g-64" target="_blank" href="{{$settingInfo->social_facebook}}"></a></li>
                        @endif
                        @if($settingInfo->social_twitter)
						<li><a title="{{__('webMessage.twitter')}}" class="icon-h-58" target="_blank" href="{{$settingInfo->social_twitter}}"></a></li>
                        @endif
                        @if($settingInfo->social_instagram)
						<li><a title="{{__('webMessage.instagram')}}" class="icon-g-67" target="_blank" href="{{$settingInfo->social_instagram}}"></a></li>                        @endif
                        @if($settingInfo->social_linkedin)
						<li><a title="{{__('webMessage.linkedin')}}" class="icon-g-68" target="_blank" href="{{$settingInfo->social_linkedin}}"></a></li>
                        @endif
                        @if($settingInfo->social_youtube)
						<li><a title="{{__('webMessage.youtube')}}" class="icon-g-76" target="_blank" href="{{$settingInfo->social_youtube}}"></a></li>
                        @endif
					</ul>
				</div>
			</div>
		</div>
	</div>
	<div class="tt-footer-col tt-color-scheme-01">
		<div class="container">
			<div class="row">
                
				<div class="col-md-3 col-lg-3 col-xl-3">
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
                
				<div class="col-md-3 col-lg-3 col-xl-3">
					<div class="tt-mobile-collapse">
						<h4 class="tt-collapse-title">
							{{strtoupper(__('webMessage.importantlinks'))}}
						</h4>
						<div class="tt-collapse-content">
							<ul class="tt-list">
							    @if(!empty($about_details->slug))<li><a href="{{url('/page/'.$about_details->slug)}}">{{__('webMessage.aboutus')}}</a></li>@endif
								<li><a href="{{url('/contactus')}}">{{__('webMessage.contactus')}}</a></li>
								
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
                <div class="col-md-3 col-lg-3 col-xl-3">
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
				<div class="col-md-3 col-lg-3 col-xl-3">
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