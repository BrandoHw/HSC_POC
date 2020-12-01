<div class="card">
    <div class="card-header">
        <h3 class="card-title"><strong>Schedule Management</strong></h3>
        <h6 class="card-subtitle text-muted">Showing this group's schedule.</h6>
    </div>
    <div class="card-body">
        <!-- Display alert -->
        @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible" schedule="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div class="alert-message">
                    <strong>Success!</strong> {{ $message }}
                </div>
            </div>
            <script>
                notyf.success('{{ $message }}')
            </script>
        @endif

        <table class="table table-bordered" style="width: 100%">
            <colgroup>
                <col span="1" style="width: 23%;">
                <col span="1" style="width: 11%;">
                <col span="1" style="width: 11%;">
                <col span="1" style="width: 11%;">
                <col span="1" style="width: 11%;">
                <col span="1" style="width: 11%;">
                <col span="1" style="width: 11%;">
                <col span="1" style="width: 11%;">
            </colgroup>
            <thead>
                <th>Time</th>
                @foreach($weekDays as $day)
                    <th>{{ $day }}</th>
                @endforeach
            </thead>
            <tbody>
                @foreach($calendarData as $time => $days) 
                    <tr style = "padding: 0px; margin: 0px;">
                        <td
                            font-variant-numeric: normal;
                            style = "text-align:center; padding: 0px; margin: 0px">
                            {{ $time }}
                        </td>
                        @foreach($days as $value)
                            @if (is_array($value))
                                @if (count($value) >= 6)
                                    <td rowspan="{{ $value['rowspan'] }}" class="align-middle text-center" 
                                        style="background-color:#90ee90; padding: 0px; margin: 0px" >
                                        <button type="button" class="btn btn-primary" style="background-color: #90ee90; border: none; font-size:10px; color:black" data-toggle="modal" 
                                        data-target="#timeblock-modal-{{ $value['day'].$value['i']}}">
                                            <strong>{{ $value['company']->name}}:</strong>
                                            <br>
                                            {{ $value['building']->name}} 
                                        </button>
                                        <div class="modal fade" id="timeblock-modal-{{ $value['day'].$value['i']}}" tabindex="-1" role="dialog" aria-labelledby="timeblockModal" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="timeblockModal">Timeblock Information</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">×</span>
                                                        </button>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="form-group">
                                                                <div class="modal-body">
                                                                    <table class="table table-bordered table-striped">
                                                                        <tbody>                                                                		
                                                                            <tr>
                                                                                <th>
                                                                                    Schedule ID
                                                                                </th>
                                                                                <td>
                                                                                    {{ $value['schedule_id'] }}
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>
                                                                                    Company
                                                                                </th>
                                                                                <td>
                                                                                    {{ $value['company']->name }}
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>
                                                                                    Building
                                                                                </th>
                                                                                <td>
                                                                                    {{ $value['building']->name }}
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>
                                                                                    Lcoation
                                                                                </th>
                                                                                <td>
                                                                                    {{ $value['building']->address }}
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>
                                                                                    Day
                                                                                </th>
                                                                                <td>
                                                                                    {{ $value['day'] }}
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>
                                                                                    Start Time
                                                                                </th>
                                                                                <td>
                                                                                    {{ $value['start_time'] }}
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>
                                                                                    End Time
                                                                                </th>
                                                                                <td>
                                                                                    {{ $value['end_time'] }}
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button onclick="deleteTimeblock(this.id)" name='delete-form' id="deleteTimeblock-{{ $value['id'] }}" value='delete' class="btn btn-danger">
                                                                        @svg('trash', 'feather-trash align-middle') Delete
                                                                    </button>
                                                                <script type="text/javascript">
                                                                function form_submit(id) {
                                                                    // console.log("delete-form-".concat(id));
                                                                    // document.getElementById("delete-form-".concat(id)).submit();
                                                                }    
                                                                </script>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <br>
                                    </td>
                                @elseif (count($value) === 5)
                                    <td style = "overflow:hidden;white-space:nowrap; height: 100%; padding: 0px; margin: 0px;">
                                    
                                        <button type="button" class="btn btn-primary" style="background-color: #FFFFFF; width: 100%; height: 100%; border: none" data-toggle="modal" 
                                            data-target="#empty-timeblock-modal-{{ $value['day'].$value['i']}}" >
                                            pad<br>
                                            pad
                                        </button>
                                

                                        <div class="modal fade" id="empty-timeblock-modal-{{ $value['day'].$value['i']}}" tabindex="-1" role="dialog" aria-labelledby="emptyTimeblockModal" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="emptyTimeblockModal">Add Timeblock</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">×</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body m-3">
                                                        <div class="col-xs-12 col-sm-12 col-md-12">
                                                            <div class="form-group">
                                                                <strong>Schedule ID:</strong>
                                                                <input type="text" name="schedule_id" value="{{$value['schedule_id']}}" class="form-control" id="createSchedule-{{ $value['day'].$value['i'] }}" placeholder="schedule_id" readonly>
                                                            </div>
                                                        </div>

                                                        <div class="col-xs-12 col-sm-12 col-md-12">
                                                            <div class="form-group">
                                                                <strong>Day: {{$weekDays[$value['day']]}}</strong>
                                                                <input type="text" name="day" value="{{$value['day']}}" class="form-control" placeholder="day" id="createDay-{{ $value['day'].$value['i'] }}" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-12 col-sm-12 col-md-12">
                                                            <div class="form-group">
                                                                <label>
                                                                    Company
                                                                    <span style="color: red; display:block; float:right"> *</span>
                                                                </label>
                                                                <div>
                                                                    {!! Form::select('company_id', $companies, null, array('placeholder' => 'Please select a company...','class' => 'form-control', 'id' => 'createCompany-'.$value["day"].$value["i"])) !!}
                                                                </div>
                                                                <script>
                                                                    $(function(){
                                                                        $('#createCompany-{{ $value['day'].$value['i'] }}').select2({
                                                                            width: 'resolve',
                                                                            placeholder: 'Please select a company...',
                                                                        });
                                                                    })
                                                                </script>
                                                                @error('company_id')
                                                                    <!-- <script>
                                                                        $(function(){
                                                                            $('#empty-timeblock-modal-{{ $value["day"].$value["i"]}}').modal('show');
                                                                            $('#createCompany-'+@json($value["day"]) + @json($value["i"]))
                                                                                .find('span.select2-selection')
                                                                                .css("border", "1px solid red");
                                                                        })
                                                                    </script>
                                                                    <div class="alert-danger">{{ $message }}</div> -->
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="col-xs-12 col-sm-12 col-md-12">
                                                            <div class="form-group">
                                                                <label>
                                                                    Building
                                                                    <span style="color: red; display:block; float:right"> *</span>
                                                                </label>
                                                                <div>
                                                                    {!! Form::select('building_id', [], null, array('placeholder' => 'Please select a company first...','class' => 'form-control', 'id' => 'createBuilding-'.$value["day"].$value["i"])) !!}
                                                                </div>
                                                                <script>
                                                                    $(function() {
                                                                        $('#createBuilding-{{ $value["day"].$value["i"] }}').select2({
                                                                            placeholder: 'Please select a company first...',
                                                                        });

                                                                        $('#createCompany-{{ $value["day"].$value["i"] }}').change(function() {

                                                                            $.ajaxSetup({
                                                                                headers: {
                                                                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                                                                }
                                                                            });

                                                                            var data = {
                                                                                'companyId': $('#createCompany-{{ $value["day"].$value["i"] }}').val(),
                                                                            }

                                                                            $.ajax({
                                                                                url: '{{ route("groups.timeblocks.create", $group->id) }}',
                                                                                type: "GET",
                                                                                data: data,
                                                                                success:function(response){
                                                                                    console.log(response);
                                                                                    var buildings = response['buildings'];
                                                                                    $('#createBuilding-{{ $value["day"].$value["i"] }}').select2({
                                                                                        placeholder: 'Please select a building...',
                                                                                        data: buildings
                                                                                    });
                                                                                },
                                                                                error:function(error){
                                                                                    console.log(error);
                                                                                }
                                                                            })
                                                                        });
                                                                    });
                                                                </script>
                                                                @error('building_id')
                                                                    <!-- <script>
                                                                        $(function(){
                                                                            $('#empty-timeblock-modal-'+@json($value["day"]) + @json($value["i"])).modal('show');
                                                                            $('#createBuilding-'+@json($value["day"]) + @json($value["i"]))
                                                                                .find('span.select2-selection')
                                                                                .css("border", "1px solid red");
                                                                        })
                                                                    </script>
                                                                    <div class="alert-danger">{{ $message }}</div> -->
                                                                @enderror
                                                            </div>
                                                        </div>

                                                        <div class="col-xs-12 col-sm-12 col-md-4">
                                                            <div class="form-group">
                                                                <strong>Start Time:</strong>
                                                                <div class=timepicker id ="start{{ $value['day'].$value['i']}}">
                                                                <div class="input-group date" id="start-{{$value['i']}}" data-target-input="nearest">
                                                                    <input type="text" name="start_time" value="{{ $value['start_time']}}" id="createStartTime-{{ $value['day'].$value['i'] }}" class="form-control datetimepicker-input" data-target="#start-{{$value['i']}}"/>
                                                                    <div class="input-group-append" data-target="#start-{{ $value['i']}}" data-toggle="datetimepicker">
                                                                        <div class="input-group-text"><i class="fa fa-clock"></i></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="col-xs-12 col-sm-12 col-md-4">
                                                            <div class="form-group">
                                                                <strong>End Time:</strong>
                                                                <div class=timepicker id ="end{{ $value['day'].$value['i']}}">
                                                                <div class="input-group date" id="end-{{ $value['i']}}" data-target-input="nearest">
                                                                    <input type="text" name="end_time" value="{{ $value['end_time']}}" id="createEndTime-{{ $value['day'].$value['i'] }}" class="form-control datetimepicker-input" data-target="#end-{{$value['i']}}"/>
                                                                    <div class="input-group-append" data-target="#end-{{$value['i']}}" data-toggle="datetimepicker">
                                                                        <div class="input-group-text"><i class="fa fa-clock"></i></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button onclick="saveTimeblock(this.id)" id="timeblock-{{ $value['day'].$value['i'] }}" name='add-form' value='add' class="btn btn-danger">
                                                            @svg('plus', 'feather-plus align-middle') Add
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                @endif
                            @endif
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
