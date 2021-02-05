@extends('layouts.app')

@section('content')
<div class="container-fluid p-0">
    <!-- Display alert -->
    @if ($groupNum == 0)
        <div class="alert alert-danger" tag="alert">
            <div class="alert-message">
                <strong>No group is found!</strong> Please create a new group to proceed!
            </div>
        </div>
    @endif

    <!-- Title & Add-Button -->
    <div class="row mb-2 mb-xl-3">
        <div class="col-auto d-none d-sm-block">
            <h3><strong>Attendance</strong> List</h3>
        </div>
	</div>
    
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-4">
                            <label class="sr-only">Group</label>
                            <div class="input-group mb-2 mr-sm-2">
                                <div class="input-group-text">Group</div>
                                {!! Form::select('group_id', $groups, null, array('class' => 'form-control', 'id' => 'group_id')) !!}
                            </div>
                        </div>
                        <div class="col-4">
                            <label class="sr-only">Date</label>
                            <div class="input-group mb-2 mr-sm-2">
                                <div class="input-group-text">Date</div>
                                <input type="text" class="form-control" id="datepicker" kl_vkbd_parsed="true">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tag-data-table" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Tag</th>
                                    <th>Check-In At</th>
                                    <th>Status</th>
                                    <th>Check-Out At</th>
                                    <th>Status</th>
                                    <th>Location</th>
                                    <th>Duration</th>
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
@endsection 

@section("script")
<script type="text/javascript">

    var tagTable;

    $(function(){

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
            startDate: moment(),
            endDate: moment(),
            maxDate: moment(),
            }, 
            function(start, end, label) {
                console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
            }
        );

        @if($groupNum == 0)
        tagTable = $('#tag-data-table').DataTable({
			dom: '<fl<t>ip>',
        })
        @else
        tagTable = $('#tag-data-table').DataTable({
			dom: '<fl<t>ip>',
			processing: true,
            serverSide: false,
            ajax: {
                url: '{{ route("tag_data_logs.process") }}',
                data: function(data) {
                    data.group_id = $("#group_id").val();
                    data.date_range = $("#datepicker").val();
                }
            },
            columns:[
                {data: 'user'},
                {data: 'mac_addr'},
                {data: 'first_detected_at'},
                {data: 'check_in_status'},
                {data: 'last_detected_at'},
                {data: 'check_out_status'},
                {data: 'location'},
                {data: 'duration'},
            ],
        })

        $('#group_id').on("change",function(){
            console.log($(this).val());
            console.log($('#datepicker').val());
            tagTable.ajax.reload();
        });
        
        $('#datepicker').on("change",function(){
            console.log($(this).val());
            console.log($('#group_id').val());
            tagTable.ajax.reload();
        });

        setInterval( function () {
            tagTable.ajax.reload();
        }, 60000 );
        @endif

        

    })

</script>
@endsection