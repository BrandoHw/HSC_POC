<div class="card">
    <div class="card-header">
        <h3 class="card-title"><strong>Users Management</strong></h3>
        <h6 class="card-subtitle text-muted">Showing users that are assigned to this group.</h6>
    </div>
    <div class="card-body">
        <table class="table table-striped table-sm" id="groupUserTable">
            <thead>
                <tr>
                    <th scope="col" style="width:5%">#</th>
                    <th scope="col" style="width:25%">Name</th>
                    <th scope="col" style="width:20%">Role</th>
                    <th scope="col" style="width:25%">Tag</th>
                    <th class="align-middle noSort" scope="col" style="width:10%">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($group->users as $user)
                    <tr id="trUser-{{ $user->id }}">
                        <td>{{ $user->id }}</td>
                        <td id="tdUserName-{{ $user->id }}">
                            <a href="#" id="{{ $user->id }}">
                                {{ $user->name }} 
                            </a>
                        </td>
                        <td id="tdUserRole-{{ $user->id }}">
                            <span class="badge badge-pill badge-dark" style="background-color: {{ $user->roles[0]->color }}">{{ $user->getRoleNames()[0] }}</span>
                        </td>
                        <td id="tdUserTag-{{ $user->id }}">{{ $user->tag->serial ?? 'not assigned' }}</td>
                        <td id="tdUserAction-{{ $user->id }}" class="table-action" style="margin:0px">
                            <a href="#" id="{{ $user->id }}" onClick="editUser(this.id)">
                                @svg('edit-2', 'feather-edit-2 align-middle')  
                            </a>
                            <a href="#" id="{{ $user->id }}" onClick="deleteUser(this.id)">
                                @svg("trash", "feather-trash align-middle")
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>