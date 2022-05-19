@extends('layouts.app')

@section('style_link')
@endsection

@section('content')
    
    <div class="container-fluid register_container">
        <div class="container">
            <div class="row justify-content-center">
                <!-- <div class="col-md-5 col-lg-6 register_img">
                    <img class="img-fluid" src="img/register_element.png" alt="x" />
                </div> -->
                <div class="col-md-7 col-lg-6 register_frm req_new_pass_wrap">
                    <h3>Passwort zurücksetzen</h3>
                    <form action="{{ route('password.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">
                        <div class="input-group register_field mb-3">
                            <div class="input-group-prepend">
                                <i class="input-group-text fa fa-envelope" aria-hidden="true"></i>
                            </div>
                            <input type="email" id="email" name="email"  value="{{ old('email') }}" class="form-control" placeholder="E-Mail" required>
                            @error('email')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="input-group register_field mb-3">
                            <div class="input-group-prepend">
                                <i class="input-group-text fa fa-unlock-alt" aria-hidden="true"></i>
                            </div>
                            <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
                            @error('password')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="input-group register_field mb-3">
                            <div class="input-group-prepend">
                                <i class="input-group-text fa fa-unlock-alt" aria-hidden="true"></i>
                            </div>
                            <input type="password" id="password-confirm" name="password_confirmation" class="form-control" placeholder="Confirm Password" required>
                        </div>
                        <button type="submit" class="btn guest_btn pull-right ml-2">Passwort zurücksetzen</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection 
  
@section('scripts')
    <script>
        $(".register_btn").click(function(event) {
            window.location.href = '{{ route('coach-register') }}';
        });
    </script>
@endsection
