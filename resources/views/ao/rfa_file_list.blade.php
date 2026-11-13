@extends('adminlte::page')

@section('title', 'Dashboard')

@section('plugins.Sweetalert2', true)
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)

@section('content_header')
    {{-- <h1><i class="fas fa-solid fa-chart-line"></i> Dashboard</h1> --}}
    @can('is-admin')
    <style>
        /* .main-sidebar { display: none !important; } */
        /* .content-wrapper, .main-header { margin-left: 0 !important; } */
    </style>
@endcan
@stop

@section('content')
<div class="row">
    
    <div class="col-12">
 
        <section class="content-header">
            <div class="container-fluid">
                <div class="row align-items-center justify-content-between">
                </div>
        </section>

    </div>
</div>
<div class="row mr-4">
        <div class="col-12">
            

            <div class="card card-dark shadow-sm">
            <div class="card-header">
                <h3 class="card-title font-weight-bold">
                    <i class="fas fa-list-ul mr-2"></i> All AO File Link Requests 
                </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0" style="">
                <table id="FileSendTable" class="table table-striped text-nowrap table-hover">
                    <thead>
                        <tr>
                            <th>Item Code</th>
                            <th>Sender Name</th>
                            <th>Sender Email</th>
                            <th>Recipient Email</th>
                            <th>Shared File/Link</th>
                            <th>Date Sent</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data populated dynamically or via DataTables -->
                    </tbody>
                </table>
            </div>
        </div>

           <div class="card card-dark shadow-sm">
            <div class="card-header">
                <h3 class="card-title font-weight-bold">
                    <i class="fas fa-list-ul mr-2"></i> Ready For Item Code Creationg Artwork
                </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0" style="">
                <table id="ReadyForItemCodeTable" class="table table-striped text-nowrap table-hover">
                    <thead>
                        <tr>
                            <th>Item Code</th>
                            <th>Sender Name</th>
                            <th>Sender Email</th>
                            {{-- <th>Recipient Email</th> --}}
                            <th>Shared File/Link</th>
                            <th>Date Sent</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data populated dynamically or via DataTables -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- A. CONTROL PANEL (Start a new task) -->

        @can('is-readonly')
        <div class="row mr-4">
            <div class="col-12 mt-4">
                <div class="card card-dark shadow-sm">
                    <div class="card-header">
                        <h3 class="card-title font-weight-bold">
                            <i class="fas fa-users mr-2"></i> List of Users
                        </h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0" style="max-height: 400px;">
                        <table id="UsersTable" class="table table-striped text-nowrap table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>User_id</th>
                                    <th>Item Code</th>
                                    <th>Start_time</th>
                                    <th>Stop_time</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data populated dynamically or via DataTables -->
                            </tbody>
                        </table>
                    </div>
                </div>          
            </div>
        </div>
        @endcan

                {{-- end --}}



                {{-- specifications form  {{ route('specifications.store') }} --}}

                {{-- end specification --}}

                {{-- checklist form test --}}


                <!-- A. CONTROL PANEL (Start a new task) -->

        @include('partials.modal.item_search')

        </div>
</div> 

@endsection

@section('js')
@include('partials.modal.ao.add_item_code')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    $(document).ready(function () {
        
        // select2 multiple -
    $('#employee_email').select2({
        tags: true, // Enables adding custom typed options
        tokenSeparators: [',', ' '], // Pressing comma or space adds the typed value as a tag
        placeholder: "Search list or type custom email...",
        allowClear: true,
        createTag: function (params) {
            var term = $.trim(params.term);

            // Don't create tag if empty
            if (term === '') {
                return null;
            }

            // Optional: Prevent adding special option names like INTERNAL_NOTE manually
            if (term.toUpperCase() === 'INTERNAL_NOTE') {
                return null;
            }

            return {
                id: term,
                text: term,
                newTag: true // Marks it as a user-entered custom tag
            };
        }
    });
        // ---------------------------------------------------------
        // SWEETALERT SUCCESS TOAST
        // ---------------------------------------------------------
        var Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 5000
        });

        @if (\Session::has('success'))
            Toast.fire({
                icon:'success',
                title:"{{ \Session::get('success') }}"
            });
        @endif

        window.addEventListener('load', function() {
            // This runs only after jQuery, Select2, and all assets are 100% loaded
            $('#select-tag').select2({
                placeholder: 'Search for a Item...',
                allowClear: true
            });
        });
       
        //list item timeline
    //     var UsersTable = $('#UsersTable').DataTable({
    //     //dom: 'lrt',
    //     "responsive":true,
    //     "lengthChange": true,
    //     //"lengthMenu":[[10,25,50,-2],[10,25,50,'All']],
    //     "autoWidth": false,
    //     ajax: '{{ route('timeline.showAll') }}',
    //     serverSide: true,
    //     processing: true,
    //     //"bFilter": false,
        
    //     "columns": [
    //     { data: 'id',
    //         visible: true,
    //         searchable: false
    //     } ,
    //     // { data: 'id'},
    //     { data: 'user_id' },
    //     { data: 'item_code' },
    //     { data: 'start_time' },
    //     { data: 'stop_time' },
    //     { data: 'status' },
    //     //{ data: 'source'},
    //     //{ data: 'market_code' },
    //     //{ data: 'scan_datetime' },
    //     //{ data: 'action'}
    //     ],
    //     "initComplete": function() {
    //         UsersTable.buttons().container().appendTo('#UsersTable_wrapper .col-md-6:eq(0)');
    //         $("#UsersTable").show();
    //     },
    //     "buttons": {
    //         buttons: [
    //     //        // { extend:"copy", className: 'btn btn-success'},
    //     //        // { extend:"csv", className:'btn btn-success' },
    //             // {
    //             //     className: 'btn btn-warning',
    //             //     text: '<i class="fas fa-plus"></i>',
    //             //     titleAttr: 'Create',
    //             //     action: function(e, dt, node, config){
    //             //         window.location = '{{route('users.create')}}';
    //             //     }
    //             // },
    //     //         { extend: "excel", className: 'btn btn-warning', text: '<i class="fas fa-file-excel"></i>',titleAttr: 'Export to Excel',
    //     //             action: function(e, dt, node, config){
    //     //                 window.location = '';
    //     //             }
    //     //             // exportOptions: {
    //     //             //     columns: [1,2,3,4,5,6]
    //     //             // }
    //     //         },
    //     //         { extend: "pdf", className: 'btn btn-warning', text: '<i class="fas fa-file-pdf"></i>',titleAttr: 'Export to PDF',
    //     //             orientation: 'landscape',
    //     //             download: 'open',
    //     //             exportOptions: {
    //     //                 columns: [1,2,3,4,7]
    //     //             },
    //     //             customize: function (doc) {
    //     //                 doc.content[1].table.widths = 
    //     //                     Array(doc.content[1].table.body[0].length + 1).join('*').split('');
    //     //             }
    //     //         },
    //     //         { extend: "print", className: 'btn btn-warning', text: '<i class="fas fa-print"></i>', titleAttr: 'Print',
    //     //             exportOptions: {
    //     //                 columns: [1,2,3,4,5,6]
    //     //             }
    //     //         },
    //     //         { extend: "colvis", className: 'btn btn-warning dropdown-toggle dropdown-icon', text:'', titleAttr: 'Column Visibility',columns: ':gt(0)'}
            
    //             {
    //                 className: 'btn btn-success',
    //                 text: '<i class="fas fa-plus"></i> Add User',
    //                 titleAttr: 'New',
    //                 action: function(e, dt, node, config){
    //                     location.href='{{ route('users.create') }}';
    //                 }
    //             },
    //         ]

            
    //     }
        
    // });


    //vertical timeline
    var UsersTable1 = $('#UsersTable1').DataTable({
        "responsive": true,
        "lengthChange": true,
        "autoWidth": false,
        "serverSide": true,
        "processing": true,
        "ajax": {
            "url": '{{ route('timeline.showAll') }}',
            "type": 'GET',
        },
        "columns": [
            { data: 'id' },
            { data: 'user_id' },
            { data: 'item_code' },
            { data: 'start_time' },
            { data: 'stop_time' },
            { data: 'status' }
        ],
        
        "drawCallback": function(settings) {
            var api = this.api();
            var data = api.rows({ page: 'current' }).data();
            var $container =$('#timelineContainer');
            
            $container.empty(); // Clear loading indicator or previous content

            if (data.length === 0) {
                $container.html('<div class="text-center p-4 text-muted"><i class="fas fa-info-circle"></i> No timeline records found.</div>');
                return;
            }

            // Iterate over returned rows and build AdminLTE timeline blocks
            data.each(function(row) {
                // Determine status badge color and icon
                var statusClass = 'bg-secondary';
                var statusIcon = 'fa-clock';
                var statusLower = row.status ? row.status.toLowerCase() : '';

                if (statusLower === 'completed' || statusLower === 'success' || statusLower === 'active') {
                    statusClass = 'bg-success';
                    statusIcon = 'fa-check';
                } else if (statusLower === 'For Item Code Creation' || statusLower === 'in progress' || statusLower === 'For Item Code Creation') {
                    statusClass = 'bg-warning';
                    statusIcon = 'fa-spinner fa-spin';
                } else if (statusLower === 'failed' || statusLower === 'cancelled') {
                    statusClass = 'bg-danger';
                    statusIcon = 'fa-times';
                }

                var timelineItemHtml = `
                    <div>
                        <i class="fas ${statusIcon} ${statusClass}"></i>
                        <div class="timeline-item shadow-sm">
                            <span class="time">
                                <i class="far fa-clock"></i> ${row.start_time || 'N/A'}
                            </span>
                            <h3 class="timeline-header">
                                <a href="#">User ID: ${row.user_id}</a> updated item <strong>#${row.item_code}</strong>
                            </h3>
                            <div class="timeline-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <strong>Start Time:</strong> ${row.start_time || 'N/A'}<br>
                                        <strong>Stop Time:</strong> ${row.stop_time || 'N/A'}
                                    </div>
                                    <div class="col-sm-6">
                                        <strong>Item Code:</strong> <code>${row.item_code}</code><br>
                                        <strong>Status:</strong> <span class="badge ${statusClass}">${row.status}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="timeline-footer">
                                <span class="badge badge-light border">ID: #${row.id}</span>
                            </div>
                        </div>
                    </div>
                `;

                $container.append(timelineItemHtml);
            });

            // Append AdminLTE end-of-timeline marker icon
            $container.append(`
                <div>
                    <i class="far fa-clock bg-gray"></i>
                </div>
            `);
        }
    });

//fetch fileSend Table
    // var FileSendLinkTable = $('#FileSendTable').DataTable({
    //     responsive: true,
    //     lengthChange: true,
    //     autoWidth: false,
    //     serverSide: true,
    //     processing: true,
    //     ajax: {
    //         url: "{{ route('FileSendShowAll') }}",
    //         type: 'GET'
    //     },
    //     columns: [
    //         { data: 'ao_name' },
    //         { data: 'from_email' },
    //         { data: 'to_email' },
    //         { 
    //             data: 'file_link',
    //             render: function(data) {
    //                 if (!data) return '';
                    
    //                 // Renders as a standard clickable blueprint hyperlink
    //                 return '<a href="' + data + '" target="_blank" style="text-decoration: underline; color: blue;">' + data + '</a>';
    //             }
    //         },
    //         { data: 'created_at' },
    //         { data: 'status' },
    //            {
    //             data: 'id', 
    //             orderable: false, 
    //             searchable: false, 
    //             render: function (data) {
    //                         // 1. Generate the Laravel named route using your exact route name
    //                         var editUrl = "{{ route('getArtworkFileDetail', ':id') }}";
                            
    //                         // 2. Replace the placeholder with the dynamic ID from the data row
    //                         editUrl = editUrl.replace(':id', data);

    //                         // 3. Return an HTML link element styled as a button
    //                         return `<a href="${editUrl}" class="btn btn-sm btn-primary">Edit</a>`;
    //                     }
    //             }
    //     ]
    // });

    // fetch fileSend Table
var FileSendLinkTable = $('#FileSendTable').DataTable({
    responsive: true,
    lengthChange: true,
    autoWidth: false,
    serverSide: true,
    processing: true,
    order: [[5, 'desc']],
    
    // Custom DOM layout: places length menu & search on top, table in middle, info & pagination at bottom
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
        url: "{{ route('FileSendShowAll') }}",
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
            data: 'rfa_reference', 
            orderable: false, 
            searchable: false, 
            render: function (data) {
               
                var editUrl = "{{ route('getArtworkFileDetail', ':item_id') }}";
                editUrl = editUrl.replace(':item_id', data);

                return '<button type="button" class="btn btn-xs btn-info edit-btn" data-url="' + editUrl + '">' +
                            '<i class="fas fa-edit mr-1"></i> Edit' +
                       '</button>';
            }
        }
    ]
});


//ready for item code creation
var FileSendLinkTable = $('#ReadyForItemCodeTable').DataTable({
    responsive: true,
    lengthChange: true,
    autoWidth: false,
    serverSide: true,
    processing: true,
    order: [[5, 'desc']],
    
    // Custom DOM layout: places length menu & search on top, table in middle, info & pagination at bottom
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
        url: "{{ route('showAllReadyForItemCode') }}",
        type: 'GET'
    },
    columns: [
        { 
           
    data: 'item_code',
    render: function(data, type, row) {
        if (!data) {
      
            return '<button type="button" class="btn btn-xs btn-info add-item-code" data-rfa-reference="' +row.rfa_reference+ '">' +
                '<i class="fas fa-edit mr-1"></i> Add Item Code' +
                '</button>';
        }
        // return '<span class="font-weight-bold">' + data + '</span>';
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
                console.log(statusLower);

                if (statusLower.includes('in progress') || statusLower.includes('active')) {
                    badgeClass = 'badge-success pulse-animation';
                    iconClass = 'fa-play';
                } else if (statusLower.includes('pending') ||  statusLower.includes('for item code creation')) {
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
            data: 'rfa_reference', 
            orderable: false, 
            searchable: false, 
            render: function (data) {
               
                var editUrl = "{{ route('getArtworkFileDetail', ':item_id') }}";
                editUrl = editUrl.replace(':item_id', data);

                return '<button type="button" class="btn btn-xs btn-info edit-btn" data-url="' + editUrl + '">' +
                            '<i class="fas fa-edit mr-1"></i> Edit' +
                       '</button>';
            }
        }
    ]
});

    // fetch fileSend supplied Verfication Table
var FileSendLinkTable = $('#aoApprovalTableBody').DataTable({
    responsive: true,
    lengthChange: true,
    autoWidth: false,
    serverSide: true,
    processing: true,
    order: [[5, 'desc']],
    
    // Custom DOM layout: places length menu & search on top, table in middle, info & pagination at bottom
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
        url: "{{ route('FileSendShowAll') }}",
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
                var editUrl = "{{ route('getArtworkFileDetail', ':item_id') }}";
                editUrl = editUrl.replace(':item_id', data);

                return '<button type="button" class="btn btn-xs btn-info edit-btn" data-url="' + editUrl + '">' +
                            '<i class="fas fa-edit mr-1"></i> Edit' +
                       '</button>';
            }
        }
    ]
});

// SweetAlert2 Confirmation & Loading Trigger
$('#FileSendTable').on('click', '.edit-btn', function() {
    var targetUrl = $(this).data('url'); // Grab the dynamic URL from the clicked button

    // Step 1: Show confirmation choice prompt
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
            
            // Step 2: Show native SweetAlert loading screen feedback while routing transfers pages
            Swal.fire({
                title: 'Loading...',
                text: 'Please wait while we redirect you.',
                allowOutsideClick: false,
                allowEscapeKey: false,
                allowEnterKey: false,
                didOpen: () => {
                    Swal.showLoading(); 
                }
            });

            // Step 3: Shift pages to the edit view
            window.location.href = targetUrl;
        }
    });
});
$('#ReadyForItemCodeTable').on('click', '.edit-btn', function() {
    var targetUrl = $(this).data('url'); // Grab the dynamic URL from the clicked button

    // Step 1: Show confirmation choice prompt
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
            
            // Step 2: Show native SweetAlert loading screen feedback while routing transfers pages
            Swal.fire({
                title: 'Loading...',
                text: 'Please wait while we redirect you.',
                allowOutsideClick: false,
                allowEscapeKey: false,
                allowEnterKey: false,
                didOpen: () => {
                    Swal.showLoading(); 
                }
            });

            // Step 3: Shift pages to the edit view
            window.location.href = targetUrl;
        }
    });
});
// Global Back-Button Cache Fix
window.addEventListener('pageshow', function (event) {
    if (event.persisted || (window.performance && window.performance.navigation.type === 2)) {
        if (typeof Swal !== 'undefined' && Swal.isVisible()) {
            Swal.close(); // Clears any frozen loaders immediately upon tracking historical entry points
        }
    }
});

    search = function(){
        console.log($('#search').val());
        InvoicesTable.search($('#search').val()).draw();
        PODsTable.search($('#search').val()).draw();
    }
        //end

$('#btn_start_time').on('click', function() {
        // 1. Get the selected ID value from your Select2 input
        
        let selectedId = $('#select-tag').val();
        // 2. Alert the user if they haven't selected an option yet
        if (!selectedId) {
            Toast.fire({ icon: 'error', title: 'Please select a Item first!' });
            return;
        }

        Swal.fire({  
            title: 'Are you sure you want to Start this?', 
            icon: 'warning', 
            showCancelButton: true,  
            confirmButtonText: 'Yes',  
            cancelButtonText: 'No',
        }).then((result) => {  
            if (result.isConfirmed || result.value) { 
                $.ajax({
                    url: '/timeStart', 
                    type: 'post',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: selectedId 
                    },
                    success: function(response){
                        if (response.success){
                            Toast.fire({ icon: 'success', title: response.success });
                            
                            let currentCount = parseInt($('#total-start-count').text()) || 0;
                            if (currentCount > 0) {
                                $('#total-start-count').text(currentCount - 1);
                            }
                        } else if(response.error){
                            Toast.fire({ icon: 'error', title: response.error });
                        }
                    }
                }); 
            }
        });
    });
//stoptime
       // ==========================================
        // 3. MO SEARCH MODAL & DATATABLE
        // ==========================================
        window.showMOSearch = function() {
            // if (!validatePrestartSetup('searching for an MO')) return;
            
            $('#SearchMOModal').modal('show');
        }

$('#btn_stop_time').on('click', function() {
        // 1. Get the selected ID value from your Select2 input
        
        let selectedId = $('#select-tag').val();
        // 2. Alert the user if they haven't selected an option yet
        if (!selectedId) {
            Toast.fire({ icon: 'error', title: 'Please select a Item first!' });
            return;
        }

        Swal.fire({  
            title: 'Are you sure you want to Stop this?', 
            icon: 'warning', 
            showCancelButton: true,  
            confirmButtonText: 'Yes',  
            cancelButtonText: 'No',
        }).then((result) => {  
            if (result.isConfirmed || result.value) { 
                $.ajax({
                    url: '/timeStop', 
                    type: 'post',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: selectedId 
                    },
                    success: function(response){
                        if (response.success){
                            Toast.fire({ icon: 'success', title: response.success });
                            
                            let currentCount = parseInt($('#total-start-count').text()) || 0;
                            if (currentCount > 0) {
                                $('#total-start-count').text(currentCount - 1);
                            }
                        } else if(response.error){
                            Toast.fire({ icon: 'error', title: response.error });
                        }
                    }
                }); 
            }
        });
    });

    });
</script>

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