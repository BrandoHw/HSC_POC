<!-- Resolve: Empty -->
<div class="modal fade" id="resolve-empty-modal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body" style="margin-left: -15px; margin-right: -15px; margin-top: -15px">
                <div class="container-fluid bd-example-row">
                    <div class="row justify-content-center iq-bg-primary">
                        <i class="ri-error-warning-fill text-primary" style="font-size: 85px; margin: -15px"></i>
                    </div>
                    <div class="row mt-3 justify-content-center mt-2">
                        <div class="h4 font-weight-bold">No alert selected!</div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="">Select at least one alert to mark as resolved.</div>
                    </div>
                    <div class="row mt-5 justify-content-center">
                        <button type="button" class="btn btn-secondary m-1" data-dismiss="modal">Dismiss</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Resolve: Confirmation for 1 -->
<div class="modal fade" id="resolve-confirmation-modal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body" style="margin-left: -15px; margin-right: -15px; margin-top: -15px">
                <div class="container-fluid bd-example-row">
                    <div class="row justify-content-center iq-bg-primary">
                        <i class="ri-checkbox-circle-fill text-primary" style="font-size: 85px; margin: -15px"></i>
                    </div>
                    <div class="row mt-3 justify-content-center mt-2">
                        <div class="h4 font-weight-bold">Mark this alert as resolved?</div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="">You will not be able to undo this action.</div>
                    </div>
                    <div class="row mt-5 justify-content-center">
                        <button type="button" class="btn btn-secondary m-1" id="cancel-btn" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary m-1" id="resolve-btn" onClick="confirmResolveAlert(this.id)">Yes, resolve it.</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Resolve: Confirmation for multiple-->
<div class="modal fade" id="resolve-confirmation-multiple-modal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body" style="margin-left: -15px; margin-right: -15px; margin-top: -15px">
                <div class="container-fluid bd-example-row">
                    <div class="row justify-content-center iq-bg-primary">
                        <i class="ri-checkbox-circle-fill text-primary" style="font-size: 85px; margin: -15px"></i>
                    </div>
                    <div class="row mt-3 justify-content-center mt-2">
                        <div class="h4 font-weight-bold">Mark these alert as resolved?</div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="">You will not be able to undo this action.</div>
                    </div>
                    <div class="row mt-5 justify-content-center">
                        <button type="button" class="btn btn-secondary m-1" id="cancel-multiple-btn" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary m-1" id="resolve-multiple-btn" onClick="confirmResolveAllAlert(this.id)">Yes, resolve them.</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>