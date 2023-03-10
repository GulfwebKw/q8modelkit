@php
$bannerBox1 = App\Http\Controllers\webController::getRandomBanner(1,1);
$bannerBox2 = App\Http\Controllers\webController::getRandomBanner(1,2);
$bannerBox3 = App\Http\Controllers\webController::getRandomBanner(2,3);
$bannerBox4 = App\Http\Controllers\webController::getRandomBanner(1,4);
$bannerBox5 = App\Http\Controllers\webController::getRandomBanner(1,5);
$bannerBox6 = App\Http\Controllers\webController::getRandomBanner(3,6);
@endphp
<div class="container-indent0">
		<div class="container-fluid">
			<div class="row tt-layout-promo-box">
				<div class="col-sm-12 col-md-6">
					<div class="row">
						<div class="col-sm-6">
                            @if(!empty($bannerBox1->id))
							<a href="@if(!empty($bannerBox1->link)) {{$bannerBox1->link}} @else javascript:; @endif" class="tt-promo-box tt-one-child hover-type-2 bannerCounts" id="{{$bannerBox1->id}}" >
								<img src="{{url('uploads/banner/'.$bannerBox1->image)}}" data-src="{{url('uploads/banner/'.$bannerBox1->image)}}" alt="" class="loaded" data-was-processed="true">
								@if(!empty($bannerBox1->title_en) && app()->getLocale()=="en")
                                <div class="tt-description">
									<div class="tt-description-wrapper">
										<div class="tt-background"></div>
										<h4 class="tt-title-small">{{$bannerBox1->title_en}}</h4>
									</div>
								</div>
                                @elseif(!empty($bannerBox1->title_ar) && app()->getLocale()=="ar")
                                <div class="tt-description">
									<div class="tt-description-wrapper">
										<div class="tt-background"></div>
										<h4 class="tt-title-small" style="text-align:center;">{{$bannerBox1->title_ar}}</h4>
									</div>
								</div>
                                @endif
							</a>
                            @endif
                            @if(!empty($bannerBox2->id))
							<a href="@if(!empty($bannerBox2->link)) {{$bannerBox2->link}} @else javascript:; @endif" class="tt-promo-box tt-one-child hover-type-2 bannerCounts" id="{{$bannerBox2->id}}">
								<img src="{{url('uploads/banner/'.$bannerBox2->image)}}" data-src="{{url('uploads/banner/'.$bannerBox2->image)}}" alt="" class="loaded" data-was-processed="true">
								@if(!empty($bannerBox2->title_en) && app()->getLocale()=="en")
                                <div class="tt-description">
									<div class="tt-description-wrapper">
										<div class="tt-background"></div>
										<h4 class="tt-title-small">{{$bannerBox2->title_en}}</h4>
									</div>
								</div>
                                @elseif(!empty($bannerBox2->title_ar) && app()->getLocale()=="ar")
                                <div class="tt-description">
									<div class="tt-description-wrapper">
										<div class="tt-background"></div>
										<h4 class="tt-title-small" style="text-align:center;">{{$bannerBox2->title_ar}}</h4>
									</div>
								</div>
                                @endif
							</a>
                            @endif
						</div>
						<div class="col-sm-6">
                           @if(!empty($bannerBox3->id))
							<a href="@if(!empty($bannerBox3->link)) {{$bannerBox3->link}} @else javascript:; @endif" class="tt-promo-box tt-one-child hover-type-2 bannerCounts" id="{{$bannerBox3->id}}">
								<img src="{{url('uploads/banner/'.$bannerBox3->image)}}" data-src="{{url('uploads/banner/'.$bannerBox3->image)}}" alt="" class="loaded" data-was-processed="true">
								@if(!empty($bannerBox3->title_en) && app()->getLocale()=="en")
                                <div class="tt-description">
									<div class="tt-description-wrapper">
										<div class="tt-background"></div>
										<h4 class="tt-title-small">{{$bannerBox3->title_en}}</h4>
									</div>
								</div>
                                @elseif(!empty($bannerBox3->title_ar) && app()->getLocale()=="ar")
                                <div class="tt-description">
									<div class="tt-description-wrapper">
										<div class="tt-background"></div>
										<h4 class="tt-title-small" style="text-align:center;">{{$bannerBox3->title_ar}}</h4>
									</div>
								</div>
                                @endif
							</a>
                            @endif
						</div>
					</div>
				</div>
				<div class="col-sm-12 col-md-6">
					<div class="row">
						<div class="col-sm-6">
                            @if(!empty($bannerBox4->id))
							<a href="@if(!empty($bannerBox4->link)) {{$bannerBox4->link}} @else javascript:; @endif" class="tt-promo-box tt-one-child hover-type-2 bannerCounts" id="{{$bannerBox4->id}}">
								<img src="{{url('uploads/banner/'.$bannerBox4->image)}}" data-src="{{url('uploads/banner/'.$bannerBox4->image)}}" alt="" class="loaded" data-was-processed="true">
								@if(!empty($bannerBox4->title_en) && app()->getLocale()=="en")
                                <div class="tt-description">
									<div class="tt-description-wrapper">
										<div class="tt-background"></div>
										<h4 class="tt-title-small">{{$bannerBox4->title_en}}</h4>
									</div>
								</div>
                                @elseif(!empty($bannerBox4->title_ar) && app()->getLocale()=="ar")
                                <div class="tt-description">
									<div class="tt-description-wrapper">
										<div class="tt-background"></div>
										<h4 class="tt-title-small" style="text-align:center;">{{$bannerBox4->title_ar}}</h4>
									</div>
								</div>
                                @endif
							</a>
                            @endif
						</div>
						<div class="col-sm-6">
                           @if(!empty($bannerBox5->id))
							<a href="@if(!empty($bannerBox5->link)) {{$bannerBox5->link}} @else javascript:; @endif" class="tt-promo-box tt-one-child hover-type-2 bannerCounts" id="{{$bannerBox5->id}}">
								<img src="{{url('uploads/banner/'.$bannerBox5->image)}}" data-src="{{url('uploads/banner/'.$bannerBox5->image)}}" alt="" class="loaded" data-was-processed="true">
								@if(!empty($bannerBox5->title_en) && app()->getLocale()=="en")
                                <div class="tt-description">
									<div class="tt-description-wrapper">
										<div class="tt-background"></div>
										<h4 class="tt-title-small">{{$bannerBox5->title_en}}</h4>
									</div>
								</div>
                                @elseif(!empty($bannerBox5->title_ar) && app()->getLocale()=="ar")
                                <div class="tt-description">
									<div class="tt-description-wrapper">
										<div class="tt-background"></div>
										<h4 class="tt-title-small" style="text-align:center;">{{$bannerBox5->title_ar}}</h4>
									</div>
								</div>
                                @endif
							</a>
                           @endif 
						</div>
						<div class="col-sm-12">
                        @if(!empty($bannerBox6->id))
							<a href="@if(!empty($bannerBox6->link)) {{$bannerBox6->link}} @else javascript:; @endif" class="tt-promo-box tt-one-child bannerCounts" id="{{$bannerBox6->id}}">
								<img src="{{url('uploads/banner/'.$bannerBox6->image)}}" data-src="{{url('uploads/banner/'.$bannerBox6->image)}}" alt="" class="loaded" data-was-processed="true">
								@if(!empty($bannerBox6->title_en) && app()->getLocale()=="en")
                                <div class="tt-description">
									<div class="tt-description-wrapper">
										<div class="tt-background"></div>
										<h4 class="tt-title-small">{{$bannerBox6->title_en}}</h4>
									</div>
								</div>
                                @elseif(!empty($bannerBox6->title_ar) && app()->getLocale()=="ar")
                                <div class="tt-description">
									<div class="tt-description-wrapper">
										<div class="tt-background"></div>
										<h4 class="tt-title-small" style="text-align:center;">{{$bannerBox6->title_ar}}</h4>
									</div>
								</div>
                                @endif
							</a>
                         @endif   
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>