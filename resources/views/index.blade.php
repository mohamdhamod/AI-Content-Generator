@extends('layout.main')
@include('layout.extra_meta')
@section('content')
    <div class="container-fluid">
        <!-- Page Title Section -->
        <div class="page-title-section">
            <div class="row justify-content-center py-5">
                <div class="col-xxl-5 col-xl-7 text-center">
                <span class="badge badge-default fw-normal shadow px-2 py-1 mb-2 fst-italic fs-xxs">
                    <i class="bi bi-stars me-1"></i> Medium and Large Business
                </span>
                    <h3 class="fw-bold">The Ultimate Admin & Dashboard Theme</h3>
                    <p class="fs-md text-muted mb-0">A premium collection of elegant, accessible components and a powerful codebase. Built for modern frameworks. Developer Friendly. Production Ready.</p>
                </div>
            </div>
        </div>

        <!-- Stats Cards Row -->
        <div class="row">
            <!-- Charts Grid -->
            <div class="col-12">
                <div class="row">
                    <!-- Today's Prompts Chart -->
                    <div class="col-md-6 col-xl-3 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="card-title mb-0">Today's Prompts</h5>
                                    <i class="bi bi-chat-square-text text-muted"></i>
                                </div>
                                <div>
                                    <canvas id="promptsChart" height="200"></canvas>
                                </div>

                                <div class="d-flex justify-content-between mt-3">
                                    <div>
                                        <span class="text-muted">Total Today</span>
                                        <h4 class="mb-0">1,245</h4>
                                    </div>
                                    <div class="text-end">
                                        <span class="text-muted">vs Yesterday</span>
                                        <div class="d-flex align-items-center justify-content-end">
                                            <i class="bi bi-arrow-up text-success me-1"></i>
                                            <span class="text-success">+12%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Response Accuracy Chart -->
                    <div class="col-md-6 col-xl-3 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="card-title mb-0">Response Accuracy</h5>
                                    <i class="bi bi-graph-up text-muted"></i>
                                </div>
                                <div>
                                    <canvas id="accuracyChart" height="200"></canvas>
                                </div>

                                <div class="text-center mt-3">
                                    <h3 class="mb-0">94.3%</h3>
                                    <span class="text-muted">Average Accuracy</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Token Usage Chart -->
                    <div class="col-md-6 col-xl-3 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="card-title mb-0">Token Usage</h5>
                                    <i class="bi bi-cpu text-muted"></i>
                                </div>
                                <div>
                                    <canvas id="tokenChart" height="200"></canvas>
                                </div>

                                <div class="d-flex justify-content-between mt-3">
                                    <div>
                                        <span class="text-muted">Today's Usage</span>
                                        <h4 class="mb-0">920.4K</h4>
                                    </div>
                                    <div class="text-end">
                                        <span class="text-muted">vs Yesterday</span>
                                        <div class="d-flex align-items-center justify-content-end">
                                            <i class="bi bi-arrow-up text-success me-1"></i>
                                            <span class="text-success">+6.4%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- AI Requests Chart -->
                    <div class="col-md-6 col-xl-3 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="card-title mb-0">AI Requests</h5>
                                    <i class="bi bi-robot text-muted"></i>
                                </div>
                                <div>
                                    <canvas id="requestsChart" height="200"></canvas>
                                </div>

                                <div class="text-center mt-3">
                                    <h3 class="mb-0">3,148</h3>
                                    <span class="text-muted">Total Requests Today</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Request Statistics Section -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-xl-3 col-md-6">
                                <div class="text-center">
                                    <p class="mb-4 d-flex align-items-center gap-1 justify-content-center">
                                        <i class="bi bi-robot"></i> AI Requests
                                    </p>
                                    <h2 class="fw-bold mb-0">807,621</h2>
                                    <p class="text-muted">Total AI requests in last 30 days</p>
                                    <p class="mb-0 mt-4 d-flex align-items-center gap-1 justify-content-center">
                                        <i class="bi bi-calendar"></i> Data from May
                                    </p>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6 order-xl-last">
                                <div class="text-center">
                                    <p class="mb-4 d-flex align-items-center gap-1 justify-content-center">
                                        <i class="bi bi-clock"></i> Usage Duration
                                    </p>
                                    <h2 class="fw-bold mb-0">9 Months</h2>
                                    <p class="text-muted">Including 4 weeks this quarter</p>
                                    <p class="mb-0 mt-4 d-flex align-items-center gap-1 justify-content-center">
                                        <i class="bi bi-clock-history"></i> Last accessed: 12.06.2025
                                    </p>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="w-100" style="height: 240px;">
                                    <!-- Chart placeholder -->
                                    <div class="placeholder-chart" style="height: 240px;">
                                        <canvas id="requestTrendsChart" style="width: 562px; display: block; box-sizing: border-box; height: 240px;" height="300" width="703"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex align-items-center text-muted justify-content-between">
                            <div>Last update: 16.06.2025</div>
                            <div>You received 2 new AI feedback reports</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Sessions & Model Usage Section -->
        <div class="row">
            <div class="col-xxl-6">
                <!-- Recent AI Sessions -->
                <div class="card">
                    <div class="card-header justify-content-between align-items-center border-dashed">
                        <h4 class="card-title mb-0">Recent AI Sessions</h4>
                        <div class="d-flex gap-2">
                            <a href="javascript:void(0);" class="btn btn-sm btn-light">
                                <i class="bi bi-plus me-1"></i> New Session
                            </a>
                            <a href="javascript:void(0);" class="btn btn-sm btn-primary">
                                <i class="bi bi-download me-1"></i> Export Logs
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-centered table-custom table-sm table-nowrap table-hover mb-0">
                                <tbody>
                                <!-- 10 Session rows -->
                                <tr>
                                    <td><div class="d-flex align-items-center"><img class="avatar-sm rounded-circle me-2" src="https://coderthemes.com/vona-angular/assets/images/users/user-1.jpg" alt="Alice Cooper"><div><span class="text-muted fs-xs">Alice Cooper</span><h5 class="fs-base mb-0"><a class="text-body" href="/dashboard">#AI-5001</a></h5></div></div></td>
                                    <td><span class="text-muted fs-xs">Model</span><h5 class="fs-base mb-0 fw-normal">GPT-4</h5></td>
                                    <td><span class="text-muted fs-xs">Date</span><h5 class="fs-base mb-0 fw-normal">2025-05-01</h5></td>
                                    <td><span class="text-muted fs-xs">Tokens</span><h5 class="fs-base mb-0 fw-normal">2,304</h5></td>
                                    <td><span class="text-muted fs-xs">Status</span><h5 class="fs-base mb-0 fw-normal d-flex align-items-center"><i class="bi bi-circle-fill text-success fs-xs me-1"></i> Completed</h5></td>
                                    <td style="width: 30px;"><div class="dropdown"><a href="javascript:void(0);" class="dropdown-toggle text-muted drop-arrow-none card-drop p-0" data-bs-toggle="dropdown"><i class="bi bi-three-dots-vertical fs-lg"></i></a><div class="dropdown-menu dropdown-menu-end"><a href="javascript:void(0)" class="dropdown-item">{{ __('translation.general.view_details') }}</a><a href="javascript:void(0)" class="dropdown-item">{{ __('translation.general.delete') }}</a></div></div></td>
                                </tr>
                                <tr>
                                    <td><div class="d-flex align-items-center"><img class="avatar-sm rounded-circle me-2" src="https://coderthemes.com/vona-angular/assets/images/users/user-2.jpg" alt="Bob Smith"><div><span class="text-muted fs-xs">Bob Smith</span><h5 class="fs-base mb-0"><a class="text-body" href="/dashboard">#AI-5002</a></h5></div></div></td>
                                    <td><span class="text-muted fs-xs">Model</span><h5 class="fs-base mb-0 fw-normal">Claude 2</h5></td>
                                    <td><span class="text-muted fs-xs">Date</span><h5 class="fs-base mb-0 fw-normal">2025-05-02</h5></td>
                                    <td><span class="text-muted fs-xs">Tokens</span><h5 class="fs-base mb-0 fw-normal">1,850</h5></td>
                                    <td><span class="text-muted fs-xs">Status</span><h5 class="fs-base mb-0 fw-normal d-flex align-items-center"><i class="bi bi-circle-fill text-warning fs-xs me-1"></i> Pending</h5></td>
                                    <td style="width: 30px;"><div class="dropdown"><a href="javascript:void(0);" class="dropdown-toggle text-muted drop-arrow-none card-drop p-0" data-bs-toggle="dropdown"><i class="bi bi-three-dots-vertical fs-lg"></i></a><div class="dropdown-menu dropdown-menu-end"><a href="javascript:void(0)" class="dropdown-item">{{ __('translation.general.view_details') }}</a><a href="javascript:void(0)" class="dropdown-item">{{ __('translation.general.delete') }}</a></div></div></td>
                                </tr>
                                <tr>
                                    <td><div class="d-flex align-items-center"><img class="avatar-sm rounded-circle me-2" src="https://coderthemes.com/vona-angular/assets/images/users/user-3.jpg" alt="Carol Lee"><div><span class="text-muted fs-xs">Carol Lee</span><h5 class="fs-base mb-0"><a class="text-body" href="/dashboard">#AI-5003</a></h5></div></div></td>
                                    <td><span class="text-muted fs-xs">Model</span><h5 class="fs-base mb-0 fw-normal">GPT-3.5</h5></td>
                                    <td><span class="text-muted fs-xs">Date</span><h5 class="fs-base mb-0 fw-normal">2025-05-03</h5></td>
                                    <td><span class="text-muted fs-xs">Tokens</span><h5 class="fs-base mb-0 fw-normal">3,120</h5></td>
                                    <td><span class="text-muted fs-xs">Status</span><h5 class="fs-base mb-0 fw-normal d-flex align-items-center"><i class="bi bi-circle-fill text-danger fs-xs me-1"></i> Failed</h5></td>
                                    <td style="width: 30px;"><div class="dropdown"><a href="javascript:void(0);" class="dropdown-toggle text-muted drop-arrow-none card-drop p-0" data-bs-toggle="dropdown"><i class="bi bi-three-dots-vertical fs-lg"></i></a><div class="dropdown-menu dropdown-menu-end"><a href="javascript:void(0)" class="dropdown-item">{{ __('translation.general.view_details') }}</a><a href="javascript:void(0)" class="dropdown-item">{{ __('translation.general.delete') }}</a></div></div></td>
                                </tr>
                                <tr>
                                    <td><div class="d-flex align-items-center"><img class="avatar-sm rounded-circle me-2" src="https://coderthemes.com/vona-angular/assets/images/users/user-4.jpg" alt="David Kim"><div><span class="text-muted fs-xs">David Kim</span><h5 class="fs-base mb-0"><a class="text-body" href="/dashboard">#AI-5004</a></h5></div></div></td>
                                    <td><span class="text-muted fs-xs">Model</span><h5 class="fs-base mb-0 fw-normal">Llama</h5></td>
                                    <td><span class="text-muted fs-xs">Date</span><h5 class="fs-base mb-0 fw-normal">2025-05-04</h5></td>
                                    <td><span class="text-muted fs-xs">Tokens</span><h5 class="fs-base mb-0 fw-normal">2,900</h5></td>
                                    <td><span class="text-muted fs-xs">Status</span><h5 class="fs-base mb-0 fw-normal d-flex align-items-center"><i class="bi bi-circle-fill text-success fs-xs me-1"></i> Completed</h5></td>
                                    <td style="width: 30px;"><div class="dropdown"><a href="javascript:void(0);" class="dropdown-toggle text-muted drop-arrow-none card-drop p-0" data-bs-toggle="dropdown"><i class="bi bi-three-dots-vertical fs-lg"></i></a><div class="dropdown-menu dropdown-menu-end"><a href="javascript:void(0)" class="dropdown-item">{{ __('translation.general.view_details') }}</a><a href="javascript:void(0)" class="dropdown-item">{{ __('translation.general.delete') }}</a></div></div></td>
                                </tr>
                                <tr>
                                    <td><div class="d-flex align-items-center"><img class="avatar-sm rounded-circle me-2" src="https://coderthemes.com/vona-angular/assets/images/users/user-5.jpg" alt="Eva Green"><div><span class="text-muted fs-xs">Eva Green</span><h5 class="fs-base mb-0"><a class="text-body" href="/dashboard">#AI-5005</a></h5></div></div></td>
                                    <td><span class="text-muted fs-xs">Model</span><h5 class="fs-base mb-0 fw-normal">PaLM</h5></td>
                                    <td><span class="text-muted fs-xs">Date</span><h5 class="fs-base mb-0 fw-normal">2025-05-05</h5></td>
                                    <td><span class="text-muted fs-xs">Tokens</span><h5 class="fs-base mb-0 fw-normal">2,100</h5></td>
                                    <td><span class="text-muted fs-xs">Status</span><h5 class="fs-base mb-0 fw-normal d-flex align-items-center"><i class="bi bi-circle-fill text-success fs-xs me-1"></i> Completed</h5></td>
                                    <td style="width: 30px;"><div class="dropdown"><a href="javascript:void(0);" class="dropdown-toggle text-muted drop-arrow-none card-drop p-0" data-bs-toggle="dropdown"><i class="bi bi-three-dots-vertical fs-lg"></i></a><div class="dropdown-menu dropdown-menu-end"><a href="javascript:void(0)" class="dropdown-item">{{ __('translation.general.view_details') }}</a><a href="javascript:void(0)" class="dropdown-item">{{ __('translation.general.delete') }}</a></div></div></td>
                                </tr>
                                <tr>
                                    <td><div class="d-flex align-items-center"><img class="avatar-sm rounded-circle me-2" src="https://coderthemes.com/vona-angular/assets/images/users/user-6.jpg" alt="Fiona White"><div><span class="text-muted fs-xs">Fiona White</span><h5 class="fs-base mb-0"><a class="text-body" href="/dashboard">#AI-5006</a></h5></div></div></td>
                                    <td><span class="text-muted fs-xs">Model</span><h5 class="fs-base mb-0 fw-normal">GPT-4</h5></td>
                                    <td><span class="text-muted fs-xs">Date</span><h5 class="fs-base mb-0 fw-normal">2025-05-06</h5></td>
                                    <td><span class="text-muted fs-xs">Tokens</span><h5 class="fs-base mb-0 fw-normal">2,750</h5></td>
                                    <td><span class="text-muted fs-xs">Status</span><h5 class="fs-base mb-0 fw-normal d-flex align-items-center"><i class="bi bi-circle-fill text-success fs-xs me-1"></i> Completed</h5></td>
                                    <td style="width: 30px;"><div class="dropdown"><a href="javascript:void(0);" class="dropdown-toggle text-muted drop-arrow-none card-drop p-0" data-bs-toggle="dropdown"><i class="bi bi-three-dots-vertical fs-lg"></i></a><div class="dropdown-menu dropdown-menu-end"><a href="javascript:void(0)" class="dropdown-item">{{ __('translation.general.view_details') }}</a><a href="javascript:void(0)" class="dropdown-item">{{ __('translation.general.delete') }}</a></div></div></td>
                                </tr>
                                <tr>
                                    <td><div class="d-flex align-items-center"><img class="avatar-sm rounded-circle me-2" src="https://coderthemes.com/vona-angular/assets/images/users/user-7.jpg" alt="George Brown"><div><span class="text-muted fs-xs">George Brown</span><h5 class="fs-base mb-0"><a class="text-body" href="/dashboard">#AI-5007</a></h5></div></div></td>
                                    <td><span class="text-muted fs-xs">Model</span><h5 class="fs-base mb-0 fw-normal">Claude 2</h5></td>
                                    <td><span class="text-muted fs-xs">Date</span><h5 class="fs-base mb-0 fw-normal">2025-05-07</h5></td>
                                    <td><span class="text-muted fs-xs">Tokens</span><h5 class="fs-base mb-0 fw-normal">2,980</h5></td>
                                    <td><span class="text-muted fs-xs">Status</span><h5 class="fs-base mb-0 fw-normal d-flex align-items-center"><i class="bi bi-circle-fill text-warning fs-xs me-1"></i> Pending</h5></td>
                                    <td style="width: 30px;"><div class="dropdown"><a href="javascript:void(0);" class="dropdown-toggle text-muted drop-arrow-none card-drop p-0" data-bs-toggle="dropdown"><i class="bi bi-three-dots-vertical fs-lg"></i></a><div class="dropdown-menu dropdown-menu-end"><a href="javascript:void(0)" class="dropdown-item">{{ __('translation.general.view_details') }}</a><a href="javascript:void(0)" class="dropdown-item">{{ __('translation.general.delete') }}</a></div></div></td>
                                </tr>
                                <tr>
                                    <td><div class="d-flex align-items-center"><img class="avatar-sm rounded-circle me-2" src="https://coderthemes.com/vona-angular/assets/images/users/user-8.jpg" alt="Hannah Black"><div><span class="text-muted fs-xs">Hannah Black</span><h5 class="fs-base mb-0"><a class="text-body" href="/dashboard">#AI-5008</a></h5></div></div></td>
                                    <td><span class="text-muted fs-xs">Model</span><h5 class="fs-base mb-0 fw-normal">GPT-3.5</h5></td>
                                    <td><span class="text-muted fs-xs">Date</span><h5 class="fs-base mb-0 fw-normal">2025-05-08</h5></td>
                                    <td><span class="text-muted fs-xs">Tokens</span><h5 class="fs-base mb-0 fw-normal">2,600</h5></td>
                                    <td><span class="text-muted fs-xs">Status</span><h5 class="fs-base mb-0 fw-normal d-flex align-items-center"><i class="bi bi-circle-fill text-success fs-xs me-1"></i> Completed</h5></td>
                                    <td style="width: 30px;"><div class="dropdown"><a href="javascript:void(0);" class="dropdown-toggle text-muted drop-arrow-none card-drop p-0" data-bs-toggle="dropdown"><i class="bi bi-three-dots-vertical fs-lg"></i></a><div class="dropdown-menu dropdown-menu-end"><a href="javascript:void(0)" class="dropdown-item">{{ __('translation.general.view_details') }}</a><a href="javascript:void(0)" class="dropdown-item">{{ __('translation.general.delete') }}</a></div></div></td>
                                </tr>
                                <tr>
                                    <td><div class="d-flex align-items-center"><img class="avatar-sm rounded-circle me-2" src="https://coderthemes.com/vona-angular/assets/images/users/user-9.jpg" alt="Ivy Grey"><div><span class="text-muted fs-xs">Ivy Grey</span><h5 class="fs-base mb-0"><a class="text-body" href="/dashboard">#AI-5009</a></h5></div></div></td>
                                    <td><span class="text-muted fs-xs">Model</span><h5 class="fs-base mb-0 fw-normal">Whisper</h5></td>
                                    <td><span class="text-muted fs-xs">Date</span><h5 class="fs-base mb-0 fw-normal">2025-05-09</h5></td>
                                    <td><span class="text-muted fs-xs">Tokens</span><h5 class="fs-base mb-0 fw-normal">1,950</h5></td>
                                    <td><span class="text-muted fs-xs">Status</span><h5 class="fs-base mb-0 fw-normal d-flex align-items-center"><i class="bi bi-circle-fill text-success fs-xs me-1"></i> Completed</h5></td>
                                    <td style="width: 30px;"><div class="dropdown"><a href="javascript:void(0);" class="dropdown-toggle text-muted drop-arrow-none card-drop p-0" data-bs-toggle="dropdown"><i class="bi bi-three-dots-vertical fs-lg"></i></a><div class="dropdown-menu dropdown-menu-end"><a href="javascript:void(0)" class="dropdown-item">{{ __('translation.general.view_details') }}</a><a href="javascript:void(0)" class="dropdown-item">{{ __('translation.general.delete') }}</a></div></div></td>
                                </tr>
                                <tr>
                                    <td><div class="d-flex align-items-center"><img class="avatar-sm rounded-circle me-2" src="https://coderthemes.com/vona-angular/assets/images/users/user-10.jpg" alt="Jack White"><div><span class="text-muted fs-xs">Jack White</span><h5 class="fs-base mb-0"><a class="text-body" href="/dashboard">#AI-5010</a></h5></div></div></td>
                                    <td><span class="text-muted fs-xs">Model</span><h5 class="fs-base mb-0 fw-normal">Stable Diffusion</h5></td>
                                    <td><span class="text-muted fs-xs">Date</span><h5 class="fs-base mb-0 fw-normal">2025-05-10</h5></td>
                                    <td><span class="text-muted fs-xs">Tokens</span><h5 class="fs-base mb-0 fw-normal">2,400</h5></td>
                                    <td><span class="text-muted fs-xs">Status</span><h5 class="fs-base mb-0 fw-normal d-flex align-items-center"><i class="bi bi-circle-fill text-success fs-xs me-1"></i> Completed</h5></td>
                                    <td style="width: 30px;"><div class="dropdown"><a href="javascript:void(0);" class="dropdown-toggle text-muted drop-arrow-none card-drop p-0" data-bs-toggle="dropdown"><i class="bi bi-three-dots-vertical fs-lg"></i></a><div class="dropdown-menu dropdown-menu-end"><a href="javascript:void(0)" class="dropdown-item">{{ __('translation.general.view_details') }}</a><a href="javascript:void(0)" class="dropdown-item">{{ __('translation.general.delete') }}</a></div></div></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer border-0">
                        <div class="align-items-center justify-content-between row text-center text-sm-start">
                            <div class="col-sm">
                                <div class="text-muted">
                                    Showing <span class="fw-semibold">1</span> to <span class="fw-semibold">10</span> of <span class="fw-semibold">2684</span> Sessions
                                </div>
                            </div>
                            <div class="col-sm-auto mt-3 mt-sm-0">
                                <nav>
                                    <ul class="pagination pagination-sm pagination-boxed mb-0 justify-content-center">
                                        <li class="page-item disabled">
                                            <a class="page-link" href="#" tabindex="-1" aria-disabled="true">
                                                <i class="bi bi-chevron-left"></i>
                                            </a>
                                        </li>
                                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                                        <li class="page-item">
                                            <a class="page-link" href="#">
                                                <i class="bi bi-chevron-right"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xxl-6">
                <!-- AI Model Usage Summary -->
                <div class="card mb-4">
                    <div class="card-header border-dashed">
                        <h4 class="card-title mb-0">AI Model Usage Summary</h4>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-sm table-centered table-custom table-nowrap mb-0">
                                <thead class="bg-light-subtle thead-sm">
                                <tr class="text-uppercase fs-xxs">
                                    <th>Model</th>
                                    <th>Requests</th>
                                    <th>Total Tokens</th>
                                    <th>Average Tokens</th>
                                    <th>Last Used</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>GPT-4</td>
                                    <td>1,248</td>
                                    <td>2,483,920</td>
                                    <td>1,989</td>
                                    <td>2025-06-15</td>
                                </tr>
                                <tr>
                                    <td>DALL·E</td>
                                    <td>328</td>
                                    <td>194,320</td>
                                    <td>592</td>
                                    <td>2025-06-14</td>
                                </tr>
                                <tr>
                                    <td>Claude 2</td>
                                    <td>814</td>
                                    <td>1,102,390</td>
                                    <td>1,354</td>
                                    <td>2025-06-13</td>
                                </tr>
                                <tr>
                                    <td>Whisper</td>
                                    <td>512</td>
                                    <td>653,210</td>
                                    <td>1,275</td>
                                    <td>2025-06-12</td>
                                </tr>
                                <tr>
                                    <td>Stable Diffusion</td>
                                    <td>102</td>
                                    <td>61,400</td>
                                    <td>602</td>
                                    <td>2025-06-10</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer border-top-0 text-end">
                        <span class="text-muted">Updated 1 hour ago</span>
                    </div>
                </div>

                <!-- API Performance Metrics -->
                <div class="card">
                    <div class="card-header border-dashed">
                        <h4 class="card-title mb-0">AI API Performance Metrics</h4>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-sm table-centered table-nowrap table-custom mb-0">
                                <thead class="bg-light-subtle thead-sm">
                                <tr class="text-uppercase fs-xxs">
                                    <th>Endpoint</th>
                                    <th>Latency</th>
                                    <th>Requests</th>
                                    <th>Error Rate</th>
                                    <th>Cost ($)</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>/v1/chat/completions</td>
                                    <td>720ms</td>
                                    <td>8,204</td>
                                    <td>0.18%</td>
                                    <td>128.34</td>
                                </tr>
                                <tr>
                                    <td>/v1/images/generations</td>
                                    <td>930ms</td>
                                    <td>1,029</td>
                                    <td>0.03%</td>
                                    <td>43.89</td>
                                </tr>
                                <tr>
                                    <td>/v1/audio/transcriptions</td>
                                    <td>1.2s</td>
                                    <td>489</td>
                                    <td>0.00%</td>
                                    <td>16.45</td>
                                </tr>
                                <tr>
                                    <td>/v1/embeddings</td>
                                    <td>610ms</td>
                                    <td>2,170</td>
                                    <td>0.10%</td>
                                    <td>24.98</td>
                                </tr>
                                <tr>
                                    <td>/v1/chat/moderation</td>
                                    <td>450ms</td>
                                    <td>5,025</td>
                                    <td>0.01%</td>
                                    <td>7.52</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer border-top-0 text-end">
                        <span class="text-muted">API stats updated: 2025-06-16 08:32 AM</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection


@push('scripts')

@endpush
