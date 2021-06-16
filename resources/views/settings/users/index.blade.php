<div class="iq-card">
    <div class="iq-card-body">
        <div class="iq-search-bar row justify-content-between">
            <form action="#" class="searchbox">
                <input type="text" id="userCustomSearchBox" class="text search-input" placeholder="Type here to search...">
                <a class="search-link" href="#"><i class="ri-search-line"></i></a>
            </form>
            <div class="col-4 row justify-content-end">
                @can('user-create')
                <a class="btn btn-primary" href="{{ route('users.create') }}" style="margin-right: 10px;"><i class="ri-add-line"></i>Add User</a>
                @endcan
                @can('user-delete')
                <a class="btn btn-danger" href="#" id="deleteUser">Delete</a>
                @endcan
            </div>
        </div>
        <div class="table-responsive" style="margin-top: 15px">
            <table class="table table-stripe table-bordered hover" id="userTable">
            <thead>
                    <tr>
                        <th scope="col" style="width:10%">#</th>
                        <th scope="col">First Name</th>
                        <th scope="col">Last Name</th>
                        <th scope="col">Role</th>
                        <th scope="col">Beacon</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr id="user-{{ $user->user_id }}" href="{{ route('users.edit',$user->user_id) }}">
                            <td>{{ $user->user_id }}</td>
                            <td class="info-user">{{ $user->fName }}</td>
                            <td class="info-user">{{ $user->lName }}</td>
                            <td class="info-user">
                                <span class="badge badge-dark" style="background-color: {{ $user->roles[0]->color->color_code }} !important">
                                    {{ $user->getRoleNames()[0] }}
                                </span>
                            </td>
                            <td class="info-user">
                                @if(isset($user->tag))
                                    <span {{ $user->tag->trashed() ? 'class=text-secondary style=font-style:italic':''}}>{{ $user->tag->beacon_mac }}
                                        @if($user->tag->trashed())
                                        <small><em>[Deleted]</em></small>
                                        @endif
                                    </span>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table> 
        </div>
    </div>
    @can('user-delete')
    <!-- User Delete: Empty -->
    <div class="modal fade" id="user-empty-modal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body" style="margin-left: -15px; margin-right: -15px; margin-top: -15px">
                    <div class="container-fluid bd-example-row">
                        <div class="row justify-content-center iq-bg-primary">
                            <i class="ri-error-warning-fill text-primary" style="font-size: 85px; margin: -15px"></i>
                        </div>
                        <div class="row mt-3 justify-content-center mt-2">
                            <div class="h4 font-weight-bold">No user selected!</div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="">Select at least one user to delete.</div>
                        </div>
                        <div class="row mt-5 justify-content-center">
                            <button type="button" class="btn btn-secondary m-1" data-dismiss="modal">Dismiss</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- User Delete: Confirmation for 1 -->
    <div class="modal fade" id="user-confirmation-modal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body" style="margin-left: -15px; margin-right: -15px; margin-top: -15px">
                    <div class="container-fluid bd-example-row">
                        <div class="row justify-content-center iq-bg-danger">
                            <i class="ri-error-warning-fill text-danger" style="font-size: 85px; margin: -15px"></i>
                        </div>
                        <div class="row mt-3 justify-content-center mt-2">
                            <div class="h4 font-weight-bold">Delete this user?</div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="">You will not be able to recover it.</div>
                        </div>
                        <div class="row mt-5 justify-content-center">
                            <button type="button" class="btn btn-secondary m-1" id="user-cancel-btn" data-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-danger m-1" id="user-delete-btn" onClick="confirmDeleteUser(this.id)">Yes, delete it</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- User Delete: Confirmation for multiple-->
    <div class="modal fade" id="user-confirmation-multiple-modal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body" style="margin-left: -15px; margin-right: -15px; margin-top: -15px">
                    <div class="container-fluid bd-example-row">
                        <div class="row justify-content-center iq-bg-danger">
                            <i class="ri-error-warning-fill text-danger" style="font-size: 85px; margin: -15px"></i>
                        </div>
                        <div class="row mt-3 justify-content-center mt-2">
                            <div class="h4 font-weight-bold">Delete these users?</div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="">You will not be able to recover them.</div>
                        </div>
                        <div class="row mt-5 justify-content-center">
                            <button type="button" class="btn btn-secondary m-1" id="user-cancel-multiple-btn" data-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-danger m-1" id="user-delete-multiple-btn" onClick="confirmDeleteUser(this.id)">Yes, delete them</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endcan
</div>