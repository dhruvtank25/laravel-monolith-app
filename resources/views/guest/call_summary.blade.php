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
                  <div class="col-12">
                      <div class="summary_wrapper">
                          <h2 class="section_head text-center">Das Gespräch wurde beendet. </h2>
                          <a href="{{ route('guest_user') }}" class="btn orange_background_btn">Zurück zur Startseite</a>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>
@endsection