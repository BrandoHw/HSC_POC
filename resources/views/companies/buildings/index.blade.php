<script
  src="https://code.jquery.com/jquery-3.5.1.min.js"
  integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
  crossorigin="anonymous"></script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
<script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
 
{{-- Not supposed to include jQuery as it should be covered by @extends('layouts.app')
    in companies.show.blade, but for some reason this blade does not have access to jQuery
    and I didn't have the time to figure out why --}}
@if(session()->has('success'))

<script>
   const notyf2 = new Notyf();
    const notification = notyf2.success('Floor plan successfully uploaded.');
</script>
@endif

<div class="card">
    <div class="card-header">
        <h3 class="card-title"><strong>Buildings Management</strong></h3>
        <h6 class="card-subtitle text-muted">Showing buildings that belong to this client.</h6>
    </div>
    <div class="card-body">
        <table class="table table-striped table-sm" id="companyBuildingTable">
            <thead>
                <tr>
                    <th scope="col" style="width:5%">#</th>
                    <th scope="col" style="width:15%">Name</th>
                    <th scope="col" style="width:45%">Address</th>
                    <th scope="col" style="width:20%">Floor Number</th>
                    <th scope="col" style="width:15%" class="noSort">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($company->buildings as $building)
                    <tr id="trBuilding-{{ $building->id }}">
                        <td>{{ $building->id }}</td>
                        <td id="tdBuildingName-{{ $building->id }}">
                            <a data-toggle="modal" data-target="#building-{{$building->id}}">{{$building->name}}</a>
        
                            <!--.modal -->
                            <div id="building-{{$building->id}}" class="modal fade modal-scroll" tabindex="-1" data-replace="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Upload <strong>New</strong> Floor Plan For <strong>{{$building->name}}</strong></h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                        </div>
                    
                                        {!! Form::open(['url' => '/file-upload', 'method' => 'post', 'enctype'=>  'multipart/form-data']) !!}
                                        {{ csrf_field() }}
                                        @method('post')

                                            <input type="hidden" id="buildingId-{{$building->id}}" name="building_id-{{$building->id}}" value="{{$building->id}}">
                                            <div class="col-xs-12 col-sm-12 col-md-12">
                                                <div class="form-group">
                                                    <div class="col-sm-7" style="margin-top: 20px; margin-bottom: 15px">
                                                        <label>
                                                            Floor
                                                            <span style="color: red; display:block; float:right;"> *</span>
                                                        </label>
                                                        <div>
                                                            <select id='selectFloor-{{$building->id}}' class="form-control" name='selectFloor-{{$building->id}}' style='height: 200px; width: 295px;'></select>
                                                        </div>   
                                                    </div>

                                                    <label class="col-sm-2 col-form-label">{{ __('Alias') }}</label>
                                                    <div class="col-sm-7">
                                                        <div class="form-group{{ $errors->has('alias') ? ' has-danger' : '' }}">
                                                            <input class="form-control{{ $errors->has('alias') ? ' is-invalid' : '' }}"
                                                            name="alias-{{$building->id}}" id="alias-{{$building->id}}" type="text" placeholder="{{ __('Name') }}"
                                                            value="" required="true" aria-required="true"
                                                            />
                                                        </div>
                                                    </div>

                                                    <label for="image" class="col-sm-6 col-form-label">Floor Plan Image</label>
                                                    <div class="col-sm-7">
                                                        <input id="image-{{$building->id}}" type="file" class="form-control" name="image-{{$building->id}}">
                                                        <img src="#" alt = "" id="img-tag-{{$building->id}}" width="200px" />   <!--for preview purpose -->
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="building-{{$building->id}}">Cancel</button>
                                                        <button type="submit" id="submit-{{$building->id}}" class="btn btn-primary">Upload File</button>
                                                    </div>
                                
                                                </div>
                                                {{ Form::close() }}
                                                <script>
                                                    $(function(){
                                                        var building = <?php echo $building; ?>;
                                                        var floors = <?php echo $floors ?>;
                                                        selectData = $.map(floors, function (obj) {
                                                            obj.text = obj.text || obj.number;  
                                                            return obj;
                                                        });
                                                        console.log(building);
                                                        console.log(floors.filter(floor => floor.building_id == building.id));
                                                        $('#selectFloor-{{ $building->id }}').select2({
                                                            width: 'resolve',
                                                            placeholder: 'Please select a floor...',
                                                            data: selectData.filter(floor => floor.building_id == building.id),
                                                        });
                                                        
                                                        $('#selectFloor-{{ $building->id }}').change(function(){
                                                            var building_id = {{ $building->id }};
                                                            floor_alias = $('#selectFloor-{{ $building->id }}').select2('data')[0]["alias"];
                                                            console.log(floor_alias);
                                                            if (!floor_alias){
                                                             console.log("null");
                                                            }
                                                            document.getElementById('alias-{{ $building->id }}').value = floor_alias;
                                                        });
                                                       
                                                        function readURL(input) {
                                                            if (input.files && input.files[0]) {
                                                                var reader = new FileReader();

                                                                reader.onload = function (e) {
                                                                    $('#img-tag-{{ $building->id }}').attr('src', e.target.result);
                                                                }

                                                                reader.readAsDataURL(input.files[0]);
                                                            }
                                                        }

                                                        $('#image-{{ $building->id }}').change(function(){
                                                            readURL(this);
                                                        });
                                                    })
                                                </script>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.modal -->
                        </td>
                        <td id="tdBuildingAddress-{{ $building->id }}">{{ $building->address }}</td>
                        <td id="tdBuildingFloor-{{ $building->id }}">{{ $building->floors()->count() }}</td>
                        <td id="tdBuildingAction-{{ $building->id }}" class="table-action" style="margin:0px">
                            <a href="{{route('map.show', ['map' => $building->id])}}" id="{{ $building->id }}">
                                @svg("map", "feather-map align-middle")
                            </a>
                            <a href="#" id="{{ $building->id }}" onClick="editBuilding(this.id)">
                                @svg('edit-2', 'feather-edit-2 align-middle')  
                            </a>
                            <a href="#" id="{{ $building->id }}" onClick="deleteBuilding(this.id)">
                                @svg("trash", "feather-trash align-middle")
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
