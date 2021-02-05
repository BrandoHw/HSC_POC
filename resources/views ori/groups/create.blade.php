<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title">Create <strong>New</strong> Group</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
    </div>
    <div class="modal-body m-3" id="createModalBody">
        <div id="stepper" class="bs-stepper linear">
            <div class="bs-stepper-header" role="tablist">
                <div class="step active" data-target="#test-l-1">
                <button type="button" class="step-trigger" role="tab" id="steppertrigger1" aria-controls="test-l-1" aria-selected="true">
                    <span class="bs-stepper-circle">1</span>
                    <span class="bs-stepper-label">Basic Information</span>
                </button>
                </div>
                <div class="bs-stepper-line"></div>
                <div class="step" data-target="#test-l-2">
                <button type="button" class="step-trigger" role="tab" id="steppertrigger2" aria-controls="test-l-2" aria-selected="false" disabled="disabled">
                    <span class="bs-stepper-circle">2</span>
                    <span class="bs-stepper-label">Select Project</span>
                </button>
                </div>
                <div class="bs-stepper-line"></div>
                <div class="step" data-target="#test-l-3">
                <button type="button" class="step-trigger" role="tab" id="steppertrigger3" aria-controls="test-l-3" aria-selected="false" disabled="disabled">
                    <span class="bs-stepper-circle">3</span>
                    <span class="bs-stepper-label">Done</span>
                </button>
                </div>
            </div>
            <div class="bs-stepper-content">
                <div id="test-l-1" role="tabpanel" class="bs-stepper-pane active dstepper-block" aria-labelledby="steppertrigger1">
                    <div class="form-group row">
                        <label class="col-form-label col-sm-3 text-sm-right">
                            Name
                            <span style="color: red; display:block; float:right"> *</span>
                        </label>
                        <div class="col-sm-8" id="groupNameField">
                            {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control', 'id' => 'groupName')) !!}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-sm-3 text-sm-right">
                            Detail
                        </label>
                        <div class="col-sm-8" id="groupDetailField">
                            {!! Form::textarea('detail', null, array('placeholder' => 'Detail','class' => 'form-control', 'id' => 'groupDetail')) !!}
                        </div>
                    </div>
                    <button style="float:right" class="btn btn-primary" id="nextBtn-1">Next</button>
                </div>
                <div id="test-l-2" role="tabpanel" class="bs-stepper-pane" aria-labelledby="steppertrigger2">
					<p class="text-left" id="selectProjectMessage">
						<span style="color: red; display:block; float:left"> *</span>
						<em>Please select at least one project to proceed.</em>
					</p>
					<div class="table-responsive" id="projectTableHolder">
						<table class="table table-striped table-bordered table-hover table-sm" id="projectTable">
							<thead>
								<tr>
									<th scope="col" style="width:10%"></th>
									<th scope="col" >#</th>
									<th scope="col" >Name</th>
									<th scope="col" >Start Date</th>
									<th scope="col" >End Date</th>
								</tr>
							</thead>
							<tbody>
								@foreach($projects as $project)
								<tr id="trNullProject-{{ $project->id }}">
									<td style="text-align: center" >{{ $project->id }}</td>
									<td >{{ $project->id }}</td>
									<td  id="trNullProjectName-{{ $project->id }}">{{ $project->name }}</td>
									<td  id="trNullProjectStart-{{ $project->id }}">{{ $project->start_date }}</td>
									<td  id="trNullProjectEnd-{{ $project->id }}">{{ $project->end_date }}</td>
								</tr>
								@endforeach
							</tbody>
						</table>
					</div>
					<div style="float:right">
                        <button class="btn btn-secondary" id="previousBtn-2">Back</button>
                        <button type="submit" class="btn btn-primary" id="nextBtn-2">Complete</button>
                    </div>
                </div>
                <div id="test-l-3" role="tabpanel" class="bs-stepper-pane text-center" aria-labelledby="steppertrigger3">
                    <div class="alert alert-success" role="alert">
                        <div class="alert-message" id="completeMessage"></div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-sm-3 text-sm-right">
                            Name
                            <span style="color: red; display:block; float:right"> *</span>
                        </label>
                        <div class="col-sm-8" id="createBuildingNameField">
                            {!! Form::text('name', null, array('placeholder' => 'Name','class' => "form-control", 'id' => 'displayName', 'readonly')) !!}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-sm-3 text-sm-right">
                            Detail
                        </label>
                        <div class="col-sm-8" id="createBuildingDetailField">
                            {!! Form::textarea('detail', null, array('placeholder' => 'Detail','class' => "form-control", 'id' => 'displayDetail', 'readonly')) !!}
                        </div>
                    </div>
                    <table class="table table-striped table-bordered table-hover table-sm" id="displayProjectTable">
						<thead>
							<tr>
								<th scope="col" style="width: 10%">#</th>
								<th scope="col" style="width: 40%">Name</th>
								<th scope="col" style="width: 25%">Start Date</th>
								<th scope="col" style="width: 25%">End Date</th>
							</tr>
						</thead>
					</table>
                    <div style="float:right">
                        <button class="btn btn-secondary mt-5" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>