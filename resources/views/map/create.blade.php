<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title">Create <strong>New</strong> Zone</h5>
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
                    <span class="bs-stepper-label">Select Gateway</span>
                </button>
                </div>
                <div class="bs-stepper-line"></div>
                <div class="step" data-target="#test-l-2">
                <button type="button" class="step-trigger" role="tab" id="steppertrigger2" aria-controls="test-l-2" aria-selected="false" disabled="disabled">
                    <span class="bs-stepper-circle">2</span>
                    <span class="bs-stepper-label">Assign Location</span>
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
                    <p class="text-left" id="selectGatewayMessage">
                        <span style="color: red; display:block; float:left"> *</span>
                        <em>Please select a gateway to proceed.</em>
                    </p>
                    <div class="form-group row" id="selReaderHolder">
                        <label class="col-form-label col-sm-3 text-sm-right" for="selReader">Mac Address</label>
                        <select id='selReader' class="col-sm-8"  style='height: 200px; width: 295px;'></select>
                    </div>
                    <button style="float:right" class="btn btn-primary" id="nextBtn-1">Next</button>
                </div>
                <div id="test-l-2" role="tabpanel" class="bs-stepper-pane" aria-labelledby="steppertrigger2">
                    <div class="collapse show" id="locationTableCollapsible">
                        <p class="text-left" id="addLocationMessage">
                            <span style="color: red; display:block; float:left"> *</span>
                            <em>Please select a single location.</em>
                        </p>
                        <div class="table-responsive" id="locationTableHolder">
                            <table class="table table-striped table-hover table-sm" style="width:100%" id="locationTable">
                                <thead>
                                    <tr>
                                        <th scope="col" style="width:5%">#</th>
                                        <th scope="col" style="width:40%">Name</th>
                                        <th scope="col" style="width:35%">Floor</th>
                                        <th scope="col" style="width:20%">Location Type</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <div style="float:right">
                            <button class="btn btn-secondary" id="previousBtn-2">Back</button>
                            <button type="submit" class="btn btn-primary" id="nextBtn-2">Complete</button>
                        </div>
                    </div>
                    <div class="collapse" id="locationForm">
                        <div class="row mb-2 mb-xl-3 justify-content-start">
                            <a href="#" style="padding-left: 12px" class="quitLocationCreate">
                                @svg('chevron-left', 'feather-chevron-left align-middle')  
                            </a>
                            <h3 style="padding-left: 12px">Create<strong> New </strong>Location Form</h4>
                        </div>
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
                <div id="test-l-3" role="tabpanel" class="bs-stepper-pane text-center" aria-labelledby="steppertrigger3">
                    <div class="alert alert-success" role="alert">
                        <div class="alert-message" id="completeMessage">
                            @svg('check-circle', 'feather-check-circle align-middle')
                            <strong>Zone</strong> Added Successfully!
                        </div>
                    </div>
                    <div style="float:right">
                        <button class="btn btn-secondary mt-5" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>