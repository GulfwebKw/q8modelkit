@php
$slideshows = App\Http\Controllers\webController::getSlideshow();
@endphp
@if(!empty($slideshows) && count($slideshows)>0)
	<div class="container-indent nomargin">
		<div class="container-fluid">
			<div class="row">
				<div class="slider-revolution revolution-default">
					<div class="tp-banner-container">
						<div class="tp-banner revolution">
							<ul>
                            @foreach($slideshows as $slideshow)
                            @php
                            if(!empty($slideshow->link)){$lnks=$slideshow->link;}else{$lnks="";}
                            @endphp 
								<li data-thumb="{{url('uploads/slideshow/'.$slideshow->image)}}" data-transition="fade" data-slotamount="1" data-masterspeed="1000" data-saveperformance="off"  data-title="Slide" class="openmyLink" id="{{$lnks}}" myatt="{{$slideshow->id}}">
                                    
									<img src="{{url('uploads/slideshow/'.$slideshow->image)}}"  alt="slide1"  data-bgposition="center center" data-bgfit="cover" data-bgrepeat="no-repeat"   >
									@if(empty($slideshow->video_link))
                                    <div class="tp-caption tp-caption1 lfr str"
										data-x="right"
										data-y="center"
										data-hoffset="-351"
										data-voffset="-20"
										data-speed="600"
										data-start="900"
										data-easing="Power4.easeOut"
										data-endeasing="Power4.easeIn">
										<div class="tp-caption1-wd-1"><span class="tt-base-color">@if(app()->getLocale()=="en" && $slideshow->title_en) {!!nl2br($slideshow->title_en)!!} @elseif(app()->getLocale()=="ar" && $slideshow->title_ar) {!!$slideshow->title_ar!!} @endif</span></div>
									</div>
                                    @else
                                    @if(!empty($slideshow->video_link_img) && !empty($slideshow->video_link))
                                    <div class="tp-caption tp-fade fadeout fullscreenvideo"
										data-x="0"
										data-y="0"
										data-speed="600"
										data-start="0"
										data-easing="Power4.easeOut"
										data-endspeed="1500"
										data-endeasing="Power4.easeIn"
										data-autoplay="true"
										data-autoplayonlyfirsttime="false"
										data-nextslideatend="true"
										data-forceCover="1"
										data-dottedoverlay="twoxtwo"
										data-aspectratio="16:9">
										<video muted class="video-js vjs-default-skin" preload="none"
											poster='{{url("uploads/slideshow/".$slideshow->video_link_img)}}' data-setup="{}">
											<source src='{{url("videos/".$slideshow->video_link)}}' type='video/mp4'>
										</video>
									</div>
                                    @endif
                                    @endif
								</li>
                             @endforeach
                             
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
 @endif   