// Display coach availabilities to users

var AvailCalendarModule = (function(){

    // Coach Id
    var coach_id = '';

    // availability/unavailability dates
    var availDates        = {};
    var unavailDates      = {};
    var bookedDates       = {};
    var requestableDates  = {};

    // Calendar ids
    var avail_cal_id      = '';
    var unavail_cal_id    = '';
    var allowed_min_dur   = '';
    var allowed_max_dur   = '';

    function pad2 (number) {
        return (number < 10 ? '0' : '') + number;
    }

    function renderBeforeShowDay (date, is_booking) {
        var this_moment_date = moment(date);
        var now_moment_date  = moment();
        if(this_moment_date.startOf('day').isBefore(now_moment_date.startOf('day')))
            return false;
        
        var is_avail       = availDates[date];
        var is_unavail     = unavailDates[date];
        var is_booked      = bookedDates[date];
        var is_requestable = requestableDates[date];

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
        else if(is_requestable && !is_booking)
            return [true, '', 'requestable'];
        else
            return [false, '', ''];
    }

    function setAvailabilityStatuses (booking_type, calendar_start) {
        availDates       = {};
        unavailDates     = {};
        bookedDates      = {};
        requestableDates = {};

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
                            availDates[ date_obj] = date_obj;
                        } else if(data[key]=='unavailable') {
                            unavailDates[ date_obj] = date_obj;
                        } else if(data[key]=='booked') {
                            bookedDates[ date_obj] = date_obj;
                        }else {
                            requestableDates[ date_obj] = date_obj;
                        }
                    }
                }

                // Re-render datepicker beforeShowDay event
                if(booking_type=='both' || booking_type=='availablility')
                    $("#"+avail_cal_id).datepicker('refresh');
                if(booking_type=='both' || booking_type=='unavailability')
                    $("#"+unavail_cal_id).datepicker('refresh');
                //$(".datepicker").datepicker('option', 'beforeShowDay', renderBeforeShowDay);
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

    function setAvailabilitySlot (booking_type) {
        // Re-render datepicker beforeShowDay event
        if(booking_type=='both' || booking_type=='availablility') {
            var date = $("#"+avail_cal_id).val();
            var parent = $("#"+avail_cal_id).closest('.availContainer');
            $(parent).find('.avail_request.unavail_active .select_date').text(date);
            $(parent).find('.avail_request.unavail_active .select_date').data('date', date);
        }
        if(booking_type=='both' || booking_type=='unavailability') {
            var date = $("#"+unavail_cal_id).val();
            var parent = $("#"+unavail_cal_id).closest('.availContainer');
            $(parent).find('.avail_request.unavail_active .select_date').text(date);
            $(parent).find('.avail_request.unavail_active .select_date').data('date', date);
            //$(parent).find('.avail_request.unavail_active .unavail_hdate').text(date);
        }

        // Get available slots for this date
        $.ajax({
            url: publicUrl+'coach-available-slot',
            type: 'GET',
            data: {coach_id: coach_id, date: date},
        }) 
        .done(function(data) {
            console.log("success");
            var slots = data.slots;
            var av_option_html = '';
            var un_option_html = '';
            
            // Unavailability Intial slots
            var unavail_start = '04:00:00';
            if(date==moment().format('Y-M-D')) {
                var unavail_moment = moment().tz("Europe/Berlin").add(2, 'hours');
                var new_minute     = unavail_moment.format('mm');
                if(new_minute==0)
                    unavail_start  = unavail_moment.format('HH:00:00');
                else if(new_minute<30)
                    unavail_start  = unavail_moment.format('HH:30:00');
                else
                    unavail_start  = moment().tz("Europe/Berlin").add(3, 'hours').format('HH:00:00');
            }
            for(let i = 0, length1 = slots.length; i < length1; i++) {
                if((booking_type=='both' || booking_type=='availablility') && !data.only_requestable)
                    av_option_html  = setAvailabilityOptions(av_option_html, slots[i]);
                if(booking_type=='both' || booking_type=='unavailability') {
                    var slot_arr = slots[i].split(' ');
                    if(unavail_start!=slot_arr[0]) {
                        var new_slot = unavail_start+' '+slot_arr[0];
                        un_option_html  = setAvailabilityOptions(un_option_html, new_slot);
                    }
                    //unavail_start = slot_arr[1];
                }
            }
            if(booking_type=='both' || booking_type=='availablility') {
                var parent = $("#"+avail_cal_id).closest('.availContainer');
                var time_elem = $(parent).find('.avail_timeslots.unavail_active .select_time');
                $(time_elem).html(av_option_html);
                setAvailabilityDuration($(time_elem));
            }
            if(booking_type=='both' || booking_type=='unavailability') {
                // Shows only Unavailable Slots
                //un_option_html  = setAvailabilityOptions(un_option_html, unavail_start+' 23:00:00');
                // Shows unavailable as well as available slots
                un_option_html  = '';
                un_option_html  = setAvailabilityOptions(un_option_html, unavail_start+' 23:00:00');
                var parent = $("#"+unavail_cal_id).closest('.availContainer');
                var time_elem = $(parent).find('.avail_timeslots.unavail_active .select_time');
                $(time_elem).html(un_option_html);
                setAvailabilityDuration($(time_elem));
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

    function setAvailabilityOptions (option_html, slot) {
        var slot_arr = slot.split(' ');
        var start_time = moment(slot_arr[0], ["HH:mm"]);
        var end_time = moment(slot_arr[1], ["HH:mm"]);
        var this_time = start_time;
        while (this_time.isBefore(end_time) && end_time.diff(this_time, 'minutes')>=30) {
            var new_time = this_time.format("HH:mm");
            option_html += '<option value="'+new_time+'" data-slot="'+slot+'">'+new_time+' Uhr</option>';
            this_time.add(15, 'm');
        }
        return option_html;
    }

    function setAvailabilityDuration ($timeElem) {
        var $parentelem   = $timeElem.closest('.avail_request');
        var $option       = $parentelem.find('.select_time option:selected');
        var slot          = $option.data('slot');
        var duration_html = '';
        if(slot!=undefined) {
            var slot_arr   = slot.split(' ');
            var slot_end   = moment(slot_arr[1], ["HH:mm"]);
            var selected   = moment($option.val(), ["HH:mm"]);
            var time_diff  = slot_end.diff(selected, 'minutes');

            var min_duration = allowed_min_dur;
            var max_duration = allowed_max_dur;
            // if(time_diff<min_duration)
            //     return false;
            if(time_diff>max_duration)
                time_diff = max_duration;

            while (time_diff>=allowed_min_dur) {
                duration_html += '<p class="blk_rnd_select slot_selector mr-1 mb-1" data-duration="'+min_duration+'">'+min_duration+' Minuten</p>';
                min_duration = min_duration+allowed_min_dur;
                time_diff = time_diff-allowed_min_dur;
            }
        }
        $parentelem.find('.avail_duration').html(duration_html);
        if(duration_html=='')
            $parentelem.find('.avail_dur_container').hide();
        else
            $parentelem.find('.avail_dur_container').show();
    }

    function initAvailDatepicker(calendarId) {
        avail_cal_id = calendarId;
        $("#"+avail_cal_id).datepicker({
            dateFormat: "yy-mm-dd",
            //showOtherMonths: true,
            numberOfMonths: 2,
            showButtonPanel: false,
            firstDay: 1,
            //dayNamesMin: ['S', 'M', 'T', 'W', 'T', 'F', 'S'],
            minDate: new Date(),
            maxDate: "+3M",
            onSelect: function(dateText) {
                setAvailabilitySlot('availablility');
            },
            onChangeMonthYear: function(year, month) {
                var calendar_start = year+'-'+pad2(month)+'-01';
                console.log(calendar_start);

                // Reset Calendar Highlights
                setAvailabilityStatuses('availablility', calendar_start);
            },
            beforeShowDay: function(date) {
                return renderBeforeShowDay(date, true);   
            }
        });
    }

    function initUnAvailDatepicker (calendarId) {
        unavail_cal_id = calendarId;
        $("#"+unavail_cal_id).datepicker({
            dateFormat: "yy-mm-dd",
            //showOtherMonths: true,
            numberOfMonths: 2,
            showButtonPanel: false,
            firstDay: 1,
            //dayNamesMin: ['S', 'M', 'T', 'W', 'T', 'F', 'S'],
            //minDate: 1,
            minDate: new Date(),
            maxDate: "+3M",
            onSelect: function(dateText) {
                setAvailabilitySlot('unavailability');
            },
            onChangeMonthYear: function(year, month) {
                var calendar_start = year+'-'+pad2(month)+'-01';
                console.log(calendar_start);

                // Reset Calendar Highlights
                setAvailabilityStatuses('unavailability', calendar_start);
            },
            beforeShowDay: function(date) {
                return renderBeforeShowDay(date, false);   
            }
        });
    }

    function initSlotStatuses (booking_type) {
        if(booking_type=='unavailability')
            var date_obj   = $("#"+unavail_cal_id).datepicker('getDate');
        else
            var date_obj   = $("#"+avail_cal_id).datepicker('getDate');
        calendar_start = date_obj.getFullYear()+'-'+pad2(date_obj.getMonth() + 1)+'-01';
        // Set Calendar Highlights
        setAvailabilityStatuses(booking_type, calendar_start);
        setAvailabilitySlot(booking_type);
    }

    function setCoacingMode () {
        $.ajax({
            url: publicUrl+'coach-detail/'+coach_id+'?result=json',
        })
        .done(function(data) {
            console.log("success");
            var coach = data.coach;
            var coaching_method = coach.coaching_method;
            // Set coaching method
            var html   = '<h6 class="mb-2">Beratungsort</h6>';
            var is_active = ' active';
            if(coaching_method=='both' || coaching_method=='online') {
                html += '<p class="blk_rnd_select mode_selector mr-1'+is_active+'" data-val="online">Online</p>';
                is_active = '';
            }
            if(coaching_method=='both' || coaching_method=='offline') {
                html += '<p class="blk_rnd_select mode_selector mr-1'+is_active+'" data-val="offline">Offline</p>';
                is_active = '';
            }
            $(".availContainer").find('.coach_mode_container').each(function(index, el) {
                if($(el).html().trim()=="") {
                    $(el).html(html);
                }
            });
        })
        .fail(function(error) {
            console.log("error");
            toastr.error('Unexpected error occured while fetching coach details');
            console.log(error);

        })
        .always(function() {
            console.log("complete");
        });
    }

    $(document).on('click', '.choosetimeslots', function(event) {
        event.preventDefault();
        // Set inactive data for current active slot
        $curr_active = $(this).closest('.availContainer').find('.avail_request.unavail_active');
        var date     = $curr_active.find('.select_date').text();
        var time     = $curr_active.find(".select_time option:selected").val();
        var duration = $curr_active.find(".slot_selector.active").data('duration');
        duration     = duration!=undefined?duration+' Minuten':'No duration selected';
        var status   = $curr_active.find('.mode_selector.active').text();

        console.log(status);
        $curr_active.find('.unavail_hdate').text(date);
        $curr_active.find('.unavail_htime').text(time+' Uhr');
        $curr_active.find('.unavail_hduratoin').text(duration);
        $curr_active.find('.unavail_hstatus').text(status);

        /** Show hide appropriately */
        var $parent = $(this).closest('.availContainer');
        // Hide all slots editable data
        $parent.find('.unavail_inactive_container').show();
        $parent.find('.unavail_data_container').hide();

        // Show selected slots editable data
        $(this).closest('.avail_request').find('.unavail_inactive_container').hide();
        $(this).closest('.avail_request').find('.unavail_data_container').show();


        // Update active slot class appropriately
        $parent.find('.avail_request').removeClass('unavail_active');
        $(this).closest('.avail_request').addClass('unavail_active');

        var date = $(this).closest('.avail_request').find('.select_date').data('date');
        if(date=='')
            var date_obj = moment().toDate();
        else
            var date_obj = moment(date, 'YYYY-MM-DD').toDate();
        $parent.find('.datepicker').datepicker("setDate", date_obj);

        if($parent.find('.avail_request.unavail_active .select_time').html().trim()=="")
            $parent.find('.datepicker .ui-datepicker-current-day').click();
    });

    $(document).on('change', '.select_time', function(event) {
        event.preventDefault();
        setAvailabilityDuration($(this));
    });

    $(document).on('click', '.slot_selector, .mode_selector', function(event) {
        event.preventDefault();
        $(this).parent().find('.active').removeClass('active');
        $(this).addClass('active');
    });

    // Book an appointment
    $(".availContainer .bookAppmntBtn").click(function(event) {
        var $parentelem = $(this).closest('.availContainer').find('.avail_request');
        var date        = $parentelem.find('.select_date').data('date');
        var category_id = '1';
        var start       = $parentelem.find(".select_time option:selected").val();
        var duration    = $parentelem.find(".slot_selector.active").data('duration');
        var mode        = $parentelem.find('.mode_selector.active').data('val');
        var notes       = $(this).closest('.availContainer').find('.notes').val();
        var token       = $('input[name="_token"]').val();
        if(start!=undefined && start!='' && duration>=0) {
            var slot_start  = moment(start, ["HH:mm"]);
            var slot_end    = slot_start.add(duration, 'minutes');
            var end         = slot_end.format('HH:mm');
            start           = date+' '+start+':00';
            end             = date+' '+end+':00';
            console.log(start, end);
        } else {
            toastr.error('Please make all selection');
            return false;
        }
        var data        = {
                                _token:      token,
                                coach_id:    coach_id,
                                category_id: category_id,
                                start:       start,
                                end:         end,
                                mode:        mode,
                                notes:       notes
                            };
        bookAppointment(data);
    });

    function bookAppointment (data_obj) {
        showLoader();
        $.ajax({
            url: publicUrl+'book-coach',
            type: 'POST',
            data: data_obj,
        })
        .done(function(data) {
            console.log("success");
            if(data.success=='true') {
                toastr.success(data.message);
                // var appointment_id = data.appointment_id;
                // Show Booking confirmation page
                window.location.href = data.summary_url;
            } else {
                console.log(data);
                if(data.message=='Slot has booking') {
                    toastr.error('Slot has booked, please try different slot or try again later!');
                }
                else if(data.user==null) {
                    window.location.href =  publicUrl+'login';
                } else {
                    toastr.error('Something went wrong!');
                }
            }
        })
        .fail(function(error) {
            console.log("error");
            console.log(error);
            toastr.error('Some unexpected error occured!');
        })
        .always(function() {
            console.log("complete");
            hideLoader();
        });
    }

    // Request an appointment
    $(".availContainer .requestAppmntBtn").click(function(event) {
        var data      = new Array();
        var $this     = $(this);
        var $availCon = $this.closest('.availContainer');
        var error     = false;
        $availCon.find('.avail_request').each(function(index, el) {
            //var $parentelem = $(el).closest('.row');
            var $parentelem = $(el);
            var date        = $parentelem.find('.select_date').data('date');
            var start       = $parentelem.find(".select_time option:selected").val();
            var duration    = $parentelem.find(".slot_selector.active").data('duration');
            var mode        = $parentelem.find('.mode_selector.active').data('val');
            if(start!=undefined && start!='' && duration>=0) {
                var slot_start  = moment(start, ["HH:mm"]);
                var slot_end    = slot_start.add(duration, 'minutes');
                var end         = slot_end.format('HH:mm');
                start           = date+' '+start+':00';
                end             = date+' '+end+':00';
                console.log(start, end);
            } else {
                toastr.error('Please make all selection for slot '+(index+1));
                error = true;
            }
            var record  = {
                            start: start,
                            end:   end,
                            mode:  mode,
                        };
            data.push(record);
        });
        if(error)
            return false;
        var category_id  =  '1';
        var request_id   =  $availCon.find('.request_id').val();
        var request_data =  {
                                _token: $('input[name="_token"]').val(), 
                                slots: JSON.stringify(data),
                            };
        var is_suggest = false;
        if(request_id==undefined || request_id=='' || request_id<=0) {
            request_data.notes       = $availCon.find('.notes').val();
            request_data.category_id = category_id;
            request_data.coach_id    = coach_id;
        } else {
            request_data.request_id = request_id;
            is_suggest = true;
        }
        /*console.log(request_data);
        return false;*/
        if($this.closest('.modal').length>0)
            $this.closest('.modal').modal('hide');
        showLoader();
        $.ajax({
            url: publicUrl+'/request-appointment',
            type: 'POST',
            data: request_data,
        })
        .done(function(data) {
            console.log("success");
            console.log(data);
            if(data.success=='true')
                toastr.success(data.message);
            else
                toastr.error(data.message);
            if(is_suggest &&  $(".avail_request-"+request_id).length>0)
                $(".avail_request-"+request_id).remove();
        })
        .fail(function(error) {
            console.log("error");
            console.log(error);
        })
        .always(function() {
            console.log("complete");
            hideLoader();
        });
    });

    return {
        initData: function(coachId, min_dur, max_dur, set_coach_mode) {
            coach_id        = coachId;
            allowed_min_dur = min_dur;
            allowed_max_dur = max_dur;
            if(set_coach_mode)
                setCoacingMode();
        },
        initAvailCalendar: function(calendarId) {
            initAvailDatepicker(calendarId);
        },
        initUnAvailCalendar: function(calendarId) {
            initUnAvailDatepicker(calendarId);
        },
        initSlotStatus: function(booking_type) {
            initSlotStatuses(booking_type);
        }
    }

})();