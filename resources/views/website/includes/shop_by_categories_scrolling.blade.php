@php
if(isset(Request()->catid)){$catidtop=Request()->catid;}else{$catidtop=0;}
$shopcategoriesLists = App\Http\Controllers\webController::getProductCategories($catidtop);
$settingInfo = App\Http\Controllers\webController::settings();
@endphp
@if(!empty($shopcategoriesLists) && count($shopcategoriesLists)>0)
    <div class="container-indent">
		<div class="container container-fluid-custom-mobile-padding">
			<div class="tt-block-title">
                @if($settingInfo->theme==2)
				<h3 class="tt-title tt-title-span"><span><a href="{{!empty($link)?$link:'#'}}">{{trans('webMessage.shopbycategory')}}</a></span></h3>
                @elseif($settingInfo->theme==2)
                @else
                <h1 class="tt-title noborder "><a style="margin:20px 0; color:#f29a33; font-weight:bold; font-size:25px;" href="{{!empty($link)?$link:'#'}}">{{strtoupper(trans('webMessage.shopbycategory'))}}</a></h1>
                @endif
              <div class="tt-description">&nbsp;</div>
			</div>
			<div class="tt-carousel-products row arrow-location-tab arrow-location-tab01 tt-alignment-img tt-collection-listing slick-animated-show-js">
			@foreach($shopcategoriesLists as $shopcategoriesList)
               @php
               if($shopcategoriesList->cimage){
               $imagecats=url('uploads/category/thumb/'.$shopcategoriesList->cimage);
               }else{
               $imagecats=url('uploads/category/no-image.png');
               }
               @endphp
                <div class="col-2 col-md-4 col-lg-3">
                    <div class="tt-product thumbprod-center">
						<div class="tt-image-box">
							<a href="{{url(app()->getLocale().'/products/'.$shopcategoriesList->cid.'/'.$shopcategoriesList->friendly_url)}}"><img src="{{url('hakum_assets/images/loader.svg')}}" data-src="{{$imagecats}}" alt=""></a>                       
						</div>
						<div class="tt-description">
							<h2 class="tt-title"><a href="{{url(app()->getLocale().'/products/'.$shopcategoriesList->cid.'/'.$shopcategoriesList->friendly_url)}}">
                            @if(app()->getLocale()=="en" && !empty($shopcategoriesList->name_en)){{$shopcategoriesList->name_en}}@endif
                            @if(app()->getLocale()=="ar" && !empty($shopcategoriesList->name_ar)){{$shopcategoriesList->name_ar}}@endif</a></h2>
						</div>
					</div>
				</div>
                @endforeach
			</div>
		</div>
	</div>
   @endif 