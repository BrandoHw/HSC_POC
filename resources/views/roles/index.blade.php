@extends('layouts.app')

@section('content')
<div class="container-fluid p-0">
    <!-- Title & Add-Button -->
    <div class="row mb-2 mb-xl-3">
        <div class="col-auto d-none d-sm-block">
            <h3><strong>Roles</strong> Management</h3>
        </div>
        <div class="col-auto ml-auto text-right mt-n1">
            @can('role-create')
                <a class="btn btn-primary" href="{{ route('roles.create') }}">
                    @svg('plus', 'feather-plus align-middle')  
                    <span class="align-middle">Add role</span>
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
                        <table class="table table-striped table-hover" id="roleTable">
                            <thead>
                                <tr>
                                    <th scope="col" style="width:5%">#</th>
                                    <th scope="col" style="width:40%">Name</th>
                                    <th scope="col" style="width:15%">Color</th>
                                    <th scope="col" style="width:15%">Users</th>
                                    <th scope="col" style="width:15%">Permissions</th>
                                    <th scope="col" class="noSort">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($roles as $role)
                                    <tr id="trRole-{{ $role->id }}">
                                        <td>{{ $role->id }}</td>
                                        <td id="tdRoleName-{{ $role->id }}">
                                            <a href="#" data-toggle="modal" data-target="#showRoleModal" id="{{ $role->id }}" onClick="getShowRoleInfo(this.id)">
                                                {{ $role->name }} 
                                            </a>
                                        </td>
                                        <td id="tdRoleColor-{{ $role->id }}">
                                            <span class="badge badge-dark" style="background-color: {{ $role->color }} !important">{{ Str::upper($role->color) }}</span>
                                        </td>
                                        <td id="tdRoleUserNum-{{ $role->id }}">
                                            @if(!empty($users))
                                                @foreach ($users as $user)
                                                    {{ $user->role($role->name)->count() }}
                                                    @break
                                                @endforeach
                                            @endif
                                        </td>
                                        <td id="tdRolePermissionNum-{{ $role->id }}">
                                            @if(!empty($rolePermissions))
                                                {{ $rolePermissions->where("role_id", $role->id)->count() }}
                                            @endif
                                        </td>
                                        <td class="table-action row" style="margin:0px">
                                            @if($role->name != "Admin")
                                                @can('role-edit')
                                                    <a href="{{ route('roles.edit',$role->id) }}">
                                                        @svg('edit-2', 'feather-edit-2 align-middle')  
                                                    </a>
                                                @endcan
                                                @can('role-delete')
                                                    <a href="#" data-toggle="modal" data-target="#deletetRoleModal" id="{{ $role->id }}" onClick="getDeleteRoleInfo(this.id)">
                                                        @svg("trash", "feather-trash align-middle")
                                                    </a>
                                                @endcan
                                            @endif
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
    <!-- Show Role Modal -->
    <div class="modal fade" id="showRoleModal" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document" style="heigh:400px; overflow:auto;">
            @include("roles.show")
        </div>
    </div>
    <!-- Delete Role Modal -->
    <div class="modal fade" id="deleteRoleModal" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document" style="heigh:400px; overflow:auto;">
            @include("roles.delete")
        </div>
    </div>
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
        $('#roleTable').DataTable({
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
    })
    
    /* Insert Data into Permission Table */
    function populatePermissionTable(item){
        function capitalFirstLetter(str){
            str = str[0].toUpperCase() + str.substr(1) + " Management";
            return str;
        }   

        function getControlInfoHtml(control){
            str = '<label class="d-flex justify-content-center custom-control custom-checkbox">';
            
            if(control != null){
                str = str + '<input type="checkbox" class="custom-control-input" name="permission[]" value="'+ control + '" checked disabled>';
            } 
            else{
                str = str + '<input type="checkbox" class="custom-control-input" name="permission[]" value="'+ control + '" disabled>';
            }

            str = str + '<span class="custom-control-label" style="float:none"></span></label>';

            return str;
        }
        
        this.row.add([
            capitalFirstLetter(item['name']),
            getControlInfoHtml(item['list']),
            getControlInfoHtml(item['create']),
            getControlInfoHtml(item['edit']),
            getControlInfoHtml(item['delete']),
        ])
        .node().id = 'trModule-' + item['name'];
        this.draw(false);

        for(i = 2; i < 6; i++){
            $('#trModule-' + item['name'] + 'td:eq(' + i + ')').attr('class', 'text-center')    
        }
    }

    function getShowRoleInfo(id){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#showFooter').empty();

        $.ajax({
            url: 'roles/' + id,
            type: "GET",
            data: {id: id},
            success:function(response){
                var role = response['role'];
                var modules= response['modules'];
                // console.log(role);
                // console.log(modules);

                $("#showModalHeader")
                    .empty()
                    .append('<h4 class="modal-title"><strong>' + role['name'] +'</strong> Role</h4>'
                        + '<button type="button" class="close " data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>');
                
                $('#showRoleModal').modal('show');
                $(".editRoleBtn").attr("id", id);
                $(".deleteRoleBtn").attr("id", id);
                $('#showName').html('<label class="col-form-label col-sm-2 text-sm-right">Name:</label><div class="col-sm-10">' + role['name'] +'</div>');
                $('#showColor').html('<label class="col-form-label col-sm-2 text-sm-right">Color:</label><div class="col-sm-10"><span class="badge badge-dark" style="background-color:'+ role['color'] +' !important">' + role['color'].toUpperCase() + '</span></div>');
                $('#showPermissions').html('<label class="col-form-label col-sm-2 text-sm-right">Permissions:</label><div class="col-sm-10 table-responsive"><table class="table table-striped table-hover table-sm" id="showPermissionTable"></table></div></div>');

                if(role['name'] == "Admin"){
                    $('#showFooter').html('<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>');
                }else{
                    @can('role-delete')
                        $('#showFooter').append('<a href="#" class="mr-auto text-danger deleteRoleBtn" data-dismiss="modal" onClick="getDeleteRoleInfo(this.id)">Delete profile</a>');
                    @endcan
                    $('#showFooter').append('<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>');
                    @can('role-edit')
                        $('#showFooter').append('<button type="button" class="btn btn-primary editRoleBtn" data-dismiss="modal" onClick="getEditRoleInfo(this.id)">Edit role</button>');
                    @endcan
                }
                
                /* Initiate dataTable */
                var table = $('#showPermissionTable').DataTable({
                    responsive: true,
                    stateSave: true,
                    paging: false,
                    searching: false,
                    info: false,
                    'columns': [
                        {'title': 'Modules', 'style': 'width:40%', "cellType": "td"},
                        {'title': 'View', className: 'text-center noSort', "cellType": "td", 'orderable': false},
                        {'title': 'Create', className: 'text-center noSort', "cellType": "td", 'orderable': false},
                        {'title': 'Edit', className: 'text-center noSort', "cellType": "td", 'orderable': false},
                        {'title': 'Delete', className: 'text-center noSort', "cellType": "td", 'orderable': false},
                    ]
                })

                modules.forEach(populatePermissionTable, table);
                
            },
            error:function(error){
                console.log(error)
            }
        })
    }

    @can('role-edit')
    function getEditRoleInfo(id)
    {
        var url = "{{ route('roles.edit', ':id') }}";

        url = url.replace(':id', id);

        location.href=url;
    }
    @endcan

    @can('role-delete')
    function getDeleteRoleInfo(id){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: 'roles/' + id,
            type: "GET",
            data: {id: id},
            success:function(response){
                var role = response['role'];
                var modules= response['modules'];
                // console.log(role);
                // console.log(modules);
                
                $('#deleteRoleModal').modal('show');
                $(".deleteRoleBtn").attr("id", id);
                $('#deleteName').html('<label class="col-form-label col-sm-2 text-sm-right">Name:</label><div class="col-sm-10">' + role['name'] +'</div>');
                $('#deleteColor').html('<label class="col-form-label col-sm-2 text-sm-right">Color:</label><div class="col-sm-10"><span class="badge badge-dark" style="background-color:'+ role['color'] +' !important">' + role['color'].toUpperCase() + '</span></div>');
                $('#deletePermissions').html('<label class="col-form-label col-sm-2 text-sm-right">Permissions:</label><div class="col-sm-10 table-responsive"><table class="table table-striped table-hover table-sm" id="deletePermissionTable"></table></div></div>');
                
                /* Initiate dataTable */
                var table = $('#deletePermissionTable').DataTable({
                    responsive: true,
                    stateSave: true,
                    paging: false,
                    searching: false,
                    info: false,
                    'columns': [
                        {'title': 'Modules', 'style': 'width:40%', "cellType": "td"},
                        {'title': 'View', className: 'text-center noSort', "cellType": "td", 'orderable': false},
                        {'title': 'Create', className: 'text-center noSort', "cellType": "td", 'orderable': false},
                        {'title': 'Edit', className: 'text-center noSort', "cellType": "td", 'orderable': false},
                        {'title': 'Delete', className: 'text-center noSort', "cellType": "td", 'orderable': false},
                    ]
                })

                modules.forEach(populatePermissionTable, table);
            },
            error:function(error){
                console.log(error)
            }
        })
    }

    function deleteRole(id){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: 'roles/' + id,
            type: "DELETE",
            data: {id: id},
            success:function(response){
                if($.isEmptyObject(response['error'])){
                    
                    // console.log(response['success']);
                    var table = $('#roleTable').DataTable();

                    table.row('#trRole-'+id).remove().draw();
                    $("#deleteRoleModal").modal('toggle');

                } else {
                    console.log(response.error);
                }
            },
            error:function(error){
                console.log(error)
            }
        })

    }
    @endcan
</script>
@endsection