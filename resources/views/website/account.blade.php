@php
$settingInfo = App\Http\Controllers\webController::settings();
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>@if (app()->getLocale() == 'en') {{ $settingInfo->name_en }} @else {{ $settingInfo->name_ar }} @endif | {{ __('webMessage.dashboard') }}</title>
    <meta name="description" content="@if (app()->getLocale() == 'en') {{ $settingInfo->seo_description_en }} @else {{ $settingInfo->seo_description_ar }} @endif" />
    <meta name="abstract" content="@if (app()->getLocale() == 'en') {{ $settingInfo->seo_description_en }} @else {{ $settingInfo->seo_description_ar }} @endif">
    <meta name="keywords" content="@if (app()->getLocale() == 'en') {{ $settingInfo->seo_keywords_en }} @else {{ $settingInfo->seo_keywords_ar }} @endif" />
    <meta name="Copyright" content="{{ $settingInfo->name_en }}, Kuwait Copyright 2020 - {{ date('Y') }}" />
    <META NAME="Geography" CONTENT="@if (app()->getLocale() == 'en') {{ $settingInfo->address_en }} @else {{ $settingInfo->address_ar }} @endif">
    @if ($settingInfo->extra_meta_tags) {!! $settingInfo->extra_meta_tags !!} @endif
    @if ($settingInfo->favicon)
        <link rel="icon" href="{{ url('uploads/logo/' . $settingInfo->favicon) }}">
    @endif
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    @include("website.includes.css")
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <!--preloader -->
    @include("website.includes.preloader")
    <!--end preloader -->
    <!--header -->
    @include("website.includes.header")
    <!--end header -->
    <div class="tt-breadcrumb">
        <div class="container">
            <ul>
                <li><a href="{{ url(app()->getLocale() . '/') }}">{{ __('webMessage.home') }}</a></li>
                <li>{{ __('webMessage.dashboard') }}</li>
            </ul>
        </div>
    </div>
    <div id="tt-pageContent">
        <div class="container-indent">
            <div class="container container-fluid-custom-mobile-padding">
                <h1 class="tt-title-subpages noborder">{{ __('webMessage.dashboard') }}</h1>
                <div class="tt-shopping-layout">

                    <ul class="list-form-inline">
                        <li><a class="btn-link" href="{{ url(app()->getLocale() . '/account') }}"><i
                                    class="icon-f-94"></i> {{ __('webMessage.dashboard') }}</a></li>
                        <li><a class="btn-link" href="{{ url(app()->getLocale() . '/changepass') }}"><i
                                    class="icon-g-40"></i> {{ __('webMessage.changepassword') }}</a></li>
                        <li><a class="btn-link" href="{{ url(app()->getLocale() . '/editprofile') }}"><i
                                    class="icon-01"></i> {{ __('webMessage.editprofile') }}</a></li>
                        <li><a class="btn-link" href="{{ url(app()->getLocale() . '/myorders') }}"><i
                                    class="icon-f-68"></i> {{ __('webMessage.myorders') }}</a></li>
                        <li><a class="btn-link" href="{{ url(app()->getLocale() . '/wishlist') }}"><i
                                    class="icon-n-072"></i> {{ __('webMessage.wishlists') }}</a></li>
                    </ul>

                    <div class="tt-wrapper">
                        <h3 class="tt-title">{{ __('webMessage.accountdetails') }} <a
                                title="{{ __('webMessage.editprofile') }}"
                                href="{{ url(app()->getLocale() . '/editprofile') }}"
                                class="btn-link @if (app()->getLocale() == 'en') float-right @else float-left @endif"><i class="icon-01"></i></a></h3>
                        <div class="tt-table-responsive">
                            <table class="tt-table-shop-02">
                                <tbody>
                                    @if (Auth::guard('webs')->user()->name)
                                        <tr>
                                            <td>{{ __('webMessage.name') }}:</td>
                                            <td>{{ Auth::guard('webs')->user()->name }} @if (!empty(Auth::guard('webs')->user()->is_seller))(SELLER)@endif</td>
                                        </tr>
                                    @endif
                                    @if (Auth::guard('webs')->user()->email)
                                        <tr>
                                            <td>{{ __('webMessage.email') }}:</td>
                                            <td>{{ Auth::guard('webs')->user()->email }}</td>
                                        </tr>
                                    @endif
                                    @if (Auth::guard('webs')->user()->mobile)
                                        <tr>
                                            <td>{{ __('webMessage.mobile') }}:</td>
                                            <td>{{ Auth::guard('webs')->user()->mobile }}</td>
                                        </tr>
                                    @endif
                                    @if (Auth::guard('webs')->user()->username)
                                        <tr>
                                            <td>{{ __('webMessage.username') }}:</td>
                                            <td>{{ Auth::guard('webs')->user()->username }}</td>
                                        </tr>
                                    @endif
                                    @if (Auth::guard('webs')->user()->created_at)
                                        <tr>
                                            <td>{{ __('webMessage.created') }}:</td>
                                            <td>{{ \Carbon\Carbon::parse(Auth::guard('webs')->user()->created_at)->diffForHumans() }}
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>

                        <a href="{{ url(app()->getLocale() . '/newaddress') }}"
                            class="btn btn-border">{{ __('webMessage.addnewaddress') }}</a>
                        @if (session('session_msg')) <br clear="all"><br
                                clear="all">
                            <div class="alert-success">{{ session('session_msg') }}</div>
                        @endif
                        @if (session('session_msg_f')) <br clear="all"><br
                                clear="all">
                            <div class="alert-danger">{{ session('session_msg_f') }}</div>
                        @endif
                        @if (!empty($customerAddress) && count($customerAddress) > 0)
                            <br clear="all"><br clear="all">
                            <div class="row">
                                @foreach ($customerAddress as $customerAddr)
                                    @php
                                        $address = App\Http\Controllers\accountController::getCustAddress($customerAddr->id);
                                    @endphp
                                    <div class="col-lg-4" @if (!empty($customerAddr->is_default)) style="border:2px #0000FF solid;" @endif>
                                        <br clear="all">
                                        <h3 class="tt-title">{{ $customerAddr->title }}
                                            <a title="{{ __('webMessage.edit') }}"
                                                href="{{ url(app()->getLocale() . '/editaddress/' . $customerAddr->id) }}"
                                                class="@if (app()->getLocale() == 'en') float-right @else float-left @endif btn-link"
                                                title="{{ __('webMessage.edit') }}"><i
                                                    class="icon-01"></i></a>
                                            <a title="{{ __('webMessage.delete') }}"
                                                href="{{ url(app()->getLocale() . '/addressdelete/' . $customerAddr->id) }}"
                                                id="{{ $customerAddr->id }}"
                                                class="deletemyAddress @if (app()->getLocale() == 'en') float-right @else float-left @endif btn-link"
                                                title="{{ __('webMessage.delete') }}"><i
                                                    class="icon-02"></i></a>
                                        </h3>
                                        {!! $address !!}
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--footer-->
    @include("website.includes.footer")



    <!-- modal (AddToCartProduct) -->
    @include("website.includes.addtocart_modal")

    <script src="{{ url('assets/external/jquery/jquery.min.js') }}"></script>
    <script src="{{ url('assets/external/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ url('assets/external/slick/slick.min.js') }}"></script>
    <script src="{{ url('assets/external/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ url('assets/external/panelmenu/panelmenu.js') }}"></script>
    <script src="{{ url('assets/external/instafeed/instafeed.min.js') }}"></script>
    <script src="{{ url('assets/external/countdown/jquery.plugin.min.js') }}"></script>
    <script src="{{ url('assets/external/countdown/jquery.countdown.min.js') }}"></script>
    <script src="{{ url('assets/external/rs-plugin/js/jquery.themepunch.tools.min.js') }}"></script>
    <script src="{{ url('assets/external/rs-plugin/js/jquery.themepunch.revolution.min.js') }}"></script>
    <script src="{{ url('assets/external/lazyLoad/lazyload.min.js') }}"></script>
    <script src="{{ url('assets/js/main.js') }}"></script>

    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    @if ($settingInfo->theme != 17)
        <script defer src="{{ url('hakum_assets/js/bundle.js') }}"></script>
    @endif
    <script src="{{ url('assets/js/gulfweb.js') }}"></script>

</body>

</html>
