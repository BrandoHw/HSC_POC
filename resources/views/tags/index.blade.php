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
                            @can('beacon-create')
                            <a class="btn btn-primary" href="{{ route('beacons.create') }}" style="margin-right: 10px"><i class="ri-add-line"></i>Add Beacon</a>
                            @endcan
                            @can('beacon-delete')
                            <a class="btn btn-danger" href="#" id="deleteBeacon">Delete</a>
                            @endcan
                        </div>
                    </div>
                    <div class="table-responsive" style="margin-top: 15px">
                        <table class="table table-stripe table-bordered hover" id="tagTable">
                        <thead>
                                <tr>
                                    <th scope="col" style="width:10%">#</th>
                                    <th scope="col" style="width:35%">Mac Address</th>
                                    <th scope="col" style="width:25%">Type</th>
                                    <th scope="col" style="width:30%">Assigned To</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tags as $tag)
                                    <tr id="beacon-{{ $tag->beacon_id }}" href="{{ route('beacons.edit',$tag->beacon_id) }}">
                                        <td>{{ $tag->beacon_id }}</td>
                                        <td class="info">{{ $tag->beacon_mac }}</td>
                                        <td class="info">
                                            <span class="badge badge-pill iq-bg-{{ ($tag->beacon_type == 1) ? 'primary':'success' }}">
                                                {{ ($tag->beacon_type == 2) ? 'Card':'Wristband' }}
                                            </span>
                                        </td>
                                        <td class="info">
                                            @if(!empty($tag->user))
                                                {{ $tag->user->full_name ?? '-' }}
                                            @else
                                                {{ $tag->resident->full_name ?? '-' }}
                                            @endif
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
    @can('beacon-delete')
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
                            <div class="h4 font-weight-bold">No beacon selected!</div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="">Select at least one beacon to delete.</div>
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
                            <div class="h4 font-weight-bold">Delete this beacon?</div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="">You will not be able to recover it.</div>
                        </div>
                        <div class="row mt-5 justify-content-center">
                            <button type="button" class="btn btn-secondary m-1" id="cancel-btn" data-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-danger m-1" id="delete-btn" onClick="confirmDeleteBeacon(this.id)">Yes, delete it</button>
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
                            <div class="h4 font-weight-bold">Delete these beacons?</div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="">You will not be able to recover them.</div>
                        </div>
                        <div class="row mt-5 justify-content-center">
                            <button type="button" class="btn btn-secondary m-1" id="cancel-multiple-btn" data-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-danger m-1" id="delete-multiple-btn" onClick="confirmDeleteBeacon(this.id)">Yes, delete them</button>
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
    @if($message = Session::get('success'))
        notyf.success(@json($message));
    @endif

    /* Initiate dataTable */
    var dTable = $('#tagTable').DataTable({
            order: [[1, 'asc']],
        })

    $('#myCustomSearchBox').keyup(function(){  
        dTable.search($(this).val()).draw();   // this  is for customized searchbox with datatable search feature.
    })

    $('#tagTable').on('click', '.info', function () {
        window.location.href = $(this).parent('tr').attr('href');
    });

    @can('beacon-delete')
    $('#deleteBeacon').on('click', function(){
        let beacon_selected = dTable.column(0).checkboxes.selected();
        if(_.isEmpty(beacon_selected)){
            $('#empty-modal').modal('toggle');
        } else {
            if(beacon_selected.length == 1){
                $('#cancel-btn').prop('hidden', false);
                $('#delete-btn').html('Yes, delete it');
                $('#delete-btn').prop('disabled', false);
                $('#delete-btn').css('background-color', 'var(--iq-danger)');
                $('#delete-btn').css('border-color', 'var(--iq-danger)');
                $('#confirmation-modal').modal('toggle');
                
            } else {
                $('#cancel-multiple-btn').prop('hidden', false);
                $('#delete-multiple-btn').html('Yes, delete them');
                $('#delete-multiple-btn').prop('disabled', false);
                $('#delete-multiple-btn').css('background-color', 'var(--iq-danger)');
                $('#delete-multiple-btn').css('border-color', 'var(--iq-danger)');
                $('#confirmation-multiple-modal').modal('toggle');
            }
        }
    })

    function confirmDeleteBeacon(id){
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

        let beacons_id = [];
        $.each(selected_row, function(index, value){
            let data = dTable.rows('#beacon-'+value).data()[0];
            beacons_id.push(data[0]);
        });
        
        let result = {
            beacons_id: beacons_id,
            _token: $('meta[name="csrf-token"]').attr('content')
        };
        
        $.ajax({
            url: '{{ route("beacons.destroys") }}',
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

                    beacons_id.forEach(function(item){
                        dTable
                            .rows('#beacon-'+ item)
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