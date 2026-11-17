@extends('adminlte::page')

@section('title', 'Dashboard')

@section('plugins.Sweetalert2', true)
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)

@section('content_header')
    {{-- <h1><i class="fas fa-solid fa-chart-line"></i> Dashboard</h1> --}}
        <style>
         .main-sidebar { display: none !important; }
          .content-wrapper, .main-header { margin-left: 0 !important; } */
        </style>
    @can('is-admin')
    <style>
         .main-sidebar { display: none !important; }
        /* .main-sidebar {  }
        .content-wrapper, .main-header { margin-left: 0 !important; } */
    </style>
    <!-- Custom CSS for Horizontal Scrolling Timeline Layout -->
    {{-- <style>
    .horizontal-timeline-wrapper {
        overflow-x: auto;
        white-space: nowrap;
        padding: 20px 10px;
        display: flex;
        align-items: flex-start;
        gap: 25px;
        scroll-behavior: smooth;
    }
    .timeline-node {
        min-width: 320px;
        max-width: 320px;
        display: inline-block;
        vertical-align: top;
        white-space: normal; /* Allows text to wrap normally inside the card */
        position: relative;
    }
    /* Horizontal connector line linking the cards */
    .timeline-node:not(:last-child)::after {
        content: '';
        position: absolute;
        top: 25px;
        left: 100%;
        width: 25px;
        height: 4px;
        background-color: #dee2e6;
        z-index: 1;
    }
    .node-icon-badge {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px auto;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        color: #fff;
        font-size: 1.2rem;
    }
</style> --}}

<style>
    .horizontal-timeline-wrapper {
        background: #f8fafc;
        padding: 24px;
        border-radius: 16px;
    }

    .item-timeline-lane + .item-timeline-lane {
        border-top: 1px solid #e2e8f0;
        padding-top: 24px;
        margin-top: 12px;
    }

    .item-lane-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 18px;
    }

    .item-title-area {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .item-title-icon {
        width: 46px;
        height: 46px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #e0e7ff;
        color: #4338ca;
        font-size: 19px;
    }

    .item-code-badge {
        background: #e2e8f0;
        color: #475569;
        padding: 7px 12px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
    }

    .horizontal-scroll-lane {
        display: flex;
        flex-wrap: nowrap;
        align-items: stretch;
        gap: 24px;
        overflow-x: auto;
        padding: 18px 5px 24px;
        scrollbar-width: thin;
        scrollbar-color: #cbd5e1 transparent;
    }

    .timeline-node {
        width: 275px;
        min-width: 275px;
        flex: 0 0 275px;
        position: relative;
        padding-top: 17px;
    }

    .timeline-node::before {
        content: "";
        position: absolute;
        top: 7px;
        left: 0;
        right: -24px;
        height: 2px;
        background: #cbd5e1;
    }

    .timeline-node:last-child::before {
        right: 50%;
    }

    .node-icon-badge {
        position: absolute;
        z-index: 2;
        top: 0;
        left: 16px;
        width: 16px;
        height: 16px;
        border: 4px solid #f8fafc;
        box-sizing: content-box;
        border-radius: 50%;
    }

    .node-icon-badge.bg-success {
        background: #16a34a !important;
    }

    .node-icon-badge.bg-danger {
        background: #dc2626 !important;
    }

    .timeline-modern-card {
        height: 100%;
        overflow: hidden;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        box-shadow: 0 3px 12px rgba(15, 23, 42, .05);
        transition: transform .2s ease, box-shadow .2s ease;
    }

    .timeline-modern-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(15, 23, 42, .09);
    }

    .timeline-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 8px;
        padding: 15px;
        border-bottom: 1px solid #f1f5f9;
    }

    .timeline-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 9px;
        border-radius: 7px;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: .4px;
    }

    .timeline-status.started {
        background: #dcfce7;
        color: #166534;
    }

    .timeline-status.stopped {
        background: #fee2e2;
        color: #991b1b;
    }

    .timeline-clock {
        color: #475569;
        font-size: 12px;
        font-weight: 700;
        white-space: nowrap;
    }

    .timeline-card-body {
        padding: 15px;
    }

    .timeline-user-box {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 11px;
        margin-bottom: 14px;
        border-radius: 10px;
        background: #f8fafc;
        border: 1px solid #f1f5f9;
    }

    .timeline-user-avatar {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: #e2e8f0;
        color: #475569;
    }

    .timeline-user-name {
        color: #1e293b;
        font-size: 13px;
        font-weight: 700;
        overflow-wrap: anywhere;
    }

    .timeline-user-id {
        display: block;
        margin-top: 3px;
        color: #94a3b8;
        font-size: 11px;
    }

    .timeline-date {
        color: #64748b;
        font-size: 12px;
    }

    .timeline-date strong {
        color: #334155;
    }

    .timeline-duration {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 14px;
        padding: 10px;
        border-radius: 9px;
        background: #eef2ff;
        color: #4338ca;
        font-size: 12px;
        font-weight: 600;
    }

    .timeline-empty {
        text-align: center;
        padding: 50px 15px;
        color: #94a3b8;
    }

    .timeline-empty i {
        display: block;
        font-size: 34px;
        margin-bottom: 12px;
    }

    @media (max-width: 576px) {
        .horizontal-timeline-wrapper {
            padding: 15px;
        }

        .timeline-node {
            width: 255px;
            min-width: 255px;
            flex-basis: 255px;
        }

        .item-title-area h5 {
            font-size: 16px;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .timeline-modern-card {
            transition: none;
        }
    }
    .clock-widget{
        float: right;
    }
</style>
@endcan
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <section class="content-header">
    <div class="container-fluid">
        <div class="row align-items-center justify-content-between">

            {{-- Page Title --}}
        

        </div>
    </div>
</section>
    </div>
</div>
<div class="row mr-4">
    
    

    <div class="col-12">
    
        <div class="container-fluid">
     <section class="content">
            <div class="container-fluid">

                <!-- TOP KPI OVERVIEW CARDS -->
                {{-- <div class="row">
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-white border shadow-sm">
                            <div class="inner">
                                <h3 id="kpiTotalDuration" class="timer-display text-primary">04:18:22</h3>
                                <p class="text-muted mb-0 font-weight-bold">Shared Task Consumed Time</p>
                            </div>
                            <div class="icon"><i class="fas fa-stopwatch text-primary opacity-50"></i></div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-white border shadow-sm">
                            <div class="inner">
                                <h3 id="kpiStatus" class="text-capitalize text-success font-weight-bold">In Progress</h3>
                                <p class="text-muted mb-0 font-weight-bold">Current Task Status</p>
                            </div>
                            <div class="icon"><i class="fas fa-signal text-success opacity-50"></i></div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-white border shadow-sm">
                            <div class="inner">
                                <h3 id="kpiContributors">3 Users</h3>
                                <p class="text-muted mb-0 font-weight-bold">Active Team Contributors</p>
                            </div>
                            <div class="icon"><i class="fas fa-users text-warning opacity-50"></i></div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-white border shadow-sm">
                            <div class="inner">
                                <h3 id="kpiTimelineSteps">5 Steps</h3>
                                <p class="text-muted mb-0 font-weight-bold">Recorded Stage Checkpoints</p>
                            </div>
                            <div class="icon"><i class="fas fa-route text-info opacity-50"></i></div>
                        </div>
                    </div>
                </div> --}}

                <div class="tab-content">
                    
                    <!-- PANE 1: WORKBENCH & TIMER -->
                    <div class="tab-pane fade active show" id="tracker-pane">
                        
<!-- MAIN LIVE TIMER CARD CONTAINER -->
<div class="card card-primary card-outline shadow-sm">
    <div class="card-header bg-white d-flex align-items-center justify-content-between py-3">
        <div class="d-flex align-items-center">
            <span id="ticketBadge" class="badge badge-primary font-mono p-2 mr-2">{{ $artwork->item_code }}</span>
            <h3 id="taskTitleHeader" class="card-title font-weight-bold text-dark mb-0">Artwork Work Flow (GFLOW)</h3>
        </div>
        <span id="taskStatusBadge" class="badge {{ $artwork->is_running ? 'badge-success pulse-live' : ($artwork->timeLogs->last() && $artwork->timeLogs->last()->action === 'finish' ? 'badge-secondary' : 'badge-warning') }} status-pill">
            <i class="fas {{ $artwork->is_running ? 'fa-play-circle' : ($artwork->timeLogs->last() && $artwork->timeLogs->last()->action === 'finish' ? 'fa-check-circle' : 'fa-pause-circle') }} mr-1"></i>
            {{ $artwork->is_running ? 'Running' : ($artwork->timeLogs->last() && $artwork->timeLogs->last()->action === 'finish' ? 'Stopped' : 'Paused') }}
        </span>
    </div>

    <div class="card-body">
        <p id="taskDescHeader" class="text-muted mb-4">{{ $artwork->client_name }}</p>

        @php
            $lastLog = $artwork->timeLogs->whereIn('action', ['start', 'resume'])->last();
            $startTimeIso = ($artwork->is_running && $lastLog) ? $lastLog->recorded_at->toIso8601String() : '';
        @endphp

        <div class="p-4 rounded border bg-light d-flex flex-column flex-md-row align-items-center justify-content-between shadow-sm"
             id="live-counter"
             data-start="{{ $startTimeIso }}"
             data-accumulated="{{ $artwork->total_seconds }}"
             data-running="{{ $artwork->is_running ? 'true' : 'false' }}"
             data-action-state="{{ $artwork->timeLogs->last() ? $artwork->timeLogs->last()->action : '' }}">
             
            <input type="hidden" id="artwork-code" value="{{ $artwork->item_code }}">

            <div class="d-flex align-items-center mb-3 mb-md-0">
                <div class="p-3 bg-white border rounded-circle shadow-sm text-primary mr-3">
                    <i class="fas fa-clock fa-2x"></i>
                </div>
                <div>
                    <h6 class="text-uppercase font-weight-bold text-muted">Active Session Ticker (<span id="activeUserLabel" class="text-primary">{{ $artwork->artist_name ?? 'Unassigned' }}</span>)</h6>
                    <div id="sessionTimerDisplay" class="timer-display h2 font-weight-bold text-dark mb-0 text-monospace">00:00:00</div>
                </div>
            </div>

            <div class="d-flex align-items-center">
                
                <div class="mr-2">
                    @can('is-artist')
                    <button id="btnStart" class="btn btn-success btn-lg font-weight-bold px-4 action-btn" data-action="start">
                        <i class="fas fa-play mr-1"></i> Start
                    </button>
                    <button id="btnPause" class="btn btn-warning btn-lg font-weight-bold px-4 action-btn d-none" data-action="pause">
                        <i class="fas fa-pause mr-1"></i> Pause
                    </button>
                    <button id="btnResume" class="btn btn-info btn-lg font-weight-bold px-4 action-btn d-none" data-action="resume">
                        <i class="fas fa-redo mr-1"></i> Resume
                    </button>
                    @endcan
                </div>

                <div>
                     @can('is-artist')
                        <button id="btnFinish" class="btn btn-danger btn-lg font-weight-bold px-4 action-btn" data-action="finish">
                            <i class="fas fa-check-circle mr-1"></i> Finish Task
                        </button>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>



                <div class="row">
                    <!-- MULTI-USER ACCUMULATED TIME TABLE -->
               <div class="col-lg-7">
    <div class="card card-dark card-outline shadow-sm">
        <div class="card-header bg-white">
            <h3 class="card-title font-weight-bold text-dark">
                <i class="fas fa-users text-primary mr-2"></i>User Time Consumption (Item Code: <span id="sharedTaskIdLabel" class="text-primary font-mono">{{ $artwork->item_code }}</span>)
            </h3>
        </div>
        <div class="card-body p-0 table-responsive">
            <table class="table table-striped table-hover align-middle mb-0 text-sm">
                <thead class="thead-light">
                    <tr>
                        <th>Assigned User</th>
                        <th>Role</th>
                        <th>Time Spent</th>
                        <th>Duration Share</th>
                    </tr>
                </thead>
                <!-- Targeted ID for the jQuery script -->
                <tbody id="timeSpentDuration">
                    <tr>
                        <td colspan="4" class="text-center text-muted py-3">Loading breakdown records...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
                    <!-- TASK ASSETS & ATTACHMENTS -->
                    <div class="col-lg-5">
                        <div class="card card-secondary card-outline shadow-sm">
                            <div class="card-header bg-white">
                                <h3 class="card-title font-weight-bold text-dark">
                                    <i class="fas fa-paperclip text-info mr-2"></i> Item &amp; File Link
                                </h3>
                            </div>
                            <div class="card-body p-0">
                                <ul class="list-group list-group-flush" id="taskAssetsList">
                <li class="list-group-item d-flex align-items-center justify-content-between">
                <div>
                    <i class="far fa-file-alt text-primary mr-2"></i>
                    <strong class="text-dark">{{ $artwork->file_info }}</strong>
                    {{-- <small class="text-muted ml-2">(4.2 MB)</small> --}}
                </div>
                {{-- <button class="btn btn-xs btn-outline-primary" onclick="toastr.info('Downloading asset package...', 'Download')">
                    <i class="fas fa-download mr-1"></i> Get Link
                </button> --}}
                <a href="{{ $artwork->file_link }}" class="btn btn-xs btn-outline-info file-link" data-id="{{ $artwork->item_code }}" target="_blank">
                        <i class="fas fa-external-link-alt mr-1"></i> View Link
                    </a>
            </li>
        
          
        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- PANE 2: DELIVERY-STYLE TIMELINE -->
                    <div class="tab-pane fade" id="timeline-pane">
                        <div class="card card-info card-outline shadow-sm">
                            <div class="card-header bg-white d-flex align-items-center justify-content-between py-3">
                                <h3 class="card-title font-weight-bold text-dark">
                                    <i class="fas fa-truck text-info mr-2"></i> Delivery-Style Stage Execution Timeline
                                </h3>
                                <div class="card-tools">
                                    <select id="timelineFilterSelect" onchange="renderTimeline()" class="form-control form-control-sm">
                                        <option value="ALL">All Stages</option>
                                        <option value="START">Start Events</option>
                                        <option value="PAUSE">Pause Events</option>
                                        <option value="RESUME">Resume Events</option>
                                        <option value="FINISH">Finish Events</option>
                                    </select>
                                </div>
                            </div>

                            <div class="card-body bg-light">
                                <div class="delivery-timeline" id="deliveryTimelineContainer">
            <div class="delivery-step">
                <div class="d-flex align-items-start">
                    <div class="delivery-badge bg-success text-white mr-3">
                        <i class="fas fa-play"></i>
                    </div>
                    <div class="card card-outline card-light shadow-sm flex-fill mb-0">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="badge bg-success text-white px-2 py-1 font-weight-bold text-uppercase">START CHECKPOINT</span>
                                <small class="text-muted font-mono"><i class="far fa-clock mr-1"></i>2026-11-03 09:00:12</small>
                            </div>
                            <p class="font-weight-normal text-dark mb-2">Task initialized &amp; moodboard created.</p>
                            <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                                <div class="d-flex align-items-center">
                                    <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&amp;auto=format&amp;fit=crop&amp;q=80" class="img-circle elevation-1 mr-2" style="width:24px; height:24px;" alt="Alex Rivera">
                                    <span class="font-weight-bold text-dark text-xs">Alex Rivera</span>
                                    <small class="text-muted ml-1">(Lead Designer)</small>
                                </div>
                                <div>
                                    <small class="text-muted font-weight-bold mr-1">Duration:</small>
                                    <span class="badge badge-light border font-mono">+00:00:00</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        
            <div class="delivery-step">
                <div class="d-flex align-items-start">
                    <div class="delivery-badge bg-warning text-dark mr-3">
                        <i class="fas fa-pause"></i>
                    </div>
                    <div class="card card-outline card-light shadow-sm flex-fill mb-0">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="badge bg-warning text-dark px-2 py-1 font-weight-bold text-uppercase">PAUSE CHECKPOINT</span>
                                <small class="text-muted font-mono"><i class="far fa-clock mr-1"></i>2026-11-03 11:15:00</small>
                            </div>
                            <p class="font-weight-normal text-dark mb-2">Paused for client feedback review call.</p>
                            <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                                <div class="d-flex align-items-center">
                                    <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&amp;auto=format&amp;fit=crop&amp;q=80" class="img-circle elevation-1 mr-2" style="width:24px; height:24px;" alt="Alex Rivera">
                                    <span class="font-weight-bold text-dark text-xs">Alex Rivera</span>
                                    <small class="text-muted ml-1">(Lead Designer)</small>
                                </div>
                                <div>
                                    <small class="text-muted font-weight-bold mr-1">Duration:</small>
                                    <span class="badge badge-light border font-mono">+02:14:48</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        
            <div class="delivery-step">
                <div class="d-flex align-items-start">
                    <div class="delivery-badge bg-info text-white mr-3">
                        <i class="fas fa-redo"></i>
                    </div>
                    <div class="card card-outline card-light shadow-sm flex-fill mb-0">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="badge bg-info text-white px-2 py-1 font-weight-bold text-uppercase">RESUME CHECKPOINT</span>
                                <small class="text-muted font-mono"><i class="far fa-clock mr-1"></i>2026-11-03 13:00:00</small>
                            </div>
                            <p class="font-weight-normal text-dark mb-2">UI Component library drafting started.</p>
                            <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                                <div class="d-flex align-items-center">
                                    <img src="https://images.unsplash.com/photo-1517841905240-472988babdf9?w=100&amp;auto=format&amp;fit=crop&amp;q=80" class="img-circle elevation-1 mr-2" style="width:24px; height:24px;" alt="Elena Rostova">
                                    <span class="font-weight-bold text-dark text-xs">Elena Rostova</span>
                                    <small class="text-muted ml-1">(UI/UX Designer)</small>
                                </div>
                                <div>
                                    <small class="text-muted font-weight-bold mr-1">Duration:</small>
                                    <span class="badge badge-light border font-mono">+00:00:00</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        
            <div class="delivery-step">
                <div class="d-flex align-items-start">
                    <div class="delivery-badge bg-warning text-dark mr-3">
                        <i class="fas fa-pause"></i>
                    </div>
                    <div class="card card-outline card-light shadow-sm flex-fill mb-0">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="badge bg-warning text-dark px-2 py-1 font-weight-bold text-uppercase">PAUSE CHECKPOINT</span>
                                <small class="text-muted font-mono"><i class="far fa-clock mr-1"></i>2026-11-03 14:20:00</small>
                            </div>
                            <p class="font-weight-normal text-dark mb-2">Handed off 3D asset specs to Marcus.</p>
                            <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                                <div class="d-flex align-items-center">
                                    <img src="https://images.unsplash.com/photo-1517841905240-472988babdf9?w=100&amp;auto=format&amp;fit=crop&amp;q=80" class="img-circle elevation-1 mr-2" style="width:24px; height:24px;" alt="Elena Rostova">
                                    <span class="font-weight-bold text-dark text-xs">Elena Rostova</span>
                                    <small class="text-muted ml-1">(UI/UX Designer)</small>
                                </div>
                                <div>
                                    <small class="text-muted font-weight-bold mr-1">Duration:</small>
                                    <span class="badge badge-light border font-mono">+01:20:00</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        
            <div class="delivery-step">
                <div class="d-flex align-items-start">
                    <div class="delivery-badge bg-info text-white mr-3">
                        <i class="fas fa-redo"></i>
                    </div>
                    <div class="card card-outline card-light shadow-sm flex-fill mb-0">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="badge bg-info text-white px-2 py-1 font-weight-bold text-uppercase">RESUME CHECKPOINT</span>
                                <small class="text-muted font-mono"><i class="far fa-clock mr-1"></i>2026-11-03 15:00:00</small>
                            </div>
                            <p class="font-weight-normal text-dark mb-2">Cinema4D render testing initiated.</p>
                            <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                                <div class="d-flex align-items-center">
                                    <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100&amp;auto=format&amp;fit=crop&amp;q=80" class="img-circle elevation-1 mr-2" style="width:24px; height:24px;" alt="Marcus Vance">
                                    <span class="font-weight-bold text-dark text-xs">Marcus Vance</span>
                                    <small class="text-muted ml-1">(3D Motion Artist)</small>
                                </div>
                                <div>
                                    <small class="text-muted font-weight-bold mr-1">Duration:</small>
                                    <span class="badge badge-light border font-mono">+00:00:00</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
                            </div>
                        </div>
                    </div>

                    <!-- PANE 3: AUDIT & ACTION LOGS -->
                    <div class="tab-pane fade" id="activity-pane">
                        <div class="card card-warning card-outline shadow-sm">
                            <div class="card-header bg-white d-flex align-items-center justify-content-between">
                                <h3 class="card-title font-weight-bold text-dark">
                                    <i class="fas fa-history text-warning mr-2"></i> Activity Audit Feed
                                </h3>
                                <div class="card-tools">
                                    <input type="text" id="logSearchInput" class="form-control form-control-sm" placeholder="Search activity logs..." onkeyup="renderActivityLogs()">
                                </div>
                            </div>
                            <div class="card-body p-0 table-responsive" style="max-height: 500px;">
                                <table class="table table-hover table-striped mb-0 text-sm">
                                    <thead class="thead-light sticky-top">
                                        <tr>
                                            <th>Timestamp</th>
                                            <th>User</th>
                                            <th>Action Type</th>
                                            <th>Event Summary</th>
                                        </tr>
                                    </thead>
                                    <tbody id="activityLogsTableBody">
            <tr>
                <td class="font-mono text-muted text-nowrap"><i class="far fa-clock mr-1"></i>15:00:00</td>
                <td><strong>Marcus Vance</strong></td>
                <td><span class="badge badge-info">RESUME</span></td>
                <td>Resumed work on 3D Motion Assets</td>
            </tr>
        
            <tr>
                <td class="font-mono text-muted text-nowrap"><i class="far fa-clock mr-1"></i>14:20:00</td>
                <td><strong>Elena Rostova</strong></td>
                <td><span class="badge badge-warning">PAUSE</span></td>
                <td>Paused session. Logged 01h 20m</td>
            </tr>
        
            <tr>
                <td class="font-mono text-muted text-nowrap"><i class="far fa-clock mr-1"></i>13:00:00</td>
                <td><strong>Elena Rostova</strong></td>
                <td><span class="badge badge-info">RESUME</span></td>
                <td>Switched active session to Elena</td>
            </tr>
        
            <tr>
                <td class="font-mono text-muted text-nowrap"><i class="far fa-clock mr-1"></i>11:15:00</td>
                <td><strong>Alex Rivera</strong></td>
                <td><span class="badge badge-warning">PAUSE</span></td>
                <td>Paused session. Logged 02h 14m</td>
            </tr>
        
            <tr>
                <td class="font-mono text-muted text-nowrap"><i class="far fa-clock mr-1"></i>09:00:12</td>
                <td><strong>Alex Rivera</strong></td>
                <td><span class="badge badge-success">START</span></td>
                <td>Initialized project timer for TSK-9402</td>
            </tr>
        </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </section>
    </div>     

        {{-- end --}}

        {{-- request form   {{ route('artwork-request.store') }} --}}
        @include('partials.modal.supplied_file_verification')
        {{-- specifications form  {{ route('specifications.store') }} --}}
         @include('partials.modal.specifications')
        {{-- end specification --}}

        {{-- checklist form test --}}

            @can('read-only')
          <!-- A. CONTROL PANEL (Start a new task) -->
            <div class="card card-primary card-outline shadow-sm">
                <div class="card-header bg-light">
                    <h3 class="card-title font-weight-bold"><i class="fas fa-play-circle text-success mr-2"></i> Search Item</h3>
                </div>
                <div class="card-body p-3">
                    <form id="CreateForm">
                        <div class="row">
                            <!-- Column 1: Personnel -->
                            <div class="col-md-6" id="operator_section">
                                {{-- <div class="form-group mb-2">
                                    <label class="mb-1 text-sm">Operator: <span class="text-danger">*</span></label>
                                    <select id="operator_id" name="operator_id[]" class="form-control form-control-sm select2bs4" multiple required> --}}
                                        {{-- <option value="">Select Operator</option> --}}
                                    {{-- </select> --}}
                                {{-- </div> --}}
                                {{-- <div class="form-group mb-0">
                                    <label class="mb-1 text-sm">Companions:</label>
                                    <select id="asst_operator_id" name="asst_operator_id[]" class="form-control form-control-sm select2bs4" multiple>
                                        {{-- <option value="">Select Assistant</option> --}}
                                  
                                {{-- </div>  --}}

                            <div class="row align-items-end mb-3">
                            <div class="col-md-12">
                                <div class="form-group mb-0">
                                    <label class="mb-1 text-sm" id="target_label"></label>
                                    <div class="d-flex align-items-center">
                                        
                                        <!-- PRODUCTION BUTTONS -->
                                        <div id="mo_jo_buttons">
                                            <!-- Search MO Button -->
                                            <button type="button" class="btn btn-secondary mr-2" onclick="showMOSearch()">
                                                <i class="fas fa-search"></i> Search Item
                                            </button>

                                            <!-- Manual JO Button -->
                                            {{-- @if($user->allow_manual_jo) --}}
                                                <button type="button" class="btn btn-outline-primary" onclick="showManualJOModal()">
                                                    <i class="fas fa-edit"></i> External / Campaign Ref
                                                </button>
                                            {{-- @endif --}}
                                        </div>

                                        <!-- DOWNTIME BUTTON (Hidden by default) -->
                                        <div id="downtime_button_wrapper" class="d-none">
                                            <button type="button" class="btn btn-danger shadow-sm font-weight-bold px-4" onclick="startDowntime()">
                                                <i class="fas fa-power-off"></i> Start Downtime
                                            </button>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                            </div>

                            <!-- Column 2: Action Setup -->
                            <div class="col-md-6 border-left">
                                <div class="form-group mb-2">
                                    <label class="mb-1 text-sm">Action Type: <span class="text-danger">*</span></label>
                                    <select class="form-control select2 select2-hidden-accessible" style="width: 100%;" data-select2-id="1" tabindex="-1" aria-hidden="true">
                                        <option selected="selected" data-select2-id="3">ArtWorkFlow</option>
                                        <option data-select2-id="35">Retouching</option>
                                        <option data-select2-id="36">Apparel</option>
                                        <option data-select2-id="37">Revision</option>
                                       
                                    </select>
                                    {{-- <div class="d-flex action-btn-group gap-2">
                                        <button type="button" class="btn btn-sm btn-success mr-1">DIRECT</button>
                                        <button type="button" class="btn btn-sm btn-warning mr-1 text-dark">NON-PROD.</button>
                                        <button type="button" class="btn btn-sm btn-danger">PROD. DOWN</button>
                                    </div> --}}
                                </div>
                                <div class="form-group mb-2">
                                    {{-- <label class="mb-1 text-sm">Action: <span class="text-danger">*</span></label>
                                    <select id="action_id" class="form-control form-control-sm select2bs4">
                                        <option value="">Select Action...</option>
                                    </select> --}}
                                </div>

                                <!-- ========================================== -->
                                <!-- INSERT NEW CONFIGURATION CONTAINER HERE    -->
                                <!-- ========================================== -->
                                <div id="job_type_container" class="form-group mt-3" style="display: none;">
                                    <label class="mb-1 text-sm">Job Type <span class="text-danger">*</span></label>
                                    <div class="d-flex align-items-center text-sm">
                                        <div class="custom-control custom-radio mr-3">
                                            <input class="custom-control-input" type="radio" id="type_new" name="job_type" value="NEW">
                                            <label for="type_new" class="custom-control-label">NEW</label>
                                        </div>
                                        
                                        {{-- Make Ready Specific --}}
                                        <div class="custom-control custom-radio mr-3" id="wrapper_reorder" style="display: none;">
                                            <input class="custom-control-input" type="radio" id="type_reorder" name="job_type" value="RE-ORDER">
                                            <label for="type_reorder" class="custom-control-label">RE-ORDER</label>
                                        </div>

                                        {{-- Running Specific --}}
                                        <div class="custom-control custom-radio" id="wrapper_replate" style="display: none;">
                                            <input class="custom-control-input" type="radio" id="type_replate" name="job_type" value="REPLATE">
                                            <label for="type_replate" class="custom-control-label">REPLATE</label>
                                        </div>
                                    </div>
                                </div>

                                {{-- Replate Reason --}}
                                <div id="replate_reason_container" class="form-group mt-2" style="display: none;">
                                    <label class="mb-1 text-sm">Replate Reason <span class="text-danger">*</span></label>
                                    <select id="replate_reason" class="form-control form-control-sm select2bs4">
                                        <option value="">Select Reason for Replating...</option>
                                        @foreach($replate_reasons ?? [] as $item)
                                            <option value="{{ $item->name }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- ========================================== -->
                                <!-- END NEW CONFIGURATION CONTAINER            -->
                                <!-- ========================================== -->
                            </div>
                        </div>

                        <hr class="my-3">

                        <!-- Bottom Row: MO Selection & Start -->
          
                    </form>
                </div>
            </div>

        <div class="row justify-content-center">
        <!-- Left Side: Profile Summary Card -->
        <div class="col-md-4 mt-5">
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                   <form action="" method="POST">
    @csrf
    
    <div class="form-group">
        <label for="select-tag">Select Item:</label>
        <select id="select-tag" name="tag_id" class="form-control select2 @error('tag_id') is-invalid @enderror" style="width: 100%;">
            <option value="">-- Choose an option --</option>
            {{-- @foreach($availableItemsForSelect as $list)
                <option value="{{ $list->item_code }}" {{ old('tag_id') == $list->item_code ? 'selected' : '' }}>
                    {{ $list->item_code }}
                </option>
            @endforeach --}}
        </select>
        
        @error('tag_id')
            {{-- <span class="error invalid-feedback">{{ $message }}</span> --}}
        @enderror
    </div>
</form>

                    <button id="btn_start_time"class="btn btn-secondary btn-success" tabindex="0" aria-controls="UsersTable" type="button" title="New" >
                <span>
                    <i class="fas fa-start"></i>
                    Start
                </span>
            </button>
            <button class="btn btn-secondary btn-warning" tabindex="0" aria-controls="UsersTable" type="button" title="New">
                <span>
                    <i class="fas fa"></i>
                    Pause
                </span>
            </button>
            <button id="btn_stop_time" class="btn btn-secondary btn-danger" tabindex="0" aria-controls="UsersTable" type="button" title="New">
                <span>
                    <i class="fas fa"></i>
                    Stop
                </span>
            </button>
                </div>
            </div>
        </div>

        <!-- Right Side: Detailed Profile Information -->
        <div class="col-md-6 mt-5">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-user-circle mr-1"></i> Account Details
                    </h3>
                </div>
                <div class="card-body">
                    <strong><i class="fas fa-id-card mr-1 text-secondary"></i> Full Name</strong>
                    <p class="text-muted">Aljay F. Ramile</p>
                    <hr>

                    <strong><i class="fas fa-envelope mr-1 text-secondary"></i> Email Address</strong>
                    <p class="text-muted">aframile@ugecorp.com</p>
                    <hr>

                    <strong><i class="fas fa-user-shield mr-1 text-secondary"></i> Account Status</strong>
                    <p class="text-muted">
                                                    <span class="badge badge-warning">Unverified</span>
                                            </p>
                </div>
                <div class="card-footer text-right">
                    <a href="http://localhost:9000/users/1/edit" class="btn btn-primary btn-sm">
                        <i class="fas fa-edit mr-1"></i> Edit Profile
                    </a>
                </div>
            </div>
        </div>
    </div>

<form action="" method="POST">
    @csrf
    
    <div class="form-group">
        <label for="select-tag">Select Item:</label>
        <select id="select-tag" name="tag_id" class="form-control select2 @error('tag_id') is-invalid @enderror" style="width: 100%;">
            <option value="">-- Choose an option --</option>
            @foreach($availableItemsForSelect as $list)
                <option value="{{ $list->item_code }}" {{ old('tag_id') == $list->item_code ? 'selected' : '' }}>
                    {{ $list->item_code }}
                </option>
            @endforeach
        </select>
        
        @error('tag_id')
            <span class="error invalid-feedback">{{ $message }}</span>
        @enderror
    </div>
</form>

                    <button id="btn_start_time"class="btn btn-secondary btn-success" tabindex="0" aria-controls="UsersTable" type="button" title="New" >
                <span>
                    <i class="fas fa-start"></i>
                    Start
                </span>
            </button>
            <button class="btn btn-secondary btn-warning" tabindex="0" aria-controls="UsersTable" type="button" title="New">
                <span>
                    <i class="fas fa"></i>
                    Pause
                </span>
            </button>
            <button id="btn_stop_time" class="btn btn-secondary btn-danger" tabindex="0" aria-controls="UsersTable" type="button" title="New">
                <span>
                    <i class="fas fa"></i>
                    Stop
                </span>
            </button>


    @can('is-readonly')
    <div class="row  mr-4">
    <div class="col-12 mt-4 ">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-solid fa-users"> </i> List of Users</h3>
            </div>
              <!-- /.card-header -->
            <div class="card-body">
                
                <br>
                <div class="row">
                    <div class="col-md-12">
                        <table id="UsersTable" class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>User_id</th>
                                    <th>Item Code</th>
                                    <th>Start_time</th>
                                    <th>Stop_time</th>
                                    <th>Status</th>
                                
                                    <!--<th>Actions</th>-->
                                    
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>           
    </div>
</div> 
@endcan


<!-- Modern Tracking Grid Wrapper --> 
<div class="timeline-tracking-grid d-flex flex-column gap-5 p-4" style="background: #f8fafc; font-family: system-ui, -apple-system, sans-serif;">
    @php $currentGroupCode = null; @endphp

    @forelse($timeLineList as $row)
        {{-- Every unique item code drops down neatly to its own full width row line --}}
        @if($currentGroupCode !== $row->item_code)
            @if(!$loop->first)
                        </div> <!-- End horizontal scroll step track container -->
                    </div> <!-- End lane right node container track row -->
                </div> <!-- End global item tracking block wrapper line -->
            @endif

            {{-- 1. CLEAN MODERN BLOCK ROW COMPONENT CONTAINER --}}
            <div class="asset-tracking-row-block bg-white rounded-2xl p-4 shadow-sm border border-slate-100 w-100 d-block mb-4" style="border-radius: 16px;">
                
                <!-- Minimal Row Header Title Line Layout -->
                <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom border-slate-100 w-100">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-xl d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: #eff6ff; color: #2563eb; border-radius: 12px;">
                            <i class="fas fa-cube" style="font-size: 16px;"></i>
                        </div>
                        <div>
                            <h5 class="m-0 font-weight-bold tracking-tight text-slate-900" style="font-size: 16px; color: #0f172a;">
                                {{ $row->item_name ?? 'Item Tracking Log' }}
                            </h5>
                            <span class="text-slate-400 font-weight-medium" style="font-size: 12px; color: #64748b;">Code Reference: {{ $row->item_code }}</span>
                        </div>
                    </div>
                </div>

                <!-- Horizontal Process Flow Stream -->
                <div class="item-process-lane-track w-100">
                    <div class="horizontal-step-track d-flex flex-nowrap overflow-auto pb-2 pt-3 align-items-start" style="position: relative; gap: 40px;">
                        
                        <!-- Minimal background connect rail line -->
                        <div class="process-rail-line" style="position: absolute; top: 18px; left: 24px; right: 24px; height: 2px; background: #e2e8f0; z-index: 1;"></div>
            
            @php $currentGroupCode = $row->item_code; @endphp
        @endif

        {{-- 2. MINIMALIST PLAY / START EVENT NODE --}}
        @if($row->start_time)
            <div class="process-node-card flex-shrink-0 text-left" style="width: 240px; position: relative; z-index: 2;">
                <!-- Modern Interactive Mini Circle Node Pin -->
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" 
                         style="width: 36px; height: 36px; background: #bbf7d0; color: #15803d; border: 4px solid #fff;">
                        <i class="fas fa-play" style="font-size: 10px;"></i>
                    </div>
                    <div>
                        <span class="badge font-weight-bold px-2 py-0.5" style="background: #f0fdf4; color: #16a34a; font-size: 11px; border-radius: 6px;">STARTED</span>
                    </div>
                </div>

                <!-- Translucent Modern Data Card Capsule -->
                <div class="p-3 border border-slate-100 shadow-sm transition-all" style="background: #fafafa; border-radius: 12px;">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <small class="text-slate-500 font-weight-semibold" style="font-size: 11px; color: #64748b;"><i class="far fa-clock mr-1"></i> {{ \Carbon\Carbon::parse($row->start_time)->format('h:i A') }}</small>
                        <small class="text-slate-400" style="font-size: 11px; color: #94a3b8;">{{ \Carbon\Carbon::parse($row->start_time)->format('M d, Y') }}</small>
                    </div>
                    
                    <div class="bg-white p-2 rounded border border-slate-100 d-flex align-items-center shadow-2xs" style="border-radius: 8px;">
                        <div class="bg-slate-50 text-slate-400 rounded p-1 mr-2 d-flex align-items-center justify-content-center" style="width: 24px; height: 24px; border-radius: 6px;">
                            <i class="fas fa-user-check" style="font-size: 11px;"></i>
                        </div>
                        <div style="line-height: 1.1; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                            <span class="d-block font-weight-bold text-slate-800" style="font-size: 12px; color: #334155;">{{ $row->user_name ?? 'System' }}</span>
                            <span class="text-slate-400 font-weight-medium" style="font-size: 10px; color: #94a3b8;">ID: {{ $row->user_id }}</span>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- 3. MINIMALIST STOP / PAUSE EVENT NODE --}}
        @if($row->stop_time)
            <div class="process-node-card flex-shrink-0 text-left" style="width: 240px; position: relative; z-index: 2;">
                <!-- Modern Interactive Mini Circle Node Pin -->
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" 
                         style="width: 36px; height: 36px; background: #fecaca; color: #b91c1c; border: 4px solid #fff;">
                        <i class="fas fa-stop" style="font-size: 10px;"></i>
                    </div>
                    <div>
                        <span class="badge font-weight-bold px-2 py-0.5" style="background: #fef2f2; color: #dc2626; font-size: 11px; border-radius: 6px;">STOPPED</span>
                    </div>
                </div>

                <!-- Translucent Modern Data Card Capsule -->
                <div class="p-3 border border-slate-100 shadow-sm transition-all" style="background: #fafafa; border-radius: 12px;">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <small class="text-slate-500 font-weight-semibold" style="font-size: 11px; color: #64748b;"><i class="far fa-clock mr-1"></i> {{ \Carbon\Carbon::parse($row->stop_time)->format('h:i A') }}</small>
                        <small class="text-slate-400" style="font-size: 11px; color: #94a3b8;">{{ \Carbon\Carbon::parse($row->stop_time)->format('M d, Y') }}</small>
                    </div>
                    
                    <div class="bg-white p-2 rounded border border-slate-100 d-flex align-items-center shadow-2xs mb-2" style="border-radius: 8px;">
                        <div class="bg-slate-50 text-slate-400 rounded p-1 mr-2 d-flex align-items-center justify-content-center" style="width: 24px; height: 24px; border-radius: 6px;">
                            <i class="fas fa-user-check" style="font-size: 11px;"></i>
                        </div>
                        <div style="line-height: 1.1; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                            <span class="d-block font-weight-bold text-slate-800" style="font-size: 12px; color: #334155;">{{ $row->user_name ?? 'System' }}</span>
                            <span class="text-slate-400 font-weight-medium" style="font-size: 10px; color: #94a3b8;">ID: {{ $row->user_id }}</span>
                        </div>
                    </div>

                    @if($row->start_time)
                        <div class="d-flex align-items-center justify-content-between px-1 py-1 rounded" style="background: #f1f5f9;">
                            <span class="text-uppercase tracking-wider font-weight-bold text-slate-400" style="font-size: 9px; color: #64748b;">Duration Tracked</span>
                            <span class="font-weight-bold text-slate-700" style="font-size: 11px; color: #334155;">
                                {{ \Carbon\Carbon::parse($row->start_time)->diffForHumans(\Carbon\Carbon::parse($row->stop_time), true) }}
                            </span>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        {{-- Tie together all row markup layout segments at loop termination --}}
        @if($loop->last)
                    </div> <!-- End horizontal step track container -->
                </div> <!-- End lane right node container track row -->
            </div> <!-- End global item tracking block wrapper line -->
        @endif

    @empty
        <div class="w-100 text-center p-5 bg-white border rounded-2xl shadow-xs" style="border-radius: 16px;">
            <div class="text-slate-300 mb-3"><i class="fas fa-inbox fa-2x" style="color: #cbd5e1;"></i></div>
            <p class="m-0 font-weight-bold text-slate-500" style="color: #64748b;">No horizontal timeline activity metrics captured.</p>
        </div>
    @endforelse
</div>


{{-- ------------------------------------------ --}}

<!-- Modern Circular Timeline Track Grid --> 
<div class="timeline-tracking-grid d-flex flex-column gap-5 p-4" style="background: #f8fafc; font-family: system-ui, -apple-system, sans-serif;">
    @php $currentGroupCode = null; @endphp

    @forelse($timeLineList as $row)
        {{-- Every unique item code drops down neatly to its own full width row lane --}}
        @if($currentGroupCode !== $row->item_code)
            @if(!$loop->first)
                        </div> <!-- End horizontal scroll track -->
                    </div> <!-- End lane container -->
                </div> <!-- End global item tracking block -->
            @endif

            {{-- 1. STREAMLINED ROW CONTAINER --}}
            <div class="asset-tracking-row-block bg-white p-4 shadow-sm border border-slate-100 w-100 d-block mb-5" style="border-radius: 24px;">
                
                <!-- Modern Row Header Title Layout -->
                <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom border-slate-100 w-100">
                    <div class="d-flex align-items-center gap-3">
                        <div class="d-flex align-items-center justify-content-center shadow-xs" style="width: 54px; height: 54px; background: #f1f5f9; color: #475569; border-radius: 50%;">
                            <i class="fas fa-layer-group" style="font-size: 22px;"></i>
                        </div>
                        <div>
                            <!-- Increased Main Title to h4 and font-size: 20px -->
                            <h4 class="m-0 font-weight-bold tracking-tight" style="font-size: 20px; color: #0f172a; letter-spacing: -0.3px;">
                                {{ $row->item_name ?? 'Item Tracking Log' }}
                            </h4>
                            <!-- Increased Reference Subtitle to font-size: 14px -->
                            <span class="font-weight-medium" style="font-size: 14px; color: #64748b;">Identifier: <strong class="text-primary">{{ $row->item_code }}</strong></span>
                        </div>
                    </div>
                </div>

                <!-- Horizontal Flow Lane Track -->
                <div class="item-process-lane-track w-100">
                    <div class="horizontal-step-track d-flex flex-nowrap overflow-auto pb-4 pt-4 align-items-start" style="position: relative; gap: 56px;">
                        
                        <!-- Smooth underlying tracking connection rail line (adjusted top alignment for larger circles) -->
                        <div class="process-rail-line" style="position: absolute; top: 80px; left: 60px; right: 60px; height: 4px; background: #e2e8f0; z-index: 1; border-radius: 10px;"></div>
            
            @php $currentGroupCode = $row->item_code; @endphp
        @endif

        {{-- 2. ULTRA-MODERN CIRCULAR START EVENT NODE --}}
        @if($row->start_time)
            <div class="process-node-card flex-shrink-0 text-center" style="width: 170px; position: relative; z-index: 2;">
                <!-- Main Concentric Status Circle Ring Container (Scaled up to 120px) -->
                <div class="d-flex justify-content-center mb-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center shadow" 
                         style="width: 120px; height: 120px; background: #f0fdf4; border: 4px solid #fff; transition: transform 0.2s ease;">
                        <div class="rounded-circle d-flex flex-column align-items-center justify-content-center text-success" 
                             style="width: 100px; height: 100px; background: #ffffff; border: 2px dashed #bbf7d0;">
                            <i class="fas fa-play mb-1" style="font-size: 14px;"></i>
                            <!-- Increased Badge text inside circle to font-size: 12px -->
                            <span class="font-weight-bold tracking-wider" style="font-size: 12px; letter-spacing: 0.5px;">START</span>
                        </div>
                    </div>
                </div>

                <!-- Circular Meta Information Block Details -->
                <div class="text-center px-1">
                    <!-- Increased Time font to font-size: 16px -->
                    <div class="font-weight-bold text-slate-800 mb-1" style="font-size: 16px; color: #1e293b;">{{ \Carbon\Carbon::parse($row->start_time)->format('h:i A') }}</div>
                    <!-- Increased Date font to font-size: 13px -->
                    <div class="text-slate-400 mb-2 font-weight-medium" style="font-size: 13px; color: #94a3b8;">{{ \Carbon\Carbon::parse($row->start_time)->format('M d, Y') }}</div>
                    
                    <!-- Increased User capsule sizing and font-size: 13px -->
                    <div class="inline-flex align-items-center bg-light px-3 py-2 border text-truncate w-100" style="border-radius: 20px; font-size: 13px; background: #f8fafc;" title="{{ $row->user_name ?? 'System' }}">
                        <i class="fas fa-user-circle text-muted mr-1" style="font-size: 14px;"></i>
                        <span class="text-slate-600 font-weight-bold text-truncate d-inline-block" style="max-width: 110px;">{{ $row->user_name ?? 'System' }}</span>
                    </div>
                </div>
            </div>
        @endif

        {{-- 3. ULTRA-MODERN CIRCULAR STOP EVENT NODE --}}
        @if($row->stop_time)
            <div class="process-node-card flex-shrink-0 text-center" style="width: 170px; position: relative; z-index: 2;">
                <!-- Main Concentric Status Circle Ring Container (Scaled up to 120px) -->
                <div class="d-flex justify-content-center mb-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center shadow" 
                         style="width: 120px; height: 120px; background: #fef2f2; border: 4px solid #fff; transition: transform 0.2s ease;">
                        <div class="rounded-circle d-flex flex-column align-items-center justify-content-center text-danger" 
                             style="width: 100px; height: 100px; background: #ffffff; border: 2px dashed #fecaca;">
                            <i class="fas fa-stop mb-1" style="font-size: 14px;"></i>
                            <!-- Increased Badge text inside circle to font-size: 12px -->
                            <span class="font-weight-bold tracking-wider" style="font-size: 12px; letter-spacing: 0.5px;">STOP</span>
                        </div>
                    </div>
                </div>

                <!-- Circular Meta Information Block Details -->
                <div class="text-center px-1">
                    <!-- Increased Time font to font-size: 16px -->
                    <div class="font-weight-bold text-slate-800 mb-1" style="font-size: 16px; color: #1e293b;">{{ \Carbon\Carbon::parse($row->stop_time)->format('h:i A') }}</div>
                    <!-- Increased Date font to font-size: 13px -->
                    <div class="text-slate-400 mb-2 font-weight-medium" style="font-size: 13px; color: #94a3b8;">{{ \Carbon\Carbon::parse($row->stop_time)->format('M d, Y') }}</div>
                    
                    <!-- Duration Badge Bubble Pill (Increased padding and font-size: 12px) -->
                    @if($row->start_time)
                        <div class="mb-2">
                            <span class="badge text-white font-weight-bold px-3 py-1" style="background: #475569; font-size: 12px; border-radius: 12px;">
                                <i class="fas fa-hourglass-half mr-1"></i>{{ \Carbon\Carbon::parse($row->start_time)->diffForHumans(\Carbon\Carbon::parse($row->stop_time), true) }}
                            </span>
                        </div>
                    @endif

                    <!-- Increased User capsule sizing and font-size: 13px -->
                    <div class="inline-flex align-items-center bg-light px-3 py-2 border text-truncate w-100" style="border-radius: 20px; font-size: 13px; background: #f8fafc;" title="{{ $row->user_name ?? 'System' }}">
                        <i class="fas fa-user-circle text-muted mr-1" style="font-size: 14px;"></i>
                        <span class="text-slate-600 font-weight-bold text-truncate d-inline-block" style="max-width: 110px;">{{ $row->user_name ?? 'System' }}</span>
                    </div>
                </div>
            </div>
        @endif

        {{-- Final closure tags structure generation validation check --}}
        @if($loop->last)
                    </div> <!-- End horizontal scroll track -->
                </div> <!-- End lane container -->
            </div> <!-- End global item tracking block -->
        @endif

    @empty
        <div class="w-100 text-center p-5 bg-white border shadow-xs" style="border-radius: 24px;">
            <div class="text-slate-300 mb-2"><i class="fas fa-circle-notch fa-spin fa-2x" style="color: #cbd5e1;"></i></div>
            <p class="m-0 font-weight-bold text-slate-500" style="color: #64748b; font-size: 16px;">No horizontal timeline activity metrics captured.</p>
        </div>
    @endforelse
</div>
<!-- Modern Circular Timeline Track Grid --> 
<div class="timeline-tracking-grid d-flex flex-column gap-5 p-4" style="background: #f8fafc; font-family: system-ui, -apple-system, sans-serif;">
    @php $currentGroupCode = null; @endphp

    @forelse($timeLineList as $row)
        {{-- Every unique item code drops down neatly to its own full width row lane --}}
        @if($currentGroupCode !== $row->item_code)
            @if(!$loop->first)
                        </div> <!-- End horizontal scroll track -->
                    </div> <!-- End lane container -->
                </div> <!-- End global item tracking block -->
            @endif

            {{-- 1. STREAMLINED ROW CONTAINER --}}
            <div class="asset-tracking-row-block bg-white p-4 shadow-sm border border-slate-100 w-100 d-block mb-5" style="border-radius: 24px;">
                
                <!-- Modern Row Header Title Layout -->
                <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom border-slate-100 w-100">
                    <div class="d-flex align-items-center gap-3">
                        <div class="d-flex align-items-center justify-content-center shadow-xs" style="width: 54px; height: 54px; background: #f1f5f9; color: #475569; border-radius: 50%;">
                            <i class="fas fa-layer-group" style="font-size: 22px;"></i>
                        </div>
                        <div>
                            <!-- Increased Main Title to h4 and font-size: 20px -->
                            <h4 class="m-0 font-weight-bold tracking-tight" style="font-size: 20px; color: #0f172a; letter-spacing: -0.3px;">
                                {{ $row->item_name ?? 'Item Tracking Log' }}
                            </h4>
                            <!-- Increased Reference Subtitle to font-size: 14px -->
                            <span class="font-weight-medium" style="font-size: 14px; color: #64748b;">Identifier: <strong class="text-primary">{{ $row->item_code }}</strong></span>
                        </div>
                    </div>
                </div>

                <!-- Horizontal Flow Lane Track -->
                <div class="item-process-lane-track w-100">
                    <div class="horizontal-step-track d-flex flex-nowrap overflow-auto pb-4 pt-4 align-items-start" style="position: relative; gap: 56px;">
                        
                        <!-- Smooth underlying tracking connection rail line (adjusted top alignment for larger circles) -->
                        <div class="process-rail-line" style="position: absolute; top: 80px; left: 60px; right: 60px; height: 4px; background: #e2e8f0; z-index: 1; border-radius: 10px;"></div>
            
            @php $currentGroupCode = $row->item_code; @endphp
        @endif

        {{-- 2. ULTRA-MODERN CIRCULAR START EVENT NODE --}}
        @if($row->start_time)
            <div class="process-node-card flex-shrink-0 text-center" style="width: 170px; position: relative; z-index: 2;">
                <!-- Main Concentric Status Circle Ring Container (Scaled up to 120px) -->
                <div class="d-flex justify-content-center mb-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center shadow" 
                         style="width: 120px; height: 120px; background: #f0fdf4; border: 4px solid #fff; transition: transform 0.2s ease;">
                        <div class="rounded-circle d-flex flex-column align-items-center justify-content-center text-success" 
                             style="width: 100px; height: 100px; background: #ffffff; border: 2px dashed #bbf7d0;">
                            <i class="fas fa-play mb-1" style="font-size: 14px;"></i>
                            <!-- Increased Badge text inside circle to font-size: 12px -->
                            <span class="font-weight-bold tracking-wider" style="font-size: 12px; letter-spacing: 0.5px;">START</span>
                        </div>
                    </div>
                </div>

                <!-- Circular Meta Information Block Details -->
                <div class="text-center px-1">
                    <!-- Increased Time font to font-size: 16px -->
                    <div class="font-weight-bold text-slate-800 mb-1" style="font-size: 16px; color: #1e293b;">{{ \Carbon\Carbon::parse($row->start_time)->format('h:i A') }}</div>
                    <!-- Increased Date font to font-size: 13px -->
                    <div class="text-slate-400 mb-2 font-weight-medium" style="font-size: 13px; color: #94a3b8;">{{ \Carbon\Carbon::parse($row->start_time)->format('M d, Y') }}</div>
                    
                    <!-- Increased User capsule sizing and font-size: 13px -->
                    <div class="inline-flex align-items-center bg-light px-3 py-2 border text-truncate w-100" style="border-radius: 20px; font-size: 13px; background: #f8fafc;" title="{{ $row->user_name ?? 'System' }}">
                        <i class="fas fa-user-circle text-muted mr-1" style="font-size: 14px;"></i>
                        <span class="text-slate-600 font-weight-bold text-truncate d-inline-block" style="max-width: 110px;">{{ $row->user_name ?? 'System' }}</span>
                    </div>
                </div>
            </div>
        @endif

        {{-- 3. ULTRA-MODERN CIRCULAR STOP EVENT NODE --}}
        @if($row->stop_time)
            <div class="process-node-card flex-shrink-0 text-center" style="width: 170px; position: relative; z-index: 2;">
                <!-- Main Concentric Status Circle Ring Container (Scaled up to 120px) -->
                <div class="d-flex justify-content-center mb-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center shadow" 
                         style="width: 120px; height: 120px; background: #fef2f2; border: 4px solid #fff; transition: transform 0.2s ease;">
                        <div class="rounded-circle d-flex flex-column align-items-center justify-content-center text-danger" 
                             style="width: 100px; height: 100px; background: #ffffff; border: 2px dashed #fecaca;">
                            <i class="fas fa-stop mb-1" style="font-size: 14px;"></i>
                            <!-- Increased Badge text inside circle to font-size: 12px -->
                            <span class="font-weight-bold tracking-wider" style="font-size: 12px; letter-spacing: 0.5px;">STOP</span>
                        </div>
                    </div>
                </div>

                <!-- Circular Meta Information Block Details -->
                <div class="text-center px-1">
                    <!-- Increased Time font to font-size: 16px -->
                    <div class="font-weight-bold text-slate-800 mb-1" style="font-size: 16px; color: #1e293b;">{{ \Carbon\Carbon::parse($row->stop_time)->format('h:i A') }}</div>
                    <!-- Increased Date font to font-size: 13px -->
                    <div class="text-slate-400 mb-2 font-weight-medium" style="font-size: 13px; color: #94a3b8;">{{ \Carbon\Carbon::parse($row->stop_time)->format('M d, Y') }}</div>
                    
                    <!-- Duration Badge Bubble Pill (Increased padding and font-size: 12px) -->
                    @if($row->start_time)
                        <div class="mb-2">
                            <span class="badge text-white font-weight-bold px-3 py-1" style="background: #475569; font-size: 12px; border-radius: 12px;">
                                <i class="fas fa-hourglass-half mr-1"></i>{{ \Carbon\Carbon::parse($row->start_time)->diffForHumans(\Carbon\Carbon::parse($row->stop_time), true) }}
                            </span>
                        </div>
                    @endif

                    <!-- Increased User capsule sizing and font-size: 13px -->
                    <div class="inline-flex align-items-center bg-light px-3 py-2 border text-truncate w-100" style="border-radius: 20px; font-size: 13px; background: #f8fafc;" title="{{ $row->user_name ?? 'System' }}">
                        <i class="fas fa-user-circle text-muted mr-1" style="font-size: 14px;"></i>
                        <span class="text-slate-600 font-weight-bold text-truncate d-inline-block" style="max-width: 110px;">{{ $row->user_name ?? 'System' }}</span>
                    </div>
                </div>
            </div>
        @endif

        {{-- Final closure tags structure generation validation check --}}
        @if($loop->last)
                    </div> <!-- End horizontal scroll track -->
                </div> <!-- End lane container -->
            </div> <!-- End global item tracking block -->
        @endif

    @empty
        <div class="w-100 text-center p-5 bg-white border shadow-xs" style="border-radius: 24px;">
            <div class="text-slate-300 mb-2"><i class="fas fa-circle-notch fa-spin fa-2x" style="color: #cbd5e1;"></i></div>
            <p class="m-0 font-weight-bold text-slate-500" style="color: #64748b; font-size: 16px;">No horizontal timeline activity metrics captured.</p>
        </div>
    @endforelse
</div>


@endcan


    </div>
</div> 

@endsection
    
@section('js')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    $(document).ready(function () {
        
        // ---------------------------------------------------------
        // SWEETALERT SUCCESS TOAST
        // ---------------------------------------------------------
        var Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 5000
        });

        @if (\Session::has('success'))
            Toast.fire({
                icon:'success',
                title:"{{ \Session::get('success') }}"
            });
        @endif

        window.addEventListener('load', function() {
            // This runs only after jQuery, Select2, and all assets are 100% loaded
            $('#select-tag').select2({
                placeholder: 'Search for a Item...',
                allowClear: true
            });
        });
       

$('#FileSendTable').on('click', '.btn-verify', function () {
    var recordId = $(this).data('id');
  
    console.log($(this).data());

    $.ajax({
        url: "/suppliedVerified/" + recordId, // Replace with your Laravel route URL
        type: "GET",
        dataType: "json",
        success: function (response) {
            console.log(response.data);
            // Populate all modal fields using response data
            // console.log(response.data.item_code)
           $('#item_code_label').text(response.data.item_code);
           $('#verify_file_id').val(recordId);
             $('#item_code').val(response.data.item_code);
              $('#client').val(response.data.id);
              $('#spread_h').val(response.data.spread_h);
              $('#spread_w').val(response.data.spread_w);
              $('#spread_l').val(response.data.spread_l);
              $('#folded_h').val(response.data.spread_h);
              $('#folded_w').val(response.data.spread_w);
              $('#folded_l').val(response.data.spread_l);
               $('#default_color_text').val(response.data.colors);
               $('#text_specs').val(response.data.text_specs);
              $('#item_description').val(response.data.file_info);
       
      
            // $('#item_description').val(item_description);
            
            // Handle links or custom elements
            if (response.file_link) {
                $('#modal_file_link').attr('href', response.file_link).removeClass('d-none');
            } else {
                $('#modal_file_link').addClass('d-none');
            }

            // If you have nested relations, access them directly:
            // $('#modal_client_address').val(response.client ? response.client.address : '');
        },
        error: function (xhr, status, error) {
            console.error("Failed to fetch full record:", error);
            alert("Could not load details. Please try again.");
        }
    });
    // Populate data inside the modal form
    

    // Show the modal
    $('#verificationModal').modal('show');
});


$('#btn_start_time').on('click', function() {
        // 1. Get the selected ID value from your Select2 input
        
        let selectedId = $('#select-tag').val();
        // 2. Alert the user if they haven't selected an option yet
        if (!selectedId) {
            Toast.fire({ icon: 'error', title: 'Please select a Item first!' });
            return;
        }

        Swal.fire({  
            title: 'Are you sure you want to Start this?', 
            icon: 'warning', 
            showCancelButton: true,  
            confirmButtonText: 'Yes',  
            cancelButtonText: 'No',
        }).then((result) => {  
            if (result.isConfirmed || result.value) { 
                $.ajax({
                    url: '/timeStart', 
                    type: 'post',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: selectedId 
                    },
                    success: function(response){
                        if (response.success){
                            Toast.fire({ icon: 'success', title: response.success });
                            
                            let currentCount = parseInt($('#total-start-count').text()) || 0;
                            if (currentCount > 0) {
                                $('#total-start-count').text(currentCount - 1);
                            }
                        } else if(response.error){
                            Toast.fire({ icon: 'error', title: response.error });
                        }
                    }
                }); 
            }
        });
    });
//stoptime
       // ==========================================
        // 3. MO SEARCH MODAL & DATATABLE
        // ==========================================
        window.showMOSearch = function() {
            // if (!validatePrestartSetup('searching for an MO')) return;
            
            $('#SearchMOModal').modal('show');
        }

$('#btn_stop_time').on('click', function() {
        // 1. Get the selected ID value from your Select2 input
        
        let selectedId = $('#select-tag').val();
        // 2. Alert the user if they haven't selected an option yet
        if (!selectedId) {
            Toast.fire({ icon: 'error', title: 'Please select a Item first!' });
            return;
        }

        Swal.fire({  
            title: 'Are you sure you want to Stop this?', 
            icon: 'warning', 
            showCancelButton: true,  
            confirmButtonText: 'Yes',  
            cancelButtonText: 'No',
        }).then((result) => {  
            if (result.isConfirmed || result.value) { 
                $.ajax({
                    url: '/timeStop', 
                    type: 'post',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: selectedId 
                    },
                    success: function(response){
                        if (response.success){
                            Toast.fire({ icon: 'success', title: response.success });
                            
                            let currentCount = parseInt($('#total-start-count').text()) || 0;
                            if (currentCount > 0) {
                                $('#total-start-count').text(currentCount - 1);
                            }
                        } else if(response.error){
                            Toast.fire({ icon: 'error', title: response.error });
                        }
                    }
                }); 
            }
        });
    });

    });
</script>

<script>
    //Clock
    $(function() {
        const $timeEl = $('#live-time');
        const $dateEl = $('#live-date');

        function updateClock() {
            const now = new Date();
            
            // Format time: hh:mm:ss AM/PM
            const timeString = now.toLocaleTimeString('en-US', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: true
            });

            // Format date: Day, Month Date, Year
            const dateString = now.toLocaleDateString('en-US', {
                weekday: 'long',
                month: 'long',
                day: 'numeric',
                year: 'numeric'
            });

            // Inject into elements using jQuery .text() for security
            $timeEl.text(timeString);
            $dateEl.text(dateString);
        }

        updateClock(); // Execute immediately on page load
        setInterval(updateClock, 1000); // Tick every second
    });
</script>



<script>
 function fetchNotifications() {
    $.ajax({
        url: "{{ route('Notifications') }}",
        type: "GET",
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        success: function (response) {
            // Logs the complete response object (success, html, unreadCount, logs array)
            console.log('Full Response:', response);

            // Logs only the array of ArtworkLog models
            if (response.logs) {
                console.log('All Notification Records:', response.logs);
            }

            if (response.html) {
                $('#activityFeedList').html(response.html);
            }
            if (response.unreadCount !== undefined) {
                const badge = $('#fbNotificationBadge');
                badge.text(response.unreadCount > 99 ? '99+' : response.unreadCount);
                if (response.unreadCount === 0) {
                    badge.addClass('d-none');
                } else {
                    badge.removeClass('d-none');
                }
            }
        },
        error: function (xhr) {
            console.error('Error fetching notifications:', xhr);
        }
    });
}

    $(document).ready(function () {
        // Run once when page loads
        fetchNotifications();
    });



    //for timer

$(document).ready(function() {
    let timerInterval = null;
    const $wrap = $('#live-counter');
    const $timer = $('#sessionTimerDisplay');
    const $badge = $('#taskStatusBadge');
    // const timerUrl = "{{ route('artwork.timer', $artwork->item_code) }}";
const timerUrl = "{{ url('artwork') }}/" + encodeURIComponent($('#artwork-code').val()) + "/timer";

    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } });

    // Initial load framework call to populate grid items immediately
    getTimeSpentDuration();

    // 1. Check Working Hours (Mon-Fri, 8 AM to 6 PM, excluding holidays)
    function isWorkingHours() {
        const now = new Date();
        const day = now.getDay(), hour = now.getHours();
        const today = now.toISOString().split('T')[0];
        const holidays = ['2026-11-05', '2026-12-25'];

        if (day === 0 || day === 6 || holidays.includes(today)) return false;
        return hour >= 8 && hour < 18;
    }

    // 2. Format Seconds -> HH:MM:SS (Main Top Running Session Display UI Element Only)
    function formatTime(s) {
        return new Date(Math.max(0, s) * 1000).toISOString().substr(11, 8);
    }

    //  UI Badge Helper
    function setBadge(type, text, icon) {
        $badge.attr('class', `badge badge-${type}`).html(`<i class="fas fa-${icon} mr-1"></i> ${text}`);
    }

    //  Update Header UI Controls Display values
    function renderUI() {
        const isRunning = $wrap.data('running') == true;
        const state = $wrap.data('action-state');
        const accumulated = parseInt($wrap.data('accumulated')) || 0;
        const isOpen = isWorkingHours();

        $('#btnStart, #btnPause, #btnResume, #btnFinish').addClass('d-none').prop('disabled', !isOpen);
        if (accumulated > 0 || isRunning) $('#btnFinish').removeClass('d-none');

        if (isRunning) {
            if (!isOpen) return sendTimerAction('auto-shift-end');
            $('#btnPause').removeClass('d-none');
            setBadge('success pulse-live', 'Running', 'play-circle');
            startTimer();
        } else {
            $timer.text(formatTime(accumulated));
            
            if (accumulated === 0 && !state) {
                $('#btnStart').removeClass('d-none');
                $('#btnFinish').addClass('d-none');
                setBadge('warning', 'Ready', 'clock');
            } else {
                $('#btnResume').removeClass('d-none');
                if (state === 'finish') setBadge('secondary', 'Stopped', 'check-circle');
                else if (state === 'auto-shift-end') setBadge('danger', 'Shift Ended', 'history');
                else setBadge('warning', 'Paused', 'pause-circle');
            }

            startMonitoring();
        }

        if (!isOpen) {
            if (state !== 'finish' && state !== 'auto-shift-end') {
                setBadge('danger', 'Locked (Off-hours)', 'lock');
            }
        }
    }

    // Running Session Clock Live Loop
    function startTimer() {
        clearInterval(timerInterval);
        const startTime = new Date($wrap.data('start')).getTime();
        const baseSeconds = parseInt($wrap.data('accumulated')) || 0;

        timerInterval = setInterval(() => {
            if (!isWorkingHours()) return sendTimerAction('auto-shift-end');
            const elapsed = Math.floor((Date.now() - startTime) / 1000);
            $timer.text(formatTime(baseSeconds + Math.max(0, elapsed)));
        }, 1000);
    }

    //Background Off-hours Monitor 
    function startMonitoring() {
        clearInterval(timerInterval);
        timerInterval = setInterval(() => {
            const state = $wrap.data('action-state');
            if (state === 'pause' || state === 'finish') return clearInterval(timerInterval);
            if (state === 'auto-shift-end' && isWorkingHours()) {
                clearInterval(timerInterval);
                sendTimerAction('resume');
            }
        }, 5000);
    }

    // function sendTimerAction(action, $btn = null) {
    //     if ($btn) $btn.prop('disabled', true);

    //     $.post(timerUrl, { action: action, item_code: $('#artwork-code').val() }, function(res) {
    //         if (res.status === 'success' || action === 'auto-shift-end') {
    //             $wrap.data({
    //                 running: action === 'auto-shift-end' ? false : res.is_running,
    //                 start: action === 'auto-shift-end' ? null : res.started_at,
    //                 accumulated: res.total_seconds,
    //                 'action-state': action
    //             });
    //             renderUI();
    //             if (action === 'auto-shift-end') alert('Tracking shift ended. Progress saved.');
    //         }
    //     }).fail(xhr => {
    //         alert(xhr.status === 403 ? xhr.responseJSON.message : 'Error updating timer.');
    //     }).always(() => {
    //         if ($btn) $btn.prop('disabled', false);
            
    //         // 🔴 Auto-update dynamic millisecond breakdown grid when actions finish executing
    //         getTimeSpentDuration();
    //     });
    // }

        function sendTimerAction(action, $btn = null) {
        if ($btn) $btn.prop('disabled', true);

        // 💡 Ensure we read the fresh live value from the element field
        const currentItemCode = $('#artwork-code').val();

        $.post(timerUrl, { 
            action: action, 
            item_code: currentItemCode 
        }, function(res) {
            // Check for success responses back from ArtistController
            if (res.status === 'success' || action === 'auto-shift-end') {
                $wrap.data({
                    running: action === 'auto-shift-end' ? false : res.is_running,
                    start: action === 'auto-shift-end' ? null : res.started_at,
                    accumulated: res.total_seconds,
                    'action-state': action
                });
                
                // Re-render the play/pause/resume control buttons based on state rules
                renderUI();
                
                if (action === 'auto-shift-end') {
                    alert('Tracking shift ended. Progress saved.');
                }
            }
        }).fail(xhr => {
            // Give specific error reporting if a validation lock fails
            let errorMsg = 'Error updating timer.';
            if (xhr.status === 403 || xhr.status === 422) {
                errorMsg = xhr.responseJSON.message || errorMsg;
            }
            alert(errorMsg);
        }).always(() => {
            if ($btn) $btn.prop('disabled', false);
            
            // 🔴 Updates the grid metrics logs instantly
            if (typeof getTimeSpentDuration === "function") {
                getTimeSpentDuration();
            }

            // 🔄 NEW AUTOMATION: Instantly reloads the AdminLTE timeline card wrapper 
            // so supervisors and artists see the activity logs pop up without hitting refresh!
            if ($('#timeline-container-box').length > 0) {
                $('#timeline-container-box').load(window.location.href + ' #timeline-container-box > *');
            }
        });
    }


    // 8. Action Button Click Event Router
    $('.action-btn').on('click', function() {
        const action = $(this).data('action');
        if (!isWorkingHours() && (action === 'start' || action === 'resume')) {
            return alert('Operation locked. Outside tracking frame parameters.');
        }
        sendTimerAction(action, $(this));
    });

    @if($artwork->timeLogs->last() && $artwork->timeLogs->last()->action === 'auto-shift-end')
        $wrap.data('action-state', 'auto-shift-end');
    @endif

    renderUI();
});


// Time Breakdown Processor (Calculates exact millisecond intervals via custom layout patterns)
function getTimeSpentDuration() {
    const artworkId = "{{ $artwork->item_code }}"; 
    const currentUserId = "{{ auth()->id() }}"; 

    // High-precision millisecond string renderer output pattern: "HH:MM:SS.mmm"
    function formatMilliseconds(totalMs) {
        if (!totalMs || totalMs < 0) return "00:00:00.000";
        
        const hours = String(Math.floor(totalMs / 3600000)).padStart(2, '0');
        const minutes = String(Math.floor((totalMs % 3600000) / 60000)).padStart(2, '0');
        const seconds = String(Math.floor((totalMs % 60000) / 1000)).padStart(2, '0');
        const ms = String(totalMs % 1000).padStart(3, '0');
        
        return `${hours}:${minutes}:${seconds}.${ms}`;
    }

    $.ajax({
        url: `/getTimeShareDuration/${artworkId}`,
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            let rowsHtml = '';
            
            if (!response || response.length === 0) {
                rowsHtml = `<tr><td colspan="4" class="text-center text-muted py-3">No tracking logs found.</td></tr>`;
            } else {
                response.forEach(function(item) {
                    const isYou = String(item.user_id) === String(currentUserId) ? ' <small class="text-primary">(You)</small>' : '';
                    
                    // Extracts the high precision millisecond column value parsed via the controller endpoint
                    const formattedTime = formatMilliseconds(item.time_spent_milliseconds);
                    const share = item.duration_share_percentage || 0;
                    
                    let userRole = item.role;
                    if (userRole == 4) userRole = 'Artist';

                    rowsHtml += `
                        <tr class="${isYou ? 'bg-light' : ''}">
                            <td>
                                                <div class="d-flex align-items-center">
                                                
                                                    <strong class="text-dark">{{ $artwork->artist_name }} <small class="text-primary">(You)</small></strong>
                                                </div>
                                            </td>
 <td class="text-muted">{{ $artwork->artist_role_id == 4 ? 'Artist' : $artwork->artist_role_id }}</td>


                        
                            <td><span class="timer-display text-primary font-mono font-weight-bold">${formattedTime}</span></td>
                            <td>
                                <div class="progress progress-xs mb-1" style="height: 6px;">
                                    <div class="progress-bar bg-primary" role="progressbar" style="width: ${share}%" aria-valuenow="${share}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <small class="text-muted font-mono">${share}%</small>
                            </td>
                        </tr>
                    `;
                });
            }
            $('#timeSpentDuration').html(rowsHtml);
        },

        error: function(xhr, status, error) {
            console.error("Failed to fetch time consumption logs:", error);
            $('#timeSpentDuration').html(`<tr><td colspan="4" class="text-center text-danger py-3">Error fetching duration statistics.</td></tr>`);
        }
    });
}
</script>

</script>
</script>

@endsection