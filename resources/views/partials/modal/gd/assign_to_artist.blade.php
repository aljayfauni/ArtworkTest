<!-- Status Update / Assign & Re-Assign Modal -->
<div class="modal fade" id="assignArtistModal" tabindex="-1" role="dialog" aria-labelledby="assignArtistModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="assignArtistModalLabel">
                    <i class="fas fa-user-edit mr-1"></i> Artist Assignment
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body p-3 bg-light">
                <!-- Item Code Badge Header -->
                <div class="d-flex justify-content-between align-items-center bg-white p-2 px-3 rounded border mb-3">
                    <span class="text-xs text-uppercase text-muted font-weight-bold">Target Item Code</span>
                    <span class="badge badge-primary px-2 py-1" id="displayItemCodeBadge">---</span>
                </div>

                <!-- ACCORDION WRAPPER -->
                <div class="accordion" id="artistModalAccordion">

                    <!-- ============================================== -->
                    <!-- PANEL 1: ASSIGN TO ARTIST                      -->
                    <!-- ============================================== -->
                    <div class="card mb-2 border shadow-none">
                        <div class="card-header bg-white py-2 px-3" id="headingAssign">
                            <h6 class="mb-0">
                                <button class="btn btn-link btn-block text-left d-flex justify-content-between align-items-center text-dark font-weight-bold p-0 text-decoration-none"
                                        type="button"
                                        data-toggle="collapse"
                                        data-target="#collapseAssign"
                                        aria-expanded="true"
                                        aria-controls="collapseAssign">
                                    <span><i class="fas fa-user-plus text-primary mr-2"></i> Assign to Artist</span>
                                    <i class="fas fa-chevron-down text-muted small"></i>
                                </button>
                            </h6>
                        </div>

                        <div id="collapseAssign" class="collapse show" aria-labelledby="headingAssign" data-parent="#artistModalAccordion">
                            <div class="card-body">
                                <form id="assignArtistForm" class="artist-assignment-form" data-url="{{ route('assignToArtist.save') }}">
                                    @csrf
                                    <input type="hidden" class="modal-item-code" name="item_code" required>
                                    <input type="hidden" name="action_type" value="assign">

                                    <!-- Select Primary Artist -->
                                    <div class="form-group">
                                        <label class="text-uppercase text-xs text-muted font-weight-bold">
                                            Select Artist <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-control select2-artist" id="artistSelect" name="assigned_artist" style="width: 100%;" >
                                            <option value="" disabled selected>-- Select Artist --</option>
                                            @foreach ($artistLists as $artist)
                                                <option value="{{ $artist->emp_id }}">
                                                    {{ $artist->emp_id }} - {{ $artist->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Toggle Companion Switch -->
                                    <div class="form-group mb-2">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input toggle-companion-switch" id="toggleCompanionAssign" data-target="#companionBoxAssign">
                                            <label class="custom-control-label text-xs text-uppercase text-muted font-weight-bold" for="toggleCompanionAssign">
                                                Add Companion(s)
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Hidden Companion Select2 -->
                                    <div id="companionBoxAssign" class="form-group d-none p-3 bg-light rounded border mt-2">
                                        <label class="text-uppercase text-xs text-muted font-weight-bold">
                                            Select Companion(s)
                                        </label>
                                        <select name="companions[]" class="form-control select2-companion" style="width: 100%;" multiple="multiple">
                                            @foreach($artistLists as $user)
                                                <option value="{{ $user->emp_id }}">
                                                    {{ $user->emp_id }} - {{ $user->name }} ({{ $user->email }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="d-flex justify-content-end mt-3 pt-2 border-top">
                                        <button type="button" class="btn btn-default btn-sm mr-2" data-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary btn-sm btn-save-artist">
                                            <i class="fas fa-save mr-1"></i> Save Assignment
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- ============================================== -->
                    <!-- PANEL 2: RE-ASSIGN ARTIST                      -->
                    <!-- ============================================== -->
                    <div class="card mb-0 border shadow-none">
                        <div class="card-header bg-white py-2 px-3" id="headingReassign">
                            <h6 class="mb-0">
                                <button class="btn btn-link btn-block text-left d-flex justify-content-between align-items-center text-dark font-weight-bold p-0 text-decoration-none collapsed"
                                        type="button"
                                        data-toggle="collapse"
                                        data-target="#collapseReassign"
                                        aria-expanded="false"
                                        aria-controls="collapseReassign">
                                    <span><i class="fas fa-sync-alt text-warning mr-2"></i> Re-Assign Artist</span>
                                    <i class="fas fa-chevron-down text-muted small"></i>
                                </button>
                            </h6>
                        </div>

                        <div id="collapseReassign" class="collapse" aria-labelledby="headingReassign" data-parent="#artistModalAccordion">
                            <div class="card-body">
                                <form id="reassignArtistForm" class="artist-assignment-form" data-url="{{ route('reassignArtist.save') }}">
                                    @csrf
                                    <input type="hidden" class="modal-item-code" name="item_code" required>
                                    <input type="hidden" name="action_type" value="reassign">

                                    <!-- Select New Artist -->
                                    <div class="form-group">
                                        <label class="text-uppercase text-xs text-muted font-weight-bold">
                                            New Primary Artist <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-control select2-artist" id="reassignArtistSelect" name="assigned_artist" style="width: 100%;" required>
                                            <option value="" disabled selected>-- Select New Artist --</option>
                                            @foreach ($artistLists as $artist)
                                                <option value="{{ $artist->emp_id }}">
                                                    {{ $artist->emp_id }} - {{ $artist->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Toggle Companion Switch (Re-Assign) -->
                                    <div class="form-group mb-2">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input toggle-companion-switch" id="toggleCompanionReassign" data-target="#companionBoxReassign">
                                            <label class="custom-control-label text-xs text-uppercase text-muted font-weight-bold" for="toggleCompanionReassign">
                                                Add / Update Companion(s)
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Hidden Companion Select2 (Re-Assign) -->
                                    <div id="companionBoxReassign" class="form-group d-none p-3 bg-light rounded border mt-2">
                                        <label class="text-uppercase text-xs text-muted font-weight-bold">
                                            Select Companion(s)
                                        </label>
                                        <select name="companions[]" id="reassignCompanionsSelect" class="form-control select2-companion" style="width: 100%;" multiple="multiple">
                                            @foreach($artistLists as $user)
                                                <option value="{{ $user->emp_id }}">
                                                    {{ $user->emp_id }} - {{ $user->name }} ({{ $user->email }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Optional Reason -->
                                    <div class="form-group mt-2">
                                        <label class="text-uppercase text-xs text-muted font-weight-bold">
                                            Re-Assign Reason / Remarks
                                        </label>
                                        <textarea name="remarks" class="form-control form-control-sm" rows="2" placeholder="Optional reason for re-assigning..."></textarea>
                                    </div>

                                    <div class="d-flex justify-content-end mt-3 pt-2 border-top">
                                        <button type="button" class="btn btn-default btn-sm mr-2" data-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-warning text-dark font-weight-bold btn-sm btn-save-artist">
                                            <i class="fas fa-sync-alt mr-1"></i> Confirm Re-Assign
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                </div><!-- /.accordion -->
            </div>
        </div>
    </div>
</div>

<style>
    .select2-container .select2-selection--multiple .select2-selection__choice {
        background-color: #007bff !important;
        border-color: #007bff !important;
        color: #ffffff !important;
    }
    .select2-container .select2-selection--multiple .select2-selection__choice__remove {
        color: #ffffff !important;
    }
</style>

@push('js')
<script>
$(document).ready(function() {

    // 1. Initialize Select2 properly inside the modal
    $('.select2-artist').select2({
        dropdownParent: $('#assignArtistModal'),
        width: '100%',
        placeholder: "-- Select Artist --"
    });

    $('.select2-companion').select2({
        dropdownParent: $('#assignArtistModal'),
        width: '100%',
        placeholder: "Select companion(s)",
        allowClear: true
    });

    // 2. Show/Hide Companion Select Box when toggle switch changes
    $(document).on('change', '.toggle-companion-switch', function() {
        var targetSelector = $(this).data('target');
        var $targetBox = $(targetSelector);

        if ($(this).is(':checked')) {
            $targetBox.removeClass('d-none').hide().slideDown(180);
        } else {
            $targetBox.slideUp(180, function() {
                $(this).addClass('d-none');
                // Clear selected companions when toggled off
                $targetBox.find('select').val(null).trigger('change');
            });
        }
    });

    // 3. Open Modal (supports both Assign and Re-Assign buttons)
    // Usage on button:
    // <button class="open-assign-to-artist-modal" data-item-code="ITEM-01" data-mode="assign">Assign</button>
    // <button class="open-assign-to-artist-modal" data-item-code="ITEM-01" data-mode="reassign" data-current-artist="EMP01" data-companions='["EMP02","EMP03"]'>Re-Assign</button>
    $(document).on('click', '.open-assign-to-artist-modal', function() {
        var itemCode      = $(this).data('item-code');
        var mode          = $(this).data('mode') || 'assign'; // 'assign' or 'reassign'
        var currentArtist = $(this).data('current-artist') || null;
        var companions    = $(this).data('companions') || [];

        // Reset both forms & hide companion boxes first
        $('.artist-assignment-form')[0].reset();
        $('#reassignArtistForm')[0].reset();
        $('.select2-artist, .select2-companion').val(null).trigger('change');
        $('.toggle-companion-switch').prop('checked', false);
        $('#companionBoxAssign, #companionBoxReassign').addClass('d-none');

        // Set item code in badge and hidden inputs
        $('#displayItemCodeBadge').text(itemCode);
        $('.modal-item-code').val(itemCode);

        // Open appropriate Accordion Panel
        if (mode === 'reassign') {
            $('#collapseAssign').collapse('hide');
            $('#collapseReassign').collapse('show');

            // Pre-fill existing artist & companions if passed via data attributes
            if (currentArtist) {
                $('#reassignArtistSelect').val(currentArtist).trigger('change');
            }
            if (Array.isArray(companions) && companions.length > 0) {
                $('#toggleCompanionReassign').prop('checked', true);
                $('#companionBoxReassign').removeClass('d-none');
                $('#reassignCompanionsSelect').val(companions).trigger('change');
            }
        } else {
            $('#collapseReassign').collapse('hide');
            $('#collapseAssign').collapse('show');
        }

        $('#assignArtistModal').modal('show');
    });

    // 4. Unified AJAX Submit Handler for both Assign & Re-Assign forms
    $(document).on('submit', '.artist-assignment-form', function(e) {
        e.preventDefault();

        var $form   = $(this);
        var postUrl = $form.data('url');
        var $btn    = $form.find('.btn-save-artist');
        var origBtn = $btn.html();

        $.ajax({
            url: postUrl,
            type: "POST",
            data: $form.serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function() {
                $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Saving...');
            },
            success: function(response) {
                $('#assignArtistModal').modal('hide');
                $btn.prop('disabled', false).html(origBtn);

                if ($.fn.DataTable.isDataTable('#FileSendTable')) {
                    $('#FileSendTable').DataTable().ajax.reload(null, false);
                }

                Swal.fire({
                    title: 'Success!',
                    text: response.message || 'Artist assignment saved successfully!',
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
            },
            error: function(xhr) {
                $btn.prop('disabled', false).html(origBtn);
                var errorMsg = xhr.responseJSON?.message || 'An error occurred while saving.';

                Swal.fire({
                    title: 'Error!',
                    text: errorMsg,
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        });
    });

});
</script>
@endpush