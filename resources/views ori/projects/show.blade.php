@extends('layouts.app')

@section('content')
<div class="container-fluid p-0">

	<!-- Title & Add-Button -->
	<div class="row mb-2 mb-xl-3 justify-content-start">
        <a href="{{ route('projects.index') }}" style="padding-left: 12px">
            @svg('chevron-left', 'feather-chevron-left align-middle')  
        </a>
        <h3 style="padding-left: 12px"><strong>{{ $project->name }}</strong></h3>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <!-- Basic Information -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Basic Information</h5>
                </div>
                <div class="card-body" id="showCardBody">
					<div class="form-group row">
						<label class="col-form-label col-sm-2 text-sm-right">
							Name
							<span style="color: red; display:block; float:right"> *</span>
						</label>
						<div class="col-sm-8">
							{!! Form::text('name', $project->name, array('placeholder' => 'Name','class' => "form-control", 'id' => 'showName', 'readonly')) !!}
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-2 text-sm-right">
							Start Date
                            <span style="color: red; display:block; float:right"> *</span>
						</label>
						<div class="col-sm-8">
                            <div class="input-group date" id="datetimepicker7" data-target-input="nearest" id="showStartDate">
                                <input type="text" value="{{ $project->start_date }}" readonly name="start_date" placeholder="yyyy/mm/dd" class="form-control datetimepicker-input" data-target="#datetimepicker7"/>
                                <div class="input-group-append" data-target="#datetimepicker7" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                            </div>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-2 text-sm-right">
							End Date
                            <span style="color: red; display:block; float:right"> *</span>
						</label>
						<div class="col-sm-8">
                            <div class="input-group date" id="datetimepicker8" data-target-input="nearest" id="showEndDate">
                                <input type="text" value="{{ $project->end_date }}" readonly name="end_date" placeholder="yyyy/mm/dd" class="form-control datetimepicker-input" data-target="#datetimepicker8"/>
                                <div class="input-group-append" data-target="#datetimepicker8" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
						</div>
					</div>
                    <div class="form-group row">
						<label class="col-form-label col-sm-2 text-sm-right">
							Detail
						</label>
						<div class="col-sm-8">
							{!! Form::textarea('detail', $project->detail, array('placeholder' => 'Detail','class' => "form-control", 'id' => 'showDetail', 'readonly')) !!}
						</div>
					</div>
				</div>
            </div>
    </div>

    <!-- Button -->
    <div class="col-xs-12 col-sm-12 col-md-12 text-center">
        <a href="{{ route('projects.index') }}" class="btn btn-secondary">Back</a>
    </div>

</div>
@endsection