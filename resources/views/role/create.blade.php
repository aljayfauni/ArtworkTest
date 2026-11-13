@extends('adminlte::page')
@section('title','User Profile | Create')

@section('plugins.Sweetalert2',true)
@section('plugins.Select2',true)

@section('content_header')
    {{-- <h1>User Profile</h1> --}}
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-9">
            <form action="{{route('users.store')}}" method="post">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">New User</h3>
                    </div>
                    <div class="card-body">
                        
                        @csrf
                
                        <div class="row">
                            <div class="col-md-6">
                                {{-- Name field --}}
                        
                                <div class="form-group">
                                    <label for="name">Full Name:</label><i> (Required)</i>
                                    <div class="input-group mb-3">
                                        <input type="text" id="name" name="name" class="form-control form-control-sm {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                        value="{{ old('name') }}" placeholder="Full name" autofocus required>
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
                                    <label for="username">Username:</label><i> (Required)</i>
                                    <div class="input-group mb-3">
                                        <input type="text" id="username" name="username" class="form-control form-control-sm {{ $errors->has('username') ? 'is-invalid' : '' }}"
                                            value="{{ old('username') }}" placeholder="Username" required>
                                        
                                        @if($errors->has('username'))
                                            <div class="invalid-feedback">
                                                <strong>{{ $errors->first('username') }}</strong>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="col-md-3">
                                {{-- Status field 

                                <div class="form-group">
                                    <label for="status">Status:</label>
                                    <div class="input-group mb-3">
                                        <input type="checkbox" value="1" name="status" {{ old('status')== '1' ? 'checked' : '' }} checked data-bootstrap-switch  data-on-text='Active' data-off-text='Inactive'>
                                    </div>
                                </div>
                            </div> --}}
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password">Default Password:</label>
                                    <div class="input-group mb-3">
                                        <input type="text" id="password" name="password" class="form-control form-control-sm {{ $errors->has('password') ? 'is-invalid' : '' }}"
                                            value="Ugec-12345" placeholder="password" readonly>
                                        
                                        @if($errors->has('password'))
                                            <div class="invalid-feedback">
                                                <strong>{{ $errors->first('password') }}</strong>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="description">Roles:</label><i> (Required)</i>
                                    <div class="input-group mb-3">
                                        <select name="roles[]" class="form-control form-control-sm {{ $errors->has('roles') ? 'is-invalid' : '' }} select2bs4" multiple autofocus required>
                                            @if($roles)
                                                @foreach($roles as $value)
                                                <option value="{{ $value->name }}" {{ in_array($value->name, old('roles', [])) ? 'selected' : '' }}>{{ $value->description }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        
                                        @if($errors->has('roles'))
                                            <div class="invalid-feedback">
                                                <strong>{{ $errors->first('roles') }}</strong>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        
                    
                    </div>
                    <div class="card-footer text-right">
                        <button type="submit" class="btn btn-success">Submit</button>
                        <a href="{{route('users.index')}}" class="btn btn-primary">Back</a>
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

        $('.select2bs4').select2({
            theme: 'bootstrap4',
            placeholder: 'Select Roles',
        });


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