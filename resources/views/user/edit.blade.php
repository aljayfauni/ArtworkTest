@extends('adminlte::page')
@section('title','User Profile | Edit')

@section('plugins.Sweetalert2',true)
@section('plugins.Select2',true)
@section('plugins.bootstrapSwitch',true)

@section('content_header')
    {{-- <h1>User Profile</h1> --}}
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-9">
            <form action="{{route('users.update',$user->id)}}" method="post">
                @method('patch')
                @csrf
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">Edit User</h3>
                    </div>
                    <div class="card-body">
                
                        <div class="row">
                            <div class="col-md-6">
                                {{-- Name field --}}
                        
                                <div class="form-group">
                                    <label for="name">Full Name:</label>
                                    <div class="input-group mb-3">
                                        <input type="text" id="name" name="name" class="form-control form-control-sm {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                        value="{{ $user->name }}" placeholder="Full name" autofocus disabled>
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
                                    <label for="username">Username:</label>
                                    <div class="input-group mb-3">
                                        <input type="text" id="username" name="username" class="form-control form-control-sm {{ $errors->has('username') ? 'is-invalid' : '' }}"
                                            value="{{ $user->username }}" placeholder="Username" disabled>
                                        
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
                                    <label for="description">Roles:</label>
                                    <div class="input-group mb-3">
                                        <select name="role_id" class="form-control form-control-sm {{ $errors->has('role_id') ? 'is-invalid' : '' }} select2bs4" autofocus>
                                            <option value="">Select Role</option>
                                            @foreach($roles as $item)
                                                <option value="{{ $item->id }}"{{ $item->id == $user->role_id ? 'selected' : '' }}>{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                        
                                        @if($errors->has('role_id'))
                                            <div class="invalid-feedback">
                                                <strong>{{ $errors->first('role_id') }}</strong>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="description">Receive Notifications:</label>
                                    <div class="input-group mb-3">
                                        <input type="checkbox" value="1" name="receive_notifications" {{ $user->receive_notifications == '1' ? 'checked' : '' }} data-bootstrap-switch data-on-text='Yes' data-off-text='No'>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        
                    
                    </div>
                    <div class="card-footer text-right">
                        <button type="submit" class="btn btn-success">Update</button>
                        {{-- @if(!$user->guid && !$user->domain)
                        <button type="button" onclick="resetPassword()" class="btn btn-danger">Reset Password</button>
                        @endif --}}
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
    var resetPassword;

    $(document).ready(function () {

        $('.select2bs4').select2({
            theme: 'bootstrap4',
            placeholder: 'Select Roles',
        });

        $('[data-bootstrap-switch]').each(function(){
            $(this).bootstrapSwitch('state', $(this).prop('checked'));
        });


        // $('[type=tel]').on('change', function(e) {
        //     $(e.target).val($(e.target).val().replace(/[^\d\.]/g, ''))
        // })

        // $('[type=tel]').on('keypress', function(e) {
        //     keys = ['0','1','2','3','4','5','6','7','8','9']
        //     return keys.indexOf(event.key) > -1
        // })

        // $('[type=tel]').prop("autocomplete", 'off');

        resetPassword = function() {
            Swal.fire({  
                title: 'Are you sure you want to reset the password of the selected user?', 
                text: "You won't be able to revert this!",
                icon: 'warning', 
                showCancelButton: true,  
                confirmButtonText: `Yes`,  
                cancelButtonText: `No`,
            }).then((result) => {  
                /* Read more about isConfirmed, isDenied below */ 
                if (result.value) {    
                    $.ajax({
                        url: '{{ route('users.passwordreset',$user->id) }}', // Your API endpoint
                        type: 'PATCH',
                        data: {
                            _token: '{{ csrf_token() }}',
                        },
                        success: function(response) {
                            // console.log(response.success)
                            if(response.success) {
                                Swal.fire({  
                                    title: response.success, 
                                    text: 'Password: ' + response.pass,
                                    icon: 'warning', 
                                    showCancelButton: false,  
                                    confirmButtonText: `Ok`,  
                                    cancelButtonText: `No`,
                                })
                            }
                            // $('#responseMessage').html('<p>Success: ' + response.message + '</p>');
                        },
                        error: function(xhr) {
                            var errorMessage = xhr.responseJSON.message || 'An error occurred.';
                            $('#responseMessage').html('<p>Error: ' + errorMessage + '</p>');
                        }
                    }); 
                }
            });
        }


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