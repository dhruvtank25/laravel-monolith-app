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
				<div class="col-md-7 col-lg-6 register_frm">
					<h3>Willkommen bei <span>himmlischberaten.de</span></h3>
					<form  action="{{ route('login') }}" method="POST">
                        @csrf
                        <input type="hidden" name="redirect_to" value="{{$redirect_to}}">
						<div class="input-group register_field mb-3">
							<div class="input-group-prepend">
								<i class="input-group-text fa fa-envelope" aria-hidden="true"></i>
							</div>
							<input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="E-Mail" required>
                            @error('email')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
						</div>
						<div class="input-group register_field mb-4">
							<div class="input-group-prepend">
								<i class="input-group-text fa fa-unlock-alt" aria-hidden="true"></i>
							</div>
							<input type="password" id="password" name="password" class="form-control" placeholder="Passwort" required>
                            @error('password')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
						</div>
						<button type="submit" class="btn register_btn">Login</button>
						<p class="frgt_pass pull-right"><a href="{{ route('password.request') }}">Passwort vergessen?</a></p>
                        <p class="mt-2 reg_login">Du hast noch keinen Account?</p>
                        <p class="mt-2 reg_login">
                            <a class="" href="{{ route('user-register') }}">Jetzt registrieren</a>
                            @if(session('appointment_data', null))
                                oder <a class="reg_login" href="{{ route('guest-register') }}">als Gast fortfahren</a>
                            @endif
                        </p>
					</form>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('scripts')
@endsection