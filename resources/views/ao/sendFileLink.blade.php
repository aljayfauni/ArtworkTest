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
    
        <div class="alert role-highlight-banner  shadow-sm mb-4 bg-white" style="border-left:5px solid #2da2d8 ;">
            <div class="d-flex align-items-center">
                <i class="fas fa-user-tie fa-2x text-primary mr-3"></i>
                <div>
                    <h5 class="mb-0 font-weight-bold text-dark">Account Officer (AO)</h5>
                    <p class="mb-0 text-muted">Submit Request Form with File Link And Approve GD Supervisor File Verification Form.</p>
                </div>
            </div>
        </div>
        <!-- SINGLE FORM CONTAINER (Untouched original form) -->
{{-- <div class="row" id="single-form-container"> --}}
       <!-- Option Cards / Buttons -->
        <div class="row mb-4">
            <div class="col-md-4 col-sm-6 mb-3">
                <div id="btn-new-item" class="card h-100 shadow-sm border-0 option-card" style="border-radius: 12px; cursor: pointer;">
                    <div class="card-body text-center d-flex flex-column align-items-center justify-content-center p-4">
                        <i class="fas fa-file-alt text-primary mb-3" style="font-size: 2rem;"></i>
                        <h5 class="font-weight-bold text-dark mb-1">New Artwork Item</h5>
                        <p class="text-muted small m-0">New request for new item Code</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-sm-6 mb-3" title="No Item code">
                <div id="btn-existing-item" class="card h-100 shadow-sm border-0 option-card" style="border-radius: 12px; cursor: pointer;">
                    <div class="card-body text-center d-flex flex-column align-items-center justify-content-center p-4">
                        <i class="fas fa-copy text-info mb-3" style="font-size: 2rem;"></i>
                        <h5 class="font-weight-bold text-dark mb-1">Create RFA With Item Code</h5>
                        <p class="text-muted small m-0">Rfa With Item Code</p>
                    </div>
                </div>
            </div>
        </div>
    <div class="col-md-12" >
        
        <form id="aoRfaFormNew" style="display:none;" action="{{ route('saveNewItem') }}" method="POST">
    @csrf

    <div id="ajax-alert-container"></div>

    <!-- Container for all cards -->
    <div id="form-items-wrapper">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h5 class="fw-bold text-dark m-0"></h5>
        </div>

        <!-- Individual Form Item Card -->
        <div class="card card-outline card-primary shadow-sm mb-4 form-item-card">
            <!-- Paper-Style Header -->
            <div class="card-header bg-white py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="card-title text-bold text-dark m-0">
                            <i class="fas fa-file-invoice text-primary mr-2"></i> AO RFA Link Submission Form To GD
                               {{-- hidden rfa-reference --}}
                             <input type="text" id="ref_reference_input" name="ref_reference_input" class="form-control" value="" readonly>
                        </h3>
                              
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-danger btn-remove-form d-none">
                        <i class="fas fa-trash-alt mr-1"></i> Remove Form
                    </button>
                </div>
            </div>
            
            <div class="card-body bg-light">
                
                <!-- SECTION 1: BASIC DETAILS -->
                <div class="card card-white border mb-3">
                    <div class="card-header bg-white py-2">
                        <h5 class="card-title text-bold text-secondary text-sm">
                            <i class="fas fa-info-circle text-info mr-1"></i> 1. RFA DETAILS
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                   
                            <!-- Client Name Field -->
                            <div class="form-group col-md-6">
                                <label class="text-uppercase text-xs text-muted font-weight-bold">Client Name <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    </div>
                                    <input type="text" name="client_name[]" class="form-control" placeholder="Enter Client Name" required>
                                </div>
                            </div>

                            <!-- File Link / Item Description Field -->
                            <div class="form-group col-md-6">
                                <label class="text-uppercase text-xs text-muted font-weight-bold">File Link / Description <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-file-alt"></i></span>
                                    </div>
                                    <input type="text" name="link[]" class="form-control" placeholder="Brief Item Description / Link" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
               <div class="card border-0 shadow-none bg-transparent">
                <div class="card-footer bg-transparent border-0 pb-4 pt-2 px-0 d-flex justify-content-end">
                    <button type="submit" id="btn_submit_rfa" class="btn btn-success px-4 py-2 font-weight-bold shadow-none" style="border-radius: 8px; font-size: 0.88rem;">
                        <span class="btn-text">
                            <i class="fas fa-paper-plane mr-2" style="font-size: 0.8rem;"></i> Submit
                        </span>
                        <span class="btn-loading d-none">
                            <i class="fas fa-spinner fa-spin mr-2" style="font-size: 0.85rem;"></i> Processing...
                        </span>
                    </button>
                </div>
            </div>
</form>
    </div>
     


        <!-- Existing with item code -->
        <form id="aoRfaFormExisting" style="display:none;" action="{{ route('ao_send_link') }}" method="POST">
            @csrf

        

            <div id="ajax-alert-container"></div>

            <!-- Container for all cards -->
            <div id="form-items-wrapper2">
                <div class="d-flex align-items-center justify-content-between mb-4">
        <h5 class="fw-bold text-dark m-0"></h5>
        <button type="button" 
            id="btn-add-form" 
            class="btn btn-primary d-inline-flex align-items-center gap-2 px-3 py-2 fw-medium rounded-3 border-0 shadow-sm custom-btn-indigo">
             <i class="fa-solid fa-plus fs-6"></i>
                <span>Add Form</span>
            </button>
        </div>

                <!-- Individual Form Item Card -->
          <div class="card card-outline card-primary shadow-sm mb-4 form-item-card" >
    <!-- Paper-Style Header -->
    <div class="card-header bg-white py-3">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h3 class="card-title text-bold text-dark m-0">
                    <i class="fas fa-file-invoice text-primary mr-2"></i> AO RFA Link Submission Form To GD
                </h3>
                    {{-- hidden rfa-reference --}}
                             <input type="text" name="rfa_reference[]" class="form-control" value="" readonly>
            </div>
            <button type="button" class="btn btn-sm btn-outline-danger btn-remove-form d-none">
                <i class="fas fa-trash-alt mr-1"></i> Remove Form
            </button>
        </div>
    </div>
    
    <div class="card-body bg-light">
        
        <!-- SECTION 1: SEARCH & BASIC DETAILS -->
        <div class="card card-white border mb-3">
            <div class="card-header bg-white py-2">
                <h5 class="card-title text-bold text-secondary text-sm">
                    <i class="fas fa-search text-info mr-1"></i> 1. ITEM SELECTION & BASICS
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-12 mb-3">
                        <label class="text-uppercase text-xs text-muted font-weight-bold">FACT Item Code Search</label>
                        <select name="fact_item_code[]" class="form-control select2-fact-item fact_item_code {{ $errors->has('fact_item_code') ? 'is-invalid' : '' }}" data-placeholder="Search and select Item Code to auto-fill fields...">
                            <option value=""></option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-4">
                        <label class="text-uppercase text-xs text-muted font-weight-bold">Item Code <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-code"></i></span>
                            </div>
                            <input type="text" name="item_code[]" class="form-control" placeholder="e.g. ITM-99201" required>
                        </div>
                    </div>

                    <div class="form-group col-md-4">
                        <label class="text-uppercase text-xs text-muted font-weight-bold">Client Name <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                            </div>
                            <input type="text" name="client_name[]" class="form-control" placeholder="Enter Client Name" required>
                        </div>
                    </div>

                    <div class="form-group col-md-4">
                        <label class="text-uppercase text-xs text-muted font-weight-bold">Item Description <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-file-alt"></i></span>
                            </div>
                            <input type="text" name="file_info[]" class="form-control" placeholder="Brief Item Description" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SECTION 2: DIMENSIONS -->
        <div class="card card-white border mb-3">
            <div class="card-header bg-white py-2">
                <h5 class="card-title text-bold text-secondary text-sm">
                    <i class="fas fa-ruler-combined text-info mr-1"></i> 2. SIZE & DIMENSION SPECIFICATIONS
                </h5>
            </div>
            <div class="card-body">
                <!-- Spread Sizes Row -->
                <div class="row">
                    <div class="form-group col-md-3">
                        <label class="text-uppercase text-xs text-muted font-weight-bold">Spread Size</label>
                        <input type="text" name="spread_size[]" class="form-control" placeholder="Total Spread">
                    </div>
                    <div class="form-group col-md-3">
                        <label class="text-uppercase text-xs text-muted font-weight-bold">Spread Height (H)</label>
                        <input type="text" name="spread_h[]" class="form-control" placeholder="Height">
                    </div>
                    <div class="form-group col-md-3">
                        <label class="text-uppercase text-xs text-muted font-weight-bold">Spread Width (W)</label>
                        <input type="text" name="spread_w[]" class="form-control" placeholder="Width">
                    </div>
                    <div class="form-group col-md-3">
                        <label class="text-uppercase text-xs text-muted font-weight-bold">Spread Length (L)</label>
                        <input type="text" name="spread_l[]" class="form-control" placeholder="Length">
                    </div>
                </div>

                <!-- Folded Sizes Row -->
                <div class="row">
                    <div class="form-group col-md-3">
                        <label class="text-uppercase text-xs text-muted font-weight-bold">Folded Size</label>
                        <input type="text" name="folded_size[]" class="form-control" placeholder="Total Folded">
                    </div>
                    <div class="form-group col-md-3">
                        <label class="text-uppercase text-xs text-muted font-weight-bold">Folded Height (H)</label>
                        <input type="text" name="folded_h[]" class="form-control" placeholder="Height">
                    </div>
                    <div class="form-group col-md-3">
                        <label class="text-uppercase text-xs text-muted font-weight-bold">Folded Width (W)</label>
                        <input type="text" name="folded_w[]" class="form-control" placeholder="Width">
                    </div>
                    <div class="form-group col-md-3">
                        <label class="text-uppercase text-xs text-muted font-weight-bold">Folded Length (L)</label>
                        <input type="text" name="folded_l[]" class="form-control" placeholder="Length">
                    </div>
                </div>
            </div>
        </div>

        <!-- SECTION 3: SPECS & DELIVERY -->
        <div class="card card-white border mb-3">
            <div class="card-header bg-white py-2">
                <h5 class="card-title text-bold text-secondary text-sm">
                    <i class="fas fa-sliders-h text-info mr-1"></i> 3. ADDITIONAL SPECS & DELIVERY
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-md-12">
                        <label class="text-uppercase text-xs text-muted font-weight-bold">File URL / Link <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-globe"></i></span>
                            </div>
                            <input type="url" name="link[]" class="form-control" placeholder="https://drive.google.com/..." required>
                        </div>
                    </div>

                    {{-- <div class="form-group col-md-6">
                        <label class="text-uppercase text-xs text-muted font-weight-bold">Email Address (GD) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            </div>
                            <select name="employee_email[0][]" class="form-control select2-email" multiple="multiple" >
                                @foreach($users as $user)
                                    <option value="{{ $user->email }}">{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div> --}}

                    <div class="form-group col-md-4">
                        <label class="text-uppercase text-xs text-muted font-weight-bold">Colors</label>
                        <input type="text" name="colors[]" class="form-control" placeholder="e.g. CMYK / Pantone">
                    </div>

                    <div class="form-group col-md-4">
                        <label class="text-uppercase text-xs text-muted font-weight-bold">Number of Colors</label>
                        <input type="number" name="num_colors[]" class="form-control" placeholder="0">
                    </div>

                    <div class="form-group col-md-4">
                        <label class="text-uppercase text-xs text-muted font-weight-bold">Type of Image</label>
                        {{-- <input type="text" name="image_type[]" class="form-control" placeholder="e.g. Vector / Raster"> --}}
                              <select name="image_type[]" class="form-control select2-email"  required>
                                    <option value="">--Select Image Type--</option>
                                    <option value="Vector">Vector</option>
                                    <option value="Jpeg">JPEG</option>
                                    <option value="Png">PNG</option>
                              
                            </select>
                    </div>

                    <div class="form-group col-md-4">
                        <label class="text-uppercase text-xs text-muted font-weight-bold">Other Specs 1</label>
                        <textarea name="other_specs1[]" class="form-control" placeholder="Specification 1" rows="2"></textarea>
                    </div>

                    <div class="form-group col-md-4">
                        <label class="text-uppercase text-xs text-muted font-weight-bold">Other Specs 2</label>
                        <textarea name="other_specs2[]" class="form-control" placeholder="Specification 2"></textarea>
                    </div>

                    <div class="form-group col-md-4">
                        <label class="text-uppercase text-xs text-muted font-weight-bold">Other Specs 3</label>
                        <textarea name="other_specs3[]" class="form-control" placeholder="Specification 3"></textarea>
                    </div>

                    <div class="form-group col-md-4">
                        <label class="text-uppercase text-xs text-muted font-weight-bold">With LSD</label>
                        <input type="text" name="with_lsd[]" class="form-control" placeholder="Details regarding LSD">
                        {{-- <select name="image_type[]" class="form-control select2-email"  >
                                    <option value="">--Select--</option>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                   
                              
                            </select> --}}
                    </div>
                </div>
            </div>
        </div>

        <!-- SECTION 4: NOTES -->
        <div class="card card-white border mb-0">
            <div class="card-header bg-white py-2">
                <h5 class="card-title text-bold text-secondary text-sm">
                    <i class="fas fa-comment-alt text-info mr-1"></i> 4. NOTES & DESCRIPTION
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-12 mb-0">
                        <textarea name="description[]" class="form-control" placeholder="Add any special instructions or remarks here..." rows="3"></textarea>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

            </div>

            <!-- Single submit button for all forms -->
            <div class="card border-0 shadow-none bg-transparent">
                <div class="card-footer bg-transparent border-0 pb-4 pt-2 px-0 d-flex justify-content-end">
                    <button type="submit" id="btn_submit_rfa" class="btn btn-success px-4 py-2 font-weight-bold shadow-none" style="border-radius: 8px; font-size: 0.88rem;">
                        <span class="btn-text">
                            <i class="fas fa-paper-plane mr-2" style="font-size: 0.8rem;"></i> Submit All & Email
                        </span>
                        <span class="btn-loading d-none">
                            <i class="fas fa-spinner fa-spin mr-2" style="font-size: 0.85rem;"></i> Processing...
                        </span>
                    </button>
                </div>
            </div>
        </form>
    </div>

            <div class="col-lg-12">
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

                {{-- specifications --}}
                    <div class="card card-primary card-outline shadow-sm" style="display:none;">
                    <div class="card-header">
                        <h3 class="card-title font-weight-bold text-dark">
                            <i class="fas fa-check-double text-primary mr-2"></i> GD Supervisor Specifications Pending AO Approval
                        </h3>
                    </div>
                    <div class="card-body table-responsive p-0" style="max-height: 300px;">
                        <table class="table table-hover" id="aoApprovalSpecifications">
                            <thead>
                                <tr>
                                    <tr>
                                        <th>ID</th>
                                        <th>Item Code</th>
                                        <th>Instruction Agreement</th>
                                        <th>Printing Process</th>
                                        <th>Special Process</th>
                                        <th>E-file Format</th>
                                        <th>Color Guide</th>
                                        <th>Output Digital Proof (PC)</th>
                                        <th>Output Mockup (PC)</th>
                                        <th>Output Digital Proof Check</th>
                                        <th>Output Mockup Check</th>
                                        <th>Date Requested</th>
                                        <th>Date Required</th>
                                        <th>Spec Size Spread</th>
                                        <th>Spec Size Folded</th>
                                        <th>Substrate</th>
                                        <th>Spec Number of Colors</th>
                                        <th>Color Guide Others</th>
                                        <th>Reference Item Code</th>
                                        <th>Requested By</th>
                                        {{-- <th>Client</th> --}}
                                        <th>Rfa Reference</th>
                                        <th>Item Description</th>
                                        <th>Status</th>
                                        <th>Date Created</th>
                                        <th>Date Updated</th>
                                        <th>Spread H</th>
                                        <th>Spread W</th>
                                        <th>Spread L</th>
                                        <th>Folded H</th>
                                        <th>Folded W</th>
                                        <th>Folded L</th>
                                        {{-- <th>File Link</th> --}}
                                        {{-- <th>Action</th> --}}
                                    </tr>

                                </tr>
                            </thead>
                            <tbody ><tr><td colspan="12" class="text-center text-muted py-3">No Item pending AO approval.</td></tr></tbody>
                        </table>
                    </div>
                </div> 

                     {{-- Include the partial table --}}
                    @include('partials.tables.rfa_file_list', [
                        'tableId' => 'FileSendTable',
                        'title' => 'All AO File Link Requests'
                    ]) 
             </div>


       
   
   
  

{{-- </div> --}}



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

{{-- @section('js')

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
</script>
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

    // start item search from fact product
    let formIndex = 0;
    // Helper function to initialize Select2 instances on a card element
    function initCardSelect2(card) {
        // 1. Initialize AJAX Search on .fact_item_code inside this specific card
        $(card).find('.fact_item_code').select2({
            theme: 'bootstrap4',
            placeholder: 'Search Item Code',
            minimumInputLength: 2,
            ajax: {
                url: '{{ route("bom-creations.item-search") }}',
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                id: item.id,
                                text: item.text,
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
                cache: true
            }
        });

        // 2. Initialize Email Select2 on this card
        $(card).find('.select2-email').select2({
            width: '100%'
        });
    }

    // Initialize Select2 on the default initial card
    initCardSelect2('#form-items-wrapper .form-item-card:first');

    // Scoped Item Selection: Populate inputs ONLY inside the card where user searched
    $(document).on('select2:select', '.fact_item_code', function (e) {
        let d = e.params.data;
        let currentCard = $(this).closest('.form-item-card');

        // Map Select2 response data to input names
        const fieldMap = {
            'item_code[]': d.item_code,
            'file_info[]': d.item_desc,
            'spread_h[]': d.spread_h,
            'spread_w[]': d.spread_w,
            'spread_l[]': d.spread_l,
            'folded_h[]': d.folded_h,
            'folded_w[]': d.folded_w,
            'folded_l[]': d.folded_l,
            'colors[]': d.color
        };

        // Update inputs strictly within currentCard
        $.each(fieldMap, function (inputName, value) {
            currentCard.find(`input[name="${inputName}"]`).val(value || '').trigger('change');
        });

        // Optional spec comparison reset if function exists
        if (typeof compareSpecs === 'function') {
            compareSpecs(currentCard);
        }
    });

    // Event listener triggered when an item is selected from FACT Item Code
    $(document).on('select2:select', '.fact_item_code', function (e) {
        let d = e.params.data;
        
        // Find the specific card where the dropdown selection happened
        let currentCard = $(this).closest('.form-item-card');

        // Map Select2 data properties to input element name attributes
        const fieldMap = {
            'item_code[]': d.item_code,
            'file_info[]': d.item_desc,
            'spread_h[]': d.spread_h,
            'spread_w[]': d.spread_w,
            'spread_l[]': d.spread_l,
            'folded_h[]': d.folded_h,
            'folded_w[]': d.folded_w,
            'folded_l[]': d.folded_l,
            'colors[]': d.color
        };

        // Auto-fill input fields only inside currentCard
        $.each(fieldMap, function (inputName, value) {
            currentCard.find(`input[name="${inputName}"]`).val(value || '').trigger('change');
        });

        // Loop over remaining properties for any direct ID or name matching elements inside this card
        Object.keys(d).forEach(function (key) {
            let el = currentCard.find('#' + key + ', [name="' + key + '[]"]');
            if (el.length) {
                if (el.is('input, textarea, select')) {
                    el.val(d[key]).trigger('change');
                } else {
                    el.text(d[key]);
                }
            }
        });

        // Reset checkboxes within this card
        currentCard.find('.spec-checkbox').prop('checked', false);

        if (typeof compareSpecs === 'function') {
            compareSpecs(currentCard);
        }
    });

       // ==========================================
        // 3. MO SEARCH MODAL & DATATABLE
        // ==========================================
        window.showMOSearch = function() {
            // if (!validatePrestartSetup('searching for an MO')) return;
            $('#SearchMOModal').modal('show');
        }

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

   let formIndex = 0;

    function initSelect2(element) {
        // Enable tags to accept custom email inputs
        $(element).find('.select2-email').select2({
            width: '100%',
            tags: true,
            tokenSeparators: [',', ' '],
            placeholder: 'Select or type custom email...',
            createTag: function (params) {
                let term = $.trim(params.term);

                // Simple regex email format check before creating custom tag
                let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailPattern.test(term)) {
                    return null; // Don't create tag if invalid email format
                }

                return {
                    id: term,
                    text: term,
                    newTag: true
                };
            }
        });

        $(element).find('.select2-fact').select2({
            width: '100%'
        });
    }

    // Initialize Select2 on original card
    initSelect2('#form-items-wrapper .form-item-card:first');

 

    // Remove Form Event
    $(document).on('click', '.btn-remove-form', function () {
        $(this).closest('.form-item-card').remove();
    });


    // Add Form Event


    // Remove Form Event
    $(document).on('click', '.btn-remove-form', function () {
        $(this).closest('.form-item-card').remove();
    });

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
</script> --}}
{{-- @endsection --}}

@section('js')

@include('partials.modal.ao.update_supplied_status')
<script src="{{ asset('js/file-send-table.js') }}"></script>

<script>
    // Pass dynamic route definitions safely to global JS scope
    window.artworkDetailRoute = "{{ route('getArtworkFileDetail', ':item_id') }}";

    $(document).ready(function() {
        // Initialize the table with custom parameters
        initFileSendTable('#FileSendTable', "{{ route('FileSendShowAll') }}");
    });
</script>
<script>
    let formIndex = 0;
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

        //
        // $('#btn-new-item').on('click', function() {
        //     $('#aoRfaFormNew').css('display', 'block');
        //      $('#aoRfaFormExisting').css('display', 'none');
        // });

$('#btn-new-item').on('click', function() {
    Swal.fire({
        title: 'Create New Item?',
        text: 'Are you sure you want to create a new item?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, create it',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (!result.isConfirmed) {
            return;
        }

        $('#aoRfaFormNew').css('display', 'block');
        $('#aoRfaFormExisting').css('display', 'none');

        $.ajax({
            url: '/getNewReferenceNum',
            type: 'GET',
            dataType: 'json',
            beforeSend: function() {
                $('#ref_reference_input').val('Generating...');
            },
            success: function(response) {
                if (response.new_ref) {
                    $('#ref_reference_input').val(response.new_ref);
                } else {
                    Swal.fire('Error', 'Could not generate reference number.', 'error');
                    $('#aoRfaFormNew').css('display', 'none');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
                Swal.fire('Error', 'Something went wrong while generating the reference number.', 'error');
                $('#aoRfaFormNew').css('display', 'none');
            }
        });
    });
});



     
    //   $('#btn-existing-item').on('click', function() {
    //         $('#aoRfaFormExisting').css('display', 'block');
    //         $('#aoRfaFormNew').css('display', 'none');
    //     });

    // 1. EXISTING ITEM BUTTON (Generates initial reference for the first card)
$('#btn-existing-item').on('click', function() {
    Swal.fire({
        title: 'Create Existing Item?',
        text: 'Are you sure you want to proceed with an existing item?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, proceed',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (!result.isConfirmed) return;

        $('#aoRfaFormExisting').css('display', 'block');
        $('#aoRfaFormNew').css('display', 'none');

        let firstRefInput = $('#form-items-wrapper2 .form-item-card:first input[name="rfa_reference[]"]');

        $.ajax({
            url: '/getExistingReferenceNum',
            type: 'GET',
            dataType: 'json',
            data: { _token: $('meta[name="csrf-token"]').attr('content') },
            beforeSend: function() {
                firstRefInput.val('Generating...');
            },
            success: function(response) {
                if (response.new_ref) {
                    $('#form-items-wrapper2 input[name="rfa_reference[]"]').each(function() {
                        if (!$(this).val() || $(this).val() === 'Generating...') {
                            $(this).val(response.new_ref);
                        }
                    });
                } else {
                    Swal.fire('Error', 'Could not generate reference number.', 'error');
                    $('#aoRfaFormExisting').css('display', 'none');
                }
            },
            error: function() {
                Swal.fire('Error', 'Something went wrong while generating the reference number.', 'error');
                $('#aoRfaFormExisting').css('display', 'none');
            }
        });
    });
});

// 3. ADD FORM BUTTON (.off('click') prevents duplicate listeners from firing twice)
$('#btn-add-form').off('click').on('click', function () {
    Swal.fire({
        title: 'Add Another Item Form?',
        text: 'Are you sure you want to add another form? This will generate and log a new RFA reference number.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, add form',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (!result.isConfirmed) return;

        formIndex++;

        let firstCard = $('#form-items-wrapper2 .form-item-card:first');

        // Temporarily destroy Select2 on template source before cloning
        firstCard.find('.select2-hidden-accessible').select2('destroy');

        // Clone template card
        let newCard = firstCard.clone();

        // Reset inputs and selections on new clone
        newCard.find('input, textarea').val('');
        newCard.find('select').val(null).trigger('change');

        // Adjust email select name array key
        newCard.find('.select2-email').attr('name', `employee_email[${formIndex}][]`);

        // Reveal the remove button on new clone
        newCard.find('.btn-remove-form').removeClass('d-none');

        // Append to wrapper
        $('#form-items-wrapper2').append(newCard);

        // Re-initialize Select2 on both template card and new clone
        // initCardSelect2(firstCard);
        initCardSelect2(newCard);

        // Fetch next reference from getAddFormReferenceNum
        let newRefInput = newCard.find('input[name="rfa_reference[]"]');
        $.ajax({
            url: '/getAddFormReferenceNum',
            type: 'GET', // Note: use POST if your route in web.php is Route::post(...)
            dataType: 'json',
            data: { _token: $('meta[name="csrf-token"]').attr('content') },
            beforeSend: function() {
                newRefInput.val('Generating...');
            },
            success: function(response) {
                if (response.new_ref) {
                    newRefInput.val(response.new_ref);
                } else {
                    Swal.fire('Error', 'Could not generate reference number.', 'error');
                    newCard.remove();
                }
            },
            error: function() {
                Swal.fire('Error', 'Something went wrong while generating the reference number.', 'error');
                newCard.remove();
            }
        });
    });
});



        // 1. SELECT2 INITIALIZATIONS
        // Employee Email Tagging
        $('#employee_email').select2({
            tags: true,
            tokenSeparators: [',', ' '],
            placeholder: "Search list or type custom email...",
            allowClear: true,
            createTag: function (params) {
                var term = $.trim(params.term);
                if (term === '' || term.toUpperCase() === 'INTERNAL_NOTE') {
                    return null;
                }
                return { id: term, text: term, newTag: true };
            }
        });

        // Form Card Select2 Initializer
        function initCardSelect2(card) {
            // AJAX Item Search
            $(card).find('.fact_item_code').select2({
                theme: 'bootstrap4',
                placeholder: 'Search Item Code',
                minimumInputLength: 2,
                ajax: {
                    url: '{{ route("bom-creations.item-search") }}',
                    dataType: 'json',
                    delay: 250,
                    processResults: function (data) {
                        return {
                            results: $.map(data, function (item) {
                                return Object.assign({ id: item.id, text: item.text }, item);
                            })
                        };
                    },
                    cache: true
                }
            });

            // Email Select2 with Regex Validation
            $(card).find('.select2-email').select2({
                width: '100%',
                tags: true,
                tokenSeparators: [',', ' '],
                placeholder: 'Select or type custom email...',
                createTag: function (params) {
                    let term = $.trim(params.term);
                    let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    return emailPattern.test(term) ? { id: term, text: term, newTag: true } : null;
                }
            });

            $(card).find('.select2-fact').select2({ width: '100%' });
        }

        // Add Form Event
        // $('#btn-add-form').on('click', function () {
        //     formIndex++;

        //     let firstCard = $('#form-items-wrapper2 .form-item-card:first');

        //     // Temporarily destroy Select2 on template source before cloning
        //     firstCard.find('.select2-hidden-accessible').select2('destroy');

        //     // Clone template card
        //     let newCard = firstCard.clone();

        //     // Reset inputs and selections on new clone
        //     newCard.find('input, textarea').val('');
        //     newCard.find('select').val(null).trigger('change');

        //     // Adjust email select name array key
        //     newCard.find('.select2-email').attr('name', `employee_email[${formIndex}][]`);

        //     // Reveal the remove button on new clone
        //     newCard.find('.btn-remove-form').removeClass('d-none');

        //     // Append to wrapper
        //     $('#form-items-wrapper2').append(newCard);

        //     // Re-initialize Select2 on both template card and new clone
        //     initCardSelect2(firstCard);
        //     initCardSelect2(newCard);

        // });

        // Remove Form Card Event
        $(document).on('click', '.btn-remove-form', function () {
            $(this).closest('.form-item-card').remove();
        });

        // Initialize Select2 on the first default form card

        // Initialize first card
        initCardSelect2('#form-items-wrapper2 .form-item-card:first');

        // 2. DATATABLES
        // Timeline DataTable
        var UsersTable1 = $('#UsersTable1').DataTable({
            responsive: true,
            lengthChange: true,
            autoWidth: false,
            serverSide: true,
            processing: true,
            ajax: {
                url: '{{ route('timeline.showAll') }}',
                type: 'GET'
            },
            columns: [
                { data: 'id' },
                { data: 'user_id' },
                { data: 'item_code' },
                { data: 'start_time' },
                { data: 'stop_time' },
                { data: 'status' }
            ],
            drawCallback: function (settings) {
                var api = this.api();
                var data = api.rows({ page: 'current' }).data();
                var $container = $('#timelineContainer').empty();

                if (data.length === 0) {
                    $container.html('<div class="text-center p-4 text-muted"><i class="fas fa-info-circle"></i> No timeline records found.</div>');
                    return;
                }

                data.each(function (row) {
                    var statusClass = 'bg-secondary';
                    var statusIcon = 'fa-clock';
                    var statusLower = (row.status || '').toLowerCase();

                    if (['completed', 'success', 'active'].includes(statusLower)) {
                        statusClass = 'bg-success';
                        statusIcon = 'fa-check';
                    } else if (['pending', 'in progress'].includes(statusLower)) {
                        statusClass = 'bg-warning';
                        statusIcon = 'fa-spinner fa-spin';
                    } else if (['failed', 'cancelled'].includes(statusLower)) {
                        statusClass = 'bg-danger';
                        statusIcon = 'fa-times';
                    }

                    var timelineItemHtml = `
                        <div>
                            <i class="fas ${statusIcon} ${statusClass}"></i>
                            <div class="timeline-item shadow-sm">
                                <span class="time"><i class="far fa-clock"></i> ${row.start_time || 'N/A'}</span>
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
                        </div>`;
                    $container.append(timelineItemHtml);
                });

                $container.append('<div><i class="far fa-clock bg-gray"></i></div>');
            }
        });

        // File Send DataTable
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
        //             render: function (data) {
        //                 return data ? `<a href="${data}" target="_blank" style="text-decoration: underline; color: blue;">${data}</a>` : '';
        //             }
        //         },
        //         { data: 'created_at' },
        //         { data: 'status' }
        //     ]
        // });

        // Search trigger helper
        window.search = function () {
            var val = $('#search').val();
            if (typeof InvoicesTable !== 'undefined') InvoicesTable.search(val).draw();
            if (typeof PODsTable !== 'undefined') PODsTable.search(val).draw();
        };

        // 3. SWEETALERT & NOTIFICATIONS
        var Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 5000
        });

        @if (\Session::has('success'))
            Toast.fire({
                icon: 'success',
                title: "{{ \Session::get('success') }}"
            });
        @endif

        // AJAX Form Submission
$('#aoRfaFormNew').on('submit', function (e) {
    e.preventDefault();
    var $form = $(this);

    Swal.fire({
        title: 'Submit?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, Send'
    }).then((result) => {
        if (!result.isConfirmed) return;

        Swal.fire({ 
            title: 'Sending...', 
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading() 
        });

        $.ajax({
            url: $form.attr('action'),
            type: 'POST',
            data: $form.serialize(),
            success: function (res) {
                Swal.fire({
                    title: 'Success!',
                    text: res.success || res.message || 'Sent successfully!',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((confirmResult) => {
                    if (confirmResult.isConfirmed || confirmResult.isDismissed) {
                        
                        // 1. Remove all dynamically added form cards (keeps only card #1)
                        $('#form-items-wrapper .form-item-card:not(:first)').remove();

                        // 2. Clear native input and textarea fields
                        $form[0].reset();

                        // 3. Reset Select2 fields inside the remaining default card
                        $('#form-items-wrapper .form-item-card:first select').val(null).trigger('change');

                        // 4. Update activity feed if HTML is returned
                        if (res.html) {
                            $('#activityFeedList').html(res.html);
                        }
                    }
                });
            },
            error: function () {
                Swal.fire('Error!', 'Something went wrong. Try again.', 'error');
            }
        });
    });
});

$('#aoRfaFormExisting').on('submit', function (e) {
    e.preventDefault();
    var $form = $(this);

    Swal.fire({
        title: 'Submit?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, Send'
    }).then((result) => {
        if (!result.isConfirmed) return;

        Swal.fire({ 
            title: 'Sending...', 
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading() 
        });

        $.ajax({
            url: $form.attr('action'),
            type: 'POST',
            data: $form.serialize(),
            success: function (res) {
                Swal.fire({
                    title: 'Success!',
                    text: res.success || res.message || 'Sent successfully!',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((confirmResult) => {
                    if (confirmResult.isConfirmed || confirmResult.isDismissed) {
                        
                        // 1. Remove all dynamically added form cards (keeps only card #1)
                        $('#form-items-wrapper .form-item-card:not(:first)').remove();

                        // 2. Clear native input and textarea fields
                        $form[0].reset();

                        // 3. Reset Select2 fields inside the remaining default card
                        $('#form-items-wrapper .form-item-card:first select').val(null).trigger('change');

                        // 4. Update activity feed if HTML is returned
                        if (res.html) {
                            $('#activityFeedList').html(res.html);
                        }
                    }
                });
            },
            error: function () {
                Swal.fire('Error!', 'Something went wrong. Try again.', 'error');
            }
        });
    });
});

        // 4. DYNAMIC DOM EVENT LISTENERS
        // Populate inputs when an item is selected from FACT Item Code
        $(document).on('select2:select', '.fact_item_code', function (e) {
            let d = e.params.data;
            let currentCard = $(this).closest('.form-item-card');
            console.log(d);
            const fieldMap = {
                'item_code[]': d.item_code,
                'file_info[]': d.item_desc,
                'spread_h[]': d.spread_h,
                'spread_w[]': d.spread_w,
                'spread_l[]': d.spread_l,
                'folded_h[]': d.folded_h,
                'folded_w[]': d.folded_w,
                'folded_l[]': d.folded_l,
                'colors[]': d.color,
                'client_name[]': d.customer_name,
                'others_spec1[]': d.others_spec1,
                'others_spec2[]': d.others_spec2,
                'others_spec3[]': d.others_spec3,
            };

            $.each(fieldMap, function (inputName, value) {
                currentCard.find(`input[name="${inputName}"]`).val(value || '').trigger('change');
            });

            Object.keys(d).forEach(function (key) {
                let el = currentCard.find('#' + key + ', [name="' + key + '[]"]');
                if (el.length) {
                    el.is('input, textarea, select') ? el.val(d[key]).trigger('change') : el.text(d[key]);
                }
            });

            currentCard.find('.spec-checkbox').prop('checked', false);

            if (typeof compareSpecs === 'function') {
                compareSpecs(currentCard);
            }
        });

        // Option cards selection
        $('.option-card').on('click', function () {
            $('.option-card').removeClass('border-primary shadow').css('border-width', '0');
            $(this).addClass('border-primary shadow').css('border', '2px solid #007bff');

            if ($(this).attr('id') === 'btn-single') {
                $('#single-form-container').removeClass('d-none');
            } else {
                $('#single-form-container').addClass('d-none');
            }
        });

        // Remove Form Card
        $(document).on('click', '.btn-remove-form', function () {
            $(this).closest('.form-item-card').remove();
        });

        // Notification Modal
        $(document).on('click', function (e) {
            if ($('#fbNotificationModal').hasClass('show')) {
                if (!$(e.target).closest('#fbNotificationModal .modal-content, #fbNotificationModalTrigger').length) {
                    $('#fbNotificationModal').modal('hide');
                }
            }
        });

        $('#markAllReadBtn').on('click', function () {
            $('#fbNotificationBadge').addClass('d-none').text('0');
            $('#activityFeedList .notification-item.unread-item').css('background-color', '#ffffff').removeClass('unread-item');
        });

        // Modals
        window.showMOSearch = function () {
            $('#SearchMOModal').modal('show');
        };

    });

    // 5. WINDOW LOAD & CLOCK
    window.addEventListener('load', function () {
        $('#select-tag').select2({
            placeholder: 'Search for a Item...',
            allowClear: true
        });
    });

    // Live Clock Routine
    $(function () {
        const $timeEl = $('#live-time');
        const $dateEl = $('#live-date');

        function updateClock() {
            const now = new Date();
            $timeEl.text(now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true }));
            $dateEl.text(now.toLocaleDateString('en-US', { weekday: 'long', month: 'long', day: 'numeric', year: 'numeric' }));
        }

        updateClock();
        setInterval(updateClock, 1000);
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

        // fetch fileSend supplied Verfication Table
var FileSendLinkTable = $('#aoApprovalSpecifications').DataTable({
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
        url: "{{ route('getSpecificationsAll') }}",
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
    { data: 'instruction_agreement' },
    { data: 'printing_process' },
    { data: 'special_process' },
    { data: 'efile_format' },
    { data: 'color_guide' },
    { data: 'output_digital_proof_pc' },
    { data: 'output_mockup_pc' },
    { data: 'output_digital_proof_check' },
    { data: 'output_mockup_check' },
    { data: 'date_requested' },
    { data: 'date_required' },
    { data: 'spec_size_spread' },
    { data: 'spec_size_folded' },
    { data: 'substrate' },
    { data: 'spec_number_of_colors' },
    { data: 'color_guide_others' },
    { data: 'reference_item_code' },
    { data: 'requested_by' },
  
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
    { data: 'updated_at' },
    { data: 'spread_h' },
    { data: 'spread_w' },
    { data: 'spread_l' },
    { data: 'folded_h' },
    { data: 'folded_w' },
    { data: 'folded_l' },
    // { 
    //     data: 'file_link',
    //     render: function(data) {
    //         if (!data) return '<span class="text-muted">No Link</span>';
    //         return '<a href="' + data + '" target="_blank" class="btn btn-xs btn-outline-primary">' +
    //                     '<i class="fas fa-external-link-alt mr-1"></i> View Link' +
    //                '</a>';
    //     }
    // },
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
// $('#btn-new-item').on('click', function() {
//     $('#aoRfaFormNew').css('display', 'block');
// });

});
</script>
@endsection