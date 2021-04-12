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
                            <a class="btn btn-primary" href="{{ route('policies.create') }}" style="margin-right: 10px">Create</a>
                            <a class="btn btn-danger" href="#">Delete</a>
                        </div>
                    </div>
                    <div class="table-responsive" style="margin-top: 15px">
                        <table class="table table-stripe table-bordered hover" id="policyTable">
                        <thead>
                                <tr>
                                    <th scope="col" style="width:10%">#</th>
                                    <th scope="col" style="width:35%">Name</th>
                                    <th scope="col" style="width:25%">Type</th>
                                    <th scope="col" style="width:25%">Parameters</th>
                                    <th scope="col" style="width:30%">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($policies as $policy)
                                    <tr href="{{ route('policies.edit',$policy->rules_id) }}">
                                        <td>{{ $policy->rules_id }}</td>
                                        <td class="info">{{ $policy->description }}</td>
                                        <td class="info">{{ $policy->policyType->rules_type_desc }}</td>
                                        <td class="info">
                                            @switch($policy->policyType->rules_type_id)
                                                @case(1)
                                                    {{ ($policy->attendance) ? 'Present':'Absent' }}
                                                    @break  
                                                @case(2)
                                                @case(3)
                                                @case(4)
                                                    -
                                                    @break  
                                                @case(5)
                                                    {{ ($policy->geofence) ? 'Entering Zone':'Leaving Zone' }}
                                                    @break  
                                                @case(6)
                                                    x-axis: {{ ($policy->x_threshold) ?:'-' }}<br>
                                                    y-axis: {{ ($policy->y_threshold) ?:'-' }}<br>
                                                    z-axis: {{ ($policy->z_threshold) ?:'-' }}
                                                    @break  
                                            @endswitch
                                        </td>
                                        <td class="info">
                                            <span class="badge badge-pill iq-bg-{{ ($policy->alert_action == 1) ? 'success':'secondary' }}">
                                                {{ ($policy->alert_action == 1) ? 'Active':'Inactive' }}
                                            </span>
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
</div>
@endsection 

@section("script")
<script>
    /* Initiate dataTable */
    var dTable = $('#policyTable').DataTable({
            order: [[1, 'asc']],
        })

    $('#myCustomSearchBox').keyup(function(){  
        dTable.search($(this).val()).draw();   // this  is for customized searchbox with datatable search feature.
    })

    $('#policyTable').on('click', '.info', function () {
        window.location.href = $(this).parent('tr').attr('href');
    });
</script>
@endsection