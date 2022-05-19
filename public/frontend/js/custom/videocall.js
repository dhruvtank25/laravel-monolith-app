$(function() {
    
    //==================================
    // INTIALIZE VARIABLES
    //==================================
    var sessionId             = 'himmlischCall'+appointmentId;
    var isHost                = false;
    var hostConfId            = null;
    var ua                    = null;
    var connectedSession      = null;
    var localStream           = null;
    var connectedConversation = null;
    var call_logger           = null;
    var duration_min          = 0;
    var screensharingStream   = null;
    var isRemoteScreenshared  = false;

    //==================================
    // USER ACTIONS
    //==================================
    $(document).on('change', '#select-mic', function(event) {
        toggleMicStatus();
    });

    $(document).on('change', '#select-camera', function(event) {
        toggleCameraStatus();
    });

    $(document).on('click', '#select-screenshare', function(event) {
        if(isRemoteScreenshared) {
            hideScreenShareBtn();
            return false;
        }
        if(screensharingStream!==null) {
            stopScreenShare();
        } else {
            startScreenShare();
        }
    });

    $(document).on('click', '#hangup', function(event) {
        connectedConversation.leave();
    });

    $(document).on('click', '#reconnect_btn', function(event) {
        if(!$("#disconnect-modal").data('is_remote'))
            location.reload();
        else
            $("#disconnect-modal").modal('hide');
    });

    $(document).on('click', '#summary_btn', function(event) {
        window.location.href = publicUrl+"video-call/summary/"+appointmentId;
    });

    //======================================
    // HELPER TO ATTACH MEDIA STREAM IN DIV
    //======================================
    function addStreamInDiv(stream, divId, mediaEltId, style, muted) {
        var streamIsVideo = stream.hasVideo();
        console.log('addStreamInDiv - hasVideo? ' + streamIsVideo);

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
        if(divId=='remote-container') {
            mediaElt.classList.add("remote-stream-"+stream.streamId);
            //mediaElt.classList.add("col-md-*");
        }
        divElement.appendChild(mediaElt);
        promise = mediaElt.play();

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

    //==============================
    // CREATE AND JOIN CONFERENCE
    //==============================
    (function joinConversation() {
        console.log('joining conversation');
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
            id : userId // OPTIONAL // This is used for setting userId
        }).then(function (session) {
            console.log("Registration OK");
            
            // Save session
            connectedSession = session;

            //============================
            // LISTEN TO SESSION EVENTS
            //============================
            subscribeSession();

            //==============================
            // CREATE CONVERSATION
            //==============================
            connectedConversation = connectedSession.getConversation(sessionId);

            //==============================
            // JOIN CONVERSATION
            //==============================
            connectedConversation.join()
                .then(function(response) {
                    //==============================
                    // CREATE LOCAL STREAM
                    //==============================
                    //https://dev.apirtc.com/reference/global.html#CreateStreamOptions
                    ua.createStream()
                        .then(function (stream) {
                            // Save local stream
                            localStream = stream;

                            // Set local stream
                            addStreamInDiv(localStream, 'local-container', 'local-media', {width : "160px", height : "120px"}, true);

                            //================================
                            // LISTEN TO LOCALSTREAM EVENTS
                            //================================
                            subscribeLocalStream();

                            // unmute Audio
                            localStream.unmuteAudio();

                            // mute Video
                            localStream.muteVideo();

                            // Show Call UI
                            showCallScreen();

                            //==============================
                            // PUBLISH OWN STREAM
                            //==============================
                            connectedConversation.publish(localStream, null).then(function(stream){
                                console.log('local stream is published'); 
                                //===============================
                                // CHECK IF OTHER USER HAS JOINED
                                //===============================
                                if(connectedSession.getOnlineContactsArray().length==0) {
                                //if(Object.keys(connectedConversation.getContacts()).length==0) {
                                    toastr.error('Die Verbindung wird automatisch aufgebaut, sobald dein Gesprächspartner online ist.');
                                    isHost = true;
                                    hostConfId = connectedSession.getUserData().userConfId;
                                }
                            }).catch(function(err) {
                                console.error('failed to publish local stream', err);
                            });

                        }).catch(function (err) {
                            console.error('create stream error', err);
                        });
                });
            
            //=================================
            // LISTEN TO CONVERSATION EVENTS
            //=================================
            subscribeConversation();

        }).catch(function (error) {
            // error
            console.error('User agent registration failed', error);
        });
    })();

    //==============================
    // SESSION EVENTS
    //==============================
    function subscribeSession() {
        // https://dev.apirtc.com/apiRTC_V3/module-ApiRTCSession.html#~event:channelEvent__anchor
        connectedSession.on('disconnect', function(e) {
            if(e.detail.eventType=='channelEvent') {
                switch (e.detail.channelEvent) {
                    case 'onChannelDisconnect':
                        showDisconnectedModal('local');
                        break;

                    case 'onChannelReconnect':
                        $("#disconnect-modal").modal('hide');
                        break;

                    default:
                        break;
                }
            }
        });
    }

    //==============================
    // LOCALSTREAM EVENTS
    //==============================
    function subscribeLocalStream() {
        localStream.on('muteStateChange', function(state) {
            if(state.type=='video') {
                if(state.muted) {
                    $('label[for="select-camera"]').css({'background':'red','color':'#ffffff'}).html('<img class="video_icon" src="https://himmlischberaten.de/frontend/img/video_deactive.png">');
                } else {
                    $('label[for="select-camera"]').css({'background':'#00bf00','color':'#ffffff'}).html('<img class="video_icon" src="https://himmlischberaten.de/frontend/img/video-active.png">');
                }
            } else if(state.type=='audio') {
                if(state.muted) {
                    $("label[for='select-mic']").css({'background':'red','color':'#ffffff'}).html('<i class="fa fa-microphone-slash" aria-hidden="true"></i>');
                } else {
                    $("label[for='select-mic']").css({'background':'#00bf00','color':'#ffffff'}).html('<i class="fa fa-microphone" aria-hidden="true"></i>');
                }
            }
        });

        localStream.on("stopped", function () { 
            console.error("Stream stopped");
            $('#local-media').remove();
        });
    }

    //==============================
    // CONVERSATION EVENTS
    //==============================
    function subscribeConversation() {
        //=================================
        // STREAM LIST CHANGED
        //=================================
        connectedConversation.on('streamListChanged', function(streamInfo) {
            console.log("streamListChanged :", streamInfo);
            if (streamInfo.listEventType === 'added') {
                if (streamInfo.isRemote === true) {
                    connectedConversation.subscribeToStream(streamInfo.streamId)
                        .then(function (stream) {
                            console.log('subscribeToMedia success');
                        }).catch(function (err) {
                            console.error('subscribeToMedia error', err);
                        });
                }
            }   
        });

        //============================================
        // NEW STREAM IS ADDED TO CONVERSATION
        //============================================
        connectedConversation.on('streamAdded', function(stream) {
            console.log('remote stream added');
            console.log(stream);
            if(stream.isScreensharing()) {
                console.log('in screen share!');
                hideScreenShareBtn();
                $("[class*=remote-stream]").hide();
            }
            addStreamInDiv(stream, 'remote-container', 'remote-media', {width : "640px", height : "480px"}, false);
        });

        //=====================================================
        // STREAM WAS REMOVED FROM THE CONVERSATION
        //=====================================================
        connectedConversation.on('streamRemoved', function(stream) {
            console.log('remote host disconnected');
            console.log(stream);

            document.getElementsByClassName('remote-stream-'+stream.streamId)[0].remove();
            //document.getElementById('remote-media').remove();

            if(stream.isScreensharing()) {
                showScreenShareBtn();
                $("[class*=remote-stream]").show();
            } else {
                // Contacts list
                var contactsIdArr = Object.keys(connectedConversation.getContacts()).sort();

                // Updated Contacts List
                // Contact list takes a little time to update after contact leaves
                // so we will manually remove left user from existing list.
                contactsIdArr = contactsIdArr.filter(function(contactId){
                    return stream.contact.id!=contactId;
                });

                // Check if host was disconnected
                if(stream.contact.userData.userConfId==hostConfId || hostConfId==null) {
                    console.log('host disconnected!');

                    // Check if we can make you host
                    var thisUserId = connectedSession.getId();
                    if(contactsIdArr.length==0 || contactsIdArr[0]>thisUserId) {
                        console.log('updated to be host');
                        isHost = true;
                        hostConfId = connectedSession.getUserData().userConfId;
                    }
                }
            }
        });

        //=====================================================
        // NEW USER JOINED CONVERSATION
        //=====================================================
        connectedConversation.on('contactJoined', function(contact) {
            $("#disconnect-modal").modal('hide');
            toastr.success('New User has joined the conversation');
            if(isHost) {
                if(call_logger==null) {
                    startCallTimer();
                }
                // Send total call duration
                connectedConversation.sendData({'time': duration_min});
            }
        });

        //=========================================================
        // CONNECTED USER LEFT THE CONVERSATION
        // 
        // Contact list updation takes some time to get updated
        // So this event  takes little time to get fired
        // That is why immediately updation stuffs are 
        // handled inside "streamRemoved" Event
        //=========================================================
        connectedConversation.on('contactLeft', function(contact) {
            var contactsIdArr = Object.keys(connectedConversation.getContacts());
            if(contactsIdArr.length==0) {
                saveCallRecord();
                showDisconnectedModal('remote');
            }
        });

        //=====================================================
        // YOU LEFT CONVERSATION
        //=====================================================
        connectedConversation.on('left', function() {
            saveCallRecord();
            localStream.release();
            showDisconnectedModal('local');
        });

        //====================================================
        // CONTACT BROADCASTED DATA MESSAGE
        //====================================================
        connectedConversation.on('data', function(dataObj) {
            var data      = dataObj.content;
            var senderObj = dataObj.sender;
            if(typeof data.time!=undefined) {
                console.log('syncing call duration');
                duration_min = data.time;
                startCallTimer();

                // Set Message Sender as Host
                hostConfId = senderObj.userData.userConfId;
            }
        });

        /*connectedConversation.on('localStreamUpdated', function(streamInfo) {
            console.log('local stream updated');
            console.log(streamInfo);
        });*/

        /*connectedConversation.on('remoteStreamUpdated', function(streamInfo) {
            console.log('remote stream updated');
            console.log(streamInfo);
        });*/
    }

    //=============================
    // LOCAL SCREENSHARING EVENTS
    //=============================
    function subscribeLocalScreensShare() {
        screensharingStream.on("stopped", function() {
            console.log('in screen share stopped!');
            screensharingStream = null;
            toastr.success("Screen sharing stopped");
            // Update screenshare button
            $("#select-screenshare").find('i.fa-desktop').css('color', 'red');
        });
    }

    //==============================
    // SCREENSHARING
    //==============================
    function startScreenShare() {
        const displayMediaStreamConstraints = {
                    video: {
                        cursor: "always"
                    },
                    audio: {
                        echoCancellation: true,
                        noiseSuppression: true,
                        sampleRate: 44100
                    }
                };

        apiRTC.Stream.createDisplayMediaStream(displayMediaStreamConstraints, false)
            .then(function(stream) {
                //Display your stream in video element
                toastr.success("You are sharing the screen");
                screensharingStream = stream;
                connectedConversation.publish(screensharingStream);

                // Update screenshare button
                $("#select-screenshare").find('i.fa-desktop').css('color', 'green');

                // LISTEN TO SCREEN SHARE EVENT
                subscribeLocalScreensShare();
            })
            .catch(function(err) {
                toastr.error('Could not share screen: '+err.message);
                console.error('Could not create screensharing stream :', err);
            });
    }

    function stopScreenShare() {
        if(screensharingStream!==null) {
            // Stop Screenshare
            connectedConversation.unpublish(screensharingStream);
            screensharingStream.release();
        }
    }   

    function hideScreenShareBtn() {
        isRemoteScreenshared = true;
        $("#select-screenshare").hide();
    }

    function showScreenShareBtn() {
        isRemoteScreenshared = false;
        $("#select-screenshare").show();
    }

    //============================
    // CALL LOGGING / TIMER
    //============================
    function startCallTimer () {
        stopCallTimer();
        call_logger = setInterval(function() { 
                        duration_min += 1;
                    }, 60000); // every 1 minutes
    }

    function stopCallTimer () {
        console.log('call log ended');
        if(call_logger!=null) {
            // End call logging
            clearInterval(call_logger);
            call_logger = null;
        }
    }

    function saveCallRecord () {
        $.ajax({
            url: publicUrl+'save-call-log',
            type: 'POST',
            data: {
                    _token: $('input[name="_token"]').val(),
                    conversation_id: sessionId,
                    appointment_id: appointmentId,
                    duration: duration_min,
                    status: connectedConversation.getStatus().description
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

    //=============================
    // OTHER HELPERS
    //=============================

    function toggleMicStatus () {
        if(localStream.isAudioMuted()) {
            localStream.unmuteAudio();
        } else {
            localStream.muteAudio();
        }
    }

    function toggleCameraStatus () {
        if(localStream.isVideoMuted()) {
            localStream.unmuteVideo();
        } else {
            localStream.muteVideo();
        }
    }

    function showCallScreen () {
        $(".incallelement").show();
        $("#local-container").show();
        $("#remote-container").show();
    }

    function showDisconnectedModal(intiated_by) {
        if(intiated_by=='remote')
            $("#disconnect-modal").data('is_remote', true);
        else
            $("#disconnect-modal").data('is_remote', false);
        $("#disconnect-modal").modal('show');
    }

});
