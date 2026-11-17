<!-- Status Update Modal -->
<div class="modal fade" id="assignArtistModal" tabindex="-1" role="dialog" aria-labelledby="assignArtistModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title" id="assignArtistModalLabel">
                    <i class="fas fa-edit mr-1"></i> Assign to Artist
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="assignArtistForm">
                @csrf
                <div class="modal-body">
                    <!-- Select Artist Dropdown (Changed ID to artistSelect) -->
                    <div class="form-group">
                        <label for="artistSelect">Select Artist</label>
                        <select class="form-control select2" id="artistSelect" name="assigned_artist" required>
                            @foreach ($artistLists as $artist)
                                <option value="{{ $artist->emp_id }}">
                                    {{ $artist->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Item Code & Hidden Inputs -->
                    <div class="form-group">
                        
                        <!-- Hidden fields -->
                        <input type="hidden" name="artist_id" id="artist_id">
                        <input type="hidden" name="artist_name" id="artistName">
                        <input type="hidden" name="status" id="modalStatus">
                        <input type="hidden" class="form-control" id="modalItemCode" name="item_code" readonly required>
                    </div>
                </div>

                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="btnSaveStatus">
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
    function loadArtists(selectedArtistId = null) {
        var $select = $('#modalStatus');
 
        $.ajax({
            url: " ", // Replace with your endpoint route
            type: "GET",
            dataType: "json",
            beforeSend: function() {
                $select.html('<option value="">Loading artists...</option>').prop('disabled', true);
            },
            success: function(response) {
                $select.empty().append('<option value="">-- Select Artist --</option>');

                // Assuming response returns an array of objects: [{ id: 1, name: 'John Doe' }]
                if (response.data && response.data.length > 0) {
                    $.each(response.data, function(index, artist) {
                        $select.append(new Option(artist.name, artist.id));
                    });
                } else if (Array.isArray(response)) {
                    $.each(response, function(index, artist) {
                        $select.append(new Option(artist.name, artist.id));
                    });
                }

                $select.prop('disabled', false);

                // Pre-select artist if editing or previously selected
                if (selectedArtistId) {
                    $select.val(selectedArtistId);
                }

                // Re-initialize or trigger Select2 if enabled
                if ($.fn.select2) {
                    $select.trigger('change');
                }
            },
            error: function() {
                $select.html('<option value="">Failed to load artists</option>').prop('disabled', false);
                if (typeof toastr !== 'undefined') {
                    toastr.error('Could not fetch artists list.');
                }
            }
        });
    }
  });

$(document).ready(function() {

    // 1. Event listener to open modal when clicking status badge
$(document).on('click', '.open-assign-to-artist-modal', function() {
    var itemCode = $(this).data('item-code');

    // 1. Set hidden item code input
    $('#modalItemCode').val(itemCode);

    // 2. Show the modal
    $('#assignArtistModal').modal('show');

    // 3. Get text from the SELECT dropdown (not the modal div)
    var selectedArtistName = $('#artistSelect').find('option:selected').text();
    var selectedArtistEmpId = $('#artistSelect').val(); //get artist Emp Id

    // 4. Set hidden artistName field (if present)
    $('#modalArtistName').val(selectedArtistName);

    // 5. Console log (removed the PHP '$' sign)
    console.log(selectedArtistEmpId);
});

  
       
  

    // 2. AJAX Submission to Laravel Controller
    // $('#assignArtistForm').on('submit', function(e) {
    //     e.preventDefault();

    //     var formData = $(this).serialize();
    //     // var artistName = $('#assigned_artist').val();
    //     // console.log(formData);

        

    //     $.ajax({
    //         url: "{{ route('assignToArtist.save') }}",
    //         type: "POST",
    //         data: formData,
    //         headers: {
    //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //         },
    //         beforeSend: function() {
    //             $('#btnSaveStatus').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Saving...');
    //         },
    //         success: function(response) {
    //             $('#statusModal').modal('hide');
    //             $('#btnSaveStatus').prop('disabled', false).html('<i class="fas fa-save mr-1"></i> Save Changes');

    //             // Reload DataTables without resetting pagination
    //             if ($.fn.DataTable.isDataTable('#yourTableId')) {
    //                 $('#yourTableId').DataTable().ajax.reload(null, false);
    //             }

    //             // AdminLTE Toast notification
    //             if (typeof toastr !== 'undefined') {
    //                 toastr.success(response.message || 'Assign to Artist successfully!');
    //             }
    //         },
    //         error: function(xhr) {
    //             $('#btnSaveStatus').prop('disabled', false).html('<i class="fas fa-save mr-1"></i> Save Changes');
    //             var errorMsg = xhr.responseJSON?.message || 'An error occurred while assigning to artist.';
                
    //             if (typeof toastr !== 'undefined') {
    //                 toastr.error(errorMsg);
    //             } else {
    //                 alert(errorMsg);
    //             }
    //         }
    //     });
    // });

    $('#assignArtistForm').on('submit', function(e) {
    e.preventDefault();

    var formData = $(this).serialize();
    var $form = $(this);

    $.ajax({
        url: "{{ route('assignToArtist.save') }}",
        type: "POST",
        data: formData,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function() {
            $('#btnSaveStatus').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Saving...');
        },
        success: function(response) {
            $('#statusModal').modal('hide');
            $('#btnSaveStatus').prop('disabled', false).html('<i class="fas fa-save mr-1"></i> Save Changes');

            // Reset the form inputs
            $form[0].reset();
            
            // Reset Select2 if you are using it inside this form
            $form.find('select').val(null).trigger('change');

            // Reload DataTables without resetting pagination (make sure table ID matches)
            if ($.fn.DataTable.isDataTable('#FileSendTable')) {
                $('#FileSendTable').DataTable().ajax.reload(null, false);
            }

            // SweetAlert Success Notification
            Swal.fire({
                title: 'Success!',
                text: response.message || 'Assign to Artist successfully!',
                icon: 'success',
                confirmButtonText: 'OK'
            });
        },
        error: function(xhr) {
            $('#btnSaveStatus').prop('disabled', false).html('<i class="fas fa-save mr-1"></i> Save Changes');
            var errorMsg = xhr.responseJSON?.message || 'An error occurred while assigning to artist.';
            
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