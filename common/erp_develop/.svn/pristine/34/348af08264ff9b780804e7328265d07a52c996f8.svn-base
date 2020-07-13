jQuery(document).ready(function($) {
    var arrayTransfer = [];
    $('.add_transfer').click(function(){

        var arrayTransfer = { 
            'tour_id'       : $('#tour_id').val(),
            'airport_id'    : $('#add_transfer_airport_id').val(),
            'from'          : $('#add_transfer_from').val(),
            'to'            : $('#add_transfer_to').val(),
            'min_pax'       : $('#add_transfer_min_pax').val(),
            'type'          : $('#add_transfer_type').val(),
            'note'          : $('#add_transfer_note').val()
        }
        
        
        //alert(JSON.stringify(arrayTransfer[]));
         /****** Redirect to the page and allow time for process *************/
          var delay = 1000; //Your delay in milliseconds
             //setTimeout(function(){ window.location = "<?php echo base_url('admin_tour/updateTourForm/'); ?>" + "/" + $('#tour_id').val() + "/" + "#tabs-6"; }, delay);
             setTimeout(function(){ window.location = ""; }, delay);

           
        // Prcoess form data from php
        $.ajax({
          type: "POST",
          dataType: "json",
          data: {get_param: JSON.stringify(arrayTransfer)},
          beforeSend: function(x) {
            if(x && x.overrideMimeType) {
              x.overrideMimeType("application/json;charet=UTF-8");
            }
          },
          url: "<?php echo base_url('admin_tour/add_tour_transfer');?>",
          success: function(data) {
             
           alert('Tour Transfer successfully added !');

            //window.location.href = "service_requests";
            /*if(data.msg == "success") {

            } else {
              //alert('nope mate');
            } */           // 'data' is a JSON object which we can access directly.
           
          }

        }); 
        return false;
    });

    /*$('.remove_transfer').click(function(){
            var hrefId = $(this).attr('id');
            alert(hrefId);
    });*/

    $(".remove_transfer").click("click", function() { 
            var hrefId = $(this).attr('id');
            var delay = 200; //Your delay in milliseconds
                        setTimeout(function(){ window.location = ""; }, delay);
            //$.post('<?php echo base_url('admin_tour/delete_tour_transfer/'); ?>',hrefId,function(data){ alert(hrefId) });
            $.ajax({
                type: "POST",
                url: "<?php echo base_url('admin_tour/delete_tour_transfer/'); ?>",
                data: { get_param: $(this).attr('id') }
              }).done(function( msg ) {
                    alert( "Data Delete: " + msg );

                        

                        /*$('#tabs-6').focus(function() {
                            alert('Handler for .focus() called.');
                        });*/
                      //$('#tabs-6').load('data.php?id=' + id);
                      //$('#transfer_data').load('<?php echo base_url('login/support'); ?>');
                      /*$('#transfer_data').load('<?php echo base_url('admin_tour/view_form/'); ?>'+"/"+ $('#tour_id').val(), function() {
                        alert('Load was performed.');
                      });*/
                  

             });
    });

}); 