@extends('layouts.main')

@section('styles')
@endsection

@section('content')
 	{{ csrf_field() }}
 	<div class="row">
		<div class="col-md-6">
			<a class="btn btn-large btn-info width-100 m-5" href="{{url('admin/coaches/create?type=coach')}}"><span class="fa fa-plus-circle"></span> Add New</a>
		</div>
		<div class="col-md-6 text-right">
			<div class="dropdown">
				<a href="#" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
					<i class="fa fa-filter"></i> Filters
				</a>
				<ul class="dropdown-menu" role="menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 34px, 0px);" id="users_filter">
					<li class="active" data-filter="all"><a href="javascript:;">All</a></li>
					<li data-filter="active"><a href="javascript:;">Active</a></li>
					<li data-filter="inactive"><a href="javascript:;">Inactive</a></li>
					<li data-filter="incomplete"><a href="javascript:;">Incomplete</a></li>
					<li data-filter="kyc pending"><a href="javascript:;">KYC pending</a></li>
					<li data-filter="ubo pending"><a href="javascript:;">UBO pending</a></li>
					<li data-filter="approval"><a href="javascript:;">Pending Approval</a></li>
				</ul>
			</div>
		</div>
	</div>
	<table id="users_table" class="table table-striped table-bordered">
		<thead>
			<tr>
				<th width="1%" data-orderable="false"></th>
				<th class="text-nowrap">First Name</th>
				<th class="text-nowrap">Last Name</th>
				<th class="text-nowrap">Email</th>
				<th class="text-nowrap">status</th>
				<th class="text-nowrap">KYC Status</th>
				<th class="text-nowrap">UBO Status</th>
				<th class="text-nowrap">Action</th>
			</tr>
		</thead>
		<tbody>
		</tbody>
	</table>

	@include('admin.users._user_info')
@endsection

@section('scripts')
	<!-- ==================   PARTIAL INFO JS   ================== -->
	<script src="{{ asset('backend/js/user_info.js')}}"></script>
    <!-- ================== PARTIAL INFO JS END ================== -->
    <script>
    	var datataTableUrl = baseUrl+'/users/datatables/coach';
    </script>
    <script src="{{ asset('backend/js/admin/user.js')}}"></script>
@endsection