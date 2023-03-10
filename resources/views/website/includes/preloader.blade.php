<div id="loader-wrapper">
    {{-- for Tstore Ecomm --}}
    <div id="loader">
        @if ($settingInfo->theme == 6)
            <div class="dot"><img class="tt-retina" src="{{ url('uploads/preloads/1.png') }}" width="30"
                    height="30"></div>
            <div class="dot"><img class="tt-retina" src="{{ url('uploads/preloads/2.png') }}" width="30"
                    height="30"></div>
            <div class="dot"><img class="tt-retina" src="{{ url('uploads/preloads/3.png') }}" width="30"
                    height="30"></div>
            <div class="dot"><img class="tt-retina" src="{{ url('uploads/preloads/4.png') }}" width="30"
                    height="30"></div>
            <div class="dot"><img class="tt-retina" src="{{ url('uploads/preloads/5.png') }}" width="30"
                    height="30"></div>
            <div class="dot"><img class="tt-retina" src="{{ url('uploads/preloads/6.png') }}"
                    width="30" height="30"></div>
        @elseif ($settingInfo->theme == 17)
            <img src="{{ url('assets/images/hakumNewAssets/loader.gif') }}" alt="" style="width:200px; height:auto;">
        @else
            <div class="dot"></div>
            <div class="dot"></div>
            <div class="dot"></div>
            <div class="dot"></div>
            <div class="dot"></div>
            <div class="dot"></div>
        @endif
    </div>
</div>
