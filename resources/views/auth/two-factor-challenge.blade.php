@extends('layouts.appori')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Two factor challenge') }}</div>

                <div class="card-body">
                    <span id="title-code"> {{ __('Please enter your authentication code to login.') }} </span>
                    <span style="display:none" id="title-recovery"> {{ __('Please enter a recovery code to login.') }} </span>

                    <form method="POST" action="{{ route('two-factor.login') }}" id="two-factor-form">
                        @csrf
                        <div class="form-group row" id="code-div">
                            <label for="code" class="col-md-4 col-form-label text-md-right">{{ __('Code') }}</label>

                            <div class="col-md-6">
                                <input id="code" type="code" class="form-control @error('code') is-invalid @enderror" name="code" required autocomplete="current-code">

                                @error('code')
                                    <span id="code-error" class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row" style="display: none" id="recovery-div">
                            <label for="recovery_code" class="col-md-4 col-form-label text-md-right">{{ __('Recovery Code') }}</label>

                        <div class="col-md-6">
                                <input id="recovery_code" value=0 type="recovery_code" class="form-control @error('recovery_code') is-invalid @enderror" name="recovery_code" required autocomplete="recovery_code">

                                @error('recovery_code')
                                    <span id="recovery-code-error"  class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Submit') }}
                                </button>
                                <span id="recovery-link" class="btn btn-link">
                                    {{ __('No Access To Device?') }}
                                </span>
                                <span style="display: none" id="recovery-link-back" class="btn btn-link">
                                    {{ __('Back') }}
                                </span>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
@section("script")
<script>
    $(function (){

        var errors = @json($errors->all());
        console.log(errors);

        if (errors[1] === "RECOVERY"){
            $("#code-div").hide();
            $("#recovery-div").show();
            $("#code").val('');
            $("#recovery_code").val('');
            $("#recovery-link-back").show();
            $("#recovery-link").hide();
            $("#title-recovery").show();
            $("#title-code").hide();

            $("code-error").hide();
            $("recovery-code-error").show();
        }else  
            $("code-error").show();
            $("recovery-code-error").hide();{
        }


        $("#recovery-link").click(function (){
            $("#code-div").hide();
            $("#recovery-div").show();
            $("#code").val('RECOVERY');
            $("#recovery_code").val('');
            $("#recovery-link-back").show();
            $("#recovery-link").hide();
            $("#title-recovery").show();
            $("#title-code").hide();
        })

        $("#recovery-link-back").click(function (){
            $("#code-div").show();
            $("#recovery-div").hide();
            $("#code").val('');
            $("#recovery_code").val(0000000);
            $("#recovery-link-back").hide();
            $("#recovery-link").show();
            $("#title-recovery").hide();
            $("#title-code").show();
        })
    });
</script>
 