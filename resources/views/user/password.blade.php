@extends('adminlte::auth.auth-page', ['auth_type' => 'login'])

{{-- @section('plugins.Select2',true) --}}

@section('auth_header', 'Change Password')

@section('auth_body')
    @if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @else
    <div class="alert alert-danger">
        Password has been reset. Please change your password.
    </div>
    @endif
    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif
    @if (session('message'))
        <div class="alert alert-info">
            {{ session('message') }}
        </div>
    @endif
    <form action="{{ route('password.change') }}" method="post">
        @method('patch')
        @csrf

        <div class="input-group mb-3">
            <input type="password" name="password" class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                    placeholder="New Password" autofocus>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-calendar {{ config('adminlte.classes_auth_icon', '') }}"></span>
                </div>
            </div>
            @if($errors->has('password'))
                <div class="invalid-feedback">
                    <strong>{{ $errors->first('password') }}</strong>
                </div>
            @endif
        </div>

        {{-- Password field --}}
        <div class="input-group mb-3">
            <input type="password" name="password_confirmation" class="form-control {{ $errors->has('password_confirmation') ? 'is-invalid' : '' }}"
                    placeholder="Password Confirmation" autofocus>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-calendar {{ config('adminlte.classes_auth_icon', '') }}"></span>
                </div>
            </div>
            @if($errors->has('password_confirmation'))
                <div class="invalid-feedback">
                    <strong>{{ $errors->first('password_confirmation') }}</strong>
                </div>
            @endif
        </div>

        {{-- Confirm password reset button --}}
        <div class="row">
            {{-- <div class="col-5">
                {{-- <a href="{{route('login')}}"> User Login</a> --}}
                
            {{-- </div> --}}

            <div class="col-md-12 text-right">
                <button type=submit class="btn {{ config('adminlte.classes_auth_btn', 'btn-flat btn-primary') }}">
                    <span class="fas fa-sign-in-alt"></span>
                    {{ __('adminlte::adminlte.sign_in') }}
                </button>
                {{-- <form action="{{ route('logout') }}" method="post">
                    @csrf --}}
                    <button type=button onclick="logout()" class="btn btn-flat btn-danger">
                        <span class="fas fa-solid fa-right-from-bracket"></span>
                        {{ __('adminlte::adminlte.log_out') }}
                    </button>
                {{-- </form> --}}
            </div>
        </div>

    </form>
@stop

@section('js')

<script>
    var logout;
    $(document).ready(function () {
        logout = function() {
            $.ajax({
                url: '{{ route('password.logout') }}', // Your API endpoint
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                },
                success: function(response) {
                    // console.log(response)
                    if(response.success) {
                        window.location = '{{ route("login") }}'
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
</script>

@endsection

