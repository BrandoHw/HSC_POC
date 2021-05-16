@extends('layouts.app')

@section('content')
<div class="container-fluid relative">
    <div class="row">
        <div class="col-lg-12">
            <div class="iq-card">
                <div class="iq-card-body">
                    <div>
                        <ul class="nav nav-tabs justify-content-right" id="myTab-2" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="profile-setting" data-toggle="tab" href="#manage-profile" role="tab" aria-selected="true">Profile</a>
                            </li>
                            @can('user-list')
                            <li class="nav-item">
                                <a class="nav-link" id="user-setting" data-toggle="tab" href="#manage-user" role="tab"  aria-selected="false">Manage Users</a>
                            </li>
                            @endcan
                            @can('role-list')
                            <li class="nav-item">
                                <a class="nav-link" id="role-setting" data-toggle="tab" href="#manage-role" role="tab"  aria-selected="false">Roles & Permissions</a>
                            </li>
                            @endcan
                        </ul>
                        <div class="tab-content" id="myTabContent-3">
                            <div class="tab-pane fade show active" id="manage-profile" role="tabpanel" aria-labelledby="profile-setting">
                                @include('settings.profile')
                            </div>
                            @can('user-list')
                            <div class="tab-pane fade" id="manage-user" role="tabpanel" aria-labelledby="user-setting">
                                @include('settings.users.index')
                            </div>
                            @endcan
                            @can('role-list')
                            <div class="tab-pane fade" id="manage-role" role="tabpanel" aria-labelledby="role-setting">
                                @include('settings.roles.index')
                            </div>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 

@section("script")
<script>
    $(function(){
        @if(session('password'))
            $('#manage-personal').removeClass('active');
            $('#personal-tab').removeClass('active show');
            $('#manage-password').addClass('active');
            $('#password-tab').addClass('active show');
        @endif

        @can('user-list')
        @if(session('user'))
            $('#profile-setting').removeClass('active');
            $('#role-setting').removeClass('active');
            $('#user-setting').addClass('active');
            $('#manage-profile').removeClass('active show');
            $('#manage-role').removeClass('active show');
            $('#manage-user').addClass('active show');
            @php(Session::forget('user'))
        @endif
        @endcan

        @can('role-list')
        @if(session('role'))
            $('#profile-setting').removeClass('active');
            $('#user-setting').removeClass('active');
            $('#role-setting').addClass('active');
            $('#manage-profile').removeClass('active show');
            $('#manage-user').removeClass('active show');
            $('#manage-role').addClass('active show');
            @php(Session::forget('role'))
        @endif
        @endcan

        /* Profile */
        /* Initialise select2 */
        changeSelect();

        @if($available)
            @if(!empty($user->tag))
                $('#tag').select2('val', [@json($user->tag->beacon_id)]);
            @else
                $('#tag').val('').trigger('change');
            @endif
        @else
            $('#assign').prop('disabled', true);
            $('#tag').prop('disabled', true);
        @endif

        @canany(['user-edit', 'beacon-edit'])
            $('#assign').prop('disabled', false);
            $('#tag').prop('disabled', false);
        @endcanany
        
        @can('user-edit')
            $('#role').prop('disabled', false);
        @endcan

        /* Display select2 error */
        let message = "Error Message";

        @error('gender')
        /* Profile Tag Error */
        message = @json($message);
        $('#gender').siblings('span').find('.select2-selection').css('border', '1px solid #dc3545');
        $('#gender').siblings('span').after('<div class="invalid-feedback" style="display:block">'+ message +'</div>');
        @enderror

        @error('role')
        /* Profile Tag Error */
        message = @json($message);
        $('#role').siblings('span').find('.select2-selection').css('border', '1px solid #dc3545');
        $('#role').siblings('span').after('<div class="invalid-feedback" style="display:block">'+ message +'</div>');
        @enderror

        @error('beacon_id')
        /* Profile Tag Error */
        message = @json($message);
        $('#tag').siblings('span').find('.select2-selection').css('border', '1px solid #dc3545');
        $('#tag').siblings('span').after('<div class="invalid-feedback" id="invalid-tag" style="display:block">'+ message +'</div>');
        $('#tag').val('').trigger('change');
        @enderror
    })

    @if($message = session('success'))
        notyf.success(@json($message));
    @endif
    @if($message = session('error'))
        notyf.error(@json($message));
    @endif

    /* Profile */
    /* Initialise select2 */
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        changeSelect()
    })

    function changeSelect(){
        $('#gender').select2({
            tags: true,
        });
        $('#role').select2({ 
            tags: true ,
        });
        $('#tag').select2({ 
            tags: true ,
        });
    }

    @can('user-edit')
    $('#assign').on('change', function(){
        if($('#assign').is(':checked')){
            if($('#tag').hasClass("select2-hidden-accessible")){
                $('#tag').select2('destroy');
            }
            $('#tag-div').prop('hidden', false);
            if(!$('#tag').hasClass("select2-hidden-accessible")){
                $('#tag').select2();
            }
            if($('#invalid-tag').length){
                $('#invalid-tag').remove();
            }
            $('#assign').val('1');
        } else {
            $('#assign').val('0');
            $('#tag-div').prop('hidden', true);
        }
        $('#tag').val('').trigger('change');
    })
    @endcan

    @can('user-list')
    /* Initiate user dataTable */
    var userTable = $('#userTable').DataTable({
            order: [[1, 'asc']],
        })

    $('#userCustomSearchBox').keyup(function(){  
        userTable.search($(this).val()).draw();   // this  is for customized searchbox with datatable search feature.
    })

    $('#userTable').on('click', '.info-user', function () {
        window.location.href = $(this).parent('tr').attr('href');
    });
    @endcan

    @can('role-list')
    /* Initiate role dataTable */
    var roleTable = $('#roleTable').DataTable({
            order: [[1, 'asc']],
        })

    $('#roleCustomSearchBox').keyup(function(){  
        roleTable.search($(this).val()).draw();   // this  is for customized searchbox with datatable search feature.
    })

    $('#roleTable').on('click', '.info-role', function () {
        window.location.href = $(this).parent('tr').attr('href');
    });
    @endcan

    @can('user-delete')
    /* DeleteUser */
    $('#deleteUser').on('click', function(){
        let user_selected = userTable.column(0).checkboxes.selected();
        if(_.isEmpty(user_selected)){
            $('#user-empty-modal').modal('toggle');
        } else {
            if(user_selected.length == 1){
                $('#user-cancel-btn').prop('hidden', false);
                $('#user-delete-btn').html('Yes, delete it');
                $('#user-delete-btn').prop('disabled', false);
                $('#user-delete-btn').css('background-color', 'var(--iq-danger)');
                $('#user-delete-btn').css('border-color', 'var(--iq-danger)');
                $('#user-confirmation-modal').modal('toggle');
                
            } else {
                $('#user-cancel-multipl-btn').prop('hidden', false);
                $('#user-delete-multipl-btn').html('Yes, delete them');
                $('#user-delete-multipl-btn').prop('disabled', false);
                $('#user-delete-multipl-btn').css('background-color', 'var(--iq-danger)');
                $('#user-delete-multipl-btn').css('border-color', 'var(--iq-danger)');
                $('#user-confirmation-multiple-modal').modal('toggle');
            }
        }
    })

    function confirmDeleteUser(id){
        let cancel_btn = $('#user-cancel-btn');
        let delete_btn = $('#user-delete-btn');
        let modal = $('#user-confirmation-modal');

        if(id != "user-delete-btn"){
            cancel_btn = $('#user-cancel-multiple-btn');
            delete_btn = $('#user-delete-multiple-btn');
            modal = $('#user-confirmation-multiple-modal');
        }
        
        cancel_btn.prop('hidden', true);
        delete_btn.prop('disabled', true);
        delete_btn.html('<i class="fa fa-circle-o-notch fa-spin"></i>Deleting');

        let selected_row = userTable.column(0).checkboxes.selected();

        let users_id = [];
        $.each(selected_row, function(index, value){
            let data = userTable.rows('#user-'+value).data()[0];
            users_id.push(data[0]);
        });
        
        let result = {
            users_id: users_id,
            _token: $('meta[name="csrf-token"]').attr('content')
        };
        
        $.ajax({
            url: '{{ route("users.destroys") }}',
            type: "DELETE",
            data: result,
            success:function(response){
                let errors = response['errors'];
                if($.isEmptyObject(response['success'])){
                    console.log(errors);
                } else {
                    delete_btn.css('background-color', 'var(--iq-success)');
                    delete_btn.css('border-color', 'var(--iq-success)');
                    delete_btn.html('<i class="fa fa-check"></i>Deleted');
                    setTimeout(function() {
                        modal.modal('toggle');
                    }, 500);

                    users_id.forEach(function(item){
                        userTable
                            .rows('#user-'+ item)
                            .remove()
                            .draw();
                    })
				    notyf.success(response['success']);
                }
            },
            error:function(error){
                console.log(error);
            }
        });
    };
    @endcan

    @can('role-delete')
    /* DeleteRole */
    $('#deleteRole').on('click', function(){
        let role_selected = roleTable.column(0).checkboxes.selected();
        if(_.isEmpty(role_selected)){
            $('#role-empty-modal').modal('toggle');
        } else {
            if(role_selected.length == 1){
                $('#role-cancel-btn').prop('hidden', false);
                $('#role-delete-btn').html('Yes, delete it');
                $('#role-delete-btn').prop('disabled', false);
                $('#role-delete-btn').css('background-color', 'var(--iq-danger)');
                $('#role-delete-btn').css('border-color', 'var(--iq-danger)');
                $('#role-confirmation-modal').modal('toggle');
                
            } else {
                $('#role-cancel-multipl-btn').prop('hidden', false);
                $('#role-delete-multipl-btn').html('Yes, delete them');
                $('#role-delete-multipl-btn').prop('disabled', false);
                $('#role-delete-multipl-btn').css('background-color', 'var(--iq-danger)');
                $('#role-delete-multipl-btn').css('border-color', 'var(--iq-danger)');
                $('#role-confirmation-multiple-modal').modal('toggle');
            }
        }
    })

    function confirmDeleteRole(id){
        let cancel_btn = $('#role-cancel-btn');
        let delete_btn = $('#role-delete-btn');
        let modal = $('#role-confirmation-modal');

        if(id != "role-delete-btn"){
            cancel_btn = $('#role-cancel-multiple-btn');
            delete_btn = $('#role-delete-multiple-btn');
            modal = $('#role-confirmation-multiple-modal');
        }
        
        cancel_btn.prop('hidden', true);
        delete_btn.prop('disabled', true);
        delete_btn.html('<i class="fa fa-circle-o-notch fa-spin"></i>Deleting');

        let selected_row = roleTable.column(0).checkboxes.selected();

        let roles_id = [];
        $.each(selected_row, function(index, value){
            let data = roleTable.rows('#role-'+value).data()[0];
            roles_id.push(data[0]);
        });
        
        let result = {
            roles_id: roles_id,
            _token: $('meta[name="csrf-token"]').attr('content')
        };
        
        $.ajax({
            url: '{{ route("roles.destroys") }}',
            type: "DELETE",
            data: result,
            success:function(response){
                let errors = response['errors'];
                if($.isEmptyObject(response['success'])){
                    console.log(errors);
                } else {
                    delete_btn.css('background-color', 'var(--iq-success)');
                    delete_btn.css('border-color', 'var(--iq-success)');
                    delete_btn.html('<i class="fa fa-check"></i>Deleted');
                    setTimeout(function() {
                        modal.modal('toggle');
                    }, 500);

                    roles_id.forEach(function(item){
                        roleTable
                            .rows('#role-'+ item)
                            .remove()
                            .draw();
                    })
				    notyf.success(response['success']);
                }
            },
            error:function(error){
                console.log(error);
            }
        });
    };
    @endcan
</script>
@endsection