<nav class="mobile-bottom-nav">
    <div class="mobile-bottom-nav__item ">
        <a href="{{ url('/' . app()->getLocale()) }}">

            <div
                class="mobile-bottom-nav__item-content {{ Request::path() == app()->getLocale() ? 'mobile-bottom-nav__item--active mobile_acive' : '' }}">
                <img src="{{Request::path() == app()->getLocale()?url('assets/images/icon-svg/homeop.svg'):url('assets/images/icon-svg/home.svg')}}" class="home_ico" alt="">
                Home
            </div>
        </a>
    </div>

    <div class="mobile-bottom-nav__item ">
        <a href="{{ url(app()->getLocale() . '/categories') }}">
            <div
                class="mobile-bottom-nav__item-content {{ substr(Request::path(), 2) == '/categories' ? 'mobile-bottom-nav__item--active mobile_acive' : '' }}">
                <img src="{{ substr(Request::path(), 2) == '/categories' ? url('assets/images/icon-svg/categoriesop.svg') : url('assets/images/icon-svg/categories.svg') }}"
                    class="category_ico" alt="">
                Categories
            </div>
        </a>
    </div>
    <div class="mobile-bottom-nav__item ">
        <a href="{{ url(app()->getLocale() . '/wishlist') }}">
            <div
                class="mobile-bottom-nav__item-content {{ substr(Request::path(), 2) == '/wishlist' ? 'mobile-bottom-nav__item--active mobile_acive' : '' }} ">
                <img src="{{substr(Request::path(), 2) == '/wishlist' ?  url('assets/images/icon-svg/favoriteop.svg'):url('assets/images/icon-svg/favorite.svg') }}" class="favo_ico" alt="">
                Wishlist
            </div>
        </a>
    </div>

    <div class="mobile-bottom-nav__item" data-tposition="bottom">
        <a href="{{ url(app()->getLocale() . '/checkout') }}" class="tt-dropdown-toggle tt-dropdown-toggle">
            <div
                class="mobile-bottom-nav__item-content {{ substr(Request::path(), 2) == '/checkout' ? 'mobile-bottom-nav__item--active mobile_acive' : '' }} ">
                <img src="{{substr(Request::path(), 2) == '/checkout' ? url('assets/images/icon-svg/checkoutop.svg'):url('assets/images/icon-svg/checkout.svg')  }}" class="check_ico" alt="">
                Checkout
            </div>
        </a>
    </div>
</nav>
