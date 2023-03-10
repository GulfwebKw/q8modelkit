@php
$shopcategoriesLists = App\Http\Controllers\webController::getProductCategories(0);
@endphp
@if(!empty($shopcategoriesLists) && count($shopcategoriesLists)>0)
<div class="container-indent">
		<div class="container">
			<div class="tt-block-title text-center">
				<h3 class="tt-title tt-title-span"><span><a href="{{!empty($link)?$link:'#'}}">{{trans('webMessage.shopbycategory')}}</a></span></h3>
			</div>
			<div class="row tt-layout-promo02">
                @foreach($shopcategoriesLists as $shopcategoriesList)
                @php
               if($shopcategoriesList->cimage){
               $imagecats=url('uploads/category/thumb/'.$shopcategoriesList->cimage);
               }else{
               $imagecats=url('uploads/category/no-image.png');
               }
               @endphp
				<div class="col-6 col-md-3 col-6-440width">
					<div class="tt-promo02">
						<div class="image-box"><a href="{{url(app()->getLocale().'/products/'.$shopcategoriesList->cid.'/'.$shopcategoriesList->friendly_url)}}"><img src="{{url('hakum_assets/images/loader.svg')}}" data-src="{{$imagecats}}" alt=""></a></div>
						<div class="tt-description">
							<a href="{{url(app()->getLocale().'/products/'.$shopcategoriesList->cid.'/'.$shopcategoriesList->friendly_url)}}" class="tt-title">
								<div class="tt-title-large">
                                @if(app()->getLocale()=="en" && !empty($shopcategoriesList->name_en)){{$shopcategoriesList->name_en}}@endif
                                @if(app()->getLocale()=="ar" && !empty($shopcategoriesList->name_ar)){{$shopcategoriesList->name_ar}}@endif
                                </div>
							</a>
						</div>
					</div>
				</div>
				@endforeach
			</div>
		</div>
	</div>
   @endif 