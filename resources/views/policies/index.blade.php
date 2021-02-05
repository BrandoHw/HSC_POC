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
                        <table id="policyTable" class="table table-stripe table-bordered hover">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Name</th>
                                    <th>ID</th>
                                    <th>Type</th>
                                    <th>Applied To</th>
                                    <th>Created At</th>
                                    <th>State</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($policies as $policy)
                                <tr href="{{ route('policies.edit', $policy['id']) }}">
                                    <td>{{ $policy['id'] }}</td>
                                    <td>{{ $policy['name'] }}</td>
                                    <td>{{ $policy['id'] }}</td>
                                    <td>{{ $policy['type'] }}</td>
                                    <td>{{ $policy['applied_to'] }}</td>
                                    <td>{{ $policy['created_at'] }}</td>
                                    <td><span class="badge badge-pill badge-{{ ($policy['state'] == 'Active') ? 'success':'secondary' }}">{{ $policy['state'] }}</span></td>
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

@section('script')
<script type="text/javascript">
   dTable = $('#policyTable').DataTable({
        order: [[5, 'asc']],
   });
   
    $('#myCustomSearchBox').keyup(function(){  
        dTable.search($(this).val()).draw();   // this  is for customized searchbox with datatable search feature.
    })

    $('#policyTable tbody tr td:not(:first-child)').click(function () {
        window.location.href = $(this).parent('tr').attr('href');
    });

</script>
@endsection