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
                            <a class="btn btn-primary" href="{{ route('alerts.create') }}" style="margin-right: 10px">Create</a>
                            <a class="btn btn-danger" href="#">Delete</a>
                        </div>
                    </div>
                    <div class="table-responsive" style="margin-top: 15px">
                        <table class="table table-stripe table-bordered hover" id="alertTable">
                        <thead>
                                <tr>
                                    <th scope="col" style="width:10%">#</th>
                                    <th scope="col" style="width:35%">Name</th>
                                    <th scope="col" style="width:25%">Type</th>
                                    <th scope="col" style="width:30%">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($alerts as $alert)
                                    <tr href="{{ route('alerts.edit',$alert->rules_id) }}">
                                        <td>{{ $alert->rules_id }}</td>
                                        <td>{{ $alert->description }}</td>
                                        <td>{{ $alert->alertType->rules_type_desc }}</td>
                                        <td>
                                            <span class="badge badge-pill iq-bg-{{ ($alert->alert_option == 1) ? 'success':'secondary' }}">
                                                {{ ($alert->alert_option == 1) ? 'Active':'Inactive' }}
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
    var dTable = $('#alertTable').DataTable({
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