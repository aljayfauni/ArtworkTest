<!-- Supplied File Verification Modal -->
<div class="modal fade" id="verificationModal" tabindex="-1" role="dialog" aria-labelledby="verificationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <form action="{{ route("suppliedVerified.save") }}" method="POST" id="verificationForm">
                @csrf
                <input type="hidden" name="file_id" id="verify_file_id">

                <div class="card card-primary card-outline mb-0">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center w-100">
                            <h3 class="card-title text-uppercase font-weight-bold mb-0">
                                Supplied File Verification <small class="text-muted font-italic">(To be filled out by GD)</small>
                            </h3>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <label for="item_code_label">Item Code:&nbsp; </label><span id="item_code_label"></span>
                         <label for="rfa_reference_label">RFA Reference Number:&nbsp; </label><span id="rfa_reference_label"></span>
                    </div>
                    
                    <div class="card-body">
                        {{-- Client & Item Description --}}
                        <div class="row">
                           
                            <div class="col-md-6 form-group">
                                
                                 <input type="text" name="item_code" id="item_code" class="form-control" value="" placeholder="Item_code" hidden >
                                   <input type="text" name="rfa_reference" id="rfa_reference" class="form-control" value="" placeholder="Rfa_reference" hidden >
                                <label for="client">Client <span class="text-danger"></span></label>
                                
                                <input type="text" name="client" id="client" class="form-control @error('client') is-invalid @enderror" value="{{ old('client') }}" placeholder="Enter client name" readonly>
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

                        {{-- File Source (Radio Buttons) & Application (Select2) --}}
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label class="d-block font-weight-bold">File Source</label>
                                <div class="d-flex flex-wrap align-items-center">
                                    <div class="custom-control custom-radio mr-3 mb-2">
                                        <input class="custom-control-input" type="radio" id="source_email" name="file_source" value="Email">
                                        <label for="source_email" class="custom-control-label font-weight-normal">Email</label>
                                    </div>
                                    <div class="custom-control custom-radio mr-3 mb-2">
                                        <input class="custom-control-input" type="radio" id="source_cddvd" name="file_source" value="CD/DVD">
                                        <label for="source_cddvd" class="custom-control-label font-weight-normal">CD/DVD</label>
                                    </div>
                                    <div class="custom-control custom-radio mr-3 mb-2">
                                        <input class="custom-control-input" type="radio" id="source_flashdisc" name="file_source" value="Flash Disc">
                                        <label for="source_flashdisc" class="custom-control-label font-weight-normal">Flash Disc</label>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <input type="text" name="file_source_other" id="file_source_other" class="form-control form-control-sm" placeholder="Others: Specify">
                                </div>
                            </div>

                               <div class="col-md-6 form-group">
                                <label class="d-block font-weight-bold">File Link</label>
                                
                                <div class="mt-2">
                                    <input type="text" name="file_link" id="file_link" class="form-control form-control-sm" placeholder="File Link Url" readonly>
                                </div>
                            </div>

                            <div class="col-md-6 form-group">
                                <label for="application" class="d-block font-weight-bold">Application</label>
                                <select class="form-control select2" id="application" name="application" style="width: 100%;">
                                    <option value="">Select Application...</option>
                                    <option value="Photoshop">Photoshop</option>
                                    <option value="Illustrator">Illustrator</option>
                                    <option value="InDesign">InDesign</option>
                                    <option value="Acrobat">Acrobat</option>

                                </select>
                            </div>
                        </div>

                        <hr>

                        {{-- Size & Specs Row --}}
                        <div class="row">
                            <div class="col-md-6">
                            <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Size: Spread</label>
                                    <div class="col-sm-9">
                                        <div class="row">
                                            <div class="col-sm-4 mb-2">
                                                <label for="spread_h" class="form-label text-muted small">Height (H)</label>
                                                <input type="text" name="spread_h" id="spread_h" class="form-control" placeholder="Enter height">
                                            </div>
                                            <div class="col-sm-4 mb-2">
                                                <label for="spread_w" class="form-label text-muted small">Width (W)</label>
                                                <input type="text" name="spread_w" id="spread_w" class="form-control" placeholder="Enter width">
                                            </div>
                                            <div class="col-sm-4 mb-2">
                                                <label for="spread_l" class="form-label text-muted small">Length (L)</label>
                                                <input type="text" name="spread_l" id="spread_l" class="form-control" placeholder="Enter length">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="size_folded" class="col-sm-3 col-form-label">Folded</label>
                                       <div class="col-sm-9">
                                        <div class="row">
                                            <div class="col-sm-4 mb-2">
                                                <label for="folded_h" class="form-label text-muted small">Height (H)</label>
                                                <input type="text" name="folded_h" id="folded_h" class="form-control" placeholder="Enter height">
                                            </div>
                                            <div class="col-sm-4 mb-2">
                                                <label for="folded_w" class="form-label text-muted small">Width (W)</label>
                                                <input type="text" name="folded_w" id="folded_w" class="form-control" placeholder="Enter width">
                                            </div>
                                            <div class="col-sm-4 mb-2">
                                                <label for="folded_l" class="form-label text-muted small">Length (L)</label>
                                                <input type="text" name="folded_l" id="folded_l" class="form-control" placeholder="Enter length">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="row">
                                    {{-- Color Specs (Radio Buttons) --}}
                                    <div class="col-md-4 form-group">
                                        <label class="d-block font-weight-bold">Color</label>
                                   
                                        <div class="custom-control custom-radio mb-1">
                                            <input class="custom-control-input" type="radio" id="color_cmyk" name="color" value="CMYK">
                                            <label for="color_cmyk" class="custom-control-label font-weight-normal">CMYK</label>
                                        </div>
                                        <div class="custom-control custom-radio mb-1">
                                            <input class="custom-control-input" type="radio" id="color_rgb" name="color" value="RGB">
                                            <label for="color_rgb" class="custom-control-label font-weight-normal">RGB</label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input class="custom-control-input" type="radio" id="color_grayscale" name="color" value="Grayscale">
                                            <label for="color_grayscale" class="custom-control-label font-weight-normal">Grayscale</label>
                                        </div>
                                        
                                            
                                    </div>
                                            <div class=" mb-2">
                                                <label for="default_color_text" class="form-label text-muted ">from Fact color</label>
                                                <input type="text" name="default_color_text" id="default_color_text" class=" w-100" placeholder="Enter Color" disabled>
                                            </div>
                                    {{-- Text Specs (Radio Buttons) --}}
                                    <div class="col-md-4 form-group">
                                        <label class="d-block font-weight-bold">Text</label>
                                        <div class="custom-control custom-radio mb-1">
                                            <input class="custom-control-input" type="radio" id="text_editable" name="text_specs" value="Editable">
                                            <label for="text_editable" class="custom-control-label font-weight-normal">Editable</label>
                                        </div>
                                        <div class="custom-control custom-radio mb-1">
                                            <input class="custom-control-input" type="radio" id="text_path" name="text_specs" value="Converted to path">
                                            <label for="text_path" class="custom-control-label font-weight-normal">Converted to path</label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input class="custom-control-input" type="radio" id="text_missing_fonts" name="text_specs" value="Missing Font(s)">
                                            <label for="text_missing_fonts" class="custom-control-label font-weight-normal">Missing Font(s)</label>
                                        </div>
                                    </div>

                                    {{-- Other Specs (Checkboxes) --}}
                                    <div class="col-md-4 form-group">
                                        <label class="d-block font-weight-bold">Other Specs</label>
                                        <div class="custom-control custom-checkbox mb-1">
                                            <input class="custom-control-input" type="checkbox" id="spec_diecut" name="other_specs[]" value="Die-cut">
                                            <label for="spec_diecut" class="custom-control-label font-weight-normal">Die-cut</label>
                                        </div>
                                        <div class="custom-control custom-checkbox mb-1">
                                            <input class="custom-control-input" type="checkbox" id="spec_stamping" name="other_specs[]" value="Stamping">
                                            <label for="spec_stamping" class="custom-control-label font-weight-normal">Stamping</label>
                                        </div>
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" type="checkbox" id="spec_uv" name="other_specs[]" value="UV">
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
                                <label class="d-block font-weight-bold">Image Type</label>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="custom-control custom-checkbox mb-1">
                                            <input class="custom-control-input" type="checkbox" id="img_layered" name="image_type[]" value="Layered">
                                            <label for="img_layered" class="custom-control-label font-weight-normal">Layered</label>
                                        </div>
                                        <div class="custom-control custom-checkbox mb-1">
                                            <input class="custom-control-input" type="checkbox" id="img_missing" name="image_type[]" value="Missing Image(s)">
                                            <label for="img_missing" class="custom-control-label font-weight-normal">Missing Image(s)</label>
                                        </div>
                                        <div class="custom-control custom-checkbox mb-1">
                                            <input class="custom-control-input" type="checkbox" id="img_high_res" name="image_type[]" value="High Resolution">
                                            <label for="img_high_res" class="custom-control-label font-weight-normal">High Resolution</label>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="custom-control custom-checkbox mb-1">
                                            <input class="custom-control-input" type="checkbox" id="img_embedded" name="image_type[]" value="Embedded">
                                            <label for="img_embedded" class="custom-control-label font-weight-normal">Embedded</label>
                                        </div>
                                        <div class="custom-control custom-checkbox mb-1">
                                            <input class="custom-control-input" type="checkbox" id="img_flattened" name="image_type[]" value="Flattened">
                                            <label for="img_flattened" class="custom-control-label font-weight-normal">Flattened</label>
                                        </div>
                                        <div class="custom-control custom-checkbox mb-1">
                                            <input class="custom-control-input" type="checkbox" id="img_low_res" name="image_type[]" value="Low Resolution">
                                            <label for="img_low_res" class="custom-control-label font-weight-normal">Low Resolution</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="number_of_colors" class="font-weight-bold">Number of Colors</label>
                                    <input type="text" name="number_of_colors" id="number_of_colors" class="form-control" placeholder="e.g. 4-Color Process, Pantone 185C">
                                </div>

                                <div class="form-group row mt-4">
                                    <label for="verified_by_date" class="col-sm-4 col-form-label font-weight-bold">Verified by / Date</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="verified_by_date" id="verified_by_date" class="form-control" value="{{ auth()->user()->name . ' / ' . date('Y-m-d') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer text-right">
                        <button type="button" class="btn btn-default mr-2" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Save Verification</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>