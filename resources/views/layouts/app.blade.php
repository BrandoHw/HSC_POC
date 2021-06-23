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
        {{-- <link href="{{ asset('template/images/favicon.ico') }}" rel="stylesheet" type="image/x-icon"> --}}
        <!-- Bootstrap CSS -->
        <!-- <link href="{{ asset('template/css/bootstrap.min.css') }}" rel="stylesheet"> -->
        <!-- Typography CSS -->
        <link href="{{ asset('template/css/typography.css') }}" rel="stylesheet">
        <!-- Style CSS -->
        <link href="{{ asset('template/css/style.css') }}" rel="stylesheet">
        <!-- Reponsive CSS -->
        <link href="{{ asset('template/css/responsive.css') }}" rel="stylesheet">

        <!-- Developer Defined Style -->
        <link href="{{ asset('css/custom/datatable.css') }}" rel="stylesheet">
        <link href="{{ asset('css/custom/select2.css') }}" rel="stylesheet">
        <link href="{{ asset('css/custom/custom.css') }}" rel="stylesheet">

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
        <!-- Smooth S../crollbar JavaScript -->
        <script src="{{ asset('template/js/smooth-scrollbar.js') }}"></script>
        <!-- Owl Carousel -->
        <script src="{{ asset('template/js/owl.carousel.min.js') }}"></script>
        <!-- Lottie -->
        <script src="{{ asset('template/js/lottie.js') }}"></script>
        <!-- Custom JavaScript -->
        <script src="{{ asset('template/js/custom-modified.js') }}"></script>

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
                types: [
                    {
                        type: 'warning',
                        background: 'orange',
                        icon: {
                            className: 'fa fa-exclamation-triangle custom-notyf',
                            tagName: 'i',
                            color: 'white',
                        },
                        dismissible: true,
                        duration: 0
                    }
                ]
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

            // var csrfToken = $('[name="csrf_token"]').attr('content');

            // setInterval(refreshToken, 360000); // 1 hour 

            // function refreshToken(){
            //     $.get('refresh-csrf').done(function(data){
            //         csrfToken = data; // the new token
            //     })
            // }

            $.fn.select2.defaults.set( "theme", "bootstrap" );
            $.fn.select2.defaults.set( "closeOnSelect", true );
            $.fn.select2.defaults.set( "placeholder", "Please select..." );
        </script>
        
        @yield('script')

    </body>
</html>
