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
    <style>
        .video-wrapper{
        position:relative;
        }
        #remote-container{
        position:relative;
        width:100%;
        height:100vh;
        background: #000000;
        }
        .overflowhidden{
        overflow: hidden;
        }
        .video-wrapper #remote-container video{
        position: absolute;
        top: -1px;
        left: -1px;
        width: calc(100% - 2px) !important;
        height: calc(100% - 2px) !important;
        /*  
        transform: translate(-50%, -10%);
        min-width: 100%;
        min-height: 100%;*/
        }
        #local-container{
        position: absolute;
        right: 5px;
        text-align: right;
        top: 5px;
        }
        #local-container video{
        width:100%;
        }

        .onlinevideo-controls{
        position: absolute;
        bottom: 10px;
        margin: 0 auto;
        left: 0;
        z-index: 99;
        right: 0;
        text-align: center;
        }

        .onlinevideo-controls div{
        display:inline-block;
        margin-right:5px;
        vertical-align: top;
        }

        #hangup{
        border-radius: 50%;
        width: 50px;
        height: 50px;
        background: red;
        border: 0;
        color: #fff;
        padding:6px 15px;
        font-size:25px;
        cursor:pointer;
        }
        .hascheckbox input{
        display:none;
        }
        
        .hascheckbox label[for="select-mic"]{
        border-radius: 50%;
        width: 50px;
        height: 50px;
        background: rgba(255,255,255,0.7);
        border: 0;
        color: #000000;
        padding:6px 15px;
        font-size:25px;
        cursor:pointer;
        }

        .hascheckbox label[for="select-camera"]{
        border-radius: 50%;
        width: 50px;
        height: 50px;
        background: #00bf00;
        border: 0;
        color: #ffffff;
        padding:8px 15px;
        font-size:22px;
        cursor:pointer;
        }
        .onlyaudio{
        background:#222222;
        color:#ffffff;
        font-size:30px;
        font-weight:bold;
        height:100vh;
        padding-top:20%;
        text-align:center;
        }

        .novideolocal{
        height:150px;
        }

        .video_icon{
        width: 28px;
        display: block;
        margin-left: -3px;
        margin-top: 3px;
        }

        .video_alert{
        position: absolute;
        z-index: 9999;
        top:0;
        width:100%;
        left:0;
        }

        .video_logo{
        position:absolute;
        z-index: 9999;
        top:20px;
        left:20px;
        width:200px;
        }

        @media(max-width:560px) {
            .onlinevideo-controls{
                top: 75% !important;
            }
            #local-container {
                position: absolute !important;
                text-align: right;
                top: 5% !important;
                right: -0px !important;
            }
            /* #local-container #local-media{
                width: 90px  !important;
                height: 120px  !important;
                border: 2px solid #fff
            } */
        }

        @media(max-width:500px) {
          /*  .video-wrapper #remote-container video {
                height: 40vh !important;
            }*/

             .video-wrapper #remote-container video{
            min-width: 100%;
            min-height: 100%;
            margin-top: -50px;
            }

            #local-container{
            position:relative;
            }
            .onlinevideo-controls{
            top:25%;
            bottom:auto;
            }
          
        }
    </style>
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
        <input type="button" id='makeCall' value="Call" onclick="makeCall()" style="display:none;" disabled>
        <div class="onlinevideo-controls">
            <div class="checkbox hascheckbox incallelement" style="display:none;">
                <input type="checkbox" id="select-mic" value="1">
                <label for="select-mic"><i class="fa fa-microphone" aria-hidden="true"></i></label>
            </div>
            <div id='hangup' value="Hangup" style="display: none;">
                <i class="fa fa-phone" aria-hidden="true"></i>
            </div>
             <div class="checkbox hascheckbox incallelement" style="display:none;">
                <input type="checkbox" id="select-camera" value="1">
                <label for="select-camera"><img class="video_icon" src="{{ asset('frontend/img/video_deactive.png') }}"></label>
            </div>
        </div>

         <div class="overflowhidden">
            <div class="" id="remote-container" style="width:100%;">
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
    {{-- <script src="{{ asset('frontend/js/plugins.js') }}"></script> --}}
    <script src="{{ asset('frontend/js/jquery.rateyo.min.js') }}"></script>
    <script src="{{ asset('frontend/js/bootstrap.min.js') }}"></script>
    {{-- <script src="{{ asset('js/app.js') }}"></script> --}}
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

    <script>
        var appointmentId = '{{$appointment_id}}';
        var receiverId = '{{$client_id}}';
        var connectedSession = null;
        var ua           = null;
        var localStream  = null;
        var call         = null;
        var selectCamera = document.getElementById("select-camera");
        var selectMic    = document.getElementById("select-mic");
        var has_ended    = false;

        $(document).on('change', '#select-mic', function(event) {
            toggleMicStatus();
        });

        $(document).on('change', '#select-camera', function(event) {
            toggleCameraStatus();
        });

        function toggleMicStatus () {
            if(localStream.isAudioMuted()) {
                localStream.unmuteAudio();
                $("label[for='select-mic']").css({'background':'#00bf00','color':'#ffffff'}).html('<i class="fa fa-microphone" aria-hidden="true"></i>');
            } else {
                localStream.muteAudio();
                $("label[for='select-mic']").css({'background':'red','color':'#ffffff'}).html('<i class="fa fa-microphone-slash" aria-hidden="true"></i>'); 
            }
        }

        function toggleCameraStatus () {
            if(localStream.isVideoMuted()) {
                localStream.unmuteVideo();
                $('label[for="select-camera"]').css({'background':'#00bf00','color':'#ffffff'}).html('<img class="video_icon" src="https://himmlischberaten.de/frontend/img/video-active.png">');
            } else {
                localStream.muteVideo();
                $('label[for="select-camera"]').css({'background':'red','color':'#ffffff'}).html('<img class="video_icon" src="https://himmlischberaten.de/frontend/img/video_deactive.png">');
            }
        }

        //Function to add media stream in Div
        function addStreamInDiv(stream, divId, mediaEltId, style, muted) {

            var streamIsVideo = stream.hasVideo();
            console.error('addStreamInDiv - hasVideo? ' + streamIsVideo);

            var mediaElt = null,
                divElement = null,
                funcFixIoS = null,
                promise = null;

            if (streamIsVideo === false) {
                mediaElt = document.createElement("audio");
                mediaElt.style.display = "none";
                $("#"+divId+" .onlyaudio").show();
            } else {
                mediaElt = document.createElement("video");
                $("#"+divId+" .onlyaudio").hide();
            }

            mediaElt.id = mediaEltId;
            mediaElt.autoplay = true;
            mediaElt.muted = muted;
            mediaElt.style.width = style.width;
            mediaElt.style.height = style.height;

            funcFixIoS = function () {
                var promise = mediaElt.play();

                console.log('funcFixIoS');
                if (promise !== undefined) {
                    promise.then(function () {
                        // Autoplay started!
                        console.log('Autoplay started');
                        console.error('Audio is now activated');
                        document.removeEventListener('touchstart', funcFixIoS);

                        $('#status').empty().append('iOS / Safari : Audio is now activated');

                    }).catch(function (error) {
                        // Autoplay was prevented.
                        console.error('Autoplay was prevented');
                    });
                }
            };

            stream.attachToElement(mediaElt);

            divElement = document.getElementById(divId);
            divElement.appendChild(mediaElt);
            promise = mediaElt.play();

            if(divId=='remote-container') {
                // Display hangup button
                showCallScreen();
            } else {
                toggleMicStatus();
                toggleCameraStatus();
            }

            if (promise !== undefined) {
                promise.then(function () {
                    // Autoplay started!
                    console.log('Autoplay started');
                }).catch(function (error) {
                    // Autoplay was prevented.
                    if (apiRTC.osName === "iOS") {
                        console.info('iOS : Autoplay was prevented, activating touch event to start media play');
                        //Show a UI element to let the user manually start playback

                        //In our sample, we display a modal to inform user and use touchstart event to launch "play()"
                        document.addEventListener('touchstart',  funcFixIoS);
                        console.error('WARNING : Audio autoplay was prevented by iOS, touch screen to activate audio');
                        $('#status').empty().append('WARNING : iOS / Safari : Audio autoplay was prevented by iOS, touch screen to activate audio');
                    } else {
                        console.error('Autoplay was prevented');
                    }
                });
            }
        }

        function setCallListeners () {
            call.on("localStreamAvailable", function (stream) {
                        console.log('localStreamAvailable');
                        localStream = stream;
                        //document.getElementById('local-media').remove();
                        addStreamInDiv(stream, 'local-container', 'local-media', {width : "160px", height : "120px"}, true);
                        stream.on("stopped", function () { //When client receives an screenSharing call from another user
                            console.error("Stream stopped");
                            $('#local-media').remove();
                        });
                    })
                    .on("streamAdded", function (stream) {
                        console.log('stream :', stream);
                        addStreamInDiv(stream, 'remote-container', 'remote-media', {width : "640px", height : "480px"}, false);
                        $("#disconnect-modal").modal('hide');
                    })
                    .on('streamRemoved', function (stream) {
                        // Remove media element
                        document.getElementById('remote-media').remove();
                    })
                    .on('userMediaError', function (e) {
                        console.log('userMediaError detected : ', e);
                        console.log('userMediaError detected with error : ', e.error);

                        //Checking if tryAudioCallActivated
                        if (e.tryAudioCallActivated === false) {
                            $('#hangup').hide();
                        }
                    })
                    .on('hangup', function () {
                        $('#hangup').hide();
                        console.log(call);
                        console.log(call.getId());
                        // End call logging
                        endCallLog();
                        hideCallScreen();
                    });
        }

        function makeCall () {
            console.log('making a call to id '+receiverId);
            if (contact !== null) {
                // CALL CONTACT
                // call = contact.call(localStream, {audioOnly: true}); // For audio only call
                //showCallScreen();
                call = contact.call()
                    .on('accepted', function () {
                        console.log('New call Id:', call.getId());
                        initiateCallLog();
                    })
                    .on('declined', function () {
                        console.warn('User has declined your call invitation');
                        hideCallScreen();
                    });
                setCallListeners();
            }
        }

        $("#reconnect_btn").click(function(event) {
            if($(this).text()=='Zum Gespräch')
                location.reload();
            else
                $("#disconnect-modal").modal('hide');
        });

        $("#summary_btn").click(function(event) {
            // Redirect to summary page
            window.location.href = publicUrl+"video-call/summary/"+appointmentId;
        });

        /** Call logging */
        var call_logger  = null;
        var duration_min = 0;
        function initiateCallLog () {
            if(call!==null) {
                console.log('initiating call log');
                // Make a ajax request to create new call log record
                call_logger = setInterval(function() { 
                                duration_min += 5;
                                saveCallRecord(call);
                            }, 300000); // every 5 minutes
            }
        }

        function endCallLog () {
            console.log('call log ended');
            if(call_logger!=null) {
                // End call logging
                clearInterval(call_logger);
                call_logger = null;

                // Save final call log
                console.log('saving final call log');
                saveCallRecord(call);

                // Reset call object
                call = null;
                duration_min = 0;

            }
            $("#disconnect-modal").modal('show');
            if(has_ended) {
                has_ended = false;
                $("#reconnect_btn").text('Zum Gespräch');
            } else {
                $("#reconnect_btn").text('Zum Gespräch');
            }
        }

        function saveCallRecord (call) {
            $.ajax({
                url: publicUrl+'save-call-log',
                type: 'POST',
                data: {
                        _token: $('input[name="_token"]').val(),
                        conversation_id: call.getId(),
                        appointment_id: appointmentId,
                        duration: duration_min,
                        status: call.getStatus().description
                    },
            })
            .done(function(data) {
                console.log("success");
                console.log(data);
            })
            .fail(function(data) {
                console.log("error");
                console.log(data);
            })
            .always(function() {
                console.log("complete");
            });
        }
        /** Call logging End */

        $(document).ready(function() {

            //==============================
            // CREATE USER AGENT 
            //==============================
            ua = new apiRTC.UserAgent({
                uri: 'apzkey:2089b37bcf5914c0d0dfea2a7e5edd76'
            });

            //==============================
            // REGISTER
            //==============================
            ua.register({
                id : {{$user_id}} // OPTIONAL // This is used for setting userId
            }).then(function (session) {
                // ok
                console.log("Registration OK");
                connectedSession = session;
                console.log(connectedSession);

                //createStream();


                // Get contact
                contact = connectedSession.getOrCreateContact(receiverId);
                if(contact !== null && contact.isOnline()) {
                    console.log(contact);
                    makeCall();
                    //hideCallScreen();
                } else {
                    // Listen for contact online
                    toastr.error('Die Verbindung wird automatisch aufgebaut, sobald dein Gesprächspartner online ist.');
                }

                // WHEN A CONTACT CALLS ME
                connectedSession.on('incomingCall', function (invitation) {
                    console.log("MAIN - incomingCall");
                    if(call!=null && call.getStatus().description=='CALL_STATUS_ENDED')
                        call = null;
                    //==============================
                    // ACCEPT CALL INVITATION
                    //==============================
                    invitation.accept()
                        .then(function (inCall) {
                            call = inCall;
                            setCallListeners();
                        });
                })
                /*.on('contactListUpdate', function(leftGroup, joinedGroup, userDataChanged) {
                    console.log(leftGroup);
                    console.log(joinedGroup);
                    console.log(userDataChanged);
                })*/
                // Enable call button
                //hideCallScreen();
            }).catch(function (error) {
                // error
                console.error('User agent registration failed', error);
            });

            $("#hangup").click(function(event) {
                // Call hangup
                has_ended = true;
                call.hangUp();
                hideCallScreen();

            });
            
        });

        function showCallScreen () {
            $(".incallelement").show();
            $("#makeCall").attr('disabled', 'disabled');
            //$("#makeCall").hide();
            $("#hangup").show();
        }

        function hideCallScreen () {
            $(".incallelement").hide();
            $("#makeCall").removeAttr('disabled');
            $("#local-container .onlyaudio").hide();
            $("#remote-container .onlyaudio").hide();
            //$("#makeCall").show();
        }

    </script>
</body>
</html>