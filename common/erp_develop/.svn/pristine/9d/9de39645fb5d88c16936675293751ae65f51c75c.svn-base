jQuery(function ($) {

    $("#search_statement_from").mask("9999-99-99");
    $("#search_statement_to").mask("9999-99-99");


    /***************** Agent name autocomplete **************/
    var availableAgents = [];
    $.ajax({
        url: "get_agentname_arr",
        type: "POST",

        success: function (msg) {
            availableAgents = JSON.parse(msg);
            $('#agentcode.search-agent-input').autocomplete({
                source: availableAgents.code
            });
            $("#gen_agent_code").autocomplete({
                source: availableAgents.code
            });
        },
        error: function () {
            //alert('error');
            console.log("error");
        }
    });

    /***************** Statement ID autocomplete **************/
    var $searchStatement = $('#statementIDField.search-field-input');
    var availableStatement = [];
    $.ajax({
        url: "get_statement_arr",
        type: "POST",
        success: function (msg) {
            if (msg != 'false') {
                availableStatement = JSON.parse(msg);
                $searchStatement.autocomplete({
                    source: availableStatement.statement_id
                })
            } else {
                //availableStatement = JSON.parse(msg);
                $searchStatement.autocomplete({
                    source: availableStatement
                })
            }
        },
        error: function () {
            console.log("error");
        }
    });


    return false;

});

function refreshPage() {
    setTimeout(function () {
        window.location = "searchStatement";
        location.reload(true);
    }, 3000);
}

function getCheckedValue() {
    var valObj = [];
    $('#group:checked').each(function () {
        var1 = $(this).val();
        var2 = $(this).closest('tr').find('td:eq(4)').text();
        var3 = $(this).closest('tr').find('td:eq(3)').text();
        valObj.push({
            id: var1,
            code: var2,
            ver: var3
        });
    });
    //$('#t').val(allVals)
    return valObj;
}

// This is a functions that scrolls to #{blah}link
function goToByScroll(id) {
    // Remove "link" from the ID
    id = id.replace("link", "");
    // Scroll
    $('html,body').animate({
            scrollTop: $("#" + id).offset().top
        },
        'slow');
}

function fnGeneratePDF(value, url) {
    $('.progress-label').html("System is generating PDF, please wait... ");
    $('#progressbar').show();
    goToByScroll("progressbar");
    /////// Call PHP create PDF function
    $.post(url, {
            statementObj: value
        },
        function (data) {
            data = JSON.parse(data);
            console.log(data);
            $('#progressbar').hide();
            $("input.email-btn").removeAttr("disabled");
            if (data.status != false) {
                $("div#msg_div").removeClass("error_div").addClass("success_div");
                $('#msg_div').html(" You have successfully generated : " + data.name + " PDFs").show('fade', 800);
                $('#msg_div').delay(3500).fadeOut(800);
                refreshPage();
            } else {
                $('div#msg_div').removeClass("success_div").addClass("error_div");
                $('#msg_div').html("ERROR: " + data.name).show('fade', 800);
            }
        });
}

$(function () {

    var filename = '';
    var progressbar = $("#progressbar"),
        progressLabel = $(".progress-label");
    progressbar.progressbar({
        value: false
    });

    // Download PDFs function
    $('input[name="download-pdfs"].email-btn').on('click', function (e) {
        e.preventDefault();
        if ($('input[type="checkbox"]').is(':checked')) {
            var checkedVal = getCheckedValue();
            var i = 0;
            if (checkedVal.length > 0) {
                $("input.email-btn").attr("disabled", "disabled");
                for (var i = 0; i <= checkedVal.length; i++) {
                    setTimeout(function (x) {
                        return function () {
                            if (checkedVal[x].ver > 0) {
                                filename = "statement_" + checkedVal[x].id + "_" + checkedVal[x].ver + ".pdf";
                                window.location.href = 'downloadFiles?file_name=' + filename;
                            } else {
                                alert("There is no generated pdf for the selected statement:" + checkedVal[x].id);
                            }
                            if (x + 1 == checkedVal.length) {
                                $("input.email-btn").removeAttr("disabled");
                            }
                        };
                    }(i), 1500 * i);
                }
            } else {
                /////// Error Message
                console.log('jQuery function error !');
            }
        } else {
            alert("To download PDFs: Please select at least one statement !");
        }
    });

    //// Group email statement function
    $('input[name="group-email-statement"].email-btn').on('click', function (e) {
        e.preventDefault();
        if ($('input[type="checkbox"]').is(':checked')) {

            var $checkedVal = getCheckedValue();
            $url = "emailStatement";   ///// Email function from php

            if ($checkedVal.length > 0) {
                if (confirm('Are you sure email statement to agents?')) {
                    $("input.email-btn").attr("disabled", "disabled");
                    $('.progress-label').html("System is sending email, please wait... ");
                    $('#progressbar').show();
                    goToByScroll("progressbar");
                    /////// Call PHP email function to send statements
                    $.post($url, {
                            statementObj: $checkedVal

                        },
                        function (data) {
                            //data = JSON.parse(data);
                            //console.log(data);
                            $('#progressbar').hide();
                            $("input.email-btn").removeAttr("disabled");
                            if (data.status != false) {
                                $("div#msg_div").removeClass("error_div").addClass("success_div");
                                $('#msg_div').html(" You have successfully email to : " + data.name + " agents").show('fade', 800);
                                $('#msg_div').delay(3500).fadeOut(800);
                                refreshPage();
                            } else {
                                $('div#msg_div').removeClass("success_div").addClass("error_div");
                                $('#msg_div').html("ERROR: " + data.name).show('fade', 800);
                            }
                        }, "json");
                }
            } else {
                /////// Error Message
                console.log('jQuery function error !');
            }
        } else {
            alert("To send statement to agents: Please select at least one statement !");
        }
    });

    //// Group email statement fucntion
    $('input[name="group-email-supplier-statement"].email-btn').on('click', function (e) {
        e.preventDefault();
        if ($('input[type="checkbox"]').is(':checked')) {

            var $checkedVal = getCheckedValue();
            $url = "emailSupplierStatement";   ///// Email function from php

            if ($checkedVal.length > 0) {
                if (confirm('Are you sure email statement to agents?')) {
                    $("input.email-btn").attr("disabled", "disabled");
                    $('.progress-label').html("System is sending email, please wait... ");
                    $('#progressbar').show();
                    goToByScroll("progressbar");
                    /////// Call PHP email function to send statements
                    $.post($url, {
                            statementObj: $checkedVal

                        },
                        function (data) {
                            //data = JSON.parse(data);
                            //  console.log(data);
                            $('#progressbar').hide();
                            $("input.email-btn").removeAttr("disabled");
                            if (data.status != false) {
                                $("div#msg_div").removeClass("error_div").addClass("success_div");
                                $('#msg_div').html(" You have successfully email to : " + data.name + " agents").show('fade', 800);
                                $('#msg_div').delay(3500).fadeOut(800);
                                refreshPage();
                            } else {
                                $('div#msg_div').removeClass("success_div").addClass("error_div");
                                $('#msg_div').html("ERROR: " + data.name).show('fade', 800);
                            }
                        }, "json");
                }
            } else {
                /////// Error Message
                console.log('jQuery function error !');
            }
        } else {
            alert("To send statement to agents: Please select at least one statement !");
        }
    });

    //// Group generate PDF fucntion
    $('input[name="group-generate-pdf"].email-btn').on('click', function (e) {
        e.preventDefault();
        if ($('input[type="checkbox"]').is(':checked')) {

            var $checkedVal = getCheckedValue();
            $url = "generateStatementPDF";   ///// Create PDF function from php

            if ($checkedVal.length > 0) {
                if (confirm('Are you sure generate PDFs?')) {
                    $("input.email-btn").attr("disabled", "disabled");
                    fnGeneratePDF($checkedVal, $url);
                } //// End Confirm
            } else {
                /////// Error Message
                console.log('jQuery function error !');
            }
            return false;
        } else {
            alert("To create pdfs: Please select at least one statement !");
        }
    });

    //// Group generate PDF fucntion
    $('input[name="group-generate-supplier-pdf"]').on('click', function (e) {
        e.preventDefault();
        if ($('input[type="checkbox"]').is(':checked')) {

            var $checkedVal = getCheckedValue();
            $url = "generateSupplierStatementPDF";   ///// Create PDF function from php

            if ($checkedVal.length > 0) {
                if (confirm('Are you sure generate PDFs?')) {
                    fnGeneratePDF($checkedVal, $url);
                } //// End Confirm
            } else {
                /////// Error Message
                console.log('jQuery function error !');
            }
            return false;
        } else {
            alert("To create pdfs: Please select at least one statement !");
        }
    });

    //// Generate Single PDF fucntion
    $('a.generate-a-pdf').on('click', function (e) {
        e.preventDefault();
        var statementID = $(this).closest('tr').find('td:eq(1)').text();
        var agentCode = $(this).closest('tr').find('td:eq(4)').text();
        var valObj = [];
        if (statementID != "" && agentCode != "") {
            valObj.push({id: statementID, code: agentCode});
            url = "generateStatementPDF";   ///// Create PDF function from php
            if (confirm('Are you sure to generate a PDF?')) {
                fnGeneratePDF(valObj, url);
            } //// End Confirm
        } else {
            ///// Display Error
            return false;
        }
    });

    $('a.generate-a-pdf-supplier').on('click', function (e) {
        e.preventDefault();
        var statementID = $(this).closest('tr').find('td:eq(1)').text();
        var agentCode = $(this).closest('tr').find('td:eq(4)').text();
        var valObj = [];
        if (statementID != "" && agentCode != "") {
            valObj.push({id: statementID, code: agentCode});
            url = "generateSupplierStatementPDF";   ///// Create PDF function from php
            if (confirm('Are you sure to generate a PDF?')) {
                fnGeneratePDF(valObj, url);
            } //// End Confirm
        } else {
            ///// Display Error
            return false;
        }
    });

    ///// Load PDF Version

    $('div#doc-popup').mouseout(function (e) {
        $('div#doc-popup').hide();
    });
    $('div#doc-popup').mouseover(function (e) {
        $('div#doc-popup').show();
    });

    var getId = "";

    $('tr td').hover(function (e) {
        if (getId != $(this).attr('id')) {
            $('div#doc-popup').hide();
        }
    });

    $('td span.nh-folder').hover(function (e) {

        if (getId == $(this).closest('td').attr('id')) {

            $('div#doc-popup').show();

        } else {
            $('div#doc-popup').hide();
        }
        getId = $(this).closest('td').attr('id');

        //console.log(getId);

        var verNum = $(this).closest('tr').find('td.ver').text();
        var statementID = $(this).closest('tr').find('td.statement_id').text();

        if (verNum > 0) {
            var offset=$(this).offset();
            $('div#doc-popup').show()
                .css('top', offset.top+10)
                .css('left', offset.left+60)
                .css('text-align', 'center')
                .css('position', 'absolute')
                .css('border', 1)
                .css('padding', 2)
                .css('color', '#FFF')
                .css('margin-left', '-220px')
                .css('background-color', '#000')
                .css('fron-size', '8px')
                .appendTo('body');
            /*.css('top', e.pageY)
             .css('left', '1810px')
             .css('text-align', 'center')
             .css('position', 'absolute')
             .css('border', 1)
             .css('margin-left', '-200px')
             .css('background-color', '#ccc')

             .css('fron-size', '8px')
             .appendTo('table#admin_invoice_table');
             */
            data_table = "<h3>Available PDF version</h3>";
            data_table += "<table class='pdf-version' cellspacing='0'>";
            data_table += "<tr>";
            data_table += "<th>Download</th><th>Ver</th>";
            data_table += "</tr>";
            for (a = 0; a < verNum; a++) {
                docVer = a + 1;
                data_table += "<tr><td>";
                data_table += "<a style='color:red;' href='view_statement_pdf/statement_" + statementID + "_" + (verNum - a) + ".pdf' target='_blank'>" + statementID + "</a>";
                data_table += "</td><td>";
                data_table += (verNum - a) + "</td></tr>";
            }
            data_table += "</table>";
            //console.log(data_table);
            $('div#doc-popup p').html(data_table);

        } else {
            $('div#doc-popup').hide();
        }
    }, function () {
        $('div#doc-popup').hide();
    });

    //// Check and uncheck all function
    $('#select_all').on('click', function (e) {
        e.preventDefault();
        if ($('input[type="checkbox"]').is(':checked')) {
            $('input[type="checkbox"]').prop("checked", false);
        } else {
            $('input[type="checkbox"]').prop("checked", true);
        }
    });

    ///// Generate statement function
    $("input[name='generate_statement']").on("click", function (e) {
        e.preventDefault();
        var $agent = $("input#gen_agent_code").val();
        var $type = $("select#tourtype").val();
        var $withCredit=$("input#with_credit").is(":checked")?1:0;
        $url = "generateNewStatement";
        //$( "div#msg_div" ).removeClass( "error_div" );
        if (confirm('Are you sure to generate statements?')) {
            $('#msg_div').hide('fade', 800);
            $("input#date_to").removeClass("ui-state-error");
            $("input#date_from").removeClass("ui-state-error");

            goToByScroll("progressbar");
            $('.progress-label').html("System is generating Statements, please wait... ");
            $('#progressbar').show();

            ///// GET SUCCESS OR ERROR MESSAGE
            ///// generateNewStatement
            $.post($url, {
                    gen_agent_code: $agent,
                    type: $type,
                    with_credit:$withCredit
                },
                function (data) {
                    //$msg = JSON.parse(data);
                    console.log(data.name);
                    $('#progressbar').hide();
                    goToByScroll("msg_div");
                    if (data.name != false) {
                        $("div#msg_div").removeClass("error_div").addClass("success_div");
                        $('#msg_div').html(" You have successfully generated : " + data.name + " statements").show('fade', 800);

                        $('#msg_div').delay(3500).fadeOut(800);
                        refreshPage();
                    } else {

                        $('div#msg_div').removeClass("success_div").addClass("error_div");
                        if (data.problem == "DB") {
                            $('#msg_div').html("DB Error: Unsuccessful !").show('fade', 800);
                        }
                        if (data.problem == "INVOICE") {
                            $('#msg_div').html("No invoice found for generate statements !").show('fade', 800);
                        }
                    }
                }, "json");
            return false;
        }
    });

    ///// Generate statement function
    $("input[name='generate_supplier_statement']").on("click", function (e) {
        e.preventDefault();
        var $from = $("input#date_from").val();
        var $to = $("input#date_to").val();
        $url = "generateSupplierStatement";
        if ($from == "" || $to == "") {
            //$('#error_div').html("Please enter date from and date to !");
            if ($to == "") {
                $("input#date_to").addClass("ui-state-error");
                $("input#date_from").removeClass("ui-state-error");
                $('input#date_to').focus();
                $('div#msg_div').removeClass("success_div").addClass("error_div");
                $('#msg_div').html("Please enter to tour departure date. ").show('fade', 800);
            }
            if ($from == "") {
                $("input#date_from").addClass("ui-state-error");
                $("input#date_to").removeClass("ui-state-error");
                $("input#date_from").focus();
                $("div#msg_div").removeClass("success_div").addClass("error_div");
                $('#msg_div').html("Please enter from tour departure date. ").show('fade', 800);
            }
            return false;
        } else {
            //$( "div#msg_div" ).removeClass( "error_div" );
            if (confirm('Are you sure to generate statements?')) {
                $('#msg_div').hide('fade', 800);
                $("input#date_to").removeClass("ui-state-error");
                $("input#date_from").removeClass("ui-state-error");

                goToByScroll("progressbar");
                $('.progress-label').html("System is generating Statements, please wait... ");
                $('#progressbar').show();

                ///// GET SUCCESS OR ERROR MESSAGE
                ///// generateNewStatement
                $.post($url, {
                        from: $from,
                        to: $to,
                    },
                    function (data) {
                        //$msg = JSON.parse(data);
                        console.log(data.name);
                        $('#progressbar').hide();
                        goToByScroll("msg_div");
                        if (data.name != false) {
                            $("div#msg_div").removeClass("error_div").addClass("success_div");
                            $('#msg_div').html(" You have successfully generated : " + data.name + " statements").show('fade', 800);

                            $('#msg_div').delay(3500).fadeOut(800);
                            refreshPage();
                        } else {

                            $('div#msg_div').removeClass("success_div").addClass("error_div");
                            if (data.problem == "DB") {
                                $('#msg_div').html("DB Error: Unsuccessful !").show('fade', 800);
                            }
                            if (data.problem == "INVOICE") {
                                $('#msg_div').html("No invoice found for generate statements !").show('fade', 800);
                            }
                        }
                    }, "json");
                return false;
            }
        }
    });

}); 