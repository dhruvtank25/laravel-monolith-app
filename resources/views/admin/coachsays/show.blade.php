@extends('layouts.main')

@section('styles')
@endsection

@section('content')
    <div class="well">
        <p><h2>{{$coach->first_name}} {{$coach->last_name}}</h2></p>
        <p>{{$coachsay->comment}}</p>
        <p><a href="{{ url('admin/coach-says') }}" class="btn btn-sm btn-info"><i class="fas fa-arrow-alt-circle-right fa-flip-horizontal"></i> Back</a></p>
    </div>
@endsection

@section('scripts')
@endsection
