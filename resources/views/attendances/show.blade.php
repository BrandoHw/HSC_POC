@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2> Show Attendance</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('attendances.index') }}">Back</a>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Date:</strong>
            {{ $attendance->date }}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Clock In:</strong>
            {{ $attendancce->clock_in }}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Clock Out:</strong>
            {{ $attendancce->clock_out }}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Total Time:</strong>
            {{ $attendancce->total_time }}
        </div>
    </div>
</div>
@endsection
