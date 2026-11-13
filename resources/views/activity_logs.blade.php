@extends('adminlte::page')

@section('title', 'Master Activity Logs')

@section('plugins.Sweetalert2', true)
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)

@section('content_header')
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row align-items-center justify-content-between">
                    <h5><i class="fas fa-list-alt mr-1 text-primary"></i> Master Activity Logs</h5>
                </div>
            </div>
        </section>
    </div>
</div>

<div class="row mr-4">
    <div class="col-12 mx-auto">
        <!-- Master Activity Logs Table Panel -->
        <div class="card card-outline card-primary shadow">
            <div class="card-header d-flex align-items-center justify-content-between flex-wrap">
                <h3 class="card-title my-1">
                    <i class="fas fa-history mr-1 text-primary"></i> Action Logs
                </h3>
                
                <!-- Integrated Search Form Box -->
                <div class="card-tools my-1">
                    <form action="{{ url()->current() }}" method="GET" class="input-group input-group-sm" style="width: 250px;">
                        <input type="text" name="search" class="form-control float-right" placeholder="Search logs, item code, names..." value="{{ $search ?? '' }}">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-default text-primary">
                                <i class="fas fa-search"></i>
                            </button>
                            @if(!empty($search))
                                <a href="{{ url()->current() }}" class="btn btn-default text-danger" title="Clear Search">
                                    <i class="fas fa-times"></i>
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped m-0">
                        <thead class="bg-light text-muted">
                            <tr>
                                <th style="width: 15%;">Timestamp</th>
                                <th style="width: 12%;">Log Type</th>
                                <th style="width: 13%;">Item Code</th>
                                <th style="width: 15%;">Triggered By</th>
                                <th style="width: 45%;">Log Activity Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse(($activityLogs ?? []) as $log)
                                <tr>
                                    <!-- Timestamp Column -->
                                    <td class="align-middle text-sm">
                                        <div><strong>{{ $log->created_at->format('M d, Y') }}</strong></div>
                                        <div class="text-xs text-muted">{{ $log->created_at->format('h:i A') }}</div>
                                        <small class="text-xs text-primary font-italic">({{ $log->created_at->diffForHumans() }})</small>
                                    </td>

                                    <!-- Log Type Column -->
                                    <td class="align-middle">
                                        @if($log->log_type === 'action_taken')
                                            <span class="badge badge-purple px-2 py-1"><i class="fas fa-palette mr-1 text-xs"></i>Action</span>
                                        @else
                                            <span class="badge badge-teal px-2 py-1"><i class="fas fa-cog mr-1 text-xs"></i>History</span>
                                        @endif
                                    </td>
                                    
                                    <!-- Item Code Identifier Column -->
                                    <td class="align-middle">
                                        <span class="badge badge-info p-2 font-weight-bold">
                                            <i class="fas fa-barcode mr-1"></i>{{ $log->loggable->item_code ?? 'N/A' }}
                                        </span>
                                    </td>
                                    
                                    <!-- Actor Column -->
                                    <td class="align-middle text-sm font-weight-bold text-secondary">
                                        <i class="fas fa-user-circle mr-1 text-muted"></i>{{ $log->user->name ?? 'System Process' }}
                                    </td>
                                    
                                    <!-- Log Activity Description -->
                                    <td class="align-middle text-sm">
                                        <div class="font-weight-normal mb-1">
                                            @if($log->event === 'timer_started')
                                                <span class="badge badge-success mr-1 text-xs">Started</span>
                                            @elseif($log->event === 'timer_paused')
                                                <span class="badge badge-warning mr-1 text-xs">Paused</span>
                                            @elseif($log->event === 'sent_back_to_ao')
                                                <span class="badge badge-orange text-white mr-1 text-xs">To AO</span>
                                            @endif
                                            
                                            {{ $log->description }}
                                        </div>
                                        
                                        <!-- CASE A: Background System History Metadata Diffs -->
                                        @if($log->log_type === 'history' && isset($log->properties['old']) && is_array($log->properties['new']))
                                            <a href="#details-{{ $log->id }}" class="text-xs text-teal font-weight-bold d-inline-block mt-1" data-toggle="collapse" aria-expanded="false">
                                                <i class="fas fa-eye mr-1"></i> View Changes
                                            </a>
                                            
                                            <div id="details-{{ $log->id }}" class="collapse mt-2">
                                                <div class="p-2 bg-light rounded border text-xs" style="max-height: 150px; overflow-y: auto;">
                                                    @foreach($log->properties['new'] as $field => $newValue)
                                                        <div class="mb-1 pl-1 border-bottom pb-1 last-border-none" style="border-color: #e9ecef !important;">
                                                            <span class="badge badge-dark text-xs uppercase" style="font-size: 10px;">{{ str_replace('_', ' ', $field) }}</span>: 
                                                            changed from <del class="text-danger font-weight-bold px-1 rounded bg-danger-light" style="text-decoration: line-through;">{{ $log->properties['old'][$field] ?? 'Blank' }}</del> 
                                                            to <ins class="text-success font-weight-bold px-1 rounded bg-success-light text-decoration-none">{{ $newValue }}</ins>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif

                                        <!-- CASE B: Human Artist Remarks & Duration Notes -->
                                        @if($log->log_type === 'action_taken' && (isset($log->properties['artist_notes']) || isset($log->properties['time_spent']) || isset($log->properties['artist_name'])))
                                            <a href="#details-{{ $log->id }}" class="text-xs text-purple font-weight-bold d-inline-block mt-1" data-toggle="collapse" aria-expanded="false">
                                                <i class="fas fa-envelope-open-text mr-1"></i> View Work Notes
                                            </a>
                                            
                                            <div id="details-{{ $log->id }}" class="collapse mt-2">
                                                <div class="p-2 bg-light rounded border text-xs border-left border-purple" style="border-left-width: 4px !important;">
                                                    @if(isset($log->properties['artist_notes']))
                                                        <div class="mb-1">
                                                            <strong>Artist Remarks:</strong> <span class="text-dark font-italic">"{{ $log->properties['artist_notes'] }}"</span>
                                                        </div>
                                                    @endif

                                                    @if(isset($log->properties['time_spent']))
                                                        <div class="mt-1 font-weight-bold text-purple">
                                                            <i class="fas fa-stopwatch mr-1"></i> Session Duration: {{ $log->properties['time_spent'] }}
                                                        </div>
                                                    @endif

Use code with caution.
@if($log->event === 'artist_assigned' && isset($log->properties['artist_name']))

Assigned Specialist: {{ $log->properties['artist_name'] }}

@endif


@endif


@empty
@endforelse


@if($activityLogs instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator)

{{ $activityLogs->links('pagination::bootstrap-4') }}

@endif




@endsection
    
@section('js')
<script>
    // Live Clock Widget Engine
    $(function() {
        const $timeEl = $('#live-time');
        const $dateEl = $('#live-date');

        function updateClock() {
            const now = new Date();
            $timeEl.text(now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true }));
            $dateEl.text(now.toLocaleDateString('en-US', { weekday: 'long', month: 'long', day: 'numeric', year: 'numeric' }));
        }

        if($timeEl.length && $dateEl.length) {
            updateClock();
            setInterval(updateClock, 1000);
        }
    });
</script>

<script>
    // Continuous Notification Polling Logic
    function fetchNotifications() {
        $.ajax({
            url: "{{ route('Notifications') }}",
            type: "GET",
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            success: function (response) {
                if (response.html) {
                    $('#activityFeedList').html(response.html);
                }
                if (response.unreadCount !== undefined) {
                    const badge = $('#fbNotificationBadge');
                    badge.text(response.unreadCount > 99 ? '99+' : response.unreadCount);
                    response.unreadCount === 0 ? badge.addClass('d-none') : badge.removeClass('d-none');
                }
            },
            error: function (xhr) {
                console.error('AJAX Polling Failure:', xhr);
            }
        });
    }

    $(document).ready(function () {
        fetchNotifications();
    });
</script>
@endsection
