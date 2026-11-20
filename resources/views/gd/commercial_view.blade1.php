@extends('adminlte::page')

@section('title', 'Dashboard')

@section('plugins.Sweetalert2', true)
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)

@section('content_header')
    <style>
        /* Layout Overrides */
        .main-sidebar { display: none !important; }
        .content-wrapper, .main-header { margin-left: 0 !important; }
        .content-wrapper { background-color: #f1f5f9 !important; }

        /* Modern Card System */
        .modern-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.05);
            margin-bottom: 1.5rem;
            overflow: hidden;
            transition: box-shadow 0.2s ease;
        }
        .modern-card:hover {
            box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.08);
        }
        .modern-card-header {
            background: #ffffff;
            border-bottom: 1px solid #f1f5f9;
            padding: 1.25rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }
        .modern-card-title {
            font-size: 1.05rem;
            font-weight: 700;
            color: #0f172a;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .modern-card-body {
            padding: 1.5rem;
        }

        /* Badges & Status Pills */
        .code-pill {
            background: #eef2ff;
            color: #4338ca;
            border: 1px solid #c7d2fe;
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
            font-weight: 700;
            font-size: 0.85rem;
            padding: 0.4rem 0.85rem;
            border-radius: 8px;
            letter-spacing: 0.02em;
        }
        .status-pill {
            padding: 0.45rem 1rem;
            border-radius: 9999px;
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 0.03em;
            text-transform: uppercase;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .pulse-live {
            animation: livePulse 2s infinite;
        }
        @keyframes livePulse {
            0% { box-shadow: 0 0 0 0 rgba(22, 163, 74, 0.4); }
            70% { box-shadow: 0 0 0 8px rgba(22, 163, 74, 0); }
            100% { box-shadow: 0 0 0 0 rgba(22, 163, 74, 0); }
        }

        /* Hero Timer Box */
        .timer-hero-box {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }
        @media (min-width: 768px) {
            .timer-hero-box {
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
            }
        }
        .timer-icon-ring {
            width: 58px;
            height: 58px;
            border-radius: 16px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            color: #4f46e5;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 10px rgba(79, 70, 229, 0.08);
            flex-shrink: 0;
        }
        .timer-display-modern {
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
            font-variant-numeric: tabular-nums;
            font-size: 2.35rem;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.1;
            letter-spacing: -0.02em;
        }

        /* Modern Buttons */
        .btn-modern {
            border-radius: 10px;
            font-weight: 600;
            padding: 0.65rem 1.35rem;
            font-size: 0.925rem;
            transition: all 0.15s ease;
            border: none;
            box-shadow: 0 2px 6px rgba(15, 23, 42, 0.08);
        }
        .btn-modern:hover:not(:disabled) {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.14);
        }
        .btn-modern:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* Artist Action Form Panel */
        .artist-action-panel {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-left: 4px solid #4f46e5;
            border-radius: 12px;
            padding: 1.25rem;
            margin-bottom: 1.25rem;
        }
        .artist-action-panel label {
            font-size: 0.78rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: #64748b;
            margin-bottom: 0.35rem;
        }
        .artist-action-panel .form-control {
            border-radius: 8px;
            border: 1px solid #cbd5e1;
            font-size: 0.9rem;
            padding: 0.5rem 0.75rem;
        }
        .artist-action-panel .form-control[readonly] {
            background-color: #f8fafc;
            color: #475569;
        }

        /* Modern Table */
        .modern-table thead th {
            background-color: #f8fafc;
            color: #64748b;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-top: none;
            border-bottom: 1px solid #e2e8f0;
            padding: 0.9rem 1.25rem;
        }
        .modern-table tbody td {
            padding: 1rem 1.25rem;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
            color: #1e293b;
            font-size: 0.9rem;
        }
        .modern-table tbody tr:last-child td {
            border-bottom: none;
        }

        /* File Link Item */
        .asset-item-box {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 1rem 1.25rem;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
        }

        /* Custom Scrollbar for Activity Logs */
        .activity-scroll-area {
            max-height: 400px;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 transparent;
        }
        .activity-scroll-area::-webkit-scrollbar {
            width: 6px;
        }
        .activity-scroll-area::-webkit-scrollbar-thumb {
            background-color: #cbd5e1;
            border-radius: 9999px;
        }
    </style>

    @can('is-admin')
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
        .node-icon-badge.bg-success { background: #16a34a !important; }
        .node-icon-badge.bg-danger { background: #dc2626 !important; }
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
        .timeline-status.started { background: #dcfce7; color: #166534; }
        .timeline-status.stopped { background: #fee2e2; color: #991b1b; }
        .timeline-clock {
            color: #475569;
            font-size: 12px;
            font-weight: 700;
            white-space: nowrap;
        }
        .timeline-card-body { padding: 15px; }
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
        .timeline-date { color: #64748b; font-size: 12px; }
        .timeline-date strong { color: #334155; }
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
            .horizontal-timeline-wrapper { padding: 15px; }
            .timeline-node { width: 255px; min-width: 255px; flex-basis: 255px; }
            .item-title-area h5 { font-size: 16px; }
        }
        @media (prefers-reduced-motion: reduce) {
            .timeline-modern-card { transition: none; }
        }
        .clock-widget { float: right; }
    </style>
    @endcan
@endsection

@section('content')
<div class="container-fluid py-3">
    <section class="content">
        <div class="tab-content">
            <!-- PANE 1: WORKBENCH & TIMER -->
            <div class="tab-pane fade active show" id="tracker-pane">

                <!-- MAIN LIVE TIMER CARD CONTAINER -->
                <div class="modern-card">
                    <div class="modern-card-header">
                        <div class="d-flex align-items-center flex-wrap" style="gap: 12px;">
                            <span id="ticketBadge" class="code-pill">{{ $artwork->item_code }}</span>
                            <div>
                                <h3 id="taskTitleHeader" class="modern-card-title">Artwork Work Flow (GFLOW)</h3>
                                <p id="taskDescHeader" class="text-muted mb-0 mt-1" style="font-size: 0.9rem;">
                                    <i class="far fa-building mr-1 text-secondary"></i> {{ $artwork->client_name }}
                                </p>
                            </div>
                        </div>

                        <span id="taskStatusBadge" class="badge {{ $artwork->is_running ? 'badge-success pulse-live' : ($artwork->timeLogs->last() && $artwork->timeLogs->last()->action === 'finish' ? 'badge-secondary' : 'badge-warning') }} status-pill">
                            <i class="fas {{ $artwork->is_running ? 'fa-play-circle' : ($artwork->timeLogs->last() && $artwork->timeLogs->last()->action === 'finish' ? 'fa-check-circle' : 'fa-pause-circle') }}"></i>
                            {{ $artwork->is_running ? 'Running' : ($artwork->timeLogs->last() && $artwork->timeLogs->last()->action === 'finish' ? 'Stopped' : 'Paused') }}
                        </span>
                    </div>

                    <div class="modern-card-body">
                        @php
                            $lastLog = $artwork->timeLogs->whereIn('action', ['start', 'resume'])->last();
                            $startTimeIso = ($artwork->is_running && $lastLog) ? $lastLog->recorded_at->toIso8601String() : '';
                            $lastActionTaken = optional($artwork->timeLogs->last())->action_taken ?? ($artwork->action_taken ?? '');
                        @endphp

                        <!-- STEP 1: ARTIST ACTION / STATUS SELECTION (REQUIRED BEFORE TIMER) -->
                        @can('is-artist')
                        <div class="artist-action-panel">
                            <form id="actionTaken" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-3 mb-2">
                                        <label for="modalItemCode">Item Code</label>
                                        <input type="text" class="form-control" id="modalItemCode" name="item_code" value="{{ $artwork->item_code }}" readonly required>
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <label for="modalItemDescription">Item Description</label>
                                        <input type="text" class="form-control" id="modalItemDescription" name="item_desc" value="{{ $artwork->description }}" readonly required>
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <label for="modalClientName">Client Name</label>
                                        <input type="text" class="form-control" id="modalClientName" name="client_name" value="{{ $artwork->client_name }}" readonly required>
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <label for="modalRfaRef">RFA Reference</label>
                                        <input type="text" class="form-control" id="modalRfaRef" name="rfa_ref" value="{{ $artwork->rfa_reference }}" readonly required>
                                    </div>
                                </div>

                                <div class="row align-items-end mt-2">
                                    <div class="col-md-6 mb-2 mb-md-0">
                                        <label for="action_taken" class="text-primary">
                                            <i class="fas fa-tasks mr-1"></i> Step 1: Select Action / Status Before Timing <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-control select2" id="action_taken" name="action_taken" required>
                                            <option value="" disabled {{ !$lastActionTaken ? 'selected' : '' }}>-- Choose Action Taken First --</option>
                                            <option value="Re-touching" {{ $lastActionTaken === 'Re-touching' ? 'selected' : '' }}>Re-touching</option>
                                            <option value="Revision" {{ $lastActionTaken === 'Revision' ? 'selected' : '' }}>Revision</option>
                                            <option value="Redesign" {{ $lastActionTaken === 'Redesign' ? 'selected' : '' }}>Redesign</option>
                                            <option value="For Profreading" {{ $lastActionTaken === 'For Profreading' ? 'selected' : '' }}>For Proofreading</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 d-flex justify-content-md-end" style="gap: 10px;">
                                        <button type="button" class="btn btn-light border btn-modern" data-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary btn-modern" id="btnSaveStatus">
                                            <i class="fas fa-save mr-1"></i> Save Changes
                                        </button>
                                    </div>
                                </div>

                                @if(session('success'))
                                    <div class="alert alert-success mt-3 mb-0 rounded-lg">
                                        <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
                                    </div>
                                @endif
                            </form>
                        </div>
                        @endcan

                        <!-- STEP 2: LIVE COUNTER HERO BANNER -->
                        <div class="timer-hero-box"
                             id="live-counter"
                             data-start="{{ $startTimeIso }}"
                             data-accumulated="{{ $artwork->total_seconds }}"
                             data-running="{{ $artwork->is_running ? 'true' : 'false' }}"
                             data-action-state="{{ $artwork->timeLogs->last() ? $artwork->timeLogs->last()->action : '' }}">

                            <input type="hidden" id="artwork-code" value="{{ $artwork->item_code }}">

                            <!-- Left: Ticker Display -->
                            <div class="d-flex align-items-center" style="gap: 16px;">
                                <div class="timer-icon-ring">
                                    <i class="fas fa-stopwatch fa-lg"></i>
                                </div>
                                <div>
                                    <div class="text-uppercase font-weight-bold text-muted" style="font-size: 0.75rem; letter-spacing: 0.05em;">
                                        Active Session Ticker • <span id="activeUserLabel" class="text-primary">{{ $artwork->artist_name ?? $artwork->ao_name ?? 'Unassigned' }}</span>
                                    </div>
                                    <div id="sessionTimerDisplay" class="timer-display timer-display-modern mt-1">00:00:00</div>
                                    @can('is-artist')
                                    <small id="currentActionIndicator" class="text-muted font-weight-bold d-block mt-1">
                                        <i class="fas fa-info-circle text-warning mr-1"></i> Please select a status above to unlock timer actions
                                    </small>
                                    @endcan
                                </div>
                            </div>

                            <!-- Right: Timer Controls -->
                            @can('is-artist')
                            <div class="d-flex flex-column align-items-md-end">
                                <span class="text-uppercase font-weight-bold text-muted mb-2" style="font-size: 0.7rem; letter-spacing: 0.05em;">
                                    Step 2: Timer Controls
                                </span>
                                <div class="d-flex align-items-center flex-wrap" style="gap: 10px;">
                                    <button id="btnStart" class="btn btn-success btn-modern action-btn" data-action="start" disabled>
                                        <i class="fas fa-play mr-1"></i> Start
                                    </button>
                                    <button id="btnPause" class="btn btn-warning btn-modern text-white action-btn d-none" data-action="pause" disabled>
                                        <i class="fas fa-pause mr-1"></i> Pause
                                    </button>
                                    <button id="btnResume" class="btn btn-info btn-modern action-btn d-none" data-action="resume" disabled>
                                        <i class="fas fa-redo mr-1"></i> Resume
                                    </button>
                                    <button id="btnFinish" class="btn btn-danger btn-modern action-btn" data-action="finish" disabled>
                                        <i class="fas fa-check-circle mr-1"></i> Finish Task
                                    </button>
                                </div>
                            </div>
                            @endcan
                        </div>
                    </div>
                </div>

                <!-- BOTTOM TWO-COLUMN GRID -->
                <div class="row">
                    <!-- LEFT COLUMN: MULTI-USER ACCUMULATED TIME TABLE -->
                    <div class="col-lg-7">
                        <div class="modern-card">
                            <div class="modern-card-header">
                                <h3 class="modern-card-title">
                                    <i class="fas fa-users text-primary"></i>
                                    <span>User Time Consumption</span>
                                </h3>
                                <span id="sharedTaskIdLabel" class="code-pill">{{ $artwork->item_code }}</span>
                            </div>
                            <div class="table-responsive">
                                <table class="table modern-table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>Assigned User</th>
                                            <th>Role</th>
                                            <th>Time Spent</th>
                                            <th>Duration Share</th>
                                        </tr>
                                    </thead>
                                    <tbody id="timeSpentDuration">
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-4">
                                                <i class="fas fa-spinner fa-spin mr-1"></i> Loading breakdown records...
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- RIGHT COLUMN: ASSETS & ACTIVITY LOGS -->
                    <div class="col-lg-5">
                        <!-- TASK ASSETS & ATTACHMENTS -->
                        <div class="modern-card">
                            <div class="modern-card-header">
                                <h3 class="modern-card-title">
                                    <i class="fas fa-paperclip text-info"></i>
                                    <span>Item &amp; File Link</span>
                                </h3>
                            </div>
                            <div class="modern-card-body">
                                <ul class="list-unstyled mb-0" id="taskAssetsList">
                                    <li class="asset-item-box">
                                        <div class="d-flex align-items-center text-truncate" style="gap: 12px;">
                                            <div class="p-2 bg-white border rounded text-primary">
                                                <i class="far fa-file-alt fa-lg"></i>
                                            </div>
                                            <strong class="text-dark text-truncate">{{ $artwork->file_info }}</strong>
                                        </div>
                                        <a href="{{ $artwork->file_link }}" class="btn btn-sm btn-outline-primary btn-modern py-1 px-3 flex-shrink-0 file-link" data-id="{{ $artwork->item_code }}" target="_blank">
                                            <i class="fas fa-external-link-alt mr-1"></i> View Link
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- ACTIVITY LOGS CARD -->
                        <div class="modern-card">
                            <div class="modern-card-header">
                                <h3 class="modern-card-title">
                                    <i class="fas fa-history text-primary"></i>
                                    <span>Activity Logs</span>
                                </h3>
                                <span id="activity-log-count" class="badge badge-light border px-3 py-1" style="border-radius: 999px; font-weight: 600;">0 events</span>
                            </div>
                            <div class="modern-card-body activity-scroll-area">
                                <div id="activity-logs-container">
                                    <p class="text-muted text-center my-4">
                                        <i class="fas fa-spinner fa-spin mr-1"></i> Loading activity logs...
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- Modals --}}
    @include('partials.modal.supplied_file_verification')
    @include('partials.modal.specifications')
</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const ARTWORK_ITEM_CODE = "{{ $artwork->item_code ?? request()->route('id') }}";

    // Time Breakdown Processor (Calculates exact millisecond intervals via custom layout patterns)
    function getTimeSpentDuration() {
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
            url: `/getTimeShareDuration/${encodeURIComponent(ARTWORK_ITEM_CODE)}`,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                let rowsHtml = '';

                if (!response || response.length === 0) {
                    rowsHtml = `<tr><td colspan="4" class="text-center text-muted py-3">No tracking logs found.</td></tr>`;
                } else {
                    response.forEach(function(item) {
                        const isYou = String(item.user_id) === String(currentUserId) ? ' <small class="text-primary">(You)</small>' : '';

                        const formattedTime = formatMilliseconds(item.time_spent_milliseconds);
                        const share = item.duration_share_percentage || 0;

                        let userRole = item.role;
                        if (userRole == 4) userRole = 'Artist';

                        rowsHtml += `
                            <tr class="${isYou ? 'bg-light' : ''}">
                                <td>
                                    <div class="d-flex align-items-center">
                                        <strong class="text-dark">${item.user_name ?? 'Unknown User'}${isYou}</strong>
                                    </div>
                                </td>
                                <td class="text-muted">${userRole ?? '-'}</td>
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

    // Activity Logs Loader
    function loadActivityLogs(itemCode = ARTWORK_ITEM_CODE) {
        const container = document.getElementById('activity-logs-container');
        const countBadge = document.getElementById('activity-log-count');

        if (!container || !countBadge) return;

        fetch(`{{ route('artwork.activity_logs') }}?id=${encodeURIComponent(itemCode)}`, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(async res => {
            if (!res.ok) {
                const errorText = await res.text();
                throw new Error(`HTTP ${res.status}: ${errorText.substring(0, 300)}`);
            }
            return res.json();
        })
        .then(logs => {
            countBadge.textContent = `${logs.length} event${logs.length === 1 ? '' : 's'}`;

            if (!logs.length) {
                container.innerHTML = `<p class="text-muted text-center my-3">No activity recorded for this artwork yet.</p>`;
                return;
            }

            // AdminLTE 3 native timeline icons & background classes
            const eventStyles = {
                'timer_start':       { icon: 'fas fa-play',         bg: 'bg-success' },
                'timer_resume':      { icon: 'fas fa-redo',         bg: 'bg-success' },
                'timer_pause':       { icon: 'fas fa-pause',        bg: 'bg-warning' },
                'timer_finish':      { icon: 'fas fa-check',        bg: 'bg-danger'  },
                'artist_assigned':   { icon: 'fas fa-user-plus',    bg: 'bg-primary' },
                'artist_reassigned': { icon: 'fas fa-exchange-alt', bg: 'bg-info'    },
            };

            // Color-coded pill styles for action_taken
            const actionBadges = {
                'Re-touching':      'background:#e0e7ff; color:#3730a3; border:1px solid #c7d2fe;',
                'Revision':         'background:#fef3c7; color:#92400e; border:1px solid #fde68a;',
                'Redesign':         'background:#fce7f3; color:#9d174d; border:1px solid #fbcfe8;',
                'For Profreading':  'background:#dcfce7; color:#166534; border:1px solid #bbf7d0;',
                'For Proofreading': 'background:#dcfce7; color:#166534; border:1px solid #bbf7d0;',
            };

            const timelineItems = logs.map(log => {
                const style = eventStyles[log.event] || { icon: 'fas fa-info', bg: 'bg-secondary' };
                const badgeStyle = actionBadges[log.action_taken] || 'background:#f1f5f9; color:#334155; border:1px solid #cbd5e1;';
                const actionLabel = log.action_taken === 'For Profreading' ? 'For Proofreading' : log.action_taken;

                const actionBadge = log.action_taken
                    ? `<span class="badge ml-1" style="${badgeStyle} font-size:0.72rem; padding:4px 8px; border-radius:6px;">
                           <i class="fas fa-tag mr-1"></i>${actionLabel}
                       </span>`
                    : '';

                return `
                    <div>
                        <i class="${style.icon} ${style.bg}"></i>
                        <div class="timeline-item shadow-none border">
                            <span class="time">
                                <i class="far fa-clock"></i> ${log.time}
                            </span>
                            <h3 class="timeline-header border-0 pb-1" style="font-size: 0.9rem;">
                                ${log.description} ${actionBadge}
                            </h3>
                            <div class="timeline-footer pt-0 text-muted" style="font-size: 0.8rem;">
                                By <strong>${log.actor_name}</strong> &bull; ${log.date}
                            </div>
                        </div>
                    </div>
                `;
            }).join('');

            container.innerHTML = `
                <div class="timeline timeline-inverse mb-0">
                    ${timelineItems}
                    <div>
                        <i class="far fa-clock bg-gray"></i>
                    </div>
                </div>
            `;
        })
        .catch(err => {
            console.error('Failed to load activity logs:', err);
            container.innerHTML = `<div class="alert alert-danger small mb-0">${err.message}</div>`;
        });
    }

    // Notifications Loader
    function fetchNotifications() {
        $.ajax({
            url: "{{ route('Notifications') }}",
            type: "GET",
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function (response) {
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
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } });

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
                icon: 'success',
                title: "{{ \Session::get('success') }}"
            });
        @endif

        window.addEventListener('load', function() {
            $('#select-tag').select2({
                placeholder: 'Search for a Item...',
                allowClear: true
            });
        });

        // ---------------------------------------------------------
        // LIVE CLOCK
        // ---------------------------------------------------------
        const $timeEl = $('#live-time');
        const $dateEl = $('#live-date');

        function updateClock() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('en-US', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: true
            });
            const dateString = now.toLocaleDateString('en-US', {
                weekday: 'long',
                month: 'long',
                day: 'numeric',
                year: 'numeric'
            });

            $timeEl.text(timeString);
            $dateEl.text(dateString);
        }

        updateClock();
        setInterval(updateClock, 1000);

        // Initial Data Loads
        fetchNotifications();
        getTimeSpentDuration();
        loadActivityLogs(ARTWORK_ITEM_CODE);

        // ---------------------------------------------------------
        // TIMER & ACTION STATUS WORKFLOW
        // ---------------------------------------------------------
        let timerInterval = null;
        const $wrap = $('#live-counter');
        const $timer = $('#sessionTimerDisplay');
        const $badge = $('#taskStatusBadge');
        const $actionSelect = $('#action_taken');
        const $indicator = $('#currentActionIndicator');
        const timerUrl = "{{ url('artwork') }}/" + encodeURIComponent($('#artwork-code').val()) + "/timer";

        // 1. Check Working Hours (Mon-Fri, 8 AM to 6 PM, excluding holidays)
        function isWorkingHours() {
            const now = new Date();
            const day = now.getDay(), hour = now.getHours();
            const today = now.toISOString().split('T')[0];
            const holidays = ['2026-11-05', '2026-12-25'];

            if (day === 0 || day === 6 || holidays.includes(today)) return false;
            return hour >= 8 && hour < 18;
        }

        // 2. Format Seconds -> HH:MM:SS
        function formatTime(s) {
            return new Date(Math.max(0, s) * 1000).toISOString().substr(11, 8);
        }

        // 3. Safe Timestamp Parser (Handles ISO & SQL Server formats)
        function parseSafeTimestamp(raw) {
            if (!raw) return NaN;
            const normalized = typeof raw === 'string' && raw.includes(' ') && !raw.includes('T')
                ? raw.replace(' ', 'T')
                : raw;
            return new Date(normalized).getTime();
        }

        // 4. UI Badge Helper
        function setBadge(type, text, icon) {
            $badge.attr('class', `badge badge-${type} status-pill`).html(`<i class="fas fa-${icon}"></i> ${text}`);
        }

        // 5. Update Action Indicator & Validate Status Selection
        function updateActionRequirementIndicator() {
            const selectedAction = $actionSelect.val();
            const hasAction = selectedAction && selectedAction.trim() !== '';

            if ($indicator.length) {
                if (hasAction) {
                    const displayLabel = selectedAction === 'For Profreading' ? 'For Proofreading' : selectedAction;
                    $indicator.html(
                        `<i class="fas fa-check-circle text-success mr-1"></i> Ready for: <span class="text-dark">${displayLabel}</span>`
                    );
                } else {
                    $indicator.html(
                        `<i class="fas fa-info-circle text-warning mr-1"></i> Please select a status above to unlock timer actions`
                    );
                }
            }

            return hasAction;
        }

        // 6. Start Live Ticker (With fallback if backend omits start_time on start/resume)
        function startTimer() {
            clearInterval(timerInterval);

            let startIso = $wrap.data('start');
            const accumulated = parseInt($wrap.data('accumulated')) || 0;

            if (!startIso) {
                startIso = new Date().toISOString();
                $wrap.data('start', startIso);
            }

            let startMs = parseSafeTimestamp(startIso);
            if (isNaN(startMs)) {
                startMs = Date.now();
            }

            function tick() {
                if (!isWorkingHours()) {
                    clearInterval(timerInterval);
                    return sendTimerAction('auto-shift-end');
                }
                const elapsed = Math.max(0, Math.floor((Date.now() - startMs) / 1000));
                $timer.text(formatTime(accumulated + elapsed));
            }

            tick();
            timerInterval = setInterval(tick, 1000);
        }

        // 7. Stop Live Ticker
        function stopTimer() {
            clearInterval(timerInterval);
            timerInterval = null;
        }

        // 8. Update Header UI Controls Display Values
        function renderUI() {
            const isRunning = $wrap.data('running') == true || $wrap.data('running') === 'true';
            const state = $wrap.data('action-state');
            const accumulated = parseInt($wrap.data('accumulated')) || 0;
            const isOpen = isWorkingHours();
            const hasAction = updateActionRequirementIndicator();

            // Unlock timer controls ONLY during working hours AND once an action status is selected
            const canUseButtons = isOpen && hasAction;

            $('#btnStart, #btnPause, #btnResume, #btnFinish').addClass('d-none').prop('disabled', !canUseButtons);
            if (accumulated > 0 || isRunning) $('#btnFinish').removeClass('d-none');

            if (isRunning) {
                if (!isOpen) return sendTimerAction('auto-shift-end');
                $('#btnPause').removeClass('d-none');
                setBadge('success pulse-live', 'Running', 'play-circle');
                startTimer();
            } else {
                stopTimer();
                $timer.text(formatTime(accumulated));

                if (accumulated === 0 && !state) {
                    $('#btnStart').removeClass('d-none');
                    $('#btnFinish').addClass('d-none');
                    setBadge('warning', 'Ready', 'clock');
                } else {
                    $('#btnResume').removeClass('d-none');
                    if (state === 'finish') {
                        setBadge('secondary', 'Stopped', 'check-circle');
                    } else {
                        setBadge('warning', 'Paused', 'pause-circle');
                    }
                }
            }
        }

        // 9. Send Timer Action + Selected Status (action_taken) to Backend
        function sendTimerAction(action) {
            const selectedAction = $actionSelect.val();

            if (action !== 'auto-shift-end' && !selectedAction) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Action Required',
                    text: 'Please select a status (Re-touching, Revision, Redesign, or For Proofreading) before starting, pausing, resuming, or finishing the timer.'
                });
                return;
            }

            $('.action-btn').prop('disabled', true);

            $.ajax({
                url: timerUrl,
                type: 'POST',
                data: {
                    action: action,
                    action_taken: selectedAction
                },
                success: function(res) {
                    const isStartingOrResuming = (action === 'start' || action === 'resume');

                    // Support multiple possible JSON keys from backend or fallback immediately
                    const serverStart = res.start_time ?? res.startTime ?? res.recorded_at;
                    if (serverStart) {
                        $wrap.data('start', serverStart);
                    } else if (isStartingOrResuming) {
                        $wrap.data('start', new Date().toISOString());
                    } else {
                        $wrap.data('start', '');
                    }

                    // Update accumulated seconds (or snapshot displayed time when pausing/finishing)
                    const serverAcc = res.accumulated ?? res.total_seconds ?? res.totalSeconds;
                    if (serverAcc !== undefined && serverAcc !== null) {
                        $wrap.data('accumulated', parseInt(serverAcc) || 0);
                    } else if (!isStartingOrResuming) {
                        const parts = $timer.text().split(':').map(Number);
                        if (parts.length === 3 && !parts.some(isNaN)) {
                            $wrap.data('accumulated', parts[0] * 3600 + parts[1] * 60 + parts[2]);
                        }
                    }

                    // Explicitly update running state so startTimer() ticks immediately
                    $wrap.data('running', res.is_running !== undefined ? Boolean(res.is_running) : isStartingOrResuming);
                    $wrap.data('action-state', action);

                    renderUI();
                    getTimeSpentDuration();
                    loadActivityLogs(ARTWORK_ITEM_CODE);
                },
                error: function(xhr) {
                    renderUI();
                    Swal.fire({
                        icon: 'error',
                        title: 'Timer Error',
                        text: xhr.responseJSON?.message || 'Unable to process timer action.'
                    });
                }
            });
        }

        $actionSelect.on('change', function () {
            renderUI();
        });

        $('.action-btn').on('click', function (e) {
            e.preventDefault();
            const action = $(this).data('action');

            if ($actionSelect.length && !$actionSelect.val()) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Action Required',
                    text: 'Please select a status (Re-touching, Revision, Redesign, or For Proofreading) before starting, pausing, resuming, or finishing the timer.'
                });
                return false;
            }

            if (action === 'finish') {
                const displayLabel = $actionSelect.val() === 'For Profreading' ? 'For Proofreading' : $actionSelect.val();
                Swal.fire({
                    title: 'Finish Task?',
                    text: `This will complete the task under "${displayLabel}" and stop the timer.`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    confirmButtonText: 'Yes, Finish Task'
                }).then((result) => {
                    if (result.isConfirmed) {
                        sendTimerAction('finish');
                    }
                });
            } else {
                sendTimerAction(action);
            }
        });

        // Initialize UI on page load
        renderUI();
    });
</script>

@endsection