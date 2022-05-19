@extends('auth.layouts.app')

@section('content')
<!-- begin reset password -->
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
        <!-- begin reset-password-header -->
        <h1 class="login-header">
            <div class="brand">
                <span class="logo"></span> <b>{{ config('app.name', '') }}</b> Reset Password
                {{-- <small>responsive bootstrap 4 admin template</small> --}}
            </div>
            <div class="icon">
                <i class="fa fa-sign-in"></i>
            </div>
        </h1>
        <!-- end reset-password-header -->
        <!-- begin reset-password-content -->
        <div class="login-content">
            <form action="{{ route('password.update') }}" method="POST" class="margin-bottom-0">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <div class="form-group m-b-15">
                    <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control form-control-lg" placeholder="Email Address" required />
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group m-b-15">
                    <input id="password" name="password" type="password" class="form-control form-control-lg" placeholder="Password" required  />
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group m-b-15">
                    <input id="password-confirm" name="password_confirmation" type="password" class="form-control form-control-lg" placeholder="Confirm Password" required  />
                </div>
                <div class="login-buttons">
                    <button type="submit" class="btn btn-success btn-block btn-lg">{{ __('Reset Password') }}</button>
                </div>
                <hr />
                <p class="text-center">
                    &copy; {{ config('app.name', '') }} All Right Reserved 2019
                </p>
            </form>
        </div>
        <!-- end reset-password-content -->
    </div>
    <!-- end right-content -->
</div>
<!-- end reset password -->
@endsection
