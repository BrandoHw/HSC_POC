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
                            <a class="btn btn-primary" href="{{ route('gateways.create') }}" style="margin-right: 10px;"><i class="ri-add-line"></i>Add Gateway</a>
                            <a class="btn btn-danger" href="#" id="deleteGateway">Delete</a>
                        </div>
                    </div>
                    <div class="table-responsive" style="margin-top: 15px">
                        <table class="table table-stripe table-bordered hover" id="readerTable">
                        <thead>
                                <tr>
                                    <th scope="col" style="width:5%">#</th>
                                    <th scope="col">Serial Number</th>
                                    <th scope="col">Mac Address</th>
                                    <th scope="col">Location</th>
                                    <th scope="col">Floor</th>
                                    <th scope="col">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($readers as $reader)
                                    <tr id="gateway-{{ $reader->gateway_id }}" href="{{ route('gateways.edit',$reader->gateway_id) }}">
                                        <td>{{ $reader->gateway_id }}</td>
                                        <td>{{ isset($reader->serial) ? $reader->serial : "N/A" }} </td>
                                        <td>{{ $reader->mac_addr }}</td>
                                        <td>{{ isset($reader->location) ? $reader->location->location_description : "-"  }}</td>
                                        <td>{{ isset($reader->location) ? $reader->location->floor_level->alias : "-"  }}</td>
                                        <td>{{ isset($reader->reader_status) ? $reader->reader_status : "-"  }}</td>
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
                            <div class="h4 font-weight-bold">No gateway selected!</div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="">Select at least one gateway to delete.</div>
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
                            <div class="h4 font-weight-bold">Delete this gateway?</div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="">You will not be able to recover it.</div>
                        </div>
                        <div class="row mt-5 justify-content-center">
                            <button type="button" class="btn btn-secondary m-1" id="cancel-btn" data-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-danger m-1" id="delete-btn" onClick="confirmDeleteGateway(this.id)">Yes, delete it</button>
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
                            <div class="h4 font-weight-bold">Delete these gateways?</div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="">You will not be able to recover them.</div>
                        </div>
                        <div class="row mt-5 justify-content-center">
                            <button type="button" class="btn btn-secondary m-1" id="cancel-multiple-btn" data-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-danger m-1" id="delete-multiple-btn" onClick="confirmDeleteGateway(this.id)">Yes, delete them</button>
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
    @if ($message = Session::get('success'))
        notyf.success(@json($message));
    @endif

    /* Initiate dataTable */
    var dTable = $('#readerTable').DataTable({
            order: [[1, 'asc']],
        })

    $('#myCustomSearchBox').keyup(function(){  
        dTable.search($(this).val()).draw();   // this  is for customized searchbox with datatable search feature.
    })

    $('#readerTable tbody tr td:not(:first-child)').click(function () {
        window.location.href = $(this).parent('tr').attr('href');
    });

    $('#deleteGateway').on('click', function(){
        let gateway_selected = dTable.column(0).checkboxes.selected();
        if(_.isEmpty(gateway_selected)){
            $('#empty-modal').modal('toggle');
        } else {
            if(gateway_selected.length == 1){
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

    function confirmDeleteGateway(id){
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

        let gateways_id = [];
        $.each(selected_row, function(index, value){
            let data = dTable.rows('#gateway-'+value).data()[0];
            gateways_id.push(data[0]);
        });
        
        let result = {
            gateways_id: gateways_id,
            _token: $('meta[name="csrf-token"]').attr('content')
        };
        
        $.ajax({
            url: '{{ route("gateways.destroys") }}',
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

                    gateways_id.forEach(function(item){
                        dTable
                            .rows('#gateway-'+ item)
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
</script>
@endsection