@extends('adminlte::page')

@section('title', 'RFA Details - Acme Corp')

{{-- @section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark font-weight-bold">RFA Submission Details</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item"><a href="#">AO RFA Submissions</a></li>
                    <li class="breadcrumb-item active">View Details</li>
                </ol>
            </div>
        </div>
    </div>
@stop --}}

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-10 mt-4">

                <!-- Header Actions -->
                <div class="d-flex justify-content-between align-items-center mb-3 ">
                    <a href="{{ route('rfa_file_list') }}" class="btn btn-outline-secondary rounded-pill px-3 shadow-none">
                        <i class="fas fa-arrow-left mr-1"></i> Back to View
                    </a>
                </div>

                <!-- Main Card Layout -->
                <div class="card card-widget shadow-sm border-0 mb-4" style="border-radius: 16px; overflow: hidden;">

                    <!-- Card Header with Metadata Style -->
                    <div class="card-header bg-white border-bottom pt-4 pb-3 px-4 d-flex justify-content-between align-items-center">
                        <div>
                            {{-- <span class="badge badge-primary px-3 py-1 font-weight-normal mb-2" style="border-radius: 12px; font-size: 0.75rem;">Editing RFA ID #1024</span> --}}
                            <h3 class="card-title font-weight-bold text-dark d-block" style="font-size: 1.2rem; letter-spacing: -0.3px;">
                                {{ $item_detail->client_name ?? ''}}
                            </h3>
                        </div>
                        <div class="text-right text-muted" style="font-size: 0.8rem;">
                            <i class="far fa-clock mr-1"></i> Originally Submitted   {{ $item_detail->created_at ?? ''}}
                        </div>
                    </div>

                    <!-- Dynamic Ajax Alert Container -->
                    <div id="ajax-alert-container" class="px-4 mt-3"></div>

                    <form id="aoRfaEditForm" action="#" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="card-body bg-white px-4 py-4">

                            <!-- Section 1: File & General Information -->
                            <h6 class="text-uppercase text-secondary font-weight-bold mb-3" style="font-size: 0.75rem; letter-spacing: 0.5px;">File & General Information</h6>
                            
                            <div class="row mb-3">
                                <!-- Client Name -->
                                <div class="col-md-4 mb-3">
                                    <div class="p-3 bg-light rounded-lg h-100">
                                        <label for="client_name" class="text-uppercase text-muted d-block mb-1" style="font-size: 0.7rem; font-weight: 600;">
                                            Client Name <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-white border-right-0"><i class="fas fa-user text-primary" style="font-size: 0.85rem;"></i></span>
                                            </div>
                                            <input type="text" 
                                                name="client_name" 
                                                id="client_name" 
                                                class="form-control border-left-0 bg-white" 
                                                placeholder="Enter Client Name" 
                                                value= "{{ $item_detail->client_name ?? ''}}"
                                                style="font-size: 0.9rem;"
                                                required>
                                            <span class="invalid-feedback" id="error-client_name"></span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Item Description -->
                                <div class="col-md-4 mb-3">
                                    <div class="p-3 bg-light rounded-lg h-100">
                                        <label for="file_info" class="text-uppercase text-muted d-block mb-1" style="font-size: 0.7rem; font-weight: 600;">
                                            Item Description <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-white border-right-0"><i class="fas fa-file-alt text-info" style="font-size: 0.85rem;"></i></span>
                                            </div>
                                            <input type="text" 
                                                name="file_info" 
                                                id="file_info" 
                                                class="form-control border-left-0 bg-white" 
                                                placeholder="Enter File Info" 
                                                value="  {{ $item_detail->file_info ?? ''}}" 
                                                style="font-size: 0.9rem;"
                                                required>
                                            <span class="invalid-feedback" id="error-file_info"></span>
                                        </div>
                                    </div>
                                </div>

                                <!-- File URL / Link -->
                                <div class="col-md-4 mb-3">
                                    <div class="p-3 bg-light rounded-lg h-100">
                                        <label for="link" class="text-uppercase text-muted d-block mb-1" style="font-size: 0.7rem; font-weight: 600;">
                                            File URL / Link <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-white border-right-0"><i class="fas fa-external-link-alt text-primary" style="font-size: 0.85rem;"></i></span>
                                            </div>
                                            <input type="url" 
                                                name="link" 
                                                id="link" 
                                                class="form-control border-left-0 bg-white" 
                                                placeholder="https://google.com" 
                                                value="  {{ $item_detail->file_link ?? ''}}" 
                                                style="font-size: 0.9rem;"
                                                required>
                                            <span class="invalid-feedback" id="error-link"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Section 2: Print & Design Specifications -->
                            <h6 class="text-uppercase text-secondary font-weight-bold mb-3" style="font-size: 0.75rem; letter-spacing: 0.5px;">Print & Design Specifications</h6>
                            
                            <div class="row mb-3">
                                <!-- Spread size -->
                                <div class="col-md-4 mb-3">
                                    <div class="p-3 border rounded-lg h-100">
                                        <label for="spread_size" class="text-uppercase text-muted d-block mb-1" style="font-size: 0.7rem; font-weight: 600;">Spread Size</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-light border-right-0"><i class="fas fa-ruler text-secondary" style="font-size: 0.85rem;"></i></span>
                                            </div>
                                            <input type="text" 
                                                name="spread_size" 
                                                id="spread_size" 
                                                class="form-control border-left-0 bg-light" 
                                                placeholder="Enter Spread Size" 
                                                value="  {{ $item_detail->spread_size ?? ''}}" 
                                                style="font-size: 0.9rem;">
                                            <span class="invalid-feedback" id="error-spread_size"></span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Folded size -->
                                <div class="col-md-4 mb-3">
                                    <div class="p-3 border rounded-lg h-100">
                                        <label for="folded_size" class="text-uppercase text-muted d-block mb-1" style="font-size: 0.7rem; font-weight: 600;">Folded Size</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-light border-right-0"><i class="fas fa-map text-secondary" style="font-size: 0.85rem;"></i></span>
                                            </div>
                                            <input type="text" 
                                                name="folded_size" 
                                                id="folded_size" 
                                                class="form-control border-left-0 bg-light" 
                                                placeholder="Enter Folded Size" 
                                                value="  {{ $item_detail->folded_size ?? ''}}" 
                                                style="font-size: 0.9rem;">
                                            <span class="invalid-feedback" id="error-folded_size"></span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Type of Image -->
                                <div class="col-md-4 mb-3">
                                    <div class="p-3 border rounded-lg h-100">
                                        <label for="image_type" class="text-uppercase text-muted d-block mb-1" style="font-size: 0.7rem; font-weight: 600;">Image Type</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-light border-right-0"><i class="fas fa-image text-secondary" style="font-size: 0.85rem;"></i></span>
                                            </div>
                                            <input type="text" 
                                                name="image_type" 
                                                id="image_type" 
                                                class="form-control border-left-0 bg-light" 
                                                placeholder="Enter Image Type" 
                                                value="  {{ $item_detail->image_type ?? ''}}" 
                                                style="font-size: 0.9rem;">
                                            <span class="invalid-feedback" id="error-image_type"></span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Colors -->
                                <div class="col-md-6 mb-3">
                                    <div class="p-3 border rounded-lg h-100">
                                        <label for="colors" class="text-uppercase text-muted d-block mb-1" style="font-size: 0.7rem; font-weight: 600;">Colors</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-light border-right-0"><i class="fas fa-brush text-secondary" style="font-size: 0.85rem;"></i></span>
                                            </div>
                                            <input type="text" 
                                                name="colors" 
                                                id="colors" 
                                                class="form-control border-left-0 bg-light" 
                                                placeholder="Enter Colors" 
                                                value="  {{ $item_detail->colors ?? ''}}" 
                                                style="font-size: 0.9rem;">
                                            <span class="invalid-feedback" id="error-colors"></span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Number of Colors -->
                                <div class="col-md-6 mb-3">
                                    <div class="p-3 border rounded-lg h-100">
                                        <label for="num_colors" class="text-uppercase text-muted d-block mb-1" style="font-size: 0.7rem; font-weight: 600;">Number of Colors</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-light border-right-0"><i class="fas fa-asterisk text-secondary" style="font-size: 0.85rem;"></i></span>
                                            </div>
                                            <input type="number" 
                                                name="num_colors" 
                                                id="num_colors" 
                                                class="form-control border-left-0 bg-light" 
                                                placeholder="Enter Number of Colors" 
                                                value="  {{ $item_detail->num_of_colors ?? '0'}}" 
                                                style="font-size: 0.9rem;">
                                            <span class="invalid-feedback" id="error-num_colors"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Section 3: Recipients (GD) -->
                            <h6 class="text-uppercase text-secondary font-weight-bold mb-3" style="font-size: 0.75rem; letter-spacing: 0.5px;">Recipients (GD)</h6>
                            <div class="mb-4 p-3 bg-light rounded-lg">
                                <label for="employee_email" class="text-uppercase text-muted d-block mb-2" style="font-size: 0.7rem; font-weight: 600;">
                                    Assigned Emails <span class="text-danger">*</span>
                                </label>
                                <div class="input-group flex-nowrap">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white border-right-0"><i class="fas fa-envelope text-success" style="font-size: 0.85rem;"></i></span>
                                    </div>

                                    @php
                                // Determine selected values: priority to old input, fallback to DB selected emails
                                    $rawSelected = old('employee_email', $selectedEmails ?? []);
                                    if (!is_array($rawSelected)) {
                                        $rawSelected = [$rawSelected];
                                    }
                                    // Normalize selected array to lowercase for safe comparisons
                                    $selectedList = array_map('strtolower', array_map('trim', $rawSelected));

                                    // Track user emails already listed to render non-user emails seamlessly
                                    $renderedEmails = [];
                                 @endphp
                                <select id="employee_email" 
                                name="employee_email[]" 
                                class="form-control select2 border-left-0 bg-white" 
                                multiple="multiple"
                                data-placeholder="Select GD Emails"
                                style="width: 100%;">
                                
                            @foreach($users as $user)
                                @php 
                                    $userEmailLower = strtolower(trim($user->email));
                                    $renderedEmails[] = $userEmailLower;
                                @endphp
                                <option value="{{ $user->email }}" 
                                    {{ in_array($userEmailLower, $selectedList) ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                                @endforeach

                            {{-- Render saved emails that are NOT in the User table --}}
                            @foreach($selectedList as $customEmail)
                                @if(!in_array($customEmail, $renderedEmails) && !empty($customEmail))
                                    <option value="{{ $customEmail }}" selected>
                                        {{ $customEmail }}
                                    </option>
                                @endif
                            @endforeach
                            </select>
                                </div>
                                <span class="invalid-feedback d-block" id="error-employee_email"></span>
                            </div>

                            <!-- Section 4: Notes / Description -->
                            <h6 class="text-uppercase text-secondary font-weight-bold mb-3" style="font-size: 0.75rem; letter-spacing: 0.5px;">Notes / Description</h6>
                            <div class="p-3 border rounded-lg bg-light">
                                <label for="description" class="text-uppercase text-muted d-block mb-1" style="font-size: 0.7rem; font-weight: 600;">Instructions / Remarks</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white border-right-0"><i class="fas fa-comment-alt text-muted" style="font-size: 0.85rem;"></i></span>
                                    </div>
                                    <textarea name="description" 
                                            id="description" 
                                            class="form-control border-left-0 bg-white" 
                                            placeholder="Descriptions or Notes" 
                                            style="font-size: 0.9rem;"
                                            rows="3">{{ $item_detail->description ?? '' }}</textarea>
                                    <span class="invalid-feedback" id="error-description"></span>
                                </div>
                            </div>

                        </div>

                        <!-- Card Footer Actions -->
                        <div class="card-footer bg-white border-top pb-4 pt-3 px-4 d-flex justify-content-between align-items-center">
                            <a href="{{ route('rfa_file_list') }}" class="btn btn-light rounded-pill px-4 py-2 text-secondary font-weight-bold" style="font-size: 0.88rem;">
                                Cancel
                            </a>
                            <button type="submit" id="btn_update_rfa" class="btn btn-warning text-dark font-weight-bold rounded-pill px-4 py-2 shadow-none" style="font-size: 0.88rem;">
                                <span class="btn-text">
                                    <i class="fas fa-save mr-2" style="font-size: 0.8rem;"></i> Save Changes
                                </span>
                                <span class="btn-loading d-none">
                                    <i class="fas fa-spinner fa-spin mr-2" style="font-size: 0.85rem;"></i> Updating...
                                </span>
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .select2-container--bootstrap4 .select2-selection--multiple {
            border-top-left-radius: 0 !important;
            border-bottom-left-radius: 0 !important;
            background-color: #ffffff !important;
            border-color: #ced4da !important;
            min-height: 38px;
        }
        .select2-container {
            flex: 1 1 auto;
            width: auto !important;
        }
    </style>
@stop
@section('js')
<script>
    $(document).ready(function() {
        $('#employee_email').select2({
            tags: true, // Allows user to type and add new email addresses
            tokenSeparators: [',', ' '],
            placeholder: "Select GD Emails"
        });
    });
</script>
@endsection