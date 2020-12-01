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
                    <th scope="col" style="width:50%">Address</th>
                    <th scope="col" style="width:20%">Floor Number</th>
                    <th scope="col" style="width:10%" class="noSort">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($company->buildings as $building)
                    <tr id="trBuilding-{{ $building->id }}">
                        <td>{{ $building->id }}</td>
                        <td id="tdBuildingName-{{ $building->id }}">{{ $building->name }}</td>
                        <td id="tdBuildingAddress-{{ $building->id }}">{{ $building->address }}</td>
                        <td id="tdBuildingFloor-{{ $building->id }}">{{ $building->floors()->count() }}</td>
                        <td id="tdBuildingAction-{{ $building->id }}" class="table-action" style="margin:0px">
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