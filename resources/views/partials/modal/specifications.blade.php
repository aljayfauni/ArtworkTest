<!-- Specifications Modal -->
<div class="modal fade" id="specificationsModal" tabindex="-1" role="dialog" aria-labelledby="specificationsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
        <form action="{{ route('saveSpecification.save') }}" method="POST" id="specificationsForm">
                @csrf
                {{-- Hidden input to link with the datatable record ID --}}
                <input type="hidden" name="file_id" id="spec_file_id">

                <div class="card card-info card-outline mb-0">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center w-100">
                            <h3 class="card-title text-uppercase font-weight-bold mb-0">
                                SPECIFICATIONS <small class="text-muted font-italic">(to be filled out by requestor) <span class="text-danger font-weight-bold">mandatory</span></small>
                            </h3>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        {{-- Item Code, Size & Printing Process --}}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="spec_item_code" class="col-sm-3 col-form-label">Item Code <span class="text-danger">*</span></label>
                                    <div class="col-sm-9">
                                        <input type="text" name="spec_item_code" id="spec_item_code" class="form-control @error('spec_item_code') is-invalid @enderror" value="{{ old('spec_item_code') }}" placeholder="Enter item code" required>
                                        @error('spec_item_code')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="spec_size_spread" class="col-sm-3 col-form-label">Size: Spread</label>
                                    {{-- <div class="col-sm-9">
                                        <input type="text" name="spec_size_spread" id="spec_size_spread" class="form-control" value="{{ old('spec_size_spread') }}" placeholder="Enter spread size">
                                    </div> --}}
                                       <div class="col-sm-9">
                                        <div class="row">
                                            <div class="col-sm-4 mb-2">
                                                <label for="spec_spread_h" class="form-label text-muted small">Height (H)</label>
                                                <input type="text" name="spec_spread_h" id="spec_spread_h" class="form-control" placeholder="Enter height">
                                            </div>
                                            <div class="col-sm-4 mb-2">
                                                <label for="spec_spread_w" class="form-label text-muted small">Width (W)</label>
                                                <input type="text" name="spec_spread_w" id="spec_spread_w" class="form-control" placeholder="Enter width">
                                            </div>
                                            <div class="col-sm-4 mb-2">
                                                <label for="spec_spread_l" class="form-label text-muted small">Length (L)</label>
                                                <input type="text" name="spec_spread_l" id="spec_spread_l" class="form-control" placeholder="Enter length">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="spec_size_folded" class="col-sm-3 col-form-label">Folded</label>
                                        <div class="col-sm-9">
                                        <div class="row">
                                            <div class="col-sm-4 mb-2">
                                                <label for="spec_folded_h" class="form-label text-muted small">Height (H)</label>
                                                <input type="text" name="spec_folded_h" id="spec_folded_h" class="form-control" placeholder="Enter height">
                                            </div>
                                            <div class="col-sm-4 mb-2">
                                                <label for="spec_folded_w" class="form-label text-muted small">Width (W)</label>
                                                <input type="text" name="spec_folded_w" id="spec_folded_w" class="form-control" placeholder="Enter width">
                                            </div>
                                            <div class="col-sm-4 mb-2">
                                                <label for="spec_folded_l" class="form-label text-muted small">Length (L)</label>
                                                <input type="text" name="spec_folded_l" id="spec_folded_l" class="form-control" placeholder="Enter length">
                                            </div>
                                        </div>
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
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <div>
                            <button type="reset" class="btn btn-default mr-2">Reset</button>
                            <button type="submit" class="btn btn-info"><i class="fas fa-paper-plane mr-1"></i> Save Specifications</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>