@extends('layouts.appori')

@section('content')
<!-- Sign in Start -->
<section class="sign-in-page">
    <div class="container bg-white mt-5 p-0">
        <div class="row no-gutters">
            <div class="col-sm-6 align-self-center">
                <div class="sign-in-from">
                    <h1 class="mb-0">Sign in</h1>
                    <p>Enter your credentials to access system panel.</p>
                    <form class="mt-4" method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="form-group">
                            <label for="login">Email Address / Username</label>
                            <input type="text" name="login" value="{{ old('username') ?: old('email') }}" class="form-control mb-0 {{ $errors->has('username') || $errors->has('email') ? ' is-invalid' : '' }}" id="login" placeholder="Enter email / username" required autocomplete="login" autofocus>
                            @if ($errors->has('username') || $errors->has('email'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('username') ?: $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control mb-0 @error('password') is-invalid @enderror" name="password" id="password" placeholder="Password" required autocomplete="current-password">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="d-inline-block w-100">
                            <a href="{{ route('password.request') }}" class="float-left">Forgot password?</a>
                            <button type="submit" class="btn btn-primary float-right">Sign in</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-sm-6 text-center">
                <div class="sign-in-detail text-white">
                    <a class="sign-in-logo mb-5" href="#"><img src="{{ asset('img/icons/wecare-white.png') }}" class="img-fluid" alt="logo"><span class="text-white h3"> WECare</span></a>
                    <div class="owl-carousel" data-autoplay="true" data-loop="true" data-nav="false" data-dots="true" data-items="1" data-items-laptop="1" data-items-tab="1" data-items-mobile="1" data-items-mobile-sm="1" data-margin="0">
                        <div class="item d-flex justify-content-center">
                            <img src="{{ asset('img/login/data.eps') }}" class="img-fluid mb-4 rounded-circle" style="width: 70% !important; height: 70%; !important" alt="logo">
                            <!-- <h4 class="mb-1 text-white">Manage your orders</h4>
                            <p>It is a long established fact that a reader will be distracted by the readable content.</p> -->
                        </div>
                        <div class="item d-flex justify-content-center">
                            <img src="{{ asset('img/login/data.eps') }}" class="img-fluid mb-4 rounded-circle" style="width: 70% !important; height: 70%; !important" alt="logo">
                        </div>
                        <div class="item d-flex justify-content-center">
                            <img src="{{ asset('img/login/data.eps') }}" class="img-fluid mb-4 rounded-circle" style="width: 70% !important; height: 70%; !important" alt="logo">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Sign in END -->
@endsection