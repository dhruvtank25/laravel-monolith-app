    
var registrationId = registrationURL = preRegistrationData = accessKey = ajaxUrl = redirectUrl = '';
function createCardRegister () {
    showLoader();
    $.ajax({
        url: publicUrl+'create-card-registration',
        type: 'GET',
        data: {card_type: $("input[name='card_type']:checked").val()},
    })
    .done(function(data) {
        console.log("success");
        if(data.success=="true") {
            registrationId        = data.card_register.Id;
            registrationURL       = data.card_register.CardRegistrationURL;
            preRegistrationData   = data.card_register.PreregistrationData;
            accessKey             = data.card_register.AccessKey;
            ajaxUrl = redirectUrl = data.return_url;
            saveCard();
            console.log(registrationId);
        } else {
            hideLoader();
            toastr.error(data.message);
        }
    })
    .fail(function(data) {
        console.log("error");
        hideLoader();
        toastr.error('Something unexpected happend while registering card details!');
        console.log(data);
    })
    .always(function() {
        console.log("complete");
    });
}

function saveCard () {
    // Initialize mangoPay.cardRegistration object
    mangoPay.cardRegistration.init({
        cardRegistrationURL : registrationURL,
        preregistrationData : preRegistrationData,
        accessKey : accessKey
    });

    // Collect sensitive card data from the form
    var cardData = {
        cardNumber : $("#cardnumber").val(),
        cardExpirationDate : $("#expirymonth").val()+''+$("#expiryyear").val(),
        cardCvx : $("#cvv").val()
    };

    // Process data
    mangoPay.cardRegistration.sendDataWithAjax(
        // URL to capture response
        ajaxUrl,
        // Card data
        cardData,
        // Result Ajax callback
        function(data) { 
            console.log(data);
            var parsed_data = JSON.parse(data);
            if(parsed_data.success=="true") {
                // Update card registration
                updateCardRegister(parsed_data.registrationData, '');
            } else {
                // Update card registration
                updateCardRegister('', parsed_data.errorCode);
            }
        },
        // Error Ajax callback
        function(xhr, status, error){ 
            console.log(status);
            console.log(error);
            hideLoader();
            alert("Payment error : " + xhr.responseText + " (" + status + " - " + error + ")");
        }
    );
}

function updateCardRegister (registration_data, error_code) {
    console.log(registration_data, error_code);
    $.ajax({
        url: publicUrl+'update-card-registration',
        type: 'POST',
        data: {
                card_registration_id: registrationId, 
                registration_data: registration_data,
                errorCode:  error_code,
                _token: $('input[name="_token"]').val()
            },
    })
    .done(function(data) {
        console.log("success");
        hideLoader();
        if(data.success=='true'){
            toastr.success(data.message);
            // Show next tab
            // if($("#type")!=undefined && $("#type").length>0 && $("#type").val()=='guest') {
            //     console.log('its guest');
            if(typeof is_appointment !== 'undefined' && is_appointment=='yes') {
                console.log('its an appointment booking');
                window.location.href = publicUrl+'book-coach';
                return true;
            }
            else if($("#rootwizard").length>0) {
                var nextTab = $('#rootwizard').bootstrapWizard('currentIndex')+1;
                $('#rootwizard').bootstrapWizard('show', nextTab);
            }
        }
        else {
            toastr.error(data.message);
        }
    })
    .fail(function(data) {
        console.log("error");
        console.log(data);
        hideLoader();
        toastr.error('Unexpected error occured!');
    })
    .always(function() {
        console.log("complete");
    });
}

function payIn (appointment_id) {
    var token = $('input[name="_token"]').val();
    showLoader();
    $.ajax({
        url: publicUrl+'make-payment',
        type: 'POST',
        data: {_token: token, appointment_id: appointment_id},
    })
    .done(function(data) {
        console.log("success");
        console.log(data);
        if(data.success=='true') {
            toastr.success(data.message);
            window.location.href = data.result_url;
        } else {
            if(data.redirect_url!=undefined) {
                setInterval(function(){
                    toastr.info(data.message);
                    toastr.info('You will be redirected to bank website to complete payment in 5 secs!');
                    window.location.href = data.redirect_url;
                }, 5000);
            } else {
                toastr.error(data.message);
                if(data.transaction_id!=undefined && data.transaction_id!='')
                    window.location.href = data.result_url;
            }
        }
    })
    .fail(function(data) {
        console.log("error");
        console.log(data);
        hideLoader();
        toastr.error("Something Unexpected occure while trying to initiate payment!");
    })
    .always(function() {
        console.log("complete");
    });
}