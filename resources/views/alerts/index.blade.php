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

    table.dataTable tr th.select-checkbox.selected::after {
        content: "✔";
        margin-top: -11px;
        margin-left: -11px;
        text-align: center;
        text-shadow: rgb(176, 190, 217) 1px 1px, rgb(176, 190, 217) -1px -1px, rgb(176, 190, 217) 1px -1px, rgb(176, 190, 217) -1px 1px;
    }
    table.dataTable thead th.select-checkbox:before {
        content: "    ";
        white-space: pre;
        margin-top: -6px;
        border: 1px solid black;
        border-radius: 3px;
        font-size:10px
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="iq-card">
                <div class="iq-card-body">
                    <div class="row align-items-center" style="justify-content: space-between">
                        <div class="col-4">
                            <label class="sr-only">Date</label>
                            <div class="input-group mb-2 mr-sm-2">
                                <div class="input-group-text">Date</div>
                                <input type="text" class="form-control" id="datepicker" kl_vkbd_parsed="true">
                            </div>
                        </div>

                        <a class="btn btn-primary" href="#" style="margin-right: 10px" id="resolveAllAlert">Resolve All</a>
                    </div>
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
                            <a class="btn btn-danger" href="#" id="archiveAlert">Delete</a>
                            @endcan
                        </div>
                    </div>
                    <div class="table-responsive" style="margin-top: 15px">
                        <table class="table table-stripe table-bordered hover" id="alertTable">
                        <thead>
                                <tr>
                                    <th scope="col" style="text-align: center; width:10%" id="checkbox-all"></th>
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
                            </tbody>
                        </table> 
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('alerts.resolve_every')
    @include('alerts.resolve')
    @can('alert-delete')
    @include('alerts.archive')
    @endcan
</div>
@endsection 

@section("script")

<script src="{{ asset('js/mix/moment.js') }}"></script>
<script src="https://cdn.datatables.net/plug-ins/1.11.3/sorting/datetime-moment.js"></script>
<script>
    
    let token = $('meta[name="csrf-token"]').attr('content');
    let last = @json($alerts_last);
    console.log(last);

    $(function(){
        let timer = setInterval(getNewAlerts, 30000);
        $.fn.dataTable.moment( 'DD-MM-YYYY h:mm:ss A' );
        $.fn.dataTable.moment( 'DD-MM-YYYY hh:mm:ss A');
    });
    $('#datepicker').daterangepicker({
            showDropdowns: true,
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            },
            linkedCalendars: false,
            alwaysShowCalendars: true,
            startDate: moment().subtract(6, 'days'),
            endDate: moment(),
            maxDate: moment(),
            }, 
            function(start, end, label) {
                console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
            }
    );
    $('#datepicker').on("change",function(){
            console.log($(this).val());
            dTable.ajax.reload();
    });
    /* Initiate dataTable */

    var dTable = $('#alertTable').DataTable({
        columnDefs: [ {
            orderable: false,
            className: 'select-checkbox',
            targets:  0,
        } ],
        select: {
            style: 'multi-shift',
            selector: 'td:first-child'
        },
        order: [[5, 'desc']],
        processing: true,
        language: {
            loadingRecords: "&nbsp;",
            processing: '<i style="position: fixed; top:50%; left:50%;"class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '
        },
        ajax: {
            url: '{{ route("alert.data") }}',
            data: function(data) {
                data.date_range = $("#datepicker").val();
            }
        },
        columns:[
            {data: null, defaultContent: ''},
            {data: 'policy.policy_type.rules_type_desc'},
            {data: 'policy.description'},
            {data: 'name'},
            {data: 'reader.location.location_description'},
            {data: 'timestamp'},
            {
                data: 'resolved_at',
                render: function(data, type){
                    if (data === null)
                        return '<span class="badge badge-pill iq-bg-danger"> Unresolved </span>';
                    else 
                        return '<span class="badge badge-pill iq-bg-success"> Resolved </span>';
                }
            },
            {data: 'resolved_by'},
            {data: 'resolved_at_tz'},
           
        ],  
        pageLength: 20, 
        orderCellsTop: true,
        fixedHeader: true,
    });

    //Checkbox Header Listener
    dTable.on("click", "th.select-checkbox", function() {
        if ($("th.select-checkbox").hasClass("selected")) {
            dTable.rows().deselect();
            $("th.select-checkbox").removeClass("selected");
        } else {
            dTable.rows({page: 'current'}).select();
            $("th.select-checkbox").addClass("selected");
        }
    }).on("select deselect", function() {
        ("Some selection or deselection going on")
        if (dTable.rows({
                selected: true
            }).count() !== dTable.rows().count()) {
            $("th.select-checkbox").removeClass("selected");
        } else {
            $("th.select-checkbox").addClass("selected");
        }
    });

    $('#myCustomSearchBox').keyup(function(){  
        dTable.search($(this).val()).draw();   // this  is for customized searchbox with datatable search feature.
    })
    


    /* Resolve Alert */
    $('#resolveAlert').on('click', function(){
        //let alert_selected = dTable.column(0).checkboxes.selected();
        let alert_selected = dTable.rows( { selected: true } ).data();
        // var filteredRows = dTable.rows({page: 'current'});
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
                        dTable.ajax.reload();
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

        //let selected_row = dTable.column(0).checkboxes.selected();
        // let alerts_id = [];
        // $.each(selected_row, function(index, value){
        //     let data = dTable.rows('#alert-'+value).data()[0];
        //     alerts_id.push(data[0]);
        // });
        let selected_row = dTable.rows( { selected: true } ).data();
        let alerts_id = [];
        for (let i = 0; i < selected_row.length; i++){
            let data = selected_row[i].alert_id;
            alerts_id.push(data);
        }
        
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
                        notyf.success(response['success']);
                    }
                    dTable.rows().deselect();
                    dTable.ajax.reload();
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
        //let alert_selected = dTable.column(0).checkboxes.selected();
        let alert_selected = dTable.rows( { selected: true } ).data();
        if(_.isEmpty(alert_selected)){
            $('#archive-empty-modal').modal('toggle');
        } else {
            if(alert_selected.length == 1){
                $('#cancel-btn').prop('hidden', false);
                $('#archive-btn').html('Yes, delete it');
                $('#archive-btn').prop('disabled', false);
                $('#archive-btn').css('background-color', 'var(--iq-danger)');
                $('#archive-btn').css('border-color', 'var(--iq-danger)');
                $('#archive-confirmation-modal').modal('toggle');
                
            } else {
                $('#cancel-multiple-btn').prop('hidden', false);
                $('#archive-multiple-btn').html('Yes, delete them');
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

        // let selected_row = dTable.column(0).checkboxes.selected();

        // let alerts_id = [];
        // $.each(selected_row, function(index, value){
        //     let data = dTable.rows('#alert-'+value).data()[0];
        //     alerts_id.push(data[0]);
        // });

        let selected_row = dTable.rows( { selected: true } ).data();
        let alerts_id = [];
        for (let i = 0; i < selected_row.length; i++){
            let data = selected_row[i].alert_id;
            alerts_id.push(data);
        }
        
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
                    archive_btn.html('<i class="fa fa-check"></i>Deleted');
                    setTimeout(function() {
                        modal.modal('toggle');
                    }, 500);

                    dTable.rows().deselect();
                    dTable.ajax.reload();
				    notyf.success(response['success']);
                }
            },
            error:function(error){
                console.log(error);
            }
        });
    };
    @endcan

     /* Resolve All Alerts */
     $('#resolveAllAlert').on('click', function(){
        $('#cancel-all-btn').prop('hidden', false);
        $('#resolve-all-btn').html('Yes, resolve them.');
        $('#resolve-all-btn').prop('disabled', false);
        $('#resolve-all-btn').css('background-color', 'var(--iq-primary)');
        $('#resolve-all-btn').css('border-color', 'var(--iq-primary)');
        $('#resolve-confirmation-all-modal').modal('toggle');
    })

    function confirmResolveAllAlert(){
        let cancel_btn = $('#cancel-all-btn');
        let resolve_btn = $('#resolve-all-btn');
        let modal = $('#resolve-confirmation-all-modal');

        cancel_btn.prop('hidden', true);
        resolve_btn.prop('disabled', true);
        resolve_btn.html('<i class="fa fa-circle-o-notch fa-spin"></i>Resolving');

        let result = {
            user_id: @json(auth()->user()->user_id),
            _token: token
        };

        $.ajax({
            url: '{{ route("alerts.resolve_every") }}',
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
                    notyf.success(response['success']);
                    dTable.rows().deselect();
                    dTable.ajax.reload();
                }
            },
            error:function(error){
                console.log(error);
            }
        });

    }

</script>
@endsection