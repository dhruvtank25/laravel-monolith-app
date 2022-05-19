@extends('layouts.main')

@section('styles')
<link href="{{ asset('backend/assets/plugins/fullcalendar/fullcalendar.print.css') }}" media="print" rel="stylesheet"/>
<link href="{{ asset('backend/assets/plugins/fullcalendar/fullcalendar.min.css') }}" rel="stylesheet"/>
<link href="{{ asset('backend/assets/plugins/fullcalendar/lib/scheduler.min.css') }}" rel="stylesheet"/>
<link href="{{ asset('backend/assets/plugins/select2/dist/css/select2.min.css') }}" rel="stylesheet"/>
<!-- ================== BOOTSTRAP MINTY THEME START ================== -->
{{-- <link href="https://bootswatch.com/4/minty/bootstrap.min.css" rel="stylesheet"/> --}}
<!-- ================== BOOTSTRAP MINTY THEME END   ================== -->
@endsection

@section('content')
<div class="row">
    @if(isset($coach))
        <div class="col-md-12 text-right">
            <a class="btn btn-large btn-success" href="{{url('admin/coaches/availability/'.$coach->id)}}"></span><span class="fa fa-forward"></span>  Coach Availabilities</a>
        </div>
    @else
        <div class="col-md-12 p-t-5">
            <!-- begin panel -->
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h4 class="panel-title">Filter Coach By</h4>
                </div>
                <div class="panel-body">
                    <form class="row form">
                        <div class="col-md-4">
                            <div class="form-group">
                              <label for="coach_filter">Coach Name</label>
                              <select id="coach_filter" class="form-control default-select2">
                                  <option value="">Select Coach</option>
                                  @foreach ($coaches as $filter_coach)
                                      <option value="{{$filter_coach->id}}">{{$filter_coach->first_name.' '.$filter_coach->last_name}}</option>
                                  @endforeach
                              </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                              <label for="category_filter">Category</label>
                              <select id="category_filter" class="form-control default-select2">
                                  <option value="">Select Category</option>
                                  @foreach ($categories as $category)
                                    <option value="{{$category->id}}">{{$category->title}}</option>
                                  @endforeach
                              </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                              <label for="available_on_filter">Working On</label>
                              <select id="available_on_filter" class="form-control default-select2">
                                <option value="">Select Day</option>
                                <option value="Monday">Monday</option>
                                <option value="Tuesday">Tuesday</option>
                                <option value="Wednesday">Wednesday</option>
                                <option value="Thursday">Thursday</option>
                                <option value="Friday">Friday</option>
                                <option value="Saturday">Saturday</option>
                                <option value="Sunday">Sunday</option>
                              </select>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- end panel -->
        </div>
    @endif
    <!-- BEGIN CALENDAR -->
    <div id="calendar" class="col-md-12 m-t-5">
    </div>
    <!-- END CALENDAR -->

    <!-- BEGIN NO DATA MESSAGE -->
    <div class="col-md-12">
        <div class="panel panel-default">
            <div id="calendar_no_data" class="panel-body bg-teal-darker text-white">
                
            </div>
        </div>
    </div>
    <!-- END NO DATA MESSAGE -->

    <!-- NEW EVENT MODAL -->
    <div class="modal fade" id="lesson_modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">
                        <span class="user_title">Schedule Lesson</span>
                        <p class="f-s-11">
                            <span class="label label-primary m-l-3">with <i class="fa fa-user"></i> <span id="schedule_with"></span>
                            </span> 
                            <span class="label label-primary m-l-3"> starting from <i class="fa fa-calendar"></i> <span id="scedule_on">Monday 13 May, 2019</span>
                            </span>
                            <span class="label label-primary m-l-3"> At <i class="fa fa-clock"></i> <span id="schedule_start">11:15 AM</span>
                            </span>
                            <span class="label label-primary m-l-3"> To <i class="fa fa-clock"></i> <span id="schedule_end">12:00 PM</span>
                            </span>
                        </p>
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <form action="{{url('admin/coaches/schedule/new')}}" method="POST" id="appointmentform">
                        @csrf
                        <input type="hidden" name="coach_id" id="frm_coach_id" value="">
                        <input type="hidden" name="start" id="frm_start" value="">
                        <input type="hidden" name="end" id="frm_end" value="">
                        <input type="hidden" name="status" id="frm_status" value="scheduled">
                        <label class="control-label">User <span class="text-danger">*</span></label>
                        <div class="row m-b-15">
                            <div class="col-md-12">
                                <select name="user_id" class="modal-select2 form-control m-b-5" required>
                                    @foreach ($users as $user)
                                        <option value="{{$user->id}}">{{$user->first_name.' '.$user->last_name}}</option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <label class="control-label">Category <span class="text-danger">*</span></label>
                        <div class="row m-b-15">
                            <div class="col-md-12">
                                <select name="category_id" class="modal-select2 form-control m-b-5" required>
                                    <option value="1">Family</option>
                                    <option value="2">Other</option>
                                </select>
                                @error('category_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <label class="control-label">Mode <span class="text-danger">*</span></label>
                        <div class="row m-b-15">
                            <div class="col-md-12">
                                <div class="radio radio-css radio-inline">
                                    <input type="radio" name="mode" id="onlineRadio" value="online" checked="">
                                    <label for="onlineRadio">Online</label>
                                </div>
                                <div class="radio radio-css radio-inline">
                                    <input type="radio" name="mode" id="offlineRadio" value="offline">
                                    <label for="offlineRadio">Offline</label>
                                </div>
                                @error('mode')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <label class="control-label">Notes <span class="text-danger">*</span></label>
                        <div class="row m-b-15">
                            <div class="col-md-12">
                                <textarea name="notes" cols="30" rows="3" class="form-control m-b-5"></textarea>
                                @error('notes')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="appointSaveBtn">Save</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- END NEW EVENT MODAL -->

    <!-- Appointment Details Modal -->
    @include('admin.appointments._appointment_info')
    <!-- Appointment Details Modal END -->

</div>
@endsection

@section('scripts')
    <script src="{{ asset('backend/assets/plugins/fullcalendar/fullcalendar.min.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/fullcalendar/lib/scheduler.min.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/select2/dist/js/select2.min.js') }}"></script>
    <!-- ==================   PARTIAL INFO JS   ================== -->
    <script src="{{ asset('backend/js/appointment_info.js') }}"></script>
    <!-- ================== PARTIAL INFO JS END ================== -->
    <script>
        var coach_id = '';
        var resources = [];
        $(document).ready(function() {
            coach_id  = "{{ isset($coach)?$coach->id:''}}";

            $("#calendar_no_data").hide();

            // Initialize full Calendar
            $('#calendar').fullCalendar({
                schedulerLicenseKey: 'CC-Attribution-NonCommercial-NoDerivatives',
                //themeSystem: 'bootstrap4',
                disableDragging: true,
                defaultView: 'agendaWeek',
                allDaySlot: false, // uncomment this line to hide the all-day slot
                navLinks: true,
                header: {
                    left: 'prev,today,next',
                    center: 'title',
                    right: 'agendaDay,agendaThreeDay,agendaWeek'
                },
                views: {
                    agendaThreeDay: {
                        type: 'agenda',
                        duration: { days: 3 },

                        // views that are more than a day will NOT do this behavior by default
                        // so, we need to explicitly enable it
                        groupByResource: true

                        // uncomment this line to group by day FIRST with resources underneath
                        //  groupByDateAndResource: true
                    },
                    agendaWeek: {
                        type: 'agenda',
                        duration: { days: 7 },

                        // views that are more than a day will NOT do this behavior by default
                        // so, we need to explicitly enable it
                        groupByResource: true

                        // uncomment this line to group by day FIRST with resources underneath
                        //   groupByDateAndResource: true
                    },
                },
                height: "auto",
                aspectRatio: 1.75,
                editable: true,
                firstDay: 0,
                //minTime: "08:00:00",
                //maxTime: "22:30:00",
                slotDuration: '00:15:00',
                selectable: true,
                selectHelper: true,
                selectOverlap: false,
                select: function(start, end, jsEvent, view, resource) {
                    eventData = {
                        start: start,
                        end: end,
                        resourceId: resource.id,
                        resourceName: resource.title,
                        color: '#aa9423',
                    };

                    console.log(eventData);

                    addNewEvent(eventData);
                },
                //resources: resources,
                resources: function(callback) {
                    setResource(function(resourceObjects) {
                        callback(resourceObjects);
                    });
                },
                eventOverlap: false,
                eventLimit: true, // allow "more" link when too many events
                eventRender: function(event, element) {                                          
                    element.find('div.fc-title').html(element.find('div.fc-title').text());
                },
                eventClick: function(event) {
                    console.log(
                        'eventClick',
                        event
                    );
                    editClickEvent(event);
                },
                eventResize: function( event, delta, revertFunc, jsEvent, ui, view ) {
                    console.log(
                        'eventResize',
                        event
                    );
                    editEvent(event);
                },
                noEventsMessage: "Not Available"
            });

            // Set Calendar Events
            updateCalendarEvents();

            $(".modal-select2").select2({
                dropdownParent: $('#lesson_modal'),
                width: '100%'
            });

            $("#coach_filter, #category_filter, #available_on_filter").change(function(event) {
                updateCalendarEvents();
            });
        });
    </script>
    <script>

        function setResource (callback) {
            callback(resources);
        }

        function updateCalendarEvents () {
            var events = {
                            url:  baseUrl+'/coaches/schedule/events',
                            success : function(data) {
                                console.log('event fetch success');
                                console.log(data);
                                if(data.length==0) {
                                    $('#calendar').hide();
                                    if(coach_id!=''){
                                        var message = 'Coach has not set availability yet';
                                    }else{
                                        var message = 'No coach found that match your selection';
                                    }
                                    $("#calendar_no_data").text(message);
                                    $("#calendar_no_data").show();
                                }else{
                                    $('#calendar').show();
                                    $("#calendar_no_data").hide();
                                }
                                var resource_arr = [];
                                for(let i = 0, length1 = data.length; i < length1; i++){
                                    resource_arr.push({'id':data[i].resourceId,'title':data[i].resourceName});
                                }
                                console.log(resource_arr);
                                resources = resource_arr;
                                $('#calendar').fullCalendar('refetchResources');

                            },
                            error: function(error) {
                                console.log('event fetch error', event);
                                toastr.error('there was an error while fetching events!', 'Failed!');
                                $('#calendar').hide();
                                $("#calendar_no_data").text('Failed to fetch data!');
                                $("#calendar_no_data").show();
                            },
                            type: 'GET',
                            data: {}
                        };
            if(coach_id!=''){
                events['data']['coach_id'] = coach_id;
            }else {
                if($("#coach_filter").val()!='')
                    events['data']['coach_id']    = $("#coach_filter").val();
                if($("#category_filter").val()!='')
                    events['data']['category_id'] = $("#category_filter").val();
                if($("#available_on_filter").val()!='')
                    events['data']['day']         = $("#available_on_filter").val();
            }
            $('#calendar').fullCalendar('removeEventSources');
            $('#calendar').fullCalendar('addEventSource', events);
        }

        $(function() {
            // update resource colors to red
            $('th.fc-resource-cell').css('color', 'red');

            $('div#calendar').click(function () {
                // update the resource color
                $('th.fc-resource-cell').css('color', 'red');
            });
        });
        
        // set global event_id for editing
        var _id = '';

        function addNewEvent(eventData) {

            // Set Display data
            $("#schedule_with").text(eventData.resourceName);
            $("#scedule_on").text(eventData.start.format('dddd D MMM, YYYY'));
            $("#schedule_start").text(eventData.start.format('HH:mm A'));
            $("#schedule_end").text(eventData.end.format('HH:mm A'));

            // Set Hidden Form Data
            $("#appointmentform #frm_coach_id").val(eventData.resourceId);
            $("#appointmentform #frm_start").val(eventData.start.format('YYYY-MM-DD HH:mm:ss'));
            $("#appointmentform #frm_end").val(eventData.end.format('YYYY-MM-DD HH:mm:ss'));

            // Show form
            $('#lesson_modal').modal('show');
        }

        $("#appointSaveBtn").click(function(event) {
            $.ajax({
                url: baseUrl+'/coaches/schedule/new',
                type: 'POST',
                data: $('form#appointmentform').serialize(),
            })
            .done(function(data) {
                console.log("success");
                console.log(data);
                if(data.success=='true'){
                    // Append data in calendar
                    //$('#calendar').fullCalendar('renderEvent', data.event_data, true); // stick? = true
                    //$('#calendar').fullCalendar('unselect');

                    // close modal
                    $('#schedule_modal').modal('hide');

                    // show success response
                    toastr.success("New Appointment has been scheduled.", 'Session Created!');

                    // Update calendar
                    updateCalendarEvents();
                }else{
                    // show success response
                    toastr.error("Something went wrong.", 'Sorry! Please Resubmit');
                }
            })
            .fail(function(data) {
                console.log("error");
                console.log(data);

                var errors = data.responseJSON;

                // create errors array
                var errors_array = [];
                $.each(errors, function(i, obj) {
                    //use obj.id and obj.name here, for example:
                    errors_array.push(obj[0]);
                });

                if(! errors_array.length > 0) {
                    errors_array.push('Something went wrong, please submit the form again.');
                }

                // show error messages
                toastr.error(errors_array.join('<br>'), 'Sorry! Please Resubmit');
            })
            .always(function() {
                console.log("complete");
            });
        });
        
        function lesson_address(type) {
            // check if type is teacher
            if (type === 'teacher') {
                $('input#location_address').val($('input#teacher_address').val());

            } else {
                // check if location is not at_teacher
                if ($('input#location:checked').val() != 'at_teacher') {
                    var student_id = $('#student_id').val();
                    $('input#location_address').val($('input#student_address-' + student_id).val());
                }
            }
        }

        function editClickEvent (eventData) {
            show_appointment_info(eventData.id);
        }

        function editEvent(eventData) {
            // show modal
            $('#schedule_modal').modal('show');

            // Set data
            $("#schedule_with").text(eventData.resourceName);
            $("#scedule_on").text(eventData.start.format('dddd D MMM, YYYY'));
            $("#schedule_start").text(eventData.start.format('HH:mm A'));
            $("#schedule_end").text(eventData.end.format('HH:mm A'));

            var event_id = eventData.id;

            // remove modal-lg class
            /*if ($('div#schedule_modal .modal-dialog').hasClass('modal-lg')) {
                $('div#schedule_modal .modal-dialog').removeClass('modal-lg');
            }

            // check if type is not new_pending
            if (eventData.type !== 'new_pending') {
                var splits = eventData.id.split('-');
                event_id   = splits[1];

                // add class
                $('div#schedule_modal .modal-dialog').addClass('modal-lg');
            }

            // Fetch lesson event
            $.ajax({
                url: 'https://www.mymusician.com.au/admin/teacher/schedule/edit',
                type: "GET",
                data: {'id': event_id, 'type': eventData.type},
                success: function(data) {
                    var response = $(data); // the HTML content that controller has produced

                    // append response in teacher modal
                    $('div#schedule_modal .modal-content').html(response);

                    // show modal
                    $('#schedule_modal').modal('show');

                    $("select#teacher_instrument").select2({
                        dropdownParent: $("#schedule_modal")
                    });

                    // update global _id
                    _id = eventData.id;
                },
                error: function(data) {
                    var errors = data.responseJSON;

                    // create errors array
                    var errors_array = [];
                    $.each(errors, function(i, obj) {
                        //use obj.id and obj.name here, for example:
                        errors_array.push(obj[0]);
                    });

                    if(! errors_array.length > 0) {
                        errors_array.push('Something went wrong, please submit the form again.');
                    }

                    // show error messages
                    toastr.error(errors_array.join('<br>'), 'Sorry! Please Resubmit');
                }
            });*/
        }

        function updateTeacherEvent(form_id) {
            // save new event via ajax request
            $.ajax({
                url: 'https://www.mymusician.com.au/admin/teacher/schedule/update',
                type: "POST",
                data: $('form#' + form_id).serialize(),
                success: function(data) {
                    var response = data.responseJSON; // the HTML content that controller has produced

                    if (typeof data.event_data !== 'undefined') {
                        // remove event first from fullcalendar
                        $('#calendar').fullCalendar('removeEvents', _id);

                        // Append data in calendar
                        $('#calendar').fullCalendar('renderEvent', data.event_data, true); // stick? = true
                        $('#calendar').fullCalendar('unselect');

                        // show success response
                        toastr.success("Lesson schedule updated successfully.", 'Lesson Updated!');
                    } else if (typeof data.message !== 'undefined') {
                        // show success response
                        toastr.success(data.message, 'Notes Added!');
                    }

                    // close modal
                    $('#schedule_modal').modal('hide');

                },
                error: function(data) {
                    var errors = data.responseJSON;

                    // create errors array
                    var errors_array = [];
                    $.each(errors, function(i, obj) {
                        //use obj.id and obj.name here, for example:
                        errors_array.push(obj[0]);
                    });

                    if(! errors_array.length > 0) {
                        errors_array.push('Something went wrong, please submit the form again.');
                    }

                    // show error messages
                    toastr.error(errors_array.join('<br>'), 'Sorry! Please Resubmit');
                }
            });
        }

        function deleteTeacherEvent() {
            $.ajax({
                url: 'https://www.mymusician.com.au/admin/teacher/schedule/destroy',
                type: "POST",
                data: $('form#update_lesson').serialize(),
                success: function(data) {
                    var response = data.responseJSON; // the HTML content that controller has produced

                    // remove event first from fullcalendar
                    $('#calendar').fullCalendar('removeEvents', _id);

                    // close modal
                    $('#schedule_modal').modal('hide');

                    // show success response
                    toastr.success("Lesson schedule deleted successfully.", 'Lesson Deleted!');

                },
                error: function(data) {
                    var errors = data.responseJSON;

                    // create errors array
                    var errors_array = [];
                    $.each(errors, function(i, obj) {
                        //use obj.id and obj.name here, for example:
                        errors_array.push(obj[0]);
                    });

                    if(! errors_array.length > 0) {
                        errors_array.push('Something went wrong, please submit the form again.');
                    }

                    // show error messages
                    toastr.error(errors_array.join('<br>'), 'Sorry! Please Resubmit');
                }
            });
        }

        /*$('#schedule_modal').on('hide.bs.modal', function () {
           // reset global _id
           _id = '';
        });*/
    </script>
@endsection
