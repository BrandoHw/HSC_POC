<div class="modal-content">
    <div class="modal-header" id="editModalHeader"></div>
    <div class="modal-body m-3" id="editModalBody">
        <div class="form-group row">
            <label class="col-form-label col-sm-3 text-sm-right">
                Name
                <span style="color: red; display:block; float:right"> *</span>
            </label>
            <div class="col-sm-8" id="editNameField">
                {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control', 'id'=>'editName')) !!}
            </div>
        </div>
        <div class="form-group row">
            <label class="col-form-label col-sm-3 text-sm-right">
                Username
                <span style="color: red; display:block; float:right"> *</span>
            </label>
            <div class="col-sm-8"  id="editUsernameField">
                {!! Form::text('username', null, array('placeholder' => 'Username','class' => 'form-control', 'id'=>'editUsername')) !!}
            </div>
        </div>
        <div class="form-group row">
            <label class="col-form-label col-sm-3 text-sm-right">
                Email
                <span style="color: red; display:block; float:right"> *</span>
            </label>
            <div class="col-sm-8" id="editEmailField">
                {!! Form::text('email', null, array('placeholder' => 'Email','class' => 'form-control', 'id'=>'editEmail')) !!}
            </div>
        </div>
        <div class="form-group row align-items-center">
            <label class="col-form-label col-sm-3 text-sm-right">Password</label>
            <div class="col-sm-8"  id="editPasswordField">
                {!! Form::password('password', array('placeholder' => 'Password','class' => 'form-control', 'id'=>'editPassword')) !!}
            </div>
            <a href="#" data-toggle="tooltip" data-placement="bottom" title="Password must be more than 6 characters." style="cursor: pointer; left-padding:0">
                @svg('info', 'feather-info align-middle')
            </a>
        </div>
        <div class="form-group row align-items-center">
            <label class="col-form-label required col-sm-3 text-sm-right">
                Confirm Password
                
            </label>
            <div class="col-sm-8" id="editConfirmPasswordField">
                {!! Form::password('confirmPassword', array('placeholder' => 'Confirm Password','class' => 'form-control', 'id'=>'editConfirmPassword')) !!}
            </div>
        </div>
        <div class="form-group row">
            <label class="col-form-label col-sm-3 text-sm-right">
                Role
                <span style="color: red; display:block; float:right"> *</span>
            </label>
            <div class="col-sm-8">
                {!! Form::select('role_id[]', $roles, [], array('class' => 'form-control','multiple', 'id' => 'editRoleId')) !!}
            </div>
        </div>
    </div>
        
    <div class="modal-footer">
            @can('user-delete')
                <a href="#" class="mr-auto text-danger deleteUserBtn" data-dismiss="modal" onClick="getDeleteUserInfo(this.id)">Delete profile</a>
            @endcan
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary updateUserBtn" onClick="updateUser(this.id)">Save</button>
    </div>
</div>
