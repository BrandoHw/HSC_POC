@extends('layouts.app')

@section('style')
<style>
    .special{
        text-align: center;
        font-size: 18px;
        display: inline-block;
        border-radius: 10px;
        width: 40px;
        height: 35px;
    }
    .special:hover{
        color: var(--iq-white) !important;
        background: linear-gradient(to right, var(--iq-primary) 0%, var(--iq-primary-light) 100%);
        border-color: var(--iq-primary);
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="iq-card">
                <div class="iq-card-body">
                    <div class="iq-search-bar row justify-content-between">
                        <div class="iq-search-bar row justify-content-between">
                            <form action="#" class="searchbox">
                                <input type="text" id="myCustomSearchBox" class="text search-input" placeholder="Type here to search...">
                                <a class="search-link" href="#"><i class="ri-search-line"></i></a>
                            </form>
                            <a class="special iq-bg-primary" href="#" data-toggle="tooltip" data-placement="top" 
                                title="Refresh" style="margin-left: 10px" id="refresh-alerts" onClick="getNewAlerts()"><i class="ri-refresh-line mr-0"></i></a>
                        </div>
                        <div class="col-4 row justify-content-end">
                            <a class="btn btn-primary" href="#" style="margin-right: 10px" id="resolveAlert"><i class="ri-check-line"></i>Mark as Resolved</a>
                            @can('alert-delete')
                            <a class="btn btn-danger" href="#" id="archiveAlert">Archive</a>
                            @endcan
                        </div>
                    </div>
                    <div class="table-responsive" style="margin-top: 15px">
                        <table class="table table-stripe table-bordered hover" id="alertTable">
                        <thead>
                                <tr>
                                    <th scope="col" style="width:10%" id="checkbox-all">#</th>
                                    <th scope="col">Policy Type</th>
                                    <th scope="col">Policy Name</th>
                                    <th scope="col">Subject</th>
                                    <th scope="col">Location</th>
                                    <th scope="col">Occurred at</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Resolved by</th>
                                    <th scope="col">Resolved at</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($alerts as $alert)
                                    <tr id="alert-{{ $alert->alert_id }}">
                                        <td id="checkbox-{{ $alert->alert_id }}">{{ $alert->alert_id }}</td>
                                        <td>
                                            {{ $alert->policy->policyType->rules_type_desc }}
                                        </td>
                                        <td>
                                            @if($alert->policy->trashed())
                                                <span class="text-secondary"><em>{{ $alert->policy->description }} <span class="small text-secondary">[deleted]</span></em></span>
                                            @else
                                                {{ $alert->policy->description }}
                                            @endif
                                        </td>
                                        <td>
                                            @if($alert->tag->beacon_type == 1)
                                                {{ $alert->tag->resident->full_name ?? '-' }}
                                            @else
                                                {{ $alert->tag->user->full_name ?? '-' }}
                                            @endif
                                        </td>
                                        <td>{{ $alert->reader->location_full ?? "-" }}</td>
                                        <td>{{ $alert->occured_at_tz }}</td>
                                        <td id="status-{{ $alert->alert_id }}">
                                            <span class="badge badge-pill iq-bg-{{ $alert->resolved_at ? 'success':'danger' }}">
                                                {{ $alert->resolved_at ? 'Resolved':'Unresolved' }}
                                            </span>
                                        </td>
                                        <td id="resolved-by-{{ $alert->alert_id }}">{{ $alert->user->full_name ?? "-" }}</td>
                                        <td id="resolved-at-{{ $alert->alert_id }}">{{ $alert->resolved_at_tz }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table> 
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('alerts.resolve')
    @can('alert-delete')
    @include('alerts.archive')
    @endcan
</div>
@endsection 

@section("script")
<script>
    
    let token = $('meta[name="csrf-token"]').attr('content');
    let last = @json($alerts_last);

    $(function(){
        let timer = setInterval(getNewAlerts, 30000);
    });

    /* Initiate dataTable */
    let dTable = $('#alertTable').DataTable({
        order: [[5, 'desc']],
    })

    $('#myCustomSearchBox').keyup(function(){  
        dTable.search($(this).val()).draw();   // this  is for customized searchbox with datatable search feature.
    })

    /* Resolve Alert */
    $('#resolveAlert').on('click', function(){
        let alert_selected = dTable.column(0).checkboxes.selected();
        if(_.isEmpty(alert_selected)){
            $('#resolve-empty-modal').modal('toggle');
        } else {
            if(alert_selected.length == 1){
                $('#cancel-btn').prop('hidden', false);
                $('#resolve-btn').html('Yes, resolve it.');
                $('#resolve-btn').prop('disabled', false);
                $('#resolve-btn').css('background-color', 'var(--iq-primary)');
                $('#resolve-btn').css('border-color', 'var(--iq-primary)');
                $('#resolve-confirmation-modal').modal('toggle');
                
            } else {
                $('#cancel-multiple-btn').prop('hidden', false);
                $('#resolve-multiple-btn').html('Yes, resolve them.');
                $('#resolve-multiple-btn').prop('disabled', false);
                $('#resolve-multiple-btn').css('background-color', 'var(--iq-primary)');
                $('#resolve-multiple-btn').css('border-color', 'var(--iq-primary)');
                $('#resolve-confirmation-multiple-modal').modal('toggle');
            }
        }
    })

    function getNewAlerts(){
        let refresh_btn = $('#refresh-alerts');
        refresh_btn.html('<i class="fa fa-custom fa-circle-o-notch fa-spin mr-0"></i>');
        refresh_btn.prop('disabled', true);

        let result = {
            last_id: last,
            _token: $('meta[name="csrf-token"]').attr('content')
        };

        $.ajax({
            url: '{{ route("alerts.new_table") }}',
            type: "POST",
            data: result,
            success:function(response){
                let errors = response['errors'];
                if($.isEmptyObject(response['success'])){
                    console.log(errors);
                } else {
                    console.log(response);
                    last = response['last_id'];
                    if(response['alerts_num'] == 0){
                        notyf.open({
                            type: 'warning',
                            message: response['success'],
                            dismissible: false,
                            duration: 4000
                        });
                    } else {
                        let alerts = response['data'];
                        alerts.forEach(function(item){
                            let id = item['id'];
                            dTable.row.add([
                                id,
                                item['policy_type'],
                                item['policy'],
                                item['subject'],
                                item['location'],
                                item['occured_at'],
                                item['status'],
                                item['resolved_by'],
                                item['resolved_at'],
                            ])
                            .node().id = 'alert-' + id;
                            dTable.draw(false);

                            $('#alert-'+id+' td:eq(0)').attr('id', 'checkbox-'+id);
                            $('#alert-'+id+' td:eq(6)').attr('id', 'status-'+id);
                            $('#alert-'+id+' td:eq(7)').attr('id', 'resolved-by-'+id);
                            $('#alert-'+id+' td:eq(8)').attr('id', 'resolved-at-'+id);
                        });
                        notyf.success(response['success']);
                    }
                    
                    refresh_btn.html('<i class="fa fa-custom fa-check mr-0"></i>');
                    setTimeout(function() {
                        refresh_btn.html('<i class="ri-refresh-line mr-0"></i>');
                        refresh_btn.prop('disabled', false);
                    }, 1000);
                    console.log(last);
                }
            },
            error:function(error){
                console.log(error);
            }
        });
    }

    function confirmResolveAlert(id){
        let cancel_btn = $('#cancel-btn');
        let resolve_btn = $('#resolve-btn');
        let modal = $('#resolve-confirmation-modal');

        if(id != "resolve-btn"){
            cancel_btn = $('#cancel-multiple-btn');
            resolve_btn = $('#resolve-multiple-btn');
            modal = $('#resolve-confirmation-multiple-modal');
        }
        
        cancel_btn.prop('hidden', true);
        resolve_btn.prop('disabled', true);
        resolve_btn.html('<i class="fa fa-circle-o-notch fa-spin"></i>Resolving');

        let selected_row = dTable.column(0).checkboxes.selected();

        let alerts_id = [];
        $.each(selected_row, function(index, value){
            let data = dTable.rows('#alert-'+value).data()[0];
            alerts_id.push(data[0]);
        });
        
        let result = {
            alerts_id: alerts_id,
            user_id: @json(auth()->user()->user_id),
            _token: token
        };
        
        $.ajax({
            url: '{{ route("alerts.updates") }}',
            type: "PATCH",
            data: result,
            success:function(response){
                let errors = response['errors'];
                if($.isEmptyObject(response['success'])){
                    console.log(errors);
                } else {
                    resolve_btn.css('background-color', 'var(--iq-success)');
                    resolve_btn.css('border-color', 'var(--iq-success)');
                    resolve_btn.html('<i class="fa fa-check"></i>Resolved.');
                    setTimeout(function() {
                        modal.modal('toggle');
                    }, 500);

                    let found = response['found'];

                    if(!found){
                        notyf.open({
                            type: 'warning',
                            message: response['success'],
                            dismissible: false,
                            duration: 4000
                        });
                    } else {
                        let alerts = response['alerts'];
                        Object.keys(alerts).forEach(function(key) {
                            let item = alerts[key];
                            let item_id = item['alert_id'];
                            $('#alert-' + item_id).removeClass('selected');
                            $('#status-' + item_id).html('<span class="badge badge-pill iq-bg-success">Resolved</span>');
                            $('#resolved-by-' + item_id).html(response['user']);
                            $('#resolved-at-' + item_id).html(response['resolved_at']);
                        });
                        notyf.success(response['success']);
                    }
                    dTable.columns().checkboxes.deselect(true);
                }
            },
            error:function(error){
                console.log(error);
            }
        });
    };

    @can('alert-delete')
    /* Archive Alert */
    $('#archiveAlert').on('click', function(){
        let alert_selected = dTable.column(0).checkboxes.selected();
        if(_.isEmpty(alert_selected)){
            $('#archive-empty-modal').modal('toggle');
        } else {
            if(alert_selected.length == 1){
                $('#cancel-btn').prop('hidden', false);
                $('#archive-btn').html('Yes, archive it');
                $('#archive-btn').prop('disabled', false);
                $('#archive-btn').css('background-color', 'var(--iq-danger)');
                $('#archive-btn').css('border-color', 'var(--iq-danger)');
                $('#archive-confirmation-modal').modal('toggle');
                
            } else {
                $('#cancel-multiple-btn').prop('hidden', false);
                $('#archive-multiple-btn').html('Yes, archive them');
                $('#archive-multiple-btn').prop('disabled', false);
                $('#archive-multiple-btn').css('background-color', 'var(--iq-danger)');
                $('#archive-multiple-btn').css('border-color', 'var(--iq-danger)');
                $('#archive-confirmation-multiple-modal').modal('toggle');
            }
        }
    })

    function confirmArchiveAlert(id){
        let cancel_btn = $('#cancel-btn');
        let archive_btn = $('#archive-btn');
        let modal = $('#archive-confirmation-modal');

        if(id != "archive-btn"){
            cancel_btn = $('#cancel-multiple-btn');
            archive_btn = $('#archive-multiple-btn');
            modal = $('#archive-confirmation-multiple-modal');
        }
        
        cancel_btn.prop('hidden', true);
        archive_btn.prop('disabled', true);
        archive_btn.html('<i class="fa fa-circle-o-notch fa-spin"></i>Archiving');

        let selected_row = dTable.column(0).checkboxes.selected();

        let alerts_id = [];
        $.each(selected_row, function(index, value){
            let data = dTable.rows('#alert-'+value).data()[0];
            alerts_id.push(data[0]);
        });
        
        let result = {
            alerts_id: alerts_id,
            _token: token
        };
        
        $.ajax({
            url: '{{ route("alerts.destroys") }}',
            type: "DELETE",
            data: result,
            success:function(response){
                let errors = response['errors'];
                if($.isEmptyObject(response['success'])){
                    console.log(errors);
                } else {
                    archive_btn.css('background-color', 'var(--iq-success)');
                    archive_btn.css('border-color', 'var(--iq-success)');
                    archive_btn.html('<i class="fa fa-check"></i>Archived');
                    setTimeout(function() {
                        modal.modal('toggle');
                    }, 500);

                    alerts_id.forEach(function(item){
                        dTable
                            .rows('#alert-'+ item)
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