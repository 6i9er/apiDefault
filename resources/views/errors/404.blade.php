@extends('layouts.app')
@section('title'){{getPageNameForTitle('0')}}@stop
@section('fonts')
    <link href="//fonts.googleapis.com/css?family=Raleway:100,200,300,400,500,600,700,800,900" rel="stylesheet">
    <link href="//fonts.googleapis.com/css?family=Poiret+One" rel="stylesheet">
@stop
@section('styles')
{{--    <link rel="stylesheet" href="{{ url('css/404.css') }}">--}}
    <link rel="stylesheet" href="{{ asset(elixir('css/separate/404.css')) }}">
@endsection
@section('content')
    <div class="container demo-2">
        <div class="content">
            <div id="large-header" class="large-header">
                <h1 class="header-w3ls"> {{ trans('staticpages/404.thisPageNotFoud') }}</h1>
                <canvas id="demo-canvas"></canvas>
                <img src="images/owl.gif" alt="flashy" class="w3l">
                <h2 class="main-title">404</h2>
                <p class="w3-agileits2">{{ trans('staticpages/404.We can\'t seem to find the page you\'re looking for.') }}</p>
                <p class="copyright">© 2017 Flashy Error Page. All Rights Reserved | Design by <a href="https://w3layouts.com/" target="_blank">W3layouts</a></p>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
    <!-- js files -->
        {{--<script src="{{ url('js/404.js') }}" ></script>--}}
        <script src="{{ asset(elixir('js/separate/404.js')) }}" ></script>
    <!-- /js files -->
@endsection