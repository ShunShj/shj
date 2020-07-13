function isNumberKey(evt){
    var theEvent = evt || window.event;
    var rv = true;
      //var key = theEvent.keyCode || theEvent.which;
    var key = (typeof theEvent.which === 'undefined') ? theEvent.keyCode : theEvent.which;
    if (key && (key !== 8)){
        var keychar = String.fromCharCode(key);
        var keycheck = /^-|^\.?[0-9]*$/;
        if (!keycheck.test(keychar) )
        {
          rv = theEvent.returnValue = false;//for IE
          if (theEvent.preventDefault) theEvent.preventDefault();//Firefox
        }
    }
    return rv;
}

$('.datepick').each(function(){
    $(this).datepicker({
      changeMonth: true,
      changeYear: true,
      yearRange: '-100:+0',
      dateFormat: default_date_format||"yy-mm-dd",
      maxDate: new Date()
    });
});

$(function() {

  $("input#create-payment").live("click", function() { 
      var $bookingID = $('table#myinfo_table #booking_id').val();
      var $popupURL  = $('input#create_payment_popup_url').val();
      $("#create-payment-form").load($popupURL, { "booking_id": $bookingID });
  });

  $('table#payment_summary select#p_status').live("change", function(e) {
        $a = $(this).val();
        if($a == 1) {
          $(this).removeClass("cancel").addClass( "enable" );
        } else {
          $(this).removeClass("enable").addClass( "cancel" );
        }
  });



  $('table#payment_summary a#payment_submit').live("click", function(e) {
    var answer = confirm("Are you sure to save this payment ?")
    if (answer){
      var $paymentID      = $(this).closest('tr').attr('id'); //$('table#payment_summary tr').find("td:eq(0)").attr('id');
      var $paymentDate    = $('table#payment_summary tr#'+$paymentID+' td.update input').val();
      var $bid            = $('table#payment_summary tr#'+$paymentID+' td.bid').text();
      var $pid            = $('table#payment_summary tr#'+$paymentID+' td.pmethod select').val();
      var $ref            = $('table#payment_summary tr#'+$paymentID+' td.ref input').val();
      var $amount         = $('table#payment_summary tr#'+$paymentID+' td.pamount input').val();
      var $status         = $('table#payment_summary tr#'+$paymentID+' td.p_status select').val();
      var $url            = $('input#payment_update_url').val();
      $('table#payment_summary tr#'+$paymentID+' td.update input').removeClass('ui-state-error');
      $('table#payment_summary tr#'+$paymentID+' td.pamount input').removeClass('ui-state-error');
      if($paymentDate === "") {
          alert("Please enter payment date !");
          $('table#payment_summary tr#'+$paymentID+' td.update input').addClass('ui-state-error');
          $('table#payment_summary tr#'+$paymentID+' td.update input').focus();
          return false;
      }
      if($amount === "" || $amount === "0.00") {
          alert("Please enter payment amount !");
          $('table#payment_summary tr#'+$paymentID+' td.pamount input').addClass('ui-state-error');
          $('table#payment_summary tr#'+$paymentID+' td.pamount input').focus();
          return false;
      }
      $.post($url,
      {
        payment_id : $paymentID,
        payment_date : $paymentDate,
        bid : $bid,
        pid : $pid,
        ref : $ref,
        amount : $amount,
        status : $status
      },
      function(data,status){
        if(data) {
          console.log(data);
          alert('payment updated successfully.');
          window.location = "#payment-template";
          location.reload(true);
        } else {
            alert('payment update fail !');
        } 
      });
      return false;
    } else {
      return false;
    }
  });

}); 