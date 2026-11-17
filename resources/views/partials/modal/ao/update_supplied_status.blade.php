<!-- Status Update Modal -->
<div class="modal fade" id="statusModal" tabindex="-1" role="dialog" aria-labelledby="statusModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title" id="statusModalLabel">
                    <i class="fas fa-edit mr-1"></i> Update Item Status
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="updateStatusForm">
                @csrf
                <div class="modal-body">
                    <!-- Item Code Readonly Input -->
                    <div class="form-group">
                    
                        <label for="modalItemCode">Item Code</label>
                        <input type="text" class="form-control" id="modalItemCode" name="item_code" readonly required>
                        <label for="modalItemDescription">Item Description</label>
                        <input type="text" class="form-control" id="modalItemDescription" name="item_desc" readonly required>
                        <label for="modalClientName">Client Name</label>
                        <input type="text" class="form-control" id="modalClientName" name="client_name" readonly required>
                         <label for="modalRfaRef">Rfa reference</label>
                        <input type="text" class="form-control" id="modalRfaRef" name="rfa_ref" readonly required>
                    modalClientName
                    </div>

                    <!-- Status Select (2 Options) -->
                    <div class="form-group">
                        <label for="supplied_status">Select Status</label>
                        <select class="form-control select2" id="supplied_status" name="supplied_status" required>
                            <option value="GD to Assign Artist">GD to Assign Artist</option>
                            @can('is-traffic')
                            <option value="Verifying">Verifying</option>
                            @endcan
                             <option value="Verified">Verified</option>
                            <option value="Completed"></option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="btnSaveStatus">
                        <i class="fas fa-save mr-1"></i> Save Changes
                    </button>
                </div>
                 @if(session('success'))
                        <div class="alert alert-success m-3">
                            {{ session('success') }}
                        </div>
                    @endif
                
            </form>
        </div>
    </div>
</div>

<!-- Modal Script -->
@push('js')
<script>
$(document).ready(function() {

    // 1. Event listener to open modal when clicking status badge
    $(document).on('click', '.open-status-modal', function() {
        var rfaRef = $(this).data('rfa-reference');
        var itemCode = $(this).data('item-code');
        var itemDesc = $(this).data('item-desc');
        var client_name = $(this).data('client-name');
        var currentStatus = $(this).data('status');
        console.log(itemCode);
        $('#modalItemCode').val(itemCode);
        $('#modalItemDescription').val(itemDesc);
        $('#modalStatus').val(currentStatus);
        $('#modalRfaRef').val(rfaRef);
         $('#modalClientName').val(client_name);
    
        $('#statusModal').modal('show');
    });

    // 2. AJAX Submission to Laravel Controller
    // $('#updateStatusForm').on('submit', function(e) {
    //     e.preventDefault();

    //     var formData = $(this).serialize();

    //     $.ajax({
    //         url: "/traffic_update-supplied-status",
    //         type: "POST",
    //         data: formData,
    //         headers: {
    //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //         },
    //         beforeSend: function() {
    //             $('#btnSaveStatus').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Saving...');
    //         },
    //         success: function(response) {
    //             console.log(response);
    //             $('#statusModal').modal('hide');
    //             $('#btnSaveStatus').prop('disabled', false).html('<i class="fas fa-save mr-1"></i> Save Changes');

    //             // Reload DataTables without resetting pagination
    //             if ($.fn.DataTable.isDataTable('#yourTableId')) {
    //                 $('#yourTableId').DataTable().ajax.reload(null, false);
    //             }

    //             // AdminLTE Toast notification
    //             if (typeof toastr !== 'undefined') {
    //                 toastr.success(response.message || 'Status updated successfully!');
    //             }
    //         },
    //         error: function(xhr) {
    //             $('#btnSaveStatus').prop('disabled', false).html('<i class="fas fa-save mr-1"></i> Save Changes');
    //             var errorMsg = xhr.responseJSON?.message || 'An error occurred while updating status.';
                
    //             if (typeof toastr !== 'undefined') {
    //                 toastr.error(errorMsg);
    //             } else {
    //                 alert(errorMsg);
    //             }
    //         }
    //     });
    // });

    $('#updateStatusForm').on('submit', function(e) {
    e.preventDefault();

    var formData = $(this).serialize();
    var $form = $(this);

    $.ajax({
        url: "/traffic_update-supplied-status",
        type: "POST",
        data: formData,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function() {
            $('#btnSaveStatus').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Saving...');
        },
        success: function(response) {
            console.log(response);
            $('#statusModal').modal('hide');
            $('#btnSaveStatus').prop('disabled', false).html('<i class="fas fa-save mr-1"></i> Save Changes');

            // Reset form fields and select2 dropdowns
            $form[0].reset();
            $form.find('select').val(null).trigger('change');

            // Reload DataTables without resetting pagination (using your table ID)
            if ($.fn.DataTable.isDataTable('#FileSendTable')) {
                $('#FileSendTable').DataTable().ajax.reload(null, false);
            }

            // SweetAlert Success Notification
            Swal.fire({
                title: 'Success!',
                text: response.message || 'Status updated successfully!',
                icon: 'success',
                confirmButtonText: 'OK'
            });
        },
        error: function(xhr) {
            $('#btnSaveStatus').prop('disabled', false).html('<i class="fas fa-save mr-1"></i> Save Changes');
            var errorMsg = xhr.responseJSON?.message || 'An error occurred while updating status.';
            
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

});
</script>