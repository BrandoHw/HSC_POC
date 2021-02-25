@extends('layouts.app')

@section('content')
<div class="container-fluid relative">
    <div class="row">
        <div class="col-lg-3">
            <div class="iq-card">
                <div class="iq-card-body">
                    <div class="">
                        <div class="iq-email-list">
                            <button class="btn btn-primary btn-lg btn-block mb-3 font-size-16 p-3" data-target="#compose-email-popup" data-toggle="modal"><i class="ri-send-plane-line mr-2"></i>New Message</button>
                            <div class="iq-email-ui nav flex-column nav-pills">
                                <li class="nav-link active" role="tab" data-toggle="pill" href="#mail-inbox"><a href="index.html"><i class="ri-mail-line"></i>Inbox<span class="badge badge-primary ml-2">4</span></a></li>
                                <li class="nav-link" role="tab" data-toggle="pill" href="#mail-starred"><a href="#"><i class="ri-star-line"></i>Starred</a></li>
                                <li class="nav-link" role="tab" data-toggle="pill" href="#mail-snoozed"><a href="#"><i class="ri-time-line"></i>Snoozed</a></li>
                                <li class="nav-link" role="tab" data-toggle="pill" href="#mail-draft"><a href="#"><i class="ri-file-list-2-line"></i>Draft</a></li>
                                <li class="nav-link" role="tab" data-toggle="pill" href="#mail-sent"><a href="#"><i class="ri-mail-send-line"></i>Sent Mail</a></li>
                                <li class="nav-link" role="tab" data-toggle="pill" href="#mail-trash"><a href="#"><i class="ri-delete-bin-line"></i>Trash</a></li>
                                <li class="nav-link" role="tab" data-toggle="pill" href="#mail-important"><a href="#"><i class="ri-bookmark-line"></i>Important</a></li>
                                <li class="nav-link" role="tab" data-toggle="pill" href="#mail-spam"><a href="#"><i class="ri-spam-line"></i>Spam</a></li>
                            </div>
                            <h6 class="mt-4 mb-3">Labels</h6>
                            <ul class="iq-email-ui iq-email-label">
                                <li><a href="#"><i class="ri-focus-fill text-primary"></i>Personal</a></li>
                                <li><a href="#"><i class="ri-focus-fill text-danger"></i>Company</a></li>
                                <li><a href="#"><i class="ri-focus-fill text-success"></i>Important</a></li>
                                <li><a href="#"><i class="ri-focus-fill text-info"></i>Private</a></li>
                                <li><a href="#"><i class="ri-focus-fill text-warning"></i>Private</a></li>
                                <li><a href="#"><i class="ri-add-circle-line"></i>Add New Labels</a></li>
                            </ul>
                            <div class="iq-progress-bar-linear d-inline-block w-100">
                                <h6 class="mt-4 mb-3">Storage</h6>
                                <div class="iq-progress-bar">
                                <span class="bg-danger" data-percent="90"></span>
                                </div>
                                <span class="mt-1 d-inline-block w-100">7.02 GB (46%) of 15 GB used</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-9 setting-box-detail"></div>
        </div>
    </div>
</div>
@endsection 