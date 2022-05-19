@extends('auth.layouts.app')

@section('content')
<!-- begin forgot password -->
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
        <!-- begin forgot-password-header -->
        <h1 class="login-header">
            <div class="brand">
                <span class="logo"></span> <b>{{ config('app.name', '') }}</b> Reset Password
                {{-- <small>responsive bootstrap 4 admin template</small> --}}
            </div>
            <div class="icon">
                <i class="fa fa-sign-in"></i>
            </div>
        </h1>
        <!-- end forgot-password-header -->
        <!-- begin forgot-password-content -->
        <div class="login-content">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
            <form action="{{ route('password.email') }}" method="POST" class="margin-bottom-0">
                @csrf
                <div class="form-group m-b-15">
                    <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control form-control-lg" placeholder="Email Address" required />
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="login-buttons">
                    <button type="submit" class="btn btn-success btn-block btn-lg">{{ __('Send Password Reset Link') }}</button>
                </div>
                <div class="m-t-20 m-b-40 p-b-40 text-inverse">
                    Click <a href="{{route('login')}}">here</a> to login.
                </div>
                <hr />
                <p class="text-center">
                    &copy; {{ config('app.name', '') }} All Right Reserved 2019
                </p>
            </form>
        </div>
        <!-- end forgot-password-content -->
    </div>
    <!-- end right-content -->
</div>
<!-- end forgot password -->
@endsection
