 @extends('adminlte::page')

@section('title', 'Project Tracking Status')

@section('plugins.Sweetalert2', true)
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)

@section('content_header')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center flex-wrap">
        <div>
            <h1 class="font-weight-bold text-dark mb-1">
                <i class="fas fa-project-diagram text-primary mr-2"></i>
                Artwork Workflow Timeline
            </h1>
            <small class="text-muted">Production process visualization and activity tracking</small>
        </div>
    </div>
</div>
@stop

@section('content')
<div class="container-fluid">

    {{-- ARTWORK INFORMATION --}}
    <div class="card workflow-header shadow-sm border-0 mb-4">
        <div class="card-body">
            <div class="row align-items-center">

                <div class="col-md-4 mb-3 mb-md-0">
                    <small class="info-label">CLIENT / PROJECT</small>
                    <h5 class="font-weight-bold text-dark mb-0">
                        <i class="fas fa-building text-primary mr-2"></i>
                        {{ $artwork->client_name ?? 'Workflow Routing Progress' }}
                    </h5>
                </div>

                   <div class="col-md-4 mb-3 mb-md-0">
                    <small class="info-label">ITEM Description</small>
                    <h5 class="font-weight-bold text-primary mb-0">
                        <i class="fas fa-file mr-2"></i>
                        {{ $artwork->file_info }}
                    </h5>
                </div>

                <div class="col-md-2 mb-3 mb-md-0">
                    <small class="info-label">ITEM CODE</small>
                    <h5 class="font-weight-bold text-primary mb-0">
                        <i class="fas fa-barcode mr-2"></i>
                        {{ $artwork->item_code }}
                    </h5>
                </div>

                {{-- <div class="col-md-4">
                    <small class="info-label">ASSIGNED  AO</small>
                    <h5 class="font-weight-bold text-dark mb-0">
                        <i class="fas fa-user-tie text-secondary mr-2"></i>
                        {{ $artwork->ao_name ?? 'N/A' }}
                    </h5>
                </div> --}}
                

            </div>
        </div>
    </div>

    {{-- WORKFLOW LEGEND --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body py-3">
            <div class="d-flex align-items-center flex-wrap">

                <span class="font-weight-bold text-dark mr-3 mb-2">
                    <i class="fas fa-palette text-primary mr-1"></i>
                    Workflow Legend:
                </span>

                <span class="legend-item mb-2">
                    <span class="legend-dot bg-success"></span> Started
                </span>

                <span class="legend-item mb-2">
                    <span class="legend-dot bg-warning"></span> Paused
                </span>

                <span class="legend-item mb-2">
                    <span class="legend-dot bg-primary"></span> Status Changed
                </span>

                <span class="legend-item mb-2">
                    <span class="legend-dot bg-purple"></span> Assigned
                </span>

                <span class="legend-item mb-2">
                    <span class="legend-dot bg-orange"></span> Returned
                </span>

            </div>
        </div>
    </div>

    {{-- WORKFLOW TITLE --}}
    <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
        <div>
            <h5 class="font-weight-bold text-dark mb-1">
                Production ArtWork Flow
            </h5>
            <small class="text-muted">
                Follow the sequence of activities from left to right.
            </small>
        </div>

        <span class="badge badge-primary px-3 py-2">
            {{ count($activityLogs ?? []) }} TOTAL ACTIVITIES
        </span>
    </div>

    @php
        $lastDate = null;
        $activityCount = count($activityLogs ?? []);
    @endphp

    @if($activityCount > 0)

        <div class="flowchart-container">

            <div class="flowchart">

                {{-- START NODE --}}
                <div class="flow-start">
                    <div class="start-circle">
                        <i class="fas fa-flag"></i>
                    </div>
                    <strong>START</strong>
                </div>

                @foreach($activityLogs as $index => $log)

                    @php
                        $currentDate = $log->created_at->format('M d, Y');

                        // Default event
                        $event = [
                            'icon' => 'fa-cog',
                            'color' => '#64748b',
                            'background' => '#f1f5f9',
                            'label' => 'ACTIVITY'
                        ];

                        if (in_array($log->event, ['timer_started', 'status_initialized'])) {
                            $event = [
                                'icon' => 'fa-play',
                                'color' => '#16a34a',
                                'background' => '#dcfce7',
                                'label' => 'STARTED'
                            ];
                        } elseif ($log->event === 'timer_paused') {
                            $event = [
                                'icon' => 'fa-pause',
                                'color' => '#d97706',
                                'background' => '#fef3c7',
                                'label' => 'PAUSED'
                            ];
                        } elseif ($log->event === 'status_changed') {
                            $event = [
                                'icon' => 'fa-exchange-alt',
                                'color' => '#2563eb',
                                'background' => '#dbeafe',
                                'label' => 'STATUS CHANGED'
                            ];
                        } elseif ($log->event === 'sent_back_to_ao') {
                            $event = [
                                'icon' => 'fa-reply',
                                'color' => '#ea580c',
                                'background' => '#ffedd5',
                                'label' => 'RETURNED'
                            ];
                        } elseif ($log->event === 'artist_assigned') {
                            $event = [
                                'icon' => 'fas fa-palette',
                                'color' => '#9333ea',
                                'background' => '#f3e8ff',
                                'label' => 'ASSIGNED'
                            ];
                        }

                        // Role identification
                        $roleName = 'System';

                        if ($log->user) {
                            if (isset($log->user->role_id)) {
                                $roleName = match((int) $log->user->role_id) {
                                    1 => 'Administrator',
                                    2 => 'AO Supervisor',
                                    3 => 'GD Supervisor',
                                    4 => 'Commercial Artist',
                                    default => 'Staff Specialist'
                                };
                            } elseif ($log->user->role) {
                                $roleName = $log->user->role->name ?? 'Staff';
                            }
                        }
                    @endphp

                    {{-- DATE SEPARATOR --}}
                    {{-- @if($lastDate !== $currentDate)

                        <div class="flow-date">
                            <div class="date-line"></div>
                            <span>
                                <i class="far fa-calendar-alt mr-1"></i>
                                {{ $currentDate }}
                            </span>
                            <div class="date-line"></div>
                        </div>

                        @php $lastDate = $currentDate; @endphp

                    @endif --}}

                    {{-- FLOW NODE --}}
                    <div class="flow-node">

                        {{-- CONNECTOR --}}
                        <div class="flow-connector"></div>

                        {{-- NODE ICON --}}
                        <div class="node-icon"
                             style="--node-color: {{ $event['color'] }};
                                    --node-bg: {{ $event['background'] }};">
                            <i class="fas {{ $event['icon'] }}"></i>
                        </div>

                        {{-- NODE NUMBER --}}
                        <div class="node-number">
                            {{-- STEP {{ $index + 1 }} --}}
                        </div>

                        {{-- ACTIVITY CARD --}}
                        <div class="flow-card">

                            <div class="flow-card-header">
                                <span class="event-status"
                                      style="color: {{ $event['color'] }};
                                             background: {{ $event['background'] }};">
                                    {{ $event['label'] }}
                                </span>

                                <small class="text-muted font-weight-bold">
                                    #{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                                </small>
                            </div>

                            <div class="flow-card-body">

                                <h6 class="font-weight-bold text-dark">
                                    {{ $log->description }}
                                </h6>

                                {{-- ARTIST NOTES --}}
                                @if($log->log_type === 'action_taken' && isset($log->properties['artist_notes']))
                                    <div class="flow-notes">
                                        <div class="notes-title">
                                            <i class="fas fa-comment-alt mr-1"></i>
                                            ARTIST NOTES
                                        </div>
                                        "{{ $log->properties['artist_notes'] }}"
                                    </div>
                                @endif

                                {{-- TIME SPENT --}}
                                @if(isset($log->properties['time_spent']))
                                    <div class="flow-duration">
                                        <i class="fas fa-stopwatch mr-1"></i>
                                        <span>Production Duration</span>
                                        <strong>{{ $log->properties['time_spent'] }}</strong>
                                    </div>
                                @endif

                            </div>

                            {{-- ACTOR --}}
                            <div class="flow-card-footer">

                                <div class="actor-avatar">
                                    <i class="fas fa-user"></i>
                                </div>

                                <div class="actor-details">
                                    <strong>
                                        {{ $log->user->name ?? 'System Process' }}
                                    </strong>
                                    <small>{{ $roleName }}</small>
                                </div>

                            </div>

                            {{-- TIMESTAMP --}}
                            <div class="flow-timestamp">
                                <i class="far fa-clock mr-1"></i>
                                {{ $log->created_at->format('g:i A') }}
                                <span class="mx-1">•</span>
                                {{ $log->created_at->diffForHumans() }}
                            </div>

                        </div>

                    </div>

                @endforeach

                {{-- END NODE --}}
                <div class="flow-end">
                    <div class="end-circle">
                        <i class="fas fa-flag-checkered"></i>
                    </div>
                    <strong>END</strong>
                </div>

            </div>

        </div>

        <div class="flow-scroll-hint text-muted mt-2">
            <i class="fas fa-arrows-alt-h mr-1"></i>
            Scroll horizontally to explore the complete production workflow.
        </div>

    @else

        <div class="empty-flow">
            <i class="fas fa-project-diagram"></i>
            <h5 class="font-weight-bold mt-3">No Workflow Activities</h5>
            <p class="text-muted mb-0">
                No production activities have been recorded for this item.
            </p>
        </div>

    @endif

</div>
@stop

@section('css')
<style>
    /* GENERAL */
    .workflow-header {
        border-radius: 12px;
        border-left: 4px solid #3b82f6;
    }

    .info-label {
        display: block;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 1px;
        color: #94a3b8;
        margin-bottom: 7px;
    }

    /* LEGEND */
    .legend-item {
        display: inline-flex;
        align-items: center;
        margin-right: 20px;
        font-size: 12px;
        font-weight: 600;
        color: #64748b;
    }

    .legend-dot {
        width: 9px;
        height: 9px;
        border-radius: 50%;
        margin-right: 6px;
    }

    .bg-purple {
        background-color: #9333ea !important;
    }

    .bg-orange {
        background-color: #ea580c !important;
    }

    /* FLOWCHART CANVAS */
    .flowchart-container {
        width: 100%;
        overflow-x: auto;
        overflow-y: hidden;
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 15px;
        padding: 35px 25px 40px;
        scrollbar-width: thin;
        scrollbar-color: #94a3b8 #e2e8f0;
    }

    .flowchart-container::-webkit-scrollbar {
        height: 9px;
    }

    .flowchart-container::-webkit-scrollbar-track {
        background: #e2e8f0;
        border-radius: 20px;
    }

    .flowchart-container::-webkit-scrollbar-thumb {
        background: #94a3b8;
        border-radius: 20px;
    }

    .flowchart {
        display: flex;
        align-items: flex-start;
        width: max-content;
        min-width: 100%;
        padding: 0 10px;
        margin-top:-110px;
    }

    /* START / END */
    .flow-start,
    .flow-end {
        width: 95px;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        flex-direction: column;
        padding-top: 180px;
        position: relative;
        z-index: 2;
    }

    .flow-start strong,
    .flow-end strong {
        margin-top: 10px;
        font-size: 11px;
        letter-spacing: 1px;
        color: #64748b;
    }

    .start-circle,
    .end-circle {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        border: 5px solid #fff;
        box-shadow: 0 3px 12px rgba(0,0,0,.10);
        font-size: 17px;
    }

    .start-circle {
        background: #dcfce7;
        color: #16a34a;
    }

    .end-circle {
        background: #e2e8f0;
        color: #475569;
    }

    /* ACTIVITY NODE */
    .flow-node {
        position: relative;
        width: 305px;
        flex-shrink: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        z-index: 1;
    }

    /* DATE SEPARATOR */
    .flow-date {
        position: absolute;
        top: 0;
        width: 305px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        height: 35px;
    }

    .flow-date span {
        white-space: nowrap;
        background: #e0e7ff;
        color: #3730a3;
        border-radius: 20px;
        padding: 6px 13px;
        font-size: 11px;
        font-weight: 800;
    }

    .date-line {
        width: 20px;
        height: 1px;
        background: #c7d2fe;
    }

    /* CONNECTOR LINE */
    .flow-connector {
        position: absolute;
        top: 202px;
        left: 0;
        width: 100%;
        height: 4px;
        background: #cbd5e1;
        z-index: 0;
    }

    .flow-connector:after {
        content: '';
        position: absolute;
        right: 0;
        top: -5px;
        width: 0;
        height: 0;
        border-top: 7px solid transparent;
        border-bottom: 7px solid transparent;
        border-left: 10px solid #94a3b8;
    }

    /* MILESTONE ICON */
    .node-icon {
        position: absolute;
        top: 181px;
        width: 46px;
        height: 46px;
        border-radius: 50%;
        background: var(--node-bg);
        color: var(--node-color);
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 17px;
        border: 5px solid #fff;
        box-shadow: 0 3px 12px rgba(0,0,0,.12);
        z-index: 2;
    }

    .node-number {
        position: absolute;
        top: 130px;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 1px;
        color: #94a3b8;
    }

    /* ACTIVITY CARD */
    .flow-card {
        position: relative;
        margin-top: 245px;
        width: 275px;
        min-height: 215px;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(15,23,42,.05);
        overflow: hidden;
        transition: all .25s ease;
    }

    .flow-card:hover {
        transform: translateY(-5px);
        border-color: #93c5fd;
        box-shadow: 0 10px 25px rgba(15,23,42,.10);
    }

    .flow-card-header {
        padding: 13px 14px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #f1f5f9;
    }

    .event-status {
        padding: 5px 9px;
        border-radius: 20px;
        font-size: 9px;
        font-weight: 900;
        letter-spacing: .6px;
    }

    .flow-card-body {
        padding: 15px;
        min-height: 105px;
    }

    .flow-card-body h6 {
        font-size: 13px;
        line-height: 1.6;
        margin-bottom: 0;
        word-break: break-word;
    }

    /* NOTES */
    .flow-notes {
        background: #fffbeb;
        border-left: 3px solid #f59e0b;
        color: #92400e;
        padding: 10px;
        border-radius: 5px;
        font-size: 11px;
        font-style: italic;
        margin-top: 12px;
        word-break: break-word;
    }

    .notes-title {
        font-size: 9px;
        font-weight: 900;
        font-style: normal;
        margin-bottom: 5px;
        letter-spacing: .6px;
    }

    /* DURATION */
    .flow-duration {
        background: #f0fdfa;
        color: #0f766e;
        border-radius: 6px;
        padding: 9px;
        font-size: 10px;
        margin-top: 12px;
    }

    .flow-duration strong {
        display: block;
        font-size: 12px;
        margin-top: 5px;
    }

    /* USER */
    .flow-card-footer {
        display: flex;
        align-items: center;
        gap: 9px;
        padding: 11px 14px;
        background: #f8fafc;
        border-top: 1px solid #f1f5f9;
    }

    .actor-avatar {
        width: 32px;
        height: 32px;
        flex-shrink: 0;
        border-radius: 50%;
        background: #dbeafe;
        color: #2563eb;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
    }

    .actor-details {
        display: flex;
        flex-direction: column;
        min-width: 0;
    }

    .actor-details strong {
        font-size: 11px;
        color: #334155;
        word-break: break-word;
    }

    .actor-details small {
        font-size: 10px;
        color: #64748b;
    }

    /* TIMESTAMP */
    .flow-timestamp {
        text-align: center;
        padding: 9px;
        font-size: 10px;
        font-weight: 600;
        color: #94a3b8;
        border-top: 1px solid #f1f5f9;
    }

    /* EMPTY STATE */
    .empty-flow {
        text-align: center;
        background: #fff;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        padding: 65px 20px;
    }

    .empty-flow > i {
        font-size: 45px;
        color: #cbd5e1;
    }

    .flow-scroll-hint {
        font-size: 11px;
        text-align: right;
    }

    /* MOBILE */
    @media(max-width: 767px) {
        .flowchart-container {
            padding-left: 15px;
            padding-right: 15px;
        }

        .flow-node {
            width: 285px;
        }

        .flow-date {
            width: 285px;
        }

        .flow-card {
            width: 260px;
        }

        .flow-start,
        .flow-end {
            width: 75px;
        }

        .flow-card:hover {
            transform: none;
        }
    }
</style>
@stop


@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


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
@endsection