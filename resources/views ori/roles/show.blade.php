<div class="modal-content">
    <div class="modal-header" id="showModalHeader"></div>
    <div class="modal-body m-3">
        <div class="row align-items-center" id="showName"></div>
        <div class="row align-items-center" id="showColor"></div>    
        <div class="row align-items-top" id="showPermissions"></div>    
    </div>
    <div class="modal-footer" id="showFooter">
        @can('role-delete')
            <a href="#" class="mr-auto text-danger deleteRoleBtn" data-dismiss="modal" onClick="getDeleteRoleInfo(this.id)">Delete profile</a>
        @endcan
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        @can('role-edit')
            <button type="button" class="btn btn-primary editRoleBtn" data-dismiss="modal" onClick="getEditRoleInfo(this.id)">Edit role</button>
        @endcan
    </div>
</div>


