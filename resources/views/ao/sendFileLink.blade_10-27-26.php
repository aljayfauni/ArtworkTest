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
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background: #007bff;
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
      

    {{-- AO Form Upload sending design Link / upload  for GD --}}

<section class="content">
    <div class="container-fluid">
        <!-- Option Cards / Buttons -->
        {{-- <div class="row mb-4">
            <div class="col-md-4 col-sm-6 mb-3">
                <div id="btn-single" class="card h-100 shadow-sm border-0 option-card" style="border-radius: 12px; cursor: pointer;">
                    <div class="card-body text-center d-flex flex-column align-items-center justify-content-center p-4">
                        <i class="fas fa-file-alt text-primary mb-3" style="font-size: 2rem;"></i>
                        <h5 class="font-weight-bold text-dark mb-1">Single</h5>
                        <p class="text-muted small m-0">Submit a single request</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-sm-6 mb-3">
                <div id="btn-multiple" class="card h-100 shadow-sm border-0 option-card" style="border-radius: 12px; cursor: pointer;">
                    <div class="card-body text-center d-flex flex-column align-items-center justify-content-center p-4">
                        <i class="fas fa-copy text-info mb-3" style="font-size: 2rem;"></i>
                        <h5 class="font-weight-bold text-dark mb-1">Multiple</h5>
                        <p class="text-muted small m-0">Submit multiple requests</p>
                    </div>
                </div>
            </div>
        </div> --}}

        <!-- SINGLE FORM CONTAINER (Untouched original form) -->
        <div class="row " id="single-form-container">
            <div class="col-md-12">
                <div class="card card-widget shadow-sm border-0 mb-4" style="border-radius: 16px; overflow: hidden;">
                    <div class="card-header bg-white border-0 pt-4 pb-2 px-4">
                        <h3 class="card-title font-weight-bold text-dark m-0" style="font-size: 1.05rem;">
                            AO RFA Link Submission Form To GD
                        </h3>
                    </div>

                    <div id="ajax-alert-container"></div>

                    <form id="aoRfaForm" action="{{ route('ao_send_link') }}" method="POST">
                        @csrf
                        <div class="card-body bg-white px-4 py-3">
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="name">FACT Item Code:</label> {{--<i> (Optional)</i>--}}
                                    <div class="input-group ">
                                        <select id="fact_item_code" name="fact_item_code" class="form-control form-control-sm {{ $errors->has('fact_item_code') ? 'is-invalid' : '' }}"
                                            data-placeholder="Search Item Code" required>
                                            <option value=""></option>
                                        </select>
                                        @if($errors->has('fact_item_code'))
                                            <div class="invalid-feedback">
                                                <strong>{{ $errors->first('fact_item_code') }}</strong>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                             
                            <div class="row">
                            {{-- <div class="form-group col-md-4">
                                    <label class="font-weight-bold text-secondary mb-1" style="font-size: 0.8rem; text-transform: uppercase;">Email Address (GD) <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text bg-light border-right-0"><i class="fas fa-envelope text-muted" style="font-size: 0.85rem;"></i></span></div>
                                        <select id="single_employee_email" name="employee_email[]" class="form-control select2 border-left-0 bg-light" multiple="multiple">
                                            @foreach($users as $user)
                                                <option value="{{ $user->email }}">{{ $user->name }} ({{ $user->email }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div> --}}

                                    

                                <div class="form-group col-md-4">
                                    <label class="font-weight-bold text-secondary mb-1" style="font-size: 0.8rem; text-transform: uppercase;">Item Code <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text bg-light border-right-0"><i class="fas fa-code text-muted" style="font-size: 0.85rem;"></i></span></div>
                                        <input type="text" name="item_code" class="form-control border-left-0 bg-light" placeholder="Enter Item Code" required>
                                    </div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="font-weight-bold text-secondary mb-1" style="font-size: 0.8rem; text-transform: uppercase;">Client Name <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text bg-light border-right-0"><i class="fas fa-user text-muted" style="font-size: 0.85rem;"></i></span></div>
                                        <input type="text" name="client_name" class="form-control border-left-0 bg-light" placeholder="Enter Client Name" required>
                                    </div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="font-weight-bold text-secondary mb-1" style="font-size: 0.8rem; text-transform: uppercase;">Item description <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text bg-light border-right-0"><i class="fas fa-file-alt text-muted" style="font-size: 0.85rem;"></i></span></div>
                                        <input type="text" name="file_info" class="form-control border-left-0 bg-light" placeholder="Enter File Info" required>
                                    </div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="font-weight-bold text-secondary mb-1" style="font-size: 0.8rem; text-transform: uppercase;">File URL / Link <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text bg-light border-right-0"><i class="fas fa-globe text-muted" style="font-size: 0.85rem;"></i></span></div>
                                        <input type="text" name="link" class="form-control border-left-0 bg-light" placeholder="https://google.com" required>
                                    </div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="font-weight-bold text-secondary mb-1" style="font-size: 0.8rem; text-transform: uppercase;">Spread Size</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text bg-light border-right-0"><i class="fas fa-ruler text-muted" style="font-size: 0.85rem;"></i></span></div>
                                        <input type="text" name="spread_size" class="form-control border-left-0 bg-light" placeholder="Enter Spread Size">
                                    </div>
                                </div>
                                                                <div class="form-group col-md-4">
                                    <label class="font-weight-bold text-secondary mb-1" style="font-size: 0.8rem; text-transform: uppercase;">Spread Size H</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text bg-light border-right-0"><i class="fas fa-ruler text-muted" style="font-size: 0.85rem;"></i></span></div>
                                        <input type="text" name="spread_h" class="form-control border-left-0 bg-light" placeholder="Enter Spread Size">
                                    </div>
                                </div>
                                    <div class="form-group col-md-4">
                                        <label class="font-weight-bold text-secondary mb-1" style="font-size: 0.8rem; text-transform: uppercase;">Spread Size W</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text bg-light border-right-0"><i class="fas fa-ruler text-muted" style="font-size: 0.85rem;"></i></span></div>
                                            <input type="text" name="spread_w" class="form-control border-left-0 bg-light" placeholder="Enter Spread Size">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4">
                                    <label class="font-weight-bold text-secondary mb-1" style="font-size: 0.8rem; text-transform: uppercase;">Spread Size L</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text bg-light border-right-0"><i class="fas fa-ruler text-muted" style="font-size: 0.85rem;"></i></span></div>
                                        <input type="text" name="spread_l" class="form-control border-left-0 bg-light" placeholder="Enter Spread Size">
                                    </div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="font-weight-bold text-secondary mb-1" style="font-size: 0.8rem; text-transform: uppercase;">Folded Size</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text bg-light border-right-0"><i class="fas fa-map text-muted" style="font-size: 0.85rem;"></i></span></div>
                                        <input type="text" name="folded_size" class="form-control border-left-0 bg-light" placeholder="Enter Folded Size">
                                    </div>
                                </div>
                                   <div class="form-group col-md-4">
                                    <label class="font-weight-bold text-secondary mb-1" style="font-size: 0.8rem; text-transform: uppercase;">Folded Size H</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text bg-light border-right-0"><i class="fas fa-map text-muted" style="font-size: 0.85rem;"></i></span></div>
                                        <input type="text" name="folded_h" class="form-control border-left-0 bg-light" placeholder="Enter Folded Size">
                                    </div>
                                </div>
                                   <div class="form-group col-md-4">
                                    <label class="font-weight-bold text-secondary mb-1" style="font-size: 0.8rem; text-transform: uppercase;">Folded Size W</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text bg-light border-right-0"><i class="fas fa-map text-muted" style="font-size: 0.85rem;"></i></span></div>
                                        <input type="text" name="folded_w" class="form-control border-left-0 bg-light" placeholder="Enter Folded Size">
                                    </div>
                                </div>
                                   <div class="form-group col-md-4">
                                    <label class="font-weight-bold text-secondary mb-1" style="font-size: 0.8rem; text-transform: uppercase;">Folded Size L</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text bg-light border-right-0"><i class="fas fa-map text-muted" style="font-size: 0.85rem;"></i></span></div>
                                        <input type="text" name="folded_l" class="form-control border-left-0 bg-light" placeholder="Enter Folded Size">
                                    </div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="font-weight-bold text-secondary mb-1" style="font-size: 0.8rem; text-transform: uppercase;">Email Address (GD) <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text bg-light border-right-0"><i class="fas fa-envelope text-muted" style="font-size: 0.85rem;"></i></span></div>
                                        <select id="single_employee_email" name="employee_email[]" class="form-control select2 border-left-0 bg-light" multiple="multiple">
                                            @foreach($users as $user)
                                                <option value="{{ $user->email }}">{{ $user->name }} ({{ $user->email }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="font-weight-bold text-secondary mb-1" style="font-size: 0.8rem; text-transform: uppercase;">Colors</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text bg-light border-right-0"><i class="fas fa-brush text-muted" style="font-size: 0.85rem;"></i></span></div>
                                        <input type="text" name="colors" class="form-control border-left-0 bg-light" placeholder="Enter Colors">
                                    </div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="font-weight-bold text-secondary mb-1" style="font-size: 0.8rem; text-transform: uppercase;">Number of Colors</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text bg-light border-right-0"><i class="fas fa-asterisk text-muted" style="font-size: 0.85rem;"></i></span></div>
                                        <input type="number" name="num_colors" class="form-control border-left-0 bg-light" placeholder="Enter Number of Colors">
                                    </div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="font-weight-bold text-secondary mb-1" style="font-size: 0.8rem; text-transform: uppercase;">Type of Image</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text bg-light border-right-0"><i class="fas fa-image text-muted" style="font-size: 0.85rem;"></i></span></div>
                                        <input type="text" name="image_type" class="form-control border-left-0 bg-light" placeholder="Enter Image Type">
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="form-group col-md-12">
                                    <label class="font-weight-bold text-secondary mb-1" style="font-size: 0.8rem; text-transform: uppercase;">Notes / Description</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text bg-light border-right-0"><i class="fas fa-comment-alt text-muted" style="font-size: 0.85rem;"></i></span></div>
                                        <textarea name="description" class="form-control border-left-0 bg-light" placeholder="Descriptions or Notes" rows="3"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- <div class="card-footer bg-white border-0 pb-4 pt-2 px-4 d-flex justify-content-end">
                            <button type="submit" class="btn btn-success px-4 py-2 font-weight-bold shadow-none" style="border-radius: 8px;">
                                <i class="fas fa-paper-plane mr-2"></i> Submit & Email
                            </button>
                        </div> --}}
                              <!-- Sleek, Clean Footer Layout -->
                        <div class="card-footer bg-white border-0 pb-4 pt-2 px-4 d-flex justify-content-end">
                            <button type="submit" id="btn_submit_rfa" class="btn btn-success px-4 py-2 font-weight-bold shadow-none" style="border-radius: 8px; font-size: 0.88rem;">
                                <span class="btn-text">
                                    <i class="fas fa-paper-plane mr-2" style="font-size: 0.8rem;"></i> Submit & Email
                                </span>
                                <span class="btn-loading d-none">
                                    <i class="fas fa-spinner fa-spin mr-2" style="font-size: 0.85rem;"></i> Sending...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-lg-4"></div>
        </div>

        <!-- MULTIPLE FORM CONTAINER -->
        <div class="row d-none" id="multiple-form-container">
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="font-weight-bold text-dark m-0">Multiple Request Submissions</h5>
                    <button type="button" id="btn-add-form" class="btn btn-info font-weight-bold shadow-sm" style="border-radius: 8px;">
                        <i class="fas fa-plus-circle mr-1"></i> Add Form
                    </button>
                </div>

                <form id="aoRfaMultipleForm" action="{{ route('ao_send_link') }}" method="POST">
                    @csrf
                    
                    <!-- Dynamic form items append here -->
                    <div id="forms-wrapper"></div>

                    <div class="d-flex justify-content-end pb-4 pt-2">
                        <button type="submit" class="btn btn-success px-4 py-2 font-weight-bold shadow-none" style="border-radius: 8px;">
                            <i class="fas fa-paper-plane mr-2"></i> Submit All Requests
                        </button>
                    </div>
                </form>
            </div>
            <div class="col-lg-4"></div>
        </div>
        <div class="col-lg-12">
                                <div class="card card-warning card-outline shadow-sm">
                                    <div class="card-header">
                                        <h3 class="card-title font-weight-bold text-dark">
                                            <i class="fas fa-check-double text-warning mr-2"></i> GD Supervisor Requests Pending AO Approval
                                        </h3>
                                    </div>
                                    <div class="card-body table-responsive p-0" style="max-height: 300px;">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Client</th>
                                                    <th>File Info</th>
                                                    <th>File Link</th>
                                                    <th>Status</th>
                                                    <th>Ao Verified</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="aoApprovalTableBody"><tr><td colspan="4" class="text-center text-muted py-3">No Item pending AO approval.</td></tr></tbody>
                                        </table>
                                    </div>
                                </div>

                                {{-- <div class="card card-dark shadow-sm">
                                    <div class="card-header">
                                        <h3 class="card-title font-weight-bold"><i class="fas fa-list-ul mr-2"></i> All AO Task Requests</h3>
                                    </div>
                                    <div class="card-body table-responsive p-0" style="max-height: 250px;"> --}}
                                        {{-- <table class="table table-striped text-nowrap">
                                            <thead>
                                                <tr>
                                                    <th>Ticket #</th>
                                                    <th>Client</th>
                                                    <th>File Link</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="aoAllTasksTableBody">
            <tr>
                <td class="font-weight-bold">T-1001</td>
                <td>Nike PH</td>
                <td><a href="https://drive.google.com/file/d/nike-sample-1001" target="_blank" class="btn btn-xs btn-outline-primary"><i class="fas fa-external-link-alt"></i> View Link</a></td>
                <td><span class="badge badge-secondary status-badge"><i class="fas fa-check-double mr-1"></i>Completed</span></td>
                <td>
                    <button class="btn btn-xs btn-info" onclick="switchToTimeline('T-1001')">
                        <i class="fas fa-stream mr-1"></i> View Timeline
                    </button>
                </td>
            </tr>
        
            <tr>
                <td class="font-weight-bold">T-1002</td>
                <td>Starbucks PH</td>
                <td><a href="https://drive.google.com/file/d/starbucks-sample-1002" target="_blank" class="btn btn-xs btn-outline-primary"><i class="fas fa-external-link-alt"></i> View Link</a></td>
                <td><span class="badge badge-warning status-badge"><i class="fas fa-clock mr-1"></i>Pending Verification</span></td>
                <td>
                    <button class="btn btn-xs btn-info" onclick="switchToTimeline('T-1002')">
                        <i class="fas fa-stream mr-1"></i> View Timeline
                    </button>
                </td>
            </tr>
        </tbody>
            </table> --}}
        {{-- </div>
    </div> --}}
</div>
    </div>
</section>

<!-- HIDDEN HTML TEMPLATE FOR CLONING -->
<template id="form-card-template">
    <div class="card card-widget shadow-sm border-0 mb-4 dynamic-form-card" style="border-radius: 16px; overflow: hidden;">
        <div class="card-header bg-white border-0 pt-4 pb-2 px-4 d-flex justify-content-between align-items-center">
            <h3 class="card-title font-weight-bold text-dark m-0 card-form-title" style="font-size: 1.05rem;">
                AO RFA Link Submission Form To GD #1
            </h3>
            <button type="button" class="btn btn-sm btn-outline-danger btn-remove-card d-none">
                <i class="fas fa-trash-alt mr-1"></i> Remove
            </button>
        </div>

        <div class="card-body bg-white px-4 py-3">
            <div class="row">
                <div class="form-group col-md-4">
                    <label class="font-weight-bold text-secondary mb-1" style="font-size: 0.8rem; text-transform: uppercase;">Client Name <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text bg-light border-right-0"><i class="fas fa-user text-muted" style="font-size: 0.85rem;"></i></span></div>
                        <input type="text" name="forms[0][client_name]" class="form-control border-left-0 bg-light" placeholder="Enter Client Name" required>
                    </div>
                </div>
                <div class="form-group col-md-4">
                    <label class="font-weight-bold text-secondary mb-1" style="font-size: 0.8rem; text-transform: uppercase;">Item description <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text bg-light border-right-0"><i class="fas fa-file-alt text-muted" style="font-size: 0.85rem;"></i></span></div>
                        <input type="text" name="forms[0][file_info]" class="form-control border-left-0 bg-light" placeholder="Enter File Info" required>
                    </div>
                </div>
                <div class="form-group col-md-4">
                    <label class="font-weight-bold text-secondary mb-1" style="font-size: 0.8rem; text-transform: uppercase;">File URL / Link <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text bg-light border-right-0"><i class="fas fa-globe text-muted" style="font-size: 0.85rem;"></i></span></div>
                        <input type="text" name="forms[0][link]" class="form-control border-left-0 bg-light" placeholder="https://google.com" required>
                    </div>
                </div>
                <div class="form-group col-md-4">
                    <label class="font-weight-bold text-secondary mb-1" style="font-size: 0.8rem; text-transform: uppercase;">Spread Size</label>
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text bg-light border-right-0"><i class="fas fa-ruler text-muted" style="font-size: 0.85rem;"></i></span></div>
                        <input type="text" name="forms[0][spread_size]" class="form-control border-left-0 bg-light" placeholder="Enter Spread Size">
                    </div>
                </div>
                <div class="form-group col-md-4">
                    <label class="font-weight-bold text-secondary mb-1" style="font-size: 0.8rem; text-transform: uppercase;">Folded Size</label>
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text bg-light border-right-0"><i class="fas fa-map text-muted" style="font-size: 0.85rem;"></i></span></div>
                        <input type="text" name="forms[0][folded_size]" class="form-control border-left-0 bg-light" placeholder="Enter Folded Size">
                    </div>
                </div>
                <div class="form-group col-md-4">
                    <label class="font-weight-bold text-secondary mb-1" style="font-size: 0.8rem; text-transform: uppercase;">Email Address (GD) <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text bg-light border-right-0"><i class="fas fa-envelope text-muted" style="font-size: 0.85rem;"></i></span></div>
                        <select name="forms[0][employee_email][]" class="form-control select2-multiple border-left-0 bg-light" multiple="multiple">
                            @foreach($users as $user)
                                <option value="{{ $user->email }}">{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group col-md-4">
                    <label class="font-weight-bold text-secondary mb-1" style="font-size: 0.8rem; text-transform: uppercase;">Colors</label>
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text bg-light border-right-0"><i class="fas fa-brush text-muted" style="font-size: 0.85rem;"></i></span></div>
                        <input type="text" name="forms[0][colors]" class="form-control border-left-0 bg-light" placeholder="Enter Colors">
                    </div>
                </div>
                <div class="form-group col-md-4">
                    <label class="font-weight-bold text-secondary mb-1" style="font-size: 0.8rem; text-transform: uppercase;">Number of Colors</label>
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text bg-light border-right-0"><i class="fas fa-asterisk text-muted" style="font-size: 0.85rem;"></i></span></div>
                        <input type="number" name="forms[0][num_colors]" class="form-control border-left-0 bg-light" placeholder="Enter Number of Colors">
                    </div>
                </div>
                <div class="form-group col-md-4">
                    <label class="font-weight-bold text-secondary mb-1" style="font-size: 0.8rem; text-transform: uppercase;">Type of Image</label>
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text bg-light border-right-0"><i class="fas fa-image text-muted" style="font-size: 0.85rem;"></i></span></div>
                        <input type="text" name="forms[0][image_type]" class="form-control border-left-0 bg-light" placeholder="Enter Image Type">
                    </div>
                </div>
            </div>
            <div class="row mt-2">
                <div class="form-group col-md-12">
                    <label class="font-weight-bold text-secondary mb-1" style="font-size: 0.8rem; text-transform: uppercase;">Notes / Description</label>
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text bg-light border-right-0"><i class="fas fa-comment-alt text-muted" style="font-size: 0.85rem;"></i></span></div>
                        <textarea name="forms[0][description]" class="form-control border-left-0 bg-light" placeholder="Descriptions or Notes" rows="3"></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
{{-- </div> --}}

        {{-- END AO Form --}}






@include('partials.modal.item_search')

    </div>
</div> 

@endsection

@section('js')


<script>
    // Global Helper for Select2 initialization
function initSelect2(element) {
    if ($.fn.select2) {
        $(element).select2({
            theme: 'bootstrap4',
            width: '100%',
            placeholder: 'Select Email(s)'
        });
    }
}

$(document).ready(function () {

 
});
</script>



<script>
    $(document).ready(function () {
        
        // select2 multiple -
    $('#single_employee_email').select2({
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

// start item search from fact product
        $('#fact_item_code').select2({
            theme: 'bootstrap4',
            // placeholder: 'Search employee',
            minimumInputLength: 2, // Minimum characters to start searching
            ajax: {
                url: '{{ route('bom-creations.item-search') }}', // Laravel route for fetching users
                dataType: 'json',
                delay: 250, // Delay in milliseconds before sending the query
                processResults: function(data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                id: item.id, // The ID
                                text: item.text, // The text
                                item_code: item.item_code,
                                item_desc: item.item_desc,
                                color: item.color,
                                stock: item.stock,
                                formed: item.formed,
                                winding_direction: item.winding_direction,
                                production_type: item.production_type,
                                packing_specs: item.packing_specs,
                                fact_process: item.fact_process,
                                fact_uom: item.fact_uom,
                                fact_cylinder_type: item.fact_cylinder_type,
                                other_specs1: item.other_specs1,
                                other_specs2: item.other_specs2,
                                other_specs3: item.other_specs3,
                                spread_l: item.spread_l,
                                spread_w: item.spread_w,
                                spread_h: item.spread_h,
                                folded_l: item.folded_l,
                                folded_w: item.folded_w,
                                folded_h: item.folded_h,
                                other_dimension: item.other_dimension,
                                product_class: item.product_class,
                                sap_code: item.sap_code,
                            };
                        })
                    };
                },
                cache: true // Cache AJAX requests to avoid multiple requests
            }
        })

         $('#fact_item_code').on('select2:select', function (e) {
            let d = e.params.data;
            console.log(d)


    // Set the value of the input with name="client_name"
    // Map your Select2 data properties to input name attributes
        const fieldMap = {
            'item_code': d.item_code,
            'file_info': d.item_desc, // Maps different property name to input name
            'spread_h': d.spread_h,
            'spread_w': d.spread_w,
            'spread_l': d.spread_l,
            'folded_h': d.folded_h,
            'folded_w': d.folded_w,
            'folded_l': d.folded_l,
            'colors': d.color       // Maps color property to colors input
        };

        // Loop through the map and update inputs in one go
        $.each(fieldMap, function(inputName, value) {
            $(`input[name="${inputName}"]`).val(value).trigger('change');
        });

            Object.keys(d).forEach(function (key) {
                let el = $('#' + key);

                if (el.length) {
                    if (el.is('input, textarea, select')) {
                        el.val(d[key]);
                        console.log(d[key])
                    } else {
                        el.text(d[key]);
                    }
                }
            });

            // Reset all checkboxes
            $('.spec-checkbox').prop('checked', false);
            
            compareSpecs();
        });

// end item search from fact product

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


    // button click event

    $('.option-card').on('click', function() {
        // Reset active state for all cards
        $('.option-card').removeClass('border-primary shadow').css('border-width', '0');
        
        // Highlight selected card
        $(this).addClass('border-primary shadow').css({'border': '2px solid #007bff'});
        
        // Show form if Single is clicked, hide for others
        if ($(this).attr('id') === 'btn-single') {
            $('#single-form-container').removeClass('d-none');
        } else {
            $('#single-form-container').addClass('d-none');
            // Handle Multiple or New with LDS logic here if needed
        }
    });

    //    // Initialize Select2 on the Single form on load
    // initSelect2('#single_employee_email');

    // Function to append a new form block from template
    function appendNewForm() {
        let templateContent = $('#form-card-template').html();
        let $newForm = $(templateContent);

        $('#forms-wrapper').append($newForm);

        // Initialize Select2 specifically on the newly appended form
        initSelect2($newForm.find('.select2-multiple'));

        reindexForms();
    }

    // 1. Click "Single" Button
    $('#btn-single').on('click', function () {
        $('#multiple-form-container').addClass('d-none');
        $('#single-form-container').removeClass('d-none');
    });

    // 2. Click "Multiple" Button
    $('#btn-multiple').on('click', function () {
        $('#single-form-container').addClass('d-none');
        $('#multiple-form-container').removeClass('d-none');

        // If no forms generated yet, generate the first one automatically
        if ($('#forms-wrapper .dynamic-form-card').length === 0) {
            appendNewForm();
        }
    });

    // 3. Click "Add Form" Button
    $('#btn-add-form').on('click', function () {
        appendNewForm();
    });

    // 4. Click "Remove" Button on dynamic forms
    $(document).on('click', '.btn-remove-card', function () {
        if ($('#forms-wrapper .dynamic-form-card').length > 1) {
            $(this).closest('.dynamic-form-card').remove();
            reindexForms();
        }
    });

    // Re-index titles and field names (forms[0], forms[1], etc.)
    function reindexForms() {
        let cards = $('#forms-wrapper .dynamic-form-card');

        cards.each(function (index) {
            $(this).find('.card-form-title').text('AO RFA Link Submission Form To GD #' + (index + 1));

            // Update form input indices
            $(this).find('input, select, textarea').each(function () {
                let currentName = $(this).attr('name');
                if (currentName) {
                    let updatedName = currentName.replace(/forms\[\d+\]/, 'forms[' + index + ']');
                    $(this).attr('name', updatedName);
                }
            });
        });

        // Hide remove button if only 1 form exists
        if (cards.length > 1) {
            $('.btn-remove-card').removeClass('d-none');
        } else {
            $('.btn-remove-card').addClass('d-none');
        }
    }

});
</script>
<script>
$(document).ready(function () {
    // Automatically dismiss modal when clicking outside of it
    $(document).on('click', function (e) {
        if ($('#fbNotificationModal').hasClass('show')) {
            if (!$(e.target).closest('#fbNotificationModal .modal-content, #fbNotificationModalTrigger').length) {
                $('#fbNotificationModal').modal('hide');
            }
        }
    });

    // Mark all as read button handler
    $('#markAllReadBtn').on('click', function () {
        $('#fbNotificationBadge').addClass('d-none').text('0');

        $('#activityFeedList .notification-item.unread-item')
            .css('background-color', '#ffffff')
            .removeClass('unread-item');
    });
});
</script>
@endsection