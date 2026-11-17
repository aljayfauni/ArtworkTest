@extends('adminlte::page')

@section('title', 'Dashboard')

@section('plugins.Sweetalert2', true)
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)

@section('content_header')
    {{-- <h1><i class="fas fa-solid fa-chart-line"></i> Dashboard</h1> --}}
    @can('is-admin')
    <style>
        /* .main-sidebar {  }
        .content-wrapper, .main-header { margin-left: 0 !important; } */
    </style>
    <!-- Custom CSS for Horizontal Scrolling Timeline Layout -->
    {{-- <style>
    .horizontal-timeline-wrapper {
        overflow-x: auto;
        white-space: nowrap;
        padding: 20px 10px;
        display: flex;
        align-items: flex-start;
        gap: 25px;
        scroll-behavior: smooth;
    }
    .timeline-node {
        min-width: 320px;
        max-width: 320px;
        display: inline-block;
        vertical-align: top;
        white-space: normal; /* Allows text to wrap normally inside the card */
        position: relative;
    }
    /* Horizontal connector line linking the cards */
    .timeline-node:not(:last-child)::after {
        content: '';
        position: absolute;
        top: 25px;
        left: 100%;
        width: 25px;
        height: 4px;
        background-color: #dee2e6;
        z-index: 1;
    }
    .node-icon-badge {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px auto;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        color: #fff;
        font-size: 1.2rem;
    }
</style> --}}

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

    .node-icon-badge.bg-success {
        background: #16a34a !important;
    }

    .node-icon-badge.bg-danger {
        background: #dc2626 !important;
    }

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

    .timeline-status.started {
        background: #dcfce7;
        color: #166534;
    }

    .timeline-status.stopped {
        background: #fee2e2;
        color: #991b1b;
    }

    .timeline-clock {
        color: #475569;
        font-size: 12px;
        font-weight: 700;
        white-space: nowrap;
    }

    .timeline-card-body {
        padding: 15px;
    }

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

    .timeline-date {
        color: #64748b;
        font-size: 12px;
    }

    .timeline-date strong {
        color: #334155;
    }

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

    @media (max-width: 576px) {
        .horizontal-timeline-wrapper {
            padding: 15px;
        }

        .timeline-node {
            width: 255px;
            min-width: 255px;
            flex-basis: 255px;
        }

        .item-title-area h5 {
            font-size: 16px;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .timeline-modern-card {
            transition: none;
        }
    }
    .clock-widget{
        float: right;
    }
</style>
@endcan
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <section class="content-header">
    <div class="container-fluid">
        <div class="row align-items-center justify-content-between">

            {{-- Page Title --}}
        

        </div>
    </div>
</section>
    </div>
</div>
<div class="row mr-4">
    
    

    <div class="col-12">
    
        <div class="container-fluid">
           <div class="alert role-highlight-banner border-secondary shadow-sm bg-white" style="border-left:5px solid;" >
                            <div class="d-flex align-items-center">
                                {{-- <i class="fas fa-solid fa-traffic-light fa-2x text-secondary mr-3"></i> --}}
                                <i class="fas fa-solid fa-user-gear fa-2x text-secondary mr-3"></i>
                                <div>
                                    <h5 class="mb-0 font-weight-bold text-dark">Traffic Controller</h5>
                                    <p class="mb-0 text-muted">Recieves file links from AO, and Check the requierments.</p>
                                </div>
                            </div>
                        </div>
       <div class="row">
    <div class="col-md-12">
        <div class="card card-secondary card-outline shadow-sm">
            <div class="card-header">
                <h3 class="card-title font-weight-bold text-dark">
                    <i class="fas fa-file-signature text-secondary mr-2"></i>1. Incoming Request Item
                     {{-- <i class="fas fa-file-signature text-warning mr-2"></i>1. Incoming Request &amp; Supplied File Verification Queue --}}
                </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0 " style="max-height: 400px;">
                <table id="FileSendTable" class="table table-bordered table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>Item Code</th>
                            <th>Client Name</th>
                            <th>Sender Name</th>
                            <th>RFA Reference</th>

                            {{-- <th>Sender Email</th> --}}
                            {{-- <th>Recipient Email</th> --}}
                            <th>Shared File/Link</th>
                            <th>Date Sent</th>
                            <th>Status</th>
                              <th>View</th>
                            <th>Action</th>
                            <th>Check ArtWork</th>
                           
                            <!--<th>Actions</th>-->
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
    </div>
        <div class="col-md-12">
        {{-- <div class="card card-secondary card-outline shadow-sm">
            <div class="card-header">
                <h3 class="card-title font-weight-bold text-dark">
                    <i class="fas fa-hourglass-end text-warning mr-2"></i>1. Incoming Request Item No Item Code
                     {{-- <i class="fas fa-file-signature text-warning mr-2"></i>1. Incoming Request &amp; Supplied File Verification Queue --}}
                {{-- </h3>
            </div> --}}
            <!-- /.card-header -->
            {{-- <div class="card-body table-responsive p-0 " style="max-height: 400px;">
                <table id="FileSendTable_NoItemCode" class="table table-bordered table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>Item Code</th>
                            <th>Client Name</th>
                            <th>Item Description</th>
                            <th>Sender Name</th> --}}
                            {{-- <th>Sender Email</th> --}}
                            {{-- <th>Recipient Email</th> --}}
                            {{-- <th>Shared File/Link</th>
                            <th>Date Sent</th>
                            <th>Status</th>
                            <th>Forms</th> --}}
                             {{-- <th>To Assign</th>
                             <th>View</th> --}}
                            <!--<th>Actions</th>-->
                        {{-- </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div> --}}
            <!-- /.card-body -->
        {{-- </div> --}} 
    </div>
</div>
    </div>     

        {{-- end --}}

        {{-- request form   {{ route('artwork-request.store') }} --}}

        {{-- checklist form test --}}

    </div>
</div> 

@endsection
    
@section('js')
@include('partials.modal.ao.update_supplied_status')


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
        url: "{{ route('trafficFileRecievedLink') }}",
        type: 'GET'
    },
    columns: [
        { data: 'item_code' },
        { data: 'client_name' },
        { data: 'ao_name' },
          { data: 'rfa_reference' },

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
            data: 'rfa_reference', 
            orderable: false, 
            searchable: false, 
            render: function (data) {
               
                var editUrl = "{{ route('getArtworkFileDetail', ':item_id') }}";
                editUrl = editUrl.replace(':item_id', data);

                return '<button type="button" class="btn btn-xs btn-info edit-btn" data-url="' + editUrl + '">' +
                            '<i class="fas fa-eye mr-1"></i> View Specs' +
                       '</button>';
            }
        

        },
        // NEW COLUMN: Dynamic To Assign Column
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
            <button type="button" class="btn ${rfaClass} btn-verify mr-1" data-id="${row.id}" data-rfa-reference="${row.rfa_reference}" data-client="${row.client_name ?? ''}" title="${rfaText}" ${disabled}> 
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
        // NEW COLUMN: Dynamic To Assign Column
        // {
        //     data: null,
        //     orderable: false,
        //     searchable: false,

        //     render: function(data, type, row) {
        //         let artistName = row.is_Assign_to_Artist;
        //         console.log(row);
        //         // If an artist is assigned, show their name inside a badge/text
        //         if (artistName) {
        //             return `<span class="badge badge-success font-weight-bold p-1 btn-assign open-assign-to-artist-modal" style="cursor: pointer;">
        //                         <i class="fas fa-user-check mr-1"></i>${artistName}
        //                     </span>`;
        //         }
        //         if(row.is_verified ===true || row.is_verified ==1){
        //             return `
        //                     <button type="button" 
        //                             class="btn btn-secondary btn-sm btn-assign open-assign-to-artist-modal" 
        //                             data-id="${row.item_code}" 
        //                             data-item-code="${row.item_code ?? ''}">
        //                         <i class="fas fa-file-alt mr-1"></i> Assign To Artist
        //                     </button>
        //                 `;
        //         }
        //         return '<span class="text-muted">-</span>';    

        //     // If not assigned, render the "Assign To Artist" modal trigger button
            
        //     },

            
            
        // },
//         {
//             data: null,
//             orderable: false,
//             searchable: false,

//             render: function(data, type, row) {
//                 // If an artist is assigned, show their name inside a badge/text
         
//                 if(row.is_verified ===true || row.is_verified ==1){
//                     return `
                       

//                         <a href="${"{{ route('artwork', ':id') }}".replace(':id', row.item_code)}" 
//        class="btn btn-xs btn-outline-info " 
//        data-id="${row.item_code}" 
//        target="_blank">
//         <i class="fas fa-eye mr-1"></i> Check Artwork
//     </a>

// <a href="${"{{ route('timeline', ':id') }}".replace(':id', row.item_code)}" 
//        class="btn btn-xs btn-outline-info " 
//        data-id="${row.item_code}" 
//        target="_blank">
//         <i class="fas fa-line mr-1"></i> View TimeLine
//     </a>
//                         `;
//                 }
//                 return '<span class="text-muted">-</span>';    

//             // If not assigned, render the "Assign To Artist" modal trigger button
            
//             }
//         },
        
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

$('#FileSendTable_NoItemCode').on('click', '.btn-verify', function () {
    var recordId = $(this).data('id');
  
    console.log(recordId);

    $.ajax({
        url: "/suppliedVerifiedNoItemCode/" + recordId, // Replace with your Laravel route URL
        type: "GET",
        dataType: "json",
        success: function (response) {
            console.log(response.data);
            // Populate all modal fields using response data
            // console.log(response.data.item_code)
            $('#item_code_label_no_item_code').text(response.data.item_code);
            $('#rfa_reference_label_no_item_code').text(response.data.rfa_reference);
            $('#verify_file_id_no_item_code').val(recordId);
            $('#item_code_no_item_code').val(response.data.item_code);
            $('#rfa_reference_no_item_code').val(response.data.rfa_reference);
            $('#client_no_item_code').val(response.data.client_name);
            $('#spread_h_no_item_code').val(response.data.spread_h);
            $('#spread_w_no_item_code').val(response.data.spread_w);
            $('#spread_l_no_item_code').val(response.data.spread_l);
            $('#folded_h_no_item_code').val(response.data.spread_h);
            $('#folded_w_no_item_code').val(response.data.spread_w);
            $('#folded_l_no_item_code').val(response.data.spread_l);
            $('#application_no_item_code').val(response.data.application);
            $(`input[name="file_source_no_item_code"][value="${response.data.file_source}"]`).prop('checked', true);
            $(`input[name="color_no_item_code"][value="${response.data.colors}"]`).prop('checked', true);
            $(`input[name="color_no_item_code"][value="${response.data.colors}"]`).prop('checked', true);
            $(`input[name="text_specs_no_item_code"][value="${response.data.text_specs}"]`).prop('checked', true);
            $('#file_link_no_item_code').val(response.data.file_link);
            $('#default_color_text_no_item_code').val(response.data.colors);
            $('#text_specs_no_item_code').val(response.data.text_specs);
            $('#item_description_no_item_code').val(response.data.file_info);
       
      
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
    $('#verificationModal_no_item_code').modal('show');
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