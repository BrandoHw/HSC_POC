<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title">Create <strong>New</strong> Client</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
    </div>
    <div class="modal-body m-3" id="createModalBody">
        <div id="stepper" class="bs-stepper linear">
            <div class="bs-stepper-header" role="tablist">
                <div class="step active" data-target="#test-l-1">
                <button type="button" class="step-trigger" role="tab" id="steppertrigger1" aria-controls="test-l-1" aria-selected="true">
                    <span class="bs-stepper-circle">1</span>
                    <span class="bs-stepper-label">Basic Information</span>
                </button>
                </div>
                <div class="bs-stepper-line"></div>
                <div class="step" data-target="#test-l-2">
                <button type="button" class="step-trigger" role="tab" id="steppertrigger2" aria-controls="test-l-2" aria-selected="false" disabled="disabled">
                    <span class="bs-stepper-circle">2</span>
                    <span class="bs-stepper-label">Add Building</span>
                </button>
                </div>
                <div class="bs-stepper-line"></div>
                <div class="step" data-target="#test-l-3">
                <button type="button" class="step-trigger" role="tab" id="steppertrigger3" aria-controls="test-l-3" aria-selected="false" disabled="disabled">
                    <span class="bs-stepper-circle">3</span>
                    <span class="bs-stepper-label">Done</span>
                </button>
                </div>
            </div>
            <div class="bs-stepper-content">
                <div id="test-l-1" role="tabpanel" class="bs-stepper-pane active dstepper-block" aria-labelledby="steppertrigger1">
                    <div class="form-group row">
                        <label class="col-form-label col-sm-3 text-sm-right">
                            Name
                            <span style="color: red; display:block; float:right"> *</span>
                        </label>
                        <div class="col-sm-8" id="createNameField">
                            {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control', 'id' => 'companyName')) !!}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-sm-3 text-sm-right">
                            Detail
                        </label>
                        <div class="col-sm-8" id="createDetailField">
                            {!! Form::textarea('detail', null, array('placeholder' => 'Detail','class' => 'form-control', 'id' => 'companyDetail')) !!}
                        </div>
                    </div>
                    <button style="float:right" class="btn btn-primary" id="nextBtn-1">Next</button>
                </div>
                <div id="test-l-2" role="tabpanel" class="bs-stepper-pane" aria-labelledby="steppertrigger2">
                    <div class="collapse show" id="buildingTable">
                        <p class="text-left" id="addBuildingMessage">
                            <span style="color: red; display:block; float:left"> *</span>
                            <em>Please add at least one building.</em>
                        </p>
                        <div class="table-responsive" id="companyBuildingTableHolder">
                            <table class="table table-striped table-hover table-sm" id="companyBuildingTable">
                                <thead>
                                    <tr>
                                        <th scope="col" style="width:5%">#</th>
                                        <th scope="col" style="width:25%">Name</th>
                                        <th scope="col" style="width:40%">Address</th>
                                        <th scope="col" style="width:20%">Floor Number</th>
                                        <th scope="col" style="width:10%" class="noSort">Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <div style="float:right">
                            <button class="btn btn-secondary" id="previousBtn-2">Back</button>
                            <button type="submit" class="btn btn-primary" id="nextBtn-2">Complete</button>
                        </div>
                    </div>
                    <div class="collapse" id="buildingForm">
                        <div class="row mb-2 mb-xl-3 justify-content-start">
                            <a href="#" style="padding-left: 12px" class="quitBuildingCreate">
                                @svg('chevron-left', 'feather-chevron-left align-middle')  
                            </a>
                            <h3 style="padding-left: 12px">Create<strong> New </strong>Building Form</h4>
                        </div>
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
                        
                        <!-- Button -->
                        <div style="float:right; margin-top:0.5em">
                            <button class="btn btn-secondary quitBuildingCreate">Cancel</button>
                            <button class="btn btn-primary" id="saveBuildingBtn" onClick="createBuilding()">Save</button>
                        </div>
                    </div>
                    
                </div>
                <div id="test-l-3" role="tabpanel" class="bs-stepper-pane text-center" aria-labelledby="steppertrigger3">
                    <div class="alert alert-success" role="alert">
                        <div class="alert-message" id="completeMessage">
                            @svg('check-circle', 'feather-check-circle align-middle')
                            <strong>s</strong> Added Successfully!
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-sm-3 text-sm-right">
                            Name
                            <span style="color: red; display:block; float:right"> *</span>
                        </label>
                        <div class="col-sm-8" id="createBuildingNameField">
                            {!! Form::text('name', null, array('placeholder' => 'Name','class' => "form-control", 'id' => 'displayName', 'readonly')) !!}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-sm-3 text-sm-right">
                            Detail
                        </label>
                        <div class="col-sm-8" id="createBuildingDetailField">
                            {!! Form::textarea('detail', null, array('placeholder' => 'Detail','class' => "form-control", 'id' => 'displayDetail', 'readonly')) !!}
                        </div>
                    </div>
                    <table class="table table-striped table-bordered table-hover table-sm" id="displayCompanyBuildingTable">
                        <thead>
                            <tr>
                                <th scope="col" style="width:5%">#</th>
                                <th scope="col" style="width:30%">Name</th>
                                <th scope="col" style="width:40%">Address</th>
                                <th scope="col" style="width:25%">Floor Number</th>
                            </tr>
                        </thead>
                    </table>
                    <div style="float:right">
                        <button class="btn btn-secondary mt-5" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>