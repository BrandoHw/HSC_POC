<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'WECare') }}</title>
        
        <link rel="shortcut icon" href="{{ asset('img/icons/wecare.png') }}">
        
        <!-- Favicon -->
        <link rel="shortcut icon" href="{{ asset('template/images/favicon.ico') }}" style="" />
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="{{ asset('template/css/bootstrap.min.css') }}">
        <!-- Typography CSS -->
        <link rel="stylesheet" href="{{ asset('template/css/typography.css') }}">
        <!-- Style CSS -->
        <link rel="stylesheet" href="{{ asset('template/css/style.css') }}">
        <!-- Responsive CSS -->
        <link rel="stylesheet" href="{{ asset('template/css/responsive.css') }}">
    </head>
    <body>
        <!-- loader Start -->
        <div id="loading">
            <div id="loading-center">
            </div>
        </div>
        <!-- loader END -->
        @yield('content')

        <!-- Optional JavaScript -->
        <!-- Developer Defined Script -->
        <script src="{{ asset('js/app.js') }}"></script>

        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <!-- <script src="{{ asset('template/js/jquery.min.js') }}"></script>
        <script src="{{ asset('template/js/popper.min.js') }}"></script>
        <script src="{{ asset('template/js/bootstrap.min.js') }}"></script> -->
        <!-- Appear JavaScript -->
        <script src="{{ asset('template/js/jquery.appear.js') }}"></script>
        <!-- Countdown JavaScript -->
        <script src="{{ asset('template/js/countdown.min.js') }}"></script>
        <!-- Counterup JavaScript -->
        <script src="{{ asset('template/js/waypoints.min.js') }}"></script>
        <script src="{{ asset('template/js/jquery.counterup.min.js') }}"></script>
        <!-- Wow JavaScript -->
        <script src="{{ asset('template/js/wow.min.js') }}"></script>
        <!-- Apexcharts JavaScript -->
        <script src="{{ asset('template/js/apexcharts.js') }}"></script>
        <!-- Slick JavaScript -->
        <script src="{{ asset('template/js/slick.min.js') }}"></script>
        <!-- Select2 JavaScript -->
        <script src="{{ asset('template/js/select2.min.js') }}"></script>
        <!-- Owl Carousel JavaScript -->
        <script src="{{ asset('template/js/owl.carousel.min.js') }}"></script>
        <!-- Magnific Popup JavaScript -->
        <script src="{{ asset('template/js/jquery.magnific-popup.min.js') }}"></script>
        <!-- Smooth S../crollbar JavaScript -->
        <script src="{{ asset('template/js/smooth-scrollbar.js') }}"></script>
        <!-- lottie JavaScript -->
        <script src="{{ asset('template/js/lottie.js') }}"></script>
        <!-- Chart Custom JavaScript -->
        <script src="{{ asset('template/js/chart-custom.js') }}"></script>
        <!-- Custom JavaScript -->
        <script src="{{ asset('template/js/custom.js') }}"></script>
    </body>
</html>
