<!-- navbar -->
<nav class="navbar navbar-expand-lg fixed-top">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            <img src="{{ getFile(basicControl()->logo_driver, basicControl()->logo) }}" alt="logo">
        </a>
        <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation" style="top: 45px !important">
            <i class="fal fa-bars"></i>
        </button>
        @php
            $uriSegments = explode("/", parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
            $lastUriSegment = array_pop($uriSegments);
        @endphp
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                {!! renderHeaderMenu(getHeaderMenuData()) !!}
            </ul>
        </div>

        <div class="navbar-text d-flex gap-2">
            @guest
                <a href="{{ route('login') }}" class="btn-custom" style="font-size: x-small;">@lang('Crear anuncio')</a>
            @endguest

            @auth
                <a href="{{ route('user.listings') }}" class="btn-custom"
                    style="font-size: x-small;">@lang('Crear anuncio')</a>
            @endauth

            @guest
                <a href="{{ route('login') }}" class="btn-custom" style="font-size: x-small;">@lang('Sign in')</a>
            @endguest

            @auth
                <a href="{{ route('user.dashboard') }}" class="btn-custom"
                    style="font-size: x-small;">@lang('Dashboard')</a>
            @endauth
        </div>
    </div>
</nav>

<!-- Bottom Mobile Menu -->
<!-- <ul class="nav bottom-nav fixed-bottom d-lg-none">
    <li class="nav-item">
        <a class="nav-link @if(request()->is('blogs')) active @endif" href="{{ route('blogs') }}"><i
                class="far fa-planet-ringed"></i></a>
    </li>
    <li class="nav-item">
        <a class="nav-link @if(request()->is('/')) active @endif" href="{{ url('/') }}"><i class="far fa-house"></i></a>
    </li>
    <li class="nav-item">
        <a class="nav-link @if(request()->is('contact')) active @endif" href="{{ url('/contact') }}"><i
                class="far fa-address-book"></i></a>
    </li>
    <li class="nav-item">
        <a class="nav-link @if(request()->routeIs('user.profile')) active @endif" href="{{ route('user.profile') }}"><i
                class="far fa-user"></i></a>
    </li>
</ul> -->
<!-- Bottom Mobile Menu -->