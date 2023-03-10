@php
$slideshows = App\Http\Controllers\webController::getSlideshow();
@endphp
@if (!empty($slideshows) && count($slideshows) > 0)
<div class="tt-offset-small container-indent">
    <div class="container">
        <div class="slider-revolution revolution-default" data-fullscreen="false" data-width="1180" data-height="500">
            <div class="tp-banner-container">
                <div class="tp-banner revolution">
                    <ul>
                        @foreach($slideshows as $slideshow)
                        <li data-thumb="{{url('uploads/slideshow/'.$slideshow->image)}}" data-transition="fade" data-slotamount="1" data-masterspeed="1000" data-saveperformance="off"  data-title="Slide">
                            <img src="{{url('uploads/slideshow/'.$slideshow->image)}}"  alt="slide1"  data-bgposition="center center" data-bgfit="cover" data-bgrepeat="no-repeat" >
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@endif













