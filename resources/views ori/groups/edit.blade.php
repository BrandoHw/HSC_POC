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
        <a href="{{ route('groups.index') }}" style="padding-left: 12px">
            @svg('chevron-left', 'feather-chevron-left align-middle')  
        </a>
        <h3 style="padding-left: 12px">Edit<strong> {{ $group->name }}</strong></h3>
    </div>

	<!-- Form -->
	{!! Form::model($group, ['method' => 'PATCH','route' => ['groups.update', $group->id]]) !!}
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <!-- Basic Information -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Basic Information {{ $group->id }}</h5>
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
            
			<!-- Manage Other Information -->
			<div class="card">
				<div class="card-header">
                    <h5 class="card-title">Other Information</h5>
					<h6 class="card-subtitle text-muted">These informations can be added later.</h6>
				</div>
				<div class="card-body">
					<div class="accordion" id="createGroupAccordion">
						<div class="card">
							<div class="card-header">
								<h5 class="card-title mb-0">
									<a href="#" class="text-primary" data-toggle="collapse" data-target="#scheduleCollapse" aria-expanded="true" aria-controls="scheduleCollapse">
										Add Schedule
									</a>
								<h5>
							</div>
							<div id="scheduleCollapse" class="collapse" aria-labelledby="headingOne" data-parent="#createGroupAccordion">
								<div class="card-body">
									<div class="form-group row">
										<label class="col-form-label col-sm-2 text-sm-right">
											Schedule
										</label>
										<div class="col-sm-8">
											{!! Form::select('schedule_id', $schedules, null , array('placeholder' => 'Please select a schedule...','class' => "form-control", 'id' => 'createScheduleId')) !!}
											@error('schedule_id')
												<script>$('#createScheduleId').css("border", "1px solid red");</script>
												<div class="alert-danger">{{ $message }}</div>
											@enderror
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="card">
							<div class="card-header">
								<h5 class="card-title mb-0">
									<a href="#" class="text-primary" data-toggle="collapse" data-target="#projectCollapse" aria-expanded="true" aria-controls="projectCollapse">
										Add Projects
									</a>
								<h5>
							</div>
							<div id="projectCollapse" class="collapse" aria-labelledby="headingOne" data-parent="#createGroupAccordion">
								<div class="card-body">
									<div class="table-responsive" id="projectTableHolder">
										<table class="table table-striped table-bordered table-hover table-sm" id="projectTable">
											<thead>
												<tr>
													<th scope="col" style="width:10%"></th>
													<th scope="col" >#</th>
													<th scope="col" >Name</th>
												</tr>
											</thead>
											<tbody>
												@foreach($projects as $project)
												<tr id="trProject-{{ $project->id }}">
													<td id="tdProjectCheckbox-{{ $project->id }}">{{ $project->id }}</td>
													<td id="tdProjectId-{{ $project->id }}">{{ $project->id }}</td>
													<td id="tdProjectName-{{ $project->id }}">{{ $project->name }}</td>
												</tr>
												@endforeach
											</tbody>
										</table>
									</div>
									@error('projects_id')
										<script>$('#projectTableHolder').css("border", "1px solid red");</script>
										<div class="alert-danger">{{ $message }}</div>
									@enderror
								</div>
							</div>
						</div>
						<div class="card">
							<div class="card-header">
								<h5 class="card-title mb-0">
									<a href="#" class="text-primary" data-toggle="collapse" data-target="#tagCollapse" aria-expanded="true" aria-controls="tagCollapse">
										Add Tags
									</a>
								<h5>
							</div>
							<div id="tagCollapse" class="collapse" aria-labelledby="headingOne" data-parent="#createGroupAccordion">
								<div class="card-body">
									<div class="table-responsive" id="tagTableHolder">
										<table class="table table-striped table-bordered table-hover table-sm" id="tagTable">
											<thead>
												<tr>
													<th scope="col" style="width:10%"></th>
													<th scope="col" >#</th>
													<th scope="col" >Name</th>
													<th scope="col" >Mac Address</th>
												</tr>
											</thead>
											<tbody>
												@foreach($tagsNull as $tag)
												<tr id="trTag-{{ $tag->id }}">
													<td id="tdTagCheckbox-{{ $tag->id }}">{{ $tag->id }}</td>
													<td id="tdTagSerial-{{ $tag->id }}">{{ $tag->id }}</td>
													<td id="tdTagSerial-{{ $tag->id }}">{{ $tag->serial }}</td>
													<td id="tdTagMacAdd-{{ $tag->id }}">{{ $tag->mac_addr }}</td>
												</tr>
												@endforeach
											</tbody>
										</table>
									</div>
									@error('tags_id')
										<script>$('#tagTableHolder').css("border", "1px solid red");</script>
										<div class="alert-danger">{{ $message }}</div>
									@enderror
								</div>
							</div>
						</div>
						<div class="card">
							<div class="card-header">
								<h5 class="card-title mb-0">
									<a href="#" class="text-primary" data-toggle="collapse" data-target="#userCollapse" aria-expanded="true" aria-controls="userCollapse">
										Add Users
									</a>
								<h5>
							</div>
							<div id="userCollapse" class="collapse" aria-labelledby="headingOne" data-parent="#createGroupAccordion">
								<div class="card-body">
									<div class="table-responsive" id="userTableHolder">
										<table class="table table-striped table-bordered table-hover table-sm" id="userTable">
											<thead>
												<tr>
													<th scope="col" style="width:10%"></th>
													<th scope="col" >#</th>
													<th scope="col" >Name</th>
													<th scope="col"  >Role</th>
												</tr>
											</thead>
											<tbody>
												@foreach($usersNull as $user)
												<tr id="trUser-{{ $user->id }}">
													<td id="tdUserCheckbox-{{ $user->id }}">{{ $user->id }}</td>
													<td id="tdUserId-{{ $user->id }}">{{ $user->id }}</td>
													<td id="tdUserName-{{ $user->id }}">{{ $user->name }}</td>
													<td id="tdUserRole-{{ $user->id }}">
														<span class="badge badge-pill badge-dark" style="background-color: {{ $user->roles[0]->color }}">{{ $user->getRoleNames()[0] }}</span>
													</td>
												</tr>
												@endforeach
											</tbody>
										</table>
									</div>
									@error('users_id')
										<script>$('#userTableHolder').css("border", "1px solid red");</script>
										<div class="alert-danger">{{ $message }}</div>
									@enderror
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
        </div>
    </div>

    <!-- Button -->
    <div class="col-xs-12 col-sm-12 col-md-12 text-center">
        <a href="{{ route('groups.index') }}" class="btn btn-secondary">Cancel</a>
        <a href="#" class="btn btn-secondary" id="btn">Testing</a>
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
    {!! Form::close() !!}
</div>
@endsection

@section("script")

<script type="text/javascript">

    $(function () {

		/* Tag DataTable */
		var projectTable = $('#projectTable').DataTable({
			dom: '<fl<t>ip>',
			columnDefs: [{
				orderable: false,
				targets: 0,
				checkboxes: {
					'selectRow': true
				}
			}],
			select: {
				style: 'multi',
			},
			order: [[ 1, 'asc' ]],
			scrollY: 500,
			scrollCollapse: true,
			paging:true,
			scroller: true,
		});

		$('#projectCollapse').on('shown.bs.collapse', function () {
			projectTable.columns.adjust().draw();
		});

		var groupProjects = @json($group->projects);
		console.log(groupProjects);
		groupProjects.forEach(function(item){
			var checkbox = '#tdProjectCheckbox-' + item['id'];
			projectTable.cell(checkbox).checkboxes.select(true);
		})

		/* Tag DataTable */
		var tagTable = $('#tagTable').DataTable({
			dom: '<fl<t>ip>',
			columnDefs: [{
				orderable: false,
				targets: 0,
				checkboxes: {
					'selectRow': true
				}
			}],
			select: {
				style: 'multi',
			},
			order: [[ 1, 'asc' ]],
			scrollY: 500,
			scrollCollapse: true,
			paging:true,
			scroller: true,
		})

		$('#tagCollapse').on('shown.bs.collapse', function () {
			tagTable.columns.adjust().draw();
		});

		var groupTags = @json($group->tags);
		console.log(groupTags);
		groupTags.forEach(function(item){
			var checkbox = '#tdTagCheckbox-' + item['id'];
			tagTable.cell(checkbox).checkboxes.select(true);
		})

		/* User DataTable */
		var userTable = $('#userTable').DataTable({
			dom: '<fl<t>ip>',
			columnDefs: [{
				orderable: false,
				targets: 0,
				checkboxes: {
					'selectRow': true
				}
			}],
			select: {
				style: 'multi',
			},
			order: [[ 1, 'asc' ]],
			scrollY: 500,
			scrollCollapse: true,
			paging:true,
			scroller: true,
		})

		$('#userCollapse').on('shown.bs.collapse', function () {
			userTable.columns.adjust().draw();
		});

		var groupUsers = @json($group->users);
		console.log(groupUsers);
		groupUsers.forEach(function(item){
			var checkbox = '#tdUserCheckbox-' + item['id'];
			userTable.cell(checkbox).checkboxes.select(true);
		})


		$('#createGroupForm').on('submit', function(e){
			var form = this;

			/* Project DataTable */
			var projects_selected = projectTable.column(0).checkboxes.selected();
	
			// Iterate over all selected checkboxes
			$.each(projects_selected, function(index, value){
				// Create a hidden element
				$(form).append(
					$('<input>')
						.attr('type', 'hidden')
						.attr('name', 'projects_id[]')
						.val(value)
				);
			});
			
			/* Tag DataTable */
			var tags_selected = tagTable.column(0).checkboxes.selected();
	
			// Iterate over all selected checkboxes
			$.each(tags_selected, function(index, value){
				// Create a hidden element
				$(form).append(
					$('<input>')
						.attr('type', 'hidden')
						.attr('name', 'tags_id[]')
						.val(value)
				);
			});
			
			/* User DataTable */
			var users_selected = userTable.column(0).checkboxes.selected();
	
			// Iterate over all selected checkboxes
			$.each(users_selected, function(index, value){
				// Create a hidden element
				$(form).append(
					$('<input>')
						.attr('type', 'hidden')
						.attr('name', 'users_id[]')
						.val(value)
				);
			});
		});

		$('#btn').click(function(){
			var form = this;

			/* Project DataTable */
			var projects_selected = projectTable.column(0).checkboxes.selected();
			console.log('project')
			console.log(projects_selected);
	
			// Iterate over all selected checkboxes
			$.each(projects_selected, function(index, value){
				// Create a hidden element
				$(form).append(
					$('<input>')
						.attr('type', 'hidden')
						.attr('name', 'projects_id[]')
						.val(value)
				);
			});
			
			/* Tag DataTable */
			var tags_selected = tagTable.column(0).checkboxes.selected();
			console.log('tag')
			console.log(tags_selected);
	
			// Iterate over all selected checkboxes
			$.each(tags_selected, function(index, value){
				// Create a hidden element
				$(form).append(
					$('<input>')
						.attr('type', 'hidden')
						.attr('name', 'tags_id[]')
						.val(value)
				);
			});
			
			/* User DataTable */
			var users_selected = userTable.column(0).checkboxes.selected();
			console.log('user')
			console.log(users_selected);
	
			// Iterate over all selected checkboxes
			$.each(users_selected, function(index, value){
				// Create a hidden element
				$(form).append(
					$('<input>')
						.attr('type', 'hidden')
						.attr('name', 'users_id[]')
						.val(value)
				);
			});
		});
    })
    
</script>
@endsection