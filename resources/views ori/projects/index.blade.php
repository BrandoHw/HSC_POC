@extends('layouts.app')

@section('content')
<div class="container-fluid p-0">
    <!-- Display alert -->
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible" project="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            
			<div class="alert-message">
                <strong>Hello there!</strong> {{ $message }}
            </div>
        </div>
    @endif

    <!-- Title & Add-Button -->
    <div class="row mb-2 mb-xl-3">
        <div class="col-auto d-none d-sm-block">
            <h3><strong>Projects</strong> Management</h3>
        </div>
        
		<div class="col-auto ml-auto text-right mt-n1">
            @can('project-create')
                <a class="btn btn-primary" href="{{ route('projects.create') }}">
                    @svg('plus', 'feather-plus align-middle')  
                    <span class="align-middle">Add project</span>
                </a>
            @endcan
        </div>
	</div>
    
    <!-- Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="projectTable">
                            <thead>
                                <tr>
                                    <th scope="col" style="width:5%">#</th>
                                    <th scope="col" style="width:25%">Name</th>
                                    <th scope="col" style="width:25%">Number of Groups</th>
                                    <th scope="col" style="width:20%">Start Date</th>
                                    <th scope="col" style="width:20%">End Date</th>
                                    <th scope="col" class="noSort">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($projects as $project)
                                    <tr>
                                        <td>{{ $project->id }}</td>
                                        
                                        <td>
                                            <a href="{{ route('projects.show',$project->id) }}">
                                                {{ $project->name }} 
                                            </a>
                                        </td>
                                        
                                        <td>
                                            @if($project->groups()->doesntExist())
                                                <font color='gray'><em>No group</em></font>
                                            @else
                                                {{ $project->groups->count() }}
                                            @endif
                                        </td>
                                        
                                        <td>
                                            {{ $project->start_date}}
                                        </td>
                                        
                                        <td>
                                            {{ $project->end_date}}
                                        </td>
                                        
                                        <td class="table-action">
                                            @can('project-edit')
                                                <a href="{{ route('projects.edit',$project->id) }}">
                                                    @svg('edit-2', 'feather-edit-2 align-middle')
                                                </a>
                                            @endcan
                                            
                                            @can('project-delete')
                                                <a type="button" data-toggle="modal" data-target="#ProjectModalDelete">
                                                    @svg('trash', 'feather-trash align-middle')
                                                </a>
                                                        
                                                <div class="modal fade" id="ProjectModalDelete" style="text-align:center" tabindex="-1">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">
                                                                    Delete <strong>{{$project->name}}</strong>?
                                                                </h5>
                                            
                                                                <button class="close" data-dismiss="modal">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                        
                                                            <div class="modal-body">
                                                                <STRONG>Are you sure you want to delete?</STRONG>
                                                            </div>
                        
                                                            <div class="modal-footer">
                                                                <button class="btn btn-secondary" data-dismiss="modal">
                                                                    Close
                                                                </button>
                                            
                                                                <form action="{{ route('projects.destroy',$project->id) }}" method="POST">
                                                                    @method('DELETE')
                                                                    @csrf
                                                                    
                                                                    <button class="btn btn-danger">DELETE</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table> 
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection         

@section('script')
<script>
    /* Initiate dataTable */
    $('#projectTable').DataTable({
        dom: '<fl<t>ip>',
        responsive: true,
        stateSave: true,
        'lengthMenu': [[10, 25, 50, -1], [10, 25, 50, "All"]],
        'order': [[1, 'asc']],
        'columnDefs':[{
            'targets': 'noSort',
            'orderable': false
        }],
        pagingType: 'simple_numbers'
    })
</script>
@endsection