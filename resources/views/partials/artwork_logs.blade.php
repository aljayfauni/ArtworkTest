<!-- Notifications Dropdown Menu -->
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
@endpush
