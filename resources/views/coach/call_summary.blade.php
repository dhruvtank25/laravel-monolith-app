@extends('layouts.app')

@section('style_link')
<style>
    .aligncentercontent{
        margin:0 auto;
    }
    .summary-content{
        width: 100%;
        margin-top: 30px;
    }
    .summary-content thead th:first-child{
        background-color: #84D9E5;
        color:#ffffff;
        border-color: #fff;
        text-align: left;
    }   
    .summary-content thead th{
        padding:10px;
    }
</style>
@endsection

@section('content')
  <div class="container-fluid thankyou_container">
      <div class="container">
          <div class="row justify-content-center">
              <div class="col-sm-12 col-md-12 col-lg-8 thank_container text-center">
                  <!-- <div class="thk_icon">
                      
                  </div>  -->
<!--                   <h2 class="col-12 section_head">Summary</h2>
                  <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s</p>
                  <div class="col-md-6 aligncentercontent">
                      <table class="summary-content" border="1">
                          <thead>
                              <tr>
                                  <th>Name</th>
                                  <th>{{$appointment_user->first_name.' '.$appointment_user->last_name}}</th>
                              </tr>
                              <tr>
                                  <th>Cousellor Name</th>
                                  <th>{{$appointment_coach->first_name.' '.$appointment_coach->last_name}}</th>
                              </tr>
                              <tr>
                                  <th>Call of Duration</th>
                                  <th>{{$appointment->getCostCalculationAttribute()['duration_min']}} minutes</th>
                              </tr>
                              <tr>
                                  <th>Session Date</th>
                                  <th>{{$appointment->start->format('d/m/Y')}}</th>
                              </tr>
                              <tr>
                                  <th>Start time</th>
                                  <th>{{$appointment->start->format('h:i A')}} hrs</th>
                              </tr>
                              <tr>
                                  <th>End time</th>
                                  <th>{{$appointment->end->format('h:i A')}} hrs</th>
                              </tr>
                          </thead>
                      </table>
                  </div> -->
                  <div class="col-12">
                      <div class="summary_wrapper">
                          <h2 class="section_head text-center">Das Gespräch wurde beendet. </h2>
                          <a href="{{ route('coach') }}" class="btn orange_background_btn">Zurück zum Profil</a>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>
@endsection