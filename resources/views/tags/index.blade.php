@extends('layouts.app')

@section('content')
<div class="container-fluid p-0">
    <!-- Display alert -->
    @if ($message = Session::get('success'))

        <div class="alert alert-success alert-dismissible" tag="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <div class="alert-message">
                <strong>{{ $message }}</strong> 
            </div>
        </div>
    @endif

    <!-- Title & Add-Button -->
    <div class="row mb-2 mb-xl-3">
        <div class="col-auto d-none d-sm-block">
            <h3><strong>Beacons</strong> Management</h3>
        </div>
        <div class="col-auto ml-auto text-right mt-n1">
            @can('tag-create')
                <a class="btn btn-primary" href="{{ route('beacons.create') }}">
                    @svg('plus', 'feather-plus align-middle')  
                    <span class="align-middle">Add tag</span>
                </a>
            @endcan 
        </div>
	</div>
    
    <!-- Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover nowrap" id="tagTable">
                            <thead>
                                <tr>
                                    <th scope="col" style="width:5%">#</th>
                                    <th scope="col" style="width:25%">ID</th>
                                    <th scope="col" style="width:25%">Mac Address</th>
                                    <th scope="col" style="width:20%">User</th>
                                    <th scope="col" class="noSort">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tags as $tag)
                                    <tr>
                                        <td>{{ $tag->id }}</td>
                                        <td>
                                            <a href="{{ route('beacons.show',$tag->id) }}">
                                                {{ $tag->serial }} 
                                            </a>
                                        </td>
                                        <td>{{ $tag->mac_addr }} </td>
                                        <td>
                                            @if(empty($tag->user))
                                                <font color='gray'><em>Not Assigned</em></font>
                                            @else
                                                {{ $tag->user->name }}
                                            @endif
                                        </td>
                                        <td class="table-action">
                                            <!-- <form action="{{ route('beacons.destroy',$tag->id) }}" method="POST">
                                                @can('tag-edit')
                                                    <a href="{{ route('beacons.edit',$tag->id) }}">
                                                        @svg('edit-2', 'feather-edit-2 align-middle')
                                                    </a>
                                                @endcan
                                                @csrf
                                                @method('DELETE')
                                                @can('tag-delete')
                                                    <button type="submit">
                                                        @svg('trash', 'feather-trash align-middle')
                                                    </button>
                                                @endcan
                                            </form>
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
    $(function () {
        /* Initiate tooltip */
        $('[data-toggle="tooltip"]').tooltip()

        /* Initiate popover */
        $('[data-toggle="popover"]').popover()

        /* Initiate dataTable */
        $('#tagTable').DataTable({
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