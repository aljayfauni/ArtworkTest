

<div class="modal fade" id="SearchMOModal" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h6><i class="fas fa-solid fa-user-tie"> </i> Search MO</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><i class="fas fa-solid fa-magnifying-glass"></i></span>
                            </div>
                            <input type="text" id="search" name="search" class="form-control" placeholder="Search MO">
                        </div>
                        {{-- <input type="text" id="search" name="search" class="form-control form-control-sm" placeholder="Search MO" /> --}}
                    </div>
                    <div class="col-md-6">
                        <button type="button" onclick="searchMO()" class="btn btn-primary">Search</button>
                    </div>
                    <div class="col-md-3 text-right">
                        @if (Route::is('shopfloor-admin.edit') && $shopfloor->action->is_reprocess)
                            <a href="#" class="add_mo">Can't find MO? Add Manually Here!</a>
                        @else
                            <a href="#" class="add_mo" style="display: none">Can't find MO? Add Manually Here!</a>
                        @endif
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12">
                        <table id="MOTable" class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>MO No.</th>
                                    {{-- <th>MO Date.</th> --}}
                                    <th>Status</th>
                                    {{-- <th>BOM No.</th> --}}
                                    {{-- <th>BOM Revision No.</th> --}}
                                    <th>FG Item Code</th>
                                    <th>FG Description</th>
                                    <th>MO Qty</th>
                                    <th>UOM</th>
                                    {{-- <th>Route Name</th> --}}
                                    <th>Actions</th>
                                    {{-- <th>Actions</th> --}}
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