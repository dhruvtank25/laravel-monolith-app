<div id="header" class="header navbar-default">
  <!-- begin navbar-header -->
  <div class="navbar-header">
    <a href="{{ route('home') }}" class="navbar-brand">
      <img src="{{ asset('logo.png') }}" alt="x"/> 
      {{-- <b>{{ config('app.name', '') }}</b> --}}
    </a>
    <button type="button" class="navbar-toggle" data-click="sidebar-toggled">
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </button>
  </div>
  <!-- end navbar-header -->
  <!-- begin header-nav -->
  <ul class="navbar-nav navbar-right">
    <!-- 
      <li>
        <form class="navbar-form">
          <div class="form-group">
            <input type="text" class="form-control" placeholder="Enter keyword" />
            <button type="submit" class="btn btn-search"><i class="fa fa-search"></i></button>
          </div>
        </form>
      </li> 
    -->
    
    {{-- <li class="dropdown">
      <a href="#" data-toggle="dropdown" class="dropdown-toggle f-s-14">
        <i class="fa fa-bell"></i>
        <span class="label unread-message-count">0</span>
      </a>
      <ul class="dropdown-menu media-list dropdown-menu-right notification_contents">
        <li class="dropdown-header">NOTIFICATIONS (<span class="unread-message-count">0</span>)</li>
        <li class="dropdown-footer text-center">
          <a href="{{url($guard.'/messages')}}">View more</a>
        </li>
      </ul>
    </li> --}}
    
    <li class="dropdown navbar-user">
      <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <img src="{{ FileUploadHelper::getDocPath($user->avatar, 'avatar') }}" alt="" /> 
        <span class="d-none d-md-inline">
            {{$user->first_name.' '.$user->last_name}}
        </span> <b class="caret"></b>
      </a>
      <div class="dropdown-menu dropdown-menu-right">
        <a href="{{url($guard.'/users/'.$user->id.'/edit')}}" class="dropdown-item">Edit Profile</a>
        <a href="{{url($guard.'/messages')}}" class="dropdown-item"><span class="badge badge-danger pull-right unread-message-count">0</span> Messages</a>
        {{-- <a href="javascript:;" class="dropdown-item">Setting</a> --}}
        <div class="dropdown-divider"></div>
        <a href="{{url('/logout/')}}" class="dropdown-item">Log Out</a>
      </div>
    </li>
  </ul>
  <!-- end header navigation right -->
</div>