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
            

                     {{-- supplied file verifications --}}
                <div class="card card-warning card-outline shadow-sm">
                    <div class="card-header">
                        <h3 class="card-title font-weight-bold text-dark">
                            <i class="fas fa-check-double text-warning mr-2"></i> GD Supervisor Supplied File Verification Pending AO Approval
                        </h3>
                    </div>
                    <div class="card-body table-responsive p-0" style="max-height: 300px;">
                        <table class="table table-hover" id="aoApprovalTableBody"> 
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Item Code</th>
                                    <th>Client</th>
                                    <th>Rfa Reference</th>
                                    <th>Item Description</th>
                                    {{-- <th>File Link</th> --}}
                                    <th>Status</th>
                                    <th>Date Created</th>
                                    {{-- <th>Ao Verified</th> --}}
                                    {{-- <th>Action</th> --}}


                                </tr>
                            </thead>
                            <tbody ><tr><td colspan="12" class="text-center text-muted py-3">No Item pending AO approval.</td></tr></tbody>
                        </table>
                    </div>
                </div> 



        </div>
</div> 

@endsection

@section('js')
@include('partials.modal.ao.update_supplied_status')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
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
        url: "{{ route('getSuppliedVeried') }}",
        type: 'GET'
    },
    columns: [
        { data: 'id' },
        { 
            data: 'item_code',
            render: function(data) {
                return '<span class="font-weight-bold">' + (data || '') + '</span>';
            }
        },
        { data: 'client' },
        { data: 'rfa_number' },
        { data: 'item_description' },
        // { 
        //     data: 'file_link',
        //     render: function(data) {
        //         if (!data) return '<span class="text-muted">No Link</span>';
        //         return '<a href="' + data + '" target="_blank" class="btn btn-xs btn-outline-primary">' +
        //                     '<i class="fas fa-external-link-alt mr-1"></i> View Link' +
        //                '</a>';
        //     }
        // },
      
       { 
    data: 'status',
    render: function(data, type, row) {
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

        var itemCode = row.item_code || '';

        return '<span class="badge ' + badgeClass + ' p-1 open-status-modal" style="cursor: pointer;" ' +
               'data-item-code="' + itemCode + '" ' +
               'data-status="' + data + '">' +
                   '<i class="fas ' + iconClass + ' mr-1"></i>' + data +
               '</span>';
    }
},
          { data: 'created_at' },
        // {
        //     data: 'item_id', 
        //     orderable: false, 
        //     searchable: false, 
        //     render: function (data) {
        //         var editUrl = "{{ route('getArtworkFileDetail', ':item_id') }}";
        //         editUrl = editUrl.replace(':item_id', data);

        //         return '<button type="button" class="btn btn-xs btn-info edit-btn" data-url="' + editUrl + '">' +
        //                     '<i class="fas fa-edit mr-1"></i> Edit' +
        //                '</button>';
        //     }
        // }
    ]
});


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