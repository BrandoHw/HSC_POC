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
                            <a class="btn iq-bg-primary" href="#" data-toggle="tooltip" data-placement="top" 
                                title="Mark As Resolved" style="margin-right: 10px; font-size:20px; heigh:40px"><i class="ml-0 mr-0 ri-checkbox-line"></i></a>
                            <a class="btn iq-bg-danger" href="#" data-toggle="tooltip" data-placement="top" 
                                title="Archieve" style="margin-right: 10px"><i class="ri-archive-line"></i></a>
                            <a class="btn iq-bg-info" href="#" data-toggle="tooltip" data-placement="top" 
                                title="Refresh"><i class="ri-restart-line"></i></a>
                        </div>
                    </div>
                    <div class="table-responsive" style="margin-top: 15px">
                        <table class="table table-stripe table-bordered hover" id="alertTable">
                        <thead>
                                <tr>
                                    <th scope="col" style="width:10%">#</th>
                                    <th scope="col">Policy Type</th>
                                    <th scope="col">Policy Name</th>
                                    <th scope="col">Subject</th>
                                    <th scope="col">Location</th>
                                    <th scope="col">Occured at</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Resolved by</th>
                                    <th scope="col">Resolved at</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($alerts as $alert)
                                    <tr>
                                        <td>{{ $alert->alert_id }}</td>
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
                                        <td>
                                            <span class="badge badge-pill iq-bg-{{ ($alert->action == 1) ? 'success':'danger' }}">
                                                {{ ($alert->action == 1) ? 'Resolved':'Unresolved' }}
                                            </span>
                                        </td>
                                        <td>{{ $alert->user->full_name ?? "-" }}</td>
                                        <td>{{ $alert->resolved_at_tz }}</td>
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
    var dTable = $('#alertTable').DataTable({
            order: [[5, 'desc']],
        })

    $('#myCustomSearchBox').keyup(function(){  
        dTable.search($(this).val()).draw();   // this  is for customized searchbox with datatable search feature.
    })

    // $('#alertTable tbody tr td:not(:first-child)').click(function () {
    //     window.location.href = $(this).parent('tr').attr('href');
    // });
</script>
@endsection