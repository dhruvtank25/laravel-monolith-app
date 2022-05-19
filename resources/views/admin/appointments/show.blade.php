@extends('layouts.main')

@section('styles')
@endsection

@section('content')
    @include('admin.appointments.show_html')
@endsection

@section('scripts')
<!-- ================== PARTIAL INFO JS ================== -->
<script src="{{ asset('backend/js/appointment_info.js')}}"></script>
<script>
    $(document).ready(function() {
    });
</script>
@endsection
