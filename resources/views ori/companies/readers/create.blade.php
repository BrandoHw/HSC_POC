<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title">Assign <strong>New</strong> Reader</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
    </div>
    <div class="modal-body m-3" id="assignModalBody">
        <div id="stepper" class="bs-stepper linear">
            <div class="bs-stepper-header" role="tablist">
                <div class="step active" data-target="#test-l-1">
                <button type="button" class="step-trigger" role="tab" id="steppertrigger1" aria-controls="test-l-1" aria-selected="true">
                    <span class="bs-stepper-circle">1</span>
                    <span class="bs-stepper-label">Select Readers</span>
                </button>
                </div>
                <div class="bs-stepper-line"></div>
                <div class="step" data-target="#test-l-2">
                <button type="button" class="step-trigger" role="tab" id="steppertrigger2" aria-controls="test-l-2" aria-selected="false" disabled="disabled">
                    <span class="bs-stepper-circle">2</span>
                    <span class="bs-stepper-label">Choose Location</span>
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
                    <p class="text-left" id="selectReaderMessage">
                        <span style="color: red; display:block; float:left"> *</span>
                        <em>Please select at least one reader to proceed.</em>
                    </p>
                    <div class="table-responsive" id="readerTableHolder">
                        <table class="table table-striped table-bordered table-hover table-sm" id="readerTable">
                            <thead>
                                <tr>
                                    <th scope="col" style="width:10%"></th>
                                    <th scope="col" >#</th>
                                    <th scope="col" >Name</th>
                                    <th scope="col" >Mac Address</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <button style="float:right" class="btn btn-primary" id="nextBtn-1">Next</button>
                </div>
                <div id="test-l-2" role="tabpanel" class="bs-stepper-pane" aria-labelledby="steppertrigger2">
                    <p class="text-left" id="selectLocationMessage">
                        <span style="color: red; display:block; float:left"> *</span>
                        <em>Please assign location to these readers.</em>
                    </p>
                    <div class="table-responsive" id="selectedReaderTableHolder">
                        <table class="table table-striped table-bordered table-hover table-sm" id="selectedReaderTable">
                            <thead>
                                <tr>
                                    <th scope="col" style="width:5%">#</th>
                                    <th scope="col" style="width:16%">Serial</th>
                                    <th scope="col" style="width:20%">Mac Address</th>
                                    <th scope="col" style="width:27%">Building</th>
                                    <th scope="col" style="width:33%">Floor</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <div style="float:right">
                        <button class="btn btn-secondary" id="previousBtn-2">Back</button>
                        <button type="submit" class="btn btn-primary" id="nextBtn-2">Complete</button>
                    </div>
                </div>
                <div id="test-l-3" role="tabpanel" class="bs-stepper-pane text-center" aria-labelledby="steppertrigger3">
                    <div class="alert alert-success" role="alert">
                        <div class="alert-message">
                            @svg('check-circle', 'feather-check-circle align-middle')
                            <strong>Readers</strong> Added Successfully!
                        </div>
                    </div>
                    <table class="table table-striped table-bordered table-hover table-sm" id="displaySelectedReaderTable">
                        <thead>
                            <tr>
                                <th scope="col" style="width:5%">#</th>
                                <th scope="col" style="width:16%">Serial</th>
                                <th scope="col" style="width:20%">Mac Address</th>
                                <th scope="col" style="width:27%">Building</th>
                                <th scope="col" style="width:33%">Floor</th>
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