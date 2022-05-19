var AvailabilityModule = (function() {

    /** Set toastr options */
    toastr.options = {
            "closeButton": true,
            "positionClass": "toast-top-full-width",
            "timeOut": "0",
            "extendedTimeOut": "0",
            //"progressBar": true,
            "toastClass": "toastr", // to fix bootstrap 4.2 full width issue; 
        };

    /** Availability */
    
    var availDates     = {};
    var unavailDates   = {};
    var bookedDates    = {};
    var calendar_start = '';
    var last_range_start = null;
    var last_range_end   = null;
    //availDates[ new Date( '2019-07-20 00:00:00' )] = new Date( '2019-07-20 00:00:00' );

    function initUpdateAvailability() {

        // Initialize jquery ui datepicker
        initCalendar();

        // Set Calendar Highlights
        setAvailabilityStatuses();

        // Set slots for a day
        setAvailabilitySlot();

        // Bootstrap Range Slider

        $(document).on('slide', '.period_range_slider', function(slideEvt) {
            slideEvt.preventDefault();
            setSlideValues($(this), slideEvt);
        });

        $(document).on('slideStop', '.period_range_slider', function(slideEvt) {
            console.log('slide stop');
            if(setSlideValues($(this), slideEvt))
                setSlotUpdateType($(this).closest('.availability'));
            else
                setBackSlider($(this));
        });

        function setSlideValues ($this, slideEvt) {
            var sliderparentElem = $this.parent();
            var new_range = slideEvt.value;
            if(new_range[0]==new_range[1])
                return false;

            console.log('values before click', last_range_start, last_range_end);
            // Set previous times to help reset in case user selectes cancel
            if(last_range_start==null && last_range_end==null) {
                last_range_start = convertTimeToNumber($(sliderparentElem).find('.period_from').data('time'));
                last_range_end   = convertTimeToNumber($(sliderparentElem).find('.period_to').data('time'));
            }
            console.log('values set', last_range_start, last_range_end);

            var start_time = convertNumberToTime(new_range[0]);
            var end_time   = convertNumberToTime(new_range[1]);

            $(sliderparentElem).find('.period_from').text(start_time+' Uhr').data('time', start_time);
            $(sliderparentElem).find('.period_to').text(end_time+' Uhr').data('time', end_time);
            return true;
        }

        // Availability Functionality
        $('.add_period').click(function() {
            var total_availabilities = $("#availability_div .availability").length;
            if(total_availabilities>=4) {
                toastr.error('You can create atmost 4 slots only', 'Error');
                return false;
            } else if(total_availabilities==0) {
                $("#availability_div").html('');
            }
            //$('.period_hidden').slideDown();
            $("#availability_div").append($("#availabilities_skeleton").html());
            $(".period_range_slider:last").bootstrapSlider({
                min: 04,
                max: 23,
                step: 0.25,
                value: [00, 23]
            });

            // Set appropriate data
            setAvailabilityAttr();
        });

        var deleted_id_arr = [];
        $(document).on('click', '.delete_period', function(event) {
            event.preventDefault();
            $this = $(this);
            var $parent = $this.closest('.availability');
            if($parent.data('id')!=0) {
                var delete_obj = {id:$parent.data('id'), recurring: $parent.data('recurring')};
                if($parent.data('recurring')!='single') {
                    // Ask to remove for current date or all future date
                    console.log('stop recurring or stop for selected date only?');
                    var dialog = bootbox.dialog({
                        closeButton: false,
                        title: 'Delete Slot',
                        message: "<p>Do you want to delete for future as well?</p>",
                        size: 'large',
                        buttons: {
                            cancel: {
                                label: "Do nothing",
                                className: 'btn-danger',
                                callback: function() {
                                    console.log('dont delete anything');
                                }
                            },
                            noclose: {
                                label: "Delete selected Availability",
                                className: 'btn-warning',
                                callback: function() {
                                    delete_obj['stop_recurring'] = false;
                                    deleted_id_arr.push(delete_obj);
                                    removeSlot($this);
                                    console.log('delete current');
                                }
                            },
                            ok: {
                                label: "Delete Future Availability",
                                className: 'btn-info',
                                callback: function() {
                                    delete_obj['stop_recurring'] = true;
                                    deleted_id_arr.push(delete_obj);
                                    removeSlot($this);
                                    console.log('delete future');
                                }
                            }
                        }
                    });
                } else {
                    deleted_id_arr.push(delete_obj);
                    removeSlot($this);
                }
            } else {
                removeSlot($this);
            }
            console.log(deleted_id_arr);
        });

        function removeSlot ($elem) {
            $elem.closest('.availability').remove();
            if($("#availability_div .availability").length==0)
                clearSlots(false);
            else
                setAvailabilityAttr();
        }

        // Set or unset recurring
        $(document).on('click', '.series_switch', function(event) {
            if($(this).prop("checked") == true)
                $(this).closest('.availability').find('.term_wrapper').show();
            else if($(this).prop("checked") == false)
                $(this).closest('.availability').find('.term_wrapper').hide();

            setSlotUpdateType($(this).closest('.availability'));
        });

        $('#save_period_btn').click(function(event) {
            var date = $(".datepicker").val();
            var slot_arr = [];
            var is_valid = true;
            var err_msg  = '';
            // validate dates
            $('#availability_div .availability').each(function(index, el) {
                var $this = $(this);
                var start_time = $this.find('.period_from').data('time');
                var end_time = $this.find('.period_to').data('time');
                /* Check for slot conflicts
                    for(let i = 0, length1 = slot_arr.length; i < length1; i++) {
                        if(!(end_time<slot_arr[i]['time_from'] || start_time>slot_arr[i]['time_to'])) {
                            is_valid =false;
                            err_msg += 'Slot '+(index+1)+': Time conflict with slot '+(i+1)+' <br>';
                        }
                    }
                */

                // Check for series related data
                var is_series   = $this.find('.period_switch').is(':checked');
                var series_type = $this.find('input[type=radio]:checked').val();
                var for_week    = $this.find('input[type=number]').val();

                var id          = $this.data('id');
                var recurring   = 'single';

                if(is_series) {
                    if(!series_type)
                        err_msg += 'Slot '+(index+1)+': Series type not selected <br>';
                    else if(isNaN(for_week) || for_week<2)
                        err_msg += 'Slot '+(index+1)+': Invalid no of week! <br>';
                    else
                        recurring = series_type;
                }

                var update_recurring = $this.data('update_recurring')==undefined?true:$this.data('update_recurring');
                slot_arr.push({
                                id: id,
                                status: 'available', 
                                date_on: id>0?$this.data('date_on').split(' ')[0]:date,  
                                time_from: start_time, 
                                time_to: end_time, 
                                recurring: recurring, 
                                recurring_weeks: is_series && series_type=='weekly' ? for_week:0,
                                update_recurring: update_recurring
                            });
            });
            if(err_msg=='') {
                if(slot_arr.length==0) {
                    slot_arr.push({
                                    status: 'unavailable', 
                                    date_on: date, 
                                    time_from: '00:00:00', 
                                    time_to: '23:59:59',
                                    recurring: 'single',
                                    recurring_end: date
                                });
                }

                var availability   = slot_arr;
                var coach_id       = $("#id").val();
                
                $.ajax({
                    url: baseUrl+'/availability',
                    type: 'POST',
                    headers: {
                        'X-CSRF-Token': $('input[name="_token"]').val()
                    },
                    data: {
                            selected_date: date, 
                            availabilities: availability, 
                            delete_ids: deleted_id_arr, 
                            coach_id: coach_id
                        },
                })
                .done(function(data) {
                    console.log("success");
                    if(data.success=="true"){

                        // Set Calendar Highlights
                        setAvailabilityStatuses();

                        // Set slots for a day
                        setAvailabilitySlot();

                        toastr.success('Availability updated');
                    }else{
                        if(data.message)
                            toastr.error(data.message)
                        else
                            toastr.error('Something went wrong!');
                    }
                })
                .fail(function(data) {
                    console.log("error");
                    toastr.error('Request failed! Please try again later.');
                })
                .always(function() {
                    console.log("complete");
                });
            }
            else {
                toastr.error(err_msg, 'Please fix following Error');
            }
        });

        $('#clear_period_btn').click(function(event) {
            clearSlots(true);
        });
    }

    function initCalendar() {
        $(".datepicker").datepicker({
            dateFormat: "yy-mm-dd",
            //altField: "#availability_select_date", //An input element to be updated with the selected date
            //showOtherMonths : true, // display dates in other months at start/end of current month
            numberOfMonths: 2, // to show two months at a time
            //stepMonths: 2, // how many months to move when clicking the previous/next links.
            minDate: 0, // disables previous dates, 1 to disable previous date including today
            //maxDate: "+2M", // disable future dates
            showButtonPanel: false, // hides today button at the bottom
            firstDay: 1,    // Weeks starts with Monday instead of Sunday
            //dayNamesMin: ['S', 'M', 'D', 'M', 'D', 'F', 'S'],
            //dayNamesMin: ['S', 'M', 'T', 'W', 'T', 'F', 'S'],
            //monthNames: [ "Januar", "Februar", "März", "April", "Mai", "Juni", "Juli", "August", "September", "Oktober", "November", "Dezember" ],
            onSelect: function(dateText) {
                // Write functionality to get all the slots already created for this date
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

        var date_obj   = $('.datepicker').datepicker('getDate');
        calendar_start = date_obj.getFullYear()+'-'+pad2(date_obj.getMonth() + 1)+'-01';
        console.log(calendar_start);
    }

    function renderBeforeShowDay (date) {
        var is_avail   = availDates[date];
        var is_unavail = unavailDates[date];
        var is_booked  = bookedDates[date];
        /**
         * @return Array  [boolean: is selectable?, string: css class, string: tooltip message]
         */
        if( is_booked )
            return [true, 'drk_green', 'has booking'];
        else if(is_unavail)
            return [true, 'red', 'unavailable'];
        else if(is_avail)
            return [true, 'light_green', 'available'];
        else
            return [true, '', ''];
    }

    /**
     * Pad a number to two digits
     */
    function pad2 (number) {
        return (number < 10 ? '0' : '') + number;
    }

    function convertNumberToTime (time_val) {
        var number_arr = time_val.toFixed(2).split('.');
        var hour       = pad2(number_arr[0]);
        var minute     = pad2(60/(100/number_arr[1]));
        return hour+':'+minute;
    }

    function convertTimeToNumber (time) {
        var time_arr = time.split(':');
        var hour     = time_arr[0];
        var minute   = 100/(60/time_arr[1]);
        var number   = hour+'.'+minute
        return number*1;
    }

    function setBackSlider ($slideElem) {
        $slideElem.bootstrapSlider('setValue', [last_range_start, last_range_end], true, false);
        unsetPrevSliderVal();
    }

    function unsetPrevSliderVal() {
        last_range_start = null;
        last_range_end   = null;
    }
        
    /**
     * Check for update whether to update for this date or for future as well
     * @param Element $elem
     */
    function setSlotUpdateType ($elem) {
        if($elem.data('update_recurring')==undefined && $elem.data('id')!=0 && $elem.data('recurring')!='single') {
            // Check if update needs to be reflected in future availabilies as well
            console.log($elem.data('update_recurring'));
            var dialog = bootbox.dialog({
                closeButton: false,
                title: 'Update Slot',
                message: "<p>Do you want to update for future as well?</p>",
                size: 'large',
                buttons: {
                    cancel: {
                        label: "Do nothing",
                        className: 'btn-danger',
                        callback: function() {
                            if(last_range_start!=null && last_range_end!=null)
                                setBackSlider($elem.find('.period_range_slider'));
                            else {
                                // Reset series checkbox to previous state
                                var is_on = $elem.find('.period_switch').is(':checked');
                                if(is_on) {
                                    $elem.find('.period_switch').prop('checked', false);
                                    $elem.find('.term_wrapper').hide();
                                } else {
                                    $elem.find('.period_switch').prop('checked', true);
                                    $elem.find('.term_wrapper').show();
                                }
                            }
                            console.log('change future?' ,$elem.data('update_recurring'));
                        }
                    },
                    noclose: {
                        label: "Update selected Availability",
                        className: 'btn-warning',
                        callback: function() {
                            // Set series checkbox state to uncheck
                            $elem.find('.period_switch').prop('checked', false);
                            $elem.find('.term_wrapper').hide();

                            // Set change recurring false
                            $elem.data('update_recurring', false);
                            console.log('change future?' ,$elem.data('update_recurring'));

                            // Unset previous time
                            unsetPrevSliderVal();
                            //return false; // do not close popup
                        }
                    },
                    ok: {
                        label: "Update Future Availability",
                        className: 'btn-info',
                        callback: function() {
                            $elem.data('update_recurring', true);
                            console.log('change future?' ,$elem.data('update_recurring'));

                            // Unset previous time
                            unsetPrevSliderVal();
                        }
                    }
                }
            });
        } else {
            // Unset previous time
            unsetPrevSliderVal();
        }
    }

    function clearSlots (clearDeletion) {
        if(clearDeletion)
            deleted_id_arr = [];
        $("#availability_div").html('<p class="text-center">Keine Verfügbarkeiten für diesen Tag hinterlegt</p>');
    }

    function setAvailabilityAttr () {
        $('#availability_div .availability').each(function(index, el) {
            var $this = $(this);
            var this_index = index+1;

            // Set Availability No
            $this.find('.availability_no').text(this_index);

            // Create radion group by setting radion name
            $this.find('input[type=radio]').attr('name', 'series'+this_index);

            // All day series radio
            $this.find('.alldayseries input[type=radio]').attr({
                name: 'series'+this_index,
                id: 'alldayseries'+this_index
            });
            $this.find('.alldayseries label').attr('for', 'alldayseries'+this_index);

            // Week day series radio
            $this.find('.weekdayseries input[type=radio]').attr({
                name: 'series'+this_index,
                id: 'weekdayseries'+this_index
            });
            $this.find('.weekdayseries label').attr('for', 'weekdayseries'+this_index);
        });
    }

    /**
     * ### NOT USED ANYMORE AS OVERLAPPING will result in slot extention now on. ###
     * Checks if slot are getting overlapped and 
     * in case of overlap only 1 slot is kept based on preference.
     */
    function checkForConflicts (date, start_date, recurring, start_time, end_time) {
        var delete_slots = [];
        var save_slot    = true;
        for(let i = $('#availability_div .availability').length-1; i >= 0; i--) {
            var $availabilty = $('#availability_div .availability:nth('+i+')');
            var prev_date    = $availabilty.data('date_on');
            var prev_type    = $availabilty.data('recurring');
            var prev_start   = $availabilty.find('.period_from').data('time');
            var prev_end     = $availabilty.find('.period_to').data('time');
            console.log(prev_date, prev_type, prev_start, prev_end);

            if(start_time <= prev_end) {
                console.log("Slot conflict", start_time, end_time);
                if(start_date==date || recurring==prev_type) {
                    // Same date start will always have a higher preference
                    if(start_date>prev_date) {
                        // Delete previous Slot
                        // Always here for start_date==date
                        // as prev and current slot on same date cannot conflict
                        console.log('This slot stays!');
                        // $('.availability:last').remove();
                        delete_slots.push($availabilty);
                        // Will have to check again for new previous slot as well
                    } else {
                        save_slot = false; // skip slot
                        break;
                    }
                }
                else if(prev_type=='single') {
                    // Skip this slot
                    // Previous slot is single while current is not single
                    // Single will always have a higher preference
                    save_slot = false; // skip slot
                    break;
                }
                else if(recurring=='weekly') {
                    // Delete prev slot
                    console.log('This slot stays!');
                    delete_slots.push($availabilty);
                    //$('.availability:last').remove();
                }
                else {
                    // Prev is weekly
                    save_slot = false; // skip slot
                    break;
                }
            }
        }

        if(save_slot) {
            for(let i = 0, length1 = delete_slots.length; i < length1; i++){
                delete_slots[i].remove();
                //$('.availability:last').remove();
            }
        }
        return save_slot;
    }

    function setAvailabilitySlot () {
        var date = $(".datepicker").val();
        $("#availability_select_date").text(date);
        clearSlots(true);
        var coach_id = $("#id").val();
        $.ajax({
            url: baseUrl+'/availability/get-slots',
            type: 'POST',
            headers: {
                'X-CSRF-Token': $('input[name="_token"]').val()
            },
            data: {coach_id: coach_id, date:date},
        })
        .done(function(data) {
            if(data.success=="true") {
                var availabilities = data.availabilities;
                if(availabilities.length>0) {
                    $("#availability_div").html('');
                    for(let i = 0, length1 = availabilities.length; i < length1; i++) {

                        if(availabilities[i].status=='unavailable') {
                            clearSlots(true);
                            break;
                        }

                        // Set Selected Time
                        var start_time = availabilities[i].time_from.slice(0,-3);
                        var end_time   = availabilities[i].time_to.slice(0,-3);
                        var recurring  = availabilities[i].recurring;
                        var start_date = availabilities[i].date_on;

                        //var isSave = checkForConflicts(date, start_date, recurring, start_time, end_time);
                        var isSave = true;
                        if(isSave) {
                            // Add slot
                            $("#availability_div").append($("#availabilities_skeleton").html());

                            // Quick fix for same attrs
                            $('#availability_div .alldayseries:last input[type=radio]').attr('name', 'series_temp'+i);
                            $('#availability_div .weekdayseries:last input[type=radio]').attr('name', 'series_temp'+i);

                            // Set Selected Time
                            $('#availability_div .period_from:last').text(start_time+' Uhr').data('time', start_time);
                            $('#availability_div .period_to:last').text(end_time+' Uhr').data('time', end_time);
                            
                            // Set Time Slider
                            var start_val = convertTimeToNumber(start_time);
                            var end_val   = convertTimeToNumber(end_time);
                            $("#availability_div .period_range_slider:last").bootstrapSlider({
                                min: 04,
                                max: 23,
                                step: 0.25,
                                value: [start_val, end_val]
                            });

                            // Set recurring data
                            if(recurring!='single') {
                                $('#availability_div .period_switch:last').click();
                                var series_from = start_date.split(' ')[0];
                                var series_to   = availabilities[i].recurring_end;
                                series_to       = series_to!=null?series_to.split(' ')[0]:'&#8734;';
                                var series_lbl  = ' Serie from '+series_from+' ohne '+series_to;
                                series_lbl = '';
                                $('#availability_div .series_from:last').html(series_lbl);
                                if(recurring=='daily') {
                                    $('#availability_div .alldayseries:last input[type=radio]').prop('checked', true);
                                } else {
                                    $('#availability_div .weekdayseries:last input[type=radio]').prop('checked', true);
                                    $('#availability_div input[type=number]:last').val(availabilities[i].recurring_weeks);
                                }
                            }

                            // Set record data
                            $('#availability_div .availability:last').data('id', availabilities[i].id);
                            $('#availability_div .availability:last').data('recurring', recurring);
                            $('#availability_div .availability:last').data('date_on', start_date);
                        }

                    }
                    // Final fix for attrs
                    setAvailabilityAttr();
                }
            } else {
                toastr.error('Something went wrong!');
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

    function setAvailabilityStatuses () {
        availDates     = {};
        unavailDates   = {};
        bookedDates    = {};

        var coach_id   = $("#id").val();
        $.ajax({
            url: baseUrl+'/availability/get-statuses',
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
                $(".datepicker").datepicker('refresh');
                //$(".datepicker").datepicker('option', 'beforeShowDay', renderBeforeShowDay);
            } else {
                toastr.error('Something went wrong whilte fetching availability status!');
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

    /** Availability End */

    /** Unavailability Start */

    function initUnavailability () {
        var coach_id = $("#id").val();
        var unavailabilityTable = '';
        $("#from_date").datepicker({
            dateFormat: "yy-mm-dd",
            placeholder: 'MM/DD/YYYY',
            minDate: new Date(),
            firstDay: 1,
            onSelect: function(dateText) {
                // Check for a valid date
                $('#to_date').datepicker( "option", "minDate", new Date(dateText));
                if($('#to_date').val()<dateText) {
                    $('#to_date').val(dateText);
                }
            },
        }).datepicker("setDate", new Date());
        
        $("#to_date").datepicker({
            dateFormat: "yy-mm-dd",
            minDate: new Date(),
            firstDay: 1
        }).datepicker("setDate", new Date());

        unavailabilityTable =   $('#unavailability_table').DataTable({
                                "language": {
                                    "url": publicUrl+"/frontend/js/dataTables.german.json"
                                },
                                responsive: true,
                                processing: true,
                                serverSide: true,
                                ajax: baseUrl+'/coaches/unavailabilities/'+coach_id,
                                columns: [
                                    { 
                                        data: 'date_on', 
                                        render: function (data, type, row) {
                                            return row.date_on.split(' ')[0];
                                        },
                                        name:'date_on'
                                    },
                                    { 
                                        data: 'recurring_end', 
                                        render: function (data, type, row) {
                                            return row.recurring_end.split(' ')[0];
                                        },
                                        name:'recurring_end'
                                    },
                                    {
                                        "render": function (data, type, full, meta)
                                        { 
                                            return '<button type="button" class="btn orange_background_btn deleteunavailablebtn" data-id="'+full.id+'">Remove</button>';
                                        }
                                    },
                                ],
                                deferRender: true
                                });

        $(document).on('click', '.deleteunavailablebtn', function(event) {
            event.preventDefault();
            var id        = $(this).data('id');
            var this_elem = $(this).closest('tr');
            $.ajax({
                url: baseUrl+'/coaches/unavailabilities/'+id,
                type: 'POST',
                data: {_method: 'DELETE',_token: $('input[name="_token"]').val(),coach_id:coach_id},
            })
            .done(function(data) {
                console.log("success");
                if(data.success=="true"){
                    $(this_elem).remove();
                    toastr.success('Unavailbility removed succesfully');
                }else{
                    toastr.error('Something went wrong!');
                }
            })
            .fail(function() {
                console.log("error");
            })
            .always(function() {
                console.log("complete");
            });
        });

        $("#saveUnavilabilityBtn").click(function(event) {
            event.preventDefault();
            var unavailable_from = $("#from_date").val();
            var unavailable_to   = $("#to_date").val();
            if(unavailable_from=='' || unavailable_to=='' || unavailable_from>unavailable_to) {
                toastr.error('Please select valid FROM and TO date');
            }else{
                $.ajax({
                    url: baseUrl+'/coaches/unavailabilities',
                    type: 'POST',
                    data: {
                            _token: $('input[name="_token"]').val(), 
                            coach_id: coach_id,
                            unavailable_from: unavailable_from,
                            unavailable_to: unavailable_to
                        },
                })
                .done(function(data) {
                    console.log("success");
                    if(data.success=='true'){
                        toastr.success(data.message);
                        resetUnavailability();
                    }else{
                        toastr.options = {
                            timeOut: 0,
                            extendedTimeOut: 0
                        };
                        toastr.error(data.message);
                    }
                })
                .fail(function(data) {
                    console.log("error");
                })
                .always(function() {
                    console.log("complete");
                });
            }
        });

        $("#resetUnavailabilityBtn").click(function(event) {
            resetUnavailability();
        });

        function resetUnavailability () {
            $("#from_date, #to_date").val('');
            $("#from_date, #to_date").datepicker( "option", {minDate: new Date()});
            unavailabilityTable.ajax.reload();
        }
    }

    /** Unavailability End */

    return {
        initUpdateAvailability: initUpdateAvailability,
        initUnavailability: initUnavailability,
        initCalendar: initCalendar,
        setAvailabilityStatuses: setAvailabilityStatuses,
    };

})();