@extends('layouts.app')

@section('style_link')
    <link rel="stylesheet" href="{{ asset('frontend/css/dropzone.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/jquery-ui.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/bootstrap-slider.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/availability.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/responsive.bootstrap4.min.css') }}">
@endsection

@section('content')
<div class="container-fluid">
    <div class="container">
        <div class="row">
            <h5 class="col-12 ch_tabdata_head">Verfügbarkeit</h5>
            <div class="col-md-12 col-lg-8">
                {{ csrf_field() }}
                <input type="hidden" value="{{ Auth::guard('coach')->id() }}" name="id" id="id">
                @include('admin.coaches._availability')
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
        <script src="{{ asset('frontend/js/select2.min.js') }}"></script>
        <script src="{{ asset('frontend/js/jquery.dataTables.js') }}"></script>
        <script src="{{ asset('frontend/js/dataTables.bootstrap4.min.js') }}"></script>
        <script src="{{ asset('frontend/js/dataTables.responsive.min.js') }}"></script>   
        <script src="{{ asset('frontend/js/dropzone.js') }}"></script>
        <script src="{{ asset('frontend/js/jquery-ui.min.js') }}"></script>
        <script src="{{ asset('frontend/js/datepicker-de.js') }}"></script>
        <script src="{{ asset('frontend/js/bootstrap-slider.min.js') }}"></script>
        <script src="{{ asset('backend/js/availability.js') }}"></script>
        <script>
            $(document).ready(function() {
                // Initialize availability module
                AvailabilityModule.initUpdateAvailability();
                // Initialize unavailability module
                AvailabilityModule.initUnavailability();
            });
        </script>
@endsection