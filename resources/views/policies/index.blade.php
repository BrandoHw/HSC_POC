@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="iq-card">
                <div class="iq-card-body">
                    <div class="iq-search-bar row justify-content-between">
                        <form action="#" class="searchbox">
                            <input type="text" id="myCustomSearchBox" class="text search-input" placeholder="Type here to search...">
                            <a class="search-link" href="#"><i class="ri-search-line"></i></a>
                        </form>
                        <div class="col-4 row justify-content-end">
                            @can('policy-create')
                            <a class="btn btn-primary" href="{{ route('policies.create') }}" style="margin-right: 10px"><i class="ri-add-line"></i>Add Policy</a>
                            @endcan
                            @can('policy-delete')
                            <a class="btn btn-danger" href="#" id="deletePolicy">Delete</a>
                            @endcan
                        </div>
                    </div>
                    <div class="table-responsive" style="margin-top: 15px">
                        <table class="table table-stripe table-bordered hover" id="policyTable">
                        <thead>
                                <tr>
                                    <th scope="col" style="width:10%">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Type</th>
                                    <th scope="col">Parameters</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Created</th>
                                    <th scope="col">Updated</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($policies as $policy)
                                    <tr id="policy-{{ $policy->rules_id }}" href="{{ route('policies.edit',$policy->rules_id) }}">
                                        <td>{{ $policy->rules_id }}</td>
                                        <td class="info">{{ $policy->description }}</td>
                                        <td class="info">{{ $policy->policyType->rules_type_desc }}</td>
                                        <td class="info">
                                            @switch($policy->policyType->rules_type_id)
                                                @case(1)
                                                    {{ ($policy->attendance) ? 'Present':'Absent' }}
                                                    @break  
                                                @case(2)
                                                    < {{ $policy->battery_threshold }}%
                                                    @break
                                                @case(3)
                                                @case(4)
                                                    -
                                                    @break  
                                                @case(5)
                                                    {{ ($policy->geofence) ? 'Entering Zone':'Leaving Zone' }}
                                                    @break  
                                                @case(6)
                                                    x-axis: {{ ($policy->x_threshold) ?:'-' }}<br>
                                                    y-axis: {{ ($policy->y_threshold) ?:'-' }}<br>
                                                    z-axis: {{ ($policy->z_threshold) ?:'-' }}
                                                    @break  
                                            @endswitch
                                        </td>
                                        <td class="info">
                                            <span class="badge badge-pill iq-bg-{{ ($policy->alert_action == 1) ? 'success':'secondary' }}">
                                                {{ ($policy->alert_action == 1) ? 'Enabled':'Disabled' }}
                                            </span>
                                        </td>
                                        <td class="info">{{ $policy->created_at_tz }}</td>
                                        <td class="info">{{ $policy->updated_at_tz }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table> 
                    </div>
                </div>
            </div>
        </div>
    </div>
    @can('policy-delete')
    <!-- Delete: Empty -->
    <div class="modal fade" id="empty-modal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body" style="margin-left: -15px; margin-right: -15px; margin-top: -15px">
                    <div class="container-fluid bd-example-row">
                        <div class="row justify-content-center iq-bg-primary">
                            <i class="ri-error-warning-fill text-primary" style="font-size: 85px; margin: -15px"></i>
                        </div>
                        <div class="row mt-3 justify-content-center mt-2">
                            <div class="h4 font-weight-bold">No policy selected!</div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="">Select at least one policy to delete.</div>
                        </div>
                        <div class="row mt-5 justify-content-center">
                            <button type="button" class="btn btn-secondary m-1" data-dismiss="modal">Dismiss</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Delete: Confirmation for 1 -->
    <div class="modal fade" id="confirmation-modal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body" style="margin-left: -15px; margin-right: -15px; margin-top: -15px">
                    <div class="container-fluid bd-example-row">
                        <div class="row justify-content-center iq-bg-danger">
                            <i class="ri-error-warning-fill text-danger" style="font-size: 85px; margin: -15px"></i>
                        </div>
                        <div class="row mt-3 justify-content-center mt-2">
                            <div class="h4 font-weight-bold">Delete this policy?</div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="">You will not be able to recover it.</div>
                        </div>
                        <div class="row mt-5 justify-content-center">
                            <button type="button" class="btn btn-secondary m-1" id="cancel-btn" data-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-danger m-1" id="delete-btn" onClick="confirmDeletePolicy(this.id)">Yes, delete it</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Delete: Confirmation for multiple-->
    <div class="modal fade" id="confirmation-multiple-modal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body" style="margin-left: -15px; margin-right: -15px; margin-top: -15px">
                    <div class="container-fluid bd-example-row">
                        <div class="row justify-content-center iq-bg-danger">
                            <i class="ri-error-warning-fill text-danger" style="font-size: 85px; margin: -15px"></i>
                        </div>
                        <div class="row mt-3 justify-content-center mt-2">
                            <div class="h4 font-weight-bold">Delete these policies?</div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="">You will not be able to recover them.</div>
                        </div>
                        <div class="row mt-5 justify-content-center">
                            <button type="button" class="btn btn-secondary m-1" id="cancel-multiple-btn" data-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-danger m-1" id="delete-multiple-btn" onClick="confirmDeletePolicy(this.id)">Yes, delete them</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endcan
</div>
@endsection 

@section("script")
<script>
    /* Initiate dataTable */
    let dTable = $('#policyTable').DataTable({
        order: [[5, 'asc']],
    })

    $('#myCustomSearchBox').keyup(function(){  
        dTable.search($(this).val()).draw();   // this  is for customized searchbox with datatable search feature.
    })

    $('#policyTable').on('click', '.info', function () {
        window.location.href = $(this).parent('tr').attr('href');
    });

    @can('policy-delete')
    $('#deletePolicy').on('click', function(){
        let policy_selected = dTable.column(0).checkboxes.selected();
        if(_.isEmpty(policy_selected)){
            $('#empty-modal').modal('toggle');
        } else {
            if(policy_selected.length == 1){
                $('#cancel-btn').prop('hidden', false);
                $('#delete-btn').html('Yes, delete it');
                $('#delete-btn').prop('disabled', false);
                $('#delete-btn').css('background-color', 'var(--iq-danger)');
                $('#delete-btn').css('border-color', 'var(--iq-danger)');
                $('#confirmation-modal').modal('toggle');
                
            } else {
                $('#cancel-multipl-btn').prop('hidden', false);
                $('#delete-multipl-btn').html('Yes, delete them');
                $('#delete-multipl-btn').prop('disabled', false);
                $('#delete-multipl-btn').css('background-color', 'var(--iq-danger)');
                $('#delete-multipl-btn').css('border-color', 'var(--iq-danger)');
                $('#confirmation-multiple-modal').modal('toggle');
            }
        }
    })

    function confirmDeletePolicy(id){
        let cancel_btn = $('#cancel-btn');
        let delete_btn = $('#delete-btn');
        let modal = $('#confirmation-modal');

        if(id != "delete-btn"){
            cancel_btn = $('#cancel-multiple-btn');
            delete_btn = $('#delete-multiple-btn');
            modal = $('#confirmation-multiple-modal');
        }
        
        cancel_btn.prop('hidden', true);
        delete_btn.prop('disabled', true);
        delete_btn.html('<i class="fa fa-circle-o-notch fa-spin"></i>Deleting');

        let selected_row = dTable.column(0).checkboxes.selected();

        let policies_id = [];
        $.each(selected_row, function(index, value){
            let data = dTable.rows('#policy-'+value).data()[0];
            policies_id.push(data[0]);
        });
        
        let result = {
            policies_id: policies_id,
            _token: $('meta[name="csrf-token"]').attr('content')
        };
        
        $.ajax({
            url: '{{ route("policies.destroys") }}',
            type: "DELETE",
            data: result,
            success:function(response){
                let errors = response['errors'];
                if($.isEmptyObject(response['success'])){
                    console.log(errors);
                } else {
                    delete_btn.css('background-color', 'var(--iq-success)');
                    delete_btn.css('border-color', 'var(--iq-success)');
                    delete_btn.html('<i class="fa fa-check"></i>Deleted');
                    setTimeout(function() {
                        modal.modal('toggle');
                    }, 500);

                    policies_id.forEach(function(item){
                        dTable
                            .rows('#policy-'+ item)
                            .remove()
                            .draw();
                    })
				    notyf.success(response['success']);
                }
            },
            error:function(error){
                console.log(error);
            }
        });
    };
    @endcan
</script>
@endsection