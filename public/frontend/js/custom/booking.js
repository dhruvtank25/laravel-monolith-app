$(function() {
    var nextAppointmentDatataTableUrl = baseUrl+'/next-appointment/datatables';
    var pastAppointmentDatataTableUrl = baseUrl+'/next-appointment/datatables';
    nextAppointmentDataTable = $('#Next_appointment_table').DataTable({
                        "language": {
                            "url": publicUrl+"/frontend/js/dataTables.german.json"
                        },
                        responsive: true,
                        processing: true,
                        serverSide: true,
                        ajax:
                        {
                            url: nextAppointmentDatataTableUrl,
                            data: 'N'
                        },
                        columns: [
                            { 
                                data: 'start',
                                render: function (data, type, row) {
                                    return row.start;
                                }, 
                                name:'start'
                            },
                            { 
                                render: function (data, type, row) {
                                    return row.coach.first_name+' '+row.coach.last_name;
                                },
                                name:'coach.first_name'
                            },
                            { 
                                render: function (data, type, row) {
                                    return row.categories.title;
                                }, 
                                name:'categories.title'
                            },
                            { data: 'start_time', name:'start', searchable: false},
                            {
                                render: function (data, type, row) {
                                    return row.duration+' Minuten';
                                },
                                name:'duration', 
                                searchable: false
                            },
                            { data: 'mode', name: 'mode'},
                            { render: getUpcomingActionLinks }
                        ],
                        deferRender: true
                    });

    /** Actions */
    function getUpcomingActionLinks (data, type, full, meta) {
        var action = '';
        
        var call_active = false;
        var is_cancellable = false;
        var moment_now = moment().tz('Europe/Berlin').format('YYYY-MM-DD HH:mm:ss');
        if(moment(moment_now).isBetween(full.call_active_start, full.actual_end))
            call_active = true;
        if(moment(moment_now).isBefore(full.call_active_start))
            is_cancellable = true;
        if(full.mode=='online')
            action += '<button type="button" class="btn btn-primary actionStart" data-id="'+full.id+'" '+(call_active?'':'disabled')+'>Starten</button>';
        var now = moment(moment().tz('Europe/Berlin').format('YYYY-MM-DD HH:mm:ss'));
        var session_start = moment(full.actual_start);
        var time_diff = session_start.diff(now, 'hours');
        if(full.user_updated_on==null && time_diff>48)
            action += '<button type="button" class="btn btn-info actionMove" data-id="'+full.id+'" data-userid="'+full.user_id+'" data-coachid="'+full.coach_id+'" data-coaching-method="'+full.coach.coaching_method+'" data-duration="'+full.duration+'" data-mode="'+full.mode+'">Verschieben</button>';
        if(is_cancellable)
            action += '<button type="button" class="btn btn-danger actionCancel" data-id="'+full.id+'">Stornieren</button>';
        return action;
    }

    $('body').on('click', '.actionStart', function(event) {
        event.preventDefault();
        var appointment_id = $(this).data('id');
        var win = window.open(publicUrl+'video-call/'+appointment_id, '_blank');
        win.focus();
    });

    $('body').on('click', '.actionMove', function(event) {
        event.preventDefault();
        var appointment_id = $(this).data('id');
        var coach_id       = $(this).data('coachid');
        var duration       = $(this).data('duration');
        var coach_mode     = $(this).data('coaching-method');
        var mode           = $(this).data('mode');
        moveSession(appointment_id, coach_id, duration, coach_mode, mode);
    });

    $('body').on('click', '.actionCancel', function(event) {
        event.preventDefault();
        var appointment_id = $(this).data('id');
        var $tablerow      = $(this).closest('tr');
        // Confirm Cancellation
        bootbox.confirm({
            message: "Bist du sicher, dass du diesen Beratungstermin stornieren möchtest?",
            backdrop: true,
            buttons: {
                confirm: {
                    label: 'Termin stornieren',
                    className: 'btn-danger'
                },
                cancel: {
                    label: 'Abbrechen',
                    className: 'btn-success'
                }
            },
            callback: function (result) {
                if(result) {
                    cancelSession(appointment_id, $tablerow);
                }
            }
        });
    });
    /** Actions End */

    var pastAppointmentDataTable;
    pastAppointmentDataTable = $('#Past_appointment_table').DataTable({
                        "language": {
                            "url": publicUrl+"/frontend/js/dataTables.german.json"
                        },
                        responsive: true,
                        processing: true,
                        serverSide: true,
                        ajax:
                        {
                            url: pastAppointmentDatataTableUrl,
                            data: 'P'
                        },
                        columns: [
                            { 
                                data: 'start',
                                render: function (data, type, row) {
                                    return row.start;
                                }, 
                                name:'start'
                            },
                            { 
                                render: function (data, type, row) {
                                    return row.coach.first_name+' '+row.coach.last_name;
                                },
                                name:'coach.first_name'
                            },
                            {
                                render: function (data, type, row) {
                                    return row.categories.title;
                                }, 
                                name:'categories.title'
                            },
                            { data: 'start_time', name:'start', searchable: false},
                            { 
                                render: function (data, type, row) {
                                    return row.user.first_name+' '+row.user.last_name;
                                }, 
                                name:'user.first_name'
                            },
                            {data: 'location', name: 'location'}
                        ],
                        deferRender: true
                    });

    function cancelSession (appointment_id, $tablerow) {
        showLoader();
        $.ajax({
            url: baseUrl+'/appointment/cancel',
            type: 'POST',
            data: {appointment_id: appointment_id, _token: $('input[name="_token"]').val()},
        })
        .done(function(data) {
            console.log("success");
            if(data.success=='true') {
                toastr.success(data.message);
                $tablerow.remove();
            }
            else
                toastr.error(data.message);
        })
        .fail(function(data) {
            console.log("error");
            console.log(data);
            toastr.error('Something unexpected occured!');
        })
        .always(function() {
            console.log("complete");
            hideLoader();
        });
    }

    function moveSession (appointment_id, coach_id, duration_min, coaching_method, mode) {
        $("#appointment_id").val(appointment_id);
        $("#coach_id").val(coach_id);
        $("#duration").val(duration_min);

        var coach_mode = '<h6 class="mb-2">Beratungsort</h6>';
        if(coaching_method=='both') {
            coach_mode+= '<p class="blk_rnd_select mode_selector '+(mode=='online'?'active':'')+' mr-1" data-val="online">Online</p>';
            coach_mode+= '<p class="blk_rnd_select mode_selector '+(mode=='offline'?'active':'')+' mr-1" data-val="offline">Offline</p>';
        } else if(coaching_method=='online') {
            coach_mode+= '<p class="blk_rnd_select mode_selector active mr-1" data-val="online">Online</p>';
        } else {
            coach_mode+= '<p class="blk_rnd_select mode_selector active mr-1" data-val="offline">Offline</p>';
        }
        $("#coaching_method").html(coach_mode);
        setAvailabilityStatuses();
        setAvailabilitySlot();
        $("#coach_booking_modal").modal('show');
    }

    $(document).on('click', '.mode_selector', function(event) {
        event.preventDefault();
        $(this).parent().find('.active').removeClass('active');
        $(this).addClass('active');
    });

    $("#updateAppointmentBtn").click(function(event) {
        var appointment_id = $("#appointment_id").val();
        var date           = $("#availabilityDatepicker").val();
        var start          = $("#available_times").val();
        var duration       = $("#duration").val();

        if(start!=undefined && start!='') {
            var slot_start  = moment(start, ["HH:mm"]);
            var slot_end    = slot_start.add(duration, 'minutes');
            var end         = slot_end.format('HH:mm');
            start           = date+' '+start+':00';
            end             = date+' '+end+':00';
            updateSession(appointment_id, start, end);
        }
        else {
            toastr.error('Please make all selection');
            return false;
        }
    });

    function updateSession (appointment_id, start, end) {
        showLoader();
        var comment = $("#update_comment").val();
        var mode    = $("#coaching_method .mode_selector.active").data('val');
        $tablerow = $("#Next_appointment_table select[data-id="+appointment_id+"]").closest('tr');
        $.ajax({
            url: baseUrl+'/appointment/move',
            type: 'POST',
            data: {
                    appointment_id: appointment_id, 
                    start: start, 
                    end: end,
                    mode: mode,
                    comment: comment,
                    _token: $('input[name="_token"]').val()
                },
        })
        .done(function(data) {
            console.log("success");
            if(data.success=='true') {
                toastr.success(data.message);

                // Update Date in table
                $tablerow.find('td:first').text($("#availabilityDatepicker").val());

                // Update Start time in table
                var this_start    = $("#available_times").val();
                var moment_start  = moment(this_start, ["HH:mm"]);
                $tablerow.find('td:nth(3)').text(moment_start.format('hh:mm A'));

                // Update Session Mode
                $tablerow.find('td:nth(5)').text(mode);

                // Close Update Modal
                $("#coach_booking_modal").modal('hide');
            }
            else
                toastr.error(data.message);
        })
        .fail(function(data) {
            console.log("error");
            console.log(data);
            toastr.error('Something unexpected occured!');
        })
        .always(function() {
            console.log("complete");
            hideLoader();
        });
    }

    /** Request appointment Module */
    $('body').on('click', '.reject_app_request', function(event) {
        event.preventDefault();
        $this = $(this);
        var request_id = $this.data('id');
        showLoader();
        $.ajax({
            url: baseUrl+'/appointment-request/'+request_id+'/decline',
            type: 'POST',
            data: {_token: $('input[name="_token"]').val()},
        })
        .done(function(data) {
            console.log("success");
            if(data.success=='true') {
                toastr.success(data.message);
                $this.parent().remove();
            } else
                toastr.error(data.message);
        })
        .fail(function(error) {
            console.log("error");
            toastr.error('Something went wrong!');
            console.log(error);
        })
        .always(function() {
            console.log("complete");
            hideLoader();
        });
    });

    $('body').on('click', '.accept_app_request', function(event) {
        event.preventDefault();
        $this = $(this);
        var request_id = $this.data('id');
        var slot_id    = $this.data('slot_id');
        showLoader();
        $.ajax({
            url: baseUrl+'/appointment-request/'+request_id+'/accept',
            type: 'POST',
            data: {_token: $('input[name="_token"]').val(), slot_id: slot_id},
        })
        .done(function(data) {
            console.log("success");
            if(data.success=='true') {
                toastr.success(data.message);
                $this.closest('.requests_container').remove();
            } else
                toastr.error(data.message);
        })
        .fail(function(error) {
            console.log("error");
            toastr.error('Something went wrong!');
            console.log(error);
        })
        .always(function() {
            console.log("complete");
            hideLoader();
        });
    });
    /** Request appointment Module End */

    /** Availabilities */

    var availDates     = {};
    var unavailDates   = {};
    var bookedDates    = {};
    var calendar_start = '';

    $("#availabilityDatepicker").datepicker({
        dateFormat: "yy-mm-dd",
        //showOtherMonths: true,
        numberOfMonths: 2,
        showButtonPanel: false,
        firstDay: 1,
        //dayNamesMin: ['S', 'M', 'T', 'W', 'T', 'F', 'S'],
        minDate: new Date(),
        maxDate: "+3M",
        onSelect: function(dateText) {
            setAvailabilitySlot();
        },
        onChangeMonthYear: function(year, month) {
            calendar_start = year+'-'+pad2(month)+'-01';
            console.log(calendar_start);

            // Reset Calendar Highlights
            setAvailabilityStatuses();
        },
        beforeShowDay: renderBeforeShowDay
    });

    var date_obj   = $('#availabilityDatepicker').datepicker('getDate');
    calendar_start = date_obj.getFullYear()+'-'+pad2(date_obj.getMonth() + 1)+'-01';

    function renderBeforeShowDay (date) {
        var this_moment_date = moment(date);
        var now_moment_date  = moment();
        if(this_moment_date.startOf('day').isBefore(now_moment_date.startOf('day')))
            return false;
        
        var is_avail   = availDates[date];
        var is_unavail = unavailDates[date];
        var is_booked  = bookedDates[date];

        /**
         * @return Array  [boolean: is selectable?, string: css class, string: tooltip message]
         */
        if( is_booked )
            //return [true, 'drk_green', 'has booking'];
            return [true, 'light_green', 'available'];
        else if(is_unavail)
            return [false, 'red', 'unavailable'];
        else if(is_avail)
            return [true, 'light_green', 'available'];
        else
            return [false, '', ''];
    }

    function setAvailabilityStatuses () {
        availDates     = {};
        unavailDates   = {};
        bookedDates    = {};

        var coach_id = $("#coach_id").val();
        if(coach_id=='')
            return false;
        $.ajax({
            url: publicUrl+'coach-available-statuses',
            type: 'GET',
            headers: {
                'X-CSRF-Token': $('input[name="_token"]').val()
            },
            data: {coach_id: coach_id, start_date: calendar_start}
        })
        .done(function(data) {
            if(data.success=="true") {
                var data = data.data;
                for (var key in data) { 
                    if (data.hasOwnProperty(key)) {
                        var date_obj = moment(key, 'YYYY-MM-DD HH:mm:ss').toDate();
                        if(data[key]=='available') {
                            availDates[ date_obj ] = date_obj;
                        } else if(data[key]=='unavailable') {
                            unavailDates[ date_obj ] = date_obj;
                        } else if(data[key]=='booked') {
                            bookedDates[ date_obj ] = date_obj;
                        }
                    }
                }

                // Re-render datepicker beforeShowDay event
                $("#availabilityDatepicker").datepicker('refresh');
            } else {
                toastr.error('Something went wrong while fetching availability status!');
            }
            console.log("success");
        })
        .fail(function() {
            console.log("error");
            toastr.error('Request failed! Please try again later.');
        })
        .always(function() {
            console.log("complete");
        });
    }

    function setAvailabilitySlot () {
        var date = $("#availabilityDatepicker").val();
        var coach_id = $("#coach_id").val();
        var duration = $("#duration").val();

        $("#availability_select_date").text(date);

        // Get available slots for this date
        $.ajax({
            url: publicUrl+'coach-available-slot',
            type: 'GET',
            data: {coach_id: coach_id, date: date},
        })
        .done(function(data) {
            console.log("success");
            if(data.max_duration<duration) {
                toastr.error('Slot based on your session duration not available for the selected date');
                $("#available_times").html('');
            } else {
                var slots = data.slots;
                var option_html = '';
                for(let i = 0, length1 = slots.length; i < length1; i++){
                    var slot_arr = slots[i].split(' ');
                    var start_time = moment(slot_arr[0], ["HH:mm"]);
                    var end_time = moment(slot_arr[1], ["HH:mm"]);

                    // If duration is less than session duration, skip this slot time
                    var slot_duration = end_time.diff(start_time, 'minutes');
                    if(slot_duration<duration)
                        continue;

                    var this_time = start_time;
                    while (this_time.isBefore(end_time)) {
                        // Skip If duration between this time and endtime is less than session duration
                        var this_duration = end_time.diff(this_time, 'minutes');
                        if(this_duration<duration)
                            break;
                        
                        var new_time = this_time.format("HH:mm");
                        option_html += '<option value="'+new_time+'" data-slot="'+slots[i]+'">'+new_time+' Uhr</option>';
                        this_time.add(30, 'm');
                    }
                }
                $("#available_times").html(option_html);
            }
        })
        .fail(function(error) {
            console.log("error");
            console.log(error);
            toastr.error('Someting went wrong while fetching availabilities!');
        })
        .always(function() {
            console.log("complete");
        });
    }

    /**
     * Pad a number to two digits
     */
    function pad2 (number) {
        return (number < 10 ? '0' : '') + number;
    }

    /** Availabilities End */

});