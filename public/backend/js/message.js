
$(function() {

    /** For mobiles */
    if($(window).width()<769){
        $(".container-fluid:last").css('padding','0px');
        $(".container-fluid:last .side-body").css('margin','0px');
        $(".app-container:first").css('background-color','#fff');
        $('#frame').css('height',$( window ).height()-62+'px');
    }

    $('#frame .chat_back').click(function(){
        $("#frame .content").css('display','none');
        $("#frame #sidepanel #contacts ul li.contact .wrap .meta").css('display','inline-block');
        $("#frame #sidepanel").css({'width':'100%','min-width':'100%'});
    });
    /** For mobiles End */

    // Bring users with unread messages to top
    $("p.msgcnt").each(function() {
        var contact_div = $( this ).closest('.contact');
        var member  = contact_div.data('member');
        if(member!='Admin' ){
            contact_div.insertAfter($('.contact[data-member="Admin"]:last'));
        }
        //$( this ).closest('.contact').prependTo('#contacts ul');
    });

    $(document).ready(function() {
        // Check if came by clicking message from navbar (has message_by request parameter)
        var captured = /message_by=([^&]+)/.exec(location.href);
        if(captured && captured[1]){
            $("#contact-"+captured[1]).click();
        }
    });

    $(".messages").animate({ scrollTop: $(document).height() }, "fast");

    $('.submit').click(function() {
       newMessage();
    });

    $(window).on('keydown', function(e) {
        if (e.which == 13) {
            newMessage();
            return false;
        }
    });

    var currentContactId = null;

	$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').val()
        }
	});

	$('#frame').on('click', '.contact', function(){

	 	var contactId = $(this).attr('contact-id');
	 	currentContactId = contactId;

	 	$('.contact').removeClass('active');
	 	$(this).addClass('active');

	 	$('#main-contact-pic').attr('src', $(this).find(".contact-pic").attr('src'));
	 	$('#main-contact-name').html($(this).find(".contact-name").html());

	 	$('.messages').hide();
	 	$('.message-input').hide();

		$('#messages-'+contactId).show();
		$('.message-input').show();

		if($(this).find('.msgcnt').length)
		{
			makeSeen(contactId);
			$(this).find('.msgcnt').remove();
		}
	});

	function makeSeen(userId) {
		$.ajax({
			type: 'POST',
            url:  baseUrl + "/messages/makeseen",
			data: {user_id:userId},
            dataType: 'json',
            success: function (data) {
            	if(data.status == 'success') {
            	}
            }
        });	 	
	}

	function newMessage() {
		message = $(".message-input input").val();
		if($.trim(message) == '') {
			return false;
		}

		$('.message-input input').val(null);

		$.ajax({
			type: 'POST',
            url:  baseUrl + "/messages/send",
			data: {message:message, _id:currentContactId},
            dataType: 'json',
            success: function (data) {

            	if(data.status == 'success') {
					$('<li class="sent"><img src="'+$('#profile-img').attr('src')+'" alt="" /><p>' + message + '</p><span>'+data.date+'</span></li>').appendTo($('#messages-'+data.to_user_id+' ul'));
					
					$('#contact-'+data.to_user_id+' .preview').html('<span>You: </span>' + message);
					$(".messages").animate({ scrollTop: $(document).height() }, "fast");
            	}
            }
        });
	};

	$('#frame').on('click', '.load-more', function(){
		var userId        = $(this).parent().attr('user-id');
		var lastMessageId = $(this).next('ul').find('>li:first').attr('message-id');

		$(this).find('button').text('Loading...');

		$.ajax({
			type: 'POST',
            url:  baseUrl + "/messages/more",
			data: {user_id:userId, last_message_id:lastMessageId},
            dataType: 'json',
            success: function (data) {

            	if(data.status == 'success') {

            		if(!data.show_load_more) {
            			$('#messages-'+data.to_user_id).find(".load-more").remove();
            		}
            		else {
            			$('#messages-'+data.to_user_id).find(".load-more button").text('Load More');
            		}

            		if(data.messages) {
            			jQuery.each( data.messages, function( i, message ) {

            				console.log(message);

            				var liClass = null;
            				var userPic = null;
            				if(message.user_id == data.from_user_id) {
            					liClass = 'sent';
            					userPic = $('#profile-img').attr('src');
            				}
            				else {
            					liClass = 'replies';
            					userPic = $('#main-contact-pic').attr('src');
            				}

            				$('<li class="'+liClass+'" message-id="'+message.id+'"><img src="'+userPic+'" alt="" /><p>' + message.message + '</p><div class="clearfix"></div><span>'+message.created_at+'</span></li>').prependTo($('#messages-'+data.to_user_id+' ul'));
						  
						});
            		}

            	}
            }
        });
	});

});