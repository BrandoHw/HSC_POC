<div class="modal-content">
    <div class="modal-header">
        <h3 class="modal-title">Are you sure?</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
    </div>
    <div class="modal-body m-3">
        <p class="text-center text-danger">
            Do you really want to delete this role? This process cannot be undone.
        </p>
        <div class="row align-items-center" id="deleteName"></div>
        <div class="row align-items-center" id="deleteColor"></div>    
        <div class="row align-items-top" id="deletePermissions"></div>    
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger deleteRoleBtn" onClick="deleteRole(this.id)">Delete</button>
    </div>
</div>


