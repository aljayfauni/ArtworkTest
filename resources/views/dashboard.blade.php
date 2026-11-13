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
        
            </div>
        </section>
    </div>
</div>
<div class="row mr-4">
<div class="col-12">

        <!-- 1. Top Section KPI Summary Cards -->
            <div class="row">
            <div class="col-xl-3 col-md-6 col-12">
                <div class="small-box bg-info shadow-sm">
                <div class="inner">
                <h3 id="total-start-count">{{ $totalSentFileLink}}</h3>
                    <p>Total Sent File link </p>
                </div>
                <div class="icon">
                    <i class="fas fa-paper-plane"></i>
                </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 col-12">
                <div class="small-box bg-success shadow-sm">
                <div class="inner">
                    <h3 id="total-start-count">{{ $totalOpenFile }} </h3><i class=""></i>
                    <p>Total File Open </p>
                </div>
                <div class="icon">
                    <i class="fas fa-envelope-open-text"></i>
                </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 col-12">
                <div class="small-box bg-warning shadow-sm">
                <div class="inner">
                    <h3 id="statPending">48</h3>
                    <p>Pending Views</p>
                </div>
                <div class="icon">
                    <i class="fas fa-clock"></i>
                </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 col-12">
                <div class="small-box bg-danger shadow-sm">
                <div class="inner">
                    <h3 id="total-start-count">{{ $totalStop }}</h3>
                    <p>Total Stopped </p>
                </div>
                <div class="icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                </div>
            </div>
            </div>


    {{-- AO Form Upload sending design Link / upload  for GD --}}

    {{-- <div class="content-wrapper"> --}}
 

    <section class="content">
        <div class="container-fluid">
                <!-- A. CONTROL PANEL (Start a new task) -->
        <div class="row">
            {{-- <div class="col-md-8">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">AO RFA Link Submission Form To GD</h3>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success m-3">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('ao_send_link') }}" method="POST">
                        @csrf
                        <div class="card-body bg-white">
                            <!-- Row 1: File Info, File URL, Email Address -->
                            <div class="row">
                                <!-- File Info -->
                                <div class="form-group col-md-4">
                                    <label for="file_info" class="font-weight-bold text-secondary">
                                        File Info <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-file-alt text-muted"></i></span>
                                        </div>
                                        <input type="text" 
                                            name="file_info" 
                                            id="file_info" 
                                            class="form-control @error('file_info') is-invalid @enderror" 
                                            placeholder="Enter File Info" 
                                            value="{{ old('file_info') }}" 
                                            required>
                                        @error('file_info')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- File URL / Link -->
                                <div class="form-group col-md-4">
                                    <label for="link" class="font-weight-bold text-secondary">
                                        File URL / Link <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-globe text-muted"></i></span>
                                        </div>
                                        <input type="text" 
                                            name="link" 
                                            id="link" 
                                            class="form-control @error('link') is-invalid @enderror" 
                                            placeholder="https://google.com" 
                                            value="{{ old('link') }}" 
                                            required>
                                        @error('link')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Email Address (GD) -->
                                <div class="form-group col-md-4">
                                    <label for="employee_email" class="font-weight-bold text-secondary">
                                        Email Address (GD) <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope text-muted"></i></span>
                                        </div>
                                        <select id="employee_email" 
                                                name="employee_email[]" 
                                                class="form-control select2 @error('employee_email') is-invalid @enderror" 
                                                multiple="multiple" 
                                                style="">
                                            @foreach($users as $user)
                                                <option value="{{ $user->email }}" 
                                                    {{ (is_array(old('employee_email')) && in_array($user->email, old('employee_email'))) ? 'selected' : '' }}>
                                                    {{ $user->name }} ({{ $user->email }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('employee_email')
                                            <span class="invalid-feedback" style="display: block;">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Row 2: Notes / Description -->
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="description" class="font-weight-bold text-secondary">Notes / Description</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-comment-alt text-muted"></i></span>
                                        </div>
                                        <textarea name="description" 
                                                id="description" 
                                                class="form-control @error('description') is-invalid @enderror" 
                                                placeholder="Descriptions or Notes" 
                                                rows="3">{{ old('description') }}</textarea>
                                        @error('description')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-paper-plane mr-1"></i> Submit & Email
                            </button>
                        </div>
                    </form>
                </div>
            </div> --}}


                <!-- RIGHT SIDE COLUMN (col-lg-4): Notifications & Messaging Panel -->
     



                <!-- 2. Quick Direct Message / Internal Note Widget -->
                {{-- <div class="card card-outline card-info shadow-sm mb-4">
                <div class="card-header py-2">
                    <h3 class="card-title font-weight-bold text-dark m-0" style="font-size: 1rem;">
                    <i class="fas fa-comment-alt text-info mr-1"></i> Direct Message / Quick Note
                    </h3>
                </div>

                <div class="card-body bg-white p-3">
                    <div class="form-group mb-2">
                    <label for="msgRecipient" class="small font-weight-bold text-secondary mb-1">To Recipient</label>
                    <select id="msgRecipient" class="form-control form-control-sm">
                        <option value="ALTEST@G.COM">ALTEST@G.COM</option>
                        <option value="AFRAMILE@UGECORP.COM">AFRAMILE@UGECORP.COM (GD)</option>
                        <option value="ALL GD Team">ALL GD Team</option>
                    </select>
                    </div>

                    <div class="form-group mb-2">
                    <label for="msgContent" class="small font-weight-bold text-secondary mb-1">Message / Note</label>
                    <textarea id="msgContent" class="form-control form-control-sm" rows="3" placeholder="Type a quick message or note regarding the shared link..."></textarea>
                    </div>

                    <button type="button" id="btnSendMessage" class="btn btn-info btn-block btn-sm font-weight-bold">
                    <i class="fas fa-paper-plane mr-1"></i> Send Quick Message
                    </button>
                </div>
                </div> --}}

            </div><!-- /.col-lg-4 -->
        </div>

        {{-- search item code --}}
        @can('is-readonly')
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
                                    <select class="form-control select2 " style="width: 100%;" data-select2-id="1" tabindex="-1" aria-hidden="true">
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

        </div>
        @endcan
    </section>
{{-- </div> --}}



        {{-- END AO Form --}}

        {{-- checklist form test --}}
      @can('is-readonly')
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
        <div class="col-md-6 mt-5">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-user-circle mr-1"></i> Account Details
                    </h3>
                </div>
                <div class="card-body">
                    <strong><i class="fas fa-id-card mr-1 text-secondary"></i> Full Name</strong>
                    <p class="text-muted">Aljay F. Ramile</p>
                    <hr>

                    <strong><i class="fas fa-envelope mr-1 text-secondary"></i> Email Address</strong>
                    <p class="text-muted">aframile@ugecorp.com</p>
                    <hr>
                    <strong><i class="fas fa-user-shield mr-1 text-secondary"></i> Account Status</strong>
                    <p class="text-muted"><span class="badge badge-warning">Unverified</span></p>
                </div>
                <div class="card-footer text-right">
                    <a href="http://localhost:9000/users/1/edit" class="btn btn-primary btn-sm">
                        <i class="fas fa-edit mr-1"></i> Edit Profile
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endcan

    @can('is-readonly')
        <div class="row  mr-4">
            <div class="col-12 mt-4 ">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-solid fa-users"> </i> List of Users</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        
                        <br>
                        <div class="row">
                            <div class="col-md-12">
                                <table id="UsersTable" class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>User_id</th>
                                            <th>Item Code</th>
                                            <th>Start_time</th>
                                            <th>Stop_time</th>
                                            <th>Status</th>
                                        
                                            <!--<th>Actions</th>-->
                                            
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
        </div> 
    @endcan

        <!-- Modern Circular Timeline Track Grid --> 
        @can('is-readonly')
            
    
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

        @endcan




@include('partials.modal.item_search')

    </div>
</div> 

@endsection

@section('js')
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

//fetch fileSend Table
    var FileSendLinkTable = $('#FileSendTable').DataTable({
        responsive: true,
        lengthChange: true,
        autoWidth: false,
        serverSide: true,
        processing: true,
        ajax: {
            url: "{{ route('FileSendShowAll') }}",
            type: 'GET'
        },
        columns: [
            { data: 'ao_name' },
            { data: 'from_email' },
            { data: 'to_email' },
            { 
                data: 'file_link',
                render: function(data) {
                    if (!data) return '';
                    
                    // Renders as a standard clickable blueprint hyperlink
                    return '<a href="' + data + '" target="_blank" style="text-decoration: underline; color: blue;">' + data + '</a>';
                }
            },
            { data: 'created_at' },
            { data: 'status' },
        ]
    });

    search = function(){
        console.log($('#search').val());
        InvoicesTable.search($('#search').val()).draw();
        PODsTable.search($('#search').val()).draw();
    }
        //end

//     window.startTime = function(index){
//     Swal.fire({  
//         title: 'Are you sure you want to Start this?', 
//         icon: 'warning', 
//         showCancelButton: true,  
//         confirmButtonText: 'Yes',  
//         cancelButtonText: 'No',
//     }).then((result) => {  
//         if (result.isConfirmed || result.value) { 
//             $.ajax({
//                 url: '/timeStart', 
//                 type: 'post',
//                 data: {
//                     _token: '{{ csrf_token() }}',
//                     id: index 
//                 },
//                 success: function(response){
//                     if (response.success){
//                         Toast.fire({ icon:'success', title:response.success });
                        
//                         // Find the HTML element where {{ $totalStart }} was rendered and add 1
//                         let currentCount = parseInt($('#total-start-count').text()) || 0;
//                         $('#total-start-count').text(currentCount + 1);
//                     } else if(response.error){
//                         Toast.fire({ icon:'error', title:response.error });
//                     }
//                 }
//             }); 
//         }
//     });
// };

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
    document.addEventListener('DOMContentLoaded', function () {
    const colorPicker = document.getElementById('color_picker');
    const addBtn = document.getElementById('add_color_btn');
    const container = document.getElementById('selected_colors_container');

    function addColor(colorHex) {
        // Prevent adding exact duplicate colors
        const existingColors = Array.from(container.querySelectorAll('input[name="colors[]"]')).map(input => input.value.toLowerCase());
        if (existingColors.includes(colorHex.toLowerCase())) return;

        // Create the visible color badge
        const badge = document.createElement('div');
        badge.className = 'badge d-inline-flex align-items-center p-2 text-white shadow-sm mr-2 mb-2';
        badge.style.backgroundColor = colorHex;
        badge.style.fontSize = '0.85rem';
        badge.style.border = '1px solid rgba(0,0,0,0.15)';

        badge.innerHTML = `
            <span class="mr-2" style="filter: drop-shadow(0px 1px 1px rgba(0,0,0,0.5));">${colorHex.toUpperCase()}</span>
            <input type="hidden" name="colors[]" value="${colorHex}">
            <button type="button" class="close text-white remove-color-btn" style="font-size: 1rem; line-height: 1; float: none; text-shadow: 0 1px 0 #000;" aria-label="Remove">&times;</button>
        `;

        container.appendChild(badge);
    }

    // Option 1: Add color instantly when they choose a color and close the picker dial
    colorPicker.addEventListener('change', function() {
        addColor(this.value);
    });

    // Option 2: Also add color if they explicitly click the "Add" button
    addBtn.addEventListener('click', function() {
        addColor(colorPicker.value);
    });

    // Remove color badge when clicking the 'x'
    container.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-color-btn')) {
            e.target.closest('.badge').remove();
        }
    });
});

$(document).ready(function() {

    $('#aoRfaForm').on('submit', function(e) {
        e.preventDefault();

        // 1. Confirm
        Swal.fire({
            title: 'Submit and send email?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, Send'
        }).then((result) => {
            if (!result.isConfirmed) return;

            // 2. Loading
            Swal.fire({ title: 'Sending...', didOpen: () => Swal.showLoading() });

            // 3. Send AJAX
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: $(this).serialize(),
                success: function(res) {
                    Swal.fire('Success!', res.success || 'Sent successfully!', 'success');
                    
                    // Reset form
                    $('#aoRfaForm')[0].reset();
                    $('.select2').val(null).trigger('change');

                    // 4. Update the activity feed with the new HTML list
                    if (res.html) {
                        $('#activityFeedList').html(res.html);
                    }
                },
                error: function() {
                    Swal.fire('Error!', 'Something went wrong. Try again.', 'error');
                }
            });
        });
    });

});
</script>
@endsection