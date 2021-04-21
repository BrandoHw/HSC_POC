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
                            <li class="nav-item">
                                <a class="nav-link" id="user-setting" data-toggle="tab" href="#manage-user" role="tab"  aria-selected="false">Manage Staffs</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="role-setting" data-toggle="tab" href="#manage-role" role="tab"  aria-selected="false">Roles & Permissions</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent-3">
                            <div class="tab-pane fade show active" id="manage-profile" role="tabpanel" aria-labelledby="profile-setting">
                            <div class="iq-card">
                                 <div class="iq-card-header d-flex justify-content-between">
                                    <div class="iq-header-title">
                                       <h4 class="card-title">Personal Information</h4>
                                    </div>
                                 </div>
                                 <div class="iq-card-body">
                                    {!! Form::model($user, ['method' => 'PATCH', 'route' => ['users.update', $user->user_id]]) !!}
                                        <div class=" row align-items-center">
                                            <div class="form-group col-sm-6">
                                                <label for="fname">First Name:</label>
                                                {!! Form::text('fName', null, array('class' => "form-control", 'id' => 'editFName')) !!}
                                                @error('fName')
                                                    <div class="alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <label for="lname">Last Name:</label>
                                                {!! Form::text('lName', null, array('class' => "form-control", 'id' => 'editLName')) !!}
                                                @error('lName')
                                                    <div class="alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <label for="uname">Phone Number:</label>
                                                {!! Form::text('phone_number', null, array('class' => "form-control", 'id' => 'editPhoneNum')) !!}
                                                @error('phone_number')
                                                    <div class="alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <label for="editTag">Role:</label>
                                                {!! Form::select('user_type', $userTypes, $user->userType->user_type_id, ['placeholder' => 'Please select...', 'class' => 'form-control form-control', 'id' => 'editUserType']) !!}
                                                @error('user_type')
                                                    <div class="alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <hr>
                                        <div class=" row align-items-center">
                                            <div class="form-group col-sm-6">
                                                <label for="uname">Username:</label>
                                                {!! Form::text('username', null, array('class' => "form-control", 'id' => 'editUsername')) !!}
                                                @error('username')
                                                    <div class="alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <label for="cname">Password: </label>
                                                <a href="#" data-toggle="tooltip" data-placement="right" title="Leave blank if do not wish to change password..." style="cursor: pointer; left-padding:0">
                                                    <i class="ri-information-fill"></i>
                                                </a>
                                                {!! Form::password('password', array('class' => 'form-control', 'id'=>'editPassword')) !!}
                                                @error('password')
                                                    <div class="alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <label for="editTag">Card:</label>
                                                {!! Form::select('beacon_id', $tagsNull, null, ['placeholder' => 'Please select...', 'class' => 'form-control form-control', 'id' => 'editTag']) !!}
                                                @error('beacon_id')
                                                    <div class="alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <label for="cname">Permission: </label>
                                                <a href="#" data-toggle="tooltip" data-placement="right" title="Cannot edit permission yourself" style="cursor: pointer; left-padding:0">
                                                <i class="ri-information-fill"></i>
                                                </a>
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="user_right" disabled {{ ($user->right_id == '1') ? 'checked':'' }}>
                                                    <label class="custom-control-label" for="user_right">Administrator</label>
                                                </div>
                                            </div>
                                       </div>
                                       <div class="text-right">
                                            <button type="submit" class="btn btn-primary" disabled>Save</button>
                                            <a href="{{ route('beacons.index') }}" class="btn iq-bg-danger">Cancel</a>
                                        </div>
                                    {!! Form::close() !!}
                                 </div>
                              </div>
                            </div>
                            <div class="tab-pane fade" id="manage-user" role="tabpanel" aria-labelledby="user-setting">
                                <div class="row">   
                                    <div class="col-sm-12">
                                        <div class="iq-card">
                                            <div class="iq-card-body">
                                                <div class="iq-search-bar row justify-content-between">
                                                    <form action="#" class="searchbox">
                                                        <input type="text" id="userCustomSearchBox" class="text search-input" placeholder="Type here to search...">
                                                        <a class="search-link" href="#"><i class="ri-search-line"></i></a>
                                                    </form>
                                                    <div class="col-4 row justify-content-end">
                                                        <a class="btn btn-primary" href="{{ route('users.create') }}" style="margin-right: 10px;">Add Member</a>
                                                        <a class="btn btn-danger" href="#" style="opacity:.65" disabled >Delete</a>
                                                    </div>
                                                </div>
                                                <div class="table-responsive" style="margin-top: 15px">
                                                    <table class="table table-stripe table-bordered hover" id="userTable">
                                                    <thead>
                                                            <tr>
                                                                <th scope="col" style="width:10%">#</th>
                                                                <th scope="col">First Name</th>
                                                                <th scope="col">Last Name</th>
                                                                <th scope="col">Permission</th>
                                                                <th scope="col">Card</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($users as $user)
                                                                <tr href="{{ route('users.edit',$user->user_id) }}">
                                                                    <td>{{ $user->user_id }}</td>
                                                                    <td>{{ $user->fName }}</td>
                                                                    <td>{{ $user->lName }}</td>
                                                                    <td>{{ $user->userRight->description }}</td>
                                                                    <td>{{ $user->tag->beacon_mac ?? "-" }}</td>
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
                            <div class="tab-pane fade" id="manage-role" role="tabpanel" aria-labelledby="role-setting">
                                <div class="row">   
                                    <div class="col-sm-12">
                                        <div class="iq-card">
                                            <div class="iq-card-body">
                                                <div class="iq-search-bar row justify-content-between">
                                                    <form action="#" class="searchbox">
                                                        <input type="text" id="roleCustomSearchBox" class="text search-input" placeholder="Type here to search...">
                                                        <a class="search-link" href="#"><i class="ri-search-line"></i></a>
                                                    </form>
                                                    <div class="col-4 row justify-content-end">
                                                        <a class="btn btn-primary" href="{{ route('roles.create') }}" style="margin-right: 10px;">Add Role</a>
                                                        <a class="btn btn-danger" href="#" style="opacity:.65" disabled >Delete</a>
                                                    </div>
                                                </div>
                                                <div class="table-responsive" style="margin-top: 15px">
                                                    <table class="table table-stripe table-bordered hover" id="roleTable">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col" style="width:10%">#</th>
                                                                <th scope="col">Name</th>
                                                                <th scope="col">Color</th>
                                                                <th scope="col">Permissions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($roles as $role)
                                                                <tr href="{{ route('roles.edit',$role->id) }}">
                                                                    <td>{{ $role->id }}</td>
                                                                    <td>{{ $role->name }}</td>
                                                                    <td><span class="badge badge-dark" style="background-color: {{ $role->color }} !important">{{ Str::upper($role->color) }}</span></td>
                                                                    <td>
                                                                        @if(!empty($rolePermissions))
                                                                            {{ $rolePermissions->where("role_id", $role->id)->count() }}
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
                            </div>
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
    /* Initiate user dataTable */
    var userTable = $('#userTable').DataTable({
            order: [[1, 'asc']],
        })

    $('#userCustomSearchBox').keyup(function(){  
        userTable.search($(this).val()).draw();   // this  is for customized searchbox with datatable search feature.
    })

    $('#userTable tbody tr td:not(:first-child)').click(function () {
        window.location.href = $(this).parent('tr').attr('href');
    });

    /* Initiate role dataTable */
    var roleTable = $('#roleTable').DataTable({
            order: [[1, 'asc']],
        })

    $('#roleCustomSearchBox').keyup(function(){  
        roleTable.search($(this).val()).draw();   // this  is for customized searchbox with datatable search feature.
    })

    $('#roleTable tbody tr td:not(:first-child)').click(function () {
        window.location.href = $(this).parent('tr').attr('href');
    });
</script>
@endsection