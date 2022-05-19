@extends('layouts.main')

@section('styles')
@endsection

@section('content')
    <!-- begin table -->
    <div class="">
        <div class="category_shortdesc m-t-20">
          {{--   <h2>Short Description</h2> --}}
            {!! $country->name !!}
        </div>
        <div class="long_desc">
            {!! $country->code !!}
            <div class="clearfix"></div>
        </div>
    </div>
    
    <!-- end table -->
@endsection

@section('scripts')
@endsection
