@extends('adminlte::page')
@section('title','User Profile | Edit')

@section('plugins.Sweetalert2',true)
@section('plugins.Select2',true)
@section('plugins.bootstrapSwitch',true)

@section('content_header')
    {{-- <h1>User Profile</h1> --}}
@endsection

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <!-- Left Side: Profile Summary Card -->
        <div class="col-md-4 mt-5">
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <div class="text-center mb-4">
                        @if($user->profile_photo_path)
                            <!-- User Uploaded Image -->
                            <img class="profile-user-img img-fluid img-circle shadow-sm"
                                src="{{ asset('storage/' . $user->profile_photo_path) }}"
                                alt="{{ $user->name }}">
                        @else
                            <!-- Native AdminLTE Icon Fallback -->
                            <div class="profile-user-img img-circle shadow-sm bg-light d-inline-flex align-items-center justify-content-center text-secondary" 
                                style="height: 100px; width: 100px;">
                                <i class="fas fa-user fa-3x"></i>
                            </div>
                        @endif
                    </div>

                    <h3 class="profile-username text-center font-weight-bold">{{ $user->name }}</h3>
                    {{-- <p class="text-muted text-center">{{ $user->role ?? 'User Profile' }}</p> --}}
                       <p class="text-muted text-center" >  {{ $userRoles->name ?? 'No Role Assigned' }}</p>
                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b><i class="fas fa-envelope mr-2 text-primary"></i> Email</b> 
                            <span class="float-right text-muted">{{ $user->email }}</span>
                        </li>
                        <li class="list-group-item">
                            <b><i class="fas fa-calendar-alt mr-2 text-success"></i> Joined</b> 
                            <span class="float-right text-muted">{{ $user->created_at->format('M d, Y') }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Right Side: Detailed Profile Information -->
        <div class="col-md-6 mt-5">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-user-circle mr-1"></i> Account Details
                    </h3>
                </div>
                <div class="card-body">
                    <strong><i class="fas fa-id-card mr-1 text-secondary"></i> Full Name</strong>
                    <p class="text-muted">{{ $user->name }}</p>
                    <hr>

                    <strong><i class="fas fa-envelope mr-1 text-secondary"></i> Email Address</strong>
                    <p class="text-muted">{{ $user->email }}</p>
                    <hr>

                    <strong><i class="fas fa-user-shield mr-1 text-secondary"></i> Account Status</strong>
                    <p class="text-muted">
                        @if($user->email_verified_at)
                            <span class="badge badge-success">Verified</span>
                        @else
                            <span class="badge badge-warning">Unverified</span>
                        @endif
                    </p>
                </div>
                <div class="card-footer text-right">
                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-edit mr-1"></i> Edit Profile
                    </a>
                </div>
            </div>
        </div>
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