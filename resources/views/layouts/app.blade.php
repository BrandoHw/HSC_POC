<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'WeCare') }}</title>
        
        <link rel="shortcut icon" href="{{ asset('img/icons/wecare.png') }}">
        
        <!-- From mix -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <!-- Template Styles -->
        <!-- Favicon -->
        <link href="{{ asset('template/images/favicon.ico') }}" rel="stylesheet" type="text/plain">
        <!-- Bootstrap CSS -->
        <!-- <link href="{{ asset('template/css/bootstrap.min.css') }}" rel="stylesheet"> -->
        <!-- Typography CSS -->
        <link href="{{ asset('template/css/typography.css') }}" rel="stylesheet">
        <!-- Style CSS -->
        <link href="{{ asset('template/css/style.css') }}" rel="stylesheet">
        <!-- Reponsive CSS -->
        <link href="{{ asset('template/css/responsive.css') }}" rel="stylesheet">
        <!-- Full calendar -->
        <!-- <link href="{{ asset('template/fullcalendar/core/main.css') }} " rel='stylesheet' /> -->
        <!-- <link href="{{ asset('template/fullcalendar/daygrid/main.css') }} " rel='stylesheet' /> -->
        <!-- <link href="{{ asset('template/fullcalendar/timegrid/main.css') }} " rel='stylesheet' /> -->
        <!-- <link href="{{ asset('template/fullcalendar/list/main.css') }} " rel='stylesheet' /> -->
        <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css"> -->

        <!-- Developer Defined Style -->
        <link href="{{ asset('css/custom/datatable.css') }}" rel="stylesheet">

        @yield('style')

        <script src="{{ asset('js/app.js') }}"></script>
        
    </head>
    <body id="body">
        <!-- loader Start -->
        <div id="loading">
            <div id="loading-center" class='loader'>
            </div>
        </div>
        <!-- loader END -->
        <!-- Wrapper Start -->
        <div class="wrapper">
            @include('layouts.partials.nav')
            @include('layouts.partials.header')
            <div id="content-page" class="content-page">
                @yield('content')
            </div>
            @yield('extra')
        </div>
        @include('layouts.partials.footer')
        
        <!-- Required javascript for custom.js -->
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
        <!-- Owl Carousel JavaScript -->
        <script src="{{ asset('template/js/owl.carousel.min.js') }}"></script>
        <!-- Magnific Popup JavaScript -->
        <script src="{{ asset('template/js/jquery.magnific-popup.min.js') }}"></script>
        <!-- Smooth S../crollbar JavaScript -->
        <script src="{{ asset('template/js/smooth-scrollbar.js') }}"></script>
        <!-- lottie JavaScript -->
        <script src="{{ asset('template/js/lottie.js') }}"></script>
        <!-- Custom JavaScript -->
        <script src="{{ asset('template/js/custom.js') }}"></script>

        <!-- Header Js -->
        <script src="{{ asset('js/views/header/header.js')}}"></script>
        <script>
            // Notyf Intial Setting
            var notyf = new Notyf({
                duration: 4000,
                position:{
                    x: 'center',
                    y: 'top',
                },
            });
            
            // Datatable Default Setting
            $.extend(true, $.fn.dataTable.defaults, {
                searching: true,
                paging: true,
                pagingType: 'full_numbers',
                infoCallback: function( settings, start, end, max, total, pre ) {
                    if (0 == max) { return 'No entry found'; }
                    if (total == max) { return start + '-' + end + ' of ' + max + ' entries.'; }
                    return start + '-' + end + ' of ' + total + ' filtered entries.';
                },
                language:{
                    lengthMenu: "_MENU_",
                    paginate:{
                        first: '<i class="las la-angle-double-left">',
                        previous: '<i class="las la-angle-left">',
                        next: '<i class="las la-angle-right">',
                        last: '<i class="las la-angle-double-right">',
                    }
                },
                lengthMenu: [[15, 30, 50, 100], [15, 30, 50, 100]],
                pageLength: 100,
                dom:'rtlpi', //to hide default searchbox but search feature is not disabled hence customised searchbox can be made.
                columnDefs: [{
                    targets: 0,
                    checkboxes: {
                        'selectRow': true
                    },
                    orderable: false,
                }],
                select: {
                    style: 'multi',
                    selector: 'td:first-child'
                }
            })
        </script>
        
        @yield('script')

    </body>
</html>
