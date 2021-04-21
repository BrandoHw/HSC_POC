@extends('layouts.app')

@section('content')
<div class="container-fluid p-0">

    <!-- Title & Add-Button -->
    <div class="row mb-2 mb-xl-3 justify-content-start">
        <a href="{{ route('settings.index') }}" style="padding-left: 12px">
            @svg('chevron-left', 'feather-chevron-left align-middle')  
        </a>
        <h3 style="padding-left: 12px">Create New<strong> Role</strong></h3>
    </div>

    <!-- Form -->
    {!! Form::open(array('route' => 'roles.store','method'=>'POST')) !!}
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <!-- Basic Information -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Basic Information</h5>
                </div>
                <div class="card-body" id="createCardBody">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>
                                Name
                                <span style="color: red; display:block; float:right"> *</span>
                            </label>
                            {!! Form::text('name', null, array('placeholder' => 'Name','class' => "form-control", 'id' => 'createName')) !!}
                            @error('name')
                                <script>$('#createName').css("border", "1px solid red");</script>
                                <div class="alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label>
                                Color
                                <span style="color: red; display:block; float:right"> *</span>
                            </label>
                            <div class="input-group">
                                <input type="color" name=color id="createColor" class="form-control">
                                <div class="input-group-append">
                                    <span class="input-group-text" id="createColorHolder" style="width:100px"></span>
                                </div>
                            </div>
                            @error('color')
                                <script>$('#createColor').css("border", "1px solid red");</script>
                                <script>$('#createColorHolder').css("border", "1px solid red");</script>
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
                <div class="card-body" >
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
                                                <input type="checkbox" class="custom-control-input" name="permission[]" value="{{ $module['list']}}">
                                            @else
                                                <input type="checkbox" class="custom-control-input" name="permission[]" value="{{ $module['list']}}" disabled>
                                            @endif
                                            <span class="custom-control-label" style="float:none"></span>
                                        </label>
                                    </td>
                                    <td class="text-center">
                                        <label class="d-flex justify-content-center custom-control custom-checkbox">
                                            @if($module['create'] != null)
                                                <input type="checkbox" class="custom-control-input" name="permission[]" value="{{ $module['create']}}">
                                            @else
                                                <input type="checkbox" class="custom-control-input" name="permission[]" value="{{ $module['create']}}" disabled>
                                            @endif
                                            <span class="custom-control-label" style="float:none"></span>
                                        </label>
                                    </td>
                                    <td class="text-center">
                                        <label class="d-flex justify-content-center custom-control custom-checkbox">
                                            @if($module['edit'] != null)
                                                <input type="checkbox" class="custom-control-input" name="permission[]" value="{{ $module['edit']}}">
                                            @else
                                                <input type="checkbox" class="custom-control-input" name="permission[]" value="{{ $module['edit']}}" disabled>
                                            @endif
                                            <span class="custom-control-label" style="float:none"></span>
                                        </label>
                                    </td>
                                    <td class="text-center">
                                        <label class="d-flex justify-content-center custom-control custom-checkbox">
                                            @if($module['delete'] != null)
                                                <input type="checkbox" class="custom-control-input" name="permission[]" value="{{ $module['delete']}}">
                                            @else
                                                <input type="checkbox" class="custom-control-input" name="permission[]" value="{{ $module['delete']}}" disabled>
                                            @endif
                                            <span class="custom-control-label" style="float:none"></span>
                                        </label>
                                    </td>
                                </tr>
                                @endforeach
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
        <a href="{{ route('settings.index') }}" class="btn btn-secondary">Cancel</a>
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
    {!! Form::close() !!}
</div>
@endsection

@section("script")
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

        $('#createColor').on('change', function(event) {
            var colorCode = $('#createColor').val().toUpperCase();
            $('#createColorHolder').html(colorCode);
        });

    })
    
    
    

    
</script>
@endsection