@php
$settingInfo = App\Http\Controllers\webController::settings();
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>@if (app()->getLocale() == 'en') {{ $settingInfo->name_en }} @else {{ $settingInfo->name_ar }} @endif | {{ __('webMessage.signup') }}</title>
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
    <style>
        .g-recaptcha {
            transform: scale(0.90);
            transform-origin: 0 0;
        }

    </style>
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
                <li>{{ __('webMessage.signup') }}</li>
            </ul>
        </div>
    </div>
    <div id="tt-pageContent">
        <div class="container-indent">
            <div class="container">
                <h1 class="tt-title-subpages noborder">{{ __('webMessage.createanaccount') }}</h1>
                <div class="tt-login-form">
                    <div class="row justify-content-center">
                        <div class="col-md-8 col-lg-6">
                            <div class="tt-item">

                                <div class="form-default">
                                    <form id="customer_reg_form" method="post"
                                        action="{{ route('registerform', ['locale' => app()->getLocale()]) }}">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <h2 class="tt-title">{{ __('webMessage.personal_information') }}</h2>
                                        <div class="form-group">
                                            <label for="name">{{ __('webMessage.name') }}*</label>
                                            <input type="text" name="name" class="form-control @if ($errors->has('name')) error @endif"
                                                id="name" placeholder="{{ __('webMessage.enter_name') }}"
                                                value="{{ old('name') }}">
                                            @if ($errors->has('name'))
                                                <label class="error"
                                                    for="name">{{ $errors->first('name') }}</label>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label for="email">{{ __('webMessage.email') }}*</label>
                                            <input type="email" name="email"
                                                class="form-control @if ($errors->has('email')) error @endif" id="email"
                                                placeholder="{{ __('webMessage.enter_email') }}"
                                                value="{{ old('email') }}">
                                            @if ($errors->has('email'))
                                                <label class="error"
                                                    for="email">{{ $errors->first('email') }}</label>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label for="mobile">{{ __('webMessage.mobile') }}*</label>
                                            <input type="text" name="mobile"
                                                class="form-control @if ($errors->has('mobile')) error @endif" id="mobile"
                                                placeholder="{{ __('webMessage.enter_mobile') }}"
                                                value="{{ old('mobile') }}">
                                            @if ($errors->has('mobile'))
                                                <label class="error"
                                                    for="mobile">{{ $errors->first('mobile') }}</label>
                                            @endif
                                        </div>
                                        <h2 class="tt-title">{{ __('webMessage.login_information') }}</h2>

                                        <div class="form-group">
                                            <label for="username">{{ __('webMessage.username') }}*</label>
                                            <input type="text" name="username"
                                                class="form-control @if ($errors->has('username')) error @endif" id="username"
                                                placeholder="{{ __('webMessage.enter_username') }}"
                                                value="{{ old('username') }}">
                                            @if ($errors->has('username'))
                                                <label class="error"
                                                    for="username">{{ $errors->first('username') }}</label>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label for="password">{{ __('webMessage.password') }}*</label>
                                            <input type="password" name="password"
                                                class="form-control @if ($errors->has('password')) error @endif" id="password"
                                                placeholder="{{ __('webMessage.enter_password') }}"
                                                value="{{ old('password') }}">
                                            @if ($errors->has('password'))
                                                <label class="error"
                                                    for="password">{{ $errors->first('password') }}</label>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <div class="checkbox-group">
                                                <input type="checkbox" id="is_newsletter_active"
                                                    name="is_newsletter_active" value="1">
                                                <label for="is_newsletter_active"><span
                                                        class="check"></span><span
                                                        class="box"></span>&nbsp;{{ __('webMessage.subscribe_for_newletter') }}</label>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="g-recaptcha"
                                                data-sitekey="6LeMueQUAAAAAJ-ZUe9ZqGK3pma9VwbeoaYDgJte"></div>
                                            @if ($errors->has('recaptchaError'))
                                                <label id="message-error" class="error"
                                                    for="message">{{ $errors->first('recaptchaError') }}</label>
                                            @endif
                                        </div>



                                        <div class="row">
                                            <div class="col-auto">
                                                <div class="form-group">
                                                    <button class="btn btn-border datalayerRegister"
                                                        type="submit">{{ __('webMessage.create') }}</button>
                                                </div>
                                            </div>
                                            <div class="col-auto align-self-center">
                                                <div class="form-group">
                                                    <ul class="additional-links">
                                                        <li>{{ __('webMessage.or') }} <a
                                                                href="{{ url(app()->getLocale() . '/login') }}">{{ __('webMessage.returntosignin') }}</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>
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
    <script src='https://www.google.com/recaptcha/api.js'></script>
    @if ($settingInfo->theme != 17)
        <script defer src="{{ url('hakum_assets/js/bundle.js') }}"></script>
    @endif
    <script src="{{ url('assets/js/gulfweb.js') }}"></script>
    <script>
        $(document).on("click", ".datalayerRegister", function() {

            gtag("event", "sign_up", {
                method: "Website",
                name: $("#name").val(),
                mobile: $("#mobile").val(),
                email: $("#email").val(),
            });

        });
    </script>
</body>

</html>
