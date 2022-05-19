$(function(){

    /** Set Datatable to user lesson table */
    //$("#user-lesson-table").DataTable({responsive: true,});

});

function showUserInfo (user_id) {
    $.ajax({
        url: baseUrl+'/users/'+user_id,
    })
    .done(function(data) {
        console.log("success");

        /** append response in modal */
        $("#user_modal .modal-body").html(data.html);

        /** show modal */
        $('#user_modal').modal('show');

        /** Set Modal header data */
        $('#user_modal .user_title').text(data.user.first_name+' '+data.user.last_name);
        var status = data.user.status;
        if(status=="active"){
            $('#user_modal .user_status').html('<label class="label label-theme">'+status+'</label>');
        }else if(status=="inactive"){
            $('#user_modal .user_status').html('<label class="label label-danger">'+status+'</label>');
        }else {
            $('#user_modal .user_status').html('<label class="label label-info">'+status+'</label>');
        }
        $('#user_modal .user_role').text(data.user.roles.name);

        /** Set Datatable to user lesson table */
        var apiurl = baseUrl+'/appointment/datatables';
        if(data.user.roles.name=='coach')
            apiurl += '?coach_id='+user_id;
        else
            apiurl += '?user_id='+user_id;
        initializeLessonTable(apiurl);

        /** Set Datatable to user transaction table */
        var transapiurl = baseUrl+'/user-transactions/datatables/'+user_id;
        initializeUserTransactionTable(transapiurl, user_id);
    })
    .fail(function() {
        console.log("error");
    })
    .always(function() {
        console.log("complete");
    });
}

function initializeLessonTable (apiurl) {
    $('#user-lesson-table').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: apiurl,
                columns: [{data:'id', name:'id'},
                        { 
                            data: 'start',
                            render: function (data, type, row) {
                                return row.start;
                            }, 
                            name:'start'
                        },
                        { data: 'start_time', name:'start', searchable: false},
                        { data: 'end', name:'end', searchable: false},
                        {
                            render: function (data, type, row) {
                                return row.user.first_name+' '+row.user.last_name;
                            }, 
                            name:'user.first_name'
                        },
                        { 
                            render: function (data, type, row) {
                                return row.coach.first_name+' '+row.coach.last_name;
                            },
                            name:'coach.first_name'
                        },
                        {data: 'status', name: 'status'}
                    ],
                deferRender: true
            });
}

function initializeUserTransactionTable (apiurl, user_id) {
    $('#user-transaction-table').DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        ajax: apiurl,
        columns: [
                {   data: 'transaction_id', name: 'transaction_id'},
                {   data: 'appointment_id', name:'appointment_id'},
                {   render: function (data, type, row) {
                                if(row.type=='TRANSFER')
                                    return row.debited_user_id==user_id?'Debit (Wallet)':'Credit (Wallet)';
                                else if(row.type=='PAYOUT')
                                    return 'Credit';
                                else
                                    return row.debited_user_id==user_id?'Debit':'Credit';
                            }, 
                    name:'debit',
                    searchable:false,
                    sortable:false
                },
                {   data: 'credited_amount', name:'credited_amount'},
                {   data: 'fees', name:'fees'},
                {   data: 'type', name:'type'},
                {   data: 'created_at', name:'created_at'},
                {   data: 'status', name:'status'},
                {   data: 'result_message', name:'result_message'},
            ],
        order: [[6, "desc"]],
        deferRender: true
    });
}