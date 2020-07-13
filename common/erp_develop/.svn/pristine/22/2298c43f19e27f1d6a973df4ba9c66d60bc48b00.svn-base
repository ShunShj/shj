/****** CKEDITOR TOOLBAR SETTING *********/
CKEDITOR.config.width = 600;
CKEDITOR.replace( 'notice_desc', {
    toolbar: [
        { name: 'document', items: [ 'Source', '-', 'NewPage', 'Preview', '-', 'Templates' ] }, // Defines toolbar group with name (used to create voice label) and items in 3 subgroups.
        [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ],          // Defines toolbar group without name.                                                                                    // Line break - next group will be placed in new line.
        { name: 'basicstyles', items: [ 'Bold', 'Italic' ] },
        '/',
        { name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },
        { name: 'colors', items: [ 'TextColor', 'BGColor' ] }
    ]
}); //new ckeditor instance
/****** END CKEDITOR TOOLBAR SETTING *********/

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


  $('table#invoice_item select[name="item_status"]').live("change", function(e) {
          $a = $(this).val();
          if($a == 1) {
            $(this).removeClass("cancel").addClass( "enable" );
          } else {
            $(this).removeClass("enable").addClass( "cancel" );
          }
  });

  $('select#product_code.myinfo_input').live("change", function(e) { 
      var $product_lookup_url            = $('input#product_lookup_url').val();
      console.log($('select#product_code.myinfo_input').val());
      $.ajax({
        type: "POST",
        dataType: "json",
        data: { pcode: $('select#product_code.myinfo_input').val(), tour_id: $('input#tour_id.myinfo_input').val(), tour_date:  $('input#tour_date.myinfo_input').val() },
        beforeSend: function(x) {
          if(x && x.overrideMimeType) {
            x.overrideMimeType("application/json;charet=UTF-8");
          }
        },
        url: $product_lookup_url,
        success: function(data) {
          if(data !== false) {
              $('input#price').val(data);    
          } else {
              $('input#price').val(""); 
          }
          console.log(data);
      }
      });
  });

  $('input#update-invoice').live("click", function(e) {
    var answer = confirm("Are you sure you want to update Invoice information ?")
    if(answer) {
     var $invoice_id     = $('table#invoice_summary').find("td:eq(1)").attr('id');// This is booking_id // $('table#invoice_summary').find("td:eq(1)").text();
     var $invoice_type   = $('#invoice_summary select#invoice_type').val();
     var $invoice_status = $('#invoice_summary select#invoice_status').val();
     var $cost_approval = $('#invoice_summary select#cost_approval').val();
     var $cost = $('#invoice_summary input#cost').val();
     var $is_ready = $('#invoice_summary select#is_ready').val();
     var $invoice_note   = $('textarea#sales_note').val();
     var $amount_total   = $('input#amount_total_w_gst').val();
     var $gst_total      = $('input#gst_total').val();
     var $invoice_update_url = $('input#invoice_update_url').val();
     //var $notice_desc    = $('#notice_desc.ckeditor').val();
     var editor = CKEDITOR.instances.notice_desc; //reference to instance
     var notice_desc_data = editor.getData(); //reference to ckeditor data

     var $amount_paid    = parseFloat($('span#amount_paid').html()).toFixed(2);

     var $invoice_item_arr = [];
     var $totalPrice     = 0;


     $('table#invoice_item tr.count').each(function(index, value) {
          $id         = $(this).find("td:eq(0)").attr('id');
          $qty        = $('table#invoice_item tr.count input#qty'+index+'').val(); 
          $desc       = $('table#invoice_item tr.count textarea#desc'+index+'').val();
          $price      = $('table#invoice_item tr.count input#unit_price'+index+'').val(); 
          $status     = $('table#invoice_item tr.count select#item_status'+index+'').val(); 
          $is_gst     = $('table#invoice_item tr.count select#item_gst'+index+'').val(); 
          
          console.log($is_gst);
          
          $invoice_item_arr[index] = {
              'id'   :  $id,
              'qty'   : $qty,
              'product_desc'  : $desc,
              'price' : $price,
              'status': $status,
              'is_gst': $is_gst
          };

          if($price != "" || $price != 0) { 
            $totalPrice += parseInt($price * $qty);
          }
     });

     var $balance_due = $totalPrice - $amount_paid;
     if ($invoice_status != 3) {
         if($totalPrice == 0 || $balance_due == 0 ) {
             $invoice_status = 2;
         };
     }

     $.ajax({
        type: "POST",
        dataType: "json",
        data: { get_item: JSON.stringify($invoice_item_arr), get_invoice_type: $invoice_type, get_notice_desc: notice_desc_data, get_invoice_status: $invoice_status, get_is_ready: $is_ready, get_invoice_note: $invoice_note, get_invoice_id: $invoice_id, get_total: $amount_total, get_gst_total: $gst_total },
        beforeSend: function(x) {
          if(x && x.overrideMimeType) {
            x.overrideMimeType("application/json;charet=UTF-8");
          }
        },
        url: $invoice_update_url,
        success: function(data) {
          console.log(data);
          alert("Invoice has been updated.");
          window.location = "#sales-template";
          location.reload(true);
          return false;
        },
        error: function() {
          alert("error");
          return false;
        }
      });
   } else {
      return false;
   }
  });

 $('input#add-item').live("click", function(e) {
  var answer = confirm("Are you sure you want to add an extra item on the invoice ?")
  if (answer){
    var $invoice_id = $('table#invoice_summary').find("td:eq(1)").attr('id');
    var $amount_total   = $('input#amount_total_w_gst').val();
    var $add_qty    = $('table#add_item input#qty').val();
    var $add_price  = $('table#add_item input#price').val();
    var $is_gst  = $('table#add_item select#is_gst_selected').val();
    var $add_desc  = $('table#add_item textarea').val();
    var $add_pcode  = $('select#product_code.myinfo_input').val();
    var $add_item_url = $('input#add_item_url').val();

    $("table#add_item input#qty").removeClass("ui-state-error");
    $("table#add_item input#price").removeClass("ui-state-error");
    $("table#add_item textarea").removeClass("ui-state-error");

    if($add_pcode === "") {
      alert('Please select a product !');
      $("select#product_code.myinfo_input").addClass("ui-state-error");
      return false;
    } else {
      $("select#product_code.myinfo_input").removeClass("ui-state-error");
    }
    if($add_pcode === "TN" || $add_pcode === "TDS" || $add_pcode === "PXNAME" || $add_pcode === "SR" || $add_pcode === "OT") {
        if($add_qty === "") {
           alert('Missing field: Qty !');
           $("table#add_item input#qty").addClass("ui-state-error");
           $( "table#add_item input#qty" ).focus();
           return false;
        } 
        if($add_desc === "") {
           alert('Missing field: Description !');
           $("table#add_item textarea").addClass("ui-state-error");
           $( "table#add_item textarea" ).focus();
           return false;
        } 

    } else {
       if($add_qty === "") {
           alert('Missing field: Qty !');
           $("table#add_item input#qty").addClass("ui-state-error");
           $( "table#add_item input#qty" ).focus();
           return false;
        }
        if($add_price === "") {
           alert('Missing field: Price !');
           $("table#add_item input#price").addClass("ui-state-error");
           $("table#add_item input#price").focus();
           return false;
        }
    }

    if($add_desc === "") {
            $add_desc = "";
    }
      $.ajax({
          type: "POST",
          dataType: "json",
          data: { get_qty: $add_qty, get_price: $add_price, get_desc: $add_desc, get_pcode: $add_pcode, get_invoice_id: $invoice_id, get_total: $amount_total, get_gst: $is_gst},
          beforeSend: function(x) {
            if(x && x.overrideMimeType) {
              x.overrideMimeType("application/json;charet=UTF-8");
            }
          },
          url: $add_item_url,
          success: function(data) {
            //console.log(data);
            alert("Item has been inserted . ");
            window.location = "#sales-template";
            location.reload(true);
          },
          error: function(data) {
            alert("Sorry, there is an error !");
          }
      });

    } else {
      return false;
    }
  });

}); 