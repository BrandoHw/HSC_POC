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
                                    <th scope="col">Name</th>
                                    <th scope="col">Age</th>
                                    <th scope="col">Gender</th>
                                    <th scope="col">Wheelchair</th>
                                    <th scope="col">Walking Cane</th>
                                    <th scope="col">Beacon</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($residents as $resident)
                                    <tr href="{{ route('residents.edit',$resident->resident_id) }}">
                                        <td class='align-middle'>{{ $resident->resident_id }}</td>
                                        <td class='align-middle'><img class="rounded-circle img-fluid avatar-40" src="{{ asset('img/avatars/default-profile-m.jpg') }}" alt="profile"> {{ $resident->full_name }}</td>
                                        <td class='align-middle'>{{ $resident->resident_age }}</td>
                                        <td class='align-middle'>{{ $resident->gender ?? '-' }}</td>
                                        <td class='align-middle'>{{ ($resident->wheelchair) ? "Yes":"No" }}</td>
                                        <td class='align-middle'>{{ ($resident->walking_cane) ? "Yes":"No" }}</td>
                                        <td class='align-middle'>{{ $resident->tag->beacon_mac ?? "-" }}</td>
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
    @if ($message = Session::get('success'))
        notyf.success(@json($message));
    @endif

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