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
                            <a class="btn btn-primary" href="{{ route('gateways.create') }}" style="margin-right: 10px">Create</a>
                            <a class="btn btn-danger" href="#">Delete</a>
                        </div>
                    </div>
                    <div class="table-responsive" style="margin-top: 15px">
                        <table class="table table-stripe table-bordered hover" id="readerTable">
                        <thead>
                                <tr>
                                    <th scope="col" style="width:5%">#</th>
                                    <th scope="col">Serial Number</th>
                                    <th scope="col">Mac Address</th>
                                    <th scope="col">Location</th>
                                    <th scope="col">Floor</th>
                                    <th scope="col">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($readers as $reader)
                                    <tr href="{{ route('gateways.edit',$reader->gateway_id) }}">
                                        <td>{{ $reader->gateway_id }}</td>
                                        <td>{{ isset($reader->serial) ? $reader->serial : "N/A" }} </td>
                                        <td>{{ $reader->mac_addr }}</td>
                                        <td>{{ isset($reader->location) ? $reader->location->location_description : "N/A"  }}</td>
                                        <td>{{ isset($reader->location) ? $reader->location->floor_level->alias : "N/A"  }}</td>
                                        <td>{{ isset($reader->reader_status) ? $reader->reader_status : "N/A"  }}</td>
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
    var dTable = $('#readerTable').DataTable({
            order: [[1, 'asc']],
        })

    $('#myCustomSearchBox').keyup(function(){  
        dTable.search($(this).val()).draw();   // this  is for customized searchbox with datatable search feature.
    })

    $('#readerTable tbody tr td:not(:first-child)').click(function () {
        window.location.href = $(this).parent('tr').attr('href');
    });
</script>
@endsection