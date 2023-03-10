@php
$settingInfo = App\Http\Controllers\webController::settings();
$popUpStatus = $settingInfo->is_home_popup;
@endphp
<!-- Modal (pop up) -->
@if ($popUpStatus)
    @php
        $getPopups = App\Popup::all()->where('is_active', 1);
        $singlePopup = null;
        $singlePopup = sizeof($getPopups) > 0 ? $getPopups->random(1) : null;
    @endphp

    @if ($singlePopup)
        <div class="modal fade" id="homemodalbox" tabindex="-1" role="dialog" aria-label="myModalLabel"
            aria-hidden="true" data-pause=1500>
            <div class="modal-dialog modal-newslettet02">
                <div class="modal-content ">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span
                                class="icon icon-clear"></span></button>
                    </div>
                    <div class="tt-layout-center">
                        @if ($singlePopup[0]->image)
                            <a href="{{ $singlePopup[0]->link ? $singlePopup[0]->link : '' }}" target="_blank">
                                <img class="popup-img"
                                    src="{{ asset('/uploads/popup/' . $singlePopup[0]->image) }}">
                            </a>
                        @endif
                        {{-- <div class="text-simple-01">
                            {{ $singlePopup[0]->title_en }}
                        </div>
                        <div class="text-simple-02">
                            Are you ready to<br>
                            blow up your sales?
                        </div>
                        <div class="text-simple-03">
                        </div>
                        <a href="https://themeforest.net/item/wokiee-ecommerce-html-template/22564267" target="_blank"
                            class="btn btn-popup-simple"><span>YES!</span> I want More Sales on My Store!</a> --}}
                    </div>
                </div>
            </div>
        </div>
    @endif
    <style>
        @media only screen and (max-width: 600px) {
            #homemodalbox .popup-img {
                width: 300px;
            }
        }
        @media only screen and (max-width: 320px) {
            #homemodalbox .popup-img {
                width: 250px;
            }
        }

    </style>
@endif
