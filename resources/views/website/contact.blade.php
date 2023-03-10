@php
$settingInfo = App\Http\Controllers\webController::settings();
$slideshows = App\Http\Controllers\webController::getSlideshow();
$slidetxt='';
if(!empty($slideshows) && count($slideshows)>0){
foreach($slideshows as $slideshow){
$slidetxt.='"'.url('/uploads/slideshow/'.$slideshow->image).'",';
}
}
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>@if(app()->getLocale()=="en") {{$settingInfo->name_en}} @else {{$settingInfo->name_ar}} @endif | {{__('webMessage.contactus')}}</title>
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
<style>.g-recaptcha {transform:scale(0.90);transform-origin:0 0;}</style>

<script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Restaurant",
      "image": [
        {!!trim($slidetxt,',')!!}
       ],
      "@id": "{{url(app()->getLocale().'/contactus')}}",
      "name": "Kash5aStore",
      "address": {
        "@type": "PostalAddress",
        "streetAddress": "{!!!empty($settingInfo->address_en)?$settingInfo->name_en:''!!}",
        "addressLocality": "Kuwait City",
        "addressRegion": "Kuwait",
        "postalCode": "00000",
        "addressCountry": "KW"
      },
      "review": {
        "@type": "Review",
        "reviewRating": {
          "@type": "Rating",
          "ratingValue": "{{rand(1,5)}}",
          "bestRating": "{{rand(1,5)}}"
        },
        "author": {
          "@type": "Person",
          "name": "Gulfweb"
        }
      },
      "geo": {
        "@type": "GeoCoordinates",
        "latitude": 48.0037848,
        "longitude": 29.3391489
      },
      "url": "{{url(app()->getLocale().'/')}}",
      "telephone": "@if($settingInfo->mobile){{$settingInfo->mobile}}@endif",
      "servesCuisine": "American",
      "priceRange": "1.5-50",
      "openingHoursSpecification": [
        {
          "@type": "OpeningHoursSpecification",
          "dayOfWeek": [
            "Monday",
            "Thursday"
          ],
          "opens": "9:30",
          "closes": "20:00"
        }
      ],
      "menu": "{{url(app()->getLocale().'/')}}",
      "acceptsReservations": "True"
    }
    </script>
    
    
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
			<li>{{__('webMessage.contactus')}}</li>
		</ul>
	</div>
</div>
<div id="tt-pageContent">
  @if($settingInfo->map_embed_url)
	<div class="container-indent">
		<div class="container">
			<div class="contact-map">
				<iframe src="{!!$settingInfo->map_embed_url!!}" width="1180" height="450" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
			</div>
		</div>
	</div>
    @endif
    <div class="container-indent">
		<div class="container container-fluid-custom-mobile-padding">
			<div class="tt-contact02-col-list">
				<div class="row">
					<div class="col-sm-12 col-md-4 ml-sm-auto mr-sm-auto">
						<div class="tt-contact-info">
							<i class="tt-icon icon-f-93"></i>
							<h6 class="tt-title">{{__('webMessage.letshavechat')}}</h6>
							<address>
								@if($settingInfo->mobile){{$settingInfo->mobile}}<br>@endif
								@if($settingInfo->phone){{$settingInfo->phone}}<br>@endif
                                @if($settingInfo->email){{$settingInfo->email}}<br>@endif
							</address>
						</div>
					</div>
					<div class="col-sm-6 col-md-4">
						<div class="tt-contact-info">
							<i class="tt-icon icon-f-24"></i>
							<h6 class="tt-title">{{__('webMessage.visitourlocation')}}</h6>
							<address>
								@if(app()->getLocale()=="en" && !empty($settingInfo->address_en)) {!!$settingInfo->address_en!!} @endif
                                @if(app()->getLocale()=="ar" && !empty($settingInfo->address_ar)) {!!$settingInfo->address_ar!!} @endif
							</address>
						</div>
					</div>
					<div class="col-sm-6 col-md-4">
						<div class="tt-contact-info">
							<i class="tt-icon icon-f-92"></i>
							<h6 class="tt-title">{{__('webMessage.officehours')}}</h6>
							<address>
								@if(app()->getLocale()=="en" && !empty($settingInfo->office_hours_en)) {!!$settingInfo->office_hours_en!!} @endif
                                @if(app()->getLocale()=="ar" && !empty($settingInfo->office_hours_ar)) {!!$settingInfo->office_hours_ar!!} @endif
							</address>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
    
    <div class="container-indent">
		<div class="container container-fluid-custom-mobile-padding">
			<form id="contactformtxt" class="contact-form form-default" method="post" novalidate action="{{route('contactform',['locale'=>app()->getLocale()])}}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<input autocomplete="off" type="text" name="name" class="form-control" id="name" placeholder="{{__('webMessage.enter_your_name')}}*" value="{{old('name')}}">
                               @if($errors->has('name'))
                                <label id="name-error" class="error" for="name">{{ $errors->first('name') }}</label>
                                @endif
						</div>
						<div class="form-group">
							<input autocomplete="off" type="email" name="email" class="form-control" id="email" placeholder="{{__('webMessage.enter_your_email')}}*" value="{{old('email')}}">
                            @if($errors->has('email'))
                                <label id="email-error" class="error" for="email">{{ $errors->first('email') }}</label>
                                @endif
						</div>
                        <div class="form-group">
							<input autocomplete="off" type="text" name="mobile" class="form-control" id="mobile" placeholder="{{__('webMessage.enter_your_mobile')}}*" value="{{old('mobile')}}">
                            @if($errors->has('mobile'))
                                <label id="mobile-error" class="error" for="mobile">{{ $errors->first('mobile') }}</label>
                                @endif
						</div>
						<div class="form-group">
							<select name="subject" id="subject" class="form-control">
                                    <option disabled="disabled" selected>{{__('webMessage.choose_your_subject')}}*</option>
                                    @if(count($subjectLists))
                                    @foreach($subjectLists as $subjectList)
                                    <option value="{{$subjectList->id}}" {{old('subject')==$subjectList->id?'selected':''}}>{{$subjectList->title_en}}</option>
                                    @endforeach
                                    @endif
                                </select>
                                @if($errors->has('subject'))
                                <label id="subject-error" class="error" for="subject">{{ $errors->first('subject') }}</label>
                                @endif
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<textarea  class="form-control" rows="7" name="message"  id="message" placeholder="{{__('webMessage.write_some_text')}}*">{{old('message')}}</textarea>
                            @if($errors->has('message'))
                            <label id="message-error" class="error" for="message">{{ $errors->first('message') }}</label>
                            @endif
						</div>
					</div>
				</div> 
                <div class="row"><div class="col-lg-12"><div class="g-recaptcha" data-sitekey="6LeMueQUAAAAAJ-ZUe9ZqGK3pma9VwbeoaYDgJte"></div>
                @if($errors->has('recaptchaError'))
                <label id="message-error" class="error" for="message">{{ $errors->first('recaptchaError') }}</label>
                @endif
                </div></div>
				<div class="text-center">
					<button type="submit" class="btn">{{__('webMessage.sendnow')}}</button>
				</div>
                 @if(session('session_msg'))
                 <div class="alert-success">{{session('session_msg')}}</div>
                 @endif
			</form>
		</div>
	</div>
    
    
</div>
<!--footer-->
@include("website.includes.footer")

<!-- sugnout modal (ModalSubsribeGood) -->
@include("website.includes.signout_modal")

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
<script src='https://www.google.com/recaptcha/api.js'></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
@if ($settingInfo->theme != 17)
<script defer src="{{ url('hakum_assets/js/bundle.js') }}"></script>
@endif
<script src="{{url('assets/js/gulfweb.js')}}"></script>

<!--recaptcha-->

<script>
gtag('event', 'screen_view', {
  'screen_name' : 'contact us'
});
</script>
</body>
</html>