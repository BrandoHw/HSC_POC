@extends('layouts.appori')

@section('content')
<!-- Wrapper Start -->
<div class="wrapper">
    <div class="container-fluid p-0">
        <div class="row no-gutters">
            <div class="col-sm-12 text-center">
                <div class="iq-error error-500">
                    <img src="images/error/03.png" class="img-fluid iq-error-img" alt="">
                    <h2 class="mb-0">Oops! This Page is Not Working.</h2>
                    <p>The requested is Internal Server Error.</p>
                    <a class="btn btn-primary mt-3" href="index.html"><i class="ri-home-4-line"></i>Back to Home</a>
                    
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Wrapper END -->
@endsection