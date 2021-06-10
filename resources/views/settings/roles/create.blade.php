@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12 col-lg-9">
            {!! Form::open(array('route' => 'roles.store','method'=>'POST')) !!}
                <div class="iq-card">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title"><strong>Add Role:</strong></h4>
                        </div>
                    </div>
                    <div class="iq-card-body">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="name">Name:</label>
                                {!! Form::text('name', null, array('placeholder' => 'Enter name', 'class' => "form-control", 'id' => 'name')) !!}
                                @error('name')
                                    <script>$('#name').addClass("is-invalid");</script>
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="color">Color:</label>
                                {!! Form::select('color_id', $colors, null, ['placeholder' => 'Please select...', 'class' => 'form-control', 'id' => 'color']) !!}
                                <!-- <div class="input-group">
                                    {!! Form::select('color_id', $colors, null, ['placeholder' => 'Please select...', 'class' => 'form-control', 'id' => 'color', 'style' => 'width:80%']) !!}
                                    <div class="input-group-append">
                                        <div class="input-group-text" id='color-code' style="width:75px"></div>
                                    </div>
                                </div> -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="iq-card">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">Manage Permissions: {{session('permissions')}}</h4>
                        </div>
                    </div>
                    <div class="iq-card-body">
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
                                                {{ Form::checkbox('permission[]', $module['list'], null, array('class' => 'custom-control-input', 
                                                    'id'=>'list-'.$module['name'], 'onChange' =>'toggle_list_permission(this.id)', isset($module['list']) ? '':'disabled')) }}
                                                <span class="custom-control-label" style="float:none"></span>
                                            </label>
                                        </td>
                                        <td class="text-center">
                                            <label class="d-flex justify-content-center custom-control custom-checkbox">
                                                {{ Form::checkbox('permission[]', $module['create'], null, array('class' => 'custom-control-input', 
                                                    'id'=>'create-'.$module['name'], 'onChange' =>'toggle_list_permission(this.id)', isset($module['create']) ? '':'disabled')) }}
                                                <span class="custom-control-label" style="float:none"></span>
                                            </label>
                                        </td>
                                        <td class="text-center">
                                            <label class="d-flex justify-content-center custom-control custom-checkbox">
                                                {{ Form::checkbox('permission[]', $module['edit'], null, array('class' => 'custom-control-input', 
                                                    'id'=>'edit-'.$module['name'], 'onChange' => 'toggle_list_permission(this.id)', isset($module['edit']) ? '':'disabled')) }}
                                                <span class="custom-control-label" style="float:none"></span>
                                            </label>
                                        </td>
                                        <td class="text-center">
                                            <label class="d-flex justify-content-center custom-control custom-checkbox">
                                                {{ Form::checkbox('permission[]', $module['delete'], null, array('class' => 'custom-control-input', 
                                                    'id'=>'delete-'.$module['name'], 'onChange' =>'toggle_list_permission(this.id)', isset($module['delete']) ? '':'disabled')) }}
                                                <span class="custom-control-label" style="float:none"></span>
                                            </label>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @error('permission')
                                <script>$('#permissionTable').css("border", "1px solid #dc3545");</script>
                                <div class="invalid-feedback" style="display:block">{{ $message }}</div>
                            @enderror
                            <div class="text-center mt-5">
                                <button type="submit" class="btn btn-primary">Save Role</button>
                                @php(session(['role' => 'page']))
                                <a href="{{ route('settings.index') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </div>
                    </div>
                </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection

@section("script")
<script>

    $(function () {
        $('#color').select2({
            templateResult: formatState,
            templateSelection: formatState
        });

        function formatState (state){
            if (!state.id) {
                return state.text;
            }
            let code = state.text.split('-')[0];
            let name = state.text.split('-')[1];
            
            let $state = $(
                '<span><i class="ri-bookmark-fill" style="color:'+ code + '"></i>  ' + name +'</span>'
            )

            return $state
        }

        /* Initiate dataTable */
        let table = $('#permissionTable').DataTable({
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

        $('#color-code').html($('#color').val().toUpperCase());

        @foreach($modules as $module)
            check_all('all-' + @json($module['name']));
        @endforeach

        @error('color_id')
        message = @json($message);
        $('#color').siblings('span').find('.select2-selection').css('border', '1px solid #dc3545');
        $('#color').siblings('span').after('<div class="invalid-feedback" style="display:block">'+ message +'</div>');
        @enderror

    })

    // $('#color').on('change', function(event) {
    //     let color_code = $('#color').val();
    //     $('#color-code').css('background-color', color_code);
    // });

    function check_all(id){
        let is_all = true;
        let module = id.split('-')[1];
        
        ['list', 'create', 'edit', 'delete'].forEach(function(item){
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
    }
    
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
        check_all(id);
    };
</script>
@endsection