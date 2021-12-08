<!-- Resolve: Confirmation for all alerts-->
<div class="modal fade" id="resolve-confirmation-all-modal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body" style="margin-left: -15px; margin-right: -15px; margin-top: -15px">
                <div class="container-fluid bd-example-row">
                    <div class="row justify-content-center iq-bg-primary">
                        <i class="ri-checkbox-circle-fill text-primary" style="font-size: 85px; margin: -15px"></i>
                    </div>
                    <div class="row mt-3 justify-content-center mt-2">
                        <div class="h4 font-weight-bold">Resolve all unresolved alerts?</div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="">You will not be able to undo this action.</div>
                    </div>
                    <div class="row mt-5 justify-content-center">
                        <button type="button" class="btn btn-secondary m-1" id="cancel-all-btn" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary m-1" id="resolve-all-btn" onClick="confirmResolveAllAlert(this.id)">Yes, resolve them.</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>