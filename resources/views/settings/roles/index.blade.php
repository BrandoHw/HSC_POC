<div class="iq-card">
    <div class="iq-card-body">
        <p><i class="ri-information-fill"></i> Only allow a maximum of 20 roles in this system.</p>
        <div class="iq-search-bar row justify-content-between">
            <form action="#" class="searchbox">
                <input type="text" id="roleCustomSearchBox" class="text search-input" placeholder="Type here to search...">
                <a class="search-link" href="#"><i class="ri-search-line"></i></a>
            </form>
            <div class="col-4 row justify-content-end">
                @can('role-create')
                <a class="btn btn-primary" href="{{ route('roles.create') }}" style="margin-right: 10px;" {{ ($roles_count < 20) ? '':'hidden' }}><i class="ri-add-line"></i>Add Role</a>
                @endcan
                @can('role-delete')
                <a class="btn btn-danger" href="#" id="deleteRole">Delete</a>
                @endcan
            </div>
        </div>
        <div class="table-responsive" style="margin-top: 15px">
            <table class="table table-stripe table-bordered hover" id="roleTable">
                <thead>
                    <tr>
                        <th scope="col" style="width:10%">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Color</th>
                        <th scope="col">Permissions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($roles as $role)
                        <tr id="role-{{ $role->id }}" href="{{ route('roles.edit',$role->id) }}">
                            <td>{{ $role->id }}</td>
                            <td class="info-role">{{ $role->name }}</td>
                            <td class="info-role"><span class="badge badge-dark" style="background-color: {{ $role->color->color_code ?? 'None' }} !important">{{ $role->color->color_name ?? '-' }}</span></td>
                            <td class="info-role">
                                @if(!empty($rolePermissions))
                                    {{ $rolePermissions->where("role_id", $role->id)->count() }}
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table> 
        </div>
    </div>
    @can('role-delete')
    <!-- Role Delete: Empty -->
    <div class="modal fade" id="role-empty-modal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body" style="margin-left: -15px; margin-right: -15px; margin-top: -15px">
                    <div class="container-fluid bd-example-row">
                        <div class="row justify-content-center iq-bg-primary">
                            <i class="ri-error-warning-fill text-primary" style="font-size: 85px; margin: -15px"></i>
                        </div>
                        <div class="row mt-3 justify-content-center mt-2">
                            <div class="h4 font-weight-bold">No role selected!</div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="">Select at least one role to delete.</div>
                        </div>
                        <div class="row mt-5 justify-content-center">
                            <button type="button" class="btn btn-secondary m-1" data-dismiss="modal">Dismiss</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Role Delete: Confirmation for 1 -->
    <div class="modal fade" id="role-confirmation-modal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body" style="margin-left: -15px; margin-right: -15px; margin-top: -15px">
                    <div class="container-fluid bd-example-row">
                        <div class="row justify-content-center iq-bg-danger">
                            <i class="ri-error-warning-fill text-danger" style="font-size: 85px; margin: -15px"></i>
                        </div>
                        <div class="row mt-3 justify-content-center mt-2">
                            <div class="h4 font-weight-bold">Delete this role?</div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="">You will not be able to recover it.</div>
                        </div>
                        <div class="row mt-5 justify-content-center">
                            <button type="button" class="btn btn-secondary m-1" id="role-cancel-btn" data-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-danger m-1" id="role-delete-btn" onClick="confirmDeleteRole(this.id)">Yes, delete it</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Role Delete: Confirmation for multiple-->
    <div class="modal fade" id="role-confirmation-multiple-modal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body" style="margin-left: -15px; margin-right: -15px; margin-top: -15px">
                    <div class="container-fluid bd-example-row">
                        <div class="row justify-content-center iq-bg-danger">
                            <i class="ri-error-warning-fill text-danger" style="font-size: 85px; margin: -15px"></i>
                        </div>
                        <div class="row mt-3 justify-content-center mt-2">
                            <div class="h4 font-weight-bold">Delete these roles?</div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="">You will not be able to recover them.</div>
                        </div>
                        <div class="row mt-5 justify-content-center">
                            <button type="button" class="btn btn-secondary m-1" id="role-cancel-multiple-btn" data-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-danger m-1" id="role-delete-multiple-btn" onClick="confirmDeleteRole(this.id)">Yes, delete them</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endcan
</div>