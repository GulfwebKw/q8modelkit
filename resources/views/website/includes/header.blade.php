@php
if(!empty(app()->getLocale())){ $strLang = app()->getLocale();}else{$strLang="en";}
@endphp
<!--theme1 start-->
@if($settingInfo->theme==1) 
@include("website.includes.header_theme1")
@endif

@if($settingInfo->theme==7) 
@include("website.includes.header_theme7")
@endif
<!--theme1 end-->
@if($settingInfo->theme==4) 
<!--theme2 start -->
@include("website.includes.header_theme4")
<!--theme2 end -->
@endif

@if($settingInfo->theme==5) 
<!--theme2 start -->
@include("website.includes.header_theme5")
<!--theme2 end -->
@endif

@if($settingInfo->theme==6) 
<!--theme6 start -->
@include("website.includes.header_theme6")
<!--theme6 end -->
@endif

@if($settingInfo->theme==13) 
<!--theme2 start -->
@include("website.includes.header_theme13")
<!--theme2 end -->
@endif

@if($settingInfo->theme==14) 
<!--theme2 start -->
@include("website.includes.header_theme14")
<!--theme2 end -->
@endif


@if($settingInfo->theme==15) 
<!--theme2 start -->
@include("website.includes.header_theme15")
<!--theme2 end -->
@endif


@if($settingInfo->theme==2 || $settingInfo->theme==12) 
<!--theme2 start -->
@include("website.includes.header_theme2")
<!--theme2 end -->
@endif
<!--theme1 start-->
@if($settingInfo->theme==3) 
@if(Route::getCurrentRoute()->getActionName()=="App\Http\Controllers\webController@index")
@include("website.includes.header_theme3")
@else
@include("website.includes.header_theme3_inner")
@endif
@endif

<!--theme 8-->
@if($settingInfo->theme==8) 
@include("website.includes.header_theme8")
@endif

<!--theme 8-->
@if($settingInfo->theme==9) 
@include("website.includes.header_theme9")
@endif


<!--theme 10-->
@if($settingInfo->theme==10) 
@include("website.includes.header_theme10")
@endif

<!--theme 10-->
@if($settingInfo->theme==11) 
@include("website.includes.header_theme11")
@endif


<!--theme 16-->
@if($settingInfo->theme==16) 
@include("website.includes.header_theme16")
@endif

<!--theme 17-->
@if($settingInfo->theme==17) 
@include("website.includes.header_theme17")
@endif