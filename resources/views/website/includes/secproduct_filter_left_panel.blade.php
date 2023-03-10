
<div class="col-md-4 col-lg-3 col-xl-3 leftColumn aside desctop-no-sidebar ">
					<div class="tt-btn-col-close">
						<a href="javascript:;">{{__('webMessage.close')}}</a>
					</div>
					<!--list filter history-->
                    @if(!empty($filterHistory) && count($filterHistory)>0)
					<div class="tt-collapse open">
						<h3 class="tt-collapse-title">{{strtoupper(__('webMessage.filterby'))}}</h3>
						<div class="tt-collapse-content">
							<ul class="tt-filter-list">
                                @foreach($filterHistory as $history)
                                {!!$history!!}
                                @endforeach
							</ul>
							<a href="javascript:;" id="clearallfilter" class="btn-link-02" mykey="sec">{{strtoupper(__('webMessage.clearall'))}}</a>
						</div>
					</div>
                    @endif
                    <!--end filter history -->
                    <!-- list sub categpories -->
                    @if(!empty($productCategoriesLists) && count($productCategoriesLists)>0)
					<div class="tt-collapse open">
						<h3 class="tt-collapse-title">{{strtoupper(__('webMessage.categories'))}}</h3>
						<div class="tt-collapse-content">
							<ul class="tt-list-row">
                                @foreach($productCategoriesLists as $productCategory)
                                @php
                                $countProductss = App\Http\Controllers\webController::countProductsByCatId($productCategory->id);
                                @endphp
								<li><a href="{{url(app()->getLocale().'/products/'.$productCategory->id.'/'.$productCategory->friendly_url)}}">@if(app()->getLocale()=="en" && !empty($productCategory->name_en)) {{$productCategory->name_en}} @elseif(app()->getLocale()=="ar" && $productCategory->name_ar) {{$productCategory->name_ar}} @endif <span class="float-right">({{$countProductss}})</span></a></li>
                                @endforeach
							</ul>
						</div>
					</div>
                    @endif
                    <!--end sub categories -->
                    <!--filter by price range -->
                    @if(!empty($retailPriceRanges) && $retailPriceRanges>0)
					<div class="tt-collapse open">
						<h3 class="tt-collapse-title">{{strtoupper(__('webMessage.filterbyprice'))}}</h3>
						<div class="tt-collapse-content">
							<ul class="tt-list-row">
                                @for($i=0;$i<=$retailPriceRanges;$i+=15)
								<li><a href="javascript:;" id="{{$i}}-{{$i+15}}" class="rangeprice" mykey="sec"> {{$i}} {{__('webMessage.kd')}} —  {{$i+15}} {{__('webMessage.kd')}}</a></li>
                                @endfor
							</ul>
						</div>
					</div>
                    @endif
                    <!--end price range filter -->
                    <!--filter by size-->
                    @if(!empty($prodSizes) && count($prodSizes)>0)
					<div class="tt-collapse open">
						<h3 class="tt-collapse-title">{{strtoupper(__('webMessage.filterbysize'))}}</h3>
						<div class="tt-collapse-content">
							<ul class="tt-options-swatch options-middle">
                               @foreach($prodSizes as $prodSize)
								<li @if(!empty(session(Request()->catid.'filter_by_size')) && session(Request()->catid.'filter_by_size')==$prodSize->id) class="active" @endif><a href="javascript:;" class="filter_by_size" id="{{$prodSize->id}}" mykey="sec">@if(app()->getLocale()=="en" && !empty($prodSize->title_en)) {{$prodSize->title_en}} @elseif(app()->getLocale()=="ar" && $prodSize->title_ar) {{$prodSize->title_ar}} @endif</a></li>
                                @endforeach
							</ul>
						</div>
					</div>
                    @endif
                    <!-- end size filter -->
                    
                    <!--filter by color-->
                    @if(!empty($prodColors) && count($prodColors)>0)
					<div class="tt-collapse open">
						<h3 class="tt-collapse-title">{{strtoupper(__('webMessage.filterbycolor'))}}</h3>
						<div class="tt-collapse-content">
							<ul class="tt-options-swatch options-middle">
                               @foreach($prodColors as $prodColor)
                                @if($prodColor->image)
                                <li style="height:30px;width:30px !important;" @if(!empty(session(Request()->catid.'filter_by_color')) && session(Request()->catid.'filter_by_color')==$prodColor->id) class="active" @endif><a href="javascript:;" class="options-colors tt-border filter_by_color" id="{{$prodColor->id}}" mykey="sec"><img src="{{url('uploads/color/thumb/'.$prodColor->image)}}"  width="40" height="40"/></a></li>
                                @else
								<li style="height:30px;width:30px !important;" @if(!empty(session(Request()->catid.'filter_by_color')) && session(Request()->catid.'filter_by_color')==$prodColor->id) class="active" @endif><a href="javascript:;" class="options-colors tt-border filter_by_color" mykey="sec" id="{{$prodColor->id}}" style="background-color:{{$prodColor->color_code}};"></a></li>
                                @endif
                                @endforeach
							</ul>
						</div>
					</div>
                    @endif
                    <!--end color filter-->
                   <!-- popular items --> 
					@if(!empty($mostpopularitems) && count($mostpopularitems)>0)
					<div class="tt-collapse open">
						<h3 class="tt-collapse-title">{{strtoupper(__('webMessage.mostpopular'))}}</h3>
						<div class="tt-collapse-content">
							<div class="tt-aside">
                            @foreach($mostpopularitems as $mostpopularitem)
								<a class="tt-item" href="{{url(app()->getLocale().'/directdetails/'.$mostpopularitem->id.'/'.$mostpopularitem->slug)}}">
									<div class="tt-img">
										<span class="tt-img-default"><img src="{{url('assets/images/loader.svg')}}" data-src="@if($mostpopularitem->image) {{url('uploads/product/thumb/'.$mostpopularitem->image)}} @else {{url('uploads/no-image.png')}} @endif" alt="@if(app()->getLocale()=='en') {{$mostpopularitem->title_en}} @else {{$mostpopularitem->title_ar}} @endif"></span>
                                        @if($mostpopularitem->rollover_image)
										<span class="tt-img-roll-over"><img src="{{url('assets/images/loader.svg')}}" data-src="@if($mostpopularitem->rollover_image) {{url('uploads/product/thumb/'.$mostpopularitem->rollover_image)}} @else {{url('uploads/no-image.png')}} @endif" alt="@if(app()->getLocale()=='en') {{$mostpopularitem->title_en}} @else {{$mostpopularitem->title_ar}} @endif"></span>
                                        @endif
									</div>
									<div class="tt-content">
										<h6 class="tt-title">@if(app()->getLocale()=="en") {{$mostpopularitem->title_en}} @else {{$mostpopularitem->title_ar}} @endif</h6>
										<div class="tt-price">
											<span class="sale-price"> {{$mostpopularitem->retail_price}} {{__('webMessage.kd')}}</span>
                                            @if($mostpopularitem->old_price)
											<span class="old-price"> {{$mostpopularitem->old_price}} {{__('webMessage.kd')}}</span>
                                            @endif
										</div>
									</div>
								</a>
                             @endforeach   
							</div>
						</div>
					</div>
                   @endif
                   <!--end popular items --> 
                   <!--tags -->
                   @if(!empty($cattags) && count($cattags)>0)
					<div class="tt-collapse open">
						<h3 class="tt-collapse-title">{{strtoupper(__('webMessage.tags'))}}</h3>
						<div class="tt-collapse-content">
							<ul class="tt-list-inline">
                                @foreach($cattags as $cattag)
								<li><a href="javascript:;" class="filter_by_tags" id="{{$cattag}}" mykey="sec">{{$cattag}}</a></li>
                                @endforeach
							</ul>
						</div>
					</div>
                    @endif
                    <!--end tags -->
                    <!--banner-->
                    @php
                    $rightbanner6 = App\Http\Controllers\webController::banners(6);
                    @endphp
                     @if(!empty($rightbanner6->image))
					<div class="tt-content-aside">
						<a href="@if(!empty($rightbanner6->link)) {{$rightbanner6->link}} @else javascript:; @endif" class="tt-promo-03">
							<img src="{{url('assets/images/loader.svg')}}" data-src="{{url('uploads/banner/'.$rightbanner6->image)}}" alt="">
						</a>
					</div>
                    @endif
                    <!--end banner -->
				</div>