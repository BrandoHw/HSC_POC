@extends('layouts.app')

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
                    </div>
                    {{-- <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="home-tab-one" 
                            data-toggle="tab" href="#tab-table1" role="tab" aria-controls="home" aria-selected="true">Alerts</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="home-tab-two" 
                            data-toggle="tab" href="#tab-table2" role="tab" aria-controls="home" aria-selected="true">Gateways</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="home-tab-three" 
                            data-toggle="tab" href="#tab-table3" role="tab" aria-controls="home" aria-selected="true">Residents</a>
                        </li>
                    </ul> --}}
                  
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 

@section("script")
<script>
    
    //Date Picker Setup
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
            attendanceTable.ajax.reload();
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

 

</script>
@endsection