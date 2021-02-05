@extends('layouts.app')

@section('content')
<div class="container-fluid p-0">
    <!-- Display alert -->
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
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
            <h3><strong>Users</strong> Management</h3>
        </div>
        <div class="col-auto ml-auto text-right mt-n1">
            @can('user-create')
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createUserModal" onClick="getCreateUserInfo()">
                    @svg('plus', 'feather-plus align-middle')  
                    <span class="align-middle">Add user</span>
                </button>
            @endcan
        </div>
	</div>

    <!-- Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="userTable">
                            <thead>
                                <tr>
                                    <th scope="col" style="width:5%">#</th>
                                    <th scope="col" style="width:30%">Name</th>
                                    <th scope="col" style="width:25%">Email</th>
                                    <th scope="col" style="width:20%">Tag</th>
                                    <th scope="col" style="width:15%">Roles</th>
                                    <th scope="col" class="noSort">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr id="trUser-{{ $user->id }}">
                                        <td>{{ $user->id }}</td>
                                        <td id="tdUserName-{{ $user->id }}">
                                            <a href="#" data-toggle="modal" data-target="#showUserModal" id="{{ $user->id }}" onClick="getShowUserInfo(this.id)">
                                                {{ $user->name }} 
                                            </a>
                                        </td>
                                        <td id="tdUserEmail-{{ $user->id }}">{{ $user->email }}</td>
                                        <td id="tdUserTag-{{ $user->id }}">
                                                {{ $user->tag->serial ?? "Not Assigned"}}
                                        </td>
                                        <td id="tdUserRole-{{ $user->id }}">
                                            <span class="badge badge-pill badge-dark" style="background-color: {{ $user->roles[0]->color }}">{{ $user->getRoleNames()[0] }}</span>
                                        </td>
                                        <td class="table-action row" style="margin:0px">
                                            @if($user->name != "Superadmin")
                                                @can('user-edit')
                                                    <a href="#" data-toggle="modal" data-target="#editUserModal" id="{{ $user->id }}" onClick="getEditUserInfo(this.id)">
                                                        @svg("edit-2", "feather-edit-2 align-middle")
                                                    </a>
                                                @endcan
                                                @can('user-delete')
                                                    <a href="#" data-toggle="modal" data-target="#deletetUserModal" id="{{ $user->id }}" onClick="getDeleteUserInfo(this.id)">
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
    <!-- Show User Modal -->
    <div class="modal fade" id="showUserModal" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document" style="heigh:400px; overflow:auto;">
            @include("users.show")
        </div>
    </div>
    <!-- Create User Modal -->
    <div class="modal fade" id="createUserModal" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            @include('users.create')
        </div>
    </div>
    <!-- Edit User Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document" style="heigh:400px; overflow:auto;">
            @include("users.edit")
        </div>
    </div>
    <!-- Delete User Modal -->
    <div class="modal fade" id="deleteUserModal" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document" style="heigh:400px; overflow:auto;">
            @include("users.delete")
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
        $('#userTable').DataTable({
            dom: '<fl<t>ip>',
            responsive: true,
            stateSave: true,
            'lengthMenu': [[10, 25, 50, -1], [10, 25, 50, "All"]],
            orders: [],
            "columnDefs": [ {
                "targets"  : 'noSort',
                "orderable": false,
            }]
        })
    })
    
    function getShowUserInfo(id){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $("#showModalHeader").empty();
        $('#showName').empty();
        $('#showUsername').empty();
        $('#showEmail').empty();
        $('#showRoleId').empty();
        $('#showFooter').empty();

        $.ajax({
            url: 'users/' + id,
            type: "GET",
            data: {id: id},
            success:function(response){
                var user = response['user'];
                var userRole = response['userRole'];
                var userTagSerial = response['userTagSerial'];
                var tagsNull = response['tagsNull'];

                $("#showModalHeader")
                    .append('<h4 class="modal-title"><strong>' + user['name'] +'</strong> Profile</h4>'
                        + '<button type="button" class="close " data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>');
                
                $('#showName').html('<label class="col-form-label col-sm-4 text-sm-right">Name:</label><div class="col-sm-8">' + user['name'] +'</div>');
                $('#showUsername').html('<label class="col-form-label col-sm-4 text-sm-right">Username:</label><div class="col-sm-8">' + user['username'] +'</div>');
                $('#showEmail').html('<label class="col-form-label col-sm-4 text-sm-right">Email:</label><div class="col-sm-8">' + user['email'] +'</div>');
                // $('#showTagId').html('<label class="col-form-label col-sm-4 text-sm-right">Tag:</label><div class="col-sm-8">' + userTagSerial['serial'] +'</div>');
                $('#showRoleId').html('<label class="col-form-label col-sm-4 text-sm-right">Role:</label><div class="col-sm-8"><span class="badge badge-pill badge-secondary">' + userRole['name'] + '</span></div>');

                if(user['name'] == "Superadmin"){
                    $('#showFooter').html('<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>');
                }else{
                    @can('user-delete')
                        $('#showFooter').append('<a href="#" class="mr-auto text-danger deleteUserBtn" data-dismiss="modal" onClick="getDeleteUserInfo(this.id)">Delete profile</a>');
                    @endcan
                    $('#showFooter').append('<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>');
                    @can('user-edit')
                        $('#showFooter').append('<button type="button" class="btn btn-primary editUserBtn" data-dismiss="modal" onClick="getEditUserInfo(this.id)">Edit role</button>');
                    @endcan
                }

                $(".editUserBtn").attr("id", id);
                $(".deleteUserBtn").attr("id", id);
            },
            error:function(error){
                console.log(error)
            }
        })
    }

    @can('user-create')
    function getCreateUserInfo()
    {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: 'users/create',
            type: "GET",
            success:function(response){

                $('#createName').css('border', '');
                 $('#createUsername').css('border', '');
                 $('#createEmail').css('border', '');
                 $('#createPassword').css('border', '');
                 $('#createConfirmPassword').css('border', '');
                 $('#createNameAlert').remove();
                 $('#createUsernameAlert').remove();
                 $('#createEmailAlert').remove();
                 $('#createPasswordAlert').remove();
                 $('#createConfirmPasswordAlert').remove();
                 $('#createOtherAlert').remove();
                $('#createRoleId').val('1');
                
            },
            error:function(error){
                console.log(error)
            }
        });
    }

    function createUser(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var result = {};
        
        var name = result['name'] =  $("#createName").val();
        var username = result['username'] = $("#createUsername").val();
        var email = result['email'] = $("#createEmail").val();
        var password = result['password'] = $("#createPassword").val();
        var confirmPassword = result['confirmPassword'] = $("#createConfirmPassword").val();
        var role_id = result['role_id'] = $('#createRoleId').val();
        
        // console.log(result);

        $.ajax({
            url: 'users',
            type: "POST",
            data: result,
            success:function(response){
                if($.isEmptyObject(response['success'])){
                    var errors = response['errors'];

                     Object.keys(errors).forEach(function(key){
                         switch(key) {
                             case 'name':
                                 if(document.getElementById('createNameAlert') == null){
                                     $('#createNameField')
                                         .append('<div class="alert-danger" id="createNameAlert">' + errors[key][0] + '</div>');
                                     $("#createName").css("border", "1px solid red");
                                 }
                                 break;
                             case 'username':
                                 if(document.getElementById('createUsernameAlert') == null){
                                     $('#createUsernameField')
                                         .append('<div class="alert-danger" id="createUsernameAlert">' + errors[key][0] + '</div>');
                                     $("#createUsername").css("border", "1px solid red");
                                 }
                                 break;
                             case 'email':
                                 if(document.getElementById('createEmailAlert') == null){
                                     $('#createEmailField')
                                         .append('<div class="alert-danger" id="createEmailAlert">' + errors[key][0] + '</div>');
                                     $("#createEmail").css("border", "1px solid red");
                                 }
                                 break;
                             case 'password':
                                 if(document.getElementById('createPasswordAlert') == null){
                                     $('#createPasswordField')
                                         .append('<div class="alert-danger" id="createPasswordAlert">' + errors[key][0] + '</div>');
                                     $("#createPassword").css("border", "1px solid red");
                                     $('#createConfirmPasswordField')
                                         .append('<div class="alert-danger" id="createConfirmPasswordAlert">' + errors[key][0] + '</div>');
                                     $("#createConfirmPassword").css("border", "1px solid red");
                                 }
                                 break;
                             default:
                                 if(document.getElementById('createOtherAlert') == null){
                                     $('#createModalBody')
                                         .prepend('<div class="alert-danger" id="createOtherAlert">' + errors[key][0] + '</div>');
                                 }
                         }
                     })
                    
                } else {
                    notyf.success(response['success']);
                    
                    var id = response['userId']
                    var userRole = response['userRole'];
                    var userTagSerial = response['userTagSerial'];
                    var table = $('#userTable').DataTable();

                    table.row.add([
                        id,
                        '<a href="{{ route('users.show',$user->id) }}">' + name + '</a>',
                        email,
                        userTagSerial,
                        '<span class="badge badge-pill badge-dark" style="background-color: ' + userRole['color'] + '">' + userRole['name'] + '</span>',
                        @can('user-edit', 'user-delete')
                            '<a href="#" data-toggle="modal" data-target="#editUserModal" id="' + id + '" onClick="getEditUserInfo(this.id)">@svg("edit-2", "feather-edit-2 align-middle")</a>'
                                + '<a href="#" data-toggle="modal" data-target="#deletetUserModal" id="' + id + '" onClick="getDeleteUserInfo(this.id)">@svg("trash", "feather-trash align-middle")</a>'
                        @elsecan('user-edit')
                            '<a href="#" data-toggle="modal" data-target="#editUserModal" id="' + id + '" onClick="getEditUserInfo(this.id)">@svg("edit-2", "feather-edit-2 align-middle")</a>'
                        @elsecan('user-delete')
                            '<a href="#" data-toggle="modal" data-target="#deletetUserModal" id="' + id + '" onClick="getDeleteUserInfo(this.id)">@svg("trash", "feather-trash align-middle")</a>'
                        @endcan   
                    ])
                    .node().id = 'trUser-'+id;
                    table.draw( false );
                    $('#trUser-'+id+' td:eq(1)').attr('id', 'tdUserName-'+id);
                    $('#trUser-'+id+' td:eq(2)').attr('id', 'tdUserEmail-'+id);
                    $('#trUser-'+id+' td:eq(3)').attr('id', 'tdUserRole-'+id); 
                    $('#trUser-'+id+' td:eq(4)').attr('id', 'tdUserTag-'+id); 
                    $('#trUser-'+id+' td:eq(5)').attr('class', 'table-action row'); 
                    $('#trUser-'+id+' td:eq(5)').css('margin', '0px'); 

                    $('#createUserModal').modal('toggle');
                    $('#createName').val('');
                    $('#createUsername').val('');
                    $('#createEmail').val('');
                    $('#createPassword').val('');
                    $('#createConfirmPassword').val('');
                    $('#createRoleId').val('');
                }
            },
            error:function(error){
                console.log(error)
            }
        })

    }
    @endcan

    @can('user-edit')
    function getEditUserInfo(id)
    {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: 'users/' + id + '/edit',
            type: "GET",
            data: {id: id},
            success:function(response){
                var user = response['user'];
                var userRole = response['userRole'];
                // var userTagSerial = response['userTagSerial'];
                // var tagsNull = response['tagsNull'];

                // console.log(user);
                // console.log(userTagSerial); 
                // console.log(userRole);
                // console.log(tagsNull);
                
                $('#editUserModal').modal('show');
                $("#editModalHeader")
                    .empty()
                    .append('<h5 class="modal-title">Edit <strong>' + user['name'] +'</strong> Profile</h5>'
                        + '<button type="button" class="close " data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>');
                $(".updateUserBtn").attr("id", id);
                $(".deleteUserBtn").attr("id", id);
                $("#editName").val(user['name']);
                $("#editUsername").val(user['username']);
                $("#editEmail").val(user['email']);
                $("#editRoleId").val(userRole['id']);
                // $("#editTagId")
                //     .empty()
                //     .append($('<option></option>')
                //         .attr("value", userTagSerial['id'])
                //         .text(userTagSerial['serial']))
                //     .val(userTagSerial['id']);

                // console.log(tagsNull);

                // tagsNull.forEach(populateEditTagSelect);

                // function populateEditTagSelect(item,index) {
                //     $('#editTagId')
                //         .append($('<option></option>')  
                //             .attr("value", item["id"])
                //             .text(item['serial']));
                // }

                $('#editName').css('border', '');
                $('#editUsername').css('border', '');
                $('#editEmail').css('border', '');
                $('#editPassword').css('border', '');
                $('#editConfirmPassword').css('border', '');
                $('#editNameAlert').remove();
                $('#editUsernameAlert').remove();
                $('#editEmailAlert').remove();
                $('#editPasswordAlert').remove();
                $('#editConfirmPasswordAlert').remove();
                $('#editOtherAlert').remove();
            },
            error:function(error){
                console.log(error)
            }
        })
    }

    function updateUser(id){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var result = {};
        
        result['id'] = id;
        var name = result['name'] =  $("#editName").val();
        var username = result['username'] = $("#editUsername").val();
        var email = result['email'] = $("#editEmail").val();
        var password = result['password'] = $("#editPassword").val();
        var confirmPassword = result['confirmPassword'] = $("#editConfirmPassword").val();
        // var tag_id = result['tag_id'] = $('#editTagId').val();
        var role_id = result['role_id'] = $('#editRoleId').val();
        
        // console.log(result);

        $.ajax({
            url: 'users/' + id,
            type: "PATCH",
            data: result,
            success:function(response){
                if($.isEmptyObject(response['success'])){
                     console.log(response['errors']);
                     var errors = response['errors'];

                     Object.keys(errors).forEach(function(key){
                         switch(key) {
                             case 'name':
                                 if(document.getElementById('editNameAlert') == null){
                                     $('#editNameField')
                                         .append('<div class="alert-danger" id="editNameAlert">' + errors[key][0] + '</div>');
                                     $("#editName").css("border", "1px solid red");
                                 }
                                 break;
                             case 'username':
                                 if(document.getElementById('editUsernameAlert') == null){
                                     $('#editUsernameField')
                                         .append('<div class="alert-danger" id="editUsernameAlert">' + errors[key][0] + '</div>');
                                     $("#editUsername").css("border", "1px solid red");
                                 }
                                 break;
                             case 'email':
                                 if(document.getElementById('editEmailAlert') == null){
                                     $('#editEmailField')
                                         .append('<div class="alert-danger" id="editEmailAlert">' + errors[key][0] + '</div>');
                                     $("#editEmail").css("border", "1px solid red");
                                 }
                                 break;
                             case 'password':
                                 if(document.getElementById('editPasswordAlert') == null){
                                     $('#editPasswordField')
                                         .append('<div class="alert-danger" id="editPasswordAlert">' + errors[key][0] + '</div>');
                                     $("#editPassword").css("border", "1px solid red");
                                     $('#editConfirmPasswordField')
                                         .append('<div class="alert-danger" id="editConfirmPasswordAlert">' + errors[key][0] + '</div>');
                                     $("#editConfirmPassword").css("border", "1px solid red");
                                 }
                                 break;
                             default:
                                 if(document.getElementById('editOtherAlert') == null){
                                     $('#editModalBody')
                                         .prepend('<div class="alert-danger" id="editOtherAlert">' + errors[key][0] + '</div>');
                                 }
                         }
                     })
                     
                 } else {
                    
                    notyf.success(response['success']);
                    
                    var userRole = response['userRole'];
                    // var userTagSerial = response['userTagSerial'];

                    $('#tdUserName-' + id).html('<a href="{{ route('users.show',$user->id) }}">' + name + '</a>');
                    $('#tdUserEmail-' + id).html(email);
                    // $('#tdUserTag-' + id).html(userTagSerial['serial']);
                    $('#tdUserRole-' + id).html('<span class="badge badge-pill badge-dark" style="background-color: ' + userRole['color'] + '">' + userRole['name'] + '</span>');
                    
                    $('#editName').val('');
                    $('#editUsername').val('');
                    $('#editEmail').val('');
                    $('#editPassword').val('');
                    $('#editConfirmPassword').val('');
                    $('#editUserModal').modal('toggle');
                }
            },
            error:function(error){
                console.log(error)
            }
        })

    }
    @endcan

    @can('user-delete')
    function getDeleteUserInfo(id){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: 'users/' + id,
            type: "GET",
            data: {id: id},
            success:function(response){
                var user = response['user'];
                var userRole = response['userRole'];
                var userTagSerial = response['userTagSerial'];
                
                $('#deleteUserModal').modal('show');
                $(".deleteUserBtn").attr("id", id);
                $('#deleteName').html('<label class="col-form-label col-sm-4 text-sm-right">Name:</label><div class="col-sm-8">' + user['name'] +'</div>');
                $('#deleteUsername').html('<label class="col-form-label col-sm-4 text-sm-right">Username:</label><div class="col-sm-8">' + user['username'] +'</div>');
                $('#deleteEmail').html('<label class="col-form-label col-sm-4 text-sm-right">Email:</label><div class="col-sm-8">' + user['email'] +'</div>');
                // $('#deleteTagId').html('<label class="col-form-label col-sm-4 text-sm-right">Tag:</label><div class="col-sm-8">' + userTagSerial['serial'] +'</div>');
                $('#deleteRoleId').html('<label class="col-form-label col-sm-4 text-sm-right">Role:</label><div class="col-sm-8"><span class="badge badge-pill badge-secondary">' + userRole['name'] + '</span></div>');
                
            },
            error:function(error){
                console.log(error)
            }
        })
    }

    function deleteUser(id){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: 'users/' + id,
            type: "DELETE",
            data: {id: id},
            success:function(response){
                if($.isEmptyObject(response['error'])){
                    
                    notyf.success(response['success']);
                    var table = $('#userTable').DataTable();

                    table.row('#trUser-'+id).remove().draw();
                    $("#deleteUserModal").modal('toggle');

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
