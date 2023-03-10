@php
$footerMenusTrees = App\Categories::CategoriesTree();
$footerAboutDetails = App\Http\Controllers\webController::singlePageDetails(4);
$privacy_details = App\Http\Controllers\webController::singlePageDetails(2);
$terms_details = App\Http\Controllers\webController::singlePageDetails(3);
$about_details = App\Http\Controllers\webController::singlePageDetails(1);
$singlePageLinks = App\Http\Controllers\webController::allSinglePagesLinks();
@endphp









<footer class="tt-offset-20" id="tt-footer">
    <div class="tt-footer-col tt-color-scheme-01">



        <div class="container">
            <div class="row">
                <div class="col-md-6 col-lg-4 col-xl-4">
                    <div class="tt-mobile-collapse">
                        <div class="tt-collapse-content">
                            <img src="{{ url('uploads/logo/' . $settingInfo->logo) }}" alt="" class="footer_logo">
                            {{-- <p>
                                Lorem ipsum dolor sit amet conse ctetur adipisicing elit, sed do eiusmod tempor
                                incididunt ut labore et dolore. Lorem ipsum dolor sit amet conse ctetur adipisicing
                                elit, seddo eiusmod tempor incididunt ut labore etdolore.
                            </p> --}}
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-2 col-xl-2">
                    <div class="tt-mobile-collapse">
                        <h4 class="tt-collapse-title">
                            Important Links
                        </h4>
                        <div class="tt-collapse-content">
                            <ul class="tt-list">
                                @foreach ($singlePageLinks as $links)
                                    <li><a target="__blank"
                                            href="{{ url(app()->getLocale() . '/page/' . $links->slug) }}">{{ app()->getLocale() == 'en' ? $links->title_en : $links->title_ar }}</a>
                                    </li>
                                @endforeach
                                <li><a target="__blank" href="{{ url(app()->getLocale() . '/contactus') }}">Contact
                                        Us</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-2 col-xl-2">
                    <div class="tt-mobile-collapse">
                        <h4 class="tt-collapse-title">
                            My Account
                        </h4>
                        <div class="tt-collapse-content">
                            <ul class="tt-list">
                                @if (!empty(Auth::guard('webs')->user()->id))
                                    <li><a
                                            href="{{ url(app()->getLocale() . '/account') }}">{{ __('webMessage.dashboard') }}</a>
                                    </li>
                                    <li><a
                                            href="{{ url(app()->getLocale() . '/changepass') }}">{{ __('webMessage.changepassword') }}</a>
                                    </li>
                                    <li><a
                                            href="{{ url(app()->getLocale() . '/editprofile') }}">{{ __('webMessage.editprofile') }}</a>
                                    </li>
                                    <li><a
                                            href="{{ url(app()->getLocale() . '/myorders') }}">{{ __('webMessage.myorders') }}</a>
                                    </li>
                                    <li><a
                                            href="{{ url(app()->getLocale() . '/wishlist') }}">{{ __('webMessage.wishlist') }}</a>
                                    </li>
                                @else
                                    <li><a
                                            href="{{ url(app()->getLocale() . '/register') }}">{{ __('webMessage.signup') }}</a>
                                    </li>
                                    <li><a
                                            href="{{ url(app()->getLocale() . '/login') }}">{{ __('webMessage.signin') }}</a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 col-xl-4">
                    <div class="tt-newsletter">
                        <div class="tt-mobile-collapse">
                            <h4 class="tt-collapse-title">
                                Contact Us
                            </h4>
                            <div class="tt-collapse-content">
                                <address>
                                    <p><span>Address:</span>{{ app()->getLocale() == 'en' ? $settingInfo->address_en : $settingInfo->address_ar }}
                                    </p>
                                    <p><span>Phone:</span> {{ $settingInfo->phone }},{{ $settingInfo->mobile }}
                                    </p>
                                    <p><span>E-mail:</span> <a
                                            href="mailto:{{ $settingInfo->email }}">{{ $settingInfo->email }}</a>
                                    </p>
                                    <p><span>Our Location:</span> <a
                                            href="{{ url(app()->getLocale() . '/contactus') }}">See
                                            Location</a>
                                    </p>
                                    <p>&nbsp;</p>
                                    <ul class="tt-social-icon footer_social">
                                        @if ($settingInfo->social_facebook)
                                            <li><a target="_blank" href="{{ $settingInfo->social_facebook }}"><img
                                                        src="{{ url('assets/images/icon-svg/facebook.svg') }}"
                                                        style="height:25px;"></a></li>

                                        @endif
                                        @if ($settingInfo->social_twitter)
                                            {{-- <li><a target="_blank" href="https://instagram.com/"><img
                                            src="images/icon-svg/whatsapp.svg"></a></li> --}}
                                        @endif
                                        @if ($settingInfo->social_instagram)
                                            <li><a target="_blank" href="{{ $settingInfo->social_instagram }}"><img
                                                        src="{{ url('assets/images/icon-svg/instagram.svg') }}"></a>
                                            </li>
                                        @endif
                                        @if ($settingInfo->social_linkedin)
                                            {{-- <li><a title="{{ __('webMessage.linkedin') }}" class="icon-g-68"
                                                    target="_blank" href="{{ $settingInfo->social_linkedin }}"></a></li> --}}
                                        @endif
                                        @if ($settingInfo->social_youtube)
                                            {{-- <li><a title="{{ __('webMessage.youtube') }}" class="icon-g-76"
                                                    target="_blank" href="{{ $settingInfo->social_youtube }}"></a></li> --}}
                                        @endif

                                    </ul>
                                </address>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>




    </div>
    <div class="tt-footer-custom">
        <div class="container">
            <div class="tt-row">
                <div class="tt-col-left">
                    <div class="tt-col-item">
                        <!-- copyright -->
                        <div class="tt-box-copyright">
                            @if ($settingInfo->copyrights_en && app()->getLocale() == 'en'){!! $settingInfo->copyrights_en !!}@endif
                            @if ($settingInfo->copyrights_ar && app()->getLocale() == 'ar'){!! $settingInfo->copyrights_ar !!}@endif
                        </div>
                        <!-- /copyright -->
                    </div>
                </div>
                <div class="tt-col-right">
                    <div class="tt-col-item">
                        <!-- payment-list -->
                        @if (!empty($settingInfo->payments))
                            @php
                                $payments = explode(',', $settingInfo->payments);
                            @endphp
                            <ul class="tt-payment-list">
                                @foreach ($payments as $payment)
                                    <li><a href="javascript:;"><img
                                                src="{{ url('uploads/paymenticons/' . strtolower($payment) . '.png') }}"
                                                height="30" alt=""></a></li>
                                @endforeach
                            </ul>
                        @endif
                        <!-- /payment-list -->
                    </div>
                </div>
            </div>
        </div>
    </div>


</footer>




{{-- Modal (newsletter) 
<div class="modal  fade"  id="Modalnewsletter" tabindex="-1" role="dialog" aria-label="myModalLabel" aria-hidden="true"  data-pause=2000>
 <div class="modal-dialog modal-sm">
  <div class="modal-content ">
   <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="icon icon-clear"></span></button>
   </div>
   <form>
    <div class="modal-body no-background">
     <div class="tt-modal-newsletter">
      <div class="tt-modal-newsletter-promo">
       <div class="tt-title-small">BE THE FIRST<br> TO KNOW ABOUT</div>
       <div class="tt-title-large">WOKIEE</div>
       <p>
        HTML FASHION DROPSHIPPING THEME
       </p>
      </div>
      <p>
       By subscribe, you accept the terms &amp; privacy policy<br>
      </p>
      <div class="subscribe-form form-default">
       <div class="row-subscibe">
        <div class="input-group">
         <input type="text" class="form-control" placeholder="Enter your e-mail">
         <button type="submit" class="btn">JOIN US</button>
        </div>
       </div>
       <div class="checkbox-group">
        <input type="checkbox" id="checkBox1">
        <label for="checkBox1">
         <span class="check"></span>
         <span class="box"></span>
         Don’t Show This Popup Again
        </label>
       </div>
      </div>
     </div>
    </div>
   </form>
  </div>
 </div>
</div> --}}

{{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script> --}}
{{-- <script>
    window.jQuery || document.write('<script src="external/jquery/jquery.min.js"><\/script>')
</script> --}}


<script id="rendered-js">
    var navItems = document.querySelectorAll(".mobile-bottom-nav__item");
    navItems.forEach(function(e, i) {
        e.addEventListener("click", function(e) {
            navItems.forEach(function(e2, i2) {
                e2.classList.remove("mobile-bottom-nav__item--active");
            });
            this.classList.add("mobile-bottom-nav__item--active");
        });
    });
</script>

{{-- <a href="#" class="tt-back-to-top" id="js-back-to-top">BACK TO TOP</a> --}}
