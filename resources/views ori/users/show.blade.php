<div class="modal-content">
    <div class="modal-header" id="showModalHeader"></div>
    <div class="modal-body m-3">
        <div class="row align-items-center" id="showName"></div>
        <div class="row align-items-center" id="showUsername"></div>
        <div class="row align-items-center" id="showEmail"></div>
        <div class="row align-items-center" id="showTagId"></div>
        <div class="row align-items-center" id="showRoleId"></div>      
    </div>
    <div class="modal-footer" id="showFooter">
        @can('user-delete')
            <a href="#" class="mr-auto text-danger deleteUserBtn" data-dismiss="modal" onClick="getDeleteUserInfo(this.id)">Delete profile</a>
        @endcan
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        @can('user-edit')
            <button type="button" class="btn btn-primary editUserBtn" data-dismiss="modal" onClick="getEditUserInfo(this.id)">Edit profile</button>
        @endcan
    </div>
</div>


