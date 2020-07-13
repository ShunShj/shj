/**
 * Created by john- on 31/08/2017.
 */
jQuery(document).ready(function($) {
    // Place ID's of all required fields here.
    //required = ["company", "email1", "licence", "tcf", "manager", "phone", "fax", "address1", "suburb", "postcode", "password"];
    required = ["company", "email1", "phone", "address1", "postcode", "password", "mobile", "manager"];
    // If using an ID other than #email or #error then replace it here
    email = $("#email1");
    errornotice = $("#error");
    // The text to show up within a field when it is incorrect
    emptyerror = "Please fill out this field.";
    emailerror = "Please enter a valid e-mail.";

    $("#myinfo").submit(function(){
        //Validate required fields
        for (i=0;i<required.length;i++) {
            var input = $('#'+required[i]);
            if ((input.val() == "") || (input.val() == emptyerror)) {
                input.addClass("needsfilled");
                input.val(emptyerror);
                errornotice.fadeIn(750);
            } else {
                input.removeClass("needsfilled");
            }
        }

        // Validate the e-mail.
        if(!isValidEmailAddress(email.val())){
            email.addClass("needsfilled");
            email.val(emailerror);
        }

        // Validate the password.
        var password=$("#password");
        if((password.val()+"").length<8){
            password.addClass("needsfilled");
            password.val("Password must have at least eight characters");
        }else{
            if (! /(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}/.test(password.val())) {
                password.addClass("needsfilled");
                password.val("At least one number, one lowercase and one uppercase letter");
            }
        }

        //if any inputs on the page have the class 'needsfilled' the form will not submit
        if ($(":input").hasClass("needsfilled")) {
            return false;
        } else {
            errornotice.hide();
            return true;
        }

    });

    // Clears any fields in the form when the user clicks on them
    $(":input").focus(function(){
        if ($(this).hasClass("needsfilled") ) {
            $(this).val("");
            $(this).removeClass("needsfilled");
        }
    });

    function isValidEmailAddress(emailAddress) {
        var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
        return pattern.test(emailAddress);
    };
});