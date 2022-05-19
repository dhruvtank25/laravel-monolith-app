@extends('layouts.main')

@section('styles')
@endsection

@section('content')
    <p>{{ucfirst($faq->type)}}</p>
    <div class="well">
        <p><h2>{{$faq->title}}</h2></p>
        <p>{{$faq->description}}</p>
        <p><a href="{{ url('admin/faqs') }}" class="btn btn-sm btn-info"><i class="fas fa-arrow-alt-circle-right fa-flip-horizontal"></i> Back</a></p>
    </div>
    <div>
    </div>
@endsection

@section('scripts')
@endsection
