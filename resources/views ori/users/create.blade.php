<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title">Create <strong>New</strong> User</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
    </div>
    <div class="modal-body m-3" id="createModalBody">
        <div class="form-group row">
            <label class="col-form-label col-sm-3 text-sm-right">
                Name
                <span style="color: red; display:block; float:right"> *</span>
            </label>
            <div class="col-sm-8" id="createNameField">
                {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control', 'id' => 'createName')) !!}
            </div>
        </div>
        <div class="form-group row">
            <label class="col-form-label col-sm-3 text-sm-right">
                Username
                <span style="color: red; display:block; float:right"> *</span>
            </label>
            <div class="col-sm-8" id="createUsernameField">
                {!! Form::text('username', null, array('placeholder' => 'Username','class' => 'form-control', 'id' => 'createUsername')) !!}
            </div>
        </div>
        <div class="form-group row">
            <label class="col-form-label col-sm-3 text-sm-right">
                Email
                <span style="color: red; display:block; float:right"> *</span>
            </label>
            <div class="col-sm-8" id="createEmailField">
                {!! Form::text('email', null, array('placeholder' => 'Email','class' => 'form-control', 'id' => 'createEmail')) !!}
            </div>
        </div>
        <div class="form-group row align-items-center">
            <label class="col-form-label col-sm-3 text-sm-right">
                Password
                <span style="color: red; display:block; float:right"> *</span>
            </label>
            <div class="col-sm-8" id="createPasswordField">
                {!! Form::password('password', array('placeholder' => 'Password','class' => 'form-control', 'id' => 'createPassword')) !!}
            </div>
            <a href="#" data-toggle="tooltip" data-placement="bottom" title="Password must be more than 6 characters." style="cursor: pointer; left-padding:0">
                @svg('info', 'feather-info align-middle')
            </a>
        </div>
        <div class="form-group row align-items-center">
            <label class="col-form-label col-sm-3 text-sm-right">
                Confirm Password
                <span style="color: red; display:block; float:right"> *</span>
            </label>
            <div class="col-sm-8" id="createConfirmPasswordField">
                {!! Form::password('confirm-password', array('placeholder' => 'Confirm Password','class' => 'form-control', 'id' => 'createConfirmPassword')) !!}
            </div>
        </div>
        <div class="form-group row">
        <label class="col-form-label col-sm-3 text-sm-right">
            Role
            <span style="color: red; display:block; float:right"> *</span>
        </label>
        <div class="col-sm-8">
                {!! Form::select('role_id[]', $roles, '1', array('placeholder' => 'Please select a role...', 'class' => 'form-control', 'id' => 'createRoleId')) !!}
            </div>
        </div>      
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary createUserBtn" onClick="createUser()">Create</button>
    </div>
</div>


