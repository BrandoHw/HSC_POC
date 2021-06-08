@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="iq-card">
                <div class="iq-card-body">
                    <h3 id = chart-title style="text-align: center; display: none; margin-bottom:10px">Pie Chart</h3>
                    <div id="chart-div" style="height: 7vh"></div>
                    <p id = chart-info style="text-align: center; display: none ; margin-top:10px">
                    </p>
                </div>
            </div>
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
                            <div class="col-8 row" style="justify-content: flex-end">
                                <div id="selHolder" class="col-9 row" style="justify-content: flex-end">
                                    <label class="col-form-label col-sm-3 text-sm-right">
                                        Category:
                                        </label>
                                    <select id='selCategory' class="col-sm-4" style="margin-right: 15px"name ="select" ></select>
                                </div>
                                <button id ="draw-btn" class="btn btn-primary" style="margin-left: 15px; float: right; align-items: center;" href="#">Draw</button>
                            </div>
                    </div>

                    <ul class="nav nav-tabs" role="tablist">
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
                        <li class="nav-item">
                            <a class="nav-link" id="home-tab-four" 
                            data-toggle="tab" href="#tab-table4" role="tab" aria-controls="home" aria-selected="true">Users</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab-table1">
                            <div class="table-responsive" style="margin-top: 15px">
                                <table class="table table-stripe table-bordered hover" id="alertTable">
                                    <thead>
                                        <tr>
                                            <th scope="col">Policy Type</th>
                                            <th scope="col">Policy Name</th>
                                            <th scope="col">Name</th>
                                            <th scope="col">Location</th>
                                            <th scope="col">Date</th>
                                            <th scope="col">Time</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- @foreach ($alerts as $alert)
                                            <tr>
                                                <td>{{ $alert->policy->policyType->rules_type_desc }}</td>
                                                <td>{{ $alert->policy->description }}</td>
                                                <td>
                                                    @if($alert->tag->beacon_type == 2)
                                                        {{ $alert->tag->user->full_name ?? '-' }}
                                                    @else
                                                        {{ $alert->tag->resident->full_name ?? '-' }}
                                                    @endif
                                                </td>
                                                <td>{{ $alert->reader->location->location_description ?? "-" }}</td>
                                            </tr>
                                        @endforeach --}}
                                    </tbody>
                                </table> 
                            </div>
                        </div>
                        <div class="tab-pane" id="tab-table2">
                            <div class="table-responsive" style="margin-top: 15px">
                                <table class="table table-stripe table-bordered hover" id="gatewayTable">
                                    <thead>
                                        <tr>
                                            <th scope="col">Serial</th>
                                            <th scope="col">Mac Addr</th>
                                            <th scope="col">Location</th>
                                            <th scope="col">IP Address</th>
                                            <th scope="col">Up Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($gateways as $gateway)
                                            <tr>
                                                <td>{{ $gateway->serial ?? "-"}}</td>
                                                <td>{{ $gateway->mac_addr}}</td>
                                                <td>{{ $gateway->location->location_description ?? "-" }}</td>
                                                <td>{{ $gateway->reader_ip ?? "-" }}</td>
                                                <td>
                                                    <span class="badge badge-pill iq-bg-{{ ($gateway->online == true) ? 'success':'danger' }}">
                                                        {{ ($gateway->online == true) ? 'Online':'Offline'  }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table> 
                            </div>
                        </div>
                        <div class="tab-pane" id="tab-table3">
                            <div class="table-responsive" style="margin-top: 15px">
                                <table class="table table-stripe table-bordered hover" id="residentTable">
                                    <thead>
                                        <tr>
                                            <th scope="col">Full Name</th>
                                            <th scope="col">DoB</th>
                                            <th scope="col">Age</th>
                                            <th scope="col">Gender</th>
                                            <th scope="col">Wheelchair</th>
                                            <th scope="col">Walking Cane</th>
                                            <th scope="col">Contact Name</th>
                                            <th scope="col">Contact Phone Numbers</th>
                                            <th scope="col">Contact Address</th>
                                            <th scope="col">Contact Relationship</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($residents as $resident)
                                            <tr>
                                                <td>{{ $resident->full_name}}</td>
                                                <td>{{ $resident->resident_dob}}</td>
                                                <td>{{ $resident->age}}</td>
                                                <td>{{ $resident->resident_gender ?? "-" }}</td>
                                                <td class='info align-middle'>{{ ($resident->wheelchair) ? "Yes":"No" }}</td>
                                                <td class='info align-middle'>{{ ($resident->walking_cane) ? "Yes":"No" }}</td>
                                                <td>{{ $resident->contact_name ?? "-" }}</td>
                                                <td>{{ $resident->contact_numbers ?? "-" }}</td>
                                                <td>{{ $resident->contact_address ?? "-" }}</td>
                                                <td>{{ $resident->contact_relationship ?? "-" }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table> 
                            </div>
                        </div>
                        <div class="tab-pane" id="tab-table4">
                            <div class="table-responsive" style="margin-top: 15px">
                                <table class="table table-stripe table-bordered hover" id="userTable">
                                    <thead>
                                        <tr>
                                            <th scope="col">Name</th>
                                            <th scope="col">Username</th>
                                            <th scope="col">User Type</th>
                                            <th scope="col">Role</th>
                                            <th scope="col">Email</th>
                                            <th scope="col">Phone Number</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($users as $user)
                                            <tr>
                                                <td>{{ $user->full_name}}</td>
                                                <td>{{ $user->username}}</td>
                                                <td>{{ $user->userType->type ?? "-"}}</td>
                                                <td>{{ $user->userRight->description ?? "-"}}</td>
                                                <td>{{ $user->email}}</td>
                                                <td>{{ $user->phone_number }}</td> 
                                            </tr>
                                        @endforeach
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
    
    // var alerts = <?php echo $alerts ?>;
    // console.log(alerts);

    var select_alerts = [
        {
            "id": 1,
            "text": "Policy Type",
        },
        {
            "id": 2,
            "text": "Subject",
        },
        {
            "id": 3,
            "text": "Location",
        },
    ]

    $("#selCategory").select2({
        data:select_alerts,
        minimumResultsForSearch: Infinity,
    });

        console.log($('#selCategory').select2('data')[0]);
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
            alertTable.ajax.reload();
    });


    // Create chart instance
    var pie_chart = am4core.create("chart-div", am4charts.PieChart);
    var tab = "home-tab-one"
    var users =  <?php echo $users; ?>;
    console.log(users);
    $('a[data-toggle="tab"]').on( 'shown.bs.tab', function (e) {
        $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();
        console.log(e.target.id);

        if (e.target.id === "home-tab-one"){
            $('#selHolder').show();
            $('#draw-btn').show();
            tab = "home-tab-one";
            console.log($('#selCategory').select2('data')[0]);
        }else if (e.target.id === "home-tab-two"){
            $('#selHolder').hide();
            $('#draw-btn').show();
            tab = "home-tab-two";
        }else if (e.target.id === "home-tab-three"){
            $('#selHolder').hide();
            $('#draw-btn').hide();
            tab = "home-tab-three";
        }else if (e.target.id === "home-tab-four"){
            $('#selHolder').hide();
            $('#draw-btn').hide();
            tab = "home-tab-four";
        }

    });

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
        // orderCellsTop: true,
        // fixedHeader: true,
        ajax: {
                url: '{{ route("report.data") }}',
                data: function(data) {
                    data.date_range = $("#datepicker").val();
                }
            },
        columns:[
            {data: 'policy.policy_type.rules_type_desc'},
            {data: 'policy.description'},
            {data: 'name'},
            {data: 'reader.location.location_description'},
            {data: 'date'},
            {data: 'time'},
        ],  
        pageLength: 15, 
        orderCellsTop: true,
        fixedHeader: true,
    });

    var gatewayTable = $('#gatewayTable').DataTable({
        dom: "<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6'>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-12 col-md-5'li><'col-sm-12 col-md-7'p>>",
        buttons: [{
            extend: 'excelHtml5',
            title: 'Data export Gateways',
            },
        ],
        columnDefs: [{
            orderable: false,
        }],
        pageLength: 15,
        order: [[0, 'asc']],
    });

    
    var residentTable = $('#residentTable').DataTable({
        dom: "<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6'>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-12 col-md-5'li><'col-sm-12 col-md-7'p>>",
        buttons: [{
            extend: 'excelHtml5',
            title: 'Data export Residents',
            },
        ],
        columnDefs: [{
            orderable: false,
        }],
        pageLength: 15,
        order: [[0, 'asc']],
    });

    var userTable = $('#userTable').DataTable({
        dom: "<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6'>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-12 col-md-5'li><'col-sm-12 col-md-7'p>>",
        buttons: [{
            extend: 'excelHtml5',
            title: 'Data export Users',
            },
        ],
        columnDefs: [{
            orderable: false,
        }],
        pageLength: 15,
        order: [[0, 'asc']],
    });

    var series;
    $('#draw-btn').on('click', function (){
        // console.log(alertTable.rows( { search: 'applied' } ).data());
        // console.log(alertTable.rows( { search: 'applied' } ).data().length);
        var length = alertTable.rows( { search: 'applied' } ).data().length;
        var counts_policy_type = [];
        var counts_subject = [];
        var counts_location = [];
        for (i=0; i<length; i++){
            var data = alertTable.rows( { search: 'applied' } ).data()[i];
            counts_policy_type[data['policy']['policy_type']['rules_type_desc']] = 1 + (counts_policy_type[data['policy']['policy_type']['rules_type_desc']] || 0);
            counts_subject[data['name']] = 1 + (counts_subject[data['name']] || 0);
            counts_location[data['reader']['location']['location_description']] = 1 + (counts_location[data['reader']['location']['location_description']] || 0);
        }

        // console.log(alertTable.rows( { search: 'applied' } ).data().length);
        var counts_gateways = [];
        var counts_policy_type_data = [];
        var counts_subject_data = [];
        var counts_location_data = [];
        var counts_gateways_data = [];

        count = 0;
        for (var key in counts_policy_type){
            counts_policy_type_data[count] = {"Policy Type": key, "count": counts_policy_type[key]};
            count++;
        }
        count = 0;
        for (var key in counts_subject){
            counts_subject_data[count] = {"Subject": key, "count": counts_subject[key]};
            count++;
        }
        count = 0;
        for (var key in counts_location){
            counts_location_data[count] = {"Location": key, "count": counts_location[key]};
            count++;
        }
        count_sort = function(a, b) {
            return parseFloat(a.count) - parseFloat(b.count);
        };
        counts_policy_type_data.sort(count_sort);
        counts_subject_data.sort(count_sort);
        counts_location_data.sort(count_sort);
  
        console.log('click');
        gateways = <?php echo $gateways ?>;
        if (tab === "home-tab-two" && gateways.length != 0){
            $('#chart-div').height("40vh");
            $('#chart-title').show();
            if (pie_chart.series.indexOf(series) != -1)
            pie_chart.series.removeIndex(
                pie_chart.series.indexOf(series)
            ).dispose();
            series = pie_chart.series.push(new am4charts.series());
            series.dataFields.value = "count";
            series.slices.template.stroke = am4core.color("#fff");
            series.slices.template.strokeWidth = 2;
            series.slices.template.strokeOpacity = 1;

            // This creates initial animation
            series.hiddenState.properties.opacity = 1;
            series.hiddenState.properties.endAngle = -90;
            series.hiddenState.properties.startAngle = -90;

            var count = 0;
                var length = gateways.length;
                for (i=0; i<length; i++){
                    var data = gateways[i];
                    counts_gateways[data['online']] = 1 + (counts_gateways[data['online']] || 0);
                }
                for (var key in counts_gateways){
                    if (key === "false"){counts_gateways_data[count] = {"Gateways": "Offline", "count": counts_gateways[key], "color": am4core.color("#DC143C")};}
                    else{counts_gateways_data[count] = {"Gateways": "Online", "count": counts_gateways[key], "color": am4core.color("#228B22")};}
                    count++;
                };
                series.dataFields.category = "Gateways";
                series.slices.template.propertyFields.fill = "color";
                pie_chart.data = counts_gateways_data;
                $('#chart-title').text('Gateway Status')
                $('#chart-info').hide();

        }else if (counts_policy_type_data.length != 0){
            var text = "<b>Most Frequent Alert Type</b>: ".concat(counts_policy_type_data[0]["Policy Type"], "<br>",
                        "<b>Most Frequent Subject</b>: ", counts_subject_data[0]["Subject"], "<br>",
                        "<b>Most Frequent Location</b>: ", counts_location_data[0]["Location"])
            // console.log(counts_location_data);
            $('#chart-div').height("40vh");
            $('#chart-title').show();
            $('#chart-info').html(text);
            $('#chart-info').show();
            if (pie_chart.series.indexOf(series) != -1)
            pie_chart.series.removeIndex(
                pie_chart.series.indexOf(series)
            ).dispose();

            series = pie_chart.series.push(new am4charts.PieSeries());
            series.dataFields.value = "count";
            series.slices.template.stroke = am4core.color("#fff");
            series.slices.template.strokeWidth = 2;
            series.slices.template.strokeOpacity = 1;

            // This creates initial animation
            series.hiddenState.properties.opacity = 1;
            series.hiddenState.properties.endAngle = -90;
            series.hiddenState.properties.startAngle = -90;
            

            switch ($('#selCategory').select2('data')[0].text){
            case "Policy Type":
                series.dataFields.category = "Policy Type";
                pie_chart.data = counts_policy_type_data;
                $('#chart-title').text('Alerts by Policy Type')
                break;
            case "Subject":
                series.dataFields.category = "Subject";
                pie_chart.data = counts_subject_data;
                $('#chart-title').text('Alerts by Subject')
                break;
            case "Location":
                series.dataFields.category = "Location";
                pie_chart.data = counts_location_data;
                $('#chart-title').text('Alerts by Location')
                break;
            }
            
        }
    })

</script>
@endsection