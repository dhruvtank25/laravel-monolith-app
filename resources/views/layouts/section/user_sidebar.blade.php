<div id="sidebar" class="sidebar">
	<!-- begin sidebar scrollbar -->
	<div data-scrollbar="true" data-height="100%">
		<!-- begin sidebar user -->
		<ul class="nav">
			<li class="nav-profile">
				<a href="javascript:;" data-toggle="nav-profile">
					<div class="cover with-shadow"></div>
					<div class="image">
						<img src="{{ url('avatar/small/'.$user->avatar) }}" alt="" />
					</div>
					<div class="info">
						<b class="caret pull-right"></b>
						{{$user->first_name.' '.$user->last_name}}
						<small>{{$user->roles->name}}</small>
					</div>
				</a>
			</li>
			<li>
				<ul class="nav nav-profile">
					<li><a href="{{url('/user/profile/update')}}"><i class="fa fa-edit"></i> Edit Profile</a></li>
					<li><a href="javascript:;"><i class="fa fa-cog"></i> Settings</a></li>
					<li><a href="{{url('/logout')}}"><i class="fa fa-power-off"></i> Log Out</a></li>
				</ul>
			</li>
		</ul>
		<!-- end sidebar user -->
		<!-- begin sidebar nav -->
		<ul class="nav">
			<li class="nav-header">Navigation</li>
			<li class="{{ (Request::is('user/dashboard') || Request::is('user')) ? 'active' : '' }}">
				<a href="{{url('/user')}}">
					<i class="fa fa-th-large"></i> 
					<span>Dashboard</span> 
				</a>
			</li>
			<li class="{{ Request::is('user/profile*') ? 'active' : '' }}">
				<a href="{{url('/user/profile')}}">
					<i class="fa fa-user"></i> 
					<span>User profile</span> 
				</a>
			</li>
			<!-- begin sidebar minify button -->
			<li><a href="javascript:;" class="sidebar-minify-btn" data-click="sidebar-minify"><i class="fa fa-angle-double-left"></i></a></li>
			<!-- end sidebar minify button -->
		</ul>
		<!-- end sidebar nav -->
	</div>
	<!-- end sidebar scrollbar -->
</div>
<div class="sidebar-bg"></div>