@extends('layouts.app')

@section('style')
<style>
    .carousel-indicators li{
        background-color: black !important;
    }
    ul.timeline {
    list-style-type: none;
    position: relative;
    }
    ul.timeline:before {
        content: ' ';
        background: #d4d9df;
        display: inline-block;
        position: absolute;
        left: 29px;
        width: 2px;
        height: 100%;
        z-index: 400;
    }
    ul.timeline > li {
        margin: 20px 0;
        padding-left: 20px;
    }
    ul.timeline > li:before {
        content: ' ';
        background: white;
        display: inline-block;
        position: absolute;
        border-radius: 50%;
        border: 3px solid #22c0e8;
        left: 20px;
        width: 20px;
        height: 20px;
        z-index: 400;
    }
</style>
@endsection

@section('content')
<div class="container-fluid p-0">

    <div class="row mb-2 mb-xl-3">
        <div class="col-auto d-none d-sm-block">
            <h3><strong>Dashboard</strong></h3>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-lg-8 col-xxl-12 d-flex">
            <div class="card flex-fill">
                <div class="card-header">
                    <h5 class="card-title">AMCharts</h5>
                </div>
                <div class="card-body">
                    <div id="chartdiv" style="width: 100%; height: 98vh;"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-lg-8 col-xxl-12 d-flex">
            <div class="card flex-fill">
                <div class="card-header">
                    <h5 class="card-title">FullCalendar</h5>
                    <h6 class="card-subtitle text-muted">Open source JavaScript plugin for a full-sized, drag &amp; drop event calendar.</h6>
                </div>
                <div class="card-body">
                    <div id="fullcalendar"></div>
                    <div id="listcalendar"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-lg-8 col-xxl-9">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Today's schedule</h5>
                    <ul class="nav nav-tabs card-header-tabs pull-right justify-content-end" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link" id="completed" data-toggle="tab" href="#completed-down, #completed-up" role="tab" aria-controls="completed-up completed-down" aria-selected="false">Completed</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="on-progress" data-toggle="tab" href="#on-progress-down, #on-progress-up" role="tab" aria-controls="on-progress-up on-progress-down" aria-selected="true">In progress</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="not-started" data-toggle="tab" href="#not-started-down, #not-started-up" role="tab" aria-controls="not-started-up not-started-down" aria-selected="false">No started</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane fade completed-tab-content" id="completed-down" role="tabpanel" aria-labelledby="completed">
                            <div class="chart w-100">
                                <div id="donutChart" style="max-width: 440px; margin: auto; min-heigh:327.px"></div>
                            </div>
                            <br>
                            <table class="table table-hover my-0">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th class="d-none d-xl-table-cell">Start Date</th>
                                        <th class="d-none d-xl-table-cell">End Date</th>
                                        <th>Status</th>
                                        <th class="d-none d-md-table-cell">Assignee</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Project Apollo</td>
                                        <td class="d-none d-xl-table-cell">01/01/2020</td>
                                        <td class="d-none d-xl-table-cell">31/06/2020</td>
                                        <td><span class="badge bg-success">Done</span></td>
                                        <td class="d-none d-md-table-cell">Vanessa Tucker</td>
                                    </tr>
                                    <tr>
                                        <td>Project Fireball</td>
                                        <td class="d-none d-xl-table-cell">01/01/2020</td>
                                        <td class="d-none d-xl-table-cell">31/06/2020</td>
                                        <td><span class="badge bg-danger">Cancelled</span></td>
                                        <td class="d-none d-md-table-cell">William Harris</td>
                                    </tr>
                                    <tr>
                                        <td>Project Hades</td>
                                        <td class="d-none d-xl-table-cell">01/01/2020</td>
                                        <td class="d-none d-xl-table-cell">31/06/2020</td>
                                        <td><span class="badge bg-success">Done</span></td>
                                        <td class="d-none d-md-table-cell">Sharon Lessman</td>
                                    </tr>
                                    <tr>
                                        <td>Project Nitro</td>
                                        <td class="d-none d-xl-table-cell">01/01/2020</td>
                                        <td class="d-none d-xl-table-cell">31/06/2020</td>
                                        <td><span class="badge bg-warning">In progress</span></td>
                                        <td class="d-none d-md-table-cell">Vanessa Tucker</td>
                                    </tr>
                                    <tr>
                                        <td>Project Phoenix</td>
                                        <td class="d-none d-xl-table-cell">01/01/2020</td>
                                        <td class="d-none d-xl-table-cell">31/06/2020</td>
                                        <td><span class="badge bg-success">Done</span></td>
                                        <td class="d-none d-md-table-cell">William Harris</td>
                                    </tr>
                                    <tr>
                                        <td>Project X</td>
                                        <td class="d-none d-xl-table-cell">01/01/2020</td>
                                        <td class="d-none d-xl-table-cell">31/06/2020</td>
                                        <td><span class="badge bg-success">Done</span></td>
                                        <td class="d-none d-md-table-cell">Sharon Lessman</td>
                                    </tr>
                                    <tr>
                                        <td>Project Romeo</td>
                                        <td class="d-none d-xl-table-cell">01/01/2020</td>
                                        <td class="d-none d-xl-table-cell">31/06/2020</td>
                                        <td><span class="badge bg-warning">In progress</span></td>
                                        <td class="d-none d-md-table-cell">Christina Mason</td>
                                    </tr>
                                    <tr>
                                        <td>Project Wombat</td>
                                        <td class="d-none d-xl-table-cell">01/01/2020</td>
                                        <td class="d-none d-xl-table-cell">31/06/2020</td>
                                        <td><span class="badge bg-danger">Cancelled</span></td>
                                        <td class="d-none d-md-table-cell">William Harris</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade active show on-progress-tab-content" id="on-progress-down" role="tabpanel" aria-labelledby="on-progress">
                            <div class='row'>
                                <div class="col">

                                </div>
                                <div id="carouselExampleIndicators" class="carousel slide col" data-ride="carousel">
                                    <ol class="carousel-indicators">
                                        <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                                        <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                                        <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                                    </ol>
                                    <div class="carousel-inner">
                                        <div class="carousel-item active align-content-center">
                                            <div class="chart-carousel align-content-center">
                                                <div id="donutChart1" class="align-middle" style="max-width: 300px; margin: auto; min-height: 352.7px;"></div>
                                            </div>
                                        </div>
                                        <div class="carousel-item">
                                            <div class="chart-carousel">
                                                <div id="donutChart2" style="max-width: 300px; margin: auto; min-height: 352.7px;"></div>
                                            </div>
                                        </div>
                                        <div class="carousel-item">
                                            <div class="chart-carousel">
                                                <div id="donutChart3" style="max-width: 300px; margin: auto; min-height: 352.7px;"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <a class="carousel-control-prev" style="color: black !important" href="#carouselExampleIndicators" role="button" data-slide="prev">
                                        <span>@svg('chevron-left', 'feather-chevron-left feather-carousel align-middle')</span>
                                        <span class="sr-only">Previous</span>
                                    </a>
                                    <a class="carousel-control-next" style="color: black !important" href="#carouselExampleIndicators" role="button" data-slide="next">
                                        <span>@svg('chevron-right', 'feather-chevron-right feather-carousel align-middle')</span>
                                        <span class="sr-only">Next</span>
                                    </a>
                                </div>
                            </div>
                            <table class="table table-hover my-0" id='inProgressTable'>
                                <thead>
                                    <tr>
                                        <th>Group</th>
                                        <th class="d-none d-xl-table-cell">Company</th>
                                        <th class="d-none d-xl-table-cell">Building</th>
                                        <th class="d-none d-md-table-cell">Time</th>
                                        <th class="d-none d-md-table-cell">Attendance</th>
                                    </tr>
                                </thead>
                                <br>
                                <tbody>
                                    <tr>
                                        <td>Apollo</td>
                                        <td class="d-none d-xl-table-cell">Petronas</td>
                                        <td class="d-none d-xl-table-cell">Tower 1</td>
                                        <td class="d-none d-xl-table-cell">
                                            <div class="row align-items-end">
                                                <div class="col-10">
                                                    <p class="mb-2 font-weight-bold">08:00 <span class="float-right">13:00</span></p>
                                                    <div class="progress progress-sm">
                                                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100" style="width: 65%;">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-2">
                                                    <span class="float-right"><em>65%</em></span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="d-none d-xl-table-cell">20/25</td>
                                    </tr>
                                    <tr>
                                        <td>Apollo</td>
                                        <td class="d-none d-xl-table-cell">Petronas</td>
                                        <td class="d-none d-xl-table-cell">Tower 1</td>
                                        <td class="d-none d-xl-table-cell">
                                            <div class="row align-items-end">
                                                <div class="col-10">
                                                    <p class="mb-2 font-weight-bold">08:00 <span class="float-right">13:00</span></p>
                                                    <div class="progress progress-sm">
                                                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100" style="width: 65%;">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-2">
                                                    <span class="float-right"><em>65%</em></span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="d-none d-xl-table-cell">20/25</td>
                                    </tr>
                                    <tr>
                                        <td>Apollo</td>
                                        <td class="d-none d-xl-table-cell">Petronas</td>
                                        <td class="d-none d-xl-table-cell">Tower 1</td>
                                        <td class="d-none d-xl-table-cell">
                                            <div class="row align-items-end">
                                                <div class="col-10">
                                                    <p class="mb-2 font-weight-bold">08:00 <span class="float-right">13:00</span></p>
                                                    <div class="progress progress-sm">
                                                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100" style="width: 65%;">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-2">
                                                    <span class="float-right"><em>65%</em></span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="d-none d-xl-table-cell">20/25</td>
                                    </tr>
                                    <tr>
                                        <td>Apollo</td>
                                        <td class="d-none d-xl-table-cell">Petronas</td>
                                        <td class="d-none d-xl-table-cell">Tower 1</td>
                                        <td class="d-none d-xl-table-cell">
                                            <div class="row align-items-end">
                                                <div class="col-10">
                                                    <p class="mb-2 font-weight-bold">08:00 <span class="float-right">13:00</span></p>
                                                    <div class="progress progress-sm">
                                                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100" style="width: 65%;">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-2">
                                                    <span class="float-right"><em>65%</em></span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="d-none d-xl-table-cell">20/25</td>
                                    </tr>
                                    <tr>
                                        <td>Apollo</td>
                                        <td class="d-none d-xl-table-cell">Petronas</td>
                                        <td class="d-none d-xl-table-cell">Tower 1</td>
                                        <td class="d-none d-xl-table-cell">
                                            <div class="row align-items-end">
                                                <div class="col-10">
                                                    <p class="mb-2 font-weight-bold">08:00 <span class="float-right">13:00</span></p>
                                                    <div class="progress progress-sm">
                                                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100" style="width: 65%;">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-2">
                                                    <span class="float-right"><em>65%</em></span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="d-none d-xl-table-cell">20/25</td>
                                    </tr>
                                    <tr>
                                        <td>Apollo</td>
                                        <td class="d-none d-xl-table-cell">Petronas</td>
                                        <td class="d-none d-xl-table-cell">Tower 1</td>
                                        <td class="d-none d-xl-table-cell">
                                            <div class="row align-items-end">
                                                <div class="col-10">
                                                    <p class="mb-2 font-weight-bold">08:00 <span class="float-right">13:00</span></p>
                                                    <div class="progress progress-sm">
                                                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100" style="width: 65%;">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-2">
                                                    <span class="float-right"><em>65%</em></span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="d-none d-xl-table-cell">20/25</td>
                                    </tr>
                                    <tr>
                                        <td>Apollo</td>
                                        <td class="d-none d-xl-table-cell">Petronas</td>
                                        <td class="d-none d-xl-table-cell">Tower 1</td>
                                        <td class="d-none d-xl-table-cell">
                                            <div class="row align-items-end">
                                                <div class="col-10">
                                                    <p class="mb-2 font-weight-bold">08:00 <span class="float-right">13:00</span></p>
                                                    <div class="progress progress-sm">
                                                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100" style="width: 65%;">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-2">
                                                    <span class="float-right"><em>65%</em></span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="d-none d-xl-table-cell">20/25</td>
                                    </tr>
                                    <tr>
                                        <td>Apollo</td>
                                        <td class="d-none d-xl-table-cell">Petronas</td>
                                        <td class="d-none d-xl-table-cell">Tower 1</td>
                                        <td class="d-none d-xl-table-cell">
                                            <div class="row align-items-end">
                                                <div class="col-10">
                                                    <p class="mb-2 font-weight-bold">08:00 <span class="float-right">13:00</span></p>
                                                    <div class="progress progress-sm">
                                                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100" style="width: 65%;">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-2">
                                                    <span class="float-right"><em>65%</em></span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="d-none d-xl-table-cell">20/25</td>
                                    </tr>
                                    <tr>
                                        <td>Apollo</td>
                                        <td class="d-none d-xl-table-cell">Petronas</td>
                                        <td class="d-none d-xl-table-cell">Tower 1</td>
                                        <td class="d-none d-xl-table-cell">
                                            <div class="row align-items-end">
                                                <div class="col-10">
                                                    <p class="mb-2 font-weight-bold">08:00 <span class="float-right">13:00</span></p>
                                                    <div class="progress progress-sm">
                                                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100" style="width: 65%;">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-2">
                                                    <span class="float-right"><em>65%</em></span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="d-none d-xl-table-cell">20/25</td>
                                    </tr>
                                    <tr>
                                        <td>Apollo</td>
                                        <td class="d-none d-xl-table-cell">Petronas</td>
                                        <td class="d-none d-xl-table-cell">Tower 1</td>
                                        <td class="d-none d-xl-table-cell">
                                            <div class="row align-items-end">
                                                <div class="col-10">
                                                    <p class="mb-2 font-weight-bold">08:00 <span class="float-right">13:00</span></p>
                                                    <div class="progress progress-sm">
                                                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100" style="width: 65%;">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-2">
                                                    <span class="float-right"><em>65%</em></span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="d-none d-xl-table-cell">20/25</td>
                                    </tr>
                                    <tr>
                                        <td>Apollo</td>
                                        <td class="d-none d-xl-table-cell">Petronas</td>
                                        <td class="d-none d-xl-table-cell">Tower 1</td>
                                        <td class="d-none d-xl-table-cell">
                                            <div class="row align-items-end">
                                                <div class="col-10">
                                                    <p class="mb-2 font-weight-bold">08:00 <span class="float-right">13:00</span></p>
                                                    <div class="progress progress-sm">
                                                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100" style="width: 65%;">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-2">
                                                    <span class="float-right"><em>65%</em></span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="d-none d-xl-table-cell">20/25</td>
                                    </tr>
                                    <tr>
                                        <td>Apollo</td>
                                        <td class="d-none d-xl-table-cell">Petronas</td>
                                        <td class="d-none d-xl-table-cell">Tower 1</td>
                                        <td class="d-none d-xl-table-cell">
                                            <div class="row align-items-end">
                                                <div class="col-10">
                                                    <p class="mb-2 font-weight-bold">08:00 <span class="float-right">13:00</span></p>
                                                    <div class="progress progress-sm">
                                                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100" style="width: 65%;">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-2">
                                                    <span class="float-right"><em>65%</em></span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="d-none d-xl-table-cell">20/25</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade not-started-tab-content" id="not-started-down" role="tabpanel" aria-labelledby="not-started">
                            <h5 class="card-title">Card with tabs</h5>
                            <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.
                            </p>
                            <a href="#" class="btn btn-primary">Go somewhere</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4 col-xxl-3 d-flex">
            <div class="card flex-fill w-100">
                <div class="card-header">
                    <div class="card-actions float-right">
                        <div class="dropdown show">
                            <a href="#" data-toggle="dropdown" data-display="static">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal align-middle"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#">Action</a>
                                <a class="dropdown-item" href="#">Another action</a>
                                <a class="dropdown-item" href="#">Something else here</a>
                            </div>
                        </div>
                    </div>
                    <h5 class="card-title mb-0">Alerts</h5>
                </div>
                <div class="card-body">
                    <ul class="timeline mb-0">
                        <li class="timeline-item">
                            <strong>Signed out</strong>
                            <span class="float-right text-muted text-sm">30m ago</span>
                            <p>Nam pretium turpis et arcu. Duis arcu tortor, suscipit...</p>
                        </li>
                        <li class="timeline-item">
                            <strong>Created invoice #1204</strong>
                            <span class="float-right text-muted text-sm">2h ago</span>
                            <p>Sed aliquam ultrices mauris. Integer ante arcu...</p>
                        </li>
                        <li class="timeline-item">
                            <strong>Discarded invoice #1147</strong>
                            <span class="float-right text-muted text-sm">3h ago</span>
                            <p>Nam pretium turpis et arcu. Duis arcu tortor, suscipit...</p>
                        </li>
                        <li class="timeline-item">
                            <strong>Signed in</strong>
                            <span class="float-right text-muted text-sm">3h ago</span>
                            <p>Curabitur ligula sapien, tincidunt non, euismod vitae...</p>
                        </li>
                        <li class="timeline-item">
                            <strong>Signed up</strong>
                            <span class="float-right text-muted text-sm">2d ago</span>
                            <p>Sed aliquam ultrices mauris. Integer ante arcu...</p>
                        </li>
                        <li class="timeline-item">
                            <strong>Signed out</strong>
                            <span class="float-right text-muted text-sm">30m ago</span>
                            <p>Nam pretium turpis et arcu. Duis arcu tortor, suscipit...</p>
                        </li>
                        <li class="timeline-item">
                            <strong>Created invoice #1204</strong>
                            <span class="float-right text-muted text-sm">2h ago</span>
                            <p>Sed aliquam ultrices mauris. Integer ante arcu...</p>
                        </li>
                        <li class="timeline-item">
                            <strong>Discarded invoice #1147</strong>
                            <span class="float-right text-muted text-sm">3h ago</span>
                            <p>Nam pretium turpis et arcu. Duis arcu tortor, suscipit...</p>
                        </li>
                        <li class="timeline-item">
                            <strong>Signed in</strong>
                            <span class="float-right text-muted text-sm">3h ago</span>
                            <p>Curabitur ligula sapien, tincidunt non, euismod vitae...</p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
</div>
@endsection

@section('script')
<script>
    $(function(){

        var inProgressTable = $('#inProgressTable').DataTable({
            dom: '<fl<t>ip>',
            // ajax: {
            //     url: '{{ route("dashboard.index") }}',
            //     dataSrc: 'data'
            // },
            // columns:[
            //     {data: 'name'},
            //     {data: 'company'},
            //     {data: 'building'},
            //     {data: 'start_time'},
            //     {data: 'end_time'},
            // ],
            searching: false,
			order: [[3, 'asc']],
            scrollY: 600,
			scrollCollapse: true,
			paging:true,
			scroller: true,
        })

        inProgressTable.columns.adjust().draw();

        // var run = setInterval(function(){
        //     inProgressTable.ajax.reload();
        //     console.log('reload');
        // }, 30000);

        var options = {
            chart:{
                type: 'donut',
            },
            colors: ['#3b7ddd', '#ffC107', '#DC3545'],
            series: [18, 5, 2],
            labels: ['In-time', 'Late', 'Absent'],
            dataLabels: {
                enabled: true,
                formatter: function (val) {
                    return val + "%"
                },
                dropShadow: {
                    enabled: false,
                    enabledOnSeries: undefined,
                    top: 0,
                    left: 0,
                    blur: 3,
                    color: '#000',
                    opacity: 0.35
                }
            },
            plotOptions: {
                pie: {
                    donut: {
                        labels: {
                            show: true,
                            name: {
                                show: true,
                            },
                            value: {
                                show: true,
                            },
                            total:{
                                show:true,
                                formatter: function (w){
                                    return w.globals.seriesTotals.reduce((a, b) => {
                                        return a + b
                                    }, 0)
                                }
                            }
                        }
                    }
                }
            }
        }

        var chart = new ApexCharts(document.querySelector("#donutChart"), options);
        var chart1 = new ApexCharts(document.querySelector("#donutChart1"), options);
        var chart2 = new ApexCharts(document.querySelector("#donutChart2"), options);
        var chart3 = new ApexCharts(document.querySelector("#donutChart3"), options);

        chart.render();
        chart1.render();
        chart2.render();
        chart3.render();
    })
</script>

<script>
    /* Initiate popover */
    $('[data-toggle="popover"]').popover()

    document.addEventListener("DOMContentLoaded", function() {
        var calendarEl = document.getElementById("fullcalendar");
        var calendar = new Calendar(calendarEl, {
            plugins: [ dayGridPlugin, timeGridPlugin, listPlugin, interactionPlugin, bootstrapPlugin ],
            themeSystem: "bootstrap",
            initialView: "dayGridMonth",
            initialDate: "2020-07-07",
            headerToolbar: {
                left: "prev,next today",
                center: "title",
                right: "dayGridMonth,timeGridWeek,timeGridDay,listMonth"
            },
            events: [{
                title: "All Day Event",
                start: "2020-07-01"
            },
            {
                title: "Long Event",
                start: "2020-07-07",
                end: "2020-07-10"
            },
            {
                groupId: "999",
                title: "Repeating Event",
                start: "2020-07-09T16:00:00"
            },
            {
                groupId: "999",
                title: "Repeating Event",
                start: "2020-07-16T16:00:00"
            },
            {
                title: "Conference",
                start: "2020-07-11",
                end: "2020-07-13"
            },
            {
                title: "Meeting",
                start: "2020-07-12T10:30:00",
                end: "2020-07-12T12:30:00"
            },
            {
                title: "Lunch",
                start: "2020-07-12T12:00:00"
            },
            {
                title: "Meeting",
                start: "2020-07-12T14:30:00"
            },
            {
                title: "Birthday Party",
                start: "2020-07-13T07:00:00"
            },
            {
                title: "Click for Google",
                url: "http://google.com/",
                start: "2020-07-28"
            }
            ]
        });
        setTimeout(function() {
            calendar.render();
        }, 250)
    });

    document.addEventListener("DOMContentLoaded", function() {
        var calendarEl = document.getElementById("listcalendar");
        var calendar = new Calendar(calendarEl, {
            plugins: [ dayGridPlugin, timeGridPlugin, listPlugin, interactionPlugin, bootstrapPlugin ],
            themeSystem: "bootstrap",
            initialView: "listMonth",
            initialDate: "2020-07-07",
            events: [{
                title: "All Day Event",
                start: "2020-07-01"
            },
            {
                title: "Long Event",
                start: "2020-07-07",
                end: "2020-07-10"
            },
            {
                groupId: "999",
                title: "Repeating Event",
                start: "2020-07-09T16:00:00"
            },
            {
                groupId: "999",
                title: "Repeating Event",
                start: "2020-07-16T16:00:00"
            },
            {
                title: "Conference",
                start: "2020-07-11",
                end: "2020-07-13"
            },
            {
                title: "Meeting",
                start: "2020-07-12T10:30:00",
                end: "2020-07-12T12:30:00"
            },
            {
                title: "Lunch",
                start: "2020-07-12T12:00:00"
            },
            {
                title: "Meeting",
                start: "2020-07-12T14:30:00"
            },
            {
                title: "Birthday Party",
                start: "2020-07-13T07:00:00"
            },
            {
                title: "Click for Google",
                url: "http://google.com/",
                start: "2020-07-28"
            }
            ]
        });
        setTimeout(function() {
            calendar.render();
        }, 250)
    });

</script>

<script>
    am4core.useTheme(am4themes_animated);

    var chart = am4core.create("chartdiv", am4charts.PieChart3D);
    chart.hiddenState.properties.opacity = 0; // this creates initial fade-in

    chart.data = [
    {
        country: "Lithuania",
        litres: 501.9
    },
    {
        country: "Czech Republic",
        litres: 301.9
    },
    {
        country: "Ireland",
        litres: 201.1
    },
    {
        country: "Germany",
        litres: 165.8
    },
    {
        country: "Australia",
        litres: 139.9
    },
    {
        country: "Austria",
        litres: 128.3
    }
    ];

    chart.innerRadius = am4core.percent(40);
    chart.depth = 120;

    chart.legend = new am4charts.Legend();
    chart.legend.position = "right";

    var series = chart.series.push(new am4charts.PieSeries3D());
    series.dataFields.value = "litres";
    series.dataFields.depthValue = "litres";
    series.dataFields.category = "country";
    series.slices.template.cornerRadius = 5;
    series.colors.step = 3;
</script>


@endsection
