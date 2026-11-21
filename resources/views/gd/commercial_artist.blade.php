@extends('adminlte::page')

@section('title', 'Dashboard')

@section('plugins.Sweetalert2', true)
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)

@section('content_header')
<style>
    /* Modern Workbench Styling matching Timeline View */
    .workbench-wrapper {
        background: #f8fafc;
        padding: 24px;
        border-radius: 16px;
        min-height: calc(100vh - 120px);
    }

    .modern-banner {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-left: 5px solid #16a34a;
        border-radius: 14px;
        padding: 20px 24px;
        box-shadow: 0 3px 12px rgba(15, 23, 42, 0.04);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 24px;
    }

    .banner-icon-box {
        width: 50px;
        height: 50px;
        border-radius: 14px;
        background: #dcfce7;
        color: #16a34a;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }

    .modern-user-pill {
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        color: #334155;
        padding: 8px 14px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .modern-user-pill .status-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #16a34a;
    }

    /* Modern Cards */
    .modern-work-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        box-shadow: 0 4px 16px rgba(15, 23, 42, 0.04);
        margin-bottom: 28px;
        overflow: hidden;
    }

    .modern-card-header {
        padding: 18px 24px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        background: #ffffff;
    }

    .card-title-group {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .card-header-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
    }

    .card-header-icon.primary-green {
        background: #dcfce7;
        color: #166534;
    }

    .card-header-icon.companion-indigo {
        background: #e0e7ff;
        color: #4338ca;
    }

    .modern-card-title {
        margin: 0;
        font-size: 16px;
        font-weight: 700;
        color: #1e293b;
    }

    .modern-card-subtitle {
        margin: 0;
        font-size: 12px;
        color: #64748b;
    }

    /* Clean Table Styling */
    .modern-table-wrapper {
        padding: 16px 24px 24px;
    }

    table.modern-datatable {
        width: 100% !important;
        border-collapse: separate;
        border-spacing: 0;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        overflow: hidden;
    }

    table.modern-datatable thead th {
        background: #f8fafc !important;
        color: #475569 !important;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 14px 16px !important;
        border-bottom: 1px solid #e2e8f0 !important;
        border-top: none !important;
    }

    table.modern-datatable tbody td {
        padding: 14px 16px !important;
        vertical-align: middle !important;
        color: #334155;
        font-size: 13.5px;
        border-bottom: 1px solid #f1f5f9 !important;
        background: #ffffff;
        transition: background-color 0.15s ease;
    }

    table.modern-datatable tbody tr:hover td {
        background: #f8fafc !important;
    }

    table.modern-datatable tbody tr:last-child td {
        border-bottom: none !important;
    }

    /* DataTables Search & Pagination Polish */
    .modern-table-wrapper .dataTables_filter input {
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        padding: 5px 12px;
        font-size: 13px;
        color: #1e293b;
        outline: none;
        transition: border-color 0.2s;
    }

    .modern-table-wrapper .dataTables_filter input:focus {
        border-color: #16a34a;
        box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.1);
    }

    .modern-table-wrapper .dataTables_length select {
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        padding: 4px 8px;
        font-size: 13px;
    }

    @media (max-width: 576px) {
        .workbench-wrapper {
            padding: 14px;
        }
        .modern-card-header, .modern-table-wrapper {
            padding: 14px;
        }
    }
</style>

@can('is-admin')
{{-- Preserved Admin Timeline CSS --}}
<style>
    .horizontal-timeline-wrapper {
        background: #f8fafc;
        padding: 24px;
        border-radius: 16px;
    }
    .item-timeline-lane + .item-timeline-lane {
        border-top: 1px solid #e2e8f0;
        padding-top: 24px;
        margin-top: 12px;
    }
    .item-lane-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 18px;
    }
    .item-title-area {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .item-title-icon {
        width: 46px;
        height: 46px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #e0e7ff;
        color: #4338ca;
        font-size: 19px;
    }
    .item-code-badge {
        background: #e2e8f0;
        color: #475569;
        padding: 7px 12px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
    }
    .horizontal-scroll-lane {
        display: flex;
        flex-wrap: nowrap;
        align-items: stretch;
        gap: 24px;
        overflow-x: auto;
        padding: 18px 5px 24px;
        scrollbar-width: thin;
        scrollbar-color: #cbd5e1 transparent;
    }
    .timeline-node {
        width: 275px;
        min-width: 275px;
        flex: 0 0 275px;
        position: relative;
        padding-top: 17px;
    }
    .timeline-node::before {
        content: "";
        position: absolute;
        top: 7px;
        left: 0;
        right: -24px;
        height: 2px;
        background: #cbd5e1;
    }
    .timeline-node:last-child::before {
        right: 50%;
    }
    .node-icon-badge {
        position: absolute;
        z-index: 2;
        top: 0;
        left: 16px;
        width: 16px;
        height: 16px;
        border: 4px solid #f8fafc;
        box-sizing: content-box;
        border-radius: 50%;
    }
    .node-icon-badge.bg-success { background: #16a34a !important; }
    .node-icon-badge.bg-danger { background: #dc2626 !important; }
    .timeline-modern-card {
        height: 100%;
        overflow: hidden;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        box-shadow: 0 3px 12px rgba(15, 23, 42, .05);
        transition: transform .2s ease, box-shadow .2s ease;
    }
    .timeline-modern-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(15, 23, 42, .09);
    }
    .timeline-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 8px;
        padding: 15px;
        border-bottom: 1px solid #f1f5f9;
    }
    .timeline-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 9px;
        border-radius: 7px;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: .4px;
    }
    .timeline-status.started { background: #dcfce7; color: #166534; }
    .timeline-status.stopped { background: #fee2e2; color: #991b1b; }
    .timeline-clock {
        color: #475569;
        font-size: 12px;
        font-weight: 700;
        white-space: nowrap;
    }
    .timeline-card-body { padding: 15px; }
    .timeline-user-box {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 11px;
        margin-bottom: 14px;
        border-radius: 10px;
        background: #f8fafc;
        border: 1px solid #f1f5f9;
    }
    .timeline-user-avatar {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: #e2e8f0;
        color: #475569;
    }
    .timeline-user-name {
        color: #1e293b;
        font-size: 13px;
        font-weight: 700;
        overflow-wrap: anywhere;
    }
    .timeline-user-id {
        display: block;
        margin-top: 3px;
        color: #94a3b8;
        font-size: 11px;
    }
    .timeline-date { color: #64748b; font-size: 12px; }
    .timeline-date strong { color: #334155; }
    .timeline-duration {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 14px;
        padding: 10px;
        border-radius: 9px;
        background: #eef2ff;
        color: #4338ca;
        font-size: 12px;
        font-weight: 600;
    }
    .timeline-empty {
        text-align: center;
        padding: 50px 15px;
        color: #94a3b8;
    }
    .timeline-empty i {
        display: block;
        font-size: 34px;
        margin-bottom: 12px;
    }
    .clock-widget { float: right; }
</style>
@endcan
@stop

@section('content')
<div class="container-fluid pt-3 pb-4">
    <div class="workbench-wrapper">

        <!-- Modern Header Banner -->
        <div class="modern-banner">
            <div class="d-flex align-items-center" style="gap: 16px;">
                <div class="banner-icon-box">
                    <i class="fas fa-paint-brush"></i>
                </div>
                <div>
                    <h4 class="mb-1 font-weight-bold" style="color: #0f172a; font-size: 19px;">
                        Graphic Artist Workbench & Time Tracker
                    </h4>
                    <p class="mb-0" style="color: #64748b; font-size: 13.5px;">
                        Receive assigned artwork &bull; Click tracked file links to alert Supervisors and update live status
                    </p>
                </div>
            </div>

            <div class="modern-user-pill">
                <span class="status-dot"></span>
                <span style="color: #64748b; font-weight: 500;">Artist:</span>
                <span>{{ Auth::user()->name }}</span>
            </div>
        </div>

        <!-- Primary Artwork Card -->
        <div class="modern-work-card">
            <div class="modern-card-header">
                <div class="card-title-group">
                    <div class="card-header-icon primary-green">
                        <i class="fas fa-layer-group"></i>
                    </div>
                    <div>
                        <h3 class="modern-card-title">My Assigned Artwork</h3>
                        <p class="modern-card-subtitle">Primary tasks directly assigned to your queue</p>
                    </div>
                </div>
                <span class="badge" style="background: #dcfce7; color: #166534; padding: 7px 12px; border-radius: 8px; font-weight: 700; font-size: 11px;">
                    <i class="fas fa-user-check mr-1"></i> LEAD ARTIST
                </span>
            </div>

            <div class="modern-table-wrapper table-responsive">
                <table id="MyArtworkList" class="table modern-datatable align-middle">
                    <thead>
                        <tr>
                            <th>Item Code #</th>
                            <th>Client Name</th>
                            <th>Sender (AO)</th>
                            <th>File Info & Brief</th>
                            <th>File Access Link</th>
                            <th>Status</th>
                            <th style="min-width: 240px;">View Artwork</th>
                        </tr>
                    </thead>
                    <tbody id="artistTaskTableBody">
                        <!-- Dynamic Artist Work Table -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Companion Artwork Card -->
        <div class="modern-work-card mb-0">
            <div class="modern-card-header">
                <div class="card-title-group">
                    <div class="card-header-icon companion-indigo">
                        <i class="fas fa-users"></i>
                    </div>
                    <div>
                        <h3 class="modern-card-title">Companion Artwork</h3>
                        <p class="modern-card-subtitle">Collaborative tasks where you are assisting as a companion artist</p>
                    </div>
                </div>
                <span class="badge" style="background: #e0e7ff; color: #4338ca; padding: 7px 12px; border-radius: 8px; font-weight: 700; font-size: 11px;">
                    <i class="fas fa-hands-helping mr-1"></i> COMPANION
                </span>
            </div>

            <div class="modern-table-wrapper table-responsive">
                <table id="MyArtworkListCompanion" class="table modern-datatable align-middle">
                    <thead>
                        <tr>
                            <th>Item Code #</th>
                            <th>Client Name</th>
                            <th>Sender (AO)</th>
                            <th>File Info & Brief</th>
                            <th>File Access Link</th>
                            <th>Status</th>
                            <th style="min-width: 240px;">View Artwork</th>
                        </tr>
                    </thead>
                    <tbody id="artistTaskTableBody2">
                        <!-- Dynamic Artist Work Table -->
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>


        {{-- end --}}

        {{-- request form   {{ route('artwork-request.store') }} --}}
        @include('partials.modal.supplied_file_verification')
        {{-- specifications form  {{ route('specifications.store') }} --}}
         @include('partials.modal.specifications')
        {{-- end specification --}}

        {{-- checklist form test --}}

            @can('read-only')
          <!-- A. CONTROL PANEL (Start a new task) -->

@endcan

@include('partials.modal.item_search')

    </div>
</div> 

@endsection
    
@section('js')
{{-- @include('partials.modal.gd.assign_to_artist') --}}

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
       

//fetch fileSend Table

// var FileSendLinkTable = $('#FileSendTable').DataTable({
//     responsive: true,
//     lengthChange: true,
//     autoWidth: false,
//     serverSide: true,
//     processing: true,
//     order: [[4, 'desc']],
//     // Custom DOM layout: places length menu & search on top, table in middle, info & pagination at bottom
//     dom: '<"row p-3 align-items-center"<"col-md-6"l><"col-md-6"f>>' +
//          '<"row"<"col-md-12"tr>>' +
//          '<"row p-3 align-items-center"<"col-md-5"i><"col-md-7"p>>',
         
//     language: {
//         search: "_INPUT_",
//         searchPlaceholder: "Search records...",
//         lengthMenu: "Show _MENU_ entries",
//         processing: '<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>'
//     },
//     ajax: {
//         url: "{{ route('FileRecievedLinkShowAll') }}",
//         type: 'GET'
//     },
//     columns: [
//         { data: 'item_code' },
//         { data: 'client_name' },
//         { data: 'ao_name' },

//         // Shared File / Link Column (Styled as Inspect Link Button)
//         {
//             data: 'file_link',
//             render: function(data, type, row) {
//                 if (!data) return '<span class="text-muted">N/A</span>';

//                 return `
//                     <a href="${data}" 
//                        class="btn btn-xs btn-outline-info file-link" 
//                        data-id="${row.id}" 
//                        target="_blank">
//                         <i class="fas fa-external-link-alt mr-1"></i> Inspect Link
//                     </a>
//                 `;
//             }
//         },
//         { data: 'created_at' },

//         // Restored Original Status Column
//         { data: 'status' },

//         // Restored Original Action Buttons (Verify & Specs)
//         {
//             data: null,
//             orderable: false,
//             searchable: false,
//             render: function(data, type, row) {
//                 // 1. Verification Button logic
//                 let isVerified = row.is_verified;
//                 let btnClass = isVerified ? "btn-success" : "btn-secondary";
//                 let btnText  = isVerified ? "Supplied Verified" : "Supplied File";
//                 let btnIcon  = isVerified ? "fa-check" : "fa-file-alt";
//                 let disabled = isVerified ? "disabled" : "";

//                 if(!isVerified){
//                     alert(row.is_verified);
//                 }
//                 // 2. Specifications Button logic (Dynamic check)
//                 // ALERT: Replace 'has_specifications' with the actual variable key sent from your Laravel Controller
//                 let is_specification_exist = row.is_specification_exist; 
//                 // console.log("The row data: " + JSON.stringify(row, null, 2));
//                 let specClass = is_specification_exist ? "btn-success" : "btn-success";
//                 let specText  = is_specification_exist ? "Specifications" : "Specifications";
//                 let specIcon  = is_specification_exist ? "fa-check" : "fa-check-alt";
//                  let disabled2 = is_specification_exist ? "disabled" : "";

//                return `
//     <div class="d-inline-flex align-items-center" style="gap: 6px;">
//         <!-- Verify Button -->
//         <button type="button" 
//             class="btn ${btnClass} btn-xs d-inline-flex align-items-center font-weight-bold" 
//             style="padding: 2px 6px; font-size: 11px; gap: 4px; border-radius: 3px;"
//             data-id="${row.id}" 
//             data-client="${row.client_name ?? ''}"
//             title="${btnText}"
//             ${disabled}>
//             <i class="fas ${btnIcon}" style="font-size: 10px;"></i> 
//             <span>${btnText}</span>
//         </button>
        
//         <!-- Specifications Button -->
//         <button type="button" 
//             class="btn ${specClass} btn-xs d-inline-flex align-items-center font-weight-bold" 
//             style="padding: 2px 6px; font-size: 11px; gap: 4px; border-radius: 3px;"
//             data-id="${row.id}" 
//             data-itemcode="${row.item_code ?? ''}"
//             title="${specText}"  
//             ${disabled}>
//             <i class="fas ${specIcon}" style="font-size: 10px;"></i> 
//             <span>${specText}</span>
//         </button>
//     </div>
// `;

//             }
//         }
//     ]
// });


var FileSendLinkTable = $('#MyArtworkList').DataTable({
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
        url: "{{ route('getAllMyArtworkList') }}",
        type: 'GET'
    },
    columns: [
        { data: 'item_code' },
        { data: 'client_name' },
         { data: 'ao_name' },
        { data:'item_description' },
        // { data: 'ao_name' },

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

         { data: 'status' },
        // { data: 'created_at' },
// {
//     data: null,
//     orderable: false,
//     searchable: false,
//     render: function(data, type, row) {
//         const id = row.item_code;
//         const storageKey = `dt_timer_${id}`;
        
//         // Initialize global tracking structures if missing
//         window.tableTimers = window.tableTimers || {};

//         // Define persistent state engine handlers once globally
//         if (!window.timerHandlersRegistered) {
            
//             // Helper to get state safely from localStorage
//             window.getTimerState = function(itemId) {
//                 const saved = localStorage.getItem(`dt_timer_${itemId}`);
//                 return saved ? JSON.parse(saved) : { running: false, accumulated: 0, startTime: null };
//             };

//             // Helper to save state to localStorage
//             window.setTimerState = function(itemId, state) {
//                 localStorage.setItem(`dt_timer_${itemId}`, JSON.stringify(state));
//             };

//             window.startRowTimer = function(itemId) {
//                 let state = window.getTimerState(itemId);
//                 if (state.running && window.tableTimers[itemId]) return; // Already actively ticking

//                 if (!state.running) {
//                     state.running = true;
//                     state.startTime = Date.now();
//                     window.setTimerState(itemId, state);
//                 }

//                 const displayEl = document.getElementById(`display-${itemId}`);
                
//                 window.tableTimers[itemId] = setInterval(() => {
//                     const currentState = window.getTimerState(itemId);
//                     if (!currentState.running) return;

//                     // Calculate true elapsed time based on system wall-clock difference
//                     const elapsedMs = Date.now() - currentState.startTime;
//                     const totalSeconds = Math.floor((currentState.accumulated + elapsedMs) / 1000);
                    
//                     let hrs = Math.floor(totalSeconds / 3600);
//                     let mins = Math.floor((totalSeconds % 3600) / 60);
//                     let secs = totalSeconds % 60;
                    
//                     const pad = (val) => String(val).padStart(2, '0');
//                     if (displayEl) {
//                         displayEl.innerText = `${pad(hrs)}:${pad(mins)}:${pad(secs)}`;
//                     }
//                 }, 1000);
//             };

//             window.pauseRowTimer = function(itemId) {
//                 clearInterval(window.tableTimers[itemId]);
//                 window.tableTimers[itemId] = null;

//                 let state = window.getTimerState(itemId);
//                 if (state.running) {
//                     state.accumulated += (Date.now() - state.startTime);
//                     state.running = false;
//                     state.startTime = null;
//                     window.setTimerState(itemId, state);
//                 }
//             };

//             window.stopRowTimer = function(itemId) {
//                 clearInterval(window.tableTimers[itemId]);
//                 window.tableTimers[itemId] = null;
//                 localStorage.removeItem(`dt_timer_${itemId}`);
                
//                 const displayEl = document.getElementById(`display-${itemId}`);
//                 if (displayEl) {
//                     displayEl.innerText = "00:00:00";
//                 }
//             };

//             window.timerHandlersRegistered = true;
//         }

//         // --- RENDER TIME COMPUTE LAYER ---
//         // Instantly read standard persistent state to render initial plain HTML correctly
//         const savedState = window.getTimerState(id);
//         let totalRenderSeconds = Math.floor(savedState.accumulated / 1000);

//         if (savedState.running && savedState.startTime) {
//             const currentSessionElapsed = Date.now() - savedState.startTime;
//             totalRenderSeconds = Math.floor((savedState.accumulated + currentSessionElapsed) / 1000);
            
//             // Auto-trigger interval loop loop on row re-render/load if state is running
//             setTimeout(() => window.startRowTimer(id), 50);
//         }

//         let hrs = Math.floor(totalRenderSeconds / 3600);
//         let mins = Math.floor((totalRenderSeconds % 3600) / 60);
//         let secs = totalRenderSeconds % 60;
//         const pad = (val) => String(val).padStart(2, '0');
//         const initialFormattedTime = `${pad(hrs)}:${pad(mins)}:${pad(secs)}`;

//         // Build AdminLTE styled template elements
//         return `
//             <div class="d-flex flex-column" style="gap: 6px;">
//                 <div class="d-flex align-items-center justify-content-between p-1 bg-light border rounded">
//                     <span class="badge badge-dark font-weight-bold p-2" id="display-${id}" style="font-size: 14px; min-width: 75px;">
//                         ${initialFormattedTime}
//                     </span>
//                     <div class="btn-group btn-group-toggle ml-2">
//                         <button type="button" class="btn btn-xs btn-success" onclick="startRowTimer('${id}')" title="Start">
//                             <i class="fas fa-play"></i>
//                         </button>
//                         <button type="button" class="btn btn-xs btn-warning" onclick="pauseRowTimer('${id}')" title="Pause">
//                             <i class="fas fa-pause"></i>
//                         </button>
//                         <button type="button" class="btn btn-xs btn-danger" onclick="stopRowTimer('${id}')" title="Stop/Reset">
//                             <i class="fas fa-stop"></i>
//                         </button>
//                     </div>
//                 </div>

//             </div>
//         `;
//     }
// },

        //view artwork 
        {
            data: null,
            orderable: false,
            searchable: false,

            render: function(data, type, row) {
                // If an artist is assigned, show their name inside a badge/text
               
            return `
                <a href="${"{{ route('artwork', ':id') }}".replace(':id', row.item_code)}" 
                    class="btn btn-xs btn-outline-info " 
                    data-id="${row.item_code}" 
                    target="_blank">
                    <i class="fas fa-eye mr-1"></i> View Artwork
                </a>
                
                <a href="${"{{ route('timeline', ':id') }}".replace(':id', row.item_code)}" 
                class="btn btn-xs btn-outline-info " 
                data-id="${row.item_code}" 
                target="_blank">
                    <i class="fas fa-eye mr-1"></i> View TimeLine
                </a>
                `;
            
            }
        },
        
    ]
});

var FileSendLinkTableCompanion = $('#MyArtworkListCompanion').DataTable({
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
        url: "{{ route('getAllMyArtworkCompanion') }}",
        type: 'GET'
    },
    columns: [
        { data: 'item_code' },
        { data: 'client_name' },
         { data: 'ao_name' },
        { data:'item_description' },
        // { data: 'ao_name' },

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

         { data: 'status' },


        //view artwork 
        {
            data: null,
            orderable: false,
            searchable: false,

            render: function(data, type, row) {
                // If an artist is assigned, show their name inside a badge/text
               
            return `
                <a href="${"{{ route('artwork', ':id') }}".replace(':id', row.item_code)}" 
                    class="btn btn-xs btn-outline-info " 
                    data-id="${row.item_code}" 
                    target="_blank">
                    <i class="fas fa-eye mr-1"></i> View Artwork
                </a>
                
                <a href="${"{{ route('timeline', ':id') }}".replace(':id', row.item_code)}" 
                class="btn btn-xs btn-outline-info " 
                data-id="${row.item_code}" 
                target="_blank">
                    <i class="fas fa-eye mr-1"></i> View TimeLine
                </a>
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


$('#FileSendTableCompanion').on('click', '.file-link', function(e) {
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
    var UsersTable1 = $('#UsersTable1').DataTable({
        "responsive": true,
        "lengthChange": true,
        "autoWidth": false,
        "serverSide": true,
        "processing": true,
        "ajax": {
            "url": '{{ route('timeline.showAll') }}',
            "type": 'GET'
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
                } else if (statusLower === 'pending' || statusLower === 'in progress') {
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
        
    var UsersTable = $('#UsersTable').DataTable({
        "responsive": true,
        "lengthChange": true,
        "autoWidth": false,
        "serverSide": true,
        "processing": true,
        "ajax": {
            "url": '{{ route('timeline.showAll') }}',
            "type": 'GET'
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
            var rawData = api.rows({ page: 'current' }).data().toArray();
            var $mainContainer =$('#itemTimelinesContainer');
            
            $mainContainer.empty();

            if (rawData.length === 0) {
                $mainContainer.html('<div class="text-center p-4 text-muted"><i class="fas fa-info-circle"></i> No timeline records found.</div>');
                return;
            }

            // Group records by item_code
            var groupedItems = {};
            rawData.forEach(function(row) {
                var code = row.item_code || 'Unassigned Item';
                if (!groupedItems[code]) {
                    groupedItems[code] = [];
                }
                groupedItems[code].push(row);
            });

            // Loop through each Item Code group and create a separate card
            $.each(groupedItems, function(itemCode, events) {
                
                // Build horizontal timeline steps for this specific item
                var stepsHtml = '';
                events.forEach(function(event, index) {
                    var statusClass = 'bg-secondary';
                    var statusIcon = 'fa-clock';
                    var statusLower = event.status ? event.status.toLowerCase() : '';

                    if (['completed', 'success', 'active'].includes(statusLower)) {
                        statusClass = 'bg-success';
                        statusIcon = 'fa-check';
                    } else if (['pending', 'in progress'].includes(statusLower)) {
                        statusClass = 'bg-warning';
                        statusIcon = 'fa-sync-alt';
                    } else if (['failed', 'cancelled'].includes(statusLower)) {
                        statusClass = 'bg-danger';
                        statusIcon = 'fa-times';
                    }

                    stepsHtml += `
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="card h-100 border">
                                <div class="card-header py-1 bg-light d-flex justify-content-between align-items-center">
                                    <small class="font-weight-bold">Step ${index + 1}</small>
                                    <span class="badge ${statusClass}">${event.status || 'N/A'}</span>
                                </div>
                                <div class="card-body p-2">
                                    <small class="d-block text-muted mb-1">
                                        <i class="fas fa-user mr-1"></i> User ID: <strong>#${event.user_id}</strong>
                                    </small>
                                    <small class="d-block text-muted mb-1">
                                        <i class="far fa-clock mr-1"></i> Start: ${event.start_time || 'N/A'}
                                    </small>
                                    <small class="d-block text-muted">
                                        <i class="fas fa-stopwatch mr-1"></i> Stop: ${event.stop_time || 'N/A'}
                                    </small>
                                </div>
                                <div class="card-footer py-1 text-right bg-white">
                                    <small class="text-muted">ID: #${event.id}</small>
                                </div>
                            </div>
                        </div>
                    `;
                });

                // Parent Card layout for individual Item Code
                var itemCardHtml = `
                    <div class="card card-outline card-info mb-4">
                        <div class="card-header">
                            <h3 class="card-title text-bold">
                                <i class="fas fa-box mr-1"></i> Item Code: <span class="text-primary">${itemCode}</span>
                            </h3>
                            <div class="card-tools">
                                <span class="badge badge-info">${events.length} Events</span>
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body bg-light">
                            <!-- Horizontal row of activity cards per item -->
                            <div class="row align-items-stretch">
                                ${stepsHtml}
                            </div>
                        </div>
                    </div>
                `;

                $mainContainer.append(itemCardHtml);
            });
        }
    });



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
    var recordId = $(this).data('id');
  
    console.log($(this).data());

    $.ajax({
        url: "/suppliedVerified/" + recordId, // Replace with your Laravel route URL
        type: "GET",
        dataType: "json",
        success: function (response) {
            console.log(response.data);
            // Populate all modal fields using response data
            // console.log(response.data.item_code)
             $('#item_code_label').text(response.data.item_code);
           $('#verify_file_id').val(recordId);
             $('#item_code').val(response.data.item_code);
              $('#client').val(response.data.id);
              $('#spread_h').val(response.data.spread_h);
              $('#spread_w').val(response.data.spread_w);
              $('#spread_l').val(response.data.spread_l);
              $('#folded_h').val(response.data.spread_h);
              $('#folded_w').val(response.data.spread_w);
              $('#folded_l').val(response.data.spread_l);
               $('#default_color_text').val(response.data.colors);
               $('#text_specs').val(response.data.text_specs);
              $('#item_description').val(response.data.file_info);
       
      
            // $('#item_description').val(item_description);
            
            // Handle links or custom elements
            if (response.file_link) {
                $('#modal_file_link').attr('href', response.file_link).removeClass('d-none');
            } else {
                $('#modal_file_link').addClass('d-none');
            }

            // If you have nested relations, access them directly:
            // $('#modal_client_address').val(response.client ? response.client.address : '');
        },
        error: function (xhr, status, error) {
            console.error("Failed to fetch full record:", error);
            alert("Could not load details. Please try again.");
        }
    });
    // Populate data inside the modal form
    

    // Show the modal
    $('#verificationModal').modal('show');
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