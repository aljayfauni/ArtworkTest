{{-- @php
    // Determine unread count based on status or opened state
    $logsCollection = $logs ?? collect();
    $unreadCount = $logsCollection->filter(function($log) {
        return empty($log->file) || is_null($log->file->is_opened);
    })->count();
@endphp --}}

@php
    $logsCollection = $logs ?? collect();
    $unreadCount = $logsCollection->filter(function($log) {
        // Check log status first, fall back to log->file status
        $status = $log->status ?? $log->file?->status;

        // Unread if status is empty, null, or anything other than 'Opened'
        // return strtolower((string)$status) !== 'opened';
    })->count();
@endphp

<!-- Bell Trigger Button -->
<button type="button" 
        class="btn btn-light rounded-circle d-flex align-items-center justify-content-center border-0 p-0 shadow-sm position-relative" 
        id="fbNotificationModalTrigger" 
        data-toggle="modal" 
        data-target="#fbNotificationModal" 
        style="width: 40px; height: 40px; background-color: #e4e6eb; transition: background-color 0.2s;">
    <i class="fas fa-bell text-dark" style="font-size: 1.1rem;"></i>
    
    <!-- Unread Badge Counter -->
    <span class="badge badge-danger rounded-pill position-absolute unread-badge {{ $unreadCount === 0 ? 'd-none' : '' }}" 
          id="fbNotificationBadge"
          style="top: -2px; right: -2px; font-size: 0.7rem; padding: 0.25em 0.45em; border: 2px solid #ffffff;">
        {{ $unreadCount > 99 ? '99+' : $unreadCount }}
    </span>
</button>

<!-- Modal (data-backdrop="false" disables the dark overlay) -->
<div class="modal fade fb-notification-modal" id="fbNotificationModal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="false" style="position: absolute !important; top: 60px !important; right: 15px !important; left: auto !important; bottom: auto !important; width: 500px !important; max-width: 92vw !important; z-index: 1060 !important; pointer-events: none; height: 680px;">
    <div class="modal-dialog m-0 p-0" role="document" style="width: 100% !important; max-width: 100% !important;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 12px; overflow: hidden; pointer-events: auto;">
            
            <!-- Modal Header -->
            <div class="modal-header align-items-center border-bottom px-3 py-2 bg-white">
                <h6 class="modal-title font-weight-bold text-dark mb-0" style="font-size: 1rem;">
                    Notifications
                </h6>
                <div class="d-flex align-items-center">
                    <button type="button" class="btn btn-sm btn-link text-primary p-0 font-weight-bold text-decoration-none mr-3" id="markAllReadBtn" style="font-size: 0.8rem;">
                        {{-- Mark all as read --}}
                    </button>
                    <button type="button" class="close text-secondary p-0 m-0" data-dismiss="modal" aria-label="Close" style="font-size: 1.25rem;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>

            <!-- Scrollable Body -->
            <div class="modal-body p-0 bg-white" id="activityFeedList" style="max-height: 420px; overflow-y: auto;">
                @if($logsCollection->isNotEmpty())
                    <div class="d-flex flex-column">
                        @foreach($logsCollection as $log)
                            @php
                                $statusLower = strtolower($log->status ?? $log->file?->status ?? '');
                                $hasBeenOpened = $statusLower === 'opened';
                                $rowBgClass = $hasBeenOpened ? 'bg-white' : 'unread-item';
                                $rowBgStyle = $hasBeenOpened ? 'background-color: #ffffff;' : 'background-color: rgba(23, 162, 184, 0.05);';

                                [$bgToken, $textClass, $iconClass] = match($statusLower) {
                                    'opened', 'read' => ['background-color: rgba(23, 162, 184, 0.1);', 'text-info', 'fas fa-envelope-open'],
                                    'sent'           => ['background-color: rgba(40, 167, 69, 0.1);', 'text-success', 'fas fa-paper-plane'],
                                    'note', 'message' => ['background-color: rgba(255, 193, 7, 0.12);', 'text-warning', 'fas fa-comment-alt'],
                                    default          => ['background-color: rgba(108, 117, 125, 0.1);', 'text-secondary', 'fas fa-bell']
                                };
                            @endphp

                            <div class="notification-item {{ $rowBgClass }} px-3 py-3 position-relative" style="border-bottom: 1px solid #f0f2f5 !important; {{ $rowBgStyle }} transition: background-color 0.2s ease;">
                                   
                                 
                                <div class="d-flex align-items-start">
                                    <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0 mt-1 mr-3" 
                                         style="width: 38px; height: 38px; {{ $bgToken }}">
                                        <i class="{{ $iconClass }} {{ $textClass }}" style="font-size: 0.9rem;"></i>
                                    </div>

                                    <div class="w-100">
                                        <div class="d-flex justify-content-between align-items-start mb-1">
                                            <span class="text-dark font-weight-normal" style="font-size: 0.88rem; line-height: 1.35;">
                                                {{ $log->log ?? 'Activity Recorded' }}
                                            </span>
                                            <span class="text-muted flex-shrink-0 ml-2 small" style="font-size: 0.72rem;">
                                                {{ $log->created_at ? $log->created_at->diffForHumans(null, true) : 'N/A' }}
                                            </span>
                                        </div>

                                        @if($log->file)
                                         <div class="mb-1 mt-1 text-secondary" style="font-size: 0.82rem; line-height: 1.35;">
                                        @if($log->file->item_code ?? "No Item Code") 
                                                    <span class="text-muted">Item Code:</span> <span class="text-dark font-weight-bold">{{ $log->file->item_code ?? "No Item Code" }}</span>
                                        @endif
                                         </div>
                                            <div class="mb-1 mt-1 text-secondary" style="font-size: 0.82rem; line-height: 1.35;">
                                                @if($log->file->to_email) 
                                                    <span class="text-muted">To:</span> <span class="text-dark font-weight-bold">{{ $log->file->to_email }}</span>
                                                @endif
            
                                                @if($log->file->file_info) 
                                                    <div class="text-muted font-italic mt-1">
                                                        <a href="{{ $log->file->file_link }}" target="_blank" class="text-primary font-weight-bold d-inline-flex align-items-center text-decoration-none">
                                                            <i class="fas fa-link mr-1" style="font-size: 0.65rem;"></i>{{ 'File Info: '.$log->file->file_info }}
                                                        </a>
                                                    </div>
                                                @elseif($log->file->file_link) 
                                                    <div class="mt-1">
                                                        <a href="{{ $log->file->file_link }}" target="_blank" class="text-primary font-weight-bold d-inline-flex align-items-center text-decoration-none">
                                                            <i class="fas fa-link mr-1" style="font-size: 0.65rem;"></i>{{ $log->file->ao_name ?? 'View File Attachment' }}
                                                        </a>
                                                    </div>
                                                @endif
                                                
                                                @if($log->file->from_email) 
                                                    <div class="text-muted small mt-1">(from {{ $log->file->from_email }})</div> 
                                                @endif
                                            </div>
                                        @endif

                                        <div class="d-flex align-items-center mt-1" style="font-size: 0.7rem;">
                                            <span class="text-uppercase font-weight-bold {{ $textClass }}">
                                                {{ !empty($log->status) ? $log->status : ($log->file->status ?? 'File Link Sent') }}
                                            </span>
                                           
                                            @if($hasBeenOpened)
                                                <span class="text-muted mx-2" style="opacity: 0.4;">•</span>
                                                <span class="badge badge-light border text-info px-2 py-1" title="Opened time: {{ $log->file->is_opened }}">
                                                    <i class="fas fa-eye mr-1" style="font-size: 0.6rem;"></i> Opened
                                                </span>
                                            @endif

                                            @if($log->user)
                                                <span class="text-muted mx-2" style="opacity: 0.4;">•</span>
                                                <span class="text-muted">
                                                    <i class="far fa-user mr-1" style="font-size: 0.65rem;"></i> {{ $log->user->name ?? $log->user->email }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5 my-3">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-2" style="width: 52px; height: 52px; background-color: rgba(108, 117, 125, 0.08);">
                            <i class="fas fa-bell-slash text-muted" style="font-size: 1.2rem; opacity: 0.6;"></i>
                        </div>
                        <p class="mb-0 text-muted font-weight-normal" style="font-size: 0.88rem;">All quiet for now</p>
                    </div>
                @endif
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer px-3 py-2 bg-light border-top d-flex justify-content-end">
                <button type="button" class="btn btn-sm btn-secondary font-weight-bold" data-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>

{{-- <div class="card card-widget shadow-sm mb-4" style="border-radius: 16px; border: 1px solid #e9ecef; overflow: hidden;">
    <!-- Modern Borderless Header -->
    <div class="card-header bg-white border-0 pt-4 pb-2 px-4 d-flex justify-content-between align-items-center">
        <div class="user-block">
            <h3 class="card-title font-weight-bold text-dark m-0" style="font-size: 1.05rem; letter-spacing: -0.3px;">
                Notifications & Activity
            </h3>
        </div>
        <div class="card-tools">
            <span class="badge badge-pill badge-primary px-2.5 py-1 font-weight-bold" style="font-size: 0.75rem; background-color: rgba(0, 123, 255, 0.12); color: #007bff;">
                {{ $unreadCount ?? $logs->count() }} New
            </span>
        </div>
    </div>

    <!-- Scrollable Notification Stream Container -->
    <div class="card-body p-0" id="activityFeedList" style="max-height: 420px; overflow-y: auto;">
        @if($logs->isNotEmpty())
            <div class="d-flex flex-column">
                @foreach($logs as $log)
                    @php
                        $statusLower = strtolower($log->status ?? '');
                        
                        // Condition check: Verifies if the file relationship exists and if 'is_opened' contains a timestamp (is not null)
                        $hasBeenOpened = $log->file && !is_null($log->file->is_opened);
                        
                        // 1. Dynamic background handling based on your actual database column layout
                        // Apply soft blue-info background highlight ONLY if the file has an 'is_opened' timestamp value.
                        // Default white fallback is assigned if it is NULL or not yet opened.
                        $rowBgStyle = $hasBeenOpened ? 'background-color: rgba(23, 162, 184, 0.05);' : 'background-color: #ffffff;';

                        // 2. Map structural color tokens for contextual badges and icon frames
                        [$bgToken, $textClass, $iconClass] = match($statusLower) {
                            'opened', 'read' => ['background-color: rgba(23, 162, 184, 0.1);', 'text-info', 'fas fa-envelope-open'],
                            'sent'           => ['background-color: rgba(40, 167, 69, 0.1);', 'text-success', 'fas fa-paper-plane'],
                            'note', 'message'=> ['background-color: rgba(255, 193, 7, 0.12);', 'text-warning', 'fas fa-comment-alt'],
                            default          => ['background-color: rgba(108, 117, 125, 0.1);', 'text-secondary', 'fas fa-bell']
                        };
                    @endphp

                    <!-- Modern Row with a slightly more visible divider line and conditional file-status style -->
                    <div class="px-4 py-3" style="border-bottom: 1px solid #dee2e6 !important; {{ $rowBgStyle }} transition: background-color 0.2s ease;">
                        <div class="d-flex align-items-start">
                            
                            <!-- Premium Soft Circle Icon Frame -->
                            <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0 mt-1 mr-3" 
                                 style="width: 36px; height: 36px; {{ $bgToken }}">
                                <i class="{{ $iconClass }} {{ $textClass }}" style="font-size: 0.85rem;"></i>
                            </div>

                            <!-- Notification Text Architecture -->
                            <div class="w-100">
                                <!-- Top Segment: Action Title Headline & Realtime Counter -->
                                <div class="d-flex justify-content-between align-items-start mb-1">
                                    <span class="text-dark font-weight-normal" style="font-size: 0.88rem; line-height: 1.45; letter-spacing: -0.1px;">
                                        {{ $log->log ?? 'Activity Recorded' }}
                                    </span>
                                    <span class="text-muted flex-shrink-0 ml-3 small mt-0.5" style="font-size: 0.72rem; letter-spacing: -0.1px;">
                                        {{ $log->created_at ? $log->created_at->diffForHumans() : 'N/A' }}
                                    </span>
                                </div>

                                <!-- Middle Segment: Metadata Attachment Detail Layout -->
                                @if($log->file)
                                    <div class="mb-2 mt-1 text-secondary" style="font-size: 0.82rem; line-height: 1.45;">
                                        @if($log->file->to_email) 
                                            <span class="text-muted">To:</span> <span class="text-dark font-weight-600">{{ $log->file->to_email }}</span>
                                        @endif
                                        
                                        @if($log->file->file_info) 
                                            <div class="text-muted font-italic mt-0.5">
                                                <a href="{{ $log->file->file_link }}" target="_blank" class="text-primary font-weight-bold d-inline-flex align-items-center text-decoration-none">
                                                    <i class="fas fa-link mr-1" style="font-size: 0.7rem;"></i>{{ 'File Info: '.$log->file->file_info }}
                                                </a>
                                            </div>
                                        @elseif($log->file->file_link) 
                                            <div class="mt-1.5">
                                                <a href="{{ $log->file->file_link }}" target="_blank" class="text-primary font-weight-bold d-inline-flex align-items-center text-decoration-none">
                                                    <i class="fas fa-link mr-1" style="font-size: 0.7rem;"></i>{{ $log->file->ao_name ?? 'View File Attachment' }}
                                                </a>
                                            </div>
                                        @endif
                                        
                                        @if($log->file->from_email) 
                                            <div class="text-muted small mt-0.5">(from {{ $log->file->from_email }})</div> 
                                        @endif
                                    </div>
                                @endif

                                <!-- Bottom Segment: Micro Utility Tags -->
                                <div class="d-flex align-items-center mt-2" style="font-size: 0.7rem;">
                                    <span class="text-uppercase tracking-wider font-weight-bold {{ $textClass }}" style="letter-spacing: 0.4px;">
                                        {{-- {{ empty($log->status) ? 'File_sent' : $log->file->status}} --}}
                                        {{-- {{ !empty($log->status) ? $log->status : ($log->file->status ?? 'File Link is Sent but No Status yes') }}

                                    </span>
                                    
                                    <!-- Dynamic Badge: Visually surfaces when the link was opened based on its field timestamp -->
                                    @if($hasBeenOpened)
                                        <span class="text-muted mx-2" style="opacity: 0.4;">•</span>
                                        <span class="badge badge-light border text-info px-2 py-0.5" title="Opened time: {{ $log->file->is_opened }}">
                                            <i class="fas fa-eye mr-1" style="font-size: 0.6rem;"></i> Opened
                                        </span>
                                    @endif

                                    @if($log->user)
                                        <span class="text-muted mx-2" style="opacity: 0.4;">•</span>
                                        <span class="text-muted">
                                            <i class="far fa-user mr-1" style="font-size: 0.65rem;"></i> {{ $log->user->name ?? $log->user->email }}
                                        </span>
                                    @endif
                                </div>

                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else --}}
            <!-- Simplified Premium Empty State Panel -->
            {{-- <div class="text-center py-5 my-3">
                <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" style="width: 54px; height: 54px; background-color: rgba(108, 117, 125, 0.08);">
                    <i class="fas fa-bell-slash text-muted" style="font-size: 1.15rem; opacity: 0.6;"></i>
                </div>
                <p class="mb-0 text-muted font-weight-normal" style="font-size: 0.85rem;">All quiet for now</p>
            </div>
        @endif
    </div> --}}

    <!-- Footer Action Link -->
    {{-- <div class="card-footer bg-white border-0 pb-4 pt-2 text-center">
        <button type="button" class="btn btn-sm btn-link text-muted font-weight-bold text-decoration-none" onclick="clearFeed()" style="font-size: 0.8rem; letter-spacing: -0.1px;">
            <i class="fas fa-check-double mr-1.5" style="font-size: 0.75rem;"></i> Mark all as read
        </button>
    </div>
</div> --}} 