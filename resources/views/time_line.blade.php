
@extends('adminlte::page')

@section('title', 'Artwork Workflow')

@section('plugins.Sweetalert2', true)
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)

@section('content_header')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center flex-wrap">
        <div>
            <h1 class="font-weight-bold text-dark mb-1">
                <i class="fas fa-project-diagram text-primary mr-2"></i>
                Artwork Workflow
            </h1>
            <small class="text-muted">
                Artwork routing, assignment and production activity tracking
            </small>
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
                    <small class="info-label">ITEM DESCRIPTION</small>
                    <h5 class="font-weight-bold text-primary mb-0">
                        <i class="fas fa-file mr-2"></i>
                        {{ $artwork->file_info ?? 'N/A' }}
                    </h5>
                </div>

                <div class="col-md-2 mb-3 mb-md-0">
                    <small class="info-label">ITEM CODE</small>
                    <h5 class="font-weight-bold text-primary mb-0">
                        <i class="fas fa-barcode mr-2"></i>
                        {{ $artwork->item_code ?? 'N/A' }}
                    </h5>
                </div>

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
                <i class="fas fa-share-alt text-primary mr-2"></i>
                Production Artwork Workflow
            </h5>
            <small class="text-muted">
                Follow the routing and production activities from left to right.
            </small>
        </div>

        <span class="badge badge-primary px-3 py-2">
            {{ count($activityLogs ?? []) }} TOTAL ACTIVITIES
        </span>
    </div>


    @php
        $activityCount = count($activityLogs ?? []);
    @endphp


    @if($activityCount > 0)

        {{-- WORKFLOW SCROLL WRAPPER --}}
        <div class="workflow-scroll-wrapper">

            {{-- LEFT ARROW --}}
            <button type="button"
                    class="workflow-scroll-btn scroll-left"
                    id="scrollWorkflowLeft"
                    aria-label="Scroll workflow left">
                <i class="fas fa-chevron-left"></i>
            </button>

            {{-- RIGHT ARROW --}}
            <button type="button"
                    class="workflow-scroll-btn scroll-right"
                    id="scrollWorkflowRight"
                    aria-label="Scroll workflow right">
                <i class="fas fa-chevron-right"></i>
            </button>


            {{-- SCROLLABLE WORKFLOW --}}
            <div class="flowchart-container" id="workflowScroll">

                <div class="flowchart">

                    {{-- START NODE --}}
                    <div class="workflow-stage start-stage">

                        <div class="stage-icon start-icon">
                            <i class="fas fa-flag"></i>
                        </div>

                        <div class="stage-label">
                            <strong>START</strong>
                            <small>Artwork Created</small>
                        </div>

                    </div>


                    {{-- INITIAL AO ROUTING --}}
                    <div class="workflow-stage routing-stage">

                        <div class="stage-connector"></div>

                        <div class="stage-icon ao-icon">
                            <i class="fas fa-user-tie"></i>
                        </div>

                        <div class="stage-card">

                            <div class="stage-card-header">
                                <span class="stage-badge badge-ao">
                                    INITIAL ROUTING
                                </span>
                            </div>

                            <div class="stage-card-body">
                                <h6>
                                    <i class="fas fa-user-tie text-primary mr-1"></i>
                                    Account Officer
                                </h6>

                                <p>
                                    Artwork enters the production workflow through the AO.
                                </p>

                                <div class="stage-role">
                                    <i class="fas fa-user mr-1"></i>
                                    {{ $artwork->ao_name ?? 'Account Officer' }}
                                </div>
                            </div>

                        </div>
                    </div>


                    {{-- ACTIVITY LOGS --}}
                    @foreach($activityLogs as $index => $log)

                        @php
                            /*
                            |--------------------------------------------------------------------------
                            | EVENT INFORMATION
                            |--------------------------------------------------------------------------
                            */

                            $eventName = $log->event ?? 'activity';

                            $event = [
                                'icon' => 'fa-cog',
                                'color' => '#64748b',
                                'background' => '#f1f5f9',
                                'label' => 'ACTIVITY',
                                'class' => 'default-event'
                            ];

                            if (in_array($eventName, [
                                'timer_started',
                                'status_initialized'
                            ])) {
                                $event = [
                                    'icon' => 'fa-play',
                                    'color' => '#16a34a',
                                    'background' => '#dcfce7',
                                    'label' => 'STARTED',
                                    'class' => 'started-event'
                                ];

                            } elseif ($eventName === 'timer_paused') {
                                $event = [
                                    'icon' => 'fa-pause',
                                    'color' => '#d97706',
                                    'background' => '#fef3c7',
                                    'label' => 'PAUSED',
                                    'class' => 'paused-event'
                                ];

                            } elseif ($eventName === 'status_changed') {
                                $event = [
                                    'icon' => 'fa-exchange-alt',
                                    'color' => '#2563eb',
                                    'background' => '#dbeafe',
                                    'label' => 'STATUS CHANGED',
                                    'class' => 'status-event'
                                ];

                            } elseif ($eventName === 'sent_back_to_ao') {
                                $event = [
                                    'icon' => 'fa-reply',
                                    'color' => '#ea580c',
                                    'background' => '#ffedd5',
                                    'label' => 'RETURNED',
                                    'class' => 'returned-event'
                                ];

                            } elseif (
                                $eventName === 'artist_assigned' ||
                                str_contains($eventName, 'assigned')
                            ) {
                                $event = [
                                    'icon' => 'fa-palette',
                                    'color' => '#9333ea',
                                    'background' => '#f3e8ff',
                                    'label' => 'ASSIGNED',
                                    'class' => 'assigned-event'
                                ];
                            }


                            /*
                            |--------------------------------------------------------------------------
                            | ACTIVITY ACTOR / ROLE
                            |--------------------------------------------------------------------------
                            */

                            $actorName = $log->user->name ?? 'System Process';

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


                            /*
                            |--------------------------------------------------------------------------
                            | ACTIVITY PROPERTIES
                            |--------------------------------------------------------------------------
                            */

                            $properties = $log->properties ?? [];

                            if (is_string($properties)) {
                                $properties = json_decode($properties, true) ?? [];
                            }

                            if (!is_array($properties)) {
                                $properties = [];
                            }


                            /*
                            |--------------------------------------------------------------------------
                            | ASSIGNMENT INFORMATION
                            |--------------------------------------------------------------------------
                            | These values should be stored in the activity log properties.
                            |--------------------------------------------------------------------------
                            */

                            $assignedBy = data_get(
                                $properties,
                                'assigned_by_name',
                                $actorName
                            );

                            $gdSupervisor = data_get(
                                $properties,
                                'gd_supervisor_name'
                            ) ?? data_get(
                                $properties,
                                'assigned_to_gd_supervisor_name'
                            );

                            $commercialArtist = data_get(
                                $properties,
                                'commercial_artist_name'
                            ) ?? data_get(
                                $properties,
                                'artist_name'
                            );

                            /*
                             * Show assignment routing only when the event is
                             * an assignment event or assignment information exists.
                             */

                            $isAssignment = (
                                str_contains($eventName, 'assigned') ||
                                isset($properties['gd_supervisor_name']) ||
                                isset($properties['assigned_to_gd_supervisor_name']) ||
                                isset($properties['commercial_artist_name']) ||
                                isset($properties['artist_name'])
                            );

                            $timestamp = $log->created_at;
                        @endphp


                        {{-- ACTIVITY STAGE --}}
                        <div class="workflow-stage activity-stage">

                            <div class="stage-connector"></div>

                            {{-- NODE ICON --}}
                            <div class="stage-icon"
                                 style="
                                    color: {{ $event['color'] }};
                                    background: {{ $event['background'] }};
                                    border-color: {{ $event['color'] }};
                                 ">
                                <i class="fas {{ $event['icon'] }}"></i>
                            </div>


                            {{-- ACTIVITY CARD --}}
                            <div class="stage-card activity-card">

                                <div class="stage-card-header">

                                    <span class="stage-badge"
                                          style="
                                            color: {{ $event['color'] }};
                                            background: {{ $event['background'] }};
                                          ">
                                        {{ $event['label'] }}
                                    </span>

                                    <small class="activity-number">
                                        #{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                                    </small>

                                </div>


                                <div class="stage-card-body">

                                    <h6 class="activity-title">
                                        {{ $log->description ?? 'Activity recorded' }}
                                    </h6>


                                    {{-- ASSIGNMENT WORKFLOW --}}
                                    @if($isAssignment)

                                        <div class="assignment-flow">

                                            <div class="assignment-flow-title">
                                                <i class="fas fa-project-diagram mr-1"></i>
                                                ASSIGNMENT ROUTING
                                            </div>


                                            {{-- AO --}}
                                            {{-- <div class="assignment-step ao-step">

                                                <div class="assignment-icon">
                                                    <i class="fas fa-user-tie"></i>
                                                </div>

                                                <div class="assignment-content">
                                                    <small>ASSIGNED BY</small>
                                                    <strong>
                                                        {{ $assignedBy ?: 'Not Recorded' }}
                                                    </strong>
                                                    <span>Account Officer / Sender</span>
                                                </div>

                                            </div> --}}


                                            {{-- <div class="assignment-arrow">
                                                <i class="fas fa-arrow-down"></i>
                                            </div> --}}


                                            {{-- GD SUPERVISOR --}}
                                            <div class="assignment-step supervisor-step">

                                                <div class="assignment-icon">
                                                    <i class="fas fa-user-shield"></i>
                                                </div>

                                                <div class="assignment-content">
                                                    <small>ASSIGNED TO</small>
                                                    <strong>
                                                        {{ $assignedBy ?: 'Not Recorded' }}
                                                    </strong>
                                                    <span>GD Supervisor</span>
                                                </div>

                                            </div>


                                            <div class="assignment-arrow">
                                                <i class="fas fa-arrow-down"></i>
                                            </div>


                                            {{-- COMMERCIAL ARTIST --}}
                                            <div class="assignment-step artist-step">

                                                <div class="assignment-icon">
                                                    <i class="fas fa-palette"></i>
                                                </div>

                                                <div class="assignment-content">
                                                    <small>ASSIGNED TO</small>
                                                    <strong>
                                                        {{ $commercialArtist ?: 'Not Yet Assigned' }}
                                                    </strong>
                                                    <span>Commercial Artist</span>
                                                </div>

                                            </div>

                                        </div>

                                    @endif


                                    {{-- ARTIST NOTES --}}
                                    @if(
                                        $log->log_type === 'action_taken' &&
                                        !empty(data_get($properties, 'artist_notes'))
                                    )

                                        <div class="flow-notes">
                                            <div class="notes-title">
                                                <i class="fas fa-comment-alt mr-1"></i>
                                                ARTIST NOTES
                                            </div>

                                            <p>
                                                "{{ data_get($properties, 'artist_notes') }}"
                                            </p>
                                        </div>

                                    @endif


                                    {{-- TIME SPENT --}}
                                    @if(!empty(data_get($properties, 'time_spent')))

                                        <div class="flow-duration">
                                            <i class="fas fa-stopwatch mr-1"></i>
                                            <span>Production Duration</span>
                                            <strong>
                                                {{ data_get($properties, 'time_spent') }}
                                            </strong>
                                        </div>

                                    @endif

                                </div>


                                {{-- ACTIVITY FOOTER --}}
                                <div class="stage-card-footer">

                                    <div class="actor-avatar">
                                        <i class="fas fa-user"></i>
                                    </div>

                                    <div class="actor-details">
                                        <strong>{{ $actorName }}</strong>
                                        <small>{{ $roleName }}</small>
                                    </div>

                                </div>


                                {{-- TIMESTAMP --}}
                                <div class="flow-timestamp">
                                    <i class="far fa-clock mr-1"></i>

                                    {{ $timestamp->format('M d, Y - g:i A') }}

                                    <span class="mx-1">•</span>

                                    {{ $timestamp->diffForHumans() }}
                                </div>

                            </div>

                        </div>

                    @endforeach


                    {{-- END NODE --}}
                    <div class="workflow-stage end-stage">

                        <div class="stage-connector"></div>

                        <div class="stage-icon end-icon">
                            <i class="fas fa-flag-checkered"></i>
                        </div>

                        <div class="stage-label">
                            <strong>END</strong>
                            <small>Workflow History</small>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        <div class="flow-scroll-hint text-muted mt-3">
            <i class="fas fa-arrows-alt-h mr-1"></i>
            Use the transparent arrow buttons or scroll horizontally to explore the workflow.
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


{{-- =========================================================
     CSS
========================================================= --}}
@section('css')
<style>

/* GENERAL */
.workflow-header {
    border-radius: 12px;
    border-left: 4px solid #2563eb !important;
}

.info-label {
    display: block;
    color: #94a3b8;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 1px;
    margin-bottom: 7px;
}

.legend-item {
    display: inline-flex;
    align-items: center;
    margin-right: 20px;
    color: #64748b;
    font-size: 12px;
    font-weight: 600;
}

.legend-dot {
    height: 9px;
    width: 9px;
    border-radius: 50%;
    margin-right: 7px;
}

.bg-purple {
    background: #9333ea !important;
}

.bg-orange {
    background: #ea580c !important;
}


/* WORKFLOW SCROLL WRAPPER */
.workflow-scroll-wrapper {
    position: relative;
    width: 100%;
    border-radius: 14px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    overflow: hidden;
}


/* SCROLLABLE AREA */
.flowchart-container {
    width: 100%;
    overflow-x: auto;
    overflow-y: hidden;
    padding: 35px 65px 35px;
    scroll-behavior: smooth;
    scrollbar-width: none;
    -ms-overflow-style: none;
}

.flowchart-container::-webkit-scrollbar {
    display: none;
}


/* HORIZONTAL FLOW */
.flowchart {
    display: flex;
    align-items: flex-start;
    flex-wrap: nowrap;
    width: max-content;
    min-width: 100%;
    position: relative;
    padding-bottom: 10px;
}


/* EACH WORKFLOW STAGE */
.workflow-stage {
    position: relative;
    flex: 0 0 310px;
    width: 310px;
    min-height: 450px;
    display: flex;
    flex-direction: column;
    align-items: center;
}


/* CONNECTOR LINE */
.stage-connector {
    position: absolute;
    top: 29px;
    left: 0;
    width: 100%;
    height: 3px;
    background: #cbd5e1;
    z-index: 0;
}

.stage-connector::after {
    content: "";
    position: absolute;
    right: -1px;
    top: -5px;
    width: 0;
    height: 0;
    border-top: 6px solid transparent;
    border-bottom: 6px solid transparent;
    border-left: 8px solid #94a3b8;
}


/* STAGE ICON */
.stage-icon {
    position: relative;
    z-index: 2;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    border: 4px solid #2563eb;
    background: #dbeafe;
    color: #2563eb;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 21px;
    box-shadow: 0 0 0 7px #f8fafc;
}


/* START AND END */
.start-icon {
    background: #dcfce7;
    color: #16a34a;
    border-color: #16a34a;
}

.end-icon {
    background: #e0e7ff;
    color: #4338ca;
    border-color: #4338ca;
}

.stage-label {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-top: 18px;
    text-align: center;
}

.stage-label strong {
    color: #1e293b;
    font-size: 15px;
    letter-spacing: 1px;
}

.stage-label small {
    color: #94a3b8;
    font-size: 11px;
    margin-top: 4px;
}


/* STAGE CARDS */
.stage-card {
    position: relative;
    z-index: 2;
    width: 280px;
    margin-top: 25px;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    box-shadow: 0 5px 18px rgba(15, 23, 42, 0.06);
    overflow: hidden;
    transition: all .25s ease;
}

.stage-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 25px rgba(15, 23, 42, 0.10);
}


/* CARD HEADER */
.stage-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 13px 15px;
    border-bottom: 1px solid #f1f5f9;
}

.stage-badge {
    display: inline-block;
    padding: 5px 9px;
    border-radius: 20px;
    font-size: 9px;
    font-weight: 800;
    letter-spacing: .6px;
}

.badge-ao {
    color: #2563eb;
    background: #dbeafe;
}

.activity-number {
    color: #94a3b8;
    font-size: 10px;
    font-weight: 800;
}


/* CARD BODY */
.stage-card-body {
    padding: 16px;
}

.stage-card-body h6 {
    font-size: 14px;
    font-weight: 800;
    color: #1e293b;
    line-height: 1.5;
    margin-bottom: 10px;
}

.stage-card-body p {
    color: #64748b;
    font-size: 11px;
    line-height: 1.6;
    margin-bottom: 12px;
}

.stage-role {
    padding: 9px 10px;
    background: #eff6ff;
    color: #2563eb;
    border-radius: 7px;
    font-size: 11px;
    font-weight: 700;
}


/* ASSIGNMENT FLOW */
.assignment-flow {
    margin-top: 16px;
    padding: 13px;
    border: 1px solid #e9d5ff;
    border-radius: 10px;
    background: #faf5ff;
}

.assignment-flow-title {
    color: #7e22ce;
    font-size: 10px;
    font-weight: 900;
    letter-spacing: .8px;
    margin-bottom: 13px;
}


/* ASSIGNMENT STEPS */
.assignment-step {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 11px 10px;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-left: 3px solid #2563eb;
    border-radius: 8px;
}

.assignment-icon {
    flex: 0 0 34px;
    height: 34px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #dbeafe;
    color: #2563eb;
    font-size: 14px;
}

.assignment-content {
    min-width: 0;
    display: flex;
    flex-direction: column;
}

.assignment-content small {
    font-size: 8px;
    color: #94a3b8;
    font-weight: 900;
    letter-spacing: .6px;
    margin-bottom: 3px;
}

.assignment-content strong {
    color: #1e293b;
    font-size: 11px;
    overflow-wrap: anywhere;
}

.assignment-content span {
    color: #94a3b8;
    font-size: 9px;
    margin-top: 2px;
}


/* ASSIGNMENT STEP COLORS */
.ao-step {
    border-left-color: #2563eb;
}

.supervisor-step {
    border-left-color: #9333ea;
}

.supervisor-step .assignment-icon {
    background: #f3e8ff;
    color: #9333ea;
}

.artist-step {
    border-left-color: #16a34a;
}

.artist-step .assignment-icon {
    background: #dcfce7;
    color: #16a34a;
}


/* ASSIGNMENT ARROWS */
.assignment-arrow {
    height: 25px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #a78bfa;
    font-size: 13px;
}


/* ACTOR FOOTER */
.stage-card-footer {
    display: flex;
    align-items: center;
    gap: 9px;
    padding: 12px 15px;
    border-top: 1px solid #f1f5f9;
    background: #fafafa;
}

.actor-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #e0e7ff;
    color: #4338ca;
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
    overflow-wrap: anywhere;
}

.actor-details small {
    color: #94a3b8;
    font-size: 9px;
}


/* TIMESTAMP */
.flow-timestamp {
    padding: 10px 15px;
    color: #94a3b8;
    font-size: 9px;
    border-top: 1px solid #f1f5f9;
}


/* NOTES */
.flow-notes {
    margin-top: 13px;
    padding: 11px;
    border-left: 3px solid #0ea5e9;
    background: #f0f9ff;
    border-radius: 5px;
}

.notes-title {
    font-size: 9px;
    font-weight: 900;
    color: #0369a1;
    margin-bottom: 6px;
}

.flow-notes p {
    margin: 0;
    font-size: 11px;
    color: #475569;
    overflow-wrap: anywhere;
}


/* DURATION */
.flow-duration {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 5px;
    margin-top: 12px;
    padding: 10px;
    background: #f1f5f9;
    border-radius: 7px;
    font-size: 10px;
    color: #64748b;
}

.flow-duration strong {
    color: #1e293b;
    margin-left: auto;
}


/* TRANSPARENT SCROLL ARROWS */
.workflow-scroll-btn {
    position: absolute;
    top: 45%;
    transform: translateY(-50%);
    z-index: 20;

    width: 43px;
    height: 43px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 50%;
    border: 1px solid rgba(148, 163, 184, .55);

    background: rgba(30, 41, 59, .20);
    color: #ffffff;

    backdrop-filter: blur(6px);
    -webkit-backdrop-filter: blur(6px);

    cursor: pointer;
    transition: all .25s ease;
}

.workflow-scroll-btn:hover {
    background: rgba(30, 41, 59, .78);
    border-color: #ffffff;
    color: #ffffff;
    transform: translateY(-50%) scale(1.08);
}

.workflow-scroll-btn:active {
    transform: translateY(-50%) scale(.94);
}

.workflow-scroll-btn:focus {
    outline: none;
    box-shadow: none;
}

.scroll-left {
    left: 10px;
}

.scroll-right {
    right: 10px;
}


/* SCROLL HINT */
.flow-scroll-hint {
    font-size: 11px;
}


/* EMPTY STATE */
.empty-flow {
    padding: 60px 20px;
    text-align: center;
    background: #f8fafc;
    border: 1px dashed #cbd5e1;
    border-radius: 12px;
}

.empty-flow > i {
    font-size: 45px;
    color: #cbd5e1;
}

.empty-flow h5 {
    color: #334155;
}


/* MOBILE */
@media (max-width: 768px) {

    .flowchart-container {
        padding: 30px 50px;
    }

    .workflow-stage {
        flex-basis: 290px;
        width: 290px;
    }

    .stage-card {
        width: 260px;
    }

    .workflow-scroll-btn {
        width: 35px;
        height: 35px;
        font-size: 12px;
    }

    .scroll-left {
        left: 5px;
    }

    .scroll-right {
        right: 5px;
    }

    .legend-item {
        margin-right: 12px;
        font-size: 10px;
    }
}

</style>
@stop


{{-- =========================================================
     JAVASCRIPT
========================================================= --}}
@section('js')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const workflow = document.getElementById('workflowScroll');
    const leftButton = document.getElementById('scrollWorkflowLeft');
    const rightButton = document.getElementById('scrollWorkflowRight');

    if (!workflow || !leftButton || !rightButton) {
        return;
    }

    // Scroll to the left
    leftButton.addEventListener('click', function () {
        workflow.scrollBy({
            left: -400,
            behavior: 'smooth'
        });
    });

    // Scroll to the right
    rightButton.addEventListener('click', function () {
        workflow.scrollBy({
            left: 400,
            behavior: 'smooth'
        });
    });

    // Hide the left/right arrows when scrolling is not possible
    function updateScrollButtons() {

        const maxScroll = workflow.scrollWidth - workflow.clientWidth;

        leftButton.style.opacity =
            workflow.scrollLeft <= 5 ? '0.35' : '1';

        rightButton.style.opacity =
            workflow.scrollLeft >= maxScroll - 5 ? '0.35' : '1';

        leftButton.style.pointerEvents =
            workflow.scrollLeft <= 5 ? 'none' : 'auto';

        rightButton.style.pointerEvents =
            workflow.scrollLeft >= maxScroll - 5 ? 'none' : 'auto';

        // No need for arrows when everything fits
        if (maxScroll <= 5) {
            leftButton.style.display = 'none';
            rightButton.style.display = 'none';
        } else {
            leftButton.style.display = 'flex';
            rightButton.style.display = 'flex';
        }
    }

    workflow.addEventListener('scroll', updateScrollButtons);
    window.addEventListener('resize', updateScrollButtons);

    // Initial button state
    updateScrollButtons();

});
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
@stop



@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


<script>
    //Clock
    
</script>
@endsection