@extends('layouts.app')

@section('content')
<div class="container-fluid relative">
    <div class="row">
        <div class="col-lg-3">
            <div class="iq-card" style="height: 500px">
                <div class="iq-card-body">
                <div class="">
                    <div class="iq-email-list">
                        <div class="iq-email-ui nav flex-column nav-pills">
                            <li class="nav-link active" role="tab" data-toggle="pill" href="#breakfast"><a href="#"><i class="ri-timer-2-line"></i>Breakfast</a></li>
                            <li class="nav-link" role="tab" data-toggle="pill" href="#lunch"><a href="#"><i class="ri-timer-2-line"></i>Lunch</a></li>
                            <li class="nav-link" role="tab" data-toggle="pill" href="#dinner"><a href="#"><i class="ri-timer-2-line"></i>Dinner</a></li>
                        </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
        <div class="col-lg-9 mail-box-detail">
            <div class="iq-card" style="height: 500px">
                <div class="iq-card-body p-0">
                <div class="iq-card-body">
                    <div class="iq-search-bar row justify-content-between">
                        <form action="#" class="searchbox">
                            <input type="text" id="myCustomSearchBox" class="text search-input" placeholder="Type here to search...">
                            <a class="search-link" href="#"><i class="ri-search-line"></i></a>
                        </form>
                        <!-- <div class="input-group input-group-sm mb-0 date">
                            <input type="text" class="form-control" id="time-range" name="time-range" style="background-color: white"/>
                            <div class="input-group-append mb-1">
                                <div class="input-group-text"><i class="fa fa-clock-o"></i></div>
                            </div>
                        </div> -->
                        <div class="col-6 row justify-content-end">
                            <div class="col-8 input-group input-group-sm mb-0 date">
                                <input type="text" class="form-control" id="time-range" name="time-range" style="background-color: white"/>
                                <div class="input-group-append mb-1">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                            <div class="col-2 row justify-content-end">
                                <a class="btn btn-primary" href="#">Export</a>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive" style="margin-top: 15px">
                        <table class="table table-stripe table-bordered hover" id="alertTable">
                        <thead>
                                <tr>
                                    <th scope="col" style="width:10%">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Attendance</th>
                                    <th scope="col">Last updated at</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($alerts as $alert)
                                    @if($alert->policy->policyType->rules_type_id == 1)
                                    <tr href="{{ route('alerts.edit',$alert->alert_id) }}">
                                        <td>{{ $alert->tag->resident->alert_id }}</td>
                                        <td>{{ $alert->tag->resident->full_name }}</td>
                                        <td>3/4</td>
                                        <td>{{ $alert->occured_at }}</td>
                                    </tr>
                                    @endif
                                @endforeach
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
<script>
    $(function(){
        $('#time-range').flatpickr(
            {
                mode: "range",
                minDate: "2021-1-1",
                maxDate: "today",
                dateFormat: "Y-m-d",
                defaultDate: "today"
            }
        );
    })
    /* Initiate dataTable */
    var dTable = $('#alertTable').DataTable({
        order: [[1, 'asc']],
    })
    
    var d2Table = $('#alert2Table').DataTable({
        order: [[1, 'asc']],
    })

    $('#myCustomSearchBox').keyup(function(){  
        dTable.search($(this).val()).draw();   // this  is for customized searchbox with datatable search feature.
    })

    $('#alertTable tbody tr td:not(:first-child)').click(function () {
        window.location.href = $(this).parent('tr').attr('href');
    });
</script>
@endsection