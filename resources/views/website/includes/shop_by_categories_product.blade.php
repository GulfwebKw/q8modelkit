@php
if(isset(Request()->catid)){$catidtop=Request()->catid;}else{$catidtop=0;}
$shopcategoriesLists = App\Http\Controllers\webController::getProductCategories($catidtop);
@endphp
@if(!empty($shopcategoriesLists) && count($shopcategoriesLists)>0)
<div class="container-indent" style="margin-bottom:50px;">
		<div class="container">
			<div class="row tt-layout-promo02">
                @foreach($shopcategoriesLists as $shopcategoriesList)
                @php
               if($shopcategoriesList->cimage){
               $imagecats=url('uploads/category/thumb/'.$shopcategoriesList->cimage);
               }else{
               $imagecats=url('uploads/category/no-image.png');
               }
               @endphp
				<div class="col-sm-12 col-md-6 col-lg-3">
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