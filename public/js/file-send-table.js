function initFileSendTable(tableId = '#FileSendTable', ajaxUrl = '') {
    var table = $(tableId).DataTable({
        responsive: true,
        lengthChange: true,
        autoWidth: false,
        serverSide: true,
        processing: true,
        order: [[5, 'desc']],
        dom: '<"row p-3 align-items-center"<"col-md-6"l><"col-md-6"f>>' +
             '<"row"<"col-md-12"tr>>' +
             '<"row p-3 align-items-center"<"col-md-5"i><"col-md-7"p>>',
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search records...",
            lengthMenu: "Show _MENU_ entries",
            processing: '<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>'
        },
        ajax: {
            url: ajaxUrl,
            type: 'GET'
        },
        columns: [
            { 
                data: 'item_code',
                render: function(data) {
                    return '<span class="font-weight-bold">' + (data || '') + '</span>';
                }
            },
            { data: 'ao_name' },
            { data: 'from_email' },
            { data: 'to_email' },
            { 
                data: 'file_link',
                render: function(data) {
                    if (!data) return '<span class="text-muted">No Link</span>';
                    return '<a href="' + data + '" target="_blank" class="btn btn-xs btn-outline-primary">' +
                               '<i class="fas fa-external-link-alt mr-1"></i> View Link' +
                           '</a>';
                }
            },
            { data: 'created_at' },
            { 
                data: 'status',
                render: function(data) {
                    if (!data) return '';
                    var statusLower = data.toString().toLowerCase();
                    var badgeClass = 'badge-secondary';
                    var iconClass = 'fa-info-circle';

                    if (statusLower.includes('in progress') || statusLower.includes('active')) {
                        badgeClass = 'badge-success pulse-animation';
                        iconClass = 'fa-play';
                    } else if (statusLower.includes('pending') || statusLower.includes('waiting')) {
                        badgeClass = 'badge-warning';
                        iconClass = 'fa-clock';
                    } else if (statusLower.includes('completed') || statusLower.includes('done')) {
                        badgeClass = 'badge-secondary';
                        iconClass = 'fa-check-double';
                    } else if (statusLower.includes('rejected') || statusLower.includes('error')) {
                        badgeClass = 'badge-danger';
                        iconClass = 'fa-times-circle';
                    }

                    return '<span class="badge ' + badgeClass + ' p-1">' +
                               '<i class="fas ' + iconClass + ' mr-1"></i>' + data +
                           '</span>';
                }
            },
            {
                data: 'item_id', 
                orderable: false, 
                searchable: false, 
                render: function (data) {
                    var editUrl = window.artworkDetailRoute.replace(':item_id', data);
                    return '<button type="button" class="btn btn-xs btn-info edit-btn" data-url="' + editUrl + '">' +
                               '<i class="fas fa-edit mr-1"></i> Edit' +
                           '</button>';
                }
            }
        ]
    });

    // Row Click Confirmation
    $(tableId).on('click', '.edit-btn', function() {
        var targetUrl = $(this).data('url');

        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to open and view this artwork file record?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, proceed!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Loading...',
                    text: 'Please wait while we redirect you.',
                    allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading(); }
                });
                window.location.href = targetUrl;
            }
        });
    });

    return table;
}