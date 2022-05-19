$(function(){

    var userDataTable;

    var column_arr = [
                        //{ data: 'id', bVisible:false },
                        { "render": setAvatarRow, "sClass":  "with-img" },
                        { "render": setName, name:'first_name'},
                        { data: 'last_name', name:'last_name'},
                        { data: 'email', name:'email'},
                        { "render": setStatus, name:'status'},
                    ];
    if(datataTableUrl.indexOf('datatables/coach')!== -1) {
        column_arr.push({ 
                            data: 'kyc_status', 
                            render: function (data, type, full, meta) {
                                return full.kyc_status;
                            }, 
                            name:'kyc_status'
                        });
        column_arr.push({ 
                            data: 'ubo_status',
                            render: function (data, type, full, meta) {
                                if(full.person_type=='business')
                                    return full.ubo_status;
                                return '-';
                            },
                            name:'ubo_status'
                        });
    }
    column_arr.push({ "render": getActionLinks });

    userDataTable = $('#users_table').DataTable({
                        responsive: true,
                        processing: true,
                        serverSide: true,
                        ajax: datataTableUrl,
                        columns: column_arr,
                        deferRender: true
                    });

    function setAvatarRow(data, type, full, meta) {
        var html = '<img src="'+s3Url+'users/avatar/'+full.avatar+'" class="img-rounded height-30" />';
        return html;
    }

    function setName(data, type, full, meta) {
        return '<a href="javascript:void(0);" onclick="showUserInfo('+full.id+')">'+full.first_name+'</a>';
    }

    /** Datatable action column */
    function getActiveActionHtml(id, role_name, status) {
        var action = '';
        if(status=='kyc pending')
            action += '<a class="btn btn-sm btn-success m-r-2" target="_blank"  href="' + publicUrl + 'initiate-hook?type=kyc&coach_id=' + id + '"><i class="fa fa-sync"></i></a>';
        if(status=='ubo pending')
            action += '<a class="btn btn-sm btn-success m-r-2" targer="_blank"  href="' + publicUrl + 'initiate-hook?type=ubo&coach_id=' + id + '"><i class="fa fa-sync"></i></a>';
        if(status=='approval') {
            action += '<a class="btn btn-sm btn-success m-r-2 updateStatus" data-newstatus="active" data-userid="' + id + '" data-user-role="'+role_name+'" data-status="'+status+'" href="' + baseUrl + '/coaches/availability/' + id + '"><i class="fa fa-check"></i> Approve</a>';
            action += '<a class="btn btn-sm btn-danger m-r-2 updateStatus" data-newstatus="incomplete" data-userid="' + id + '" data-user-role="'+role_name+'" data-status="'+status+'" href="' + baseUrl + '/coaches/availability/' + id + '"><i class="fa fa-window-close"></i> Reject</a>';
        }
        if(role_name=='coach') {
            action += '<a class="btn btn-sm btn-success m-r-2 viewUser" href="' + baseUrl + '/messages?coach_id=' + id + '"><i class="fa fa-envelope"></i> Messages</a>';
            action += '<a class="btn btn-sm btn-success m-r-2 viewUser" href="' + baseUrl + '/coaches/availability/' + id + '"><i class="fa fa-calendar"></i> Availability</a>';
            action += '<a class="btn btn-sm btn-info m-r-2 viewUser" href="' + baseUrl + '/coaches/' + id + '"><i class="fa fa-eye"></i> View</a>';
            action += '<a class="btn btn-sm btn-primary m-r-2 editUser" href="' + baseUrl + '/coaches/'+id+'/edit" class=""><i class="fa fa-edit"></i> Edit</a>';
        } 
        else if(role_name=='guest') {
            action += '<a class="btn btn-sm btn-info m-r-2 viewUser" href="' + baseUrl + '/guests/' + id + '"><i class="fa fa-eye"></i> View</a>';
        }
        else{
            action += '<a class="btn btn-sm btn-info m-r-2 viewUser" href="' + baseUrl + '/users/' + id + '"><i class="fa fa-eye"></i> View</a>';
            action += '<a class="btn btn-sm btn-primary m-r-2 editUser" href="' + baseUrl + '/users/'+id+'/edit" class=""><i class="fa fa-edit"></i> Edit</a>';
        }
        action += '<a href="' + baseUrl + '/users/disable/' + id + '" class="btn btn-sm btn-warning m-r-2 updateStatus" data-newstatus="inactive" data-userid="' + id + '" data-user-role="'+role_name+'" data-status="'+status+'"><i class="fa fa-ban"></i> Block</a>';
        action += '<a href="javascript:void(0)" class="btn btn-sm btn-danger m-r-2 delUser" data-userid="' + id + '"><i class="fa fa-trash-alt"></i> Delete</a>';
        return action;
    }

    function inactiveActionHtml(id, role_name) {
        var action = '';
        action += '<a href="' + baseUrl + '/users/enable/' + id + '" class="btn btn-sm btn-success m-r-2 updateStatus" data-newstatus="incomplete" data-userid="' + id + '" data-user-role="'+role_name+'" data-status="'+status+'"><i class="fa fa-lock-open"></i> UnBlock</a>';
        action += '<a href="javascript:void(0)" class="btn btn-sm btn-danger m-r-2 delUser" data-userid="' + id + '"><i class="fa fa-trash-alt"></i> Delete</a>';
        return action;
    }

    function getActionLinks (data, type, full, meta) {
        if(full.status=='inactive'){
            return inactiveActionHtml(full.id, full.roles.name);
        }else{
            return getActiveActionHtml(full.id, full.roles.name, full.status);
        }
    }
    /** Datatable action column end */

    function setStatus(data, type, full, meta) {
        if(full.status=='active'){
            return "<label class='label label-theme width-50'>"+full.status+"</label>";
        }else if(full.status=='inactive'){
            return "<label class='label label-danger width-50'>"+full.status+"</label>";
        }else{
            return "<label class='label label-info width-50'>"+full.status+"</label>";
        }
    }

    function reloadDataTables () {
        var apiurl    = datataTableUrl;
        var filter_by = $("#users_filter li.active").data('filter');
        console.log(filter_by);
        if(filter_by!='all'){
            apiurl += '?status='+filter_by;
        }
        console.log(apiurl);
        userDataTable.ajax.url( apiurl ).load();
    }

    $("#users_filter li").click(function(event) {
        $("#users_filter li.active").removeClass('active');
        $(this).addClass('active');
        reloadDataTables();
    });

    $(document).on('click', '.delUser', function(event) {
        event.preventDefault();
        var this_elem = $(this).closest('tr');
        console.log(this_elem);
        var userid = $(this).data('userid');
        $.ajax({
            url: baseUrl+'/users/'+userid,
            type: 'DELETE',
            data: {_token: $('input[name="_token"]').val()},
        })
        .done(function(data) {
            console.log("success");
            if(data){
                $(this_elem).remove();
            }
        })
        .fail(function() {
            console.log("error");
        })
        .always(function() {
            console.log("complete");
        });
    });

    $(document).on('click', '.updateStatus', function(event) {
        event.preventDefault();
        var this_elem = $(this).closest('td');
        var userid    = $(this).data('userid');
        var userRole  = $(this).data('user-role');
        var status    = $(this).data('status');
        var new_status = $(this).data('newstatus');
        updateStatus(this_elem, userid, userRole, status, new_status);
    });

    function updateStatus (this_elem, userid, userRole, status, newStatus) {
        $.ajax({
            url: baseUrl+'/users/update-status/'+userid+'?new_status='+newStatus,
        })
        .done(function(data) {
            console.log("success");
            if(data.success=="true"){
                if(newStatus=='inactive') {
                    $(this_elem).find(".blockUser,.viewUser,.editUser").remove();
                    var actionHtml = inactiveActionHtml(userid, userRole, newStatus);
                    $(this_elem).html(actionHtml);
                } else {
                    var actionHtml = getActiveActionHtml(userid, userRole, newStatus);
                    $(this_elem).html(actionHtml);
                }
            }else{
                console.log(data);
            }
        })
        .fail(function(error) {
            console.log("error");
            console.log(error);
        })
        .always(function() {
            console.log("complete");
        });
    }

});