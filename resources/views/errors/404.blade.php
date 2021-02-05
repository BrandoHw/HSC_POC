@extends('layouts.appori')

@section('content')
<!-- Wrapper Start -->
<div class="wrapper">
    <div class="container-fluid p-0">
        <div class="row no-gutters">
            <div class="col-sm-12 text-center">
                <div class="iq-error">
                    <img src="{{ asset('template/images/error/01.png') }}" class="img-fluid iq-error-img" alt="">
                    <h2 class="mb-0">Oops! This Page is Not Found.</h2>
                    <p>The requested page dose not exist.</p>
                    <a class="btn btn-primary mt-3" href="{{ route('home') }}"><i class="ri-home-4-line"></i>Back to Home</a>                            
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Wrapper END -->
@endsection