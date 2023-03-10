@php
$bannersLists = App\Http\Controllers\webController::banners();
@endphp
@if(!empty($bannersLists) && count($bannersLists)>0)
<div class="container-indent nomargin">
		<div class="container-fluid-custom">
			<div class="row tt-list-sm-shift">
            @foreach($bannersLists as $bannersList)
				<div class="col-lg-6 col-12-575width">
                        @if(!empty($bannersList->image))
							<a href="@if(!empty($bannersList->link)) {{$bannersList->link}} @else javascript:; @endif " class="tt-promo-box tt-one-child">
								<img src="{{url('assets/images/loader.svg')}}" data-src="{{url('uploads/banner/'.$bannersList->image)}}" alt="" class="my_height">
								@if(!empty($bannersList->title_en) && app()->getLocale()=="en")
                                <div class="tt-description">
									<div class="tt-description-wrapper">
										<div class="tt-background"></div>
										<div class="tt-title-small">{{$bannersList->title_en}}</div>
									</div>
								</div>
                                @elseif(!empty($bannersList->title_ar) && app()->getLocale()=="ar")
                                <div class="tt-description">
									<div class="tt-description-wrapper">
										<div class="tt-background"></div>
										<div class="tt-title-small">{{$bannersList->title_ar}}</div>
									</div>
								</div>
                                @endif
							</a>
                         @endif   
				</div>
             @endforeach  
			</div>
		</div>
	</div>
    @endif