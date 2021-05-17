@extends('layouts.app')

@section('content')
<script src="{{ asset('js/notyf.js') }}"></script>
{{-- <script src="{{ asset('js/jquery.js') }}"></script> --}}

    <!-- Display alert -->
    @if ($message = Session::get('success-update'))

    <script>
         var notyf2 = new Notyf({
            position:{
                x: 'center',
                y: 'top',
            },
        });
        
        const notification = notyf2.success("Floor Updated");
     </script>

    @endif

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
    @if ($message = Session::get('success-destroy'))

        <script>
            var notyf2 = new Notyf({
                position:{
                    x: 'center',
                    y: 'top',
                },
            });
            
            const notification = notyf2.success("Floor deleted");
        </script>

    @endif
    @if ($message = Session::get('failure'))

        <script>
            
            var notyf2 = new Notyf({
                position:{
                    x: 'center',
                    y: 'top',
                    duration: 4000,
                },
            });
            
            const notification = notyf2.error("There was an error with your operation");
        </script>

    @endif
  
    
    <!-- Table -->
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
                        <div class="col-4 row justify-content-end mb-3">
                            @can('floor-create')
                                <a class="btn btn-primary aligh" href="#" id="createFloorButton"><i class="ri-add-line"></i>Add Floor</a>
                            @endcan 
                        </div>
                    </div>
                    <div class="table-responsive" style="margin-top: 15px">
                        <table class="table table-stripe table-bordered hover" id="floorTable">
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
                                                <a data-toggle="modal" style="cursor:pointer" data-target="#editFloorModal-{{$floor->id}}">
                                                    @svg('edit-2', 'feather-edit-2 align-middle')  
                                                </a>
                                            @endcan
                                            @can('floor-delete')
                                                <a href="{{ route('floor.destroy',$floor->id) }}" class="confirmation">
                                                    @svg("trash", "feather-trash align-middle")
                                                </a>
                                            @endcan 
                                         
                                            <div  class="modal fade modal-scroll" id="editFloorModal-{{ $floor->id }}" data-replace="true" tabindex="-1" data-backdrop="false">
                                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                                    <div class="modal-content">

                                                        <!-- Title -->
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Edit Floor</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">×</span>
                                                            </button>
                                                        </div>
                                                    
                                                        <!-- Form -->
                                                        {!! Form::open(['route' => array('floors.update', $floor->id), 'method' => 'put', 'enctype'=>  'multipart/form-data']) !!}
                                                        {{ csrf_field() }}
                                                        @method('put')
                                                    
                                                            <div class="col-xs-12 col-sm-12 col-md-12">
                                                                <div class="form-group">
                                                    
                                                                    <div class="col-sm-7">
                                                                        <div class="form-group{{ $errors->has('number') ? ' has-danger' : '' }}">
                                                                            <input class="form-control{{ $errors->has('number') ? ' is-invalid' : '' }}"
                                                                            name="number" id="number-edit-{{ $floor->id }}" type="number" placeholder="{{ __('Floor Number') }}"
                                                                            value="{{ $floor->number }}" required="true" aria-required="true" min="0"
                                                                            />
                                                                        </div>
                                                                    </div>
                                                    
                                                                    <label class="col-sm-2 col-form-label">{{ __('Alias') }}</label>
                                                                    <div class="col-sm-7">
                                                                        <div class="form-group{{ $errors->has('alias') ? ' has-danger' : '' }}">
                                                                            <input class="form-control{{ $errors->has('alias') ? ' is-invalid' : '' }}"
                                                                            name="alias" id="alias-edit-{{ $floor->id }}" type="text" placeholder="{{ __('Alias') }}"
                                                                            value="{{ $floor->alias }}" required="true" aria-required="true"
                                                                            />
                                                                        </div>
                                                                    </div>
                                                                    <label for="image-input" class="col-sm-6 col-form-label">Floor Plan Image</label>
                                                                    <div class="col-sm-7">
                                                                        <input id="image-input-edit-{{ $floor->id }}" type="file" class="form-control" name="image-input">
                                                                        <img src="#" alt = "" id="img-preview-edit-{{ $floor->id }}" width="200px" />   <!--for preview purpose -->
                                                                    </div>
                                                    
                                                                    <div class="modal-footer">
                                                                        <button type="submit" id="submit-edit-{{ $floor->id }}" class="btn btn-primary">Update Floor</button>
                                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal" >Cancel</button>
                                                                    </div>
                                                    
                                                                </div>
                                                        {{ Form::close() }}
                                                    </div>    
                                                    <script>
                                                        $(function(){
                                                            function readURL(input) {
                                                                if (input.files && input.files[0]) {
                                                                    var reader = new FileReader();
    
                                                                    reader.onload = function (e) {
                                                                        $('#img-preview-edit-{{ $floor->id }}').attr('src', e.target.result);
                                                                    }
    
                                                                    reader.readAsDataURL(input.files[0]);
                                                                }
                                                            }
    
                                                            $('#image-input-edit-{{ $floor->id }}').change(function(){
                                                                readURL(this);
                                                            });
                                                        })
                                                    </script>                                                
                                                </div>
                                            </div>
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
   <!-- Create Floor Modal -->
   <div class="modal fade" id="createFloorModal" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            @include('floors.create')
        </div>
    </div>

@endsection

@section('script')

<script>

    $(function () {

        /* Initiate dataTable */
    var dTable = $('#floorTable').DataTable({
            'lengthMenu': [[10, 25, 50, -1], [10, 25, 50, "All"]],
            orders: [],
            "columnDefs": [ {
                "targets"  : 'noSort',
                "orderable": false,
            }]
        })

        $( "#createFloorButton" ).click(function() {
            $('#createFloorModal').modal('show');
        });

    $('#myCustomSearchBox').keyup(function(){  
        dTable.search($(this).val()).draw();   // this  is for customized searchbox with datatable search feature.
    })

    $('#submit').on('click', function(){
        $('#loading-indicator').show();
        $('#submit').hide();
    });

    //Preview Floor Plan in datatable
    $(".image_holder").mouseenter(function(){
        if ($(this).parent('div').children('div.image').length) {
            $(this).parent('div').children('div.image').show();
        } else {
            var image_name=$(this).data('image');
            var imageTag='<div class="image" style="position:relative;left: 50%;transform: translateX(-50%);">'+'<img src="'+image_name+'" alt="image" height="300" />'+'</div>';
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

    //Confirmation Dialog
    var elems = document.getElementsByClassName('confirmation');
    var confirmIt = function (e) {
        if (!confirm('Confirm Deletion')) e.preventDefault();
    };
    for (var i = 0, l = elems.length; i < l; i++) {
        elems[i].addEventListener('click', confirmIt, false);
    }
})
    
</script>
@endsection