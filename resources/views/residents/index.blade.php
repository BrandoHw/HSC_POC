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
                        </div>
                    </div>
                    <div class="table-responsive" style="margin-top: 15px">
                        <table class="table table-stripe table-bordered hover" id="residentTable">
                        <thead>
                                <tr>
                                    <th scope="col" style="width:10%">#</th>
                                    <th scope="col">First Name</th>
                                    <th scope="col">Last Name</th>
                                    <th scope="col">Age</th>
                                    <th scope="col">Wheelchair</th>
                                    <th scope="col">Walking Cane</th>
                                    <th scope="col">Wristband</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($residents as $resident)
                                    <tr href="{{ route('residents.edit',$resident->resident_id) }}">
                                        <td>{{ $resident->resident_id }}</td>
                                        <td>{{ $resident->resident_fName }}</td>
                                        <td>{{ $resident->resident_lName }}</td>
                                        <td>{{ $resident->resident_age }}</td>
                                        <td>{{ ($resident->wheelchair) ? "Yes":"No" }}</td>
                                        <td>{{ ($resident->walking_cane) ? "Yes":"No" }}</td>
                                        <td>{{ $resident->tag->beacon_mac ?? "-" }}</td>
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
    var dTable = $('#residentTable').DataTable({
            order: [[1, 'asc']],
        })

    $('#myCustomSearchBox').keyup(function(){  
        dTable.search($(this).val()).draw();   // this  is for customized searchbox with datatable search feature.
    })

    $('#residentTable tbody tr td:not(:first-child)').click(function () {
        window.location.href = $(this).parent('tr').attr('href');
    });
</script>
@endsection