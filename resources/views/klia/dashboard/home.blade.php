@extends('layouts.app')

@section('style')
<style>
   .iq-card-icon { cursor: pointer }
</style>
@endsection

@section('content')
<script src="{{ asset('js/mix/apexcharts.js') }}"></script>

<div class="container-fluid">
   <div class="row">
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-body">
                <div class="row">
                    <div class="col-md-6 col-lg">
                        <div class="d-flex align-items-center mb-3 mb-lg-0">
                            <div class="rounded-circle iq-card-icon iq-bg-success mr-3" href="{{ route('gateways.index') }}" > <i class="ri-base-station-line"></i></div>
                            <div class="text-left">
                            <h4 id="icon-text-reader">{{ $readers_count }}</h4>
                            <p class="mb-0">Total Gateways</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg">
                        <div class="d-flex align-items-center mb-3 mb-md-0">
                            <div class="rounded-circle iq-card-icon iq-bg-info mr-3" href="{{ route('beacons.index') }}" > <i class="ri-share-line"></i></div>
                            <div class="text-left">
                            <h4 id="icon-text-tag">{{ $tags_count }}</h4>
                            <p class="mb-0">Total Beacons</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg">
                        <div class="d-flex align-items-center mb-3 mb-md-0">
                            <div class="rounded-circle iq-card-icon iq-bg-warning mr-3" href="{{ route('residents.index') }}" > <i class="ri-group-line"></i></div>
                            <div class="text-left">
                            <h4 id="icon-text-resident">{{ $residents_count }}</h4>
                            <p class="mb-0">Total Staff</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg">
                        <div class="d-flex align-items-center mb-3 mb-md-0">
                            <div class="rounded-circle iq-card-icon iq-bg-danger mr-3" href="{{ route('alerts-klia.index') }}" > <i class="ri-alarm-warning-line"></i></div>
                            <div class="text-left">
                            <h4 id="icon-text-alert">{{ $alerts_count }}</h4>
                            <p class="mb-0">Total Alerts</p>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-body">
                    @include ("klia.dashboard.pie")
                </div>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-body">
                    @include ("klia.dashboard.column")
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
               <div class="iq-card-body">
                  @include ("map.dashboard")
               </div>
            </div>
         </div>
    </div>
</div>

@endsection

@section('extra')
@can('alert-list')
<div class="iq-right-fixed" style="background-color: var(--iq-body-bg)">
   <div style="background-color: var(--iq-body-bg)">
      @include("klia.dashboard.locations")
   </div>
</div>
@endcan
@endsection

@section('script')
<!-- Apexcharts JavaScript -->
<script>
$(function(){
    $('#body').addClass(['sidebar-main-active', 'right-column-fixed', 'header-top-bgcolor']);
})

$('.iq-card-icon').on('click', function(){
      window.location.href = $(this).attr('href');
})
</script>

@endsection