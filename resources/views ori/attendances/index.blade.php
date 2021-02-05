@extends('layouts.app')

@section('content')
<div class="container-fluid p-0">
    <!-- Display alert -->
    @if ($message = Session::get('success'))

        <div class="alert alert-success alert-dismissible" attendance="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <div class="alert-message">
                <strong>Hello there!</strong> {{ $message }}
            </div>
        </div>
    @endif

    <!-- Title & Add-Button -->
    <div class="row mb-2 mb-xl-3">
        <div class="col-auto d-none d-sm-block">
            <h3><strong>Attendance</strong> Management</h3>
        </div>
        <div class="col-auto ml-auto text-right mt-n1">
            @can('attendance-list')
                <a class="btn btn-primary" href="#">
                    @svg('plus', 'feather-plus align-middle')  
                    <span class="align-middle">Export</span>
                </a>
            @endcan
        </div>
	</div>
    
    <!-- Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><strong>Filters</strong></h3>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="row">
                                <label class="col-form-label col-sm-4 text-sm-right">
                                    Group:
                                </label>
                                <div class="col-sm-7" id="createNameField">
                                    {!! Form::text('group', null, array('placeholder' => 'Group','class' => 'form-control')) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="row">
                                <label class="col-form-label col-sm-3 text-sm-right">
                                    User:
                                </label>
                                <div class="col-sm-7" id="createNameField">
                                    {!! Form::text('group', null, array('placeholder' => 'User','class' => 'form-control')) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="row">
                                <label class="col-form-label col-sm-5 text-sm-right">
                                    Date Range:
                                </label>
                                <div class="col-sm-7" id="createNameField">
                                    {!! Form::text('group', null, array('placeholder' => 'Date','class' => 'form-control')) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            @can('attendance-list')
                                <a class="btn btn-primary" href="#">
                                    <span class="align-middle">Apply</span>
                                </a>
                            @endcan
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover nowrap" id="attendanceTable">
                            <thead>
                                <tr>
                                    <th scope="col" style="width:5%">#</th>
                                    <th scope="col" >Date</th>
                                    <th scope="col" >Name</th>
                                    <th scope="col" >Group</th>
                                    <th scope="col" >Clock In</th>
                                    <th scope="col" >Clock Out</th>
                                    <th scope="col" >Total Time</th>
                                </tr>
                            </thead>
                        </table> 
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 

@section("script")
<script>
    $(function () {

        /* Initiate dataTable */
        $('#attendanceTable').DataTable({
            dom: '<fl<t>ip>',
            searching: false,
            paging: false,
            responsive: true,
            stateSave: true,
            'lengthMenu': [[10, 25, 50, -1], [10, 25, 50, "All"]],
            orders: [],
            "columnDefs": [ {
                "targets"  : 'noSort',
                "orderable": false,
            }]
        })
    })
</script>
@endsection