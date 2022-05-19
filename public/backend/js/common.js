$(function(){

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var $parentDiv = $($(input).parent().find('div.setpreviewimg'));
                var width  = 180;
                var height = 180;
                if($parentDiv.data('width'))
                    width = $parentDiv.data('width');
                if($parentDiv.data('height'))
                    height = $parentDiv.data('height');
                $parentDiv.html('<img src="'+e.target.result+'" alt="" class="previewimg m-b-10" width="'+width+'" height="'+height+'">');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    $(".fileuploaderpreview").change(function(event) {
        readURL(this);
    });

    // Initialize Select2
    $(".default-select2").select2({
        placeholder: 'Select',
        allowClear: true,
    });

    // Get total unread threads
    $.ajax({
        url: baseUrl+'/messages/unread-thread',
    })
    .done(function(data) {
        console.log("success");
        $("#header").find('.unread-message-count').text(data);
    })
    .fail(function(error) {
        console.log("error while fetching unread messages count");
        console.log(error);
    })
    .always(function() {
        console.log("complete");
    });
    

    // Check new messages
    /*$.ajax({
        url: baseUrl+'/messages/unread',
    })
    .done(function(data) {
        console.log("success");
        if(data.status=="success"){
            var messages = data.messages;
            // Set total unread messages
            var total_messages = messages.length;
            $("#header .unread-message-count").text(total_messages);

            // Set unread message list
            var html = '<li class="dropdown-header">NOTIFICATIONS ('+total_messages+')</li>';
            for(let i = 0, length1 = messages.length; i < length1; i++){
                var message  = messages[i];
                var time_ago = moment(message.created_at, "YYYY-MM-DD h:mm:ss").fromNow();
                html += '<li class="media">'+
                          '<a href="'+baseUrl+'/messages?message_by='+message.user_id+'">'+
                            '<div class="media-left">'+
                              '<img src="'+publicUrl+'/avatar/small/'+message.avatar+'" class="media-object" alt="" />'+
                              '<i class="fab fa-facebook-messenger text-primary media-object-icon"></i>'+
                            '</div>'+
                            '<div class="media-body">'+
                              '<h6 class="media-heading">'+message.user_name+'</h6>'+
                              '<p>'+message.message+'</p>'+
                              '<div class="text-muted f-s-11">'+time_ago+'</div>'+
                            '</div>'+
                          '</a>'+
                        '</li>';
            }
            html += '<li class="dropdown-footer text-center">'+
                        '<a href="'+baseUrl+'/messages">View more</a>'+
                    '</li>';
            $("#header .notification_contents").html(html);
        }
    })
    .fail(function() {
        console.log("error");
    })
    .always(function() {
        console.log("complete");
    });*/

});