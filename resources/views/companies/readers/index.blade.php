<div class="card">
    <div class="card-header">
        <h3 class="card-title"><strong>Readers Management</strong></h3>
        <h6 class="card-subtitle text-muted">Showing readers that are assigned to this client.</h6>
    </div>
    <div class="card-body">
        <table class="table table-striped table-sm" id="companyReaderTable">
            <thead>
                <tr>
                    <th scope="col" style="width:5%">#</th>
                    <th scope="col" style="width:20%">Serial</th>
                    <th scope="col" style="width:20%">Mac Address</th>
                    <th scope="col" style="width:20%">Building</th>
                    <th scope="col" style="width:25%">Floor</th>
                    <th class="align-middle noSort" scope="col" style="width:10%">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($company->buildings as $building)
                    @foreach($building->readers as $reader)
                        <tr id="trReader-{{ $reader->id }}">
                            <td>
                                {{ $reader->id }}
                            </td>
                            <td id="tdReaderSerial-{{ $reader->id }}">
                                <a href="{{ route('readers.show',$reader->id) }}">
                                    {{ $reader->serial }} 
                                </a>
                            </td>
                            <td id="tdReaderMacAdd-{{ $reader->id }}">{{ $reader->mac_addr }}</td>
                            <td id="tdReaderBuilding-{{ $reader->id }}">{{ $reader->floor->building->name }}</td>
                            <td id="tdReaderFloor-{{ $reader->id }}">{{ $reader->floor->number }}</td>
                            <td id="tdReaderAction-{{ $reader->id }}" class="table-action" style="margin:0px">
                                <a href="#" id="{{ $reader->id }}" onClick="editReader(this.id)">
                                    @svg('edit-2', 'feather-edit-2 align-middle')  
                                </a>
                                <a href="#" id="{{ $reader->id }}" onClick="deleteReader(this.id)">
                                    @svg("trash", "feather-trash align-middle")
                                </a>
                            </td>

                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
</div>