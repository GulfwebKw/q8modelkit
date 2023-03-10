@php
$leftbanners = App\Http\Controllers\webController::banners();
@endphp

@if (!empty($leftbanners) && count($leftbanners) > 0)
    <div class="container-indent nomargin">
        <div class="container-fluid-custom">
            <div class="row">
                @foreach ($leftbanners as $leftbanner)

                    <div class="col-6 col-sm-6 col-md-3 col-12-575width">
                        @if (!empty($leftbanner->image))
                            <a href="{{ !empty($leftbanner->link) ? $leftbanner->link : 'javascript:;' }}"
                                class="tt-promo-box tt-one-child">
                                <img src="{{ url('assets/images/loader.svg') }}"
                                    data-src="{{ url('uploads/banner/' . $leftbanner->image) }}" alt=""
                                    class="loaded">
                                <div class="tt-description">
                                    <div class="tt-description-wrapper">
                                        <div class="tt-background"></div>
                                        <div class="tt-title-small">
                                            {{ Common::getLangString($leftbanner->title_en, $leftbanner->title_ar) }}
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endif
                    </div>

                @endforeach
            </div>
        </div>
    </div>
@endif


