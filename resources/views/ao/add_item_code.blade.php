@extends('adminlte::page')

@section('title', 'RFA Details ')


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

                {{-- add item code --}}
<div class="card border bg-light shadow-sm" style="border-radius: 12px;">
  <form id="addItemCodeForm" method="POST" action="">
    @csrf
    
    <div class="card-body p-4">
      <div class="form-group mb-3">
        <label for="item_code" class="font-weight-bold text-dark mb-2">Enter Item Code</label>
        <input 
          type="text" 
          name="item_code" 
          id="item_code" 
          class="form-control bg-white border" 
          style="border-radius: 8px;"
          placeholder="Item Code" 
          required
        >
      </div>

      <!-- Hidden Inputs -->
      <input type="hidden" name="rfa_reference" id="rfa_reference">
      <input type="hidden" name="status" id="modalStatus">

      <div class="text-right">
        <button type="submit" class="btn btn-primary btn-sm px-4 font-weight-bold" style="border-radius: 8px;" id="btnSaveItemCode">
          <i class="fas fa-save mr-1"></i> Save Item Code
        </button>
      </div>
    </div>

  </form>
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
                               
                                {{-- add item code --}}
   
                                

                                   <div class="col-md-4 mb-3">
                                    <div class="p-3 bg-light rounded-lg h-100">
                                        <label for="client_name" class="text-uppercase text-muted d-block mb-1" style="font-size: 0.7rem; font-weight: 600;">
                                            item_code <span class="text-danger"></span>
                                        </label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-white border-right-0"><i class="fas fa-user text-primary" style="font-size: 0.85rem;"></i></span>
                                            </div>
                                            <input type="text" 
                                                name=" item_code" 
                                                id=" item_code" 
                                                class="form-control border-left-0 bg-white" 
                                                placeholder="Enter  item_code " 
                                                value= "{{ $item_detail->item_code ?? ''}}"
                                                style="font-size: 0.9rem;"
                                                required disabled>
                                            <span class="invalid-feedback" id="error- item_code"></span>
                                        </div>
                                    </div>
                                </div>
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
                                        <label for="spread_h" class="text-uppercase text-muted d-block mb-1" style="font-size: 0.7rem; font-weight: 600;">Spread Size H</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-light border-right-0"><i class="fas fa-ruler text-secondary" style="font-size: 0.85rem;"></i></span>
                                            </div>
                                            <input type="text" 
                                                name="spread_h" 
                                                id="spread_h" 
                                                class="form-control border-left-0 bg-light" 
                                                placeholder="Enter Spread Size H" 
                                                value="  {{ $item_detail->spread_h ?? ''}}" 
                                                style="font-size: 0.9rem;">
                                            <span class="invalid-feedback" id="error-spread_h"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <div class="p-3 border rounded-lg h-100">
                                        <label for="spread_w" class="text-uppercase text-muted d-block mb-1" style="font-size: 0.7rem; font-weight: 600;">Spread Size W</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-light border-right-0"><i class="fas fa-ruler text-secondary" style="font-size: 0.85rem;"></i></span>
                                            </div>
                                            <input type="text" 
                                                name="spread_w" 
                                                id="spread_w" 
                                                class="form-control border-left-0 bg-light" 
                                                placeholder="Enter Spread Size W" 
                                                value="  {{ $item_detail->spread_w ?? ''}}" 
                                                style="font-size: 0.9rem;">
                                            <span class="invalid-feedback" id="error-spread_w"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <div class="p-3 border rounded-lg h-100">
                                        <label for="spread_l" class="text-uppercase text-muted d-block mb-1" style="font-size: 0.7rem; font-weight: 600;">Spread Size L</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-light border-right-0"><i class="fas fa-ruler text-secondary" style="font-size: 0.85rem;"></i></span>
                                            </div>
                                            <input type="text" 
                                                name="spread_l" 
                                                id="spread_l" 
                                                class="form-control border-left-0 bg-light" 
                                                placeholder="Enter Spread Size" 
                                                value="  {{ $item_detail->spread_l ?? ''}}" 
                                                style="font-size: 0.9rem;">
                                            <span class="invalid-feedback" id="error-spread_size"></span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Folded size -->
                                {{-- <div class="col-md-4 mb-3">
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
                                </div> --}}

                                <div class="col-md-4 mb-3">
                                    <div class="p-3 border rounded-lg h-100">
                                        <label for="folded_h" class="text-uppercase text-muted d-block mb-1" style="font-size: 0.7rem; font-weight: 600;">Folded Size H</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-light border-right-0"><i class="fas fa-map text-secondary" style="font-size: 0.85rem;"></i></span>
                                            </div>
                                            <input type="text" 
                                                name="folded_h" 
                                                id="folded_h" 
                                                class="form-control border-left-0 bg-light" 
                                                placeholder="Enter Folded Size" 
                                                value="  {{ $item_detail->folded_h ?? ''}}" 
                                                style="font-size: 0.9rem;">
                                            <span class="invalid-feedback" id="error-folded_h"></span>
                                        </div>
                                    </div>
                                </div>
                                     <div class="col-md-4 mb-3">
                                    <div class="p-3 border rounded-lg h-100">
                                        <label for="folded_w" class="text-uppercase text-muted d-block mb-1" style="font-size: 0.7rem; font-weight: 600;">Folded Size W</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-light border-right-0"><i class="fas fa-map text-secondary" style="font-size: 0.85rem;"></i></span>
                                            </div>
                                            <input type="text" 
                                                name="folded_w" 
                                                id="folded_w" 
                                                class="form-control border-left-0 bg-light" 
                                                placeholder="Enter Folded Size" 
                                                value="  {{ $item_detail->folded_w ?? ''}}" 
                                                style="font-size: 0.9rem;">
                                            <span class="invalid-feedback" id="error-folded_w"></span>
                                        </div>
                                    </div>
                                </div>
                                     <div class="col-md-4 mb-3">
                                    <div class="p-3 border rounded-lg h-100">
                                        <label for="folded_l" class="text-uppercase text-muted d-block mb-1" style="font-size: 0.7rem; font-weight: 600;">Folded Size L</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-light border-right-0"><i class="fas fa-map text-secondary" style="font-size: 0.85rem;"></i></span>
                                            </div>
                                            <input type="text" 
                                                name="folded_l" 
                                                id="folded_l" 
                                                class="form-control border-left-0 bg-light" 
                                                placeholder="Enter Folded Size" 
                                                value="  {{ $item_detail->folded_l ?? ''}}" 
                                                style="font-size: 0.9rem;">
                                            <span class="invalid-feedback" id="error-folded_h"></span>
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
                            {{-- <h6 class="text-uppercase text-secondary font-weight-bold mb-3" style="font-size: 0.75rem; letter-spacing: 0.5px;">Recipients (GD)</h6>
                            <div class="mb-4 p-3 bg-light rounded-lg">
                                <label for="employee_email" class="text-uppercase text-muted d-block mb-2" style="font-size: 0.7rem; font-weight: 600;">
                                    Assigned Emails <span class="text-danger">*</span>
                                </label>
                                <div class="input-group flex-nowrap">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white border-right-0"><i class="fas fa-envelope text-success" style="font-size: 0.85rem;"></i></span>
                                    </div> --}}

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
                                {{-- <select id="employee_email" 
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
                            {{-- @foreach($selectedList as $customEmail)
                                @if(!in_array($customEmail, $renderedEmails) && !empty($customEmail))
                                    <option value="{{ $customEmail }}" selected>
                                        {{ $customEmail }}
                                    </option>
                                @endif
                            @endforeach
                            </select> --}}
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
                        @can('is-admin')
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
                            @endcan

                                  @can('is-traffic')
                        <div class="card-footer bg-white border-top pb-4 pt-3 px-4 d-flex justify-content-between align-items-center">
                            <a href="{{ route('traffic_officer.all') }}" class="btn btn-light rounded-pill px-4 py-2 text-secondary font-weight-bold" style="font-size: 0.88rem;">
                                Cancel
                            </a>
                            
                      
                            @endcan
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


    // 2. AJAX Submission to Laravel Controller
$('#addItemCodeForm').on('submit', function(e) {
    e.preventDefault();

    var $form = $(this);
    var $submitBtn = $('#btnSaveItemCode');
    var formData = $form.serialize();

    $.ajax({
        url: "{{ route('aoCreateItemCode') }}",
        type: "POST",
        data: formData,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function() {
            $submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Saving...');
        },
        success: function(response) {
            $submitBtn.prop('disabled', false).html('<i class="fas fa-save mr-1"></i> Save Changes');
            
            // Hide modal and reset form values & Select2
            $('#addItemCodeModal').modal('hide');
            $form[0].reset();
            $form.find('select').val(null).trigger('change');

            // Reload DataTables without resetting pagination (using your table ID)
            if ($.fn.DataTable.isDataTable('#FileSendTable')) {
                $('#FileSendTable').DataTable().ajax.reload(null, false);
            }

            // SweetAlert Success Notification
            Swal.fire({
                title: 'Success!',
                text: response.message || 'Item Code added successfully!',
                icon: 'success',
                confirmButtonText: 'OK'
            });
        },
        error: function(xhr) {
            $submitBtn.prop('disabled', false).html('<i class="fas fa-save mr-1"></i> Save Changes');
            var errorMsg = xhr.responseJSON?.message || 'An error occurred while saving item code.';
            
            // SweetAlert Error Notification
            Swal.fire({
                title: 'Error!',
                text: errorMsg,
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    });
});
</script>
@endsection