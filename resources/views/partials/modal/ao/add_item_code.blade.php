<!-- Status Update Modal -->
<div class="modal fade" id="addItemCodeModal" tabindex="-1" role="dialog" aria-labelledby="addItemCodeModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title" id="addItemCodeModalModalLabel">
                    <i class="fas fa-edit mr-1"></i> Add Item Code
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="addItemCodeForm">
                @csrf
                <div class="modal-body">
                    <!-- Select Artist Dropdown (Changed ID to artistSelect) -->
                    <div class="form-group">
                        <label for="artistSelect">Enter Item Code</label>
                        <input type="text" name="item_code" id="item_code" placeholder="Item Code" class="form-control">
                    </div>

                    <!-- Item Code & Hidden Inputs -->
                    <div class="form-group">
                        
                        <!-- Hidden fields -->
                        <input type="hidden" name="rfa_reference" id="rfa_reference">
                        {{-- <input type="hidden" name="status" id="modalStatus"> --}}
                        {{-- <input type="hidden" class="form-control" id="modalItemCode" name="item_code" readonly required> --}}
                    </div>
                </div>

                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="btnSaveItemCode">
                        <i class="fas fa-save mr-1"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Script -->
@push('js')
<script>

$(document).ready(function() {

    // Helper function to fetch and populate artists dynamically
    // function loadArtists(selectedArtistId = null) {
    //     var $select = $('#modalStatus');
 
    //     $.ajax({
    //         url: " ", // Replace with your endpoint route
    //         type: "GET",
    //         dataType: "json",
    //         beforeSend: function() {
    //             $select.html('<option value="">Loading artists...</option>').prop('disabled', true);
    //         },
    //         success: function(response) {
    //             $select.empty().append('<option value="">-- Select Artist --</option>');

    //             // Assuming response returns an array of objects: [{ id: 1, name: 'John Doe' }]
    //             if (response.data && response.data.length > 0) {
    //                 $.each(response.data, function(index, artist) {
    //                     $select.append(new Option(artist.name, artist.id));
    //                 });
    //             } else if (Array.isArray(response)) {
    //                 $.each(response, function(index, artist) {
    //                     $select.append(new Option(artist.name, artist.id));
    //                 });
    //             }

    //             $select.prop('disabled', false);

    //             // Pre-select artist if editing or previously selected
    //             if (selectedArtistId) {
    //                 $select.val(selectedArtistId);
    //             }

    //             // Re-initialize or trigger Select2 if enabled
    //             if ($.fn.select2) {
    //                 $select.trigger('change');
    //             }
    //         },
    //         error: function() {
    //             $select.html('<option value="">Failed to load artists</option>').prop('disabled', false);
    //             if (typeof toastr !== 'undefined') {
    //                 toastr.error('Could not fetch artists list.');
    //             }
    //         }
    //     });
    // }
  });

$(document).ready(function() {

    // 1. Event listener to open modal when clicking status badge
$(document).on('click', '.add-item-code', function() {
var rfaRef = $(this).data('rfa-reference');
    
    // Set values in the modal form
    $('#rfa_reference').val(rfaRef);
    var itemCode = $(this).data('rfa-reference');
    console.log(itemCode);
    // 1. Set hidden item code input
    // $('#modalItemCode').val(itemCode);

    // 2. Show the modal
    $('#addItemCodeModal').modal('show');

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
            
            // Hide modal and reset form values
            $('#addItemCodeModal').modal('hide');
            $form[0].reset();

            // Reload DataTables without resetting pagination
            if ($.fn.DataTable.isDataTable('#yourTableId')) {
                $('#yourTableId').DataTable().ajax.reload(null, false);
            }

            // Notification
            if (typeof toastr !== 'undefined') {
                toastr.success(response.message || 'Item Code added successfully!');
            }
        },
        error: function(xhr) {
            $submitBtn.prop('disabled', false).html('<i class="fas fa-save mr-1"></i> Save Changes');
            var errorMsg = xhr.responseJSON?.message || 'An error occurred while saving item code.';
            
            if (typeof toastr !== 'undefined') {
                toastr.error(errorMsg);
            } else {
                alert(errorMsg);
            }
        }
    });
});
});
</script>