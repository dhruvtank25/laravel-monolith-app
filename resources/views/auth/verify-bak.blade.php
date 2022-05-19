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
                <span class="logo"></span> <b>{{ config('app.name', '') }}</b> {{ __('Verify Your Email Address') }}
                {{-- <small>responsive bootstrap 4 admin template</small> --}}
            </div>
            <div class="icon">
                <i class="fa fa-sign-in"></i>
            </div>
        </h1>
        <!-- end reset-password-header -->
        <!-- begin reset-password-content -->
        <div class="login-content">
            @if (session('resent'))
                <div class="alert alert-success" role="alert">
                    {{ __('A fresh verification link has been sent to your email address.') }}
                </div>
            @endif

            {{ __('Before proceeding, please check your email for a verification link.') }}

            {{ __('If you did not receive the email') }}, <a href="{{ route('verification.resend') }}">{{ __('click here to request another') }}</a>.
        </div>
        <!-- end reset-password-content -->
    </div>
    <!-- end right-content -->
</div>
<!-- end reset password -->
@endsection
