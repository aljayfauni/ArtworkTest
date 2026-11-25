@extends('adminlte::page')

@section('title', 'History Logs')

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
                    <h5><i class="fas fa-history mr-1 text-teal"></i> History Logs</h5>
                </div>
            </div>
        </section>
    </div>
</div>

<div class="row mr-4">
    <div class="col-12 mx-auto">
        <!-- Master History Logs Table Panel -->
        <div class="card card-outline card-teal shadow">
            <div class="card-header d-flex align-items-center justify-content-between flex-wrap">
                <h3 class="card-title my-1">
                    <i class="fas fa-history mr-1 text-teal"></i> History logs
                </h3>
                
                <!-- 💡 Integrated Search Form Wrapper Box -->
                <div class="card-tools my-1">
                    <form action="{{ url()->current() }}" method="GET" class="input-group input-group-sm" style="width: 250px;">
                        <input type="text" name="search" class="form-control float-right" placeholder="Search Item, Artist, or Log..." value="{{ $search ?? '' }}">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-default text-teal">
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
                                <th style="width: 20%;">Timestamp</th>
                                <th style="width: 15%;">Item Code</th>
                                <th style="width: 15%;">Reference Number</th>
                                <th style="width: 20%;">Triggered By</th>
                                <th style="width: 45%;">Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse(($historyLogs ?? []) as $log)
                                <tr>
                                    <!-- Timestamp Column -->
                                    <td class="align-middle text-sm">
                                        <div><strong>{{ $log->created_at->format('M d, Y') }}</strong></div>
                                        <div class="text-xs text-muted">{{ $log->created_at->format('h:i A') }}</div>
                                        <small class="text-xs text-teal font-italic">({{ $log->created_at->diffForHumans() }})</small>
                                    </td>
                                    
                                    <!-- Item Code Identifier Column -->
                                    <td class="align-middle">
                                        <span class="badge badge-info p-2 font-weight-bold">
                                            <i class="fas fa-barcode mr-1"></i>{{ $log->loggable->item_code ?? 'N/A' }}
                                        </span>
                                    </td>

                                        <!-- rfa reference -->
                                    <td class="align-middle">
                                        <span class="badge  p-2 font-weight-bold">
                                            <i class="fas fa- mr-1"></i>{{ $log->rfa_reference ?? 'N/A' }}
                                        </span>
                                    </td>
                                    
                                    <!-- Actor Column -->
                                    <td class="align-middle text-sm font-weight-bold text-secondary">
                                        <i class="fas fa-user-circle mr-1 text-muted"></i>{{ $log->user->name ?? 'System Process' }}
                                    </td>
                                    
                                    <!-- Action Description Column (with Hidden Expandable Parameter Data) -->
                                    <td class="align-middle text-sm">
                                        <div class="font-weight-normal mb-1">{{ $log->description }}</div>
                                        
                                        @if(isset($log->properties['old']) && is_array($log->properties['new']))
                                            <!-- Toggle Button for Hidden Details -->
                                            <a href="#details-{{ $log->id }}" class="text-xs text-teal font-weight-bold d-inline-block mt-1" data-toggle="collapse" aria-expanded="false">
                                                <i class="fas fa-eye mr-1"></i> View Changes
                                            </a>
                                            
                                            <!-- Hidden Collapsible Div Panel -->
                                            <div id="details-{{ $log->id }}" class="collapse mt-2">
                                                <div class="p-2 bg-light rounded border text-xs" style="max-height: 150px; overflow-y: auto;">
                                                    @foreach($log->properties['new'] as $field => $newValue)
                                                        <div class="mb-1 pl-1 border-bottom pb-1 last-border-none" style="border-color: #e9ecef !important;">
                                                            <span class="badge badge-dark text-xs uppercase" style="letter-spacing: 0.3px; font-size: 10px;">
                                                                {{ str_replace('_', ' ', $field) }}
                                                            </span>: 
                                                            changed from 
                                                            <del class="text-danger font-weight-bold px-1 rounded bg-danger-light" style="text-decoration: line-through;">{{ $log->properties['old'][$field] ?? 'Blank' }}</del> 
                                                            to 
                                                            <ins class="text-success font-weight-bold px-1 rounded bg-success-light text-decoration-none">{{ $newValue }}</ins>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted p-5">
                                        <i class="fas fa-search-minus fa-2x mb-2 text-gray"></i>
                                        <p class="m-0 font-weight-bold text-sm">
                                            @if(!empty($search))
                                                No historical changes or updates found matching "{{ $search }}".
                                            @else
                                                No historical changes or updates have been logged in the system yet.
                                            @endif
                                        </p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Links Footer Block -->
                @if($historyLogs instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator)
                    <div class="card-footer d-flex justify-content-center bg-white border-top">
                        {{ $historyLogs->links('pagination::bootstrap-4') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div> 
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
