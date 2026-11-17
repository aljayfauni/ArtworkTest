{{-- <!-- Notifications Dropdown Menu -->
<li class="nav-item dropdown" id="notification-dropdown-root">
    <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="false">
        <i class="far fa-bell"></i>
        <span id="badge-container">
            @if(auth()->user()->unreadNotifications->count() > 0)
                <span class="badge badge-danger navbar-badge" id="notification-badge">
                    {{ auth()->user()->unreadNotifications->count() }}
                </span>
            @endif
        </span>
    </a>
    
    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="left: inherit; right: 0px;">
        <span class="dropdown-item dropdown-header" id="notification-header">
            {{ auth()->user()->unreadNotifications->count() }} Notifications
        </span>
        <div class="dropdown-divider"></div>
        
        <!-- Live container swapped by JavaScript -->
        <div id="notification-list" style="max-height: 300px; overflow-y: auto;">
            @forelse(auth()->user()->notifications->take(10) as $notification)
                @php
                    $url = isset($notification->data['url']) && !is_array($notification->data['url']) ? $notification->data['url'] : '#';
                    $icon = isset($notification->data['icon']) && !is_array($notification->data['icon']) ? $notification->data['icon'] : 'fas fa-envelope';
                     $item_code = isset($notification->data['Item_code']) && !is_array($notification->data['Item_code']) ? $notification->data['Item_code'] : '';
                    $title = isset($notification->data['title']) && !is_array($notification->data['title']) ? $notification->data['title'] : 'Update';
                    $message = isset($notification->data['message']) && !is_array($notification->data['message']) ? $notification->data['message'] : '';
                    $isUnread = $notification->read_at === null;
                @endphp

                <a href="{{ $url }}" 
                   class="dropdown-item d-flex align-items-center mark-as-read" 
                   data-id="{{ $notification->id }}"
                   data-unread="{{ $isUnread ? 'true' : 'false' }}"
                   style="background-color: {{ $isUnread ? '#f4f6f9' : '#ffffff' }}; transition: background 0.3s;"
                   target="_blank" rel="noopener noreferrer">
                    
                    <i class="{{ $icon }} mr-3 text-sm"></i> 
                    
                    <div class="text-truncate mr-2" style="max-width: 180px;">
                        <span class="d-block text-sm text-wrap notification-title {{ $isUnread ? 'font-weight-bold' : 'text-muted' }}">
                            {{ $title }}
                        </span>
                        <small class="text-muted d-block text-truncate">{{ $message }}</small>
                    </div>
                    
                    <span class="ml-auto text-muted text-xs whitespace-nowrap">
                        {{ $notification->created_at->diffForHumans(null, true) }}
                    </span>
                </a>
                <div class="dropdown-divider"></div>
            @empty
                <div class="dropdown-item text-center text-muted py-3">
                    <i class="fas fa-check-circle text-success mb-2 d-block"></i>
                    No notifications available
                </div>
            @endforelse
        </div>

        <div class="dropdown-divider"></div>
        <a href="#" class="dropdown-item dropdown-footer text-center">Clear All Notification Badge</a>
    </div>
</li>

@push('js')
<script>
    $(document).ready(function() {
        // 1. MARK AS READ (Facebook Background Color Swap)
        $(document).off('click', '.mark-as-read').on('click', '.mark-as-read', function(e) {
            let notificationId = $(this).data('id');
            let element = $(this);
            let isUnread = element.data('unread') === true || element.data('unread') === "true";

            if (!isUnread) return;

            $.ajax({
                url: '/notifications/' + notificationId + '/read',
                type: 'POST',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function(response) {
                    if (response.success) {
                        element.css('background-color', '#ffffff');
                        element.find('.notification-title').removeClass('font-weight-bold').addClass('text-muted');
                        element.data('unread', false);

                        let badge = $('#notification-badge');
                        let header = $('#notification-header');
                        let currentCount = parseInt(badge.text().trim()) || 0;

                        if (currentCount > 1) {
                            badge.text(currentCount - 1);
                            header.text((currentCount - 1) + ' Notifications');
                        } else {
                            badge.remove();
                            header.text('0 Notifications');
                        }
                    }
                }
            });
        });

        // 2. AJAX LIVE BACKGROUND REFRESH
        function fetchLiveNotifications() {
            // Guard rule: Don't mess with layout list if user is explicitly reading the menu
            if ($('#notification-dropdown-root').hasClass('show')) {
                return; 
            }

            $.ajax({
                url: '/api/notifications/poll',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('#notification-header').text(data.unread_count + ' Notifications');
                    $('#notification-list').html(data.html);

                    if (data.unread_count > 0) {
                        if ($('#notification-badge').length === 0) {
                            $('#badge-container').html('<span class="badge badge-danger navbar-badge" id="notification-badge">' + data.unread_count + '</span>');
                        } else {
                            $('#notification-badge').text(data.unread_count);
                        }
                    } else {
                        $('#notification-badge').remove();
                    }
                }
            });
        }

        // Poll your database automatically every 10 seconds
        setInterval(fetchLiveNotifications, 10000); 
    });
</script>
@endpush --}}

<!-- Notifications Dropdown Menu -->
<li class="nav-item dropdown" id="notification-dropdown-root">
    <a class="nav-link position-relative" data-toggle="dropdown" href="#" aria-expanded="false" title="Notifications">
        <i class="far fa-bell fa-lg"></i>
        <span id="badge-container">
            @php $unreadCount = auth()->user()->unreadNotifications->count(); @endphp
            @if($unreadCount > 0)
                <span class="badge badge-danger navbar-badge" id="notification-badge">
                    {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                </span>
            @endif
        </span>
    </a>

    <div class="dropdown-menu dropdown-menu-right shadow-lg border-0 p-0 notif-dropdown" style="width: 380px; max-width: 92vw; border-radius: 10px; overflow: hidden; left: inherit; right: 0px;">
        <!-- Sticky Header -->
        <div class="d-flex justify-content-between align-items-center px-3 py-2.5 bg-white border-bottom">
            <div class="d-flex align-items-center">
                <span class="font-weight-bold text-dark" style="font-size: 0.95rem;">Notifications</span>
                <span class="badge badge-pill badge-primary ml-2" id="notification-header-pill">{{ $unreadCount }} New</span>
            </div>
            <small class="text-muted" style="font-size: 0.75rem;">Latest 10</small>
        </div>

        <!-- Live Container Swapped by JavaScript -->
        <div id="notification-list" style="max-height: 360px; overflow-y: auto;">
            @forelse(auth()->user()->notifications->take(10) as $notification)
                @php
                    $data     = $notification->data;
                    $url      = isset($data['url']) && !is_array($data['url']) && $data['url'] !== '' ? $data['url'] : '#';
                    $icon     = isset($data['icon']) && !is_array($data['icon']) ? $data['icon'] : 'fas fa-envelope text-secondary';
                    $itemCode = $data['item_code'] ?? $data['Item_code'] ?? '';
                    $itemCode = !is_array($itemCode) ? $itemCode : '';
                    $title    = isset($data['title']) && !is_array($data['title']) ? $data['title'] : 'Update';
                    $message  = isset($data['message']) && !is_array($data['message']) ? $data['message'] : '';
                    $isUnread = $notification->read_at === null;
                @endphp

                <a href="{{ $url }}"
                   class="dropdown-item notif-item d-flex align-items-start px-3 py-2.5 border-bottom mark-as-read {{ $isUnread ? 'notif-unread' : '' }}"
                   data-id="{{ $notification->id }}"
                   data-unread="{{ $isUnread ? 'true' : 'false' }}"
                   target="_blank" rel="noopener noreferrer">

                    <!-- Circular Icon Avatar -->
                    <div class="notif-icon-circle mr-3 mt-1 flex-shrink-0">
                        <i class="{{ $icon }}"></i>
                    </div>

                    <!-- Content Body -->
                    <div class="flex-grow-1 overflow-hidden">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="notif-title text-truncate {{ $isUnread ? 'font-weight-bold text-dark' : 'text-secondary' }}" style="font-size: 0.86rem;">
                                {{ $title }}
                            </span>
                            @if($itemCode !== '')
                                <span class="badge badge-light border text-monospace ml-2 flex-shrink-0" style="font-size: 0.72rem;">
                                    #{{ $itemCode }}
                                </span>
                            @endif
                        </div>

                        <p class="mb-1 text-muted text-wrap" style="font-size: 0.8rem; line-height: 1.35; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                            {{ $message }}
                        </p>

                        <small class="text-muted d-flex align-items-center" style="font-size: 0.72rem;">
                            <i class="far fa-clock mr-1"></i> {{ $notification->created_at->diffForHumans(null, true) }}
                        </small>
                    </div>

                    <!-- Unread Blue Dot -->
                    <div class="ml-2 mt-2 flex-shrink-0">
                        <span class="unread-dot {{ $isUnread ? '' : 'd-none' }}"></span>
                    </div>
                </a>
            @empty
                <div class="text-center text-muted py-5 px-3">
                    <i class="fas fa-check-circle text-success fa-2x mb-2 d-block"></i>
                    <p class="mb-0 font-weight-medium text-sm">No notifications available</p>
                </div>
            @endforelse
        </div>

        <!-- Footer Action -->
        <a href="#" id="mark-all-read-btn" class="dropdown-item text-center py-2.5 bg-light text-primary font-weight-medium border-top" style="font-size: 0.82rem;">
            <i class="fas fa-check-double mr-1"></i> Mark All as Read
        </a>
    </div>
</li>

@push('css')
<style>
    .notif-item {
        white-space: normal !important;
        transition: background-color 0.2s ease;
        background-color: #ffffff;
    }
    .notif-item:hover {
        background-color: #f8f9fa !important;
    }
    .notif-item.notif-unread {
        background-color: #f0f6ff;
    }
    .notif-item.notif-unread:hover {
        background-color: #e5effd !important;
    }
    .notif-icon-circle {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background-color: #eef2f7;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
    }
    .unread-dot {
        display: inline-block;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background-color: #007bff;
    }
</style>
@endpush

@push('js')
<script>
    $(document).ready(function() {
        const csrfToken = $('meta[name="csrf-token"]').attr('content');

        function updateBadgeUI(newCount) {
            newCount = Math.max(0, newCount);
            $('#notification-header-pill').text(newCount + ' New');

            if (newCount > 0) {
                const displayCount = newCount > 99 ? '99+' : newCount;
                if ($('#notification-badge').length === 0) {
                    $('#badge-container').html('<span class="badge badge-danger navbar-badge" id="notification-badge">' + displayCount + '</span>');
                } else {
                    $('#notification-badge').text(displayCount);
                }
            } else {
                $('#notification-badge').remove();
            }
        }

        // 1. MARK SINGLE AS READ
        $(document).off('click', '.mark-as-read').on('click', '.mark-as-read', function() {
            let element = $(this);
            let isUnread = element.attr('data-unread') === 'true';

            if (!isUnread) return;

            let notificationId = element.data('id');

            // Instant UI swap before target opens
            element.removeClass('notif-unread').attr('data-unread', 'false');
            element.find('.notif-title').removeClass('font-weight-bold text-dark').addClass('text-secondary');
            element.find('.unread-dot').addClass('d-none');

            let currentCount = parseInt($('#notification-header-pill').text()) || 0;
            updateBadgeUI(currentCount - 1);

            $.ajax({
                url: '/notifications/' + notificationId + '/read',
                type: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken }
            });
        });

        // 2. MARK ALL AS READ (Footer Button)
        $(document).off('click', '#mark-all-read-btn').on('click', '#mark-all-read-btn', function(e) {
            e.preventDefault();
            e.stopPropagation(); // Keep dropdown open so user sees the change

            $.ajax({
                url: '/notifications/mark-all-read',
                type: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken },
                success: function(response) {
                    if (response.success) {
                        $('.mark-as-read').removeClass('notif-unread').attr('data-unread', 'false');
                        $('.notif-title').removeClass('font-weight-bold text-dark').addClass('text-secondary');
                        $('.unread-dot').addClass('d-none');
                        updateBadgeUI(0);
                    }
                }
            });
        });

        // 3. AJAX LIVE BACKGROUND REFRESH
        function fetchLiveNotifications() {
            if ($('#notification-dropdown-root').hasClass('show')) {
                return;
            }

            $.ajax({
                url: '/api/notifications/poll',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('#notification-list').html(data.html);
                    updateBadgeUI(data.unread_count);
                }
            });
        }

        setInterval(fetchLiveNotifications, 10000);
    });
</script>
@endpush