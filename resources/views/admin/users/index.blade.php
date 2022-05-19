@extends('layouts.main')

@section('styles')
@endsection

@section('content')
 	{{ csrf_field() }}
 	@if($type!='guest')
	 	<div class="row">
			<div class="col-md-6">
				<a class="btn btn-large btn-info width-100 m-5" href="{{url('admin/users/create?type=user')}}"><span class="fa fa-plus-circle"></span> Add New</a>
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
					</ul>
				</div>
			</div>
		</div>
	@endif
	<table id="users_table" class="table table-striped table-bordered">
		<thead>
			<tr>
				<th width="1%" data-orderable="false"></th>
				<th class="text-nowrap">First Name</th>
				<th class="text-nowrap">Last Name</th>
				<th class="text-nowrap">Email</th>
				<th class="text-nowrap">status</th>
				<th class="text-nowrap">Action</th>
			</tr>
		</thead>
		<tbody>
		</tbody>
	</table>

	<!-- USER DETAILS MODAL START -->
	@include('admin.users._user_info')
	<!-- USER DETAILS MODAL END -->
@endsection

@section('scripts')
	<!-- ==================   PARTIAL INFO JS   ================== -->
	<script src="{{ asset('backend/js/user_info.js')}}"></script>
    <!-- ================== PARTIAL INFO JS END ================== -->
    <script>
    	var type 	 	   = '{{$type}}';
    	var datataTableUrl = baseUrl+'/users/datatables/'+type;
    </script>
    <script src="{{ asset('backend/js/admin/user.js')}}"></script>
@endsection