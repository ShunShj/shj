var checkedPax;
var selectedRoomPax;
var roomType;
var paxValue;
var age_cat = [];
var val     = [];
var ageCat  = [];
var ageInfo  = [];
var paxInfo = [];
var myObj    =  {};
var roomObj = new Array();
//var roomCapArr = <?php echo json_encode($all_rooms) ?>;
//var roomArr[info] = {id: id, type: type, name: name, age: age}
var $a      = 1;
var maxPax;
var maxExtra;
var warning = $(".message");

/**
 *  This function is using for select room type 
 *  It assigns the roomType and maxPax value
 *  value will be assigned when people select the room type box
 **/
$("select.room_type_form_select").change(function() {
    // Get value from room type
    roomType = $(".room_type_form_select").val();

    // assign name to roomType and give it capacity
    switch(roomType) 
    {

      case '0':
        roomType = 'Single';
        maxPax      =  1;
        break;
      case '1':
        roomType = 'Double';
        maxPax      =  2;
        break;
      case '2':
        roomType = 'Twin';
        maxPax      =  2;
        break;
      case '3':
        roomType = 'Triple';
        maxPax      =  3;
        break;     
      case '4':
        roomType = 'Quad';
        maxPax      =  4;
        break;  
    }
    maxExtra = maxPax + 1;
});


/**
*  This is function when people click confirm button
**/
$(".add").click(function() {
  var i = 0;
  var tourPax = '';
  roomType = $("select.room_type_form_select").val();

   // assign name to roomType and give it capacity
    switch(roomType) 
    {

      case '0':
        roomType = 'Single';
        maxPax      =  1;
        break;
      case '1':
        roomType = 'Double';
        maxPax      =  2;
        break;
      case '2':
        roomType = 'Twin';
        maxPax      =  2;
        break;
      case '3':
        roomType = 'Triple';
        maxPax      =  3;
        break;     
      case '4':
        roomType = 'Quad';
        maxPax      =  4;
        break;  
    }
     maxExtra = maxPax + 1;

  // Room validation
  if(!roomType) 
  {
     warning.addClass("error").removeClass("success").html("Please select a room type !");
     return false;
  } 
  else 
  {
    warning.addClass("success").removeClass("error").html("");
    //$('select.room_type_form_select', this).remove();

    // Create myObj as new Object when room is selected
    
    myObj['roomType']   = roomType;
    myObj['maxPax']     = maxPax;
  }

  // Get value from room type
  paxValue = $(".room_pax_form_select").val();

  // Check if paxValue is null
  if(!paxValue) {
    // show error message remove success message
    warning.addClass("error").removeClass("success").html("Please select a passenger !");
     return false;
  }
  else
  {
    // show success message remove error message
    warning.addClass("success").removeClass("error").html("");   
  }

    // check if there is a child in the selected room
    $(':checkbox:checked').each(function(i){
      
          val[i]           = $(this).val(); 

          age_cat          = val[i].split('|');
          ageInfo[i]       = age_cat[1];
          paxInfo[i]       = age_cat[0];

          if(age_cat[1] != "Adult")
          {
              maxPax = maxExtra;
          }
          tourPax += "<li>";
          tourPax += val[i];
          tourPax += "</li>";
    });
    myObj['paxName'] = paxInfo;
    myObj['ageCat'] = ageInfo;
    // check if room type and select passenger are match
    // Compare maxPax and selected Pax
    if(paxValue.length > maxPax)
    {
          // show error message remove success message
          warning.addClass("error").removeClass("success").html("Room maximum capacity is "+maxPax+"");
          return false;
    } 
    else
    {
      // show success message remove error message
      warning.addClass("success").removeClass("error").html("");
      
      // Disable selected passenger when item added to room_pax
       var el = $("select.room_pax_form_select").multiselect(),
            disabled = $('#disabled'),
            selected = $('#selected');

      if(':checkbox:checked'){
        $("select.room_pax_form_select option:checked").attr('disabled','disabled');
        $("select.room_pax_form_select option:checked").removeAttr('selected');
      }

      if(selected.is(':checked')){
        opt.attr('selected','selected');
      }
      
      $("select.room_pax_form_select").appendTo( el );
      
      el.multiselect('refresh');

      var roomInfo = "<hr /><p id='selectedRoom"+$a+"'><span style='color:#3376bb; font-size:20px;'> "+$a+". "+roomType +" Room</span><span><ol style='margin-top:-25px; margin-left: 500px; color:#3376bb; font-size:16px; height:auto;'>"+ tourPax +"</ol></span></p>";

      $("#room_pax").append(roomInfo);  

      // convert object to be a json object
      roomObj.push(JSON.stringify(myObj));

      $a++;

    }
});


$('input[name="submit"]').click(function() {
  // Send data to php using ajax
  
$.ajax({
      type: "POST",
      dataType: "json",
      data: { get_param: roomObj },
      beforeSend: function(x) {
        if(x && x.overrideMimeType) {
          x.overrideMimeType("application/json;charet=UTF-8");
        }
      },
      url: 'service_requests',
      success: function(data) {
        // 'data' is a JSON object which we can access directly.
        // Evaluate the data.success member and do something appropriate...
        if(data.success == true) {
          alert('TRUE');
        } else {
          alert(data.room);
          $('#room_pax').html( 'Room: '+ data.room );
        }
        
      }

  });
});




 
/**
 *  This is for style and function the passenger multiselect box
 **/
$(function(){
  $("select.room_pax_form_select").multiselect({ 
   //selectedList: 0 // 0-based index   

     selectedText: function(numChecked, numTotal, checkedItems){
      return numChecked + ' of ' + numTotal + ' checked';
    }
  });
});

/**
 *  This is for reset function
 **/ 
$(".reset").click(function() {
  ConfirmDialog('Are you sure to reset step 4 ?');
});

function ConfirmDialog(message){
    $('<div></div>').appendTo('body')
      .html('<div><h6>'+message+'?</h6></div>')
      .dialog({
          modal: true, title: 'Reset', zIndex: 1000, autoOpen: true,
          width: '300px', resizable: false,
          buttons: {
              Yes: function () {
                  $("#room_pax").empty();
                  $(this).dialog("close");
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