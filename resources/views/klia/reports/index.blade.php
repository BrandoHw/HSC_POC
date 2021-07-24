@extends('layouts.app')

@section('content')
<script src="{{ asset('js/mix/apexcharts.js') }}"></script>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div id="timeline-chart-holder" class="iq-card">
                <div class="iq-card-body">
                    @include ("klia.reports.timeline")
                </div>
            </div>
            <div id="alert-chart-holder" class="iq-card">
                <div class="iq-card-body">
                    @include ("klia.reports.alert-chart")
                </div>
            </div>
            <div class="iq-card">
                <div class="iq-card-body">
                    <div class="row align-items-center m-1" style="justify-content: space-between">
                        <div class="col-5 row align-items-center">
                            <label class="sr-only">Date</label>
                            <div class="input-group mt-1 mb-1 mr-sm-2" style="width: 90%">
                                <div class="input-group-text">Date</div>
                                <input type="text" class="form-control" id="datepicker" kl_vkbd_parsed="true">
                            </div>
                            <a id="timeline-tooltip" href="#" data-toggle="tooltip" data-placement="right" title="Select a single day to view timeline chart" style="cursor: pointer; left-padding:0">
                                <i class="ri-information-fill"></i>
                            </a>
                        </div>
                        <div id = "draw-holder" class="col-7 row" style="justify-content: flex-end">
                            <div id="selHolder" class="col-9 row" style="justify-content: flex-end">
                                <label class="align-items-center col-form-label col-sm-3 text-sm-right">
                                    User:
                                </label>
                                <select id='selUser' class="col-sm-4" style="width: 70% margin-right: 15px"name ="select" ></select>
                            </div>
                            <button id ="draw-btn" class="btn btn-primary" style="margin-left: 15px; float: right; align-items: center;" href="#">Draw</button>
                            <div class="form-check" id ="mergeHolder">
                                <input class="form-check-input" type="checkbox" value="true" id="mergeCheck">
                                <label class="form-check-label" for="mergeCheckDefault">
                                  Merged Locations
                                </label>
                            </div>
                        </div>
                    </div>
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="home-tab-one" 
                            data-toggle="tab" href="#tab-table1" role="tab" aria-controls="home" aria-selected="true">Attendance</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="home-tab-two" 
                            data-toggle="tab" href="#tab-table2" role="tab" aria-controls="home" aria-selected="true">Alerts</a>
                        </li>
                    </ul>
                  
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab-table1">
                            <div class="table-responsive" style="margin-top: 15px">
                                <table class="table table-stripe table-bordered hover" id="attendanceTable">
                                    <thead>
                                        <tr>
                                            <th>User</th>
                                            <th>Tag</th>
                                            <th>First Seen</th>
                                            <th>Last Seen</th>
                                            <th>Location</th>
                                            {{-- <th>Duration</th> --}}
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table> 
                            </div>
                        </div>
                        <div class="tab-pane" id="tab-table2">
                            <div class="table-responsive" style="margin-top: 15px">
                                <table style="width:100%" class="table table-stripe table-bordered hover" id="alertTable">
                                    <thead>
                                        <tr>
                                            <th scope="col">Staff Name</th>
                                            <th scope="col">Occurred at</th>
                                            <th scope="col">Resolved at</th>
                                            <th scope="col">Status</th>
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
        </div>
    </div>
</div>
@endsection 

@section("script")
<script src="{{ asset('js/mix/moment.js') }}"></script>
<script src="{{ asset('js/mix/daterangepicker.js') }}"></script>
<script src="{{ asset('js/mix/pdfmake.js') }}"></script>
<script src="{{ asset('js/mix/jszip.js') }}"></script>
<script>
    
    //Date Picker Setup
    var startDate;
    var endDate;
    var options = {
            title: {
                text: 'Alerts By Staff',
            },
            series: [],
            noData: {
                text: 'No Data'
            },
            chart: {
              type: 'pie',
            },
            legend: {
                show: true,
            },

        };
    var alertChart = new ApexCharts(document.querySelector("#alert-chart"), options);
    alertChart.render();
    $('#timeline-chart-holder').hide();
    $('#alert-chart-holder').hide();
    $('#draw-holder').hide();
    // $('#draw-holder').hide();
    $('#selUser').select2({data: [{id: '', text: ''}]});
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
                startDate = start.format('D MMMM YYYY');
                endDate = end.format('D MMMM YYYY'); 
                if (tab === "home-tab-one"){
                    if (startDate === endDate){
                        // $('#timeline-chart-holder').show();
                        $('#draw-holder').show();
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        $.ajax({
                            url: '{{ route("reports.getSelect")}}',
                            type: "get",
                            data: { 
                            date_range: startDate,
                            },
                            success:function(response){
                                console.log(response);
                                console.log(response['select']['results'].length)
                                if (response['select']['results'].length > 0){
                                    $('#selUser').html('').select2({data: [{id: '', text: ''}]});
                                    $('#selUser').val(null).trigger('change');
                                    $("#selUser").select2({
                                        data:response['select']['results'],
                                    });
                                }else{
                                    console.log("changing")
                                    $('#selUser').html('').select2({data: [{id: '', text: ''}]});
                                    $('#selUser').val(null).trigger('change');
                                }
                            },
                            error:function(error){
                                console.log(error)
                            }
                        })
                    }else{
                        $('#selUser').html('').select2({data: [{id: '', text: ''}]});
                        $('#selUser').val(null).trigger('change');
                        $('#timeline-chart-holder').hide();
                        $('#draw-holder').hide();
                    }
                }
            }
    );
    $('#datepicker').on("change",function(){
            console.log($(this).val());
            attendanceTable.ajax.reload();
            alertTable.ajax.reload();
    });

    //Setup Tabs
    var tab = "home-tab-one"
    $('a[data-toggle="tab"]').on( 'shown.bs.tab', function (e) {
        $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();
        console.log(e.target.id);
        if (e.target.id === "home-tab-one"){
            $('#selHolder').show();
            //$('#timeline-chart-holder').show();
            $('#alert-chart-holder').hide();
            // $('#draw-btn').show();
            $('#timeline-tooltip').show();
            tab = "home-tab-one";
            // console.log($('#selCategory').select2('data')[0]);
            if (startDate != endDate){
                $('#selUser').html('').select2({data: [{id: '', text: ''}]});
                $('#selUser').val(null).trigger('change');
                $('#timeline-chart-holder').hide();
                $('#draw-holder').hide();
            }
        }else if (e.target.id === "home-tab-two"){
            $('#selHolder').hide();
            $('#mergeHolder').hide();
            $('#draw-holder').show();
            $('#timeline-chart-holder').hide();
            $('#timeline-tooltip').hide();
            //$('#alert-chart-holder').show();
            // $('#draw-btn').show();
            tab = "home-tab-two";
        }
    });
    // Setup - add a text input to each footer cell
    $('#attendanceTable thead tr').clone(true).appendTo( '#attendanceTable thead' );
    $('#attendanceTable thead tr:eq(1) th').each( function (i) {
        var title = $(this).text();
        $(this).html( '<div class="iq-search-bar row justify-content-between"> <input type="text" class="text search-input" placeholder="Filter"> </div>' );
        $( 'input', this ).on( 'keyup change', function () {
            if ( attendanceTable.column(i).search() !== this.value ) {
                attendanceTable
                    .column(i)
                    .search( this.value )
                    .draw();
            }
        } );
    } );

    // Setup - add a text input to each footer cell
    $('#alertTable thead tr').clone(true).appendTo( '#alertTable thead' );
    $('#alertTable thead tr:eq(1) th').each( function (i) {
        var title = $(this).text();
        $(this).html( '<div class="iq-search-bar row justify-content-between"> <input type="text" class="text search-input" placeholder="Filter"> </div>' );
        $( 'input', this ).on( 'keyup change', function () {
            if ( alertTable.column(i).search() !== this.value ) {
                alertTable
                    .column(i)
                    .search( this.value )
                    .draw();
            }
        } );
    } );


    /* Initiate dataTable */
    var alertTable = $('#alertTable').DataTable({
        columnDefs: [{
                orderable: false,
        }],
        dom: "<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6'>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-12 col-md-5'li><'col-sm-12 col-md-7'p>>",
        buttons: [{
            extend: 'excelHtml5',
            title: 'Data Export Alerts',
            }
        ],
        order: [[0, 'asc']],
        ajax: {
            url: '{{ route("reports.getAlerts") }}',
            data: function(data) {
                data.date_range = $("#datepicker").val();
            }
        },
        processing: true,
        serverSide: false,
        columns:[
            {data: 'full_name'},
            {data: 'occured_at'},
            {
                data: 'resolved_at',
                render: function(data, type) {
                    if (type === 'display') {
                        if (data) {
                            return data;
                        }else{
                            return '-';
                        }
                    }
                    return data;
                }
            },
            {
                data: 'status',
                render: function(data, type) {
                    if (type === 'display') {
                        if (data) {
                            return '<span class="badge badge-pill iq-bg-success' + '' + '">Resolved</span> ';
                        }else{
                            return '<span class="badge badge-pill iq-bg-danger' + '' + '">Unresolved</span> ';
                        }
                    }
                    // if (type === 'display') {
                    //     return '<span class="flag ' + 'country' + '"></span> ' + 'data';
                    // }
                    return data;
                }
            },
        ], 
        autoWidth: true,
        orderCellsTop: true,
        fixedHeader: true,
    });

    /* Initiate dataTable */
    attendanceTable = $('#attendanceTable').DataTable({
        columnDefs: [{
                orderable: false,
                
        }],
        order: [[ 2, "desc" ]],
        dom: "<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6'>>" +
        "<'row'<'col-sm-12'tr>>" +
        "<'row'<'col-sm-12 col-md-5'li><'col-sm-12 col-md-7'p>>",
        buttons: [
            'excelHtml5',
        ],
        processing: true,
        serverSide: false,
        ajax: {
            url: '{{ route("klia.get") }}',
            data: function(data) {
                data.date_range = $("#datepicker").val();
            }
        },
        columns:[
            {data: 'staff_name'},
            {data: 'tag_mac'},
            {data: 'first_seen'},
            {data: 'last_seen'},
            {data: 'location_name'},
            // {data: 'time_missing'},
        ],
        orderCellsTop: true,
        fixedHeader: true,
    
    })

    $(function() {
        $('#draw-btn').on('click', function (){
            if (tab === "home-tab-one"){
                var selected_data = $('#selUser').select2('data');
                console.log(selected_data);
                console.log(selected_data[0].tag_mac);
                if (selected_data.length > 0)
                getTimelineChartData(selected_data[0].tag_mac);
                $('#timeline-chart-holder').show();
                $('#alert-chart-holder').hide();
            }else if (tab === "home-tab-two"){
                var length = alertTable.rows( { search: 'applied' } ).data().length;
                var counts = [];
                for (i=0; i<length; i++){
                    var data = alertTable.rows( { search: 'applied' } ).data()[i];
                    counts[data['full_name']] = 1 + (counts[data['full_name']] || 0);
                }
                var series = [];
                var labels = []
                for (const [key, value] of Object.entries(counts)) {
                    console.log(key, value);
                    labels.push(key);
                    series.push(value);
                }
                options = {
                    series: series,
                    labels: labels,
                    width: '50%'
                }
                alertChart.updateOptions(options);
                $('#timeline-chart-holder').hide();
                $('#alert-chart-holder').show();
            }
        });
    });


</script>
@endsection