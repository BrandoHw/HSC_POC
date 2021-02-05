<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title">Create <strong>New</strong> Building</h5>
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
			<div class="col-sm-8" id="createBuildingNameField">
				{!! Form::text('name', null, array('placeholder' => 'Name','class' => "form-control", 'id' => 'name')) !!}
			</div>
		</div>
		<div class="form-group row">
			<label class="col-form-label col-sm-3 text-sm-right">
				Detail
			</label>
			<div class="col-sm-8" id="createBuildingDetailField">
				{!! Form::textarea('detail', null, array('placeholder' => 'Detail','class' => "form-control", 'id' => 'detail')) !!}
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label class="col-form-label col-sm-3 text-sm-right">
				Total Floor Number
				<span style="color: red; display:block; float:right"> *</span>
			</label>
			<div class="col-sm-8" id="createBuildingFloorField">
				{!! Form::text('floor_num', null, array('placeholder' => '1 - 100','class' => "form-control", 'id' => 'floor_num')) !!}
			</div>
		</div>
		<div class="form-group row">
			<label class="col-form-label col-sm-3 text-sm-right">
				Address
				<span style="color: red; display:block; float:right"> *</span>
			</label>
			<div class="col-sm-8" id="createBuildingAddressField">
				<input id="address"
						placeholder="Type to search address..."
						onFocus="geolocate()"
						type="text"
						name="address"
						class="form-control">

				<!-- //Hidden fields where the lattitude and longitude is saved -->
				<input type="hidden" name="lat" id="address_latitude" value="0" />
				<input type="hidden" name="lng" id="address_longitude" value="0" />
			</div>
		</div>
		<!-- Displays the map -->
		<div class="form-row">
			<div id="map" style="width:100%;height:400px;"></div>
		</div>  
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary createBuildingBtn" onClick="createBuilding()">Create</button>
    </div>
</div>