@php
$brandMenus = App\Http\Controllers\webController::ShopByBrandsList();
@endphp
@if(!empty($settingInfo->is_brand_active) && !empty($brandMenus) && count($brandMenus)>0)

    <div class="container-indent">
		<div class="container container-fluid-custom-mobile-padding">
			<div class="tt-block-title">
                @if($settingInfo->theme!=2)
				<h1 class="tt-title noborder"><a href="{{!empty($link)?$link:'#'}}">{{strtoupper(trans('webMessage.favoritebrands'))}}</a></h1>
                @else
				<h3 class="tt-title tt-title-span"><span><a href="{{!empty($link)?$link:'#'}}">{{trans('webMessage.favoritebrands')}}</a></span></h3>
                @endif
              <div class="tt-description">&nbsp;</div>
			</div>
			<div class="tt-carousel-products row arrow-location-tab arrow-location-tab01 tt-alignment-img tt-collection-listing slick-animated-show-js">
			@foreach($brandMenus as $brandMenu)
               @php
               if($brandMenu->image){
               $imagebrand=url('uploads/brand/thumb/'.$brandMenu->image);
               }else{
               $imagebrand=url('uploads/brand/no-image.png');
               }
               if($brandMenu->bgimage){
               $bgimagebrand=url('uploads/brand/'.$brandMenu->bgimage);
               }else{
               $bgimagebrand=url('uploads/brand/no-image.png');
               }
               @endphp
                <div class="col-2 col-md-2 col-lg-2">
                    <div class="tt-product thumbprod-center">
						<div class="tt-image-box" align="center">
							<a href="{{url(app()->getLocale().'/brands/'.$brandMenu->slug)}}" class="tt-promo-box tt-one-child">
	                <img src="{{url('hakum_assets/images/loader.svg')}}" data-src="{{$imagebrand}}" alt="" style="max-height:200px !important;max-width:200px !important;"></a>                    
						</div>
					</div>
				</div>
                @endforeach
			</div>
		</div>
	</div>
 @endif   