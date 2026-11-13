@extends('adminlte::page')

@section('title', 'Project Tracking Status')

@section('plugins.Sweetalert2', true)
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><i class="fas fa-history mr-2 text-primary"></i>Artwork Production Timeline</h1>
            </div>
        </div>
    </div>
@stop

@section('content')
<div class="container-fluid">
    <!-- Header Specifications Meta Grid Card -->
    <div class="card card-outline card-primary shadow-sm mb-4">
        <div class="card-header">
            <h3 class="card-title font-weight-bold">
                <i class="fas fa-folder-open mr-1 text-primary"></i> 
                {{ $artwork->ao_name ?? 'Workflow Routing Progress' }}
            </h3>
            <div class="card-tools">
                <span class="badge badge-info p-2 font-weight-bold" style="font-size: 13px;">
                    <i class="fas fa-barcode mr-1"></i> Item Code: {{ $artwork->item_code }}
                </span>
            </div>
        </div>
        <div class="card-body bg-light py-2 px-3">
            <span class="text-muted text-sm font-weight-bold">
                <i class="fas fa-user-tie mr-1 text-muted"></i> Client Profile Reference: 
                <span class="text-dark">{{ $artwork->client_name ?? 'N/A' }}</span>
            </span>
        </div>
    </div>

    <!-- NATIVE ADMINLTE VERTICAL TIMELINE STACK -->
    <div class="row">
        <div class="col-md-12">
            <div class="timeline">
                
                @php $lastDate = null; @endphp
                @forelse(($activityLogs ?? []) as $log)
                    @php
                        // Automatically drop a native AdminLTE Date Label when day increments
                        $currentDate = $log->created_at->format('M d, Y');
                        
                        // Map dynamic bootstrap backgrounds based on logged events
                        $iconClass = 'fa-cog bg-secondary';
                        if ($log->event === 'timer_started' || $log->event === 'status_initialized') {
                            $iconClass = 'fa-play bg-success';
                        } elseif ($log->event === 'timer_paused') {
                            $iconClass = 'fa-pause bg-warning';
                        } elseif ($log->event === 'status_changed') {
                            $iconClass = 'fa-route bg-primary';
                        } elseif ($log->event === 'sent_back_to_ao') {
                            $iconClass = 'fa-arrow-left bg-orange text-white';
                        } elseif ($log->event === 'artist_assigned') {
                            $iconClass = 'fa-user-plus bg-purple text-white';
                        }

                        // Determine dynamic display formatting for user workflow roles
                        $roleName = 'System';
                        if ($log->user) {
                            if (isset($log->user->role_id)) {
                                $roleName = match((int)$log->user->role_id) {
                                    1 => 'AO',
                                
                                    2 => 'GD Supervisor',
                                    4 => 'Commercial Artist',
                                    default => 'Staff Specialist'
                                };
                            } elseif ($log->user->role) {
                                // Fallback option if your user model uses a direct named relationship link table
                                $roleName = $log->user->role->name ?? 'Staff';
                            }
                        }
                    @endphp

                    @if($lastDate !== $currentDate)
                        <!-- Native AdminLTE Date Grouping Label Element -->
                        <div class="time-label">
                            <span class="bg-blue font-weight-bold px-3 py-1 rounded shadow-sm text-sm" style="border-radius: 4px;">
                                {{ $currentDate }}
                            </span>
                        </div>
                        @php $lastDate = $currentDate; @endphp
                    @endif

                    <!-- Timeline Item Block Wrapper -->
                    <div>
                        <!-- Embedded Event Vectors Node Icon Circle Pin -->
                        <i class="fas {{ $iconClass }} shadow-sm"></i>
                        
                        <div class="timeline-item border shadow-2xs rounded mb-3">
                            <!-- Precise Timestamp Node Wrapper Badge Header Column -->
                            <span class="time text-muted text-xs font-weight-bold pt-2 pr-3">
                                <i class="far fa-clock text-xs mr-1"></i>
                                {{ $log->created_at->format('g:i A') }}
                                <small class="text-muted ml-1">({{ $log->created_at->diffForHumans() }})</small>
                            </span>

                            <!-- Header Context Description Element Label Line Layout -->
                            <h3 class="timeline-header font-weight-bold text-dark text-sm border-bottom-0 pb-1 pt-2 pl-3">
                                {{ $log->description }}
                            </h3>

                            <!-- Timeline Card Sub-Content Body Container Box Segment -->
                            <div class="timeline-body pt-1 pb-2 pl-3 pr-3 text-secondary text-sm">
                                
                                {{-- Render manually logged artwork design burst description commentary --}}
                                @if($log->log_type === 'action_taken' && isset($log->properties['artist_notes']))
                                    <div class="p-2 my-2 rounded bg-light border-left border-warning text-dark font-italic text-sm">
                                        "{{ $log->properties['artist_notes'] }}"
                                    </div>
                                @endif

                                {{-- Render active session duration tracking metric indicators if existing --}}
                                @if(isset($log->properties['time_spent']))
                                    <div class="mt-2 text-xs font-weight-bold text-teal">
                                        <i class="fas fa-stopwatch mr-1 text-warning"></i>
                                        Logged Production Duration: <span class="badge badge-pill badge-light border border-teal text-teal px-2 py-1">{{ $log->properties['time_spent'] }}</span>
                                    </div>
                                @endif

                                <!-- Footer Author Tracker Signature Stamp Info Block -->
                                <div class="mt-2 d-flex align-items-center text-xs text-muted">
                                    <span>
                                        <i class="far fa-id-badge mr-1 text-sm text-secondary"></i>
                                        Triggered by: <span class="badge badge-dark font-weight-normal px-2 mr-1">{{ $roleName }}</span> 
                                        <strong class="text-dark">{{ $log->user->name ?? 'System Process' }}</strong>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-muted p-5 bg-white rounded border shadow-2xs">
                        <i class="fas fa-history fa-2x mb-3 text-gray"></i>
                        <h6 class="font-weight-bold m-0 text-secondary">No step logs or progress history checkpoint actions recorded on this item code timeline.</h6>
                    </div>
                @endforelse

                <!-- Final Clock Indicator Pin Bottom Hub Component Stop -->
                <div>
                    <i class="fas fa-clock bg-gray shadow-sm"></i>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


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