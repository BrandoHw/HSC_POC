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
                            <a class="btn btn-primary" href="{{ route('policies.create') }}" style="margin-right: 10px">Create</a>
                            <a class="btn btn-danger" href="#" id="deletePolicy">Delete</a>
                        </div>
                    </div>
                    <div class="table-responsive" style="margin-top: 15px">
                        <table class="table table-stripe table-bordered hover" id="policyTable">
                        <thead>
                                <tr>
                                    <th scope="col" style="width:10%">#</th>
                                    <th scope="col" style="width:35%">Name</th>
                                    <th scope="col" style="width:25%">Type</th>
                                    <th scope="col" style="width:25%">Parameters</th>
                                    <th scope="col" style="width:30%">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($policies as $policy)
                                    <tr id="policy-{{ $policy->rules_id }}"href="{{ route('policies.edit',$policy->rules_id) }}">
                                        <td>{{ $policy->rules_id }}</td>
                                        <td class="info">{{ $policy->description }}</td>
                                        <td class="info">{{ $policy->policyType->rules_type_desc }}</td>
                                        <td class="info">
                                            @switch($policy->policyType->rules_type_id)
                                                @case(1)
                                                    {{ ($policy->attendance) ? 'Present':'Absent' }}
                                                    @break  
                                                @case(2)
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
                                                {{ ($policy->alert_action == 1) ? 'Active':'Inactive' }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table> 
                    </div>
                </div>
            </div>
        </div>
    </div>
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
                            <div class="h4 font-weight-bold">Policy not found!</div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="">Select at laeast one policy to delete.</div>
                        </div>
                        <div class="row mt-5 justify-content-center">
                            <button type="button" class="btn btn-secondary m-1" data-dismiss="modal">Dismiss</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Delete: Confirmation -->
    <div class="modal fade" id="confirmation-modal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
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
                            <div class="">You will not be able to recover it.</div>
                        </div>
                        <div class="row mt-5 justify-content-center">
                            <button type="button" class="btn btn-secondary m-1" id="cancel-btn" data-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-danger m-1" id="delete-btn">Yes, delete it</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 

@section("script")
<script>
    /* Initiate dataTable */
    let dTable = $('#policyTable').DataTable({
        order: [[1, 'asc']],
    })

    $('#myCustomSearchBox').keyup(function(){  
        dTable.search($(this).val()).draw();   // this  is for customized searchbox with datatable search feature.
    })

    $('#policyTable').on('click', '.info', function () {
        window.location.href = $(this).parent('tr').attr('href');
    });

    $('#deletePolicy').on('click', function(){
        let policy_selected = dTable.column(0).checkboxes.selected();
        if(_.isEmpty(policy_selected)){
            $('#empty-modal').modal('toggle');
        } else {
            $('#confirmation-modal').modal('toggle');
        }
    })

    $('#confirmation-modal').on('hidden.bs.modal', function (e) {
        $('#cancel-btn').prop('hidden', false);
        $('#delete-btn').prop('disabled', false);
        $('#delete-btn').html('Yes, delete it');
    })

    $('#delete-btn').on('click', function(){
        console.log('inside deletePolicy');
        $('#cancel-btn').prop('hidden', true);
        $('#delete-btn').prop('disabled', true);
        $('#delete-btn').html('<i class="fa fa-circle-o-notch fa-spin"></i>Deleting');
        
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
            url: '{{ route("policies.destroy-multi") }}',
            type: "DELETE",
            data: result,
            success:function(response){
                let errors = response['errors'];
                if($.isEmptyObject(response['success'])){
                    console.log(errors);
                } else {
                    console.log(response);
                    $('#delete-btn').css('background-color', 'var(--iq-success)');
                    $('#delete-btn').css('border-color', 'var(--iq-success)');
                    $('#delete-btn').html('<i class="fa fa-check"></i>Deleted');
                    setTimeout(function() {
                        $('#confirmation-modal').modal('toggle');
                    }, 500);

                    policies_id.forEach(function(item){
                        dTable
                            .rows('#policy-'+ item)
                            .remove()
                            .draw();
                    })
				    // notyf.success(response['success']);
                }
            },
            error:function(error){
                console.log(error);
            }
        });
    })
</script>
@endsection