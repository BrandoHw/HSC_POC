@extends('layouts.app')

@section('content')
<div class="container-fluid p-0">
    
    <!-- Title & Add-Button -->
    <div class="row mb-2 mb-xl-3 justify-content-start">
        <a href="{{ route('roles.index') }}" style="padding-left: 12px">
            @svg('chevron-left', 'feather-chevron-left align-middle')  
        </a>
        <h3 style="padding-left: 12px">Edit <strong>{{ $role->name }}</strong> Role</h3>
    </div>

    <!-- Form -->
    {!! Form::model($role, ['method' => 'PATCH','route' => ['roles.update', $role->id]]) !!}
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <!-- Basic Information -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Basic Information</h5>
                </div>
                <div class="card-body" id="editCardBody">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>
                                Name
                                <span style="color: red; display:block; float:right"> *</span>
                            </label>
                            {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control', 'id' => 'editName')) !!}
                            @error('name')
                                <script>$('#editName').css("border", "1px solid red");</script>
                                <div class="alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label>
                                Color
                                <span style="color: red; display:block; float:right"> *</span>
                            </label>
                            <div class="input-group">
                                <input type="color" name=color id="editColor" class="form-control" value='{{ $role->color }}'>
                                <div class="input-group-append">
                                    <span class="input-group-text" id="editColorHolder" style="width:100px">{{ $role->color }}</span>
                                </div>
                            </div>
                            @error('color')
                                <script>$('#editColor').css("border", "1px solid red");</script>
                                <script>$('#editColorHolder').css("border", "1px solid red");</script>
                                <div class="alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                </div>
            </div>
            
            <!-- Manage Permissions -->
            <div class="card">
                <div class="card-header">
                    <label class="h5 card-title">
                        Manage Permissions
                        <span style="color: red; display:block; float:right"> *</span>
                    </label>
                    <h6 class="card-subtitle text-muted">Please check at least one permission</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive" id="permissionTableHolder">
                        <table class="table table-striped table-hover table-sm" id="permissionTable">
                            <thead>
                                <tr>
                                    <th scope="col" style="width:40%">Modules</th>
                                    <th scope="col" class="text-center noSort" style="width:15%" >View</th>
                                    <th scope="col" class="text-center noSort" style="width:15%">Create</th>
                                    <th scope="col" class="text-center noSort" style="width:15%">Edit</th>
                                    <th scope="col" class="text-center noSort" style="width:15%">Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($modules as $module)
                                <tr>
                                    <td>{{ ucfirst($module['name']) }} Management</td>
                                    <td class="text-center">
                                        <label class="d-flex justify-content-center custom-control custom-checkbox">
                                            @if($module['list'] != null)
                                                {{ Form::checkbox('permission[]', $module['list'], in_array($module['list'], $roleModules) ? true : false, array('class' => 'custom-control-input')) }}
                                            @else
                                                <input type="checkbox" class="custom-control-input" name="permission[]" value="{{ $module['list']}}" disabled>
                                            @endif
                                            <span class="custom-control-label" style="float:none"></span>
                                        </label>
                                    </td>
                                    <td class="text-center">
                                        <label class="d-flex justify-content-center custom-control custom-checkbox">
                                            @if($module['create'] != null)
                                                {{ Form::checkbox('permission[]', $module['create'], in_array($module['create'], $roleModules) ? true : false, array('class' => 'custom-control-input')) }}
                                            @else
                                                <input type="checkbox" class="custom-control-input" name="permission[]" value="{{ $module['edit']}}" disabled>
                                            @endif
                                            <span class="custom-control-label" style="float:none"></span>
                                        </label>
                                    </td>
                                    <td class="text-center">
                                        <label class="d-flex justify-content-center custom-control custom-checkbox">
                                            @if($module['edit'] != null)
                                                {{ Form::checkbox('permission[]', $module['edit'], in_array($module['edit'], $roleModules) ? true : false, array('class' => 'custom-control-input')) }}
                                            @else
                                                <input type="checkbox" class="custom-control-input" name="permission[]" value="{{ $module['edit']}}" disabled>
                                            @endif
                                            <span class="custom-control-label" style="float:none"></span>
                                        </label>
                                    </td>
                                    <td class="text-center">
                                        <label class="d-flex justify-content-center custom-control custom-checkbox">
                                            @if($module['delete'] != null)
                                                {{ Form::checkbox('permission[]', $module['delete'], in_array($module['delete'], $roleModules) ? true : false, array('class' => 'custom-control-input')) }}
                                            @else
                                                <input type="checkbox" class="custom-control-input" name="permission[]" value="{{ $module['delete']}}" disabled>
                                            @endif
                                            <span class="custom-control-label" style="float:none"></span>
                                        </label>
                                    </td>
                                </tr>
                                @endforeach
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    @error('permission')
                        <script>$('#permissionTableHolder').css("border", "1px solid red");</script>
                        <div class="alert-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>
    
    <!-- Button -->
    <div class="col-xs-12 col-sm-12 col-md-12 text-center">
        <a href="{{ route('roles.index') }}" class="btn btn-secondary">Cancel</a>
        <button type="submit" class="btn btn-primary">Save</button>
    </div>
    {!! Form::close() !!}
</div>
@endsection

@section('script')
<script type="text/javascript">

    $(function () {
        /* Initiate tooltip */
        $('[data-toggle="tooltip"]').tooltip()

        /* Initiate popover */
        $('[data-toggle="popover"]').popover()

        /* Initiate dataTable */
        var table = $('#permissionTable').DataTable({
            responsive: true,
            stateSave: true,
            paging: false,
            searching: false,
            info: false,
            'order': [],
            'columnDefs':[{
                'targets': 'noSort',
                'orderable': false
            }],
        })

        $('#editColor').on('change', function(event) {
            var colorCode = $('#editColor').val().toUpperCase();
            $('#editColorHolder').html(colorCode);
        });
    })

</script>
@endsection