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
            <input type="hidden" id="appointment_id">
            <input type="hidden" id="coach_id">
            <input type="hidden" id="duration">
            <h5 class="modal-title mb-3" id="exampleModalLongTitle">Termin verschieben</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
          </div>
          <div class="modal-body coach_msg_body">
            <form action="get">
                <div class="row">
                    <p class="col-md-12">Termine können nur einmalig und bis maximal 48 Stunden vor Beratungsbeginn verschoben werden.</p>
                    <div class="col-md-12 mb-5 mt-4 period_date_wrap">
                        <div class="datepicker" id="availabilityDatepicker"></div>
                    </div>
                    <div class="col-md-6 book_pop_avail mb-4">
                        <h6 class="mb-2">Ausgewählter Termin</h6>
                        <p class="blk_rnd_select active" id="availability_select_date">10. Mai 2019</p>
                    </div>
                    <div class="col-md-6 book_pop_avail mb-4">
                        <h6 class="mb-2">Startzeit auswählen</h6>
                        <div class="mb-3 mb-md-0 reg_single_select book_pop_select">
                            <select class="multi-select" name="languages" id="available_times">
                                <option>08.00 Uhr</option>
                                <option>08.30 Uhr</option>
                                <option>09.00 Uhr</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12 book_pop_avail mb-4" id="coaching_method">
                        <h6 class="mb-2">Beratungsort</h6>
                        <p class="blk_rnd_select mode_selector mr-1">Online</p>
                        <p class="blk_rnd_select mode_selector active mr-1">Offline</p>
                    </div>
                    <h5 class="col-md-12 ch_tabdata_head mb-3">Nachricht hinzufügen (optional)</h5>
                    <div class="col-md-12 mb-3">
                        <textarea class="form-control" id="update_comment" rows="5" placeholder="Das ist mein Thema..."></textarea>
                    </div>
                </div>
            </form>
            <button type="button" class="btn orange_background_btn mt-3 mb-3" id="updateAppointmentBtn">Beratungstermin ändern</button>
            <!-- <div class="select_coach_avail">
                <p class="col-md-12 mb-3 pl-0 pr-0">Kein passender Termin verfügbar?</p>
            </div>
            <button type="button" class="btn blk_bor_btn mb-5">Termin anfragen</button> -->
          </div>
          <!-- <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary">Save changes</button>
          </div> -->
        </div>
      </div>
    </div>
    <!-- Modal end-->

	<div class="coach_dash_wrapper">
		<!-- booking section start -->
		<div class="container-fluid ch_overview_container">
			<div class="container">
				<div class="row">
                    <p class="section_head booking_hd me_profil">
                        Buchungen
                    </p>
                </div>
                <hr>
                <div class="row">
					<h5 class="col-12 ch_tabdata_head">Nächste Termine</h5>
                    <p class="col-md-12 mb-3">Aktualisiere diese Seite sobald dein Termin beginnt – dann wird dein Startbutton aktiviert.</p>
					<div class="col-md-12 mb-5 coach_data_tbs">
						<table id="scheduled_appointment_table" class="table dt-responsive nowrap blue overview_dt_table" style="width:100%">
							<thead>
								<tr>
									<th>Datum</th>
									<th>Berater</th>
									<th>Kategorie</th>
									<th>Startzeit</th>
									<th>Dauer</th>
									<th>Ort</th>
                                    <th>Aktion</th>
								</tr>
							</thead>
							<tbody>
                                @foreach ($appointments as $appointment)
                                    @php
                                        $duration = $appointment->start->diffInMinutes($appointment->end);
                                    @endphp
                                    <tr>
                                        <td>{{$appointment->start->format('d.m.Y')}}</td>
                                        <td>{{$appointment->coach->first_name.' '.$appointment->coach->last_name}}</td>
                                        <td>{{$appointment->categories->title}}</td>
                                        <td>{{$appointment->start->format('H:i A')}}</td>
                                        <td>{{$duration}} Minuten</td>
                                        <td>{{ucwords($appointment->mode)}}</td>
                                        <td>
                                            @php
                                                $app_start = $appointment->start->format('Y-m-d H:i:s');
                                                $app_end   = $appointment->end->format('Y-m-d H:i:s');
                                                $app_now = Carbon\Carbon::now('Europe/Berlin');
                                                $call_active = false;
                                                if($app_now->isBetween($app_start, $app_end))
                                                    $call_active = true;
                                            @endphp
                                            @if($appointment->mode=='online')
                                                <button type="button" class="btn btn-primary actionStart" data-id="{{$appointment->id}}" {{$call_active?'':'disabled'}}>Starten</button>
                                            @endif
                                            @if($appointment->user_updated_on==null && Carbon\Carbon::now('Europe/Berlin')->diffInHours($appointment->start)>48)
                                                <button type="button" class="btn btn-info actionMove" data-id="{{$appointment->id}}" data-userid="{{$appointment->user_id}}" data-coachid="{{$appointment->coach_id}}" data-coaching-method="{{$appointment->coach->coaching_method}}" data-duration="{{$duration}}" data-mode="{{$appointment->mode}}">Verschieben</button>
                                            @endif
                                            @if(!$call_active)
                                                <button type="button" class="btn btn-danger actionCancel" data-id="{{$appointment->id}}">Stornieren</button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<!-- booking section end -->
	</div>
@endsection

@section('scripts')
    <script src="{{ asset('frontend/js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('frontend/js/datepicker-de.js') }}"></script>
    <script src="{{ asset('frontend/js/select2.min.js') }}"></script>
	<script src="{{ asset('frontend/js/jquery.dataTables.js') }}"></script>
	<script src="{{ asset('frontend/js/dataTables.bootstrap4.min.js') }}"></script>
	<script src="{{ asset('frontend/js/dataTables.responsive.min.js') }}"></script>
    <script>
    	$(document).ready(function() {
    		$('#scheduled_appointment_table').DataTable({
                "language": {
                    "url": publicUrl+"/frontend/js/dataTables.german.json"
                },
            });
    		/*$( ".datepicker" ).datepicker({
    		  numberOfMonths: 2,
    		  showButtonPanel: false
    		});*/
    		$('.multi-select').select2();
    		$('b[role="presentation"]').hide();
    		$('.select2-selection__arrow').append('<i class="fa fa-angle-down"></i>');
    	});
    </script>
    <script src="{{ asset('frontend/js/custom/booking.js')}}"></script>
@endsection