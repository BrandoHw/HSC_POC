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
                            <a class="btn btn-primary" href="{{ route('tags.create') }}" style="margin-right: 10px">Create</a>
                            <a class="btn btn-danger" href="#">Delete</a>
                        </div>
                    </div>
                    <div class="table-responsive" style="margin-top: 15px">
                        <table class="table table-stripe table-bordered hover" id="tagTable">
                        <thead>
                                <tr>
                                    <th scope="col" style="width:10%">#</th>
                                    <th scope="col" style="width:25%">ID</th>
                                    <th scope="col" style="width:25%">Mac Address</th>
                                    <th scope="col" style="width:40%">Resident</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tags as $tag)
                                    <tr>
                                        <td>{{ $tag->id }}</td>
                                        <td>
<<<<<<< HEAD
                                            <a href="{{ route('tags.edit',$tag->id) }}">
=======
                                            <a href="{{ route('beacons.show',$tag->id) }}">
>>>>>>> cc5e049255bdcd02557b32a4116a603b3f8644c7
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
<<<<<<< HEAD
=======
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
@endsection 

@section("script")
<script>
    $(function () {
        /* Initiate dataTable */
        $('#tagTable').DataTable({
            order: [[1, 'asc']],
        })
    })
</script>
@endsection