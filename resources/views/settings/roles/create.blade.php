@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12 col-lg-9">
            <div class="iq-card">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">Create New Role:</h4>
                    </div>
                </div>
                <div class="iq-card-body">
                    <form>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="name">Name:</label>
                                <input type="text" class="form-control" id="name" onInput="validateRoleInput(this.id)" placeholder="Enter name">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="color">Color:</label>
                                <div class="input-group">
                                    <input type="color" name=color id="color" class="form-control" onInput="validateRoleInput(this.id)" placeholder="Click to select color">
                                    <div class="input-group-append">
                                        <span class="input-group-text" id='color-code' style="width:100px">#000000</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="iq-card">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">Manage Permissions:</h4>
                    </div>
                </div>
                <div class="iq-card-body">
                    <p>Select the extra permission(s) for this role. By default, tracking is included.</p>
                    <div class="table-responsive" id="permissionTableHolder">
                        <table class="table table-striped table-hover table-sm" id="permissionTable">
                            <thead>
                                <tr>
                                    <th scope="col" style="width:40%">Modules</th>
                                    <th scope="col" class="text-center noSort">All</th>
                                    <th scope="col" class="text-center noSort">View</th>
                                    <th scope="col" class="text-center noSort">Create</th>
                                    <th scope="col" class="text-center noSort">Edit</th>
                                    <th scope="col" class="text-center noSort">Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($modules as $module)
                                <tr>
                                    <td>{{ ucfirst($module['name']) }} Management</td>
                                    <td class="text-center">
                                        <label class="d-flex justify-content-center custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" 
                                                id="all-{{ $module['name'] }}"
                                                onChange="toggle_list_permission(this.id)">
                                            <span class="custom-control-label" style="float:none"></span>
                                        </label>
                                    </td>
                                    <td class="text-center">
                                        <label class="d-flex justify-content-center custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" name="permission[]" 
                                                id="list-{{ $module['name'] }}" value="{{ $module['list']}}" 
                                                onChange="toggle_list_permission(this.id)" {{ isset($module['list']) ? '':'disabled' }}>
                                            <span class="custom-control-label" style="float:none"></span>
                                        </label>
                                    </td>
                                    <td class="text-center">
                                        <label class="d-flex justify-content-center custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" name="permission[]" 
                                                id="create-{{ $module['name'] }}" value="{{ $module['create']}}" 
                                                onChange="toggle_list_permission(this.id)" {{ isset($module['create']) ? '':'disabled' }}>
                                            <span class="custom-control-label" style="float:none"></span>
                                        </label>
                                    </td>
                                    <td class="text-center">
                                        <label class="d-flex justify-content-center custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" name="permission[]" 
                                                id="edit-{{ $module['name'] }}" value="{{ $module['edit']}}" 
                                                onChange="toggle_list_permission(this.id)" {{ isset($module['edit']) ? '':'disabled' }}>
                                            <span class="custom-control-label" style="float:none"></span>
                                        </label>
                                    </td>
                                    <td class="text-center">
                                        <label class="d-flex justify-content-center custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" name="permission[]" 
                                                id="delete-{{ $module['name'] }}" value="{{ $module['delete']}}" 
                                                onChange="toggle_list_permission(this.id)" {{ isset($module['delete']) ? '':'disabled' }}>
                                            <span class="custom-control-label" style="float:none"></span>
                                        </label>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary" onClick="saveRole()">Save</button>
                            <a href="{{ route('settings.index') }}" class="btn iq-bg-danger">Cancel</a>
                        </div>
                    </div>
                    @error('permission')
                        <script>$('#permissionTableHolder').css("border", "1px solid red");</script>
                        <div class="alert-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section("script")
<script>

    $(function () {
        /* Initiate dataTable */
        var table = $('#permissionTable').DataTable({
            responsive: true,
            stateSave: true,
            paging: false,
            searching: false,
            info: false,
            order: [0, 'asc'],
            columnDefs:[{
                targets: 'noSort',
                orderable: false
            }],
        })

        $('#color').on('change', function(event) {
            var color_code = $('#color').val().toUpperCase();
            $('#color-code').html(color_code);
        });

    })
    
    function toggle_list_permission(id){
        let permission = id.split('-')[0];
        let module = id.split('-')[1];

        $('#' + id).prop('disabled', false);

        let all_permission = ['list', 'create', 'edit', 'delete'];

        if(permission != 'all'){
            if(permission != 'list'){
                if($('#' + id).is(':checked')){
                    $('#list-' + module).prop('checked', true);
                }
            } else {
                if(!$('#' + id).is(':checked')){
                    $('#all-' + module).prop('checked', false);
                    $('#create-' + module).prop('checked', false);
                    $('#edit-' + module).prop('checked', false);
                    $('#delete-' + module).prop('checked', false);
                }
            }
        } else {
            if($('#' + id).is(':checked')){
                all_permission.forEach(function(item){
                    let element = $('#' + item + '-' + module);
                    if(!element.is(':disabled')){
                        if(!element.is(':checked')){
                            $('#' + item + '-' + module).prop('checked', true);
                        }
                    }
                })
            } else {
                all_permission.forEach(function(item){
                    $('#' + item + '-' + module).prop('checked', false);
                })
            }
        }

        
        let is_all = true;
        all_permission.forEach(function(item){
            let element = $('#' + item + '-' + module);
            if(!element.is(':disabled')){
                if(!element.is(':checked')){
                    is_all = false;
                }
            }
        });

        if(is_all){
            $('#all-' + module).prop('checked', true);
        } else {
            $('#all-' + module).prop('checked', false);
        }
    };

    function saveRole(){
        console.log('inside save role');
        let items = [
            'name',
            'color',
            'permission'
        ];

        let result = {};
        items.forEach(function(item){
            result[item] = validateRoleInput(item);
        });
    }

    function validateRoleInput(id){
        console.log('inside validate role input');
        let obj = $('#' + id);
        switch(id){
            case 'name':
                addInvalid('name');
                break;
            case 'color':
                addInvalid('color');
                break;
            case 'permission':
                addInvalid('permission');
                break;
        }
        console.log('validate');
    }
    
    function addInvalid(id){
        let obj = $('#' + id);
        if(id != "permission"){
            if(!obj.hasClass('is-invalid')){
                obj.addClass('is-invalid');

                if(id == "color"){
                    $('#color-code').css('border', '1px solid #dc3545');
                    $('#color-code').css('border-radius', '0 0.25rem 0.25rem 0');
                    obj.siblings('.input-group-append').after('<div class="invalid-feedback" id="invalid-' + id +'">Please select the color for this role.</div>');
                } else {
                    obj.after('<div class="invalid-feedback" id="invalid-' + id +'">Please enter a name in the input field.</div>');
                }
            }

        } else {
            console.log(permission);
        }
        
    }
</script>
@endsection