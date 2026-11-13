<!-- Supplied File Verification Modal -->
<div class="modal fade" id="verificationModal_no_item_code" tabindex="-1" role="dialog" aria-labelledby="verificationModal_no_item_codeLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <form action="{{ route("suppliedVerified_no_item_code.save") }}" method="POST" id="verificationForm2">
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
                         <label for="rfa_reference_label_no_item_code">RFA Reference Number:&nbsp; </label><span id="rfa_reference_label_no_item_code"></span>
                    </div>
                    
                    <div class="card-body">
                        {{-- Client & Item Description --}}
                        <div class="row">
                           
                            <div class="col-md-6 form-group">
                                
                                 <input type="text" name="item_code_no_item_code" id="item_code_no_item_code" class="form-control" value="" placeholder="Item_code" hidden >
                                   <input type="text" name="rfa_reference_no_item_code" id="rfa_reference_no_item_code" class="form-control" value="" placeholder="Rfa_reference" hidden >
                                <label for="client">Client <span class="text-danger"></span></label>
                                
                                <input type="text" name="client_no_item_code" id="client_no_item_code" class="form-control @error('client') is-invalid @enderror" value="{{ old('client') }}" placeholder="Enter client name" readonly>
                                @error('client')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6 form-group">
                                <label for="item_description_no_item_code">Item Description <span class="text-danger">*</span></label>
                                <input type="text" name="item_description_no_item_code" id="item_description_no_item_code" class="form-control @error('item_description_no_item_code') is-invalid @enderror" value="{{ old('item_description_no_item_code') }}" placeholder="Enter item description" required>
                                @error('item_description_no_item_code')
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
                                        <input class="custom-control-input" type="radio" id="source_email_no_item_code" name="file_source_no_item_code" value="Email">
                                        <label for="source_email_no_item_code" class="custom-control-label font-weight-normal">Email</label>
                                    </div>
                                    <div class="custom-control custom-radio mr-3 mb-2">
                                        <input class="custom-control-input" type="radio" id="source_cddvd_no_item_code" name="file_source_no_item_code" value="CD/DVD">
                                        <label for="source_cddvd_no_item_code" class="custom-control-label font-weight-normal">CD/DVD</label>
                                    </div>
                                    <div class="custom-control custom-radio mr-3 mb-2">
                                        <input class="custom-control-input" type="radio" id="source_flashdisc_no_item_code" name="file_source_no_item_code" value="Flash Disc">
                                        <label for="source_flashdisc_no_item_code" class="custom-control-label font-weight-normal">Flash Disc</label>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <input type="text" name="file_source_other_no_item_code" id="file_source_other_no_item_code" class="form-control form-control-sm" placeholder="Others: Specify">
                                </div>
                            </div>

                               <div class="col-md-6 form-group">
                                <label class="d-block font-weight-bold">File Link</label>
                                
                                <div class="mt-2">
                                    <input type="text" name="file_link_no_item_code" id="file_link_no_item_code" class="form-control form-control-sm" placeholder="File Link Url" readonly>
                                </div>
                            </div>

                            <div class="col-md-6 form-group">
                                <label for="application" class="d-block font-weight-bold">Application</label>
                                <select class="form-control select2" id="application_no_item_code" name="application_no_item_code" style="width: 100%;">
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
                                                <label for="spread_h_no_item_code" class="form-label text-muted small">Height (H)</label>
                                                <input type="text" name="spread_h_no_item_code" id="spread_h_no_item_code" class="form-control" placeholder="Enter height">
                                            </div>
                                            <div class="col-sm-4 mb-2">
                                                <label for="spread_w_no_item_code" class="form-label text-muted small">Width (W)</label>
                                                <input type="text" name="spread_w_no_item_code" id="spread_w_no_item_code" class="form-control" placeholder="Enter width">
                                            </div>
                                            <div class="col-sm-4 mb-2">
                                                <label for="spread_l_no_item_code" class="form-label text-muted small">Length (L)</label>
                                                <input type="text" name="spread_l_no_item_code" id="spread_l_no_item_code" class="form-control" placeholder="Enter length">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="size_folded_no_item_code" class="col-sm-3 col-form-label">Folded</label>
                                       <div class="col-sm-9">
                                        <div class="row">
                                            <div class="col-sm-4 mb-2">
                                                <label for="folded_h_no_item_code" class="form-label text-muted small">Height (H)</label>
                                                <input type="text" name="folded_h_no_item_code" id="folded_h_no_item_code" class="form-control" placeholder="Enter height">
                                            </div>
                                            <div class="col-sm-4 mb-2">
                                                <label for="folded_w_no_item_code" class="form-label text-muted small">Width (W)</label>
                                                <input type="text" name="folded_w_no_item_code" id="folded_w_no_item_code" class="form-control" placeholder="Enter width">
                                            </div>
                                            <div class="col-sm-4 mb-2">
                                                <label for="folded_l_no_item_code" class="form-label text-muted small">Length (L)</label>
                                                <input type="text" name="folded_l_no_item_code" id="folded_l_no_item_code" class="form-control" placeholder="Enter length">
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
                                            <input class="custom-control-input" type="radio" id="color_cmyk_no_item_code" name="color_no_item_code" value="CMYK">
                                            <label for="color_cmyk_no_item_code" class="custom-control-label font-weight-normal">CMYK</label>
                                        </div>
                                        <div class="custom-control custom-radio mb-1">
                                            <input class="custom-control-input" type="radio" id="color_rgb_no_item_code" name="color_no_item_code" value="RGB">
                                            <label for="color_rgb_no_item_code" class="custom-control-label font-weight-normal">RGB</label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input class="custom-control-input" type="radio" id="color_grayscale_no_item_code" name="color_no_item_code" value="Grayscale">
                                            <label for="color_grayscale_no_item_code" class="custom-control-label font-weight-normal">Grayscale</label>
                                        </div>
                                        
                                            
                                    </div>
                                            <div class=" mb-2">
                                                <label for="default_color_text" class="form-label text-muted ">from Fact color</label>
                                                <input type="text" name="default_color_text_no_item_code" id="default_color_text" class=" w-100" placeholder="Enter Color" disabled>
                                            </div>
                                    {{-- Text Specs (Radio Buttons) --}}
                                    <div class="col-md-4 form-group">
                                        <label class="d-block font-weight-bold">Text</label>
                                        <div class="custom-control custom-radio mb-1">
                                            <input class="custom-control-input" type="radio" id="text_editable_no_item_code" name="text_specs_no_item_code" value="Editable">
                                            <label for="text_editable_no_item_code" class="custom-control-label font-weight-normal">Editable</label>
                                        </div>
                                        <div class="custom-control custom-radio mb-1">
                                            <input class="custom-control-input" type="radio" id="text_path_no_item_code" name="text_specs_no_item_code" value="Converted to path">
                                            <label for="text_path_no_item_code" class="custom-control-label font-weight-normal">Converted to path</label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input class="custom-control-input" type="radio" id="text_missing_fonts_no_item_code" name="text_specs_no_item_code" value="Missing Font(s)">
                                            <label for="text_missing_fonts_no_item_code" class="custom-control-label font-weight-normal">Missing Font(s)</label>
                                        </div>
                                    </div>

                                    {{-- Other Specs (Checkboxes) --}}
                                    <div class="col-md-4 form-group">
                                        <label class="d-block font-weight-bold">Other Specs</label>
                                        <div class="custom-control custom-checkbox mb-1">
                                            <input class="custom-control-input" type="checkbox" id="spec_diecut_no_item_code" name="other_specs_no_item_code[]" value="Die-cut">
                                            <label for="spec_diecut" class="custom-control-label font-weight-normal">Die-cut</label>
                                        </div>
                                        <div class="custom-control custom-checkbox mb-1">
                                            <input class="custom-control-input" type="checkbox" id="spec_stamping_no_item_code" name="other_specs_no_item_code[]" value="Stamping">
                                            <label for="spec_stamping" class="custom-control-label font-weight-normal">Stamping</label>
                                        </div>
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" type="checkbox" id="spec_uv_no_item_code" name="other_specs_no_item_code[]" value="UV">
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
                                            <input class="custom-control-input" type="checkbox" id="img_layered_no_item_code" name="image_type_no_item_code[]" value="Layered">
                                            <label for="img_layered" class="custom-control-label font-weight-normal">Layered</label>
                                        </div>
                                        <div class="custom-control custom-checkbox mb-1">
                                            <input class="custom-control-input" type="checkbox" id="img_missing_no_item_code" name="image_type_no_item_code[]" value="Missing Image(s)">
                                            <label for="img_missing_no_item_code" class="custom-control-label font-weight-normal">Missing Image(s)</label>
                                        </div>
                                        <div class="custom-control custom-checkbox mb-1">
                                            <input class="custom-control-input" type="checkbox" id="img_high_res_no_item_code" name="image_type_no_item_code[]" value="High Resolution">
                                            <label for="img_high_res_no_item_code" class="custom-control-label font-weight-normal">High Resolution</label>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="custom-control custom-checkbox mb-1">
                                            <input class="custom-control-input" type="checkbox" id="img_embedded_no_item_code" name="image_type_no_item_code[]" value="Embedded">
                                            <label for="img_embedded_no_item_code" class="custom-control-label font-weight-normal">Embedded</label>
                                        </div>
                                        <div class="custom-control custom-checkbox mb-1">
                                            <input class="custom-control-input" type="checkbox" id="img_flattened_no_item_code" name="image_type_no_item_code[]" value="Flattened">
                                            <label for="img_flattened_no_item_code" class="custom-control-label font-weight-normal">Flattened</label>
                                        </div>
                                        <div class="custom-control custom-checkbox mb-1">
                                            <input class="custom-control-input" type="checkbox" id="img_low_res_no_item_code" name="image_type_no_item_code[]" value="Low Resolution">
                                            <label for="img_low_res_no_item_code" class="custom-control-label font-weight-normal">Low Resolution</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="number_of_colors" class="font-weight-bold">Number of Colors</label>
                                    <input type="text" name="number_of_colors_no_item_code" id="number_of_colors_no_item_code" class="form-control" placeholder="e.g. 4-Color Process, Pantone 185C">
                                </div>

                                <div class="form-group row mt-4">
                                    <label for="verified_by_date_no_item_code" class="col-sm-4 col-form-label font-weight-bold">Verified by / Date</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="verified_by_date_no_item_code" id="verified_by_date_no_item_code" class="form-control" value="{{ auth()->user()->name . ' / ' . date('Y-m-d') }}">
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