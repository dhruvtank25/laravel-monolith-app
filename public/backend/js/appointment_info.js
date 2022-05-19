$(function(){

    /** Add Note */
    $(document).on('click', '#add_note_btn', function(event) {
        event.preventDefault();
        var note = $("#add_note_txt").val();
        var id   = $("#appointment_id").val();
        if(note==''){
            toastr.error('Please add a note');
            return false;
        }else{
            $.ajax({
                url: baseUrl+'/appointment/add-note/'+id,
                type: 'POST',
                data: {_token: $('input[name="_token"]').val(), note: note},
            })
            .done(function(data) {
                console.log("success");
                if(data.success=='true'){
                    var html = '<li>'+
                                    '<div class="timeline-icon">'+
                                        '<a href="javascript:;">&nbsp;</a>'+
                                    '</div>'+
                                    '<div class="timeline-body">'+
                                        '<div class="timeline-header">'+
                                            '<span class="username">Notes</span>'+
                                            '<span class="pull-right text-muted"><i class="fa fa-clock"></i> 1 seconds ago</span>'+
                                        '</div>'+
                                        '<div class="timeline-content">'+
                                            '<p>'+note+'</p>'+
                                        '</div>'+
                                    '</div>'+
                                '</li>';
                    $(".timeline").prepend(html);
                    $("#add_note_txt").val('');
                    toastr.success('Note added successfully');
                }else{
                    toastr.error('Something went wrong');
                }
            })
            .fail(function() {
                console.log("error");
            })
            .always(function() {
                console.log("complete");
            });
            
        }
    });

});

function show_appointment_info (id) {

    $.ajax({
        url: baseUrl+'/appointment/'+id,
    })
    .done(function(data) {
        console.log("success");

        /** append response in modal */
        $("#schedule_modal .modal-body").html(data.html);

        /** show modal */
        $('#schedule_modal').modal('show');

        /** Set Modal header data */
        var status = data.appointment.status;
        if(status=="completed"){
            $('#schedule_modal .appointment_status').html('<label class="label label-theme">'+status+'</label>');
        }else if(status=="cancelled"){
            $('#schedule_modal .appointment_status').html('<label class="label label-danger">'+status+'</label>');
        }else {
            $('#schedule_modal .appointment_status').html('<label class="label label-info">'+status+'</label>');
        }
        var start = moment(data.appointment.start, 'YYYY-MM-DD hh:mm:ss');
        var end   = moment(data.appointment.end, 'YYYY-MM-DD hh:mm:ss');
        var calendar_icon = '<i class="fa fa-calendar-alt"></i> ';
        var time_icon     = '<i class="fa fa-clock"></i> ';
        var header_time = 'On '+calendar_icon+start.format('dddd, DD MMM')+' From '+time_icon+start.format('hh:mm A')+' To '+time_icon+end.format('hh:mm A');
        $('#schedule_modal .appointment_time').html(header_time);

        /** Set Datatable to user transaction table */
        var transapiurl = baseUrl+'/appointment-transactions/datatables/'+id;
        initializeAppTransactionTable(transapiurl);
    })
    .fail(function() {
        console.log("error");
    })
    .always(function() {
        console.log("complete");
    });
    
}

function initializeAppTransactionTable (apiurl) {
    $('#appointment-transaction-table').DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        ajax: apiurl,
        columns: [
                {   data: 'transaction_id', name: 'transaction_id'},
                {   data: 'appointment_id', name:'appointment_id'},
                {   data: 'credited_amount', name:'credited_amount'},
                {   data: 'fees', name:'fees'},
                {   data: 'type', name:'type'},
                {   data: 'created_at', name:'created_at'},
                {   data: 'status', name:'status'},
                {   data: 'result_message', name:'result_message'},
            ],
        order: [[5, "desc"]],
        deferRender: true
    });
}