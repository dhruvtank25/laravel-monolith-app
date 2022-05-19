@extends('layouts.main')

@section('styles')
    <link rel="stylesheet" href="{{ asset('frontend/css/bootstrap-slider.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/availability.css') }}">
    <style type="text/css" media="screen">
        .ui-datepicker-multi-2 {
            width: 80% !important;
            display: block,
        }
        .datepicker table tr td, .datepicker table tr th {
            padding-left: 2px;
            padding-right: 2px;
        }
        .slider:before {
            font-family: 'Font Awesome 5 Free';
            font-weight: 900;
        }
         .bg-success {
            background-color: #28a745!important;
        } 
    </style>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-12 col-lg-8">
        {{ csrf_field() }}
        <input type="hidden" value="{{$coach_id}}" name="id" id="id">
        @include('admin.coaches._availability')
    </div>
</div>
@endsection

@section('scripts')
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