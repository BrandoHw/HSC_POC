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
<<<<<<< HEAD
                                    <th scope="col" style="width:10%">#</th>
                                    <th scope="col" style="width:25%">ID</th>
                                    <th scope="col" style="width:25%">M.A.C</th>
                                    <th scope="col" style="width:20%">Floor</th>
                                    <th scope="col" style="width:20%">Zone</th>
=======
                                    <th scope="col" style="width:5%">#</th>
                                    <th scope="col" style="width:30%">ID</th>
                                    <th scope="col" style="width:30%">MAC</th>
                                    <th scope="col" style="width:25%">Floor</th>
                                    <th scope="col" class="noSort">Actions</th>
>>>>>>> cc5e049255bdcd02557b32a4116a603b3f8644c7
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($readers as $reader)
                                    <tr id="trReader-{{ $reader->id }}">
                                        <td>{{ $reader->id }}</td>
                                        <td id="tdReaderSerial-{{ $reader->id }}">
<<<<<<< HEAD
                                            <a href="{{ route('readers.edit',$reader->id) }}">
=======
                                            <a href="{{ route('gateways.show',$reader->id) }}">
>>>>>>> cc5e049255bdcd02557b32a4116a603b3f8644c7
                                                {{ $reader->serial }} 
                                            </a>
                                        </td>
                                        <td id="tdReaderMacAdd-{{ $reader->id }}">
                                            {{ $reader->mac_addr }}
                                        </td>
                                        <td id="tdReaderFloor-{{ $reader->id }}"d>
                                            @if(empty($reader->floor))
                                                <font color='gray'><em>Not Assigned</em></font>
                                            @else
                                                {{ $reader->floor->number }}
                                            @endif
                                        </td>
<<<<<<< HEAD
=======
                                        <td class="table-action row" style="margin:0px">
                                            @can('reader-edit')
                                                <a href="{{ route('gateways.edit',$reader->id) }}">
                                                    @svg('edit-2', 'feather-edit-2 align-middle')  
                                                </a>
                                            @endcan
                                            @can('reader-delete')
                                                <a href="#">
                                                    @svg("trash", "feather-trash align-middle")
                                                </a>
                                            @endcan 
                                        </td>
>>>>>>> cc5e049255bdcd02557b32a4116a603b3f8644c7
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
   <!-- Create Reader Modal -->
   <div class="modal fade" id="createReaderModal" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            @include('readers.create')
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