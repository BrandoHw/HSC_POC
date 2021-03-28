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
                            <a class="btn btn-primary" href="{{ route('beacons.create') }}" style="margin-right: 10px">Create</a>
                            <a class="btn btn-danger" href="#">Delete</a>
                        </div>
                    </div>
                    <div class="table-responsive" style="margin-top: 15px">
                        <table class="table table-stripe table-bordered hover" id="tagTable">
                        <thead>
                                <tr>
                                    <th scope="col" style="width:10%">#</th>
                                    <th scope="col" style="width:35%">Mac Address</th>
                                    <th scope="col" style="width:25%">Type</th>
                                    <th scope="col" style="width:30%">Assigned To</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tags as $tag)
                                    <tr href="{{ route('beacons.edit',$tag->beacon_id) }}">
                                        <td>{{ $tag->beacon_id }}</td>
                                        <td>{{ $tag->beacon_mac }}</td>
                                        <td>
                                            <span class="badge badge-pill iq-bg-{{ ($tag->beacon_type == 1) ? 'primary':'success' }}">
                                                {{ ($tag->beacon_type == 2) ? 'Card':'Wristband' }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($tag->beacon_type == 2)
                                                {{ $tag->user->full_name ?? '-' }}
                                            @else
                                                {{ $tag->resident->full_name ?? '-' }}
                                            @endif
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
    var dTable = $('#tagTable').DataTable({
            order: [[1, 'asc']],
        })

    $('#myCustomSearchBox').keyup(function(){  
        dTable.search($(this).val()).draw();   // this  is for customized searchbox with datatable search feature.
    })

    $('#tagTable tbody tr td:not(:first-child)').click(function () {
        window.location.href = $(this).parent('tr').attr('href');
    });
</script>
@endsection