@extends('layouts.app')

@section('style_link')
    <link rel="stylesheet" href="{{ asset('frontend/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/responsive.bootstrap4.min.css') }}">
@endsection

@section('content')
	<!-- coach tab data section start -->
	<div class="container-fluid ch_overview_container">
		<div class="container">
			<div class="row">
				<h5 class="col-12 ch_tabdata_head">Deine Blogeinträge</h5>
				<div class="col-md-12 mb-3 mb-md-5 coach_data_tbs nxt_meet_data_tbs">
					<table id="overview_data_table" class="table dt-responsive nowrap blue overview_dt_table" style="width:100%">
						<thead>
							<tr>
								<th>Titel</th>
								<th>Datum</th>
								<th>Ansichten</th>
								<th>Startzeit</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>Persönlichkeit entwicklen</td>
								<td class="date_view"><i class="fa fa-clock-o" aria-hidden="true"></i> 08.06.2019</td>
								<td class="date_view"><i class="fa fa-eye" aria-hidden="true"></i> 172 Views</td>
								<td>
									<select class="form-control">
										<option>Auswählen</option>
										<option>Bearbeiten</option>
										<option>Löschen</option>
									</select>
								</td>
							</tr>
							<tr>
								<td>Persönlichkeit entwicklen</td>
								<td class="date_view"><i class="fa fa-clock-o" aria-hidden="true"></i> 08.06.2019</td>
								<td class="date_view"><i class="fa fa-eye" aria-hidden="true"></i> 172 Views</td>
								<td>
									<select class="form-control">
										<option>Auswählen</option>
										<option>Bearbeiten</option>
										<option>Löschen</option>
									</select>
								</td>
							</tr>
							<tr>
								<td>Persönlichkeit entwicklen</td>
								<td class="date_view"><i class="fa fa-clock-o" aria-hidden="true"></i> 08.06.2019</td>
								<td class="date_view"><i class="fa fa-eye" aria-hidden="true"></i> 172 Views</td>
								<td>
									<select class="form-control">
										<option>Auswählen</option>
										<option>Bearbeiten</option>
										<option>Löschen</option>
									</select>
								</td>
							</tr>
							<tr>
								<td>Persönlichkeit entwicklen</td>
								<td class="date_view"><i class="fa fa-clock-o" aria-hidden="true"></i> 08.06.2019</td>
								<td class="date_view"><i class="fa fa-eye" aria-hidden="true"></i> 172 Views</td>
								<td>
									<select class="form-control">
										<option>Auswählen</option>
										<option>Bearbeiten</option>
										<option>Löschen</option>
									</select>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="col-md-12 mb-4 ch_blog_btn">
					<button type="button" class="btn orange_background_btn">Neuer Blogeinträg</button>
				</div>
			</div>
		</div>
	</div>
	<!-- coach tab data section end -->
@endsection

@section('scripts')
    
    <script src="{{ asset('frontend/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('frontend/js/dataTables.responsive.min.js') }}"></script>  
      <script>
    	$(document).ready(function() {
    		$('#overview_data_table').DataTable();
    	} );
      </script>
@endsection
