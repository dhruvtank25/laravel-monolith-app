
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
        var number   = hour+'.'+minute;
        return number*1;
    }

$(document).ready(function() {

    // Bootstrap Range Slider

    $(document).on('slide', '#time_slider', function(slideEvt) {
        slideEvt.preventDefault();
        setSlideTimeValues($(this), slideEvt);
    });

    $(document).on('slideStop', '#time_slider', function(slideEvt) {
        setSlideTimeValues($(this), slideEvt);
    });

    function setSlideTimeValues ($this, slideEvt) {
        var new_range = slideEvt.value;
        if(new_range[0]==new_range[1])
            return false;

        var start_time = convertNumberToTime(new_range[0]);
        var end_time   = convertNumberToTime(new_range[1]);

        var sliderparentElem = $this.parent();
        $(sliderparentElem).find('.period_from').text(start_time+' Uhr').data('time', start_time);
        $(sliderparentElem).find('.period_to').text(end_time+' Uhr').data('time', end_time);
    }

    $(document).on('slide', '#period', function(slideEvt) {
        event.preventDefault();
        setSlidePriceValues($(this), slideEvt);
    });

    $(document).on('slideStop', '#period', function(slideEvt) {
        setSlidePriceValues($(this), slideEvt);
    });

    function setSlidePriceValues ($this, slideEvt) {
        var new_range = slideEvt.value;
        if(new_range[0]==new_range[1])
            return false;

        var start_val = pad2(new_range[0]);
        var end_val   = pad2(new_range[1]);

        var sliderparentElem = $this.parent();
        $(sliderparentElem).find('.period_from').text(start_val+'.00 €').data('cost', start_val);
        $(sliderparentElem).find('.period_to').text(end_val+'.00 €').data('cost', end_val);
    }

    // Bootsrap Range Slider End

    var category_id = '';
    category_id = getUrlVars()['category_id'];
    var online = getUrlVars()["category_id"];
    var offline = getUrlVars()["offline_check"];
    var online = getUrlVars()["online_check"];
    if(offline == 1){
        $('#is_offline').prop('checked',true)
    }
    if(online == 1){
        $('#is_online').prop('checked',true)
    }
    $("#category_id").val(category_id);

    $('.cat_tabs li a').click(function(){
        $('.cat_tabs li a').removeClass('active-btn');
        $(this).addClass('active-btn');
    });

    $(document).on('click', '.view_more p', function () {
        $(this).closest('.coach_moreinfo_wrapper').find('.coach_moreinfo').slideToggle();
        var icon_elem = $(this).find('i');
        if($(icon_elem).hasClass("fa-angle-down")) {
            $(icon_elem).removeClass('fa-angle-down');
            $(icon_elem).addClass('fa-angle-up');
        } else {
            $(icon_elem).removeClass('fa-angle-up');
            $(icon_elem).addClass('fa-angle-down');
        }
    });
    
    /*$(document).on('click', '.coach_viewprofile .orange_background_btn', function () {
        window.location.href = baseUrl + '/coach-detail/'+$(this).data('coachid');
    });*/
    
    /*$(document).on('change', '.coach_filter .search_consult input', function () {
        callSearchFunction();
    });

    $(document).on('change', '#is_offline', function(){
        callSearchFunction();
    });

    $(document).on('change', '#is_online', function(){
        callSearchFunction();
    });*/

    $("#filterBtn").click(function(event) {
        callSearchFunction();
    });

});
    


//lazy loading 
$('.coach_filtered ul.pagination').hide();
$('.coach_filtered').jscroll({
    autoTrigger: true,
    loadingHtml: '<img class="center-block" src="'+baseUrl+'/frontend/img/loading.gif" alt="Loading..." width="30px"/>',
    padding: 0,
    //nextSelector: '.pagination li.active + li a',
    nextSelector: '.pagination li:last a',
    contentSelector: 'div.coach_filtered',
    callback: function() {
        $('.coach_filtered ul.pagination').remove();
        setStarRating();
    }
});

//submit search function
function callSearchFunction( )
{
    var is_offline = '';
    var is_online = '';
    $("#is_offline").is(':checked')?is_offline = 1 :is_offline = 0
    $("#is_online").is(':checked')?is_online = 1 :is_online = 0
    $("#offline_check").val(is_offline);
    $("#online_check").val(is_online);
    document.getElementById("search_coach").submit();
}

function getUrlVars() {
    var vars = {};
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
        vars[key] = value;
    });
    return vars;
}