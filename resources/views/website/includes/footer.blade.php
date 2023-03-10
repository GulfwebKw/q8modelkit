@php
$footerMenusTrees = App\Categories::CategoriesTree();
$footerAboutDetails = App\Http\Controllers\webController::singlePageDetails(4);
$privacy_details = App\Http\Controllers\webController::singlePageDetails(2);
$terms_details = App\Http\Controllers\webController::singlePageDetails(3);
$about_details = App\Http\Controllers\webController::singlePageDetails(1);
@endphp

@if ($settingInfo->social_whatsapp && $settingInfo->theme != 17)
    @php
        if ($settingInfo->theme == 1) {
            $curtime = strtotime(date('h:i a'));
            $firsttime1 = strtotime('09:00 am');
            $firsttime2 = strtotime('09:00 pm');
        
            if ($curtime >= $firsttime1 && $curtime < $firsttime2) {
                $whatsAppNumber = '66615592';
            } else {
                $whatsAppNumber = '94192932';
            }
        } else {
            $whatsAppNumber = $settingInfo->social_whatsapp;
        }
        
    @endphp

    @if ($settingInfo->is_float_store_location == 1)
        <a href="{{ url('/contactus') }}" class="float_map" target="_blank"><img width="50" height="50"
                src="{{ asset('/assets/images/maps60.png') }}"></a>
    @endif



    @if (!empty($settingInfo->is_float_whatsapp))
        @if ($settingInfo->theme == 15)
            <a href="https://api.whatsapp.com/send?phone={{ $whatsAppNumber }}&text={{ __('webMessage.whatsappsharetext') }}"
                class="float shareDataLayer" title="{{ trans('webMessage.whatsapp') }}" id="{{ $whatsAppNumber }}"
                target="_blank"><img width="50" height="50" src="{{ asset('/assets/images/whatsapp.png') }}"></a>
        @else
            <a href="https://api.whatsapp.com/send?phone={{ $whatsAppNumber }}&text={{ __('webMessage.whatsappsharetext') }}"
                class="float shareDataLayer" title="{{ trans('webMessage.whatsapp') }}" id="{{ $whatsAppNumber }}"
                target="_blank"><img width="60" height="60"
                    src="data:image/png;base64,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" /></a>
        @endif
    @endif
@endif

@if ($settingInfo->theme == 1 || $settingInfo->theme == 4 || $settingInfo->theme == 5 || $settingInfo->theme == 6 || $settingInfo->theme == 13 || $settingInfo->theme == 14 || $settingInfo->theme == 7)
    @include("website.includes.footer_theme1")
@endif

@if ($settingInfo->theme == 2 || $settingInfo->theme == 12)
    @include("website.includes.footer_theme2")
@endif

@if ($settingInfo->theme == 3)
    @include("website.includes.footer_theme3")
@endif

@if ($settingInfo->theme == 8)
    @include("website.includes.footer_theme8")
@endif

@if ($settingInfo->theme == 9)
    @include("website.includes.footer_theme9")
@endif

@if ($settingInfo->theme == 10)
    @include("website.includes.footer_theme10")
@endif

@if ($settingInfo->theme == 11)
    @include("website.includes.footer_theme11")
@endif

@if ($settingInfo->theme == 15)
    @include("website.includes.footer_theme15")
@endif

@if ($settingInfo->theme == 16)
    @include("website.includes.footer_theme16")
@endif
@if ($settingInfo->theme == 17)
    @include("website.includes.footer_theme17")
    @include("website.includes.footer_nav_theme17")
    @if ($settingInfo->social_whatsapp)
        @php
            $whatsAppNumber = $settingInfo->social_whatsapp;
        @endphp
        @if (!empty($settingInfo->is_float_whatsapp))
            <a style="position:fixed; bottom:125px; right:10px; z-index:999;"
                href="https://api.whatsapp.com/send?phone={{ $whatsAppNumber }}&text={{ __('webMessage.whatsappsharetext') }}"
                class="" title="{{ trans('webMessage.whatsapp') }}" id="{{ $whatsAppNumber }}"
                target="_blank"><img width="60" height="60"
                    src="data:image/png;base64,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" /></a>
        @endif

    @endif
@endif

<!--order tracking -->
<div class="modal  fade" id="modalPrderTrackBox" tabindex="-1" role="dialog" aria-label="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content ">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span
                        class="icon icon-clear"></span></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="form-group">
                            <input type="text" name="trackorderid" class="form-control" id="trackorderid"
                                placeholder="{{ __('webMessage.enter_order_id') }}" autcomplete="off">

                        </div>
                    </div>
                    <div class="col-lg-2">
                        <input type="button" value="{{ __('webMessage.checknow') }}"
                            class="btn btn-border TrackMyOrders">
                    </div>
                </div>
                <span id="responseTrackOrder"></span>
            </div>
        </div>
    </div>
</div>





<!--click to mode top -->
<a href="javascript:;" class="tt-back-to-top">BACK TO TOP</a>
<!-- google analytics -->
@if ($settingInfo->google_analytics){!! $settingInfo->google_analytics !!}@endif
<!--facebook pixel -->
@if ($settingInfo->facebook_pixel){!! $settingInfo->facebook_pixel !!}@endif
<!---home modal -->
<!-- Modal (newsletter) -->

@include("website.includes.popup_modal")
