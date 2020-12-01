@extends('layouts.app')

@section('content')
<div class="container-fluid p-0">
    <!-- Display alert -->
    @if ($message = Session::get('success'))

        <div class="alert alert-success alert-dismissible" reader="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <div class="alert-message">
                <strong>Hello there!</strong> {{ $message }}
            </div>
        </div>
    @endif

    <!-- Title & Add-Button -->
    <div class="row mb-2 mb-xl-3">
        <div class="col-auto d-none d-sm-block">
            <h3><strong>Readers</strong> Management</h3>
        </div>
        <div class="col-auto ml-auto text-right mt-n1">
            <!-- @can('reader-create')
                <a class="btn btn-primary" href="{{ route('readers.create') }}">
                    @svg('plus', 'feather-plus align-middle')  
                    <span class="align-middle">Add reader</span>
                </a>
            @endcan -->
        </div>
	</div>
    
    <!-- Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover nowrap" id="readerTable">
                            <thead>
                                <tr>
                                    <th scope="col" style="width:5%">#</th>
                                    <th scope="col" style="width:25%">ID</th>
                                    <th scope="col" style="width:25%">M.A.C</th>
                                    <th scope="col" style="width:15%">Building</th>
                                    <th scope="col" style="width:20%">Floor</th>
                                    <th scope="col" class="noSort">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($readers as $reader)
                                    <tr id="trReader-{{ $reader->id }}">
                                        <td>{{ $reader->id }}</td>
                                        <td id="tdReaderSerial-{{ $reader->id }}">
                                            <a href="{{ route('readers.show',$reader->id) }}">
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
                                        <td class="table-action row" style="margin:0px">
                                            <!-- @can('reader-edit')
                                                <a href="{{ route('readers.edit',$reader->id) }}">
                                                    @svg('edit-2', 'feather-edit-2 align-middle')  
                                                </a>
                                            @endcan
                                            @can('reader-delete')
                                                <a href="#">
                                                    @svg("trash", "feather-trash align-middle")
                                                </a>
                                            @endcan -->
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
        /* Initiate tooltip */
        $('[data-toggle="tooltip"]').tooltip()

        /* Initiate popover */
        $('[data-toggle="popover"]').popover()

        /* Initiate dataTable */
        $('#readerTable').DataTable({
            dom: '<fl<t>ip>',
            responsive: true,
            stateSave: true,
            'lengthMenu': [[10, 25, 50, -1], [10, 25, 50, "All"]],
            orders: [],
            "columnDefs": [ {
                "targets"  : 'noSort',
                "orderable": false,
            }]
        })
    })
</script>
@endsection