// Javascript for mybooking - search_reference.php and index.php
jQuery(document).ready(function ($) {

    jQuery(function ($) {
        //$("#search_tourdate").mask("99/99/9999");
    });

    var warning = $(".message");
    var booking_id = "";

    $('.cancel').click(function () {                                          // Add Btn Clicked for Creating Forms
        ConfirmDialog('Are you sure to cancel the booking ?');
        booking_id = this.id;
        action_type = 6;// 2: Cancel the booking
        console.log(booking_id);
    })

    $('.submit').click(function () {                                          // Add Btn Clicked for Creating Forms
        ConfirmDialog('Are you sure to submit the booking ?');
        booking_id = this.id;
        action_type = 2; // 1: Submit the booking
        console.log(booking_id);
    })

    $('#search_submit').click(function () {
        var ref = $('#search_ref').val();
        var search_tourdate = $('#search_tourdate').val();
        var search_pax = $('#search_pax').val();
        var search_status = $('select').val();

        if (ref == "" && search_tourdate == "" && search_status == "" && search_pax == "") {
            alert("Please input your search criteria !");
            return false;
        }
        if (ref != "" && search_tourdate != "" && search_status == "") {
            alert("You cannot search both reference no and tour date !");
            return false;
        }
        if (ref != "" && search_tourdate == "" && search_status != "") {
            alert("You cannot search both reference no and booking status !");
            return false;
        }
        if (ref != "" && search_tourdate != "" && search_status != "" && search_pax != "") {
            alert("You cannot search all criteria at the same time !");
            return false;
        }
        if (ref != "" && search_tourdate == "" && search_status == "" && search_pax != "") {
            alert("You cannot search both reference no and Lead Pax Name !");
            return false;
        }
        if (ref == "" && search_tourdate != "" && search_status != "" && search_pax != "") {
            alert("You cannot search these three search criteria at the same time !");
            return false;
        }
    })


    function ConfirmDialog(message) {
        $('<div></div>').appendTo('body')
            .html('<div><h6>' + message + '?</h6></div>')
            .dialog({
                modal: true, title: 'Action Confirmation', zIndex: 1000, autoOpen: true,
                width: '300px', resizable: false,
                buttons: {
                    Yes: function () {
                        // Process in PHP when true

                        $.ajax({
                            type: "POST",
                            dataType: "json",
                            data: {get_param: booking_id, get_type: action_type},
                            beforeSend: function (x) {
                                if (x && x.overrideMimeType) {
                                    x.overrideMimeType("application/json;charet=UTF-8");
                                }
                            },
                            url: '<?php echo base_url("mybooking/change_booking_status");?>',
                            success: function (data) {
                                //window.location.href = "mybooking";
                                //setTimeout(function(){document.location.href = "mybooking"},500);
                                //alert(data);
                                var delay = 500; //Your delay in milliseconds
                                setTimeout(function () {
                                    window.location = "<?php echo base_url('mybooking');?>";
                                }, delay);
                            }
                        });

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
});