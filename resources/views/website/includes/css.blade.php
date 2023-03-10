@php
$settingInfo = App\Http\Controllers\webController::settings();
@endphp
<!--theme1-->
@if ($settingInfo->theme == 1)
    <link rel="stylesheet" href="{{ url('assets/css/theme.css') }}">
    <link rel="stylesheet" href="{{ url('assets/css/gulfweb.css') }}">
    @if (app()->getLocale() == 'ar')
        <link href="{{ url('assets/css/rtl.css') }}" rel="stylesheet">
    @endif
@endif
@if ($settingInfo->theme == 14)
    <link rel="stylesheet" href="{{ url('theme14/css/theme.css') }}">
    <link rel="stylesheet" href="{{ url('theme14/css/gulfweb.css') }}">
    @if (app()->getLocale() == 'ar')
        <link href="{{ url('theme14/css/rtl.css') }}" rel="stylesheet">
    @endif
@endif
<!--end theme1-->
<!--theme2-->
@if ($settingInfo->theme == 2 || $settingInfo->theme == 12)
    <link rel="stylesheet" href="{{ url('hakum_assets/css/gulfweb.css') }}">
    <link rel="stylesheet" href="{{ url('hakum_assets/css/style-skin-toys.css') }}">

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    @if (app()->getLocale() == 'ar')
        <link href="{{ url('hakum_assets/css/rtl.css') }}" rel="stylesheet">
    @endif
@endif
<!--end theme2-->
<!--theme3-->
@if ($settingInfo->theme == 3)
    <link rel="stylesheet" href="{{ url('theme3/css/style.css') }}">
    <link rel="stylesheet" href="{{ url('theme3/css/gulfweb.css') }}">
    @if (app()->getLocale() == 'ar')
        <link href="{{ url('theme3/css/rtl.css') }}" rel="stylesheet">
    @endif
@endif
<!--end theme3-->
<!--theme4-->
@if ($settingInfo->theme == 4)
    <link rel="stylesheet" href="{{ url('theme4/css/theme.css') }}">
    <link rel="stylesheet" href="{{ url('theme4/css/gulfweb.css') }}">
    @if (app()->getLocale() == 'ar')
        <link href="{{ url('theme4/css/rtl.css') }}" rel="stylesheet">
    @endif
@endif
<!--end theme4-->
<!--theme5-->
@if ($settingInfo->theme == 5)
    <link rel="stylesheet" href="{{ url('theme5/css/theme.css') }}">
    <link rel="stylesheet" href="{{ url('theme5/css/gulfweb.css') }}">
    @if (app()->getLocale() == 'ar')
        <link href="{{ url('theme5/css/rtl.css') }}" rel="stylesheet">
    @endif
@endif
<!--end theme5-->
<!--theme6-->
@if ($settingInfo->theme == 6)
    <link rel="stylesheet" href="{{ url('theme6/css/style.css') }}">
    <link rel="stylesheet" href="{{ url('theme6/css/gulfweb.css') }}">
    @if (app()->getLocale() == 'ar')
        <link href="{{ url('theme6/css/rtl.css') }}" rel="stylesheet">
    @endif
@endif
<!--end theme6-->
<!--theme7-->
@if ($settingInfo->theme == 7)
    <link rel="stylesheet" href="{{ url('theme7/css/theme.css') }}">
    <link rel="stylesheet" href="{{ url('theme7/css/gulfweb.css') }}">
    @if (app()->getLocale() == 'ar')
        <link href="{{ url('theme7/css/rtl.css') }}" rel="stylesheet">
    @endif
@endif

<!--end theme7-->

<!--theme8-->
@if ($settingInfo->theme == 8)
    <link rel="stylesheet" href="{{ url('theme8/css/style.css') }}">
    <link rel="stylesheet" href="{{ url('theme8/css/gulfweb.css') }}">
    @if (app()->getLocale() == 'ar')
        <link href="{{ url('theme8/css/rtl.css') }}" rel="stylesheet">
    @endif
@endif

<!--end theme8-->

<!--theme8-->
@if ($settingInfo->theme == 15)
    <link rel="stylesheet" href="{{ url('theme15/css/style.css') }}">
    <link rel="stylesheet" href="{{ url('theme15/css/gulfweb.css') }}">
    @if (app()->getLocale() == 'ar')
        <link href="{{ url('theme15/css/rtl.css') }}" rel="stylesheet">
    @endif
@endif

<!--end theme8-->

<!--theme8-->
@if ($settingInfo->theme == 9)
    <link rel="stylesheet" href="{{ url('theme9/css/style.css') }}">
    <link rel="stylesheet" href="{{ url('theme9/css/gulfweb.css') }}">
    @if (app()->getLocale() == 'ar')
        <link href="{{ url('theme9/css/rtl.css') }}" rel="stylesheet">
    @endif
@endif

<!--end theme8-->

<!--theme8-->
@if ($settingInfo->theme == 10)
    <link rel="stylesheet" href="{{ url('theme10/css/style.css') }}">
    <link rel="stylesheet" href="{{ url('theme10/css/gulfweb.css') }}">
    @if (app()->getLocale() == 'ar')
        <link href="{{ url('theme10/css/rtl.css') }}" rel="stylesheet">
    @endif
@endif


@if ($settingInfo->theme == 11)
    <link rel="stylesheet" href="{{ url('theme11/css/style.css') }}">
    <link rel="stylesheet" href="{{ url('theme11/css/gulfweb.css') }}">
    @if (app()->getLocale() == 'ar')
        <link href="{{ url('theme11/css/rtl.css') }}" rel="stylesheet">
    @endif
@endif


<!--end theme8-->
<!--theme6-->
@if ($settingInfo->theme == 13)
    <link rel="stylesheet" href="{{ url('theme13/css/style.css') }}">
    <link rel="stylesheet" href="{{ url('theme13/css/gulfweb.css') }}">
    @if (app()->getLocale() == 'ar')
        <link href="{{ url('theme13/css/rtl.css') }}" rel="stylesheet">
    @endif
@endif
<!--end theme6-->

<!--theme16-->
@if ($settingInfo->theme == 16)
    <link rel="stylesheet" href="{{ url('theme16/css/style.css') }}">
    <link rel="stylesheet" href="{{ url('theme16/css/gulfweb.css') }}">
    @if (app()->getLocale() == 'ar')
        <link href="{{ url('theme16/css/rtl.css') }}" rel="stylesheet">
    @endif
@endif
<!--end theme16-->

<!--theme17-->
@if ($settingInfo->theme == 17)
{{-- <link rel="stylesheet" href="{{ url('theme17/css/gulfweb.css') }}"> --}}
    <link rel="stylesheet" href="{{ url('theme17/css/custom.css') }}">
    <link rel="stylesheet" href="{{ url('theme17/css/style-skin-toys.css') }}">

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    
    {{-- @if (app()->getLocale() == 'ar')
        <link href="{{ url('hakum_assets/css/rtl.css') }}" rel="stylesheet">
    @endif --}}
@endif
<!--end theme17-->



<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<link href="{{ url('assets/datetime/jquery.datetimepicker.min.css') }}" rel="stylesheet">

