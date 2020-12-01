@extends('layouts.app')

@section('style')
<style>
	.dataTables_wrapper .dataTables_length {
		float: right;
	}
	.dataTables_wrapper .dataTables_filter {
		float: left;
		text-align: left !important;
	}
</style>
@endsection

@section('content')
<div class="container-fluid p-0">

	<!-- Title & Add-Button -->
	<div class="row mb-2 mb-xl-3 justify-content-start">
        <a href="{{ route('projects.index') }}" style="padding-left: 12px">
            @svg('chevron-left', 'feather-chevron-left align-middle')  
        </a>
        <h3 style="padding-left: 12px">Create New<strong> Project</strong></h3>
    </div>

	<!-- Form -->
	{!! Form::open(array('route' => 'projects.store','method'=>'POST', 'id' => 'createProjectForm')) !!}
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <!-- Basic Information -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Basic Information</h5>
                </div>
                <div class="card-body" id="createCardBody">
					<div class="form-group row">
						<label class="col-form-label col-sm-2 text-sm-right">
							Name
							<span style="color: red; display:block; float:right"> *</span>
						</label>
						<div class="col-sm-8">
							{!! Form::text('name', null, array('placeholder' => 'Name','class' => "form-control", 'id' => 'createName')) !!}
                            @error('name')
                                <script>$('#createName').css("border", "1px solid red");</script>
                                <div class="alert-danger">{{ $message }}</div>
                            @enderror
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-2 text-sm-right">
							Start Date
                            <span style="color: red; display:block; float:right"> *</span>
						</label>
						<div class="col-sm-8">
                            <div class="input-group date" id="datetimepicker7" data-target-input="nearest" id="createStartDate">
                                <input type="text" name="start_date" placeholder="yyyy/mm/dd" class="form-control datetimepicker-input" data-target="#datetimepicker7"/>
                                <div class="input-group-append" data-target="#datetimepicker7" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                            </div>
                            @error('start_date')
                                <script>$('#createStartDate').css("border", "1px solid red");</script>
                                <div class="alert-danger">{{ $message }}</div>
                            @enderror
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-2 text-sm-right">
							End Date
                            <span style="color: red; display:block; float:right"> *</span>
						</label>
						<div class="col-sm-8">
                            <div class="input-group date" id="datetimepicker8" data-target-input="nearest" id="createEndDate">
                                <input type="text" name="end_date" placeholder="yyyy/mm/dd" class="form-control datetimepicker-input" data-target="#datetimepicker8"/>
                                <div class="input-group-append" data-target="#datetimepicker8" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                            @error('end_date')
                                <script>$('#createEndDate').css("border", "1px solid red");</script>
                                <div class="alert-danger">{{ $message }}</div>
                            @enderror
						</div>
					</div>
                    <div class="form-group row">
						<label class="col-form-label col-sm-2 text-sm-right">
							Detail
						</label>
						<div class="col-sm-8">
							{!! Form::textarea('detail', null, array('placeholder' => 'Detail','class' => "form-control", 'id' => 'createDetail')) !!}
							@error('detail')
								<script>$('#createName').css("border", "1px solid red");</script>
								<div class="alert-danger">{{ $message }}</div>
							@enderror
						</div>
					</div>
				</div>
            </div>
    </div>

    <!-- Button -->
    <div class="col-xs-12 col-sm-12 col-md-12 text-center">
        <a href="{{ route('projects.index') }}" class="btn btn-secondary">Cancel</a>
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
    {!! Form::close() !!}
</div>
@endsection

@section('script')
<script type="text/javascript">
    $(function () {
        $('#datetimepicker7').datetimepicker({
            format: 'L',
            format: 'YYYY/MM/DD'
        });
        $('#datetimepicker8').datetimepicker({
            format: 'L',
            format: 'YYYY/MM/DD',
            useCurrent: false
        });
        $("#datetimepicker7").on("change.datetimepicker", function (e) {
            $('#datetimepicker8').datetimepicker('minDate', e.date);
        });
        $("#datetimepicker8").on("change.datetimepicker", function (e) {
            $('#datetimepicker7').datetimepicker('maxDate', e.date);
        });
    });
</script>
@endsection

