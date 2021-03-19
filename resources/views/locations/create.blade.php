<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title">Create <strong>New</strong> Location</h5>
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
                <div class="col-sm-8" id="name-location-form-holder" style = "padding-left: 0">
                    <input type="name" class="form-control" id="name-location-form" placeholder="Enter Location Name" style = "color: black">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-form-label col-sm-3 text-sm-right">
                    Floor
                </label>
                <select id='selFloor-location-form' class="col-sm-8" style="width: 50%"></select>

            </div>
            <div class="form-group row">
                <label class="col-form-label col-sm-3 text-sm-right">
                    Type
                </label>
                <select id='selType-location-form' class="col-sm-8"></select>
            </div>
            
            <!-- Button -->
            <div style="float:right; margin-top:0.5em">
                <button class="btn btn-secondary quitLocationCreate">Cancel</button>
                <button class="btn btn-primary" id="saveLocationBtn" onClick="createLocation()">Save</button>
            </div>
    </div>
</div>
