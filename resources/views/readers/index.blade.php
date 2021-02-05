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
                            <a class="btn btn-primary" href="{{ route('readers.create') }}" style="margin-right: 10px">Create</a>
                            <a class="btn btn-danger" href="#">Delete</a>
                        </div>
                    </div>
                    <div class="table-responsive" style="margin-top: 15px">
                        <table class="table table-stripe table-bordered hover" id="readerTable">
                            <thead>
                                <tr>
                                    <th scope="col" style="width:10%">#</th>
                                    <th scope="col" style="width:25%">ID</th>
                                    <th scope="col" style="width:25%">M.A.C</th>
                                    <th scope="col" style="width:20%">Floor</th>
                                    <th scope="col" style="width:20%">Zone</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($readers as $reader)
                                    <tr id="trReader-{{ $reader->id }}">
                                        <td>{{ $reader->id }}</td>
                                        <td id="tdReaderSerial-{{ $reader->id }}">
                                            <a href="{{ route('readers.edit',$reader->id) }}">
                                                {{ $reader->serial }} 
                                            </a>
                                        </td>
                                        <td id="tdReaderMacAdd-{{ $reader->id }}">
                                            {{ $reader->mac_addr }}
                                        </td>
                                        <td id="tdReaderBuilding-{{ $reader->id }}">
                                            @if(empty($reader->floor))
                                                <font color='gray'><em>Not Assigned</em></font>
                                            @else
                                                {{ $reader->floor->building->name }}
                                            @endif
                                        </td>
                                        <td id="tdReaderFloor-{{ $reader->id }}"d>
                                            @if(empty($reader->floor))
                                                <font color='gray'><em>Not Assigned</em></font>
                                            @else
                                                {{ $reader->floor->number }}
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

@section('script')
<script type="text/javascript">
    $(function () {
        /* Initiate dataTable */
        $('#readerTable').DataTable({
            order: [[1, 'asc']],
        })
    })
</script>
@endsection