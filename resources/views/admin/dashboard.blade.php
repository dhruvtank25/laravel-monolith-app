@extends('layouts.main')

@section('styles')
@endsection

@section('content')
    <!-- begin row -->
    <div class="row">
        <!-- begin col-3 -->
        <div class="col-lg-3 col-md-6">
            <div class="widget widget-stats bg-red">
                <div class="stats-icon"><i class="fa fa-desktop"></i></div>
                <div class="stats-info">
                    <h4>TOTAL USERS</h4>
                    <p>{{ $data_arr['user'] }}</p>    
                </div>
                <div class="stats-link">
                    <a href="{{ url('/admin/users') }}">View Detail <i class="fa fa-arrow-alt-circle-right"></i></a>
                </div>
            </div>
        </div>
        <!-- end col-3 -->
        <!-- begin col-3 -->
        <div class="col-lg-3 col-md-6">
            <div class="widget widget-stats bg-orange">
                <div class="stats-icon"><i class="fa fa-users"></i></div>
                <div class="stats-info">
                    <h4>TOTAL COACHES</h4>
                    <p>{{ $data_arr['coach'] }}</p>   
                </div>
                <div class="stats-link">
                    <a href="{{ url('/admin/coaches') }}">View Detail <i class="fa fa-arrow-alt-circle-right"></i></a>
                </div>
            </div>
        </div>
        <!-- end col-3 -->
        <!-- begin col-3 -->
        <div class="col-lg-3 col-md-6">
            <div class="widget widget-stats bg-green">
                <div class="stats-icon"><i class="fa fa-user"></i></div>
                <div class="stats-info">
                    <h4>TOTAL GUESTS</h4>
                    <p>{{ $data_arr['guest'] }}</p>   
                </div>
                <div class="stats-link">
                    <a href="{{ url('/admin/guests') }}">View Detail <i class="fa fa-arrow-alt-circle-right"></i></a>
                </div>
            </div>
        </div>
        <!-- end col-3 -->
        <!-- begin col-3 -->
        <div class="col-lg-3 col-md-6">
            <div class="widget widget-stats bg-grey-darker">
                <div class="stats-icon"><i class="fa fa-user"></i></div>
                <div class="stats-info">
                    <h4>COMPLETED SESSIONS</h4>
                    <p>{{ $data_arr['completed_session'] }}</p>    
                </div>
                <div class="stats-link">
                    <a href="{{ url('/admin/appointment?status=completed') }}">View Detail <i class="fa fa-arrow-alt-circle-right"></i></a>
                </div>
            </div>
        </div>
        <!-- end col-3 -->
        <!-- begin col-3 -->
        <div class="col-lg-3 col-md-6">
            <div class="widget widget-stats bg-black-lighter">
                <div class="stats-icon"><i class="fa fa-clock"></i></div>
                <div class="stats-info">
                    <h4>TOTAL SESSIONS</h4>
                    <p>{{ $data_arr['total_session'] }}</p> 
                </div>
                <div class="stats-link">
                    <a href="{{ url('/admin/appointment') }}">View Detail <i class="fa fa-arrow-alt-circle-right"></i></a>
                </div>
            </div>
        </div>
        <!-- end col-3 -->
    </div>
    <!-- end row -->
@endsection

@section('scripts')
@endsection