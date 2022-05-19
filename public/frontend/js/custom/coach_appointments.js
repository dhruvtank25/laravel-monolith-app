var CoachAppointmentModule = (function(){

    // Initialize Datatables
    function initializeDatatable (tableId, type) {
        var datataTableUrl = baseUrl+'/datatables/appointments/'+type;
        $('#'+tableId).DataTable({
            "language": {
                "url": publicUrl+"/frontend/js/dataTables.german.json"
            },
            responsive: true,
            processing: true,
            serverSide: true,
            "lengthMenu": [5, 10, 25, 50, 100],
            pageLength: 5,
            ajax: datataTableUrl,
            columns: [
                    { name: 'start', data: 'start'},
                    { name: 'user.first_name', render: setName},
                    { name:'category_id', render: setCategory},
                    { name:'start_time', searchable: false, "orderable": false, data: 'start_time' 
                    },
                    { name:'duration', render: setDuration, searchable: false, "orderable": false},
                    { name: 'mode', data: 'mode'},
                    { name: 'status', data: 'status'},
                    { "render": getActionLinks, "orderable": false },
                ],
            deferRender: true
        });
    }

    function setName(data, type, full, meta) {
        if(full.user!=null) {
            if(!full.user.is_anonymous)
                return full.user.first_name+' '+full.user.last_name;
            else 
                return full.user.user_name;
        }
        return '-';
    }

    function setCategory(data, type, full, meta) {
        var notes = full.user_update_comment!=null?full.user_update_comment:full.notes;
        if(notes==null)
            return full.categories.title+'<i class="fa fa-envelope mail_disable" aria-hidden="true"></i>';
        else {
            return full.categories.title+'<i class="fa fa-envelope mail_enable" data-message="'+notes+'" data-toggle="tooltip" data-tooltip="'+full.notes+'" title="'+full.notes+'" aria-hidden="true"></i>';
        }
    }

    function setDuration (data, type, full, meta) {
        return full.duration+' Mins';
    }

    /** Actions */
    function getActionLinks (data, type, full, meta) {
        var table_type = meta.settings.ajax.split("/").pop();
        var action = '';
        if(full.status=='scheduled' && table_type!='past') {
            var call_active = false;
            var is_cancellable = false;
            var moment_now = moment().tz('Europe/Berlin').format('YYYY-MM-DD HH:mm:ss');
            if(moment(moment_now).isBetween(full.call_active_start, full.actual_end))
                call_active = true;
            if(moment(moment_now).isBefore(full.call_active_start))
                is_cancellable = true;
            if(full.mode=='online')
                action += '<button type="button" class="btn btn-primary actionStart" data-id="'+full.id+'" '+(call_active?'':'disabled')+'>Starten</button>';
            if(is_cancellable)
                action += '<button type="button" class="btn btn-danger actionCancel" data-id="'+full.id+'">Stornieren</button>';
        } else if(full.status=='completed') {
            //action = full.amount+'<i class="fa fa-eur" aria-hidden="true"></i>';
            //action = '<i class="fa fa-download" aria-hidden="true"></i>';
            action = '<a target="_blank" class="download_invoice" href="'+baseUrl+'/appointment/download/'+full.id+'"><i class="fa fa-download" aria-hidden="true"></i></a>';
        } else {
            action = '-';
        }
       return action;
    }

    $('body').on('click', '.actionStart', function(event) {
        event.preventDefault();
        var appointment_id = $(this).data('id');
        var win = window.open(publicUrl+'video-call/'+appointment_id, '_blank');
        win.focus();
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

    // Tooltip
    $('body').tooltip({ selector: ".mail_enable" });
    $('body').on('click', '.mail_enable', function(event) {
        event.preventDefault();
        var message = $(this).data('message');
        $("#booking_messsage_popup .modal-body p").html(message);
        $("#booking_messsage_popup").modal('show');
    });

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

    return {
        initDataTable: function(tableId, type) {
            initializeDatatable (tableId, type);
        },
    }

})();