@extends('layouts.app')

@section('content')
<script src="{{ asset('js/notyf.js') }}"></script>
<div class="container-fluid p-0">
    <!-- Display alert -->
    @if ($message = Session::get('success'))

        <script>
             var notyf2 = new Notyf({
                position:{
                    x: 'center',
                    y: 'top',
                },
            });
            
            const notification = notyf2.success("Floor added");
         </script>

    @endif
    @if ($message = Session::get('failure'))

        <script>
            var notyf2 = new Notyf({
                position:{
                    x: 'center',
                    y: 'top',
                },
            });
            
            const notification = notyf2.error("Failed to add floor");
        </script>

    @endif
    <!-- Title & Add-Button -->
    <div class="row mb-2 mb-xl-3">
        <div class="col-auto d-none d-sm-block">
            <h3><strong>Floors</strong> Management</h3>
        </div>
        <div class="col-auto ml-auto text-right mt-n1">
            @can('floor-create')
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createFloorModal">
                    @svg('plus', 'feather-plus align-middle')  
                    <span class="align-middle">Add Floor</span>
                </button>

            @endcan 
        </div>
	</div>
    

    <!-- Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover nowrap" id="floorTable">
                            <thead>
                                <tr>
                                    <th scope="col" style="width:20%">Floor Number</th>
                                    <th scope="col" style="width:30%">Alias</th>
                                    <th scope="col" style="width:45%">Floor Plan</th>
                                    <th scope="col" class="noSort">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($floors as $floor)
                                    <tr id="trFloor-{{ $floor->id }}">
                                        <td id="tdFloorNumber-{{ $floor->id }}">
                                                {{ $floor->number }} 
                                        </td>
                                        <td id="tdFloorAlias-{{ $floor->id }}">
                                            @if(empty($floor->alias))
                                                <font color='gray'><em>Not Assigned</em></font>
                                            @else
                                                {{ $floor->alias }}
                                            @endif
                                        </td>
                                        <td id="tdFloorPlan-{{ $floor->id }}"d>
                                            @if(empty($floor->map))
                                                <font color='gray'><em>Not Assigned</em></font>
                                            @else
                                                <div>
                                                    <a class="image_holder" href="#" data-image={{$floor->map->url}}>{{$floor->map->url}}</a>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="table-action row" style="margin:0px">
                                            @can('floor-edit')
                                                <a href="{{ route('floors.edit',$floor->id) }}">
                                                    @svg('edit-2', 'feather-edit-2 align-middle')  
                                                </a>
                                            @endcan
                                            @can('floor-delete')
                                                <a href="#">
                                                    @svg("trash", "feather-trash align-middle")
                                                </a>
                                            @endcan 
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
   <!-- Create Floor Modal -->
   <div class="modal fade" id="createFloorModal" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            @include('floors.create')
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

    //Preview Floor Plan
    $(".image_holder").mouseenter(function(){
        if ($(this).parent('div').children('div.image').length) {
            $(this).parent('div').children('div.image').show();
        } else {
            var image_name=$(this).data('image');
            var imageTag='<div class="image" style="position:absolute;">'+'<img src="'+image_name+'" alt="image" height="300" />'+'</div>';
            $(this).parent('div').append(imageTag);
        }
    });

    $(".image_holder").mouseleave(function(){
        $(this).parent('div').children('div.image').hide();
    });

    //Create Floor modal
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#img-preview').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    $('#image-input').change(function(){
        readURL(this);
    });
</script>
@endsection