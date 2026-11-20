@extends('adminlte::page')

@section('title', 'Artwork Workflow')

@section('plugins.Sweetalert2', true)
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)

@section('content_header')
<div class="container-fluid pt-2">
    <div class="hero-header-banner d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div class="d-flex align-items-center">
            <div class="hero-icon-glass mr-3">
                <i class="fas fa-project-diagram"></i>
            </div>
            <div>
                <h1 class="font-weight-bold text-white mb-0" style="font-size: 1.55rem; letter-spacing: -0.02em;">
                    Artwork Workflow
                </h1>
                <p class="text-white-50 mb-0" style="font-size: 0.875rem;">
                    Live production routing, creative assignments &amp; activity timeline
                </p>
            </div>
        </div>

        {{-- Live Clock Pill --}}
        <div class="d-none d-md-flex align-items-center clock-glass-pill px-3 py-2">
            <span class="pulse-dot mr-2"></span>
            <span id="live-date" class="text-white-50 small font-weight-medium mr-2"></span>
            <span class="text-white-50 mr-2">•</span>
            <span id="live-time" class="text-white small font-weight-bold"></span>
        </div>
    </div>
</div>
@stop

@section('content')
<div class="container-fluid pb-5">

    {{-- VIBRANT METADATA & INTERACTIVE FILTER BAR --}}
    <div class="card colorful-meta-card mb-4">
        <div class="card-body p-4">
            <div class="row align-items-center">
                <div class="col-lg-4 col-md-6 mb-3 mb-lg-0 border-right-lg">
                    <span class="meta-label">CLIENT / PROJECT</span>
                    <div class="d-flex align-items-center mt-1">
                        <div class="meta-icon grad-blue mr-2">
                            <i class="fas fa-building"></i>
                        </div>
                        <h6 class="font-weight-bold text-slate-900 mb-0 text-truncate">
                            {{ $artwork->client_name ?? 'Workflow Routing Progress' }}
                        </h6>
                    </div>
                </div>

                <div class="col-lg-5 col-md-6 mb-3 mb-lg-0 border-right-lg">
                    <span class="meta-label">ITEM DESCRIPTION</span>
                    <div class="d-flex align-items-center mt-1">
                        <div class="meta-icon grad-purple mr-2">
                            <i class="fas fa-palette"></i>
                        </div>
                        <h6 class="font-weight-bold text-slate-800 mb-0 text-truncate">
                            {{ $artwork->file_info ?? 'N/A' }}
                        </h6>
                    </div>
                </div>

                <div class="col-lg-3 col-md-12">
                    <span class="meta-label">ITEM CODE</span>
                    <div class="d-flex align-items-center mt-1">
                        <span class="vibrant-code-pill">
                            <i class="fas fa-barcode mr-2"></i>
                            {{ $artwork->item_code ?? 'N/A' }}
                        </span>
                    </div>
                </div>
            </div>

            <hr class="my-3 border-slate-100">

            {{-- COLORFUL INTERACTIVE FILTER PILLS --}}
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="d-flex align-items-center flex-wrap" id="workflowLegend">
                    <span class="text-muted small font-weight-bold mr-3 my-1">
                        FILTER STAGES:
                    </span>
                    <button type="button" class="filter-chip chip-all active" data-filter="all">
                        All Activities <span class="chip-count">{{ count($activityLogs ?? []) }}</span>
                    </button>
                    <button type="button" class="filter-chip chip-started" data-filter="started-event">
                        <span class="chip-dot"></span> Started
                    </button>
                    <button type="button" class="filter-chip chip-paused" data-filter="paused-event">
                        <span class="chip-dot"></span> Paused
                    </button>
                    <button type="button" class="filter-chip chip-status" data-filter="status-event">
                        <span class="chip-dot"></span> Status Changed
                    </button>
                    <button type="button" class="filter-chip chip-assigned" data-filter="assigned-event">
                        <span class="chip-dot"></span> Assigned
                    </button>
                    <button type="button" class="filter-chip chip-returned" data-filter="returned-event">
                        <span class="chip-dot"></span> Returned
                    </button>
                </div>

                <div class="text-muted small d-none d-sm-block">
                    <i class="fas fa-arrows-alt-h text-indigo mr-1"></i> Drag canvas or use arrows to explore
                </div>
            </div>
        </div>
    </div>

    @php
        $activityCount = count($activityLogs ?? []);
    @endphp

    @if($activityCount > 0)
        {{-- AMBIENT MESH WORKFLOW CANVAS --}}
        <div class="workflow-canvas-wrapper">
            
            {{-- EDGE FADES --}}
            <div class="canvas-fade-left" id="fadeLeft"></div>
            <div class="canvas-fade-right" id="fadeRight"></div>

            {{-- GRADIENT SCROLL BUTTONS --}}
            <button type="button" class="canvas-nav-btn nav-left" id="scrollWorkflowLeft" aria-label="Scroll left">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button type="button" class="canvas-nav-btn nav-right" id="scrollWorkflowRight" aria-label="Scroll right">
                <i class="fas fa-chevron-right"></i>
            </button>

            {{-- SCROLLABLE WORKFLOW --}}
            <div class="flowchart-container" id="workflowScroll">
                <div class="flowchart">

                    {{-- START NODE --}}
                    <div class="workflow-stage terminal-stage">
                        <div class="stage-node start-node">
                            <i class="fas fa-rocket"></i>
                        </div>
                        <div class="terminal-label">
                            <span class="badge-terminal badge-start">START</span>
                            <strong>Artwork Created</strong>
                        </div>
                    </div>

                    {{-- INITIAL AO ROUTING --}}
                    <div class="workflow-stage">
                        <div class="stage-connector connector-blue"></div>
                        <div class="stage-node ao-node">
                            <i class="fas fa-user-tie"></i>
                        </div>

                        <div class="vibrant-card card-theme-blue">
                            <div class="card-top-gradient"></div>
                            <div class="card-top-bar">
                                <span class="event-pill">INITIAL ROUTING</span>
                                <span class="step-counter">00</span>
                            </div>
                            <div class="card-content">
                                <h6 class="card-title">Account Officer Entry</h6>
                                <p class="card-desc">Artwork enters the production pipeline through the assigned Account Officer.</p>
                                
                                <div class="role-chip">
                                    <i class="fas fa-user-check mr-2"></i>
                                    <span>{{ $artwork->ao_name ?? 'Account Officer' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ACTIVITY LOGS --}}
                    @foreach($activityLogs as $index => $log)
                        @php
                            $eventName = $log->event ?? 'activity';
                            $event = [
                                'icon'      => 'fa-cog',
                                'label'     => 'ACTIVITY',
                                'class'     => 'default-event',
                                'theme'     => 'card-theme-slate',
                                'nodeGrad'  => 'linear-gradient(135deg, #64748b, #475569)',
                                'glow'      => 'rgba(100, 116, 139, 0.25)',
                                'avatarBg'  => '#e2e8f0',
                                'avatarTxt' => '#334155',
                            ];

                            if (in_array($eventName, ['timer_started', 'status_initialized'])) {
                                $event = [
                                    'icon'      => 'fa-play',
                                    'label'     => 'STARTED',
                                    'class'     => 'started-event',
                                    'theme'     => 'card-theme-emerald',
                                    'nodeGrad'  => 'linear-gradient(135deg, #10b981, #059669)',
                                    'glow'      => 'rgba(16, 185, 129, 0.32)',
                                    'avatarBg'  => '#d1fae5',
                                    'avatarTxt' => '#065f46',
                                ];
                            } elseif ($eventName === 'timer_paused') {
                                $event = [
                                    'icon'      => 'fa-pause',
                                    'label'     => 'PAUSED',
                                    'class'     => 'paused-event',
                                    'theme'     => 'card-theme-amber',
                                    'nodeGrad'  => 'linear-gradient(135deg, #f59e0b, #d97706)',
                                    'glow'      => 'rgba(245, 158, 11, 0.32)',
                                    'avatarBg'  => '#fef3c7',
                                    'avatarTxt' => '#92400e',
                                ];
                            } elseif ($eventName === 'status_changed') {
                                $event = [
                                    'icon'      => 'fa-exchange-alt',
                                    'label'     => 'STATUS CHANGED',
                                    'class'     => 'status-event',
                                    'theme'     => 'card-theme-blue',
                                    'nodeGrad'  => 'linear-gradient(135deg, #3b82f6, #06b6d4)',
                                    'glow'      => 'rgba(59, 130, 246, 0.32)',
                                    'avatarBg'  => '#dbeafe',
                                    'avatarTxt' => '#1e40af',
                                ];
                            } elseif ($eventName === 'sent_back_to_ao') {
                                $event = [
                                    'icon'      => 'fa-reply',
                                    'label'     => 'RETURNED',
                                    'class'     => 'returned-event',
                                    'theme'     => 'card-theme-coral',
                                    'nodeGrad'  => 'linear-gradient(135deg, #f97316, #e11d48)',
                                    'glow'      => 'rgba(244, 63, 94, 0.32)',
                                    'avatarBg'  => '#ffe4e6',
                                    'avatarTxt' => '#9f1239',
                                ];
                            } elseif ($eventName === 'artist_assigned' || str_contains($eventName, 'assigned')) {
                                $event = [
                                    'icon'      => 'fa-palette',
                                    'label'     => 'ASSIGNED',
                                    'class'     => 'assigned-event',
                                    'theme'     => 'card-theme-purple',
                                    'nodeGrad'  => 'linear-gradient(135deg, #8b5cf6, #ec4899)',
                                    'glow'      => 'rgba(168, 85, 247, 0.32)',
                                    'avatarBg'  => '#f3e8ff',
                                    'avatarTxt' => '#6b21a8',
                                ];
                            }

                            $actorName = $log->user->name ?? 'System Process';
                            $initials = collect(explode(' ', $actorName))->map(fn($w) => strtoupper(substr($w, 0, 1)))->take(2)->join('');
                            
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

                            $properties = $log->properties ?? [];
                            if (is_string($properties)) {
                                $properties = json_decode($properties, true) ?? [];
                            }
                            if (!is_array($properties)) {
                                $properties = [];
                            }

                            $assignedBy = data_get($properties, 'assigned_by_name', $actorName);
                            $commercialArtist = data_get($properties, 'commercial_artist_name') ?? data_get($properties, 'artist_name');

                            $isAssignment = (
                                str_contains($eventName, 'assigned') ||
                                isset($properties['gd_supervisor_name']) ||
                                isset($properties['assigned_to_role_id']) ||
                                isset($properties['commercial_artist_name']) ||
                                isset($properties['artist_name'])
                            );

                            $timestamp = $log->created_at;
                        @endphp

                        {{-- ACTIVITY STAGE --}}
                        <div class="workflow-stage activity-item" data-event-type="{{ $event['class'] }}">
                            <div class="stage-connector"></div>

                            {{-- GLOWING GRADIENT NODE --}}
                            <div class="stage-node text-white" style="background: {{ $event['nodeGrad'] }}; box-shadow: 0 0 0 5px #ffffff, 0 8px 18px {{ $event['glow'] }};">
                                <i class="fas {{ $event['icon'] }}"></i>
                            </div>

                            {{-- COLORFUL ACTIVITY CARD --}}
                            <div class="vibrant-card {{ $event['theme'] }}">
                                <div class="card-top-gradient"></div>
                                <div class="card-top-bar">
                                    <span class="event-pill">
                                        {{ $event['label'] }}
                                    </span>
                                    <span class="step-counter">#{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                </div>

                                <div class="card-content">
                                    <h6 class="card-title">
                                        {{ $log->description ?? 'Activity recorded' }}
                                    </h6>

                                    {{-- COLORFUL ROUTING CHAIN --}}
                                    @if($isAssignment)
                                        <div class="routing-tree">
                                            <div class="routing-tree-header">
                                                <i class="fas fa-sitemap mr-1"></i> ASSIGNMENT CHAIN
                                            </div>
                                            
                                            <div class="tree-node">
                                                <div class="tree-line"></div>
                                                <div class="tree-dot dot-purple"></div>
                                                <div class="tree-info">
                                                    <span class="tree-role">GD Supervisor</span>
                                                    <strong class="tree-name">{{ $assignedBy ?: 'Not Recorded' }}</strong>
                                                </div>
                                            </div>

                                            <div class="tree-node">
                                                <div class="tree-dot dot-emerald"></div>
                                                <div class="tree-info">
                                                    <span class="tree-role">Commercial Artist</span>
                                                    <strong class="tree-name">{{ $commercialArtist ?: 'Pending Assignment' }}</strong>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    {{-- ARTIST NOTES --}}
                                    @if($log->log_type === 'action_taken' && !empty(data_get($properties, 'artist_notes')))
                                        <div class="colorful-note-box">
                                            <div class="note-header">
                                                <i class="fas fa-comment-dots mr-1"></i> Artist Note
                                            </div>
                                            <p>"{{ data_get($properties, 'artist_notes') }}"</p>
                                        </div>
                                    @endif

                                    {{-- TIME SPENT --}}
                                    @if(!empty(data_get($properties, 'time_spent')))
                                        <div class="duration-pill">
                                            <span><i class="fas fa-stopwatch mr-1"></i> Duration</span>
                                            <strong>{{ data_get($properties, 'time_spent') }}</strong>
                                        </div>
                                    @endif
                                </div>

                                {{-- CARD FOOTER --}}
                                <div class="card-actor-footer">
                                    <div class="avatar-initials" style="background: {{ $event['avatarBg'] }}; color: {{ $event['avatarTxt'] }};">
                                        {{ $initials ?: 'SY' }}
                                    </div>
                                    <div class="actor-meta">
                                        <strong class="actor-name">{{ $actorName }}</strong>
                                        <span class="actor-role">{{ $roleName }}</span>
                                    </div>
                                </div>

                                <div class="card-time-footer">
                                    <span>{{ $timestamp->format('M d, Y • g:i A') }}</span>
                                    <span class="rel-time">{{ $timestamp->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    {{-- END NODE --}}
                    <div class="workflow-stage terminal-stage">
                        <div class="stage-connector"></div>
                        <div class="stage-node end-node">
                            <i class="fas fa-flag-checkered"></i>
                        </div>
                        <div class="terminal-label">
                            <span class="badge-terminal badge-end">LATEST</span>
                            <strong>Current State</strong>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    @else
        <div class="modern-empty-state">
            <div class="empty-icon-ring">
                <i class="fas fa-project-diagram"></i>
            </div>
            <h5 class="font-weight-bold text-slate-800 mt-3">No Workflow Activities Yet</h5>
            <p class="text-muted mb-0 mx-auto" style="max-width: 400px;">
                Once this artwork is routed or assigned to production staff, each colorful milestone will appear here.
            </p>
        </div>
    @endif

</div>
@stop

@section('css')
<style>
    :root {
        --slate-50: #f8fafc;
        --slate-100: #f1f5f9;
        --slate-200: #e2e8f0;
        --slate-300: #cbd5e1;
        --slate-400: #94a3b8;
        --slate-600: #475569;
        --slate-800: #1e293b;
        --slate-900: #0f172a;
    }

    .text-slate-900 { color: var(--slate-900) !important; }
    .text-slate-800 { color: var(--slate-800) !important; }
    .text-indigo { color: #6366f1 !important; }
    .border-slate-100 { border-color: var(--slate-100) !important; }

    @media (min-width: 992px) {
        .border-right-lg { border-right: 1px solid var(--slate-200); }
    }

    /* VIBRANT HERO HEADER */
    .hero-header-banner {
        padding: 20px 26px;
        border-radius: 18px;
        background: linear-gradient(120deg, #4f46e5 0%, #7c3aed 50%, #db2777 100%);
        box-shadow: 0 12px 28px -6px rgba(99, 102, 241, 0.35);
    }

    .hero-icon-glass {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        background: rgba(255, 255, 255, 0.18);
        border: 1px solid rgba(255, 255, 255, 0.3);
        backdrop-filter: blur(8px);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }

    .clock-glass-pill {
        background: rgba(15, 23, 42, 0.25);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 999px;
        backdrop-filter: blur(8px);
    }

    .pulse-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #34d399;
        box-shadow: 0 0 0 3px rgba(52, 211, 153, 0.35);
    }

    /* METADATA CARD */
    .colorful-meta-card {
        border: 1px solid var(--slate-200);
        border-radius: 18px;
        box-shadow: 0 8px 24px -4px rgba(15, 23, 42, 0.05);
        background: #ffffff;
    }

    .meta-label {
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 0.08em;
        color: var(--slate-400);
    }

    .meta-icon {
        width: 30px;
        height: 30px;
        border-radius: 9px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        color: #ffffff;
        flex-shrink: 0;
    }

    .grad-blue { background: linear-gradient(135deg, #3b82f6, #06b6d4); }
    .grad-purple { background: linear-gradient(135deg, #8b5cf6, #ec4899); }

    .vibrant-code-pill {
        display: inline-flex;
        align-items: center;
        padding: 5px 14px;
        background: linear-gradient(135deg, #eef2ff, #fae8ff);
        border: 1px solid #e0e7ff;
        border-radius: 999px;
        font-family: ui-monospace, SFMono-Regular, monospace;
        font-size: 13px;
        font-weight: 700;
        color: #4f46e5;
    }

    /* COLORFUL INTERACTIVE FILTER CHIPS */
    .filter-chip {
        display: inline-flex;
        align-items: center;
        padding: 6px 14px;
        margin: 3px 6px 3px 0;
        border-radius: 999px;
        border: 1px solid var(--slate-200);
        background: #ffffff;
        color: var(--slate-600);
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .filter-chip:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(15, 23, 42, 0.06);
    }

    .chip-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        margin-right: 7px;
    }

    .chip-count {
        margin-left: 6px;
        padding: 1px 7px;
        border-radius: 999px;
        font-size: 10px;
        background: rgba(255, 255, 255, 0.25);
    }

    /* Chip Colors & Active States */
    .chip-all.active { background: linear-gradient(135deg, #4f46e5, #7c3aed); color: #fff; border-color: transparent; }
    .chip-all:not(.active) .chip-count { background: var(--slate-100); color: var(--slate-600); }

    .chip-started .chip-dot { background: #10b981; }
    .chip-started.active { background: linear-gradient(135deg, #10b981, #059669); color: #fff; border-color: transparent; }
    .chip-started.active .chip-dot { background: #fff; }

    .chip-paused .chip-dot { background: #f59e0b; }
    .chip-paused.active { background: linear-gradient(135deg, #f59e0b, #d97706); color: #fff; border-color: transparent; }
    .chip-paused.active .chip-dot { background: #fff; }

    .chip-status .chip-dot { background: #3b82f6; }
    .chip-status.active { background: linear-gradient(135deg, #3b82f6, #06b6d4); color: #fff; border-color: transparent; }
    .chip-status.active .chip-dot { background: #fff; }

    .chip-assigned .chip-dot { background: #a855f7; }
    .chip-assigned.active { background: linear-gradient(135deg, #8b5cf6, #ec4899); color: #fff; border-color: transparent; }
    .chip-assigned.active .chip-dot { background: #fff; }

    .chip-returned .chip-dot { background: #f43f5e; }
    .chip-returned.active { background: linear-gradient(135deg, #f97316, #e11d48); color: #fff; border-color: transparent; }
    .chip-returned.active .chip-dot { background: #fff; }

    /* MESH GRADIENT + DOT GRID CANVAS */
    .workflow-canvas-wrapper {
        position: relative;
        width: 100%;
        border-radius: 20px;
        border: 1px solid #e0e7ff;
        background-color: #f8faff;
        background-image:
            radial-gradient(at 10% 15%, rgba(99, 102, 241, 0.09) 0px, transparent 45%),
            radial-gradient(at 90% 20%, rgba(236, 72, 153, 0.08) 0px, transparent 45%),
            radial-gradient(at 50% 90%, rgba(6, 182, 212, 0.08) 0px, transparent 50%),
            radial-gradient(#cbd5e1 1.1px, transparent 1.1px);
        background-size: 100% 100%, 100% 100%, 100% 100%, 22px 22px;
        overflow: hidden;
    }

    .canvas-fade-left, .canvas-fade-right {
        position: absolute;
        top: 0;
        bottom: 0;
        width: 60px;
        z-index: 10;
        pointer-events: none;
        transition: opacity 0.25s ease;
    }
    .canvas-fade-left { left: 0; background: linear-gradient(to right, rgba(248, 250, 255, 0.95), transparent); }
    .canvas-fade-right { right: 0; background: linear-gradient(to left, rgba(248, 250, 255, 0.95), transparent); }

    .flowchart-container {
        width: 100%;
        overflow-x: auto;
        overflow-y: hidden;
        padding: 46px 70px;
        cursor: grab;
        scrollbar-width: none;
    }
    .flowchart-container:active { cursor: grabbing; }
    .flowchart-container::-webkit-scrollbar { display: none; }

    .flowchart {
        display: flex;
        align-items: flex-start;
        flex-wrap: nowrap;
        width: max-content;
        min-width: 100%;
    }

    /* STAGES & GRADIENT CONNECTORS */
    .workflow-stage {
        position: relative;
        flex: 0 0 330px;
        width: 330px;
        display: flex;
        flex-direction: column;
        align-items: center;
        transition: opacity 0.3s ease, transform 0.3s ease;
    }

    .workflow-stage.dimmed {
        opacity: 0.22;
        transform: scale(0.97);
    }

    .terminal-stage {
        flex: 0 0 165px;
        width: 165px;
    }

    .stage-connector {
        position: absolute;
        top: 26px;
        left: -50%;
        width: 100%;
        height: 3px;
        background: linear-gradient(90deg, #c7d2fe, #e9d5ff, #fbcfe8);
        z-index: 1;
    }

    .terminal-stage .stage-connector {
        left: -102%;
        width: 152%;
    }

    /* NODES */
    .stage-node {
        position: relative;
        z-index: 3;
        width: 52px;
        height: 52px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 19px;
        transition: transform 0.25s ease;
    }

    .workflow-stage:hover .stage-node {
        transform: scale(1.1) rotate(3deg);
    }

    .start-node {
        background: linear-gradient(135deg, #10b981, #06b6d4);
        color: #ffffff;
        box-shadow: 0 0 0 5px #ffffff, 0 8px 20px rgba(16, 185, 129, 0.35);
    }

    .ao-node {
        background: linear-gradient(135deg, #3b82f6, #6366f1);
        color: #ffffff;
        box-shadow: 0 0 0 5px #ffffff, 0 8px 20px rgba(59, 130, 246, 0.35);
    }

    .end-node {
        background: linear-gradient(135deg, #6366f1, #ec4899);
        color: #ffffff;
        box-shadow: 0 0 0 5px #ffffff, 0 8px 20px rgba(139, 92, 246, 0.35);
    }

    .terminal-label {
        margin-top: 16px;
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .badge-terminal {
        font-size: 9px;
        font-weight: 800;
        letter-spacing: 0.08em;
        color: #ffffff;
        padding: 3px 10px;
        border-radius: 999px;
        margin-bottom: 5px;
    }
    .badge-start { background: linear-gradient(135deg, #10b981, #06b6d4); }
    .badge-end { background: linear-gradient(135deg, #6366f1, #ec4899); }

    .terminal-label strong {
        font-size: 13px;
        color: var(--slate-800);
    }

    /* VIBRANT COLOR-THEMED CARDS */
    .vibrant-card {
        position: relative;
        z-index: 2;
        width: 300px;
        margin-top: 24px;
        background: #ffffff;
        border-radius: 18px;
        border: 1px solid var(--slate-200);
        box-shadow: 0 12px 28px -6px rgba(15, 23, 42, 0.07);
        overflow: hidden;
        transition: transform 0.25s cubic-bezier(0.22, 1, 0.36, 1), box-shadow 0.25s ease;
    }

    .vibrant-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 22px 35px -10px rgba(15, 23, 42, 0.14);
    }

    .card-top-gradient {
        height: 5px;
        width: 100%;
    }

    /* Theme 1: Blue (Status / AO) */
    .card-theme-blue { border-color: #bfdbfe; background: linear-gradient(180deg, #eff6ff 0%, #ffffff 32%); }
    .card-theme-blue .card-top-gradient { background: linear-gradient(90deg, #3b82f6, #06b6d4); }
    .card-theme-blue .event-pill { background: #dbeafe; color: #1d4ed8; }

    /* Theme 2: Emerald (Started) */
    .card-theme-emerald { border-color: #a7f3d0; background: linear-gradient(180deg, #ecfdf5 0%, #ffffff 32%); }
    .card-theme-emerald .card-top-gradient { background: linear-gradient(90deg, #10b981, #14b8a6); }
    .card-theme-emerald .event-pill { background: #d1fae5; color: #047857; }

    /* Theme 3: Amber (Paused) */
    .card-theme-amber { border-color: #fde68a; background: linear-gradient(180deg, #fffbeb 0%, #ffffff 32%); }
    .card-theme-amber .card-top-gradient { background: linear-gradient(90deg, #f59e0b, #f97316); }
    .card-theme-amber .event-pill { background: #fef3c7; color: #b45309; }

    /* Theme 4: Purple-Pink (Assigned) */
    .card-theme-purple { border-color: #e9d5ff; background: linear-gradient(180deg, #faf5ff 0%, #ffffff 32%); }
    .card-theme-purple .card-top-gradient { background: linear-gradient(90deg, #8b5cf6, #ec4899); }
    .card-theme-purple .event-pill { background: #f3e8ff; color: #7e22ce; }

    /* Theme 5: Coral-Rose (Returned) */
    .card-theme-coral { border-color: #fecdd3; background: linear-gradient(180deg, #fff1f2 0%, #ffffff 32%); }
    .card-theme-coral .card-top-gradient { background: linear-gradient(90deg, #f97316, #e11d48); }
    .card-theme-coral .event-pill { background: #ffe4e6; color: #be123c; }

    /* Theme 6: Slate (Default) */
    .card-theme-slate { border-color: #e2e8f0; background: linear-gradient(180deg, #f8fafc 0%, #ffffff 32%); }
    .card-theme-slate .card-top-gradient { background: linear-gradient(90deg, #64748b, #94a3b8); }
    .card-theme-slate .event-pill { background: #f1f5f9; color: #475569; }

    .card-top-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 16px 8px;
    }

    .event-pill {
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 0.06em;
        padding: 4px 11px;
        border-radius: 999px;
    }

    .step-counter {
        font-family: ui-monospace, SFMono-Regular, monospace;
        font-size: 11px;
        font-weight: 800;
        color: var(--slate-400);
    }

    .card-content {
        padding: 8px 16px 16px;
    }

    .card-title {
        font-size: 14.5px;
        font-weight: 800;
        color: var(--slate-900);
        line-height: 1.45;
        margin-bottom: 8px;
    }

    .card-desc {
        font-size: 12px;
        color: var(--slate-600);
        line-height: 1.55;
        margin-bottom: 12px;
    }

    .role-chip {
        display: flex;
        align-items: center;
        padding: 9px 12px;
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        border-radius: 10px;
        font-size: 12px;
        font-weight: 700;
        color: #1d4ed8;
    }

    /* ROUTING TREE */
    .routing-tree {
        margin-top: 12px;
        padding: 12px;
        background: linear-gradient(135deg, #faf5ff, #fdf2f8);
        border: 1px solid #f3e8ff;
        border-radius: 12px;
    }

    .routing-tree-header {
        font-size: 9px;
        font-weight: 800;
        letter-spacing: 0.08em;
        color: #9333ea;
        margin-bottom: 10px;
    }

    .tree-node {
        position: relative;
        display: flex;
        align-items: flex-start;
        padding-left: 18px;
        padding-bottom: 10px;
    }
    .tree-node:last-child { padding-bottom: 0; }

    .tree-line {
        position: absolute;
        left: 3.5px;
        top: 10px;
        bottom: -4px;
        width: 2px;
        background: #d8b4fe;
    }

    .tree-dot {
        position: absolute;
        left: 0;
        top: 4px;
        width: 9px;
        height: 9px;
        border-radius: 50%;
    }
    .dot-purple { background: #9333ea; box-shadow: 0 0 0 3px rgba(147, 51, 234, 0.2); }
    .dot-emerald { background: #10b981; box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2); }

    .tree-info {
        display: flex;
        flex-direction: column;
        line-height: 1.25;
    }

    .tree-role { font-size: 10px; color: #7e22ce; font-weight: 600; }
    .tree-name { font-size: 12px; color: var(--slate-900); font-weight: 700; }

    /* ARTIST NOTES & DURATION */
    .colorful-note-box {
        margin-top: 12px;
        padding: 10px 12px;
        background: linear-gradient(135deg, #ecfeff, #f0f9ff);
        border-radius: 10px;
        border-left: 3px solid #06b6d4;
    }

    .note-header {
        font-size: 10px;
        font-weight: 800;
        color: #0e7490;
        margin-bottom: 3px;
    }

    .colorful-note-box p {
        margin: 0;
        font-size: 12px;
        font-style: italic;
        color: var(--slate-600);
        line-height: 1.45;
    }

    .duration-pill {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 12px;
        padding: 7px 11px;
        background: #f8fafc;
        border: 1px solid var(--slate-200);
        border-radius: 9px;
        font-size: 11px;
        color: var(--slate-600);
    }

    .duration-pill strong {
        color: #4f46e5;
        font-family: ui-monospace, SFMono-Regular, monospace;
    }

    /* CARD FOOTERS */
    .card-actor-footer {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 11px 16px;
        background: rgba(248, 250, 252, 0.85);
        border-top: 1px solid var(--slate-100);
    }

    .avatar-initials {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        font-weight: 800;
        flex-shrink: 0;
    }

    .actor-meta {
        display: flex;
        flex-direction: column;
        min-width: 0;
    }

    .actor-name {
        font-size: 12px;
        color: var(--slate-900);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .actor-role {
        font-size: 10px;
        color: var(--slate-400);
        font-weight: 600;
    }

    .card-time-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 16px;
        background: #ffffff;
        border-top: 1px solid var(--slate-100);
        font-size: 10px;
        color: var(--slate-400);
    }

    .rel-time {
        font-weight: 700;
        color: #6366f1;
    }

    /* NAV BUTTONS */
    .canvas-nav-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        z-index: 20;
        width: 44px;
        height: 44px;
        border-radius: 50%;
        border: none;
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
        color: #ffffff;
        box-shadow: 0 8px 20px rgba(99, 102, 241, 0.35);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .canvas-nav-btn:hover {
        transform: translateY(-50%) scale(1.1);
        box-shadow: 0 10px 25px rgba(124, 58, 237, 0.45);
    }

    .nav-left { left: 16px; }
    .nav-right { right: 16px; }

    /* EMPTY STATE */
    .modern-empty-state {
        padding: 70px 24px;
        text-align: center;
        background: linear-gradient(135deg, #ffffff, #f8faff);
        border: 2px dashed #c7d2fe;
        border-radius: 20px;
    }

    .empty-icon-ring {
        width: 68px;
        height: 68px;
        margin: 0 auto;
        border-radius: 50%;
        background: linear-gradient(135deg, #e0e7ff, #fae8ff);
        color: #6366f1;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 26px;
    }
</style>
@stop

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const workflow = document.getElementById('workflowScroll');
    const leftBtn = document.getElementById('scrollWorkflowLeft');
    const rightBtn = document.getElementById('scrollWorkflowRight');
    const fadeLeft = document.getElementById('fadeLeft');
    const fadeRight = document.getElementById('fadeRight');

    // 1. HORIZONTAL SCROLL & EDGE FADES
    if (workflow && leftBtn && rightBtn) {
        leftBtn.addEventListener('click', () => workflow.scrollBy({ left: -360, behavior: 'smooth' }));
        rightBtn.addEventListener('click', () => workflow.scrollBy({ left: 360, behavior: 'smooth' }));

        function updateScrollUI() {
            const maxScroll = workflow.scrollWidth - workflow.clientWidth;
            const atStart = workflow.scrollLeft <= 10;
            const atEnd = workflow.scrollLeft >= maxScroll - 10;

            if (maxScroll <= 10) {
                leftBtn.style.display = 'none';
                rightBtn.style.display = 'none';
                fadeLeft.style.opacity = '0';
                fadeRight.style.opacity = '0';
                return;
            }

            leftBtn.style.display = 'flex';
            rightBtn.style.display = 'flex';
            leftBtn.style.opacity = atStart ? '0' : '1';
            leftBtn.style.pointerEvents = atStart ? 'none' : 'auto';
            fadeLeft.style.opacity = atStart ? '0' : '1';

            rightBtn.style.opacity = atEnd ? '0' : '1';
            rightBtn.style.pointerEvents = atEnd ? 'none' : 'auto';
            fadeRight.style.opacity = atEnd ? '0' : '1';
        }

        workflow.addEventListener('scroll', updateScrollUI);
        window.addEventListener('resize', updateScrollUI);
        updateScrollUI();

        // 2. CLICK-AND-DRAG PANNING
        let isDown = false;
        let startX;
        let scrollLeft;

        workflow.addEventListener('mousedown', (e) => {
            if (e.target.closest('button')) return;
            isDown = true;
            startX = e.pageX - workflow.offsetLeft;
            scrollLeft = workflow.scrollLeft;
        });
        workflow.addEventListener('mouseleave', () => { isDown = false; });
        workflow.addEventListener('mouseup', () => { isDown = false; });
        workflow.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX - workflow.offsetLeft;
            const walk = (x - startX) * 1.5;
            workflow.scrollLeft = scrollLeft - walk;
        });
    }

    // 3. INTERACTIVE COLORFUL LEGEND FILTERING
    const filterButtons = document.querySelectorAll('#workflowLegend .filter-chip');
    const activityStages = document.querySelectorAll('.activity-item');

    filterButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            filterButtons.forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            const filter = this.getAttribute('data-filter');

            activityStages.forEach(stage => {
                if (filter === 'all' || stage.getAttribute('data-event-type') === filter) {
                    stage.classList.remove('dimmed');
                } else {
                    stage.classList.add('dimmed');
                }
            });
        });
    });

    // 4. LIVE CLOCK
    const timeEl = document.getElementById('live-time');
    const dateEl = document.getElementById('live-date');

    function updateClock() {
        if (!timeEl || !dateEl) return;
        const now = new Date();
        timeEl.textContent = now.toLocaleTimeString('en-US', {
            hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true
        });
        dateEl.textContent = now.toLocaleDateString('en-US', {
            weekday: 'short', month: 'short', day: 'numeric', year: 'numeric'
        });
    }

    updateClock();
    setInterval(updateClock, 1000);
});
</script>
@stop