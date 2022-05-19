<div id="sidebar" class="sidebar">
	<!-- begin sidebar scrollbar -->
	<div data-scrollbar="true" data-height="100%">
		<!-- begin sidebar user -->
		<ul class="nav">
			<li class="nav-profile">
				<a href="javascript:;" data-toggle="nav-profile">
					<div class="cover with-shadow"></div>
					<div class="image">
						<img src="{{ FileUploadHelper::getDocPath($user->avatar, 'avatar') }}" alt="" />
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
					<li><a href="{{url('/admin/users/'.$user->id.'/edit')}}"><i class="fa fa-edit"></i> Edit Profile</a></li>
					{{-- <li><a href="javascript:;"><i class="fa fa-cog"></i> Settings</a></li> --}}
					<li><a href="{{url('/logout')}}"><i class="fa fa-power-off"></i> Log Out</a></li>
				</ul>
			</li>
		</ul>
		<!-- end sidebar user -->
		<!-- begin sidebar nav -->
		<ul class="nav">
			<li class="nav-header">Navigation</li>
			<li class="{{ (Request::is('admin/dashboard') || Request::is('admin')) ? 'active' : '' }}">
				<a href="{{url('/admin')}}">
					<i class="fa fa-th-large"></i> 
					<span>Dashboard</span> 
				</a>
			</li>
			<li class="{{ Request::is('admin/users*') ? 'active' : '' }}">
				<a href="{{url('/admin/users')}}">
					<i class="fa fa-users"></i> 
					<span>Users</span> 
				</a>
			</li>
			<li class="{{ Request::is('admin/guests*') ? 'active' : '' }}">
				<a href="{{url('/admin/guests')}}">
					<i class="fa fa-users"></i> 
					<span>Guests</span> 
				</a>
			</li>
			<li class="{{ Request::is('admin/coaches*') ? 'active' : '' }}">
				<a href="{{url('/admin/coaches')}}">
					<i class="fa fa-university"></i> 
					<span>Coaches</span> 
				</a>
			</li>
			<li class="{{ Request::is('admin/reviews*') ? 'active' : '' }}">
				<a href="{{url('/admin/reviews')}}">
					<i class="fa fa-star"></i> 
					<span>Reviews</span> 
				</a>
			</li>
			{{-- <li class="{{ Request::is('admin/scheduler*') ? 'active' : '' }}">
				<a href="{{url('/admin/scheduler')}}">
					<i class="fa fa-calendar-alt"></i> 
					<span>Scheduler</span> 
				</a>
			</li> --}}
			<li class="{{ Request::is('admin/appointment*') ? 'active' : '' }}">
				<a href="{{url('/admin/appointment')}}">
					<i class="fa fa-book"></i> 
					<span>Appointments</span> 
				</a>
			</li>
			<li class="{{ Request::is('admin/categories*') ? 'active' : '' }}">
				<a href="{{url('/admin/categories')}}">
					<i class="fa fa-th-list"></i> 
					<span>Categories</span> 
				</a>
			</li>
			<li class="{{ Request::is('admin/companies*') ? 'active' : '' }}">
				<a href="{{url('/admin/companies')}}">
					<i class="fa fa-city"></i> 
					<span>Company</span> 
				</a>
			</li>
			<li class="{{ Request::is('admin/countries*') ? 'active' : '' }}">
				<a href="{{url('/admin/countries')}}">
					<i class="fa fa-globe"></i> 
					<span>Countries</span> 
				</a>
			</li>
			<li class="{{ Request::is('admin/faqs*') ? 'active' : '' }}">
				<a href="{{url('/admin/faqs')}}">
					<i class="fa fa-question"></i> 
					<span>FAQs</span> 
				</a>
			</li>
			<li class="{{ Request::is('admin/coach-says*') ? 'active' : '' }}">
				<a href="{{url('/admin/coach-says')}}">
					<i class="fa fa-comment"></i> 
					<span>Coach Says</span> 
				</a>
			</li>
			<li class="{{ Request::is('admin/messages*') ? 'active' : '' }}">
				<a href="{{url('/admin/messages')}}">
					<i class="fab fa-facebook-messenger"></i> 
					<span>Messages</span> 
				</a>
			</li>
			{{-- 
				<li class="has-sub {{ Request::is('admin/helper*') ? 'active' : '' }}">
					<a href="javascript:;">
						<b class="caret"></b>
						<i class="fa fa-medkit"></i>
						<span>Helper</span>
					</a>
					<ul class="sub-menu {{ Request::is('admin/helper/css') ? 'active' : '' }}">
						<li><a href="helper_css.html">Predefined CSS Classes</a></li>
					</ul>
				</li>
				<li class="has-sub">
					<a href="javascript:;">
						<b class="caret"></b>
						<i class="fa fa-align-left"></i> 
						<span>Menu Level</span>
					</a>
					<ul class="sub-menu">
						<li class="has-sub">
							<a href="javascript:;">
								<b class="caret"></b>
								Menu 1.1
							</a>
							<ul class="sub-menu">
								<li class="has-sub">
									<a href="javascript:;">
										<b class="caret"></b>
										Menu 2.1
									</a>
									<ul class="sub-menu">
										<li><a href="javascript:;">Menu 3.1</a></li>
										<li><a href="javascript:;">Menu 3.2</a></li>
									</ul>
								</li>
								<li><a href="javascript:;">Menu 2.2</a></li>
								<li><a href="javascript:;">Menu 2.3</a></li>
							</ul>
						</li>
						<li><a href="javascript:;">Menu 1.2</a></li>
						<li><a href="javascript:;">Menu 1.3</a></li>
					</ul>
				</li> 
			--}}
			<!-- begin sidebar minify button -->
			<li><a href="javascript:;" class="sidebar-minify-btn" data-click="sidebar-minify"><i class="fa fa-angle-double-left"></i></a></li>
			<!-- end sidebar minify button -->
		</ul>
		<!-- end sidebar nav -->
	</div>
	<!-- end sidebar scrollbar -->
</div>
<div class="sidebar-bg"></div>