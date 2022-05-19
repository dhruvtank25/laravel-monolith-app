@extends('layouts.app')

@section('style_link')
    <link rel="stylesheet" href="{{ asset('frontend/css/jquery-ui.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/availability.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/responsive.bootstrap4.min.css') }}">
@endsection

@section('content')
    @csrf
    <!-- Modal -->
    <div class="modal fade coach_msg_modal user_booking_pop" id="coach_booking_modal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mb-3" id="exampleModalLongTitle">Termin vorschlagen</h5>
            </div>
            <div class="modal-body coach_msg_body">
    			<div class="row availContainer m-0">
                    <input type="hidden" class="request_id">
    				<p class="col-md-12">Bitte wähle drei Termine aus, die du dem Kunden als Vorschlag senden möchtest.</p>
    				<div class="col-md-12 mb-5 mt-4 period_date_wrap">
    					<div class="datepicker" id="unavailDatepicker"></div>
    				</div>
                    <div class="col-md-12 avail_request_container">
                        <div class="row avail_request avail_timeslots unavail_active">
            				<h5 class="col-md-7 ch_tabdata_head mb-3">Terminvorschlag 1</h5>
                            <a href="javascript:void(0);" class="col-md-5 choosetimeslots">bearbeiten</a>
                            <div class="unavail_inactive_container col-md-6" style="display:none;">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="inactivedata unavail_hdate">No date selected</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="inactivedata unavail_htime">No time selected</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="inactivedata unavail_hduratoin">No duration selected</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="inactivedata unavail_hstatus">No status selected</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 unavail_data_container">
                                <div class="row">
                    				<div class="col-md-6 book_pop_avail mb-4">
                    					<h6 class="mb-2">Ausgewählter Termin</h6>
                    					<p class="blk_rnd_select select_date active">10. Mai 2019</p>
                    				</div>
                    				<div class="col-md-6 book_pop_avail mb-4">
                    					<h6 class="mb-2">Startzeit auswählen</h6>
                    					<div class="mb-3 mb-md-0 reg_single_select book_pop_select">
                    						<select class="select_time">
                    						</select>
                    					</div>
                    				</div>
                    				<div class="col-md-12 book_pop_avail mb-4 avail_dur_container">
                    					<h6 class="mb-2">Beratungsdauer</h6>
                                        <div class="avail_duration" id="availability_duration">
                                        </div>
                    				</div>
                    				<div class="col-md-12 book_pop_avail mb-4 coach_mode_container">
                    				</div>
                                </div>
                            </div>
                        </div>
                        <div class="row avail_request avail_timeslots">
                            <h5 class="col-md-7 ch_tabdata_head mb-3">Terminvorschlag 2</h5>
                            <a href="javascript:void(0);" class="col-md-5 choosetimeslots">bearbeiten</a>
                            <div class="unavail_inactive_container col-md-6">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="inactivedata unavail_hdate">No date selected</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="inactivedata unavail_htime">No time selected</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="inactivedata unavail_hduratoin">No duration selected</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="inactivedata unavail_hstatus">No status selected</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 unavail_data_container" style="display:none;">
                                <div class="row">
                                    <div class="col-md-6 book_pop_avail mb-4">
                                        <h6 class="mb-2">Ausgewählter Termin</h6>
                                        <p class="blk_rnd_select select_date active">10. Mai 2019</p>
                                    </div>
                                    <div class="col-md-6 book_pop_avail mb-4">
                                        <h6 class="mb-2">Startzeit auswählen</h6>
                                        <div class="mb-3 mb-md-0 reg_single_select book_pop_select">
                                            <select class="select_time">
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12 book_pop_avail mb-4 avail_dur_container">
                                        <h6 class="mb-2">Beratungsdauer</h6>
                                        <div class="avail_duration" id="availability_duration">
                                        </div>
                                    </div>
                                    <div class="col-md-12 book_pop_avail mb-4 coach_mode_container">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row avail_request avail_timeslots">
                            <h5 class="col-md-7 ch_tabdata_head mb-3">Terminvorschlag 3</h5>
                            <a href="javascript:void(0);" class="col-md-5 choosetimeslots">bearbeiten</a>
                            <div class="unavail_inactive_container col-md-6">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="inactivedata unavail_hdate">No date selected</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="inactivedata unavail_htime">No time selected</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="inactivedata unavail_hduratoin">No duration selected</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="inactivedata unavail_hstatus">No status selected</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 unavail_data_container" style="display:none;">
                                <div class="row">
                                    <div class="col-md-6 book_pop_avail mb-4">
                                        <h6 class="mb-2">Ausgewählter Termin</h6>
                                        <p class="blk_rnd_select select_date active">10. Mai 2019</p>
                                    </div>
                                    <div class="col-md-6 book_pop_avail mb-4">
                                        <h6 class="mb-2">Startzeit auswählen</h6>
                                        <div class="mb-3 mb-md-0 reg_single_select book_pop_select">
                                            <select class="select_time">
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12 book_pop_avail mb-4 avail_dur_container">
                                        <h6 class="mb-2">Beratungsdauer</h6>
                                        <div class="avail_duration" id="availability_duration">
                                        </div>
                                    </div>
                                    <div class="col-md-12 book_pop_avail mb-4 coach_mode_container">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
    		          <button type="button" class="btn orange_background_btn requestAppmntBtn">Vorschläge senden</button>
                    </div>
    			</div>
            </div>
        </div>
      </div>
    </div>
    <!-- Modal end-->

    <!-- Message Modal -->
    <div class="modal fade message_popup" id="booking_messsage_popup">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Message from user{{-- <p class="modal-date">24. Mai 2019</p> --}}</h5>
                    {{-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button> --}}
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
                @if($app_requests->count()>0)
    				<h5 class="col-12 ch_tabdata_head">Offene Terminanfragen ({{$app_requests->count()}})</h5>
                    @foreach ($app_requests as $app_request)
                        <div class="col-md-12 mb-5 coach_data_tbs requests_container avail_request-{{$app_request->id}}">
                            <div class="table-responsive">
                                <table class="table dt-responsive nowrap red overview_dt_table">
                                    <thead>
                                        <tr>
                                            <th>{{$app_request->user->first_name.' '.$app_request->user->last_name}}</th>
                                            <th>{{$app_request->categories->title}} <i class="fa fa-envelope {{$app_request->notes?'mail_enable':'mail_disable'}}"  data-toggle="tooltip" data-tooltip="{{$app_request->notes}}" title="{{$app_request->notes}}" aria-hidden="true" data-message="{{$app_request->notes}}"></i></th>
                                            <th>&nbsp;</th>
                                            <th>&nbsp;</th>
                                            <th>&nbsp;</th>
                                            <th>&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($app_request->slots as $slot)
                                            <tr>
                                                <td>Terminvorschlag {{ $loop->iteration }}</td>
                                                <td colspan="2">{{ $slot->start->format('d.m.Y H:i A') }}</td>
                                                <td>{{ $slot->start->diffInMinutes($slot->end) }} Minuten</td>
                                                <td>{{ $slot->mode }}</td>
                                                <td> 
                                                    <button class="btn btn-primary bluebtn accept_app_request" data-id="{{$app_request->id}}" data-slot_id="{{$slot->id}}">Bestätigen 
                                                    <i class="fa fa-check" aria-hidden="true"></i></button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <button type="button" class="btn blk_bor_btn pull-right mt-3 reject_app_request" data-id="{{$app_request->id}}">Absenden</button>
                            <button type="button" data-id="{{$app_request->id}}" class="btn blk_bor_btn pull-right mt-3 alternative_suggest_btn" data-toggle="modal">Alternativer Vorschlag</button>
                        </div>
                    @endforeach
                @endif
				<h5 class="col-12 ch_tabdata_head">Nächste Termine {{-- (1 neu) --}}</h5>
                <p class="col-md-12 mb-3">Aktualisiere diese Seite sobald dein Termin beginnt – dann wird dein Startbutton aktiviert.</p>
				<div class="col-md-12 mb-5 coach_data_tbs">
					<table id="booking_event_data_table" class="table dt-responsive nowrap blue overview_dt_table" style="width:100%">
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
				<h5 class="col-12 ch_tabdata_head">Vergangene Termine</h5>
				<div class="col-md-12 mb-5 coach_data_tbs">
					<table id="past_event_data_table" class="table dt-responsive nowrap grey overview_dt_table" style="width:100%">
						<thead>
							<tr>
								<th>Datum</th>
								<th>Kunde</th>
								<th>Kategorie</th>
								<th>Startzeit</th>
								<th>Dauer</th>
								<th>Ort</th>
                                <th>Status</th>
								<th>Rechnung</th>
							</tr>
						</thead>
						<tbody>
							{{-- <tr>
								<td>08.06.2019</td>
								<td>Max Mustermann</td>
								<td>Persönlichkeit entwickeln <i class="fa fa-envelope mail_disable" aria-hidden="true"></i></td>
								<td>15:00 Uhr</td>
								<td>90 Min</td>
								<td>Online</td>
								<td>120 <i class="fa fa-eur" aria-hidden="true"></i></td>
							</tr> --}}
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<!-- coach tab data section end -->
@endsection  

@section('scripts')  
    <script src="{{ asset('frontend/js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('frontend/js/datepicker-de.js') }}"></script>
    <script src="{{ asset('frontend/js/select2.min.js') }}"></script>
    <script src="{{ asset('frontend/js/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('frontend/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('frontend/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('frontend/js/custom/coach_appointments.js') }}"></script>
    <script src="{{ asset('frontend/js/custom/display_availabilities.js') }}"></script>
    <script>
    	$(document).ready(function() {
    		$('.multi-select').select2();
    		$('b[role="presentation"]').hide();
    		$('.select2-selection__arrow').append('<i class="fa fa-angle-down"></i>');

            $(document).on('click', '.alternative_suggest_btn', function(event) {
                event.preventDefault();
                var id = $(this).data('id');
                $("#coach_booking_modal").find('.request_id').val(id);
                $("#coach_booking_modal").modal('show');
            });

            // Initialize Datatables
            // Upcoming
            CoachAppointmentModule.initDataTable('booking_event_data_table', 'future');
            // Past
            CoachAppointmentModule.initDataTable('past_event_data_table', 'past');

            // Initialize availability calendar
            var allowed_min_dur = {{ env('SLOT_MIN_DURATION', 30) }};
            var allowed_max_dur = {{ env('SLOT_MAX_DURATION', 120) }};
            var coach_id        = '{{ Auth::guard('coach')->id() }}';
            AvailCalendarModule.initData(coach_id, allowed_min_dur, allowed_max_dur, true);
            AvailCalendarModule.initUnAvailCalendar("unavailDatepicker");
            AvailCalendarModule.initSlotStatus('unavailability');
    	});
    </script>
@endsection
