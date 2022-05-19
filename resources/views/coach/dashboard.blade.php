@extends('layouts.app')

@section('style_link')
    <link rel="stylesheet" href="{{ asset('frontend/css/jquery-ui.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/availability.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/availability.css') }}">
@endsection

@section('content')
    @csrf
    <input type="hidden" id="id" value="{{Auth::guard('coach')->id()}}">
    <!-- Message Modal -->
    <div class="modal fade" id="booking_messsage_popup">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Message</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <p>This is test message</p>
                </div>
            </div>
        </div>
    </div>
    <!-- Message modal END -->
    
    <!-- coach tab data section start -->
    <div class="container-fluid ch_overview_container">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    @php
                        $coach_status = Auth::guard('coach')->user()->status;
                    @endphp
                    @if($coach_status=='incomplete')
                        <div class="alert dashboarderror"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> Dein Profil ist nicht vollständig. Bitte fülle alle Pflichtfelder aus, damit dein Profil veröffentlicht werden kann. 
                        </div>
                    @elseif($coach_status=='approval')
                        <div class="alert dashboardwarning"><i class="fa fa-clock-o" aria-hidden="true"></i> Dein Profil wird überprüft und anschließend freigegeben.
                        </div>
                    @elseif($coach_status=='active')
                        <div class="alert dashboardsuccess"><i class="fa fa-check" aria-hidden="true"></i> Dein Profil ist aktiv.
                        </div>
                    @elseif($coach_status=='inactive')
                        <div class="alert dashboarderror">
                            Ihr Profil ist inaktiv. Wenden Sie sich an admin, um weitere Informationen zu erhalten.
                        </div>
                    @endif
                </div>
                <h5 class="col-12 ch_tabdata_head">Nächste Termine</h5>
                <div class="col-md-12 mb-5 coach_data_tbs nxt_meet_data_tbs">
                    <table id="overview_data_table" class="table dt-responsive nowrap blue overview_dt_table" style="width:100%">
                        <thead>
                            <tr>
                                <th>Datum</th>
                                <th>Kunde</th>
                                <th>Kategorie</th>
                                <th>Startzeit</th>
                                <th>Dauer</th>
                                <th>Ort</th>
                                <th>Status</th>
                                <th>Aktion</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <h5 class="col-12 ch_tabdata_head">Meine Verfügbarkeit</h5>
                <div class="overview_my_available">
                    <div class="mb-4 period_date_wrap">
                        <div class="datepicker"></div>
                    </div>
                    <a href="{{route('coach.availabilites')}}" class="btn blk_bor_btn">Verfügbarkeit bearbeiten</a>
                </div>
            </div>
        </div>
    </div>
    <!-- coach tab data section end -->
@endsection 

@section('scripts')    
    <script src="{{ asset('frontend/js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('frontend/js/datepicker-de.js') }}"></script>
    <script src="{{ asset('frontend/js/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('frontend/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('frontend/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('frontend/js/custom/coach_appointments.js') }}"></script>
    <script src="{{ asset('backend/js/availability.js') }}"></script>
    <script>
        $(document).ready(function() {

            // Initialize jquery ui calendar
            AvailabilityModule.initCalendar();

            // Set Available/UnavailableHighlights
            AvailabilityModule.setAvailabilityStatuses();

            // Initialize Datatables
            CoachAppointmentModule.initDataTable('overview_data_table', 'future');

        });

    </script>
@endsection