@extends('auth.layouts.app')

@section('content')
	<!-- begin login -->
	<div class="login login-with-news-feed">
		<!-- begin news-feed -->
		<div class="news-feed">
			<div class="news-image" style="background-image: url({{ asset('backend/assets/img/login-bg/login-bg-11.jpg')}})"></div>
			{{-- <div class="news-caption">
				<h4 class="caption-title"><b>{{ config('app.name', '') }}</b> Admin App</h4>
				<p>
					Download the Color Admin app for iPhone®, iPad®, and Android™. Lorem ipsum dolor sit amet, consectetur adipiscing elit.
				</p>
			</div> --}}
		</div>
		<!-- end news-feed -->
		<!-- begin right-content -->
		<div class="right-content">
			<!-- begin login-header -->
			<div class="login-header">
				<div class="brand">
					<span class="logo"></span> <b>{{ config('app.name', '') }}</b> Login
					{{-- <small>responsive bootstrap 4 admin template</small> --}}
				</div>
				<div class="icon">
					<i class="fa fa-sign-in"></i>
				</div>
			</div>
			<!-- end login-header -->
			<!-- begin login-content -->
			<div class="login-content">
				<form action="{{ route('login') }}" method="POST" class="margin-bottom-0">
					@csrf
					<div class="form-group m-b-15">
						<input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control form-control-lg" placeholder="Email Address" required />
						@error('email')
						    <span class="invalid-feedback" role="alert">
						        <strong>{{ $message }}</strong>
						    </span>
						@enderror
					</div>
					<div class="form-group m-b-15">
						<input type="password" id="password" name="password" class="form-control form-control-lg" placeholder="Password" required />
						@error('password')
						    <span class="invalid-feedback" role="alert">
						        <strong>{{ $message }}</strong>
						    </span>
						@enderror
					</div>
					<div class="checkbox checkbox-css m-b-30">
						<input type="checkbox"  name="remember" id="remember" {{ old('remember') ? 'checked' : '' }} />
						<label for="remember">
						{{ __('Remember Me') }}
						</label>
					</div>
					<div class="login-buttons">
						<button type="submit" class="btn btn-success btn-block btn-lg">Sign me in</button>
						@if (Route::has('password.request'))
						    <a class="btn btn-link" href="{{ route('password.request') }}">
						        {{ __('Forgot Your Password?') }}
						    </a>
						@endif
					</div>
					<div class="m-t-20 m-b-40 p-b-40 text-inverse">
						Not a member yet? Click <a href="{{route('register')}}" class="text-success">here</a> to register.
					</div>
					<hr />
					<p class="text-center text-grey-darker">
						&copy; {{ config('app.name', '') }} All Right Reserved {{ date('Y') }}
					</p>
				</form>
			</div>
			<!-- end login-content -->
		</div>
		<!-- end right-container -->
	</div>
	<!-- end login -->
@endsection
