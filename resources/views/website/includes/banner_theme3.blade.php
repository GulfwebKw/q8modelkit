@php
$leftbanners = App\Http\Controllers\webController::banners();
@endphp
@if(!empty($leftbanners) && count($leftbanners)>0)
<div class="container-indent nomargin">
		<div class="container-fluid-custom">
					<div class="row">
                    @foreach($leftbanners as $leftbanner)
						<div class="col-sm-6">
                        @if(!empty($leftbanner->image))
							<a href="@if(!empty($leftbanner->link)) {{$leftbanner->link}} @else javascript:; @endif " class="tt-promo-box tt-one-child bannerCounts" id="{{$leftbanner->id}}" >
								<img src="{{url('assets/images/loader.svg')}}" data-src="{{url('uploads/banner/'.$leftbanner->image)}}" alt="">
								@if(!empty($leftbanner->title_en) && app()->getLocale()=="en")
                                <div class="tt-description">
									<div class="tt-description-wrapper">
										<div class="tt-background"></div>
										<div class="tt-title-small">{{$leftbanner->title_en}}</div>
									</div>
								</div>
                                @elseif(!empty($leftbanner->title_ar) && app()->getLocale()=="ar")
                                <div class="tt-description">
									<div class="tt-description-wrapper">
										<div class="tt-background"></div>
										<div class="tt-title-small">{{$leftbanner->title_ar}}</div>
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