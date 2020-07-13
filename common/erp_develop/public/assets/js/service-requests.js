//Datepicker for dob
$('.datepick').each(function () {
    $(this).datepicker({
        changeMonth: true,
        changeYear: true,
        yearRange: '-100:+10',
        dateFormat: default_date_format||'yy-mm-dd',
      //  maxDate: new Date()
    });
});

// Show hidden form if checkbox is checked
$(document).ready(function () {
    var a = 1;
    var b = 1;
    var c = 1;
    var d = 1;
    var tourDate = $('input#tour_date').val();
    var tourEndDate = $('input#tour_end_date').val();
    var hotelTF = $('input#hotel_transfer_from').val();
    var hotelTT = $('input#hotel_transfer_to').val();
    var airportTF = $('input#airport_transfer_from').val();
    var airportTT = $('input#airport_transfer_to').val();
    var warning = $(".message");
    var checkedPax;
    var selectedRoomPax;
    var roomType;
    var paxValue;
    var totalSelectedPax = 0;
    var age_cat = [];
    var val = [];
    var phpArrObj = [];
    var ageCat = [];
    var ageInfo = [];
    var paxInfo = [];

    /**** Set the div height of content same as booking summary ****/
    /*$this = $("#sidebar");
     pos = $this.offset({top: 165, left: 1020});
     $this.animate({
     left: "-" + pos.left + "px",
     top:  "-" + pos.top  + "px"

     });*/
    /**** Set the div height of content same as booking summary ****/
    divSidebarH = $('#sidebar').height();
    divContentH = $('#content').height();
    if (divSidebarH > divContentH) {
        divContentH = divSidebarH + 150;
        $('#content').height(divContentH)
            .css({});
    }


//console.log(hotelTF);

    /*********************** HOTEL TRANSFER INFO ***********************/
    $('#hotel_transfer_info span.pax-btn').click(function () {
        var tourPax = "";
        $('div#errorMessageHotel').html("");
        $('div#errorMessageHotel').css("display", "none");
        date = $('input#hotel_transfer_date.datepick').val();
        flight = $('input#hotel_transfer_flight').val();
        time = $('input#hotel_transfer_time').val();
        airportNo = $('select.hotel_transfer_airport').val();
        airportName = $('select.hotel_transfer_airport option[value=' + airportNo + ']').text();
        pax = $('#hotel_transfer_tour_pax').val();

        //console.log(date);

        if (date == "") { // Check if date is null
            // show error message remove success message
            warning.addClass("error").removeClass("success").html("Please select hotel transfer date !");
            return false;
        } else {
            // show success message remove error message
            warning.addClass("success").removeClass("error").html("");
        }

        if (flight == "") { // Check if flight is null
            // show error message remove success message
            warning.addClass("error").removeClass("success").html("Please enter flight no!");
            return false;
        } else {
            // show success message remove error message
            warning.addClass("success").removeClass("error").html("");
        }

        if (time == "") { // Check if time is null
            // show error message remove success message
            warning.addClass("error").removeClass("success").html("Please enter time for hotel transfer !");
            return false;
        } else {
            // show success message remove error message
            warning.addClass("success").removeClass("error").html("");
        }

        if (airportNo == "") { // Check if airport is null
            // show error message remove success message
            warning.addClass("error").removeClass("success").html("Please select airport for pickup !");
            return false;
        } else {
            // show success message remove error message
            warning.addClass("success").removeClass("error").html("");
        }

        if (!pax) { // Check if pax is null
            // show error message remove success message
            warning.addClass("error").removeClass("success").html("Please select passenger for transfer !");
            return false;
        } else {
            // show success message remove error message
            warning.addClass("success").removeClass("error").html("");

            $(':checkbox:checked').each(function (i) {
                // Disable selected passenger when item added to room_pax
                var el = $("select#hotel_transfer_tour_pax").multiselect(),
                    disabled = $('#disabled'),
                    selected = $('#selected');

                if (':checkbox:checked') {
                    $("select#hotel_transfer_tour_pax option:checked").attr('disabled', 'disabled');
                    $("select#hotel_transfer_tour_pax option:checked").removeAttr('selected');
                }

                if (selected.is(':checked')) {
                    opt.attr('selected', 'selected');
                }

                $("select.hotel_transfer_tour_pax").appendTo(el);

                el.multiselect('refresh');
            });

            for (i = 0; i < pax.length; i++) {
                //alert($nameStr);
                // Split value from checked field into an array
                age_cat = pax[i].split('|');
                // Get age cat from splited array
                ageInfo[i] = age_cat[1];
                // Get pax name from splited array
                paxInfo[i] = age_cat[0];

                // add checked pax into string
                // as html format for display
                tourPax += "<li id=" + i + ">";
                tourPax += paxInfo[i] + "|" + ageInfo[i];
                tourPax += "</li>";
            }
        }

        if (time < hotelTF || time > hotelTT || date != tourDate) {
            alert("Hotel Transfer charge apply !");
            is_status = '<span style="color:#005702;">Transfer charge applies </span><span style="color:#005702;" class="hotel_transfer_charge' + a + '">' + pax.length + '</span><span style="color:#005702;"> Passenger/s</span>';
            is_hotelTT_charge = true;
        } else {
            paxNum = 0;
            is_status = '<span style="color:#005702;">Complimentary Transfer / Free Transfer </span><!--span style="color:#005702;" class="hotel_transfer_charge' + a + '">' + paxNum + '</span><span style="color:#005702;"> Passenger/s</span-->';
            is_hotelTT_charge = false;
        }

        /*var hotelTransferInfo = "<div id='hotelTransferDiv'><hr /><p id='air'><span id='maxPax"+a+"' style='display:none;'>"+maxPax+"</span><span style='color:#3376bb; font-size:20px;'> "+a+". </span><span id='room"+a+"' style='color:#3376bb; font-size:20px;'>"+roomType +"</span><ol id='pax"+a+"' style='margin-top:-25px; margin-left: 500px; color:#3376bb; font-size:16px; height:auto;'>"+ tourPax +"</ol></p>"+is_status+"</div>";*/
        var hotelTransferInfo = '<div id="hotelTransferDiv' + a + '"><hr /><table id="service_request_table" cellspacing="0"><tr><th>' + a + '</th><th>Date</th><th>Flight</th><th>Time</th><th class="airport">Airport</th><th class="pax">Transfer Passenger x ' + pax.length + '</th></tr><tr><td>&nbsp;</td><td class="date">' + date + '</td><td class="flight">' + flight + '</td><td class="time">' + time + '</td><td class="airport">' + airportName + '</td><td class="last"><ul>' + tourPax + '</ul></td></tr></table>' + is_status + '</div>';

        /*<span id='airport' style='color:#3376bb; font-size:20px;'>Pickup Airport: "+airport+"</span>
         <span id='time' style='color:#3376bb; font-size:20px;'>Pickup Airport: "+time+"</span><span id='date' style='color:#3376bb; font-size:20px;'>Pickup Airport: "+date+"</span><span id='flight' style='color:#3376bb; font-size:20px;'>Pickup Airport: "+flight+"</span><span class='remove_btn' style='margin-top:-50px; margin-left:660px'></span></p><hr/></div>";*/

        $('#hotel_transfer_pax').append(hotelTransferInfo);

        a++;

        console.log('Hotel Transfer Info Date:' + date);
        console.log('Hotel Transfer Info flight:' + flight);
        console.log('Hotel Transfer Info time:' + time);
        console.log('Hotel Transfer Info airport:' + airportName);
    }); // END Click
    /*********************** END HOTEL TRANSFER INFO ***********************/

    /*********************** AIRPORT TRANSFER INFO ***********************/
    $('#airport_transfer_info span.pax-btn').click(function () {

        var tourPax = '';
        $('div#errorMessageAirport').html("");
        $('div#errorMessageAirport').css("display", "none");
        date = $('input#airport_transfer_date.datepick').val();
        flight = $('input#airport_transfer_flight').val();
        time = $('input#airport_transfer_time').val();
        airportNo = $('select.airport_transfer_airport').val();
        airportName = $('select.airport_transfer_airport option[value=' + airportNo + ']').text();
        pax = $('#airport_transfer_tour_pax').val();

        console.log(date);

        if (date == "") { // Check if date is null
            // show error message remove success message
            warning.addClass("error").removeClass("success").html("Please select airport transfer date !");
            return false;
        } else {
            // show success message remove error message
            warning.addClass("success").removeClass("error").html("");
        }

        if (flight == "") { // Check if flight is null
            // show error message remove success message
            warning.addClass("error").removeClass("success").html("Please select a airport transfer flight !");
            return false;
        } else {
            // show success message remove error message
            warning.addClass("success").removeClass("error").html("");
        }

        if (time == "") { // Check if time is null
            // show error message remove success message
            warning.addClass("error").removeClass("success").html("Please select a airport transfer time !");
            return false;
        } else {
            // show success message remove error message
            warning.addClass("success").removeClass("error").html("");
        }

        if (airportNo == "") { // Check if airport is null
            // show error message remove success message
            warning.addClass("error").removeClass("success").html("Please select a airport transfer airport !");
            return false;
        } else {
            // show success message remove error message
            warning.addClass("success").removeClass("error").html("");
        }

        if (!pax) { // Check if pax is null
            // show error message remove success message
            warning.addClass("error").removeClass("success").html("Please select airport transfer passenger !");
            return false;
        } else {
            // show success message remove error message
            warning.addClass("success").removeClass("error").html("");

            $(':checkbox:checked').each(function (i) {
                // Disable selected passenger when item added to room_pax
                var el = $("select#airport_transfer_tour_pax").multiselect(),
                    disabled = $('#disabled'),
                    selected = $('#selected');

                if (':checkbox:checked') {
                    $("select#airport_transfer_tour_pax option:checked").attr('disabled', 'disabled');
                    $("select#airport_transfer_tour_pax option:checked").removeAttr('selected');
                }

                if (selected.is(':checked')) {
                    opt.attr('selected', 'selected');
                }

                $("select.airport_transfer_tour_pax").appendTo(el);

                el.multiselect('refresh');
            });

            for (i = 0; i < pax.length; i++) {
                //alert($nameStr);
                // Split value from checked field into an array
                age_cat = pax[i].split('|');
                // Get age cat from splited array
                ageInfo[i] = age_cat[1];
                // Get pax name from splited array
                paxInfo[i] = age_cat[0];

                // add checked pax into string
                // as html format for display
                tourPax += "<li id=" + i + ">";
                tourPax += paxInfo[i] + "|" + ageInfo[i];
                tourPax += "</li>";
            }
        }

        if (time < airportTF || time > airportTT || date != tourEndDate) {
            alert("Airport Transfer charge apply ! ");
            is_status = '<span style="color:#005702;">Airport Transfer Charge for </span><span style="color:#005702;" class="airport_transfer_charge' + b + '">' + pax.length + '</span><span style="color:#005702;"> Passenger/s</span>';
            is_airportTT_charge = true;
        } else {
            paxNum = 0;
            is_status = '<span style="color:#005702;">Complimentary Transfer / Free Transfer</span>';
            is_airportTT_charge = false;
        }


        /*var airportTransferInfo = "<div id='airportTransferDiv'><hr /><p id='air'><span id='maxPax"+a+"' style='display:none;'>"+maxPax+"</span><span style='color:#3376bb; font-size:20px;'> "+a+". </span><span id='room"+a+"' style='color:#3376bb; font-size:20px;'>"+roomType +"</span><ol id='pax"+a+"' style='margin-top:-25px; margin-left: 500px; color:#3376bb; font-size:16px; height:auto;'>"+ tourPax +"</ol></p>"+is_status+"</div>";*/
        var airportTransferInfo = '<div id="airportTransferDiv' + b + '"><hr /><table id="service_request_table" cellspacing="0"><tr><th>' + b + '</th><th>Date</th><th>Flight</th><th>Time</th><th class="airport">Airport</th><th class="pax">Transfer Passengers x ' + pax.length + '</th></tr><tr><td>&nbsp;</td><td class="date">' + date + '</td><td class="flight">' + flight + '</td><td class="time">' + time + '</td><td class="airport">' + airportName + '</td><td class="last"><ul>' + tourPax + '</ul></td></tr></table>' + is_status + '</div>';

        $('#airport_transfer_pax').append(airportTransferInfo);

        b++;

        console.log('airport Transfer Info Date:' + date);
        console.log('airport Transfer Info flight:' + flight);
        console.log('airport Transfer Info time:' + time);
        console.log('airport Transfer Info airport:' + airportName);
    }); // END Click
    /*********************** END AIRPORT TRANSFER INFO ***********************/

    /*********************** Pre Tour Accommodation INFO ***********************/
    $('#pre_accommodation_info span.pax-btn').click(function () {

        var tourPax = '';
        $('div#errorMessagePre').html("");
        $('div#errorMessagePre').css("display", "none");
        pax = $('#pre_accommodation_tour_pax').val();
        roomType = $('#pre_accommodation_room_type').val();
        nights = $('#pre_accommodation_nights').val();

        console.log(pax);

        if (roomType == "") { // Check if roomType is null
            // show error message remove success message
            warning.addClass("error").removeClass("success").html("Please select preferred room type !");
            return false;
        } else {
            // show success message remove error message
            warning.addClass("success").removeClass("error").html("");
        }

        if (nights == "") { // Check if night is null
            // show error message remove success message
            warning.addClass("error").removeClass("success").html("Please select number of nights accommodation !");
            return false;
        } else {
            // show success message remove error message
            warning.addClass("success").removeClass("error").html("");
        }

        if (!pax) { // Check if pax is null
            // show error message remove success message
            warning.addClass("error").removeClass("success").html("Please select passenger for accommodation!");
            return false;
        } else {
            // show success message remove error message
            warning.addClass("success").removeClass("error").html("");

            $(':checkbox:checked').each(function (i) {
                // Disable selected passenger when item added to room_pax
                var el = $("select#pre_accommodation_tour_pax").multiselect(),
                    disabled = $('#disabled'),
                    selected = $('#selected');

                if (':checkbox:checked') {
                    $("select#pre_accommodation_tour_pax option:checked").attr('disabled', 'disabled');
                    $("select#pre_accommodation_tour_pax option:checked").removeAttr('selected');
                }

                if (selected.is(':checked')) {
                    opt.attr('selected', 'selected');
                }

                $("select.pre_accommodation_tour_pax").appendTo(el);

                el.multiselect('refresh');
            });

            for (i = 0; i < pax.length; i++) {
                //alert($nameStr);
                // Split value from checked field into an array
                age_cat = pax[i].split('|');
                // Get age cat from splited array
                ageInfo[i] = age_cat[1];
                // Get pax name from splited array
                paxInfo[i] = age_cat[0];

                // add checked pax into string
                // as html format for display
                tourPax += "<li id=" + i + ">";
                tourPax += paxInfo[i] + "|" + ageInfo[i];
                tourPax += "</li>";
            }
        }


        /*var airportTransferInfo = "<div id='airportTransferDiv'><hr /><p id='air'><span id='maxPax"+a+"' style='display:none;'>"+maxPax+"</span><span style='color:#3376bb; font-size:20px;'> "+a+". </span><span id='room"+a+"' style='color:#3376bb; font-size:20px;'>"+roomType +"</span><ol id='pax"+a+"' style='margin-top:-25px; margin-left: 500px; color:#3376bb; font-size:16px; height:auto;'>"+ tourPax +"</ol></p>"+is_status+"</div>";*/
        var preTourAccommodataionInfo = '<div id="preTourAccommodationDiv' + c + '"><hr /><table id="service_request_table" cellspacing="0"><tr><th>' + c + '</th><th>Nights</th><th>Room Type</th><th class="pax">Passengers x ' + pax.length + '</th></tr><tr><td>&nbsp;</td><td class="nights">' + nights + '</td><td class="room_type">' + roomType + '</td><td class="last"><ul>' + tourPax + '</ul></td></tr></table></div>';

        /*<span id='airport' style='color:#3376bb; font-size:20px;'>Pickup Airport: "+airport+"</span>
         <span id='time' style='color:#3376bb; font-size:20px;'>Pickup Airport: "+time+"</span><span id='date' style='color:#3376bb; font-size:20px;'>Pickup Airport: "+date+"</span><span id='flight' style='color:#3376bb; font-size:20px;'>Pickup Airport: "+flight+"</span><span class='remove_btn' style='margin-top:-50px; margin-left:660px'></span></p><hr/></div>";*/

        $('#pre_accommodation_pax').append(preTourAccommodataionInfo);

        c++;


    }); // END Click
    /*********************** END Pre Tour Accommodation INFO ***********************/

    /*********************** Post Tour Accommodation INFO ***********************/
    $('#post_accommodation_info span.pax-btn').click(function () {

        var tourPax = '';
        $('div#errorMessagePost').html("");
        $('div#errorMessagePost').css("display", "none");
        pax = $('#post_accommodation_tour_pax').val();
        roomType = $('#post_accommodation_room_type').val();
        nights = $('#post_accommodation_nights').val();

        console.log(pax);

        if (roomType == "") { // Check if roomType is null
            // show error message remove success message
            warning.addClass("error").removeClass("success").html("Please select preferred room type !");
            return false;
        } else {
            // show success message remove error message
            warning.addClass("success").removeClass("error").html("");
        }

        if (nights == "") { // Check if night is null
            // show error message remove success message
            warning.addClass("error").removeClass("success").html("Please select number of nights accommodation !");
            return false;
        } else {
            // show success message remove error message
            warning.addClass("success").removeClass("error").html("");
        }

        if (!pax) { // Check if pax is null
            // show error message remove success message
            warning.addClass("error").removeClass("success").html("Please select passenger for accommodation!");
            return false;
        } else {
            // show success message remove error message
            warning.addClass("success").removeClass("error").html("");

            $(':checkbox:checked').each(function (i) {
                // Disable selected passenger when item added to room_pax
                var el = $("select#post_accommodation_tour_pax").multiselect(),
                    disabled = $('#disabled'),
                    selected = $('#selected');

                if (':checkbox:checked') {
                    $("select#post_accommodation_tour_pax option:checked").attr('disabled', 'disabled');
                    $("select#post_accommodation_tour_pax option:checked").removeAttr('selected');
                }

                if (selected.is(':checked')) {
                    opt.attr('selected', 'selected');
                }

                $("select.post_accommodation_tour_pax").appendTo(el);

                el.multiselect('refresh');
            });

            for (i = 0; i < pax.length; i++) {
                //alert($nameStr);
                // Split value from checked field into an array
                age_cat = pax[i].split('|');
                // Get age cat from splited array
                ageInfo[i] = age_cat[1];
                // Get pax name from splited array
                paxInfo[i] = age_cat[0];

                // add checked pax into string
                // as html format for display
                tourPax += "<li id=" + i + ">";
                tourPax += paxInfo[i] + "|" + ageInfo[i];
                tourPax += "</li>";
            }
        }


        /*var airportTransferInfo = "<div id='airportTransferDiv'><hr /><p id='air'><span id='maxPax"+a+"' style='display:none;'>"+maxPax+"</span><span style='color:#3376bb; font-size:20px;'> "+a+". </span><span id='room"+a+"' style='color:#3376bb; font-size:20px;'>"+roomType +"</span><ol id='pax"+a+"' style='margin-top:-25px; margin-left: 500px; color:#3376bb; font-size:16px; height:auto;'>"+ tourPax +"</ol></p>"+is_status+"</div>";*/
        var postTourAccommodataionInfo = '<div id="postTourAccommodationDiv' + d + '"><hr /><table id="service_request_table" cellspacing="0"><tr><th>' + d + '</th><th>Nights</th><th>Room Type</th><th class="pax">Passengers x ' + pax.length + '</th></tr><tr><td>&nbsp;</td><td class="nights">' + nights + '</td><td class="room_type">' + roomType + '</td><td class="last"><ul>' + tourPax + '</ul></td></tr></table></div>';

        /*<span id='airport' style='color:#3376bb; font-size:20px;'>Pickup Airport: "+airport+"</span>
         <span id='time' style='color:#3376bb; font-size:20px;'>Pickup Airport: "+time+"</span><span id='date' style='color:#3376bb; font-size:20px;'>Pickup Airport: "+date+"</span><span id='flight' style='color:#3376bb; font-size:20px;'>Pickup Airport: "+flight+"</span><span class='remove_btn' style='margin-top:-50px; margin-left:660px'></span></p><hr/></div>";*/

        $('#post_accommodation_pax').append(postTourAccommodataionInfo);

        d++;


    }); // END Click
    /*********************** END Post Tour Accommodation INFO ***********************/

    if ($('input#hotel_transfer').is(':checked')) {
        //$("#txtAge").hide();
        $("div#hotel_transfer_info").show();

        console.log('checked');

    } else {
        //$("#txtAge").show();
        $("div#hotel_transfer_info").hide();
    }

    if ($('input#airport_transfer').is(':checked')) {
        //$("#txtAge").hide();
        $("div#airport_transfer_info").show();
    } else {
        //$("#txtAge").show();
        $("div#airport_transfer_info").hide();
    }

    if ($('input#post_accommodation').is(':checked')) {
        //$("#txtAge").hide();
        $("div#post_accommodation_info").show();
    } else {
        //$("#txtAge").show();
        $("div#post_accommodation_info").hide();
    }

    if ($('input#pre_accommodation').is(':checked')) {
        //$("#txtAge").hide();
        $("div#pre_accommodation_info").show();
    } else {
        //$("#txtAge").show();
        $("div#pre_accommodation_info").hide();
    }

});

// Click yes checkbox to show hdidden form, uncheck to hide it
$('label#hotel_transfer').click(function () {
    if ($('input#hotel_transfer').is(':checked')) {
        //$("#txtAge").hide();
        $("div#hotel_transfer_info").hide();
    } else {
        //$("#txtAge").show();
        $("div#hotel_transfer_info").show();
    }
});

$('label#airport_transfer').click(function () {
    if ($('input#airport_transfer').is(':checked')) {
        //$("#txtAge").hide();
        $("div#airport_transfer_info").hide();
    } else {
        //$("#txtAge").show();
        $("div#airport_transfer_info").show();
    }
});

$('label#pre_accommodation').click(function () {
    if ($('input#pre_accommodation').is(':checked')) {
        //$("#txtAge").hide();
        $("div#pre_accommodation_info").hide();
    } else {
        //$("#txtAge").show();
        $("div#pre_accommodation_info").show();
    }
});

$('label#post_accommodation').click(function () {
    if ($('input#post_accommodation').is(':checked')) {
        //$("#txtAge").hide();
        $("div#post_accommodation_info").hide();
    } else {
        //$("#txtAge").show();
        $("div#post_accommodation_info").show();
    }
});


// Show and Hide when click a href show and hide

/**
 *  This is for style and function the passenger multiselect box
 **/
$(function () {
    $("select.room_pax_form_select").multiselect({
        //selectedList: 0 // 0-based index
        selectedText: function (numChecked, numTotal, checkedItems) {
            return numChecked + ' of ' + numTotal + ' checked';
        }
    });
});


jQuery(function ($) {
    $(".time").mask("99:99");
});

$('input[name="submit"]').click(function () {
    var arrayHotel = []; // This array stores information collected from div and will be passed to PHP from json
    var arrayAirport = []; // This array stores information collected from div and will be passed to PHP from json
    var arrayPre = []; // This array stores information collected from div and will be passed to PHP from json
    var arrayPost = []; // This array stores information collected from div and will be passed to PHP from json
    var warning = $(".message");

    var myHotelObj = {};
    var myAirportObj = {};
    var myPreObj = {};
    var myPostObj = {};
    var totalHotelTransferCharge = 0;
    var totalAirportTransferCharge = 0;
    var checkedHotel = false;
    var checkedAirport = false;
    var checkedPre = false;
    var checkedPost = false;

    if ($('textarea.special_request_text').val() != "") {
        var special_request_text = "";
        special_request_text = $('textarea.special_request_text').val();
    }

    if ($('input#hotel_transfer').is(':checked')) {
        checkedHotel = true;
        var tourPax = '';

        date = $('input#hotel_transfer_date.datepick').val();
        flight = $('input#hotel_transfer_flight').val();
        time = $('input#hotel_transfer_time').val();
        airportNo = $('select.hotel_transfer_airport').val();
        airportName = $('select.hotel_transfer_airport option[value=' + airportNo + ']').text();
        pax = $('#hotel_transfer_tour_pax').val();
        // Hotel Transfer checked
        $('div#hotel_transfer_pax table').each(function (i) {
            ageInfo = [];
            paxInfo = [];
            age_cat = [];
            i = i + 1;
            hotelTransferDate = $('div#hotelTransferDiv' + i + ' table td.date').text();
            hotelTransferFlight = $('div#hotelTransferDiv' + i + ' table td.flight').text();
            hotelTransferTime = $('div#hotelTransferDiv' + i + ' table td.time').text();
            airport = $('div#hotelTransferDiv' + i + ' table td.airport').text();
            hotelTransferAirport = $.trim(airport); // remove white spaces
            hotelTransferPax = $('div#hotelTransferDiv' + i + ' table ul').text();
            hotelTransferCharge = parseInt($('div#hotelTransferDiv' + i + ' span.hotel_transfer_charge' + i + '').text());


            // Loop how many pax insdie the ol
            $('div#hotelTransferDiv' + i + ' table ul').find('li').each(function (index) {
                // Get value from checked field
                val = $(this).text();
                // Split value from checked field into an array
                age_cat = val.split('|');
                // Get age cat from splited array
                ageInfo[index] = age_cat[1];
                // Get pax name from splited array
                paxInfo[index] = age_cat[0];
            });

            console.log(hotelTransferAirport);
            /*alert('Received Hotel Transfer Date: ' + hotelTransferDate );
             alert('Received Hotel Transfer Flight: ' + hotelTransferFlight );
             alert('Received Hotel Transfer Time: ' + hotelTransferTime );
             alert('Received Hotel Transfer Airport: ' + hotelTransferAirport );
             alert('Received Hotel Transfer Passengers: ' + paxInfo );
             alert('Received Hotel Transfer service charge: ' + hotelTransferCharge );
             */
            totalHotelTransferCharge = totalHotelTransferCharge + hotelTransferCharge;

            myHotelObj['date'] = hotelTransferDate;
            myHotelObj['flight'] = hotelTransferFlight;
            myHotelObj['time'] = hotelTransferTime;
            myHotelObj['airport'] = hotelTransferAirport;
            myHotelObj['pax'] = paxInfo;
            myHotelObj['charge'] = hotelTransferCharge;

            arrayHotel[i - 1] = JSON.stringify(myHotelObj);

        }); // end div hotel transfer pax table each
        //alert(totalHotelTransferCharge);
    }// end if

    if ($('input#airport_transfer').is(':checked')) {
        checkedAirport = true;
        $('div#airport_transfer_pax table').each(function (z) {
            ageInfo = [];
            paxInfo = [];
            age_cat = [];
            z = z + 1;
            airportTransferDate = $('div#airportTransferDiv' + z + ' table td.date').text();
            airportTransferFlight = $('div#airportTransferDiv' + z + ' table td.flight').text();
            airportTransferTime = $('div#airportTransferDiv' + z + ' table td.time').text();
            airport = $('div#airportTransferDiv' + z + ' table td.airport').text();
            airportTransferAirport = $.trim(airport); // remove white spaces
            airportTransferPax = $('div#airportTransferDiv' + z + ' table ul').text();
            airportTransferCharge = parseInt($('div#airportTransferDiv' + z + ' span.airport_transfer_charge' + z + '').text());

            // Loop how many pax insdie the ol
            $('div#airportTransferDiv' + z + ' table ul').find('li').each(function (index) {
                // Get value from checked field
                val = $(this).text();
                // Split value from checked field into an array
                age_cat = val.split('|');
                // Get age cat from splited array
                ageInfo[index] = age_cat[1];
                // Get pax name from splited array
                paxInfo[index] = age_cat[0];
            });

            console.log(airportTransferAirport);
            /*alert('Received airport Transfer Date: ' + airportTransferDate );
             alert('Received airport Transfer Flight: ' + airportTransferFlight );
             alert('Received airport Transfer Time: ' + airportTransferTime );
             alert('Received airport Transfer Airport: ' + airportTransferAirport );
             alert('Received airport Transfer Passengers: ' + paxInfo );
             alert('Received airport Transfer service charge: ' + airportTransferCharge );
             */

            totalAirportTransferCharge = totalAirportTransferCharge + airportTransferCharge;

            myAirportObj['date'] = airportTransferDate;
            myAirportObj['flight'] = airportTransferFlight;
            myAirportObj['time'] = airportTransferTime;
            myAirportObj['airport'] = airportTransferAirport;
            myAirportObj['pax'] = paxInfo;
            myAirportObj['charge'] = airportTransferCharge;

            arrayAirport[z - 1] = JSON.stringify(myAirportObj);


        }); // end div airport transfer pax table each
        //alert(totalAirportTransferCharge);
    } // end if

    if ($('input#pre_accommodation').is(':checked')) {                                                                                                 // Pre Tour Accommodation checked   
        checkedPre = true;
        $('div#pre_accommodation_pax table').each(function (z) {
            ageInfo = [];
            paxInfo = [];
            age_cat = [];
            z = z + 1;

            //pax       = $('div#preTourAccommodationDiv'+z+' table ul').find('li'val();
            roomType = $('div#preTourAccommodationDiv' + z + ' table td.room_type').text();
            nights = $('div#preTourAccommodationDiv' + z + ' table td.nights').text();


            // Loop how many pax insdie the ol
            $('div#preTourAccommodationDiv' + z + ' table ul').find('li').each(function (index) {
                // Get value from checked field
                val = $(this).text();
                // Split value from checked field into an array
                age_cat = val.split('|');

                // Get age cat from splited array
                ageInfo[index] = age_cat[1];
                // Get pax name from splited array
                paxInfo[index] = age_cat[0];
            });

            //console.log(nights);

            myPreObj['nights'] = nights;
            myPreObj['roomType'] = roomType;
            myPreObj['pax'] = paxInfo;

            arrayPre[z - 1] = JSON.stringify(myPreObj);


        }); // end div hotel transfer pax table each

    } // end if

    if ($('input#post_accommodation').is(':checked')) {         // Post Tour Accommodation checked   
        checkedPost = true;
        $('div#post_accommodation_pax table').each(function (z) {
            ageInfo = [];
            paxInfo = [];
            age_cat = [];

            z = z + 1;

            //pax       = $('div#preTourAccommodationDiv'+z+' table ul').find('li'val();
            roomType = $('div#postTourAccommodationDiv' + z + ' table td.room_type').text();
            nights = $('div#postTourAccommodationDiv' + z + ' table td.nights').text();


            // Loop how many pax insdie the ol
            $('div#postTourAccommodationDiv' + z + ' table ul').find('li').each(function (index) {
                // Get value from checked field
                val = $(this).text();
                // Split value from checked field into an array
                age_cat = val.split('|');

                // Get age cat from splited array
                ageInfo[index] = age_cat[1];
                // Get pax name from splited array
                paxInfo[index] = age_cat[0];
            });

            console.log(nights);

            myPostObj['nights'] = nights;
            myPostObj['roomType'] = roomType;
            myPostObj['pax'] = paxInfo;

            arrayPost[z - 1] = JSON.stringify(myPostObj);


        }); // end div hotel transfer pax table each

    } // end if

    /****** Redirect to the page and allow time for process *************/
    var redirect = true;

    if (checkedHotel == true) {
        if (arrayHotel == "") {
            redirect = false;
            jQuery("div#errorMessageHotel").css({ // css is only set for div #add_note tour itinerary
                "position": "absolute",
                "margin-top": "-30" + "px",
                "left": "350" + "px",
                "width": "300" + "px",
                "height": "40" + "px",
                "padding": "5" + "px",
                "background": "rgba(255,0,0,0.65)",
                "color": "#fff",
                "font-size": "16" + "px",
                "font-weight": "bold",
                "zIndex":"1"
            });
            $('div#errorMessageHotel')
                .css({
                    borderTopLeftRadius: 10,
                    borderTopRightRadius: 10,
                    borderBottomLeftRadius: 10,
                    borderBottomRightRadius: 10,
                    WebkitBorderTopLeftRadius: 10,
                    WebkitBorderTopRightRadius: 10,
                    WebkitBorderBottomLeftRadius: 10,
                    WebkitBorderBottomRightRadius: 10,
                    MozBorderRadius: 10
                });
            $('div#errorMessageHotel').hide().html("<< Please hit this button to add a hotel transfer service").fadeIn();
            return false;
        }
    }
    ;
    if (checkedAirport == true) {
        if (arrayAirport == "") {
            redirect = false;
            jQuery("div#errorMessageAirport").css({ // css is only set for div #add_note tour itinerary
                "position": "absolute",
                "margin-top": "-30" + "px",
                "left": "350" + "px",
                "width": "300" + "px",
                "height": "40" + "px",
                "padding": "5" + "px",
                "background": "rgba(255,0,0,0.65)",
                "color": "#fff",
                "font-size": "16" + "px",
                "font-weight": "bold",
                "zIndex":"1"
            });
            $('div#errorMessageAirport')
                .css({
                    borderTopLeftRadius: 10,
                    borderTopRightRadius: 10,
                    borderBottomLeftRadius: 10,
                    borderBottomRightRadius: 10,
                    WebkitBorderTopLeftRadius: 10,
                    WebkitBorderTopRightRadius: 10,
                    WebkitBorderBottomLeftRadius: 10,
                    WebkitBorderBottomRightRadius: 10,
                    MozBorderRadius: 10
                });
            $('div#errorMessageAirport').hide().html("<< Please hit this button to add an airport transfer service").fadeIn();
            return false;
        }
    }
    ;
    if (checkedPre == true) {
        if (arrayPre == "") {
            redirect = false;
            jQuery("div#errorMessagePre").css({ // css is only set for div #add_note tour itinerary
                "position": "absolute",
                "margin-top": "-30" + "px",
                "margin-left": "40" + "px",
                "width": "300" + "px",
                "height": "40" + "px",
                "padding": "5" + "px",
                "background": "rgba(255,0,0,0.65)",
                "color": "#fff",
                "font-size": "16" + "px",
                "font-weight": "bold",
                "zIndex":"1"
            });
            $('div#errorMessagePre')
                .css({
                    borderTopLeftRadius: 10,
                    borderTopRightRadius: 10,
                    borderBottomLeftRadius: 10,
                    borderBottomRightRadius: 10,
                    WebkitBorderTopLeftRadius: 10,
                    WebkitBorderTopRightRadius: 10,
                    WebkitBorderBottomLeftRadius: 10,
                    WebkitBorderBottomRightRadius: 10,
                    MozBorderRadius: 10
                });
            $('div#errorMessagePre').hide().html("<< Please hit this button to add a pre accommodation service").fadeIn();
            return false;
        }
    }
    ;
    if (checkedPost == true) {
        if (arrayPost == "") {
            redirect = false;
            jQuery("div#errorMessagePost").css({ // css is only set for div #add_note tour itinerary
                "position": "absolute",
                "margin-top": "-30" + "px",
                "margin-left": "10" + "px",
                "width": "300" + "px",
                "height": "40" + "px",
                "padding": "5" + "px",
                "background": "rgba(255,0,0,0.65)",
                "color": "#fff",
                "font-size": "16" + "px",
                "font-weight": "bold",
                "zIndex":1
            });
            $('div#errorMessagePost')
                .css({
                    borderTopLeftRadius: 10,
                    borderTopRightRadius: 10,
                    borderBottomLeftRadius: 10,
                    borderBottomRightRadius: 10,
                    WebkitBorderTopLeftRadius: 10,
                    WebkitBorderTopRightRadius: 10,
                    WebkitBorderBottomLeftRadius: 10,
                    WebkitBorderBottomRightRadius: 10,
                    MozBorderRadius: 10
                });
            $('div#errorMessagePost').hide().html("<< Please hit this button to add a post accommodation service").fadeIn();
            return false;
        }
    }
    ;

    if (redirect == true) {
        var delay = 1000; //Your delay in milliseconds

        // Prcoess form data from php
        $.ajax({
            type: "POST",
            dataType: "json",
            data: {get_param_hotel: arrayHotel, get_param_airport: arrayAirport, get_param_pre: arrayPre, get_param_post: arrayPost, get_param_special: special_request_text},
            beforeSend: function (x) {
                if (x && x.overrideMimeType) {
                    x.overrideMimeType("application/json;charet=UTF-8");
                }
            },
            url: 'service_requests_check',
            success: function (data) {
                setTimeout(function () {
                    window.location = "booking_summary";
                }, delay);
                //window.location.href = "service_requests";
                //if(data.msg == "success") {
                // } else {
                //alert('nope mate');
                //}            // 'data' is a JSON object which we can access directly.

            }
        });
    }


});

/**
 *  This is for reset function
 **/
$(".reset").click(function () {
    ConfirmDialog('Are you sure to reset transfer service and accommodation config ?');
});

function ConfirmDialog(message) {
    $('<div></div>').appendTo('body')
        .html('<div><h6>' + message + '?</h6></div>')
        .dialog({
            modal: true, title: 'Reset', zIndex: 1000, autoOpen: true,
            width: '300px', resizable: false,
            buttons: {
                Yes: function () {
                    $("#room_pax").empty();
                    $(this).dialog("close");
                    $.get('/book_tour/kill_service_requests_session');

                    /****** Redirect to the page and allow time for process *************/
                    var delay = 1000; //Your delay in milliseconds
                    setTimeout(function () {
                        window.location = "service_requests";
                    }, delay);

                    // Problem when use google chorm
                    //location.reload(true);
                },
                No: function () {
                    $(this).dialog("close");
                }
            },
            close: function (event, ui) {
                $(this).remove();
            }
        });
};
