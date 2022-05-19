@extends('layouts.main')

@section('styles')
@endsection

@section('content')
    <!-- begin table -->
    <div class="">
        <img src= "{{ FileUploadHelper::getDocPath($company->image, 'company_logo') }}" alt="x" height=100 width=100>
        <h2>{{$company->name}}</h2>
    </div>
    
    <!-- end table -->
@endsection

@section('scripts')
@endsection
