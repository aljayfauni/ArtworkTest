@extends('adminlte::page')

@section('title', 'Dashboard')

@section('plugins.Sweetalert2', true)
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)

@section('content_header')
<style>
    /* Clean Neutral Canvas */
    .content-wrapper {
        background-color: #f8fafc !important;
    }

    /* Modern Header & Role Banner */
    .modern-supervisor-header {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-left: 4px solid #0f172a;
        border-radius: 14px;
        padding: 20px 24px;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04), 0 4px 12px rgba(15, 23, 42, 0.02);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 1.5rem;
    }

    .header-icon-box {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        color: #0f172a;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.15rem;
        flex-shrink: 0;
    }

    .user-status-pill {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        color: #334155;
        padding: 8px 14px;
        border-radius: 9999px;
        font-size: 0.85rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .user-status-pill .dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #d97706;
    }

    /* Modern Card System */
    .modern-card {
        background: #ffffff;
        border: 1px solid #e2e8f0 !important;
        border-radius: 14px !important;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04), 0 4px 12px rgba(15, 23, 42, 0.02) !important;
        margin-bottom: 1.5rem;
        overflow: hidden;
    }

    .modern-card > .card-header {
        background: #ffffff;
        border-bottom: 1px solid #f1f5f9;
        padding: 1.15rem 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .modern-card > .card-header::after {
        display: none; /* Override AdminLTE clearfix float */
    }

    .card-header-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .card-icon-badge {
        width: 36px;
        height: 36px;
        border-radius: 9px;
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        color: #334155;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        flex-shrink: 0;
    }

    .modern-card-title {
        font-size: 1rem;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
    }

    .modern-card-subtitle {
        font-size: 0.78rem;
        color: #64748b;
        margin: 0;
    }

    .queue-pill {
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #e2e8f0;
        border-radius: 9999px;
        padding: 0.3rem 0.8rem;
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.03em;
        text-transform: uppercase;
    }

    .queue-pill.pending {
        background: #fffbeb;
        color: #92400e;
        border-color: #fde68a;
    }

    /* Table & Scroll Area */
    .modern-table-body {
        padding: 1.25rem 1.5rem 1.5rem;
    }

    .modern-scroll-box {
        max-height: 430px;
        overflow-y: auto;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        scrollbar-width: thin;
        scrollbar-color: #cbd5e1 transparent;
    }

    .modern-scroll-box::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }

    .modern-scroll-box::-webkit-scrollbar-thumb {
        background-color: #cbd5e1;
        border-radius: 9999px;
    }

    .modern-table {
        margin-bottom: 0 !important;
    }

    .modern-table thead th {
        position: sticky;
        top: 0;
        z-index: 2;
        background: #f8fafc !important;
        color: #64748b !important;
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-top: none !important;
        border-bottom: 1px solid #e2e8f0 !important;
        padding: 0.85rem 1.15rem !important;
        white-space: nowrap;
    }

    .modern-table tbody td {
        padding: 0.9rem 1.15rem !important;
        vertical-align: middle !important;
        border-bottom: 1px solid #f1f5f9 !important;
        color: #1e293b;
        font-size: 0.88rem;
        background: #ffffff;
    }

    .modern-table tbody tr:hover td {
        background-color: #f8fafc !important;
    }

    .modern-table tbody tr:last-child td {
        border-bottom: none !important;
    }

    /* DataTables Inputs Polish */
    .modern-table-body .dataTables_filter input {
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        padding: 0.35rem 0.75rem;
        font-size: 0.85rem;
        color: #0f172a;
        outline: none;
    }

    .modern-table-body .dataTables_filter input:focus {
        border-color: #334155;
        box-shadow: 0 0 0 3px rgba(51, 65, 85, 0.1);
    }

    .modern-table-body .dataTables_length select {
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        padding: 0.25rem 0.5rem;
        font-size: 0.85rem;
    }
</style>
@stop

@section('content')
<div class="container-fluid pt-3 pb-4">

    <!-- Unified Modern Header & Role Banner -->
    <div class="modern-supervisor-header">
        <div class="d-flex align-items-center" style="gap: 16px;">
            <div class="header-icon-box">
                <i class="fas fa-user-shield"></i>
            </div>
            <div>
                <h4 class="mb-1 font-weight-bold" style="color: #0f172a; font-size: 1.2rem;">
                    GD Supervisor Dashboard
                </h4>
                <p class="mb-0" style="color: #64748b; font-size: 0.88rem;">
                    Receives file links from AO, submits Supplied Verification Form, assigns Primary &amp; Companion Graphic Artists, and monitors activity.
                </p>
            </div>
        </div>

        <div class="user-status-pill">
            <span class="dot"></span>
            <span style="color: #64748b; font-weight: 500;">Supervisor:</span>
            <span>{{ Auth::user()->name }}</span>
        </div>
    </div>
<!-- Action Mode Selector -->
        <div class="modern-card p-3 mb-4 d-flex align-items-center justify-content-between flex-wrap" style="gap: 12px;">
            <div class="d-flex align-items-center" style="gap: 10px;">
                <span class="card-icon-badge"><i class="fas fa-stopwatch"></i></span>
                <div>
                    <h6 class="modern-card-title mb-0">Choose Action To Take</h6>
                    <small class="modern-card-subtitle">Select an action mode to start time tracking before modifying a row</small>
                </div>
            </div>

            <div class="d-flex align-items-center" style="gap: 10px;" id="gd_supervisor_actions">
                <button type="button" class="btn btn-outline-dark font-weight-bold action-mode-btn" data-action="GD ASSESS" style="border-radius: 8px;">
                    <i class="fas fa-clipboard-check mr-1"></i> Assessment
                </button>
                <button type="button" class="btn btn-outline-dark font-weight-bold action-mode-btn" data-action="GD Assign to Artist" style="border-radius: 8px;">
                    <i class="fas fa-user-plus mr-1"></i> Assigning Artwork to Artist
                </button>
            </div>
        </div>
    <br>

<!-- Wrapped Tables with Bootstrap-Only Overlay -->
<div class="position-relative">
    <!-- Overlay using pure Bootstrap classes -->
    {{-- <div id="table_lock_overlay" class="position-absolute w-100 h-100 d-flex align-items-center justify-content-center rounded" style="top: 0; left: 0; background-color: rgba(255, 255, 255, 0.75); z-index: 20;">
        <div class="bg-dark text-white px-4 py-2 rounded shadow font-weight-bold">
            <i class="fas fa-lock mr-2"></i> Select <strong>Assessment</strong> or <strong>Assigning Artwork to Artist</strong> above to unlock tables
        </div>
    </div> --}}

    <!-- 1. Incoming Request Item -->
    <div class="card modern-card">
        <div class="card-header">
            <div class="card-header-left">
                <span class="card-icon-badge">
                    <i class="fas fa-file-signature"></i>
                </span>
                <div>
                    <h3 class="modern-card-title">1. Incoming Request Item</h3>
                    <p class="modern-card-subtitle">Supplied file verification &amp; artist assignment queue</p>
                </div>
            </div>

            <div class="d-flex align-items-center" style="gap: 10px;">
                <span class="queue-pill">Verification Queue</span>
                <div class="card-tools m-0">
                    <button type="button" class="btn btn-tool text-secondary" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="card-body modern-table-body">
            <div class="modern-scroll-box table-responsive">
                <table id="FileSendTable" class="table modern-table table-hover text-nowrap align-middle">
                    <thead>
                        <tr>
                            <th>Item Code</th>
                            <th>Client Name</th>
                            <th>Sender Name</th>
                            <th>Shared File/Link</th>
                            <th>Date Sent</th>
                            <th>Status</th>
                            <th>Action</th>
                            <th>Forms</th>
                            <th>To Assign</th>
                            <th>View</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- 2. Incoming Request Item (No Item Code) -->
    <div class="card modern-card mb-0">
        <div class="card-header">
            <div class="card-header-left">
                <span class="card-icon-badge">
                    <i class="fas fa-hourglass-end"></i>
                </span>
                <div>
                    <h3 class="modern-card-title">2. Incoming Request Item (No Item Code)</h3>
                    <p class="modern-card-subtitle">Pending items awaiting item code assignment</p>
                </div>
            </div>

            <div class="d-flex align-items-center" style="gap: 10px;">
                <span class="queue-pill pending">Pending Code</span>
                <div class="card-tools m-0">
                    <button type="button" class="btn btn-tool text-secondary" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="card-body modern-table-body">
            <div class="modern-scroll-box table-responsive">
                <table id="FileSendTable_NoItemCode" class="table modern-table table-hover text-nowrap align-middle">
                    <thead>
                        <tr>
                            <th>Item Code</th>
                            <th>Client Name</th>
                            <th>Item Description</th>
                            <th>Sender Name</th>
                            <th>Shared File/Link</th>
                            <th>Date Sent</th>
                            <th>Status</th>
                            <th>Forms</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

</div>


        {{-- end --}}

        {{-- request form   {{ route('artwork-request.store') }} --}}
        @include('partials.modal.supplied_file_verification') 
        
         @include('partials.modal.supplied_file_verification_no_item_code')
        {{-- specifications form  {{ route('specifications.store') }} --}}
         @include('partials.modal.specifications')
        {{-- end specification --}}

        {{-- checklist form test --}}

</div> 

@endsection
    
@section('js')

@include('partials.modal.gd.assign_to_artist')
@include('partials.modal.gd.update_supplied_status')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    $(document).ready(function () {
        
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
       


$(document).ready(function() {
    $('.action-mode-btn').on('click', function() {
        let btn = $(this);
        let actionTaken = btn.data('action');

        btn.prop('disabled', true);

        $.ajax({
            url: "{{ route('gd-action-taken') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                action_taken: actionTaken
            },
            success: function(response) {
                $('.action-mode-btn').removeClass('btn-dark text-white').addClass('btn-outline-dark');
                btn.removeClass('btn-outline-dark').addClass('btn-dark text-white');

                // Note: remove d-flex before fading out so jQuery hide() works properly
                $('#table_lock_overlay').removeClass('d-flex').fadeOut(200);
            },
            error: function(xhr) {
                console.error('Error logging action:', xhr.responseText);
                alert('Failed to log action. Please try again.');
            },
            complete: function() {
                btn.prop('disabled', false);
            }
        });
    });
});

var FileSendLinkTable = $('#FileSendTable').DataTable({
    responsive: true,
    lengthChange: true,
    autoWidth: false,
    serverSide: true,
    processing: true,
    order: [[4, 'desc']],
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
        url: "{{ route('FileRecievedLinkShowAll') }}",
        type: 'GET'
    },
    columns: [
        { data: 'item_code' },
        { data: 'client_name' },
        { data: 'ao_name' },

        // Shared File / Link Column (Styled as Inspect Link Button)
        {
            data: 'file_link',
            render: function(data, type, row) {
                if (!data) return '<span class="text-muted">N/A</span>';

                return `
                    <a href="${data}" 
                       class="btn btn-xs btn-outline-info file-link" 
                       data-id="${row.id}" 
                       target="_blank">
                        <i class="fas fa-external-link-alt mr-1"></i> Inspect Link
                    </a>
                `;
            }
        },
        { data: 'created_at' },

        // Restored Original Status Column
        { data: 'status' },

                {
            data: null,
            orderable: false,
            searchable: false,

            render: function(data,type,row) {
                let artistName = row.is_Assign_to_Artist;
          
              
            
                    return `
                            <button type="button" 
                                    class="btn btn-warning btn-sm open-status-modal" 
                                    data-id="${row.rfa_reference}" 
                                    data-item-code="${row.item_code ?? ''}"
                                     data-rfa-reference="${row.rfa_reference}"
                                     data-item-desc="${row.file_info}"
                                        data-client-name="${row.client_name}"
                                    >
                                <i class="fas fa-edit mr-1"></i> Update Status
                            </button>
                        `;

                         return `
                            <button type="button" 
                                    class="btn btn-warning btn-sm btn-gd-assess" 
                                    data-id="${row.rfa_reference}" 
                                    data-item-code="${row.item_code ?? ''}"
                                     data-rfa-reference="${row.rfa_reference}"
                                     data-item-desc="${row.file_info}"
                                        data-client-name="${row.client_name}"
                                    >
                                <i class="fas fa-edit mr-1"></i> Assess Supplied File
                            </button>
                        `;

            // If not assigned, render the "Assign To Artist" modal trigger button
            
            },

            
            
            },

            // Restored Original Action Buttons (Verify & Specs)
            {
                data: null,
                orderable: false,
                searchable: false,
            render: function(data, type, row) {
            // 1. Convert values to Booleans (handles true/false, "1"/"0", 1/0)
            let isVerified =  row.is_verified ;
            let isAoApprove = row.is_Ao_Approve;

        

            // 2. Default state: Supplied File
            let btnText = "Supplied File";
            let btnClass = "btn-secondary";
            let btnIcon = "fa-file-alt";
            let disabled = "";

            // 3. Conditional states
            if ( isVerified) {
                btnText = "Supplied Verified";
                btnClass = "btn-success";
                btnIcon = "fa-check";
                disabled = "";
            } else if (isAoApprove && !isVerified) {
                btnText = "Supplied Submitted";
                btnClass = "btn-primary";
                btnIcon = "fa-paper-plane";
            }

            // 4. Specifications Button logic
            let is_specification_exist = row.is_specification_exist; 
            let specClass = "btn-success";
            let specText  = "Specifications";
            let specIcon  = "fa-check";

            return `
                <div class="btn-group btn-group-sm" role="group"> 
                    <!-- Verify Button --> 
                    <button type="button" class="btn ${btnClass} btn-verify mr-1" data-id="${row.id}" data-client="${row.client_name ?? ''}" data-rfaReference="${row.rfa_reference}" title="${btnText}" ${disabled}> 
                        <i class="fas ${btnIcon} mr-1"></i> ${btnText} 
                    </button> 
                    <!-- Specifications Button 
                    <button type="button" class="btn ${specClass} btn-specifications" data-id="${row.id}" data-itemcode="${row.item_code ?? ''}" title="${specText}"> 
                        <i class="fas ${specIcon} mr-1"></i> ${specText} 
                    </button>  -->
                </div>
            `;
        }
        },
        // NEW COLUMN: Dynamic To Assign Column
        {
            data: null,
            orderable: false,
            searchable: false,

            render: function(data, type, row) {
                let artistName = row.is_Assign_to_Artist;
                console.log('jj'
                +row);
                // If an artist is assigned, show their name inside a badge/text
                if (artistName) {
                    return `<span class="badge badge-success font-weight-bold p-1 btn-assign open-assign-to-artist-modal"  data-id="${row.item_code}" 
                                    data-item-code="${row.item_code ?? ''}" data-rfareference="${row.rfa_reference ?? ''}" style="cursor: pointer;">
                                <i class="fas fa-user-check mr-1"></i>${artistName}
                            </span>`;
                }
                if(row.is_verified ===true || row.is_verified ==1){
                    return `
                            <button type="button" 
                                    class="btn btn-primary btn-sm btn-assign open-assign-to-artist-modal" 
                                    data-id="${row.item_code}" 
                                    data-item-code="${row.item_code ?? ''}">
                                <i class="fas fa-paint-brush mr-1"></i> Assign To Artist
                            </button>
                        `;
                }
                return '<span class="text-muted">-</span>';    

            // If not assigned, render the "Assign To Artist" modal trigger button
            
            },

            
            
        },
        {
            data: null,
            orderable: false,
            searchable: false,

            render: function(data, type, row) {
                // If an artist is assigned, show their name inside a badge/text
         
                if(row.is_verified ===true || row.is_verified ==1){
                    return `
                       

                        <a href="${"{{ route('artwork', ':id') }}".replace(':id', row.item_code)}" 
       class="btn btn-xs btn-outline-info " 
       data-id="${row.item_code}" 
       target="_blank">
        <i class="fas fa-eye mr-1"></i> Check Artwork
    </a>

<a href="${"{{ route('timeline', ':id') }}".replace(':id', row.item_code)}" 
       class="btn btn-xs btn-outline-info " 
       data-id="${row.item_code}" 
       target="_blank">
        <i class="fas fa-line mr-1"></i> View TimeLine
    </a>
                        `;
                }
                return '<span class="text-muted">-</span>';    

            // If not assigned, render the "Assign To Artist" modal trigger button
            
            }
        },
        
    ]
});

//no item code 
var FileSendLinkTable = $('#FileSendTable_NoItemCode').DataTable({
    responsive: true,
    lengthChange: true,
    autoWidth: false,
    serverSide: true,
    processing: true,
    order: [[4, 'desc']],
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
        url: "{{ route('FileRecievedLinkShowAllNoItemCode') }}",
        type: 'GET'
    },
    columns: [
        { data: 'item_code' },
        { data: 'client_name' },
         { data: 'file_info' },
        { data: 'ao_name' },

        // Shared File / Link Column (Styled as Inspect Link Button)
        {
            data: 'file_link',
            render: function(data, type, row) {
                if (!data) return '<span class="text-muted">N/A</span>';

                return `
                    <a href="${data}" 
                       class="btn btn-xs btn-outline-info file-link" 
                       data-id="${row.id}" 
                       target="_blank">
                        <i class="fas fa-external-link-alt mr-1"></i> Inspect Link
                    </a>
                `;
            }
        },
        { data: 'created_at' },

        // Restored Original Status Column
        { data: 'status' },

        // Restored Original Action Buttons (Verify & Specs)
        {
            data: null,
            orderable: false,
            searchable: false,
    render: function(data, type, row) {
    // 1. Convert values to Booleans (handles true/false, "1"/"0", 1/0)
    let isVerified =  row.is_verified ;
    let isAoApprove = row.is_Ao_Approve;

    // 2. Default state: Supplied File
    // let btnText = "<b>Fill Up Supplied Verification!</b>";
    // let btnClass = "btn-warning";
    // let btnIcon = "fa-pencil";
    // let disabled = "";
     //rfa 
    let is_rfa_reference_exist = row.is_rfa_reference_exist; 
    let rfaClass = "btn-warning";
    let rfaText  = "<b>Fill Up Supplied Verification!</b>";
    let rfaIcon  = "fa-pencil";


        if (!is_rfa_reference_exist ) {
        rfaText = "Fill Up Supplied Verification!";
        rfaClass = "btn-warning";
        rfaIcon = "fa-pencil";
        disabled = "";
    } else if (is_rfa_reference_exist) {
        rfaText = "File Supplied Submitted";
        rfaClass = "btn-success";
        rfaIcon = "fa-paper-plane";
         disabled = "";
    }

    // 3. Conditional states
    if (!isAoApprove && isVerified) {
        btnText = "Supplied Verified";
        btnClass = "btn-success";
        btnIcon = "fa-check";
        disabled = "disabled";
    } else if (isAoApprove && !isVerified) {
        btnText = "Supplied Submitted";
        btnClass = "btn-primary";
        btnIcon = "fa-paper-plane";
    }

    // 4. Specifications Button logic
    let is_specification_exist = row.is_specification_exist; 
    let specClass = "btn-success";
    let specText  = "Specifications";
    let specIcon  = "fa-check";

   
    return `
        <div class="btn-group btn-group-sm" role="group"> 
            <!-- Verify Button --> 
            <button type="button" class="btn ${rfaClass} btn-verify mr-1" data-id="${row.id}" data-rfareference="${row.rfa_reference}" data-client="${row.client_name ?? ''}" title="${rfaText}" ${disabled}> 
                <i class="fas ${rfaIcon} mr-1"></i> ${rfaText} 
            </button> 
            <!-- Specifications Button 
            <button type="button" class="btn ${specClass} btn-specifications" data-id="${row.id}" data-itemcode="${row.item_code ?? ''}" title="${specText}"> 
                <i class="fas ${specIcon} mr-1"></i> ${specText} 
            </button>  -->
        </div> 
    `;
}
        },
      
    ]
});



// Row selection & Data extraction event listener
$('#FileSendTable tbody').on('click', 'tr', function (e) {
    // Ignore row selection if clicking interactive elements like links or buttons
    if ($(e.target).closest('a, button, .btn, input').length) {
        return;
    }

    // Retrieve row data object from DataTables
    var rowData = FileSendLinkTable.row(this).data();
    if (!rowData) return;

    // Toggle row selection background
    if ($(this).hasClass('selected')) {
        $(this).removeClass('selected table-warning');
    } else {
        FileSendLinkTable.$('tr.selected').removeClass('selected table-warning');
        $(this).addClass('selected table-warning');
    }

    // You now have access to all row properties directly:
    console.log("Selected Row ID:", rowData.id);
    console.log("Full Row Data:", rowData);

    // Call your edit function or modal trigger here using rowData.id
});

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

//open file link or inspect link url
$('#FileSendTable').on('click', '.file-link', function(e) {
    e.preventDefault();

    const fileUrl = $(this).attr('href');
    const fileId = $(this).data('id');

    $.ajax({
        url: '/update-file-status',
        type: 'POST',
        data: {
            id: fileId,
            status: 'Opened'
        },
        success: function(response) {
            // Open file after database update succeeds
            window.open(fileUrl, '_blank');
            console.log('Status updated successfully');
        },
        error: function(xhr) {
            console.error(xhr.responseJSON || xhr.responseText);
            alert('Failed to update status.');
        }
    });
});

    //vertical timeline



    search = function(){
        console.log($('#search').val());
        InvoicesTable.search($('#search').val()).draw();
        PODsTable.search($('#search').val()).draw();
    }
        //end



// Listen for click on dynamically created verify buttons
// $('#FileSendTable').on('click', '.btn-verify', function () {
//     var recordId = $(this).data('id');
//     var clientName = $(this).data('client');
//     var item_description = $(this).data('client_name');
//     console.log($(this).data());
//     // Populate data inside the modal form
//     $('#verify_file_id').val(recordId);
//     $('#client').val(clientName);
//      $('#item_description').val(item_description);

//     // Show the modal
//     $('#verificationModal').modal('show');
// });
$('#FileSendTable').on('click', '.btn-verify', function () {
    var recordId = $(this).data('rfareference'); // rfa reference number

    // 1. CLEAR PREVIOUS DATA: Reset the form so old data doesn't linger
    $('#verificationModal').find('form').trigger('reset');
    $('#item_code_label').text('');
    $('#rfa_reference_label').text('');
    $('#modal_file_link').attr('href', '#').addClass('d-none');

    // 2. Show the modal immediately (optional, or show inside success)
    $('#verificationModal').modal('show');

$.ajax({
    url: "/gd_suppliedVerified/" + recordId, // Replace with your Laravel route URL
    type: "GET",
    dataType: "json",
    success: function (response) {
        console.log(response.data);

        if (!response.data) {
            console.error("No data found in response");
            Swal.fire({
                icon: 'warning',
                title: 'No Data Found',
                text: 'No details were found for this record.',
                confirmButtonText: 'OK'
            });
            return;
        }
       
        // Populate text labels and inputs
        $('#item_code_label').text(response.data.item_code);
        $('#rfa_reference_label').text(response.data.rfa_reference);
        $('#rfa_reference').val(response.data.rfa_reference);
        $('#verify_file_id').val(recordId);
        $('#item_code').val(response.data.item_code);
        $('#client').val(response.data.client_name);
        $('#spread_h').val(response.data.spread_h);
        $('#spread_w').val(response.data.spread_w);
        $('#spread_l').val(response.data.spread_l);
        $('#folded_h').val(response.data.spread_h);
        $('#folded_w').val(response.data.spread_w);
        $('#folded_l').val(response.data.spread_l);
        $('#default_color_text').val(response.data.colors);
        $('#application').val(response.data.application);

        // Handle radio buttons safely
        $(`input[name="file_source"]`).prop('checked', false);
        $(`input[name="file_source"][value="${response.data.file_source}"]`).prop('checked', true);

        $(`input[name="color"]`).prop('checked', false);
        $(`input[name="color"][value="${response.data.colors}"]`).prop('checked', true);

        $(`input[name="text_specs"]`).prop('checked', false);
        $(`input[name="text_specs"][value="${response.data.text_specs}"]`).prop('checked', true);

        $('#file_link').val(response.data.file_link);
        $('#text_specs').val(response.data.text_specs);
        $('#item_description').val(response.data.file_info);
        $('#number_of_colors').val(response.data.num_of_colors);


        let imageTypes = response.data.image_type;

            if (typeof imageTypes === 'string') {
                try {
                    imageTypes = JSON.parse(imageTypes);
                } catch (e) {
                    imageTypes = [];
                }
            }

            $('input[name="image_type_no_item_code[]"]').prop('checked', false);

            if (Array.isArray(imageTypes)) {
                imageTypes.forEach((type) => {
                    $(`input[name="image_type_no_item_code[]"][value="${type}"]`).prop('checked', true);
                });
            }
   
        // Handle links
        if (response.data.file_link) {
            $('#modal_file_link').attr('href', response.data.file_link).removeClass('d-none');
        } else {
            $('#modal_file_link').addClass('d-none');
        }

        // Success SweetAlert (Toast style so it doesn't block the modal)
        Swal.fire({ 
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: 'Details loaded successfully!',
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true
        });
    },
    error: function (xhr, status, error) {
        console.error("Failed to fetch full record:", error);
        $('#verificationModal').modal('hide'); // Hide modal before showing alert
        
        Swal.fire({
            icon: 'warning',
            title: 'Error',
            text: 'Could not load details. Please Asses First!',
            confirmButtonText: 'OK'
        });
    }
});
});

$('#FileSendTable_NoItemCode').on('click', '.btn-verify', function () {
    var recordId = $(this).data('rfareference'); // rfa reference number

    // 1. CLEAR PREVIOUS DATA: Reset the form so old data doesn't linger
    $('#verificationModal_no_item_code').find('form').trigger('reset');
    $('#item_code_label_no_item_code').text('');
    $('#rfa_reference_label_no_item_code').text('');
    $('#modal_file_link').attr('href', '#').addClass('d-none');

    // 2. Show the modal immediately (optional, or show inside success)
    $('#verificationModal_no_item_code').modal('show');

    $.ajax({
        url: "/suppliedVerifiedNoItemCode/" + recordId, // Replace with your Laravel route URL
        type: "GET",
        dataType: "json",
        success: function (response) {
            console.log(response.data);

            if (!response.data) {
                console.error("No data found in response");
                $('#verificationModal_no_item_code').modal('hide');
                Swal.fire({
                    icon: 'warning',
                    title: 'No Data Found',
                    text: 'No details were found for this record.',
                    confirmButtonText: 'OK'
                });
                return;
            }
           
            // Populate text labels and inputs
            $('#item_code_label_no_item_code').text(response.data.item_code);
            $('#rfa_reference_label_no_item_code').text(response.data.rfa_reference);
            $('#rfa_reference_no_item_code').val(response.data.rfa_reference);
            $('#verify_file_id_no_item_code').val(recordId);
            $('#item_code_no_item_code').val(response.data.item_code);
            $('#client_no_item_code').val(response.data.client_name);
            $('#spread_h_no_item_code').val(response.data.spread_h);
            $('#spread_w_no_item_code').val(response.data.spread_w);
            $('#spread_l_no_item_code').val(response.data.spread_l);
            $('#folded_h_no_item_code').val(response.data.spread_h);
            $('#folded_w_no_item_code').val(response.data.spread_w);
            $('#folded_l_no_item_code').val(response.data.spread_l);
            $('#default_color_text_no_item_code').val(response.data.colors);
            $('#application_no_item_code').val(response.data.application);

            // Handle radio buttons safely
            $('input[name="file_source_no_item_code"]').prop('checked', false);
            $(`input[name="file_source_no_item_code"][value="${response.data.file_source}"]`).prop('checked', true);

            $('input[name="color_no_item_code"]').prop('checked', false);
            $(`input[name="color_no_item_code"][value="${response.data.colors}"]`).prop('checked', true);

            $('#number_of_colors_no_item_code').val(response.data.num_of_colors);

            $('input[name="text_specs_no_item_code"]').prop('checked', false);
            $(`input[name="text_specs_no_item_code"][value="${response.data.text_specs}"]`).prop('checked', true);

            $('#file_link_no_item_code').val(response.data.file_link);
            $('#text_specs_no_item_code').val(response.data.text_specs);
            $('#item_description_no_item_code').val(response.data.file_info);

            $('input[name="text_specs_no_item_code"]').prop('checked', false);
            $(`input[name="text_specs_no_item_code"][value="${response.data.text_specs}"]`).prop('checked', true);

           
            let imageTypes = response.data.image_type;

            if (typeof imageTypes === 'string') {
                try {
                    imageTypes = JSON.parse(imageTypes);
                } catch (e) {
                    imageTypes = [];
                }
            }

            $('input[name="image_type_no_item_code[]"]').prop('checked', false);

            if (Array.isArray(imageTypes)) {
                imageTypes.forEach((type) => {
                    $(`input[name="image_type_no_item_code[]"][value="${type}"]`).prop('checked', true);
                });
            }

       
            // Handle links
            const fileLink = response.data.file_link || response.file_link;
            if (fileLink) {
                $('#modal_file_link').attr('href', fileLink).removeClass('d-none');
            } else {
                $('#modal_file_link').addClass('d-none');
            }

            // Success SweetAlert (Toast style so it doesn't block the modal)
            Swal.fire({ 
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Details loaded successfully!',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true
            });
        },
        error: function (xhr, status, error) {
            console.error("Failed to fetch full record:", error);
            $('#verificationModal_no_item_code').modal('hide'); // Hide modal before showing alert
            
            Swal.fire({
                icon: 'warning',
                title: 'Error',
                text: 'Could not load details. Please Asses First!',
                confirmButtonText: 'OK'
            });
        }
    });
});

$('#FileSendTable').on('click', '.btn-specifications', function (e) { 
    // Prevent default actions or Bootstrap data-toggle triggers from breaking execution order
    e.preventDefault();

    var recordId = $(this).data('id'); 

    $.ajax({ 
        url: "/suppliedVerified/" + recordId, 
        type: "GET", 
        dataType: "json", 
        success: function (response) { 
            // Diagnostic check: check your console to confirm this logs exactly when you click!
            console.log("AJAX Success Data Received:", response); 

            // 1. Populate the hidden File ID element
            $('#spec_file_id').val(response.id || '');

            // 2. Populate standard input field elements
            $('#spec_item_code').val(response.data.item_code || '');

            // 3. Populate Spread details
            $('#spec_spread_h').val(response.data.spread_h || ''); 
            $('#spec_spread_w').val(response.data.spread_w || ''); 
            $('#spec_spread_l').val(response.data.spread_l || ''); 
            
            // 4. Populate Folded details
            $('#spec_folded_h').val(response.folded_h || ''); 
            $('#spec_folded_w').val(response.folded_w || ''); 
            $('#spec_folded_l').val(response.folded_l || ''); 

            // 5. Explicitly fire the modal execution only after target elements hold values
            $('#specificationsModal').modal('show'); 
        }, 
        error: function (xhr, status, error) { 
            console.error("AJAX Execution Failed:", error); 
            console.log("XHR Status details:", xhr.responseText);
            alert("Could not load details. Please try again."); 
        } 
    }); 
});

// $('#FileSendTable').on('click', '.btn-specifications', function () {
//     var recordId = $(this).data('id');
//     var itemCode = $(this).data('itemcode');

//     // Populate data inside the Specifications Modal
//     $('#spec_file_id').val(recordId);
//     if (itemCode) {
//         $('#item_code').val(itemCode);
//     }

//     // Trigger Specifications Modal
//     $('#specificationsModal').modal('show');
// });


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



<script>
 function fetchNotifications() {
    $.ajax({
        url: "{{ route('Notifications') }}",
        type: "GET",
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        success: function (response) {
            // Logs the complete response object (success, html, unreadCount, logs array)
            console.log('Full Response:', response);

            // Logs only the array of ArtworkLog models
            if (response.logs) {
                console.log('All Notification Records:', response.logs);
            }

            if (response.html) {
                $('#activityFeedList').html(response.html);
            }
            if (response.unreadCount !== undefined) {
                const badge = $('#fbNotificationBadge');
                badge.text(response.unreadCount > 99 ? '99+' : response.unreadCount);
                if (response.unreadCount === 0) {
                    badge.addClass('d-none');
                } else {
                    badge.removeClass('d-none');
                }
            }
        },
        error: function (xhr) {
            console.error('Error fetching notifications:', xhr);
        }
    });
}

    $(document).ready(function () {
        // Run once when page loads
        fetchNotifications();

        // Refresh notifications when bell icon trigger is clicked
        // $(document).on('click', '#fbNotificationModalTrigger', function () {
        //     fetchNotifications();
        // });
    });
</script>

@endsection