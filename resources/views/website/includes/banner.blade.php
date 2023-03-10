@if ($settingInfo->theme == 1 || $settingInfo->theme == 5 || $settingInfo->theme == 13 || $settingInfo->theme == 6 || $settingInfo->theme == 7)
    @include("website.includes.banner_theme1")
@endif

@if ($settingInfo->theme == 2 || $settingInfo->theme == 12)
    @include("website.includes.banner_theme2")
@endif

@if ($settingInfo->theme == 3)
    @include("website.includes.banner_theme3")
@endif

@if ($settingInfo->theme == 4)
    @include("website.includes.banner_theme4")
@endif

@if ($settingInfo->theme == 8)
    @include("website.includes.banner_theme8")
@endif

@if ($settingInfo->theme == 10)
    @include("website.includes.banner_theme10")
@endif

@if ($settingInfo->theme == 11)
    @include("website.includes.banner_theme11")
@endif

@if ($settingInfo->theme == 14)
    @include("website.includes.banner_theme14")
@endif

@if ($settingInfo->theme == 15)
    @include("website.includes.banner_theme15")
@endif

@if ($settingInfo->theme == 16)
    @include("website.includes.banner_theme16")
@endif
