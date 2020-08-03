<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    {{-- Meta Tags --}}
    @include('layouts.metaTags')
    @yield('metaTags')

    <title>minaamir.com | @yield('title') </title>

    <!-- Styles -->
    @include('layouts.styles')
    @yield('styles')
</head>
<body>
    <div id="app">
        @if(\Illuminate\Support\Facades\Auth::check())
            {{-- NavBar--}}
            @include('layouts.navBar')
        @endif

        @yield('content')
    </div>

    <!-- Scripts -->
        @include('layouts.scripts')
        @yield('scripts')
</body>
</html>
