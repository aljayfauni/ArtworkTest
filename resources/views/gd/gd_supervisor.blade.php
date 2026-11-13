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
           <div class="alert role-highlight-banner border-warning shadow-sm bg-white" style="border-left:5px solid;" >
                            <div class="d-flex align-items-center">
                                <i class="fas fa-user-shield fa-2x text-warning mr-3"></i>
                                <div>
                                    <h5 class="mb-0 font-weight-bold text-dark">GD Supervisor</h5>
                                    <p class="mb-0 text-muted">Recieves file links from AO, Submits Supplied Verification Form, Assigns Primary & Companion Graphic Artists , Monitors activity.</p>
                                </div>
                            </div>
                        </div>
       <div class="row">
    <div class="col-md-12">
        <div class="card card-warning card-outline shadow-sm">
            <div class="card-header">
                <h3 class="card-title font-weight-bold text-dark">
                    <i class="fas fa-file-signature text-warning mr-2"></i>1. Incoming Request Item
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
                            {{-- <th>Sender Email</th> --}}
                            {{-- <th>Recipient Email</th> --}}
                            <th>Shared File/Link</th>
                            <th>Date Sent</th>
                            <th>Status</th>
                            <th>Forms</th>
                             <th>To Assign</th>
                             <th>View</th>
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
        <div class="card card-secondary card-outline shadow-sm">
            <div class="card-header">
                <h3 class="card-title font-weight-bold text-dark">
                    <i class="fas fa-hourglass-end text-warning mr-2"></i>1. Incoming Request Item No Item Code
                     {{-- <i class="fas fa-file-signature text-warning mr-2"></i>1. Incoming Request &amp; Supplied File Verification Queue --}}
                </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0 " style="max-height: 400px;">
                <table id="FileSendTable_NoItemCode" class="table table-bordered table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>Item Code</th>
                            <th>Client Name</th>
                            <th>Item Description</th>
                            <th>Sender Name</th>
                            {{-- <th>Sender Email</th> --}}
                            {{-- <th>Recipient Email</th> --}}
                            <th>Shared File/Link</th>
                            <th>Date Sent</th>
                            <th>Status</th>
                            <th>Forms</th>
                             {{-- <th>To Assign</th>
                             <th>View</th> --}}
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

            @can('read-only')
          <!-- A. CONTROL PANEL (Start a new task) -->
            <div class="card card-primary card-outline shadow-sm">
                <div class="card-header bg-light">
                    <h3 class="card-title font-weight-bold"><i class="fas fa-play-circle text-success mr-2"></i> Search Item</h3>
                </div>
                <div class="card-body p-3">
                    <form id="CreateForm">
                        <div class="row">
                            <!-- Column 1: Personnel -->
                            <div class="col-md-6" id="operator_section">
                                {{-- <div class="form-group mb-2">
                                    <label class="mb-1 text-sm">Operator: <span class="text-danger">*</span></label>
                                    <select id="operator_id" name="operator_id[]" class="form-control form-control-sm select2bs4" multiple required> --}}
                                        {{-- <option value="">Select Operator</option> --}}
                                    {{-- </select> --}}
                                {{-- </div> --}}
                                {{-- <div class="form-group mb-0">
                                    <label class="mb-1 text-sm">Companions:</label>
                                    <select id="asst_operator_id" name="asst_operator_id[]" class="form-control form-control-sm select2bs4" multiple>
                                        {{-- <option value="">Select Assistant</option> --}}
                                  
                                {{-- </div>  --}}

                            <div class="row align-items-end mb-3">
                            <div class="col-md-12">
                                <div class="form-group mb-0">
                                    <label class="mb-1 text-sm" id="target_label"></label>
                                    <div class="d-flex align-items-center">
                                        
                                        <!-- PRODUCTION BUTTONS -->
                                        <div id="mo_jo_buttons">
                                            <!-- Search MO Button -->
                                            <button type="button" class="btn btn-secondary mr-2" onclick="showMOSearch()">
                                                <i class="fas fa-search"></i> Search Item
                                            </button>

                                            <!-- Manual JO Button -->
                                            {{-- @if($user->allow_manual_jo) --}}
                                                <button type="button" class="btn btn-outline-primary" onclick="showManualJOModal()">
                                                    <i class="fas fa-edit"></i> External / Campaign Ref
                                                </button>
                                            {{-- @endif --}}
                                        </div>

                                        <!-- DOWNTIME BUTTON (Hidden by default) -->
                                        <div id="downtime_button_wrapper" class="d-none">
                                            <button type="button" class="btn btn-danger shadow-sm font-weight-bold px-4" onclick="startDowntime()">
                                                <i class="fas fa-power-off"></i> Start Downtime
                                            </button>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                            </div>

                            <!-- Column 2: Action Setup -->
                            <div class="col-md-6 border-left">
                                <div class="form-group mb-2">
                                    <label class="mb-1 text-sm">Action Type: <span class="text-danger">*</span></label>
                                    <select class="form-control select2 select2-hidden-accessible" style="width: 100%;" data-select2-id="1" tabindex="-1" aria-hidden="true">
                                        <option selected="selected" data-select2-id="3">ArtWorkFlow</option>
                                        <option data-select2-id="35">Retouching</option>
                                        <option data-select2-id="36">Apparel</option>
                                        <option data-select2-id="37">Revision</option>
                                       
                                    </select>
                                    {{-- <div class="d-flex action-btn-group gap-2">
                                        <button type="button" class="btn btn-sm btn-success mr-1">DIRECT</button>
                                        <button type="button" class="btn btn-sm btn-warning mr-1 text-dark">NON-PROD.</button>
                                        <button type="button" class="btn btn-sm btn-danger">PROD. DOWN</button>
                                    </div> --}}
                                </div>
                                <div class="form-group mb-2">
                                    {{-- <label class="mb-1 text-sm">Action: <span class="text-danger">*</span></label>
                                    <select id="action_id" class="form-control form-control-sm select2bs4">
                                        <option value="">Select Action...</option>
                                    </select> --}}
                                </div>

                                <!-- ========================================== -->
                                <!-- INSERT NEW CONFIGURATION CONTAINER HERE    -->
                                <!-- ========================================== -->
                                <div id="job_type_container" class="form-group mt-3" style="display: none;">
                                    <label class="mb-1 text-sm">Job Type <span class="text-danger">*</span></label>
                                    <div class="d-flex align-items-center text-sm">
                                        <div class="custom-control custom-radio mr-3">
                                            <input class="custom-control-input" type="radio" id="type_new" name="job_type" value="NEW">
                                            <label for="type_new" class="custom-control-label">NEW</label>
                                        </div>
                                        
                                        {{-- Make Ready Specific --}}
                                        <div class="custom-control custom-radio mr-3" id="wrapper_reorder" style="display: none;">
                                            <input class="custom-control-input" type="radio" id="type_reorder" name="job_type" value="RE-ORDER">
                                            <label for="type_reorder" class="custom-control-label">RE-ORDER</label>
                                        </div>

                                        {{-- Running Specific --}}
                                        <div class="custom-control custom-radio" id="wrapper_replate" style="display: none;">
                                            <input class="custom-control-input" type="radio" id="type_replate" name="job_type" value="REPLATE">
                                            <label for="type_replate" class="custom-control-label">REPLATE</label>
                                        </div>
                                    </div>
                                </div>

                                {{-- Replate Reason --}}
                                <div id="replate_reason_container" class="form-group mt-2" style="display: none;">
                                    <label class="mb-1 text-sm">Replate Reason <span class="text-danger">*</span></label>
                                    <select id="replate_reason" class="form-control form-control-sm select2bs4">
                                        <option value="">Select Reason for Replating...</option>
                                        @foreach($replate_reasons ?? [] as $item)
                                            <option value="{{ $item->name }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- ========================================== -->
                                <!-- END NEW CONFIGURATION CONTAINER            -->
                                <!-- ========================================== -->
                            </div>
                        </div>

                        <hr class="my-3">

                        <!-- Bottom Row: MO Selection & Start -->
          
                    </form>
                </div>
            </div>

        <div class="row justify-content-center">
        <!-- Left Side: Profile Summary Card -->
        <div class="col-md-4 mt-5">
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                   <form action="" method="POST">
    @csrf
    
    <div class="form-group">
        <label for="select-tag">Select Item:</label>
        <select id="select-tag" name="tag_id" class="form-control select2 @error('tag_id') is-invalid @enderror" style="width: 100%;">
            <option value="">-- Choose an option --</option>
            @foreach($availableItemsForSelect as $list)
                <option value="{{ $list->item_code }}" {{ old('tag_id') == $list->item_code ? 'selected' : '' }}>
                    {{ $list->item_code }}
                </option>
            @endforeach
        </select>
        
        @error('tag_id')
            <span class="error invalid-feedback">{{ $message }}</span>
        @enderror
    </div>
</form>

                    <button id="btn_start_time"class="btn btn-secondary btn-success" tabindex="0" aria-controls="UsersTable" type="button" title="New" >
                <span>
                    <i class="fas fa-start"></i>
                    Start
                </span>
            </button>
            <button class="btn btn-secondary btn-warning" tabindex="0" aria-controls="UsersTable" type="button" title="New">
                <span>
                    <i class="fas fa"></i>
                    Pause
                </span>
            </button>
            <button id="btn_stop_time" class="btn btn-secondary btn-danger" tabindex="0" aria-controls="UsersTable" type="button" title="New">
                <span>
                    <i class="fas fa"></i>
                    Stop
                </span>
            </button>
                </div>
            </div>
        </div>

        <!-- Right Side: Detailed Profile Information -->




<!-- Modern Tracking Grid Wrapper --> 
<div class="timeline-tracking-grid d-flex flex-column gap-5 p-4" style="background: #f8fafc; font-family: system-ui, -apple-system, sans-serif;">
    @php $currentGroupCode = null; @endphp

    @forelse($timeLineList as $row)
        {{-- Every unique item code drops down neatly to its own full width row line --}}
        @if($currentGroupCode !== $row->item_code)
            @if(!$loop->first)
                        </div> <!-- End horizontal scroll step track container -->
                    </div> <!-- End lane right node container track row -->
                </div> <!-- End global item tracking block wrapper line -->
            @endif

            {{-- 1. CLEAN MODERN BLOCK ROW COMPONENT CONTAINER --}}
            <div class="asset-tracking-row-block bg-white rounded-2xl p-4 shadow-sm border border-slate-100 w-100 d-block mb-4" style="border-radius: 16px;">
                
                <!-- Minimal Row Header Title Line Layout -->
                <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom border-slate-100 w-100">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-xl d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: #eff6ff; color: #2563eb; border-radius: 12px;">
                            <i class="fas fa-cube" style="font-size: 16px;"></i>
                        </div>
                        <div>
                            <h5 class="m-0 font-weight-bold tracking-tight text-slate-900" style="font-size: 16px; color: #0f172a;">
                                {{ $row->item_name ?? 'Item Tracking Log' }}
                            </h5>
                            <span class="text-slate-400 font-weight-medium" style="font-size: 12px; color: #64748b;">Code Reference: {{ $row->item_code }}</span>
                        </div>
                    </div>
                </div>

                <!-- Horizontal Process Flow Stream -->
                <div class="item-process-lane-track w-100">
                    <div class="horizontal-step-track d-flex flex-nowrap overflow-auto pb-2 pt-3 align-items-start" style="position: relative; gap: 40px;">
                        
                        <!-- Minimal background connect rail line -->
                        <div class="process-rail-line" style="position: absolute; top: 18px; left: 24px; right: 24px; height: 2px; background: #e2e8f0; z-index: 1;"></div>
            
            @php $currentGroupCode = $row->item_code; @endphp
        @endif

        {{-- 2. MINIMALIST PLAY / START EVENT NODE --}}
        @if($row->start_time)
            <div class="process-node-card flex-shrink-0 text-left" style="width: 240px; position: relative; z-index: 2;">
                <!-- Modern Interactive Mini Circle Node Pin -->
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" 
                         style="width: 36px; height: 36px; background: #bbf7d0; color: #15803d; border: 4px solid #fff;">
                        <i class="fas fa-play" style="font-size: 10px;"></i>
                    </div>
                    <div>
                        <span class="badge font-weight-bold px-2 py-0.5" style="background: #f0fdf4; color: #16a34a; font-size: 11px; border-radius: 6px;">STARTED</span>
                    </div>
                </div>

                <!-- Translucent Modern Data Card Capsule -->
                <div class="p-3 border border-slate-100 shadow-sm transition-all" style="background: #fafafa; border-radius: 12px;">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <small class="text-slate-500 font-weight-semibold" style="font-size: 11px; color: #64748b;"><i class="far fa-clock mr-1"></i> {{ \Carbon\Carbon::parse($row->start_time)->format('h:i A') }}</small>
                        <small class="text-slate-400" style="font-size: 11px; color: #94a3b8;">{{ \Carbon\Carbon::parse($row->start_time)->format('M d, Y') }}</small>
                    </div>
                    
                    <div class="bg-white p-2 rounded border border-slate-100 d-flex align-items-center shadow-2xs" style="border-radius: 8px;">
                        <div class="bg-slate-50 text-slate-400 rounded p-1 mr-2 d-flex align-items-center justify-content-center" style="width: 24px; height: 24px; border-radius: 6px;">
                            <i class="fas fa-user-check" style="font-size: 11px;"></i>
                        </div>
                        <div style="line-height: 1.1; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                            <span class="d-block font-weight-bold text-slate-800" style="font-size: 12px; color: #334155;">{{ $row->user_name ?? 'System' }}</span>
                            <span class="text-slate-400 font-weight-medium" style="font-size: 10px; color: #94a3b8;">ID: {{ $row->user_id }}</span>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- 3. MINIMALIST STOP / PAUSE EVENT NODE --}}
        @if($row->stop_time)
            <div class="process-node-card flex-shrink-0 text-left" style="width: 240px; position: relative; z-index: 2;">
                <!-- Modern Interactive Mini Circle Node Pin -->
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" 
                         style="width: 36px; height: 36px; background: #fecaca; color: #b91c1c; border: 4px solid #fff;">
                        <i class="fas fa-stop" style="font-size: 10px;"></i>
                    </div>
                    <div>
                        <span class="badge font-weight-bold px-2 py-0.5" style="background: #fef2f2; color: #dc2626; font-size: 11px; border-radius: 6px;">STOPPED</span>
                    </div>
                </div>

                <!-- Translucent Modern Data Card Capsule -->
                <div class="p-3 border border-slate-100 shadow-sm transition-all" style="background: #fafafa; border-radius: 12px;">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <small class="text-slate-500 font-weight-semibold" style="font-size: 11px; color: #64748b;"><i class="far fa-clock mr-1"></i> {{ \Carbon\Carbon::parse($row->stop_time)->format('h:i A') }}</small>
                        <small class="text-slate-400" style="font-size: 11px; color: #94a3b8;">{{ \Carbon\Carbon::parse($row->stop_time)->format('M d, Y') }}</small>
                    </div>
                    
                    <div class="bg-white p-2 rounded border border-slate-100 d-flex align-items-center shadow-2xs mb-2" style="border-radius: 8px;">
                        <div class="bg-slate-50 text-slate-400 rounded p-1 mr-2 d-flex align-items-center justify-content-center" style="width: 24px; height: 24px; border-radius: 6px;">
                            <i class="fas fa-user-check" style="font-size: 11px;"></i>
                        </div>
                        <div style="line-height: 1.1; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                            <span class="d-block font-weight-bold text-slate-800" style="font-size: 12px; color: #334155;">{{ $row->user_name ?? 'System' }}</span>
                            <span class="text-slate-400 font-weight-medium" style="font-size: 10px; color: #94a3b8;">ID: {{ $row->user_id }}</span>
                        </div>
                    </div>

                    @if($row->start_time)
                        <div class="d-flex align-items-center justify-content-between px-1 py-1 rounded" style="background: #f1f5f9;">
                            <span class="text-uppercase tracking-wider font-weight-bold text-slate-400" style="font-size: 9px; color: #64748b;">Duration Tracked</span>
                            <span class="font-weight-bold text-slate-700" style="font-size: 11px; color: #334155;">
                                {{ \Carbon\Carbon::parse($row->start_time)->diffForHumans(\Carbon\Carbon::parse($row->stop_time), true) }}
                            </span>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        {{-- Tie together all row markup layout segments at loop termination --}}
        @if($loop->last)
                    </div> <!-- End horizontal step track container -->
                </div> <!-- End lane right node container track row -->
            </div> <!-- End global item tracking block wrapper line -->
        @endif

    @empty
        <div class="w-100 text-center p-5 bg-white border rounded-2xl shadow-xs" style="border-radius: 16px;">
            <div class="text-slate-300 mb-3"><i class="fas fa-inbox fa-2x" style="color: #cbd5e1;"></i></div>
            <p class="m-0 font-weight-bold text-slate-500" style="color: #64748b;">No horizontal timeline activity metrics captured.</p>
        </div>
    @endforelse
</div>


{{-- ------------------------------------------ --}}

<!-- Modern Circular Timeline Track Grid --> 
<div class="timeline-tracking-grid d-flex flex-column gap-5 p-4" style="background: #f8fafc; font-family: system-ui, -apple-system, sans-serif;">
    @php $currentGroupCode = null; @endphp

    @forelse($timeLineList as $row)
        {{-- Every unique item code drops down neatly to its own full width row lane --}}
        @if($currentGroupCode !== $row->item_code)
            @if(!$loop->first)
                        </div> <!-- End horizontal scroll track -->
                    </div> <!-- End lane container -->
                </div> <!-- End global item tracking block -->
            @endif

            {{-- 1. STREAMLINED ROW CONTAINER --}}
            <div class="asset-tracking-row-block bg-white p-4 shadow-sm border border-slate-100 w-100 d-block mb-5" style="border-radius: 24px;">
                
                <!-- Modern Row Header Title Layout -->
                <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom border-slate-100 w-100">
                    <div class="d-flex align-items-center gap-3">
                        <div class="d-flex align-items-center justify-content-center shadow-xs" style="width: 54px; height: 54px; background: #f1f5f9; color: #475569; border-radius: 50%;">
                            <i class="fas fa-layer-group" style="font-size: 22px;"></i>
                        </div>
                        <div>
                            <!-- Increased Main Title to h4 and font-size: 20px -->
                            <h4 class="m-0 font-weight-bold tracking-tight" style="font-size: 20px; color: #0f172a; letter-spacing: -0.3px;">
                                {{ $row->item_name ?? 'Item Tracking Log' }}
                            </h4>
                            <!-- Increased Reference Subtitle to font-size: 14px -->
                            <span class="font-weight-medium" style="font-size: 14px; color: #64748b;">Identifier: <strong class="text-primary">{{ $row->item_code }}</strong></span>
                        </div>
                    </div>
                </div>

                <!-- Horizontal Flow Lane Track -->
                <div class="item-process-lane-track w-100">
                    <div class="horizontal-step-track d-flex flex-nowrap overflow-auto pb-4 pt-4 align-items-start" style="position: relative; gap: 56px;">
                        
                        <!-- Smooth underlying tracking connection rail line (adjusted top alignment for larger circles) -->
                        <div class="process-rail-line" style="position: absolute; top: 80px; left: 60px; right: 60px; height: 4px; background: #e2e8f0; z-index: 1; border-radius: 10px;"></div>
            
            @php $currentGroupCode = $row->item_code; @endphp
        @endif

        {{-- 2. ULTRA-MODERN CIRCULAR START EVENT NODE --}}
        @if($row->start_time)
            <div class="process-node-card flex-shrink-0 text-center" style="width: 170px; position: relative; z-index: 2;">
                <!-- Main Concentric Status Circle Ring Container (Scaled up to 120px) -->
                <div class="d-flex justify-content-center mb-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center shadow" 
                         style="width: 120px; height: 120px; background: #f0fdf4; border: 4px solid #fff; transition: transform 0.2s ease;">
                        <div class="rounded-circle d-flex flex-column align-items-center justify-content-center text-success" 
                             style="width: 100px; height: 100px; background: #ffffff; border: 2px dashed #bbf7d0;">
                            <i class="fas fa-play mb-1" style="font-size: 14px;"></i>
                            <!-- Increased Badge text inside circle to font-size: 12px -->
                            <span class="font-weight-bold tracking-wider" style="font-size: 12px; letter-spacing: 0.5px;">START</span>
                        </div>
                    </div>
                </div>

                <!-- Circular Meta Information Block Details -->
                <div class="text-center px-1">
                    <!-- Increased Time font to font-size: 16px -->
                    <div class="font-weight-bold text-slate-800 mb-1" style="font-size: 16px; color: #1e293b;">{{ \Carbon\Carbon::parse($row->start_time)->format('h:i A') }}</div>
                    <!-- Increased Date font to font-size: 13px -->
                    <div class="text-slate-400 mb-2 font-weight-medium" style="font-size: 13px; color: #94a3b8;">{{ \Carbon\Carbon::parse($row->start_time)->format('M d, Y') }}</div>
                    
                    <!-- Increased User capsule sizing and font-size: 13px -->
                    <div class="inline-flex align-items-center bg-light px-3 py-2 border text-truncate w-100" style="border-radius: 20px; font-size: 13px; background: #f8fafc;" title="{{ $row->user_name ?? 'System' }}">
                        <i class="fas fa-user-circle text-muted mr-1" style="font-size: 14px;"></i>
                        <span class="text-slate-600 font-weight-bold text-truncate d-inline-block" style="max-width: 110px;">{{ $row->user_name ?? 'System' }}</span>
                    </div>
                </div>
            </div>
        @endif

        {{-- 3. ULTRA-MODERN CIRCULAR STOP EVENT NODE --}}
        @if($row->stop_time)
            <div class="process-node-card flex-shrink-0 text-center" style="width: 170px; position: relative; z-index: 2;">
                <!-- Main Concentric Status Circle Ring Container (Scaled up to 120px) -->
                <div class="d-flex justify-content-center mb-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center shadow" 
                         style="width: 120px; height: 120px; background: #fef2f2; border: 4px solid #fff; transition: transform 0.2s ease;">
                        <div class="rounded-circle d-flex flex-column align-items-center justify-content-center text-danger" 
                             style="width: 100px; height: 100px; background: #ffffff; border: 2px dashed #fecaca;">
                            <i class="fas fa-stop mb-1" style="font-size: 14px;"></i>
                            <!-- Increased Badge text inside circle to font-size: 12px -->
                            <span class="font-weight-bold tracking-wider" style="font-size: 12px; letter-spacing: 0.5px;">STOP</span>
                        </div>
                    </div>
                </div>

                <!-- Circular Meta Information Block Details -->
                <div class="text-center px-1">
                    <!-- Increased Time font to font-size: 16px -->
                    <div class="font-weight-bold text-slate-800 mb-1" style="font-size: 16px; color: #1e293b;">{{ \Carbon\Carbon::parse($row->stop_time)->format('h:i A') }}</div>
                    <!-- Increased Date font to font-size: 13px -->
                    <div class="text-slate-400 mb-2 font-weight-medium" style="font-size: 13px; color: #94a3b8;">{{ \Carbon\Carbon::parse($row->stop_time)->format('M d, Y') }}</div>
                    
                    <!-- Duration Badge Bubble Pill (Increased padding and font-size: 12px) -->
                    @if($row->start_time)
                        <div class="mb-2">
                            <span class="badge text-white font-weight-bold px-3 py-1" style="background: #475569; font-size: 12px; border-radius: 12px;">
                                <i class="fas fa-hourglass-half mr-1"></i>{{ \Carbon\Carbon::parse($row->start_time)->diffForHumans(\Carbon\Carbon::parse($row->stop_time), true) }}
                            </span>
                        </div>
                    @endif

                    <!-- Increased User capsule sizing and font-size: 13px -->
                    <div class="inline-flex align-items-center bg-light px-3 py-2 border text-truncate w-100" style="border-radius: 20px; font-size: 13px; background: #f8fafc;" title="{{ $row->user_name ?? 'System' }}">
                        <i class="fas fa-user-circle text-muted mr-1" style="font-size: 14px;"></i>
                        <span class="text-slate-600 font-weight-bold text-truncate d-inline-block" style="max-width: 110px;">{{ $row->user_name ?? 'System' }}</span>
                    </div>
                </div>
            </div>
        @endif

        {{-- Final closure tags structure generation validation check --}}
        @if($loop->last)
                    </div> <!-- End horizontal scroll track -->
                </div> <!-- End lane container -->
            </div> <!-- End global item tracking block -->
        @endif

    @empty
        <div class="w-100 text-center p-5 bg-white border shadow-xs" style="border-radius: 24px;">
            <div class="text-slate-300 mb-2"><i class="fas fa-circle-notch fa-spin fa-2x" style="color: #cbd5e1;"></i></div>
            <p class="m-0 font-weight-bold text-slate-500" style="color: #64748b; font-size: 16px;">No horizontal timeline activity metrics captured.</p>
        </div>
    @endforelse
</div>
<!-- Modern Circular Timeline Track Grid --> 
<div class="timeline-tracking-grid d-flex flex-column gap-5 p-4" style="background: #f8fafc; font-family: system-ui, -apple-system, sans-serif;">
    @php $currentGroupCode = null; @endphp

    @forelse($timeLineList as $row)
        {{-- Every unique item code drops down neatly to its own full width row lane --}}
        @if($currentGroupCode !== $row->item_code)
            @if(!$loop->first)
                        </div> <!-- End horizontal scroll track -->
                    </div> <!-- End lane container -->
                </div> <!-- End global item tracking block -->
            @endif

            {{-- 1. STREAMLINED ROW CONTAINER --}}
            <div class="asset-tracking-row-block bg-white p-4 shadow-sm border border-slate-100 w-100 d-block mb-5" style="border-radius: 24px;">
                
                <!-- Modern Row Header Title Layout -->
                <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom border-slate-100 w-100">
                    <div class="d-flex align-items-center gap-3">
                        <div class="d-flex align-items-center justify-content-center shadow-xs" style="width: 54px; height: 54px; background: #f1f5f9; color: #475569; border-radius: 50%;">
                            <i class="fas fa-layer-group" style="font-size: 22px;"></i>
                        </div>
                        <div>
                            <!-- Increased Main Title to h4 and font-size: 20px -->
                            <h4 class="m-0 font-weight-bold tracking-tight" style="font-size: 20px; color: #0f172a; letter-spacing: -0.3px;">
                                {{ $row->item_name ?? 'Item Tracking Log' }}
                            </h4>
                            <!-- Increased Reference Subtitle to font-size: 14px -->
                            <span class="font-weight-medium" style="font-size: 14px; color: #64748b;">Identifier: <strong class="text-primary">{{ $row->item_code }}</strong></span>
                        </div>
                    </div>
                </div>

                <!-- Horizontal Flow Lane Track -->
                <div class="item-process-lane-track w-100">
                    <div class="horizontal-step-track d-flex flex-nowrap overflow-auto pb-4 pt-4 align-items-start" style="position: relative; gap: 56px;">
                        
                        <!-- Smooth underlying tracking connection rail line (adjusted top alignment for larger circles) -->
                        <div class="process-rail-line" style="position: absolute; top: 80px; left: 60px; right: 60px; height: 4px; background: #e2e8f0; z-index: 1; border-radius: 10px;"></div>
            
            @php $currentGroupCode = $row->item_code; @endphp
        @endif

        {{-- 2. ULTRA-MODERN CIRCULAR START EVENT NODE --}}
        @if($row->start_time)
            <div class="process-node-card flex-shrink-0 text-center" style="width: 170px; position: relative; z-index: 2;">
                <!-- Main Concentric Status Circle Ring Container (Scaled up to 120px) -->
                <div class="d-flex justify-content-center mb-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center shadow" 
                         style="width: 120px; height: 120px; background: #f0fdf4; border: 4px solid #fff; transition: transform 0.2s ease;">
                        <div class="rounded-circle d-flex flex-column align-items-center justify-content-center text-success" 
                             style="width: 100px; height: 100px; background: #ffffff; border: 2px dashed #bbf7d0;">
                            <i class="fas fa-play mb-1" style="font-size: 14px;"></i>
                            <!-- Increased Badge text inside circle to font-size: 12px -->
                            <span class="font-weight-bold tracking-wider" style="font-size: 12px; letter-spacing: 0.5px;">START</span>
                        </div>
                    </div>
                </div>

                <!-- Circular Meta Information Block Details -->
                <div class="text-center px-1">
                    <!-- Increased Time font to font-size: 16px -->
                    <div class="font-weight-bold text-slate-800 mb-1" style="font-size: 16px; color: #1e293b;">{{ \Carbon\Carbon::parse($row->start_time)->format('h:i A') }}</div>
                    <!-- Increased Date font to font-size: 13px -->
                    <div class="text-slate-400 mb-2 font-weight-medium" style="font-size: 13px; color: #94a3b8;">{{ \Carbon\Carbon::parse($row->start_time)->format('M d, Y') }}</div>
                    
                    <!-- Increased User capsule sizing and font-size: 13px -->
                    <div class="inline-flex align-items-center bg-light px-3 py-2 border text-truncate w-100" style="border-radius: 20px; font-size: 13px; background: #f8fafc;" title="{{ $row->user_name ?? 'System' }}">
                        <i class="fas fa-user-circle text-muted mr-1" style="font-size: 14px;"></i>
                        <span class="text-slate-600 font-weight-bold text-truncate d-inline-block" style="max-width: 110px;">{{ $row->user_name ?? 'System' }}</span>
                    </div>
                </div>
            </div>
        @endif

        {{-- 3. ULTRA-MODERN CIRCULAR STOP EVENT NODE --}}
        @if($row->stop_time)
            <div class="process-node-card flex-shrink-0 text-center" style="width: 170px; position: relative; z-index: 2;">
                <!-- Main Concentric Status Circle Ring Container (Scaled up to 120px) -->
                <div class="d-flex justify-content-center mb-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center shadow" 
                         style="width: 120px; height: 120px; background: #fef2f2; border: 4px solid #fff; transition: transform 0.2s ease;">
                        <div class="rounded-circle d-flex flex-column align-items-center justify-content-center text-danger" 
                             style="width: 100px; height: 100px; background: #ffffff; border: 2px dashed #fecaca;">
                            <i class="fas fa-stop mb-1" style="font-size: 14px;"></i>
                            <!-- Increased Badge text inside circle to font-size: 12px -->
                            <span class="font-weight-bold tracking-wider" style="font-size: 12px; letter-spacing: 0.5px;">STOP</span>
                        </div>
                    </div>
                </div>

                <!-- Circular Meta Information Block Details -->
                <div class="text-center px-1">
                    <!-- Increased Time font to font-size: 16px -->
                    <div class="font-weight-bold text-slate-800 mb-1" style="font-size: 16px; color: #1e293b;">{{ \Carbon\Carbon::parse($row->stop_time)->format('h:i A') }}</div>
                    <!-- Increased Date font to font-size: 13px -->
                    <div class="text-slate-400 mb-2 font-weight-medium" style="font-size: 13px; color: #94a3b8;">{{ \Carbon\Carbon::parse($row->stop_time)->format('M d, Y') }}</div>
                    
                    <!-- Duration Badge Bubble Pill (Increased padding and font-size: 12px) -->
                    @if($row->start_time)
                        <div class="mb-2">
                            <span class="badge text-white font-weight-bold px-3 py-1" style="background: #475569; font-size: 12px; border-radius: 12px;">
                                <i class="fas fa-hourglass-half mr-1"></i>{{ \Carbon\Carbon::parse($row->start_time)->diffForHumans(\Carbon\Carbon::parse($row->stop_time), true) }}
                            </span>
                        </div>
                    @endif

                    <!-- Increased User capsule sizing and font-size: 13px -->
                    <div class="inline-flex align-items-center bg-light px-3 py-2 border text-truncate w-100" style="border-radius: 20px; font-size: 13px; background: #f8fafc;" title="{{ $row->user_name ?? 'System' }}">
                        <i class="fas fa-user-circle text-muted mr-1" style="font-size: 14px;"></i>
                        <span class="text-slate-600 font-weight-bold text-truncate d-inline-block" style="max-width: 110px;">{{ $row->user_name ?? 'System' }}</span>
                    </div>
                </div>
            </div>
        @endif

        {{-- Final closure tags structure generation validation check --}}
        @if($loop->last)
                    </div> <!-- End horizontal scroll track -->
                </div> <!-- End lane container -->
            </div> <!-- End global item tracking block -->
        @endif

    @empty
        <div class="w-100 text-center p-5 bg-white border shadow-xs" style="border-radius: 24px;">
            <div class="text-slate-300 mb-2"><i class="fas fa-circle-notch fa-spin fa-2x" style="color: #cbd5e1;"></i></div>
            <p class="m-0 font-weight-bold text-slate-500" style="color: #64748b; font-size: 16px;">No horizontal timeline activity metrics captured.</p>
        </div>
    @endforelse
</div>

{{-- horizontal timeline Design --}}

{{-- <div class="row mr-4">
    <div class="col-12 mt-4">
        <div class="card card-primary card-outline">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">
                    <i class="fas fa-stream mr-1"></i> Item Activity Timelines
                </h3>
                <div>
                    <a href="{{ route('users.create') }}" class="btn btn-success btn-sm">
                        <i class="fas fa-plus"></i> Add User
                    </a>
                </div>
            </div>
            
            <div class="card-body">
                <!-- Hidden DataTables instance for AJAX server-side fetching -->
                <table id="UsersTable" class="d-none">
                    <thead><tr><th>Data</th></tr></thead>
                </table>

                <!-- Main container for Item Cards -->
                <div id="itemTimelinesContainer">
                    <div class="text-center p-4 text-muted">
                        <i class="fas fa-spinner fa-spin fa-2x"></i>
                        <p class="mt-2">Loading item timelines...</p>
                    </div>
                </div>
            </div>
        </div>          
    </div>
</div> --}}
@endcan

@include('partials.modal.item_search')

    </div>
</div> 

@endsection
    
@section('js')
@include('partials.modal.gd.assign_to_artist')

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
            <button type="button" class="btn ${btnClass} btn-verify mr-1" data-id="${row.id}" data-client="${row.client_name ?? ''}" title="${btnText}" ${disabled}> 
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
                console.log(row);
                // If an artist is assigned, show their name inside a badge/text
                if (artistName) {
                    return `<span class="badge badge-success font-weight-bold p-1 btn-assign open-assign-to-artist-modal" style="cursor: pointer;">
                                <i class="fas fa-user-check mr-1"></i>${artistName}
                            </span>`;
                }
                if(row.is_verified ===true || row.is_verified ==1){
                    return `
                            <button type="button" 
                                    class="btn btn-secondary btn-sm btn-assign open-assign-to-artist-modal" 
                                    data-id="${row.item_code}" 
                                    data-item-code="${row.item_code ?? ''}">
                                <i class="fas fa-file-alt mr-1"></i> Assign To Artist
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
        url: "/gd_suppliedVerified/" + recordId, // Replace with your Laravel route URL
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
               $('#application').val(response.data.application);
              $(`input[name="file_source"][value="${response.data.file_source}"]`).prop('checked', true);
               $(`input[name="color"][value="${response.data.colors}"]`).prop('checked', true);
               $(`input[name="color"][value="${response.data.colors}"]`).prop('checked', true);
               $(`input[name="text_specs"][value="${response.data.text_specs}"]`).prop('checked', true);
               $('#file_link').val(response.data.file_link);
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