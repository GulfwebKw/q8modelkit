@php
$brandMenus = App\Http\Controllers\webController::ShopByBrandsList();
@endphp
@if(!empty($settingInfo->is_brand_active) && !empty($brandMenus) && count($brandMenus)>0)
<div class="container-indent">
		<div class="container">
			<div class="tt-block-title text-center ">
				<h3 class="tt-title tt-title-span"><span><a href="{{!empty($link)?$link:'#'}}">{{trans('webMessage.favoritebrands')}}</a></span></h3>
			</div>
			<div class="row tt-layout-promo-box">
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
				<div class="col-6 col-md-3 ">
					<a href="{{url(app()->getLocale().'/brands/'.$brandMenu->slug)}}" class="tt-promo-box tt-one-child">
	                <img src="{{url('hakum_assets/images/loader.svg')}}" data-src="{{$imagebrand}}" alt="" style="max-height:100px !important;max-width:200px !important;">
                    <!--<div class="tt-description">
							<div class="tt-description-wrapper">
								<div class="tt-background"></div>
								<img src="{{url('hakum_assets/images/loader.svg')}}" data-src="{{$imagebrand}}" alt="" style="max-height:80px; max-width:100px;">
							</div>
				   </div>-->
                   </a>
				</div>
                @endforeach
			</div>
		</div>
	</div>
 @endif   