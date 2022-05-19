<!doctype html>
<html class="no-js" lang="de">

<head>
    <meta charset="utf-8">
    <title>{{ config('app.name', '') }}{{isset($page_title)?' :: '.$page_title:''}}</title>
    <meta name="description" content="">
    <meta name="viewport" content= "width=device-width, user-scalable=no">
    <meta name="theme-color" content="#fafafa">

    <!-- ================== CSRF TOKEN ====================== -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- ================== BEGIN BASE CSS STYLE ================== -->
    <link rel="stylesheet" href="{{ asset('frontend/css/normalize.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/font-awesome.min.css') }}">   
    <link rel="stylesheet" href="{{ asset('frontend/css/bootstrap.min.css') }}">
    <!-- ================== END BASE CSS STYLE ================== -->
    <link rel="stylesheet" href="{{ asset('frontend/css/main.css') }}">

    <!-- ================== BEGIN TOASTR NOTIFICATION STYLE ================== -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.css" integrity="sha256-R91pD48xW+oHbpJYGn5xR0Q7tMhH4xOrWn1QqMRINtA=" crossorigin="anonymous" />
    <!-- ================== END TOASTR NOTIFICATION STYLE ================== -->

    <!-- ================== BEGIN PAGE LEVEL STYLE ================== -->
    <link rel="stylesheet" href="{{ asset('frontend/css/videocall.css') }}">
    <!-- ================== END PAGE LEVEL STYLE ================== -->
</head>

<body>
    @csrf
    <img class="video_logo" src="{{url('logo_white_shadow.png')}}" alt="x">
    <div class="alert alert-info video_alert">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        {{-- <strong>Facing error?</strong> Try refreshing and wait for few seconds for other side to get connected. --}}
        Die Verbindung wird automatisch aufgebaut, sobald dein Gesprächspartner online ist.
    </div>
    <div class="video-wrapper">
        <div class="onlinevideo-controls">
            <div class="checkbox hascheckbox incallelement" style="display:none;">
                <input type="checkbox" id="select-mic" value="1">
                <label for="select-mic"><i class="fa fa-microphone" aria-hidden="true"></i></label>
            </div>
            <div class="incallelement" id='hangup' value="Hangup" style="display: none;">
                <i class="fa fa-phone" aria-hidden="true"></i>
            </div>
            <div class="checkbox hascheckbox incallelement" style="display:none;">
                <input type="checkbox" id="select-camera" value="1">
                <label for="select-camera"><img class="video_icon" src="{{ asset('frontend/img/video_deactive.png') }}"></label>
            </div>
            <div class="incallelement" id="select-screenshare" style="display:none;">
                <i class="fa fa-desktop fa-3x" aria-hidden="true" style="color:red;"></i>
            </div>
        </div>

         <div class="overflowhidden">
            <div class="row" id="remote-container" style="width:100%;">
                <div class="onlyaudio " style="display: none;">
                    <p>Video unavailable</p>
                </div>
            </div>
             <div class="" id="local-container">
                <div class="onlyaudio novideolocal" style="display: none;">
                    <p>Video unavailable</p>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="disconnect-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Gespräch beenden</h4>
                </div>
                <div class="modal-body">
                    Die Verbindung wurde unterbrochen. Verbinde dich erneut und warte bis sich dein Gesprächspartner einwählt oder beende das Gespräch.
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-default greenbtn" id="reconnect_btn" {{-- data-dismiss="modal" --}}>Zum Gespräch</a>
                    <a href="#" class="btn btn-primary redbtn" id="summary_btn">Gespräch beenden</a>
                </div>
            </div>
        </div>
    </div>

    <!-- ================== BEGIN BASE JS ================== -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script>window.jQuery || document.write('<script src="{{ asset('frontend/js/vendor/jquery-3.3.1.min.js') }}"><\/script>')</script>
    <script src="{{ asset('frontend/js/vendor/modernizr-3.7.1.min.js') }}"></script>
    <script src="{{ asset('frontend/js/bootstrap.min.js') }}"></script>
    <script>
        var baseUrl = '{{ url("/") }}';
        var publicUrl = '{{ asset("") }}';
        var uploadUrl = '{{ url("/public/uploads") }}';
    </script>
    <!-- ================== END BASE JS ================== -->
    <!-- ================== BEGIN TOASTR NOTIFICATION JS ================== -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.js" integrity="sha256-yNbKY1y6h2rbVcQtf0b8lq4a+xpktyFc3pSYoGAY1qQ=" crossorigin="anonymous"></script>
    <!-- ================== END TOASTR NOTIFICATION JS ================== -->

    <!-- ================== BEGIN API RTC ======================================= -->
    {{-- <script type="text/javascript" src="https://cloud.apizee.com/apiRTC/v4.3/apiRTC-4.3.3.min.js"></script> --}}
    <script type="text/javascript" src="https://cloud.apizee.com/apiRTC/apiRTC-latest.min.js"></script>
    <!-- ================== END API RTC ======================================= -->

    <!-- ================== CUSTOM VIDEO JS SCRIPT ============================ -->
    <script>
        var appointmentId         = '{{$appointment_id}}';
        var receiverId            = '{{$client_id}}';
        var userId                = '{{$user_id}}';
    </script>
    <script src="{{ asset('frontend/js/custom/videocall.js') }}"></script>
    <!-- ================== END CUSTOM VIDEO JS SCRIPT ======================== -->

</body>
</html>