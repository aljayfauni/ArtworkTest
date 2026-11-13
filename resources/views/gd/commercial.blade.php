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
            <div class="col-md-6">
                <h1 class="mb-0 font-weight-bold">
                    <i class="fas fa-link text-primary mr-2"></i>
                   ArtWork File Link 
                </h1>
            </div>

        
         

        </div>
    </div>
</section>
    </div>
</div>
<div class="row mr-4">
    
    

    <div class="col-12">
    
        <div class="container-fluid">
           
        <div class="row">
            <div class="col-md-8">
            <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-solid fa-link"> </i> File Links List</h3>
            </div>
              <!-- /.card-header -->
            <div class="card-body">
                
                <br>
               
                    <div class="col-md-12">
                        <table id="FileSendTable" class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Sender Name</th>
                                    {{-- <th>Sender Email</th> --}}
                                    {{-- <th>Recipient Email</th> --}}
                                    <th>Shared File/Link</th>
                                    <th>Date Sent</th>
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
        <div class="col-md-4">
            <div class="card-body p-0" id="activityFeedList" style="max-height: 420px; overflow-y: auto;">
            @include('partials.artwork_logs')
            </div>
                </div>
       
               </div>
            </div>     

        {{-- end --}}

        {{-- request form   {{ route('artwork-request.store') }} --}}
        <form action="" method="POST">
    @csrf
    
    <div class="card card-primary card-outline">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center w-100">
                <h3 class="card-title text-uppercase font-weight-bold mb-0">
                    Supplied File Verification <small class="text-muted font-italic">(To be filled out by GD)</small>
                </h3>
                <span class="badge badge-secondary p-2">
                    {{-- <strong>RFA NUMBER:</strong> <span class="text-primary">{{ $rfaNumber ?? 'Auto-generated' }}</span> --}}
                </span>
            </div>
        </div>
        
        <div class="card-body">
            {{-- Client & Item Description --}}
            <div class="row">
                <div class="col-md-6 form-group">
                    <label for="client">Client <span class="text-danger">*</span></label>
                    <input type="text" name="client" id="client" class="form-control @error('client') is-invalid @enderror" value="{{ old('client') }}" placeholder="Enter client name" required>
                    @error('client')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-6 form-group">
                    <label for="item_description">Item Description <span class="text-danger">*</span></label>
                    <input type="text" name="item_description" id="item_description" class="form-control @error('item_description') is-invalid @enderror" value="{{ old('item_description') }}" placeholder="Enter item description" required>
                    @error('item_description')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <hr>

            {{-- File Source & Application --}}
            <div class="row">
                <div class="col-md-6 form-group">
                    <label class="d-block">File Source</label>
                    <div class="d-flex flex-wrap align-items-center">
                        <div class="custom-control custom-checkbox mr-3">
                            <input class="custom-control-input" type="checkbox" id="source_email" name="file_source[]" value="Email" {{ is_array(old('file_source')) && in_array('Email', old('file_source')) ? 'checked' : '' }}>
                            <label for="source_email" class="custom-control-label font-weight-normal">Email</label>
                        </div>
                        <div class="custom-control custom-checkbox mr-3">
                            <input class="custom-control-input" type="checkbox" id="source_cddvd" name="file_source[]" value="CD/DVD" {{ is_array(old('file_source')) && in_array('CD/DVD', old('file_source')) ? 'checked' : '' }}>
                            <label for="source_cddvd" class="custom-control-label font-weight-normal">CD/DVD</label>
                        </div>
                        <div class="custom-control custom-checkbox mr-3">
                            <input class="custom-control-input" type="checkbox" id="source_flashdisc" name="file_source[]" value="Flash Disc" {{ is_array(old('file_source')) && in_array('Flash Disc', old('file_source')) ? 'checked' : '' }}>
                            <label for="source_flashdisc" class="custom-control-label font-weight-normal">Flash Disc</label>
                        </div>
                    </div>
                    <div class="mt-2">
                        <input type="text" name="file_source_other" class="form-control form-control-sm" placeholder="Others: Specify" value="{{ old('file_source_other') }}">
                    </div>
                </div>

                <div class="col-md-6 form-group">
                    <label class="d-block">Application</label>
                    <div class="d-flex flex-wrap align-items-center">
                        <div class="custom-control custom-checkbox mr-3 mb-2">
                            <input class="custom-control-input" type="checkbox" id="app_photoshop" name="application[]" value="Photoshop" {{ is_array(old('application')) && in_array('Photoshop', old('application')) ? 'checked' : '' }}>
                            <label for="app_photoshop" class="custom-control-label font-weight-normal">Photoshop</label>
                        </div>
                        <div class="custom-control custom-checkbox mr-3 mb-2">
                            <input class="custom-control-input" type="checkbox" id="app_illustrator" name="application[]" value="Illustrator" {{ is_array(old('application')) && in_array('Illustrator', old('application')) ? 'checked' : '' }}>
                            <label for="app_illustrator" class="custom-control-label font-weight-normal">Illustrator</label>
                        </div>
                        <div class="custom-control custom-checkbox mr-3 mb-2">
                            <input class="custom-control-input" type="checkbox" id="app_indesign" name="application[]" value="InDesign" {{ is_array(old('application')) && in_array('InDesign', old('application')) ? 'checked' : '' }}>
                            <label for="app_indesign" class="custom-control-label font-weight-normal">InDesign</label>
                        </div>
                        <div class="custom-control custom-checkbox mr-3 mb-2">
                            <input class="custom-control-input" type="checkbox" id="app_acrobat" name="application[]" value="Acrobat" {{ is_array(old('application')) && in_array('Acrobat', old('application')) ? 'checked' : '' }}>
                            <label for="app_acrobat" class="custom-control-label font-weight-normal">Acrobat</label>
                        </div>
                    </div>
                </div>
            </div>

            <hr>

            {{-- Size & Specs Row --}}
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label for="size_spread" class="col-sm-3 col-form-label">Size: Spread</label>
                        <div class="col-sm-9">
                            <input type="text" name="size_spread" id="size_spread" class="form-control" value="{{ old('size_spread') }}" placeholder="Enter spread size">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="size_folded" class="col-sm-3 col-form-label">Folded</label>
                        <div class="col-sm-9">
                            <input type="text" name="size_folded" id="size_folded" class="form-control" value="{{ old('size_folded') }}" placeholder="Enter folded size">
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="row">
                        {{-- Color Specs --}}
                        <div class="col-md-4 form-group">
                            <label class="d-block">Color</label>
                            <div class="custom-control custom-checkbox mb-1">
                                <input class="custom-control-input" type="checkbox" id="color_cmyk" name="color[]" value="CMYK" {{ is_array(old('color')) && in_array('CMYK', old('color')) ? 'checked' : '' }}>
                                <label for="color_cmyk" class="custom-control-label font-weight-normal">CMYK</label>
                            </div>
                            <div class="custom-control custom-checkbox mb-1">
                                <input class="custom-control-input" type="checkbox" id="color_rgb" name="color[]" value="RGB" {{ is_array(old('color')) && in_array('RGB', old('color')) ? 'checked' : '' }}>
                                <label for="color_rgb" class="custom-control-label font-weight-normal">RGB</label>
                            </div>
                            <div class="custom-control custom-checkbox">
                                <input class="custom-control-input" type="checkbox" id="color_grayscale" name="color[]" value="Grayscale" {{ is_array(old('color')) && in_array('Grayscale', old('color')) ? 'checked' : '' }}>
                                <label for="color_grayscale" class="custom-control-label font-weight-normal">Grayscale</label>
                            </div>
                        </div>

                        {{-- Text Specs --}}
                        <div class="col-md-4 form-group">
                            <label class="d-block">Text</label>
                            <div class="custom-control custom-checkbox mb-1">
                                <input class="custom-control-input" type="checkbox" id="text_editable" name="text_specs[]" value="Editable" {{ is_array(old('text_specs')) && in_array('Editable', old('text_specs')) ? 'checked' : '' }}>
                                <label for="text_editable" class="custom-control-label font-weight-normal">Editable</label>
                            </div>
                            <div class="custom-control custom-checkbox mb-1">
                                <input class="custom-control-input" type="checkbox" id="text_path" name="text_specs[]" value="Converted to path" {{ is_array(old('text_specs')) && in_array('Converted to path', old('text_specs')) ? 'checked' : '' }}>
                                <label for="text_path" class="custom-control-label font-weight-normal">Converted to path</label>
                            </div>
                            <div class="custom-control custom-checkbox">
                                <input class="custom-control-input" type="checkbox" id="text_missing_fonts" name="text_specs[]" value="Missing Font(s)" {{ is_array(old('text_specs')) && in_array('Missing Font(s)', old('text_specs')) ? 'checked' : '' }}>
                                <label for="text_missing_fonts" class="custom-control-label font-weight-normal">Missing Font(s)</label>
                            </div>
                        </div>

                        {{-- Other Specs --}}
                        <div class="col-md-4 form-group">
                            <label class="d-block">Other Specs</label>
                            <div class="custom-control custom-checkbox mb-1">
                                <input class="custom-control-input" type="checkbox" id="spec_diecut" name="other_specs[]" value="Die-cut" {{ is_array(old('other_specs')) && in_array('Die-cut', old('other_specs')) ? 'checked' : '' }}>
                                <label for="spec_diecut" class="custom-control-label font-weight-normal">Die-cut</label>
                            </div>
                            <div class="custom-control custom-checkbox mb-1">
                                <input class="custom-control-input" type="checkbox" id="spec_stamping" name="other_specs[]" value="Stamping" {{ is_array(old('other_specs')) && in_array('Stamping', old('other_specs')) ? 'checked' : '' }}>
                                <label for="spec_stamping" class="custom-control-label font-weight-normal">Stamping</label>
                            </div>
                            <div class="custom-control custom-checkbox">
                                <input class="custom-control-input" type="checkbox" id="spec_uv" name="other_specs[]" value="UV" {{ is_array(old('other_specs')) && in_array('UV', old('other_specs')) ? 'checked' : '' }}>
                                <label for="spec_uv" class="custom-control-label font-weight-normal">UV</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <hr>

            {{-- Image Type & Number of Colors --}}
            <div class="row">
                <div class="col-md-6 form-group">
                    <label class="d-block">Image Type</label>
                    <div class="row">
                        <div class="col-6">
                            <div class="custom-control custom-checkbox mb-1">
                                <input class="custom-control-input" type="checkbox" id="img_layered" name="image_type[]" value="Layered" {{ is_array(old('image_type')) && in_array('Layered', old('image_type')) ? 'checked' : '' }}>
                                <label for="img_layered" class="custom-control-label font-weight-normal">Layered</label>
                            </div>
                            <div class="custom-control custom-checkbox mb-1">
                                <input class="custom-control-input" type="checkbox" id="img_missing" name="image_type[]" value="Missing Image(s)" {{ is_array(old('image_type')) && in_array('Missing Image(s)', old('image_type')) ? 'checked' : '' }}>
                                <label for="img_missing" class="custom-control-label font-weight-normal">Missing Image(s)</label>
                            </div>
                            <div class="custom-control custom-checkbox mb-1">
                                <input class="custom-control-input" type="checkbox" id="img_high_res" name="image_type[]" value="High Resolution" {{ is_array(old('image_type')) && in_array('High Resolution', old('image_type')) ? 'checked' : '' }}>
                                <label for="img_high_res" class="custom-control-label font-weight-normal">High Resolution</label>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="custom-control custom-checkbox mb-1">
                                <input class="custom-control-input" type="checkbox" id="img_embedded" name="image_type[]" value="Embedded" {{ is_array(old('image_type')) && in_array('Embedded', old('image_type')) ? 'checked' : '' }}>
                                <label for="img_embedded" class="custom-control-label font-weight-normal">Embedded</label>
                            </div>
                            <div class="custom-control custom-checkbox mb-1">
                                <input class="custom-control-input" type="checkbox" id="img_flattened" name="image_type[]" value="Flattened" {{ is_array(old('image_type')) && in_array('Flattened', old('image_type')) ? 'checked' : '' }}>
                                <label for="img_flattened" class="custom-control-label font-weight-normal">Flattened</label>
                            </div>
                            <div class="custom-control custom-checkbox mb-1">
                                <input class="custom-control-input" type="checkbox" id="img_low_res" name="image_type[]" value="Low Resolution" {{ is_array(old('image_type')) && in_array('Low Resolution', old('image_type')) ? 'checked' : '' }}>
                                <label for="img_low_res" class="custom-control-label font-weight-normal">Low Resolution</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="number_of_colors">Number of Colors</label>
                        <input type="text" name="number_of_colors" id="number_of_colors" class="form-control" value="{{ old('number_of_colors') }}" placeholder="e.g. 4-Color Process, Pantone 185C">
                    </div>

                    <div class="form-group row mt-4">
                        <label for="verified_by_date" class="col-sm-4 col-form-label">Verified by / Date</label>
                        <div class="col-sm-8">
                            <input type="text" name="verified_by_date" id="verified_by_date" class="form-control" value="{{ old('verified_by_date', auth()->user()->name . ' / ' . date('Y-m-d')) }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer text-right">
            <button type="reset" class="btn btn-default mr-2">Reset</button>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Submit Verification</button>
        </div>
    </div>
</form>
        {{-- end request form --}}


        {{-- specifications form  {{ route('specifications.store') }} --}}
        <form action="" method="POST">
    @csrf
    
    <div class="card card-info card-outline">
        <div class="card-header">
            <h3 class="card-title text-uppercase font-weight-bold">
                SPECIFICATIONS <small class="text-muted font-italic">(to be filled out by requestor) <span class="text-danger font-weight-bold">mandatory</span></small>
            </h3>
        </div>
        
        <div class="card-body">
            {{-- Item Code, Size & Printing Process --}}
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label for="item_code" class="col-sm-3 col-form-label">Item Code <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" name="item_code" id="item_code" class="form-control @error('item_code') is-invalid @enderror" value="{{ old('item_code') }}" placeholder="Enter item code" required>
                            @error('item_code')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="spec_size_spread" class="col-sm-3 col-form-label">Size: Spread</label>
                        <div class="col-sm-9">
                            <input type="text" name="spec_size_spread" id="spec_size_spread" class="form-control" value="{{ old('spec_size_spread') }}" placeholder="Enter spread size">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="spec_size_folded" class="col-sm-3 col-form-label">Folded</label>
                        <div class="col-sm-9">
                            <input type="text" name="spec_size_folded" id="spec_size_folded" class="form-control" value="{{ old('spec_size_folded') }}" placeholder="Enter folded size">
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="d-block">Printing Process</label>
                        <div class="d-flex flex-wrap align-items-center">
                            <div class="custom-control custom-checkbox mr-3 mb-2">
                                <input class="custom-control-input" type="checkbox" id="print_offset" name="printing_process[]" value="Offset" {{ is_array(old('printing_process')) && in_array('Offset', old('printing_process')) ? 'checked' : '' }}>
                                <label for="print_offset" class="custom-control-label font-weight-normal">Offset</label>
                            </div>
                            <div class="custom-control custom-checkbox mr-3 mb-2">
                                <input class="custom-control-input" type="checkbox" id="print_rotogravure" name="printing_process[]" value="Rotogravure" {{ is_array(old('printing_process')) && in_array('Rotogravure', old('printing_process')) ? 'checked' : '' }}>
                                <label for="print_rotogravure" class="custom-control-label font-weight-normal">Rotogravure</label>
                            </div>
                            <div class="custom-control custom-checkbox mr-3 mb-2">
                                <input class="custom-control-input" type="checkbox" id="print_flexo" name="printing_process[]" value="Flexo" {{ is_array(old('printing_process')) && in_array('Flexo', old('printing_process')) ? 'checked' : '' }}>
                                <label for="print_flexo" class="custom-control-label font-weight-normal">Flexo</label>
                            </div>
                            <div class="custom-control custom-checkbox mr-3 mb-2">
                                <input class="custom-control-input" type="checkbox" id="print_media" name="printing_process[]" value="Print Media" {{ is_array(old('printing_process')) && in_array('Print Media', old('printing_process')) ? 'checked' : '' }}>
                                <label for="print_media" class="custom-control-label font-weight-normal">Print Media</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="substrate" class="col-sm-4 col-form-label">Substrate</label>
                        <div class="col-sm-8">
                            <input type="text" name="substrate" id="substrate" class="form-control" value="{{ old('substrate') }}" placeholder="Enter substrate material">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="spec_number_of_colors" class="col-sm-4 col-form-label">Number of Colors</label>
                        <div class="col-sm-8">
                            <input type="text" name="spec_number_of_colors" id="spec_number_of_colors" class="form-control" value="{{ old('spec_number_of_colors') }}" placeholder="Enter color count / details">
                        </div>
                    </div>
                </div>
            </div>

            <hr>

            {{-- Special Process --}}
            <div class="form-group">
                <label class="d-block">Special Process</label>
                <div class="d-flex flex-wrap align-items-center">
                    <div class="custom-control custom-checkbox mr-4 mb-2">
                        <input class="custom-control-input" type="checkbox" id="sp_embossing" name="special_process[]" value="Embossing" {{ is_array(old('special_process')) && in_array('Embossing', old('special_process')) ? 'checked' : '' }}>
                        <label for="sp_embossing" class="custom-control-label font-weight-normal">Embossing</label>
                    </div>
                    <div class="custom-control custom-checkbox mr-4 mb-2">
                        <input class="custom-control-input" type="checkbox" id="sp_stamping" name="special_process[]" value="Stamping" {{ is_array(old('special_process')) && in_array('Stamping', old('special_process')) ? 'checked' : '' }}>
                        <label for="sp_stamping" class="custom-control-label font-weight-normal">Stamping</label>
                    </div>
                    <div class="custom-control custom-checkbox mr-4 mb-2">
                        <input class="custom-control-input" type="checkbox" id="sp_spot_varnish" name="special_process[]" value="Spot Varnish" {{ is_array(old('special_process')) && in_array('Spot Varnish', old('special_process')) ? 'checked' : '' }}>
                        <label for="sp_spot_varnish" class="custom-control-label font-weight-normal">Spot Varnish</label>
                    </div>
                    <div class="custom-control custom-checkbox mr-4 mb-2">
                        <input class="custom-control-input" type="checkbox" id="sp_spot_uv" name="special_process[]" value="Spot UV/ UV Varnish" {{ is_array(old('special_process')) && in_array('Spot UV/ UV Varnish', old('special_process')) ? 'checked' : '' }}>
                        <label for="sp_spot_uv" class="custom-control-label font-weight-normal">Spot UV / UV Varnish</label>
                    </div>
                    <div class="custom-control custom-checkbox mr-4 mb-2">
                        <input class="custom-control-input" type="checkbox" id="sp_matte_varnish" name="special_process[]" value="Matte Varnish" {{ is_array(old('special_process')) && in_array('Matte Varnish', old('special_process')) ? 'checked' : '' }}>
                        <label for="sp_matte_varnish" class="custom-control-label font-weight-normal">Matte Varnish</label>
                    </div>
                </div>
            </div>

            {{-- Output Specs --}}
            <div class="row align-items-center mb-3">
                <div class="col-md-1">
                    <label class="m-0">Output</label>
                </div>
                <div class="col-md-11">
                    <div class="d-flex flex-wrap align-items-center">
                        <div class="custom-control custom-checkbox mr-2">
                            <input class="custom-control-input" type="checkbox" id="out_digital_proof" name="output_digital_proof_check" value="1" {{ old('output_digital_proof_check') ? 'checked' : '' }}>
                            <label for="out_digital_proof" class="custom-control-label font-weight-normal">Digital Proof (</label>
                        </div>
                        <input type="number" name="output_digital_proof_pc" class="form-control form-control-sm mr-2" style="width: 80px;" value="{{ old('output_digital_proof_pc') }}" placeholder="pc.">
                        <span class="mr-4">)</span>

                        <div class="custom-control custom-checkbox mr-2">
                            <input class="custom-control-input" type="checkbox" id="out_mockup" name="output_mockup_check" value="1" {{ old('output_mockup_check') ? 'checked' : '' }}>
                            <label for="out_mockup" class="custom-control-label font-weight-normal">Mock-up (</label>
                        </div>
                        <input type="number" name="output_mockup_pc" class="form-control form-control-sm mr-2" style="width: 80px;" value="{{ old('output_mockup_pc') }}" placeholder="pc.">
                        <span class="mr-4">)</span>

                        <span class="mr-2 font-weight-bold">E-File ( Format ):</span>
                        <div class="custom-control custom-checkbox mr-3">
                            <input class="custom-control-input" type="checkbox" id="efile_pdf" name="efile_format[]" value="PDF" {{ is_array(old('efile_format')) && in_array('PDF', old('efile_format')) ? 'checked' : '' }}>
                            <label for="efile_pdf" class="custom-control-label font-weight-normal">PDF</label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input class="custom-control-input" type="checkbox" id="efile_jpeg" name="efile_format[]" value="JPEG" {{ is_array(old('efile_format')) && in_array('JPEG', old('efile_format')) ? 'checked' : '' }}>
                            <label for="efile_jpeg" class="custom-control-label font-weight-normal">JPEG</label>
                        </div>
                    </div>
                </div>
            </div>

            {{-- With Color Guide & Reference --}}
            <div class="row align-items-center mb-3">
                <div class="col-md-2">
                    <label class="m-0">With Color Guide</label>
                </div>
                <div class="col-md-10">
                    <div class="d-flex flex-wrap align-items-center">
                        <div class="custom-control custom-checkbox mr-4">
                            <input class="custom-control-input" type="checkbox" id="cg_product_sample" name="color_guide[]" value="Product Sample" {{ is_array(old('color_guide')) && in_array('Product Sample', old('color_guide')) ? 'checked' : '' }}>
                            <label for="cg_product_sample" class="custom-control-label font-weight-normal">Product Sample</label>
                        </div>
                        <div class="custom-control custom-checkbox mr-4">
                            <input class="custom-control-input" type="checkbox" id="cg_lsd" name="color_guide[]" value="LSD" {{ is_array(old('color_guide')) && in_array('LSD', old('color_guide')) ? 'checked' : '' }}>
                            <label for="cg_lsd" class="custom-control-label font-weight-normal">LSD</label>
                        </div>
                        <div class="custom-control custom-checkbox mr-2">
                            <input class="custom-control-input" type="checkbox" id="cg_others" name="color_guide[]" value="Others" {{ is_array(old('color_guide')) && in_array('Others', old('color_guide')) ? 'checked' : '' }}>
                            <label for="cg_others" class="custom-control-label font-weight-normal">Others:</label>
                        </div>
                        <div class="flex-fill">
                            <input type="text" name="color_guide_others" class="form-control form-control-sm" value="{{ old('color_guide_others') }}" placeholder="Specify other color guide details">
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <label for="reference_item_code" class="col-sm-2 col-form-label">Reference Item Code</label>
                <div class="col-sm-10">
                    <input type="text" name="reference_item_code" id="reference_item_code" class="form-control" value="{{ old('reference_item_code') }}" placeholder="Enter reference item code">
                </div>
            </div>

            <hr>

            {{-- Request Details & Signatures --}}
            <div class="row">
                <div class="col-md-4 form-group">
                    <label for="requested_by">Requested by</label>
                    <input type="text" name="requested_by" id="requested_by" class="form-control" value="{{ old('requested_by', auth()->user()->name ?? '') }}" placeholder="Name / Signature">
                    <small class="form-text text-muted text-center">Signature over printed name</small>
                </div>

                <div class="col-md-4 form-group">
                    <label for="date_requested">Date Requested</label>
                    <input type="date" name="date_requested" id="date_requested" class="form-control" value="{{ old('date_requested', date('Y-m-d')) }}">
                </div>

                <div class="col-md-4 form-group">
                    <label for="date_required">Date Required</label>
                    <input type="date" name="date_required" id="date_required" class="form-control" value="{{ old('date_required') }}">
                </div>
            </div>

            <div class="form-group">
                <label for="instruction_agreement">Instruction / Agreement</label>
                <textarea name="instruction_agreement" id="instruction_agreement" rows="3" class="form-control" placeholder="Enter instructions or agreement notes">{{ old('instruction_agreement') }}</textarea>
            </div>
        </div>

        <div class="card-footer d-flex justify-content-between align-items-center">
            <span class="text-muted font-weight-bold"></span>
            <div>
                <button type="reset" class="btn btn-default mr-2">Reset</button>
                <button type="submit" class="btn btn-info"><i class="fas fa-paper-plane mr-1"></i> Save Specifications</button>
            </div>
        </div>
    </div>
</form>
        {{-- end specification --}}

        {{-- checklist form test --}}


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
                    <p class="text-muted">
                                                    <span class="badge badge-warning">Unverified</span>
                                            </p>
                </div>
                <div class="card-footer text-right">
                    <a href="http://localhost:9000/users/1/edit" class="btn btn-primary btn-sm">
                        <i class="fas fa-edit mr-1"></i> Edit Profile
                    </a>
                </div>
            </div>
        </div>
    </div>

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
{{-- vectical timeline design --}}

<!-- AdminLTE Timeline Container -->
{{-- <div id="timelineContainer" class="timeline timeline-inverse">
    @forelse($timeLineList as $row)
         --}}
        {{-- 1. DYNAMIC START BLOCK --}}
        {{-- @if($row->start_time)
            <div>
                <i class="fas fa-play bg-success"></i>
                <div class="timeline-item shadow-sm mb-3">
                    <span class="time text-muted">
                        <i class="far fa-clock"></i> 
                        {{ \Carbon\Carbon::parse($row->start_time)->format('h:i A') }}
                    </span>
                    <h3 class="timeline-header font-weight-bold border-0">
                        <span class="badge badge-primary mr-2">Code: {{ $row->item_code }}</span> 
                        <strong>{{ $row->item_name ?? 'Unknown Item' }}</strong> 
                        <span class="text-success ml-2">Started</span>
                    </h3>
                    <div class="timeline-body pt-1 text-muted small">
                        Tracking cycle initialized on <strong>{{ \Carbon\Carbon::parse($row->start_time)->format('M d, Y') }}</strong>
                    </div>
                </div>
            </div>
        @endif --}}

        {{-- 2. DYNAMIC STOP BLOCK --}}
        {{-- @if($row->stop_time)
            <div>
                <i class="fas fa-stop bg-danger"></i>
                <div class="timeline-item shadow-sm mb-3">
                    <span class="time text-muted">
                        <i class="far fa-clock"></i> 
                        {{ \Carbon\Carbon::parse($row->stop_time)->format('h:i A') }}
                    </span>
                    <h3 class="timeline-header font-weight-bold border-0">
                        <span class="badge badge-primary mr-2">Code: {{ $row->item_code }}</span> 
                        <strong>{{ $row->item_name ?? 'Unknown Item' }}</strong> 
                        <span class="text-danger ml-2">Stopped</span>
                    </h3>
                    <div class="timeline-body pt-1 text-muted small">
                        Process completed on <strong>{{ \Carbon\Carbon::parse($row->stop_time)->format('M d, Y') }}</strong>
                        @if($row->start_time)
                            <span class="badge badge-secondary ml-2 py-1 px-2 font-weight-normal">
                                <i class="fas fa-hourglass-half mr-1"></i>
                                Duration: {{ \Carbon\Carbon::parse($row->start_time)->diffForHumans(\Carbon\Carbon::parse($row->stop_time), true) }}
                            </span>
                        @endif
                    </div>
                </div>
            </div> --}}
        {{-- @endif

    @empty
        <div class="text-center p-4 text-muted">
            <i class="fas fa-history fa-2x mb-2 text-gray"></i>
            <p class="m-0">No continuous timeline records found.</p>
        </div>
    @endforelse --}}

    <!-- Final endpoint marker -->
    {{-- <div>
        <i class="fas fa-clock bg-gray text-white"></i>
    </div>
</div> --}}

{{-- <div class="horizontal-timeline-wrapper card-body border-top">

    @php
        $groupedTimeline = collect($timeLineList)->groupBy('item_code');
    @endphp

    @forelse($groupedTimeline as $itemCode => $events)

        @php
            $firstRow = $events->first();
        @endphp

        <div class="item-timeline-lane w-100"> --}}

            {{-- ITEM HEADER --}}
            {{-- <div class="item-lane-header">

                <div class="item-title-area">
                    <div class="item-title-icon">
                        <i class="fas fa-box-open"></i>
                    </div>

                    <div>
                        <h5 class="font-weight-bold text-dark mb-1">
                            {{ $firstRow->item_name ?? 'Unknown Item' }}
                        </h5>

                        <small class="text-muted">
                            Activity timeline
                        </small>
                    </div>
                </div>

                <span class="item-code-badge">
                    <i class="fas fa-hashtag mr-1"></i>
                    {{ $itemCode ?? 'N/A' }}
                </span>

            </div> --}}

            {{-- HORIZONTAL TIMELINE --}}
            {{-- <div class="horizontal-scroll-lane">

                @foreach($events as $row) --}}

                    {{-- START EVENT --}}
                    {{-- @if($row->start_time)

                        <div class="timeline-node">

                            <div class="node-icon-badge bg-success"></div>

                            <div class="timeline-modern-card">

                                <div class="timeline-card-header">

                                    <span class="timeline-status started">
                                        <i class="fas fa-play-circle"></i>
                                        STARTED
                                    </span>

                                    <span class="timeline-clock">
                                        <i class="far fa-clock mr-1"></i>
                                        {{ \Carbon\Carbon::parse($row->start_time)->format('h:i A') }}
                                    </span>

                                </div>

                                <div class="timeline-card-body">

                                    <div class="timeline-user-box">
                                        <div class="timeline-user-avatar">
                                            <i class="fas fa-user"></i>
                                        </div>

                                        <div>
                                            <div class="timeline-user-name">
                                                {{ $row->user_name ?? 'Unknown User' }}
                                            </div>

                                            <span class="timeline-user-id">
                                                User ID: {{ $row->user_id ?? 'N/A' }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="timeline-date">
                                        <i class="far fa-calendar-alt mr-1"></i>
                                        Date:
                                        <strong>
                                            {{ \Carbon\Carbon::parse($row->start_time)->format('M d, Y') }}
                                        </strong>
                                    </div>

                                </div>
                            </div>
                        </div>

                    @endif --}}

                    {{-- STOP EVENT --}}
                    {{-- @if($row->stop_time)

                        <div class="timeline-node">

                            <div class="node-icon-badge bg-danger"></div>

                            <div class="timeline-modern-card">

                                <div class="timeline-card-header">

                                    <span class="timeline-status stopped">
                                        <i class="fas fa-stop-circle"></i>
                                        STOPPED
                                    </span>

                                    <span class="timeline-clock">
                                        <i class="far fa-clock mr-1"></i>
                                        {{ \Carbon\Carbon::parse($row->stop_time)->format('h:i A') }}
                                    </span>

                                </div>

                                <div class="timeline-card-body">

                                    <div class="timeline-user-box">
                                        <div class="timeline-user-avatar">
                                            <i class="fas fa-user"></i>
                                        </div>

                                        <div>
                                            <div class="timeline-user-name">
                                                {{ $row->user_name ?? 'Unknown User' }}
                                            </div>

                                            <span class="timeline-user-id">
                                                User ID: {{ $row->user_id ?? 'N/A' }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="timeline-date">
                                        <i class="far fa-calendar-alt mr-1"></i>
                                        Date:
                                        <strong>
                                            {{ \Carbon\Carbon::parse($row->stop_time)->format('M d, Y') }}
                                        </strong>
                                    </div>

                                    @if($row->start_time)
                                        <div class="timeline-duration">
                                            <i class="fas fa-hourglass-half"></i>

                                            <span>
                                                Duration:
                                                {{ \Carbon\Carbon::parse($row->start_time)->diffForHumans(
                                                    \Carbon\Carbon::parse($row->stop_time),
                                                    true
                                                ) }}
                                            </span>
                                        </div>
                                    @endif

                                </div>
                            </div>
                        </div>

                    @endif

                @endforeach

            </div>
        </div>

    @empty

        <div class="timeline-empty w-100">
            <i class="fas fa-history"></i>

            <h6 class="font-weight-bold text-secondary">
                No timeline activity yet
            </h6>

            <p class="small mb-0">
                Activity records will appear here when available.
            </p>
        </div>

    @endforelse

</div> --}}

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

@include('partials.modal.item_search')

    </div>
</div> 

@endsection

@section('js')
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
    ajax: {
        url: "{{ route('FileRecievedLinkShowAll') }}",
        type: 'GET'
    },
    columns: [
        { data: 'ao_name' },
        // { data: 'from_email' },
        // { data: 'to_email' },

        // { 
        //     data: 'file_link',
        //     render: function(data) {
        //         if (!data) return '';
        //         // Renders as a standard clickable blueprint hyperlink
        //         return '<a href="' + data + '" target="_blank" style="text-decoration: underline; color: blue;">' + data + '</a>';
        //     }
        // },
        // update the status of file when open
        {
            data: 'file_link',
            render: function(data, type, row) {
                if (!data) return '';

                return `<a href="${data}"
                        class="file-link"
                        data-id="${row.id}"
                        target="_blank"
                        style="text-decoration: underline; color: blue;">
                        ${data}
                        </a>`;
            }
        },
        { data: 'created_at' },
        { data: 'status' },
         
    ]
});

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

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
@endsection