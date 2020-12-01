<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'WECare') }}</title>
	
	<link rel="shortcut icon" href="{{ asset('img/icons/wecare.png') }}">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">
	
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        .dataTables_wrapper .dataTables_length {
            float: right;
        }
        .dataTables_wrapper .dataTables_filter {
            float: left;
            text-align: left !important;
            margin-bottom: 0.5em !important;
        }
        .dataTables_wrapper .dataTables_info{
            float: left;
        }
        .dataTables_wrapper .dataTables_paginate{
            float: right;
        }
        .dataTables_wrapper .dt-buttons {
            float: right;
            margin-bottom: 6px !important;
        }
    </style>
    @yield('style')
    
    <!--j Query -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    
</head>
<body>
    <div class="wrapper">
		@include('layouts.partials.nav')

		<div class="main">
			@include('layouts.partials.header')
            <main class="content">
                @yield('content')
            </main>
            @include('layouts.partials.footer')
		</div>
	</div>
    
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        var notyf = new Notyf({
            duration: 4000,
            position:{
                x: 'center',
                y: 'top',
            },
        });
    </script>
    
    @yield('script')

</body>
</html>
