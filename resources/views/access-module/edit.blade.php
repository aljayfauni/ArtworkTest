@extends('adminlte::page')
@section('title','Access Module | Edit')

@section('plugins.Sweetalert2',true)
@section('plugins.Select2',true)

@section('content_header')
    {{-- <h1>Access Module</h1> --}}
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-9">
            <form action="{{route('access-modules.update', ['access_module' => $accessModule->id])}}" method="post">
                @method('PUT')
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">Edit Access Module</h3>
                    </div>
                    <div class="card-body">
                        
                        @csrf
                
                        <div class="row">
                            <div class="col-md-6">
                                {{-- Name field --}}
                        
                                <div class="form-group">
                                    <label for="name">Module Name:</label><i> (Required)</i>
                                    <div class="input-group mb-3">
                                        <input type="text" id="name" name="name" class="form-control form-control-sm {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                        value="{{ old('name', $accessModule->name) }}" placeholder="Module name" autofocus required>
                                        {{--<div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-user"></span>
                                            </div>
                                        </div>--}}
                                        @if($errors->has('name'))
                                            <div class="invalid-feedback">
                                                <strong>{{ $errors->first('name') }}</strong>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="description">Description:</label>
                                    <div class="input-group mb-3">
                                        <input type="text" id="description" name="description" class="form-control form-control-sm {{ $errors->has('description') ? 'is-invalid' : '' }}"
                                            value="{{ old('description', $accessModule->description) }}" placeholder="Description">
                                        
                                        @if($errors->has('description'))
                                            <div class="invalid-feedback">
                                                <strong>{{ $errors->first('description') }}</strong>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    
                    </div>
                    <div class="card-footer text-right">
                        <button type="submit" class="btn btn-success">Submit</button>
                        <a href="{{route('access-modules.index')}}" class="btn btn-primary">Back</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('js')
<script>
    var showApproversTable;
    var checkIfAdmin;
    var saveSelectedBranches;
    var loadBranches;
    var disableAlert;

    $(document).ready(function () {


        // $('[type=tel]').on('change', function(e) {
        //     $(e.target).val($(e.target).val().replace(/[^\d\.]/g, ''))
        // })

        // $('[type=tel]').on('keypress', function(e) {
        //     keys = ['0','1','2','3','4','5','6','7','8','9']
        //     return keys.indexOf(event.key) > -1
        // })

        // $('[type=tel]').prop("autocomplete", 'off');


        var Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 5000
        });

        @if (\Session::has('success'))
            Toast.fire({
                icon:'success',
                title:"{{ \Session::get('success') }}"
            });
        @endif
    

    });
</script>
@endsection