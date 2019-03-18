<style type="text/css">
    ul#vr_day_list, ul#sms_day_list, ul#pgw_day_list {
        /*margin-left: 0px;*/
        margin-top: 0px;
        padding-top: 0px;
        padding-left: 0px;
        /*padding-right: 0px;*/
    }
    ul#vr_day_list li, ul#sms_day_list li, ul#pgw_day_list li {
        display:inline;
        font-size: 70%;
        padding-left: 5%;
        padding-right: 5%;
    }
    ul#vr_day_list li a {
        color: #979696;

    }
    .online {
        font-weight: bold;

    }
    ul#sms_day_list li a {
        color: #979696;

    }
    ul#pgw_day_list li a {
        color: #979696;

    }

    .chart {
        width: 100%;
        /*min-height: 450px;*/
    }
    .row {
        margin:0 !important;
    }

    .infoboxtextsize{
        text-align: center;
        /*font-size: 125%;*/
        height: 90px;
        padding-top: 10px
    }

    .knob-label{
        font-family:verdana;
        font-size:70%;
        padding-top: 10px;
        padding-bottom: 10px
    }
</style>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script>
    var vrDateShow;
    var vrAmountShow;
    var vrCountShow;
    var vrUpDownShow;
    var vrAmountAverage;
    var vrCurrentMonthShow;
    var vrLastMonthShow;
    var vrKnob;
    var smsDateShow;
    var smsAmountShow;
    var smsAmountAverage;
    var smsCountShow;
    var smsUpDownShow;
    var smsCurrentMonthShow;
    var smsLastMonthShow;
    var smsKnob;
    var pgwDateShow;
    var pgwAmountShow;
    var pgwCountShow;
    var pgwUpDownShow;
    var pgwAmountAverage;
    var pgwCurrentMonthShow;
    var pgwLastMonthShow;
    var pgwKnob;
    var getDate;
    var vrGetDate;
    var smsGetDate;
    var pgwGetDate;
    var lastSevenDayVRData;
    var lastSevenDaySMSData;
    var lastSevenDayPGWData;

    var date="";
    var a;
    $('#chartModalBody').addClass('table-responsive');
    $(function () {
        $(".knob").knob({
            "cursor" : false,
            "stopper" : false,
            "readOnly" : true,
            parse: function (v) {a=v; return parseInt(v);},
            format: function (v) {return a+"%";}
        });

    });


    // ============================================================== show user ==================================================
    function showUser(element) {
        var url = "";
        if(element== "vractive" || element== "smsactive" || element== "pgwactive") url = "dashboard/activeclient?getDate="+getDate+"&element="+element;
        else if(element== "vrnew" || element== "smsnew" || element== "pgwnew") url = "dashboard/newclient?getDate="+getDate+"&element="+element;
        else if(element== "vrinactive" || element== "smsinactive" || element== "pgwinactive") url = "dashboard/inactiveclient?getDate="+getDate+"&element="+element;
        else if(element== "vrdropclient" || element== "vrdropclientlist" ) url = "dashboard/dropclient?getDate="+vrGetDate+"&element="+element;
        else if(element== "smsdropclient" || element== "smsdropclientlist" ) url = "dashboard/dropclient?getDate="+smsGetDate+"&element="+element;
        else if(element== "pgwdropclient" || element== "pgwdropclientlist" ) url = "dashboard/dropclient?getDate="+pgwGetDate+"&element="+element;
        else url = "dashboard/data?getDate="+getDate+"&element="+element;
        $.ajax({
            url: url,
            type: 'GET',
            dataType: "json",
            success:function(data){
                console.log(data);
                if(element=="vrHourChart") areaChart(data,"#00c0ef","vrHourChart");
                if(element=="smsWeekChart") areaChart(data,"#00a65a","smsWeekChart");
                if(element=="pgwHourChart") areaChart(data,"#f39c12","pgwHourChart");
                if(element=="vrTargetChart") targetData(data,"vrTargetChart");
                if(element=="smsTargetChart") targetData(data,"smsTargetChart");
                if(element=="pgwTargetChart") targetData(data, "pgwTargetChart");
                if(element== "vrdropclient" || element== "smsdropclient" || element== "pgwdropclient"){
                    var temp = data['dropCount'] + "%";
                    // if(data['dropCount'] < 5) temp = "5%" ;
                    // else if(data['dropCount'] < 10) temp = "10%" ;
                    // else if(data['dropCount'] < 15) temp = "15%" ;
                    // else temp = ((data['dropCount'] / data['totalCount'])/100) + "%" ;
                    if(element== "vrdropclient"){
                        $('#vrdropclient').css("width", temp);
                        $('#vrdropclient').html(data['dropCount']);
                    }
                    if(element== "smsdropclient"){
                        $('#smsdropclient').css("width", temp);
                        $('#smsdropclient').html(data['dropCount']);
                    }
                    if(element== "pgwdropclient"){
                        $('#pgwdropclient').css("width", temp);
                        $('#pgwdropclient').html(data['dropCount']);
                    }
                }
                if(element== "vrnew") {
                    $('#vr_d_new').html((data['vr']["1d"]).toLocaleString());
                    $('#vr_w_new').html((data['vr']["7d"]).toLocaleString());
                    $('#vr_m_new').html((data['vr']["1m"]).toLocaleString());
                    $('#vr_3m_new').html((data['vr']["3m"]).toLocaleString());
                    $('#vr_3mg_new').html((data['vr']["6m"]).toLocaleString());
                }
                if(element== "smsnew") {
                    $('#sms_d_new').html((data['sms']["1d"]).toLocaleString());
                    $('#sms_w_new').html((data['sms']["7d"]).toLocaleString());
                    $('#sms_m_new').html((data['sms']["1m"]).toLocaleString());
                    $('#sms_3m_new').html((data['sms']["3m"]).toLocaleString());
                    $('#sms_3mg_new').html((data['sms']["6m"]).toLocaleString());
                }
                if(element== "pgwnew") {
                    $('#pgw_d_new').html((data['pgw']["1d"]).toLocaleString());
                    $('#pgw_w_new').html((data['pgw']["7d"]).toLocaleString());
                    $('#pgw_m_new').html((data['pgw']["1m"]).toLocaleString());
                    $('#pgw_3m_new').html((data['pgw']["3m"]).toLocaleString());
                    $('#pgw_3mg_new').html((data['pgw']["6m"]).toLocaleString());
                }
                if(element== "vractive") {
                    $('#vr_d_active').html((data['vr']["1d"]).toLocaleString());
                    $('#vr_w_active').html((data['vr']["7d"]).toLocaleString());
                    $('#vr_m_active').html((data['vr']["1m"]).toLocaleString());
                    $('#vr_3m_active').html((data['vr']["3m"]).toLocaleString());
                    $('#vr_3mg_active').html((data['vr']["6m"]).toLocaleString());
                }
                if(element== "smsactive") {
                    $('#sms_d_active').html((data['sms']["1d"]).toLocaleString());
                    $('#sms_w_active').html((data['sms']["7d"]).toLocaleString());
                    $('#sms_m_active').html((data['sms']["1m"]).toLocaleString());
                    $('#sms_3m_active').html((data['sms']["3m"]).toLocaleString());
                    $('#sms_3mg_active').html((data['sms']["6m"]).toLocaleString());
                }
                if(element== "pgwactive") {
                    $('#pgw_d_active').html((data['pgw']["1d"]).toLocaleString());
                    $('#pgw_w_active').html((data['pgw']["7d"]).toLocaleString());
                    $('#pgw_m_active').html((data['pgw']["1m"]).toLocaleString());
                    $('#pgw_3m_active').html((data['pgw']["3m"]).toLocaleString());
                    $('#pgw_3mg_active').html((data['pgw']["6m"]).toLocaleString());
                }
                if(element== "vrinactive") {
                    $('#vr_d_inactive').html((data['vr']["1d"]).toLocaleString());
                    $('#vr_w_inactive').html((data['vr']["7d"]).toLocaleString());
                    $('#vr_m_inactive').html((data['vr']["1m"]).toLocaleString());
                    $('#vr_3m_inactive').html((data['vr']["3m"]).toLocaleString());
                    $('#vr_3mg_inactive').html((data['vr']["6m"]).toLocaleString());
                }
                if(element== "smsinactive") {
                    $('#sms_d_inactive').html((data['sms']["1d"]).toLocaleString());
                    $('#sms_w_inactive').html((data['sms']["7d"]).toLocaleString());
                    $('#sms_m_inactive').html((data['sms']["1m"]).toLocaleString());
                    $('#sms_3m_inactive').html((data['sms']["3m"]).toLocaleString());
                    $('#sms_3mg_inactive').html((data['sms']["6m"]).toLocaleString());
                }
                if(element== "pgwinactive") {
                    $('#pgw_d_inactive').html((data['pgw']["1d"]).toLocaleString());
                    $('#pgw_w_inactive').html((data['pgw']["7d"]).toLocaleString());
                    $('#pgw_m_inactive').html((data['pgw']["1m"]).toLocaleString());
                    $('#pgw_3m_inactive').html((data['pgw']["3m"]).toLocaleString());
                    $('#pgw_3mg_inactive').html((data['pgw']["6m"]).toLocaleString());
                }

                if(element == "pgwdropclientlist"){
                    $('#chartModalCloseButton').html('<button type="button" class="close" data-dismiss="modal">&times;</button>');
                    $('#modalClose').hide();
                    $('#chartModal').modal('toggle');
                    document.getElementById("chartModalBody").innerHTML="<table id='dataTable' class='display nowrap' style='width:100%'> <thead> <tr> <th>Client</th><th>Start Date</th><th>End Date</th><th>Active</th><th>AON</th><th>Amount</th><th>Bank</th><th>SSL Part</th><th>Ticket Size</th><th>Score</th></tr> </thead> <tfoot></tfoot> </table>";
                    if(data.length==0)alert("There is no data for this filter");
                    else {
                        $('#dataTable').DataTable( {
                            "data": data,
                            "columns": [
                                { 'data':"client" },
                                { 'data':"st_date" },
                                { 'data':"en_date" },
                                { 'data':"Active" },
                                { 'data':"AON" },
                                { 'data':"Amount" ,render: $.fn.dataTable.render.number( ',', '.', 0, '' )},
                                { 'data':"bank" ,render: $.fn.dataTable.render.number( ',', '.', 0, '' )},
                                { 'data':"sslpart" ,render: $.fn.dataTable.render.number( ',', '.', 0, '' )},
                                { 'data':"Tsize" ,render: $.fn.dataTable.render.number( ',', '.', 0, '' )},
                                { 'data':"score"}
                            ],
                            aLengthMenu: [
                                [20, 50, 100, -1],
                                [ '20 rows', '50 rows', '100 rows', 'Show all' ]
                            ],
                            iDisplayLength: 20,
                        });
                    }
                }

                if(element == "vrdropclientlist" || element == "smsdropclientlist"){
                    $('#chartModalCloseButton').html('<button type="button" class="close" data-dismiss="modal">&times;</button>');
                    $('#modalClose').hide();
                    $('#chartModal').modal('toggle');
                    document.getElementById("chartModalBody").innerHTML="<table id='dataTable' class='display nowrap' style='width:100%'> <thead> <tr> <th>Client</th><th>Start Date</th><th>End Date</th><th>Active</th><th>AON</th><th>Amount</th><th>Ticket Size</th><th>Score</th></tr> </thead> <tfoot></tfoot> </table>";
                    if(data.length==0)alert("There is no data for this filter");
                    else {
                        $('#dataTable').DataTable( {
                            "data": data,
                            "columns": [
                                { 'data':"client" },
                                { 'data':"st_date" },
                                { 'data':"en_date" },
                                { 'data':"Active" },
                                { 'data':"AON" },
                                { 'data':"amount" ,render: $.fn.dataTable.render.number( ',', '.', 0, '' )},
                                { 'data':"Tsize" ,render: $.fn.dataTable.render.number( ',', '.', 0, '' )},
                                { 'data':"score"}
                            ],
                            aLengthMenu: [
                                [20, 50, 100, -1],
                                [ '20 rows', '50 rows', '100 rows', 'Show all' ]
                            ],
                            iDisplayLength: 20,
                        });
                    }
                }


            },
            error: function (){alert('error');}
        });

    }

    // ========================================================== Modal table data AJAX =============================================
    function dashboardModal(date, element, subelement, titleHeader){
        var url = "dashboard/"+element;
        $('#myModal').modal('toggle');
        // $('#chartModalCloseButton').html('<button type="button" class="close" data-dismiss="modal">&times;</button>');
        // $('#modalClose').hide();
        // $('#chartModal').modal('toggle');
        // $('#chartModalTitle').html(titleHeader);
        // document.getElementById("chartModalBody").innerHTML="<table id='dataTable' class='display nowrap' style='width:100%'> <thead> <tr> <th>Client</th> <th>Start Date</th> <th>Start Amount</th> <th>End Date</th> <th>End Amount</th> </tr> </thead> <tfoot></tfoot> </table>";
        $.ajax({
            url: url,
            type: 'GET',
            dataType: "json",
            data: {
                getDate : date,
                element : subelement
            },
            success:function(data){
                $('#myModal').modal('toggle');
                $('#chartModalCloseButton').html('<button type="button" class="close" data-dismiss="modal">&times;</button>');
                $('#modalClose').hide();
                $('#chartModal').modal('toggle');
                $('#chartModalTitle').html(titleHeader);
                document.getElementById("chartModalBody").innerHTML="<table id='dataTable' class='display nowrap' style='width:100%'> <thead> <tr> <th>Client</th> <th>Start Date</th> <th>Start Amount</th> <th>End Date</th> <th>End Amount</th> </tr> </thead> <tfoot></tfoot> </table>";
                console.log(data);
                if(data.length==0)alert("There is no data for this filter");
                else {
                    $('#dataTable').DataTable( {
                        "data": data,
                        "columns": [
                            { 'data':"client" },
                            { 'data':"st_date" },
                            { 'data':"st_amount" ,render: $.fn.dataTable.render.number( ',', '.', 0, '' )},
                            { 'data':"en_date" },
                            { 'data':"en_amount" ,render: $.fn.dataTable.render.number( ',', '.', 0, '' )}
                        ],
                        // dom: 'Bfrtip',
                        aLengthMenu: [
                            [20, 50, 100, -1],
                            [ '20 rows', '50 rows', '100 rows', 'Show all' ]
                        ],
                        iDisplayLength: 20,
                        // buttons: [
                        //     'pageLength','copy',{
                        //         extend: 'csv',
                        //         messageTop: header,
                        //         footer:true
                        //     },{
                        //         extend: 'excel',
                        //         messageTop: header,
                        //         footer:true
                        //     }, {
                        //         extend: 'print',
                        //         messageTop: header,
                        //         footer:true
                        //     }
                        // ]
                    });
                }
                $('#tableDataBox').show();
            },
            error: function (){
                alert('error table');
                $('#chartModal').modal('toggle');
                // $('#tableDataBox').show();
            }

        });
    }

    // ========================================================== int ajax =====================================================
    $(document).ready(function() {
        // ....................................Select >> vr,sms,pgw 7days data load..................................
        $.ajax({
            url: 'dashboard/init',
            type: 'GET',
            dataType: "json",
            success:function(data){
                lastSevenDayVRData = data['vr'];
                lastSevenDaySMSData = data['sms'];
                lastSevenDayPGWData = data['pgw'];

                vrDateShow = lastSevenDayVRData[0][1];
                vrAmountShow = lastSevenDayVRData[0][3];
                vrCountShow = lastSevenDayVRData[0][2];
                getDate = lastSevenDayVRData[0][7];
                vrGetDate = lastSevenDayVRData[0][7];
                showUser("vrTargetChart");
                showUser("vrHourChart");
                showUser("vrnew");
                showUser("vractive");
                showUser("vrinactive");
                showUser("vrdropclient");


                smsDateShow = lastSevenDaySMSData[0][1];
                smsAmountShow = lastSevenDaySMSData[0][3];
                smsCountShow = lastSevenDaySMSData[0][2];
                getDate = lastSevenDaySMSData[0][7];
                smsGetDate = lastSevenDaySMSData[0][7];
                showUser("smsTargetChart");
                showUser("smsWeekChart");
                showUser("smsnew");
                showUser("smsactive");
                showUser("smsinactive");
                showUser("smsdropclient");


                pgwDateShow = lastSevenDayPGWData[0][1];
                pgwAmountShow = lastSevenDayPGWData[0][3];
                pgwCountShow = lastSevenDayPGWData[0][2];
                getDate = lastSevenDayPGWData[0][7];
                pgwGetDate = lastSevenDayPGWData[0][7];
                showUser("pgwTargetChart");
                showUser("pgwHourChart");
                showUser("pgwnew");
                showUser("pgwactive");
                showUser("pgwinactive");
                showUser("pgwdropclient");

                setVRInfoBoxFields();
                setSMSInfoBoxFields();
                setPGWInfoBoxFields();

                $('#vr_0').html(lastSevenDayVRData[0][0]);
                $('#vr_1').html(lastSevenDayVRData[1][0]);
                $('#vr_2').html(lastSevenDayVRData[2][0]);
                $('#vr_3').html(lastSevenDayVRData[3][0]);
                $('#vr_4').html(lastSevenDayVRData[4][0]);
                $('#vr_5').html(lastSevenDayVRData[5][0]);
                $('#vr_6').html(lastSevenDayVRData[6][0]);

                $('#sms_0').html(lastSevenDaySMSData[0][0]);
                $('#sms_1').html(lastSevenDaySMSData[1][0]);
                $('#sms_2').html(lastSevenDaySMSData[2][0]);
                $('#sms_3').html(lastSevenDaySMSData[3][0]);
                $('#sms_4').html(lastSevenDaySMSData[4][0]);
                $('#sms_5').html(lastSevenDaySMSData[5][0]);
                $('#sms_6').html(lastSevenDaySMSData[6][0]);

                $('#pgw_0').html(lastSevenDayPGWData[0][0]);
                $('#pgw_1').html(lastSevenDayPGWData[1][0]);
                $('#pgw_2').html(lastSevenDayPGWData[2][0]);
                $('#pgw_3').html(lastSevenDayPGWData[3][0]);
                $('#pgw_4').html(lastSevenDayPGWData[4][0]);
                $('#pgw_5').html(lastSevenDayPGWData[5][0]);
                $('#pgw_6').html(lastSevenDayPGWData[6][0]);

                // showUser("activeclient");
                // showUser("inactiveclient");
            },
            error: function (){alert('error');}
        });

        // $.ajax({
        //     url: 'dashboard/activeclient',
        //     type: 'GET',
        //     dataType: "json",
        //     success:function(data){
        //         alert(data);
        //     },
        //     error: function (){alert('error');}
        // });
        //
        // $.ajax({
        //     url: 'dashboard/inactiveclient',
        //     type: 'GET',
        //     dataType: "json",
        //     success:function(data){
        //         alert(data);
        //     },
        //     error: function (){alert('error');}
        // });
    });

</script>

<script type="text/javascript">

    $("#vr_day_list").html(
        "<li><a id=\"vr_6\" href=\"#\" ></a></li>"+
        "<li><a id=\"vr_5\" href=\"#\" ></a></li>"+
        "<li><a id=\"vr_4\" href=\"#\" ></a></li>"+
        "<li><a id=\"vr_3\" href=\"#\" ></a></li>"+
        "<li><a id=\"vr_2\" href=\"#\" ></a></li>"+
        "<li><a id=\"vr_1\" href=\"#\" ></a></li>"+
        "<li><a id=\"vr_0\" href=\"#\" class=\"online\" style=\"padding-right: 0px\"></a></li>"
    );

    $("#sms_day_list").html(
        "<li><a id=\"sms_6\" href=\"#\" ></a></li>"+
        "<li><a id=\"sms_5\" href=\"#\" ></a></li>"+
        "<li><a id=\"sms_4\" href=\"#\" ></a></li>"+
        "<li><a id=\"sms_3\" href=\"#\" ></a></li>"+
        "<li><a id=\"sms_2\" href=\"#\" ></a></li>"+
        "<li><a id=\"sms_1\" href=\"#\" ></a></li>"+
        "<li><a id=\"sms_0\" href=\"#\" class=\"online\" style=\"padding-right: 0px\"></a></li>"
    );

    $("#pgw_day_list").html(
        "<li><a id=\"pgw_6\" href=\"#\" ></a></li>"+
        "<li><a id=\"pgw_5\" href=\"#\" ></a></li>"+
        "<li><a id=\"pgw_4\" href=\"#\" ></a></li>"+
        "<li><a id=\"pgw_3\" href=\"#\" ></a></li>"+
        "<li><a id=\"pgw_2\" href=\"#\" ></a></li>"+
        "<li><a id=\"pgw_1\" href=\"#\" ></a></li>"+
        "<li><a id=\"pgw_0\" href=\"#\" class=\"online\" style=\"padding-right: 0px\"></a></li>"
    );

    $("#vr_amount").click(function(){
        $("#vr_infobox_title").html("Amount");
        $("#vr_amount_count").html(vrAmountShow);
        $("#vr_sup").html("M");
        $("#vr_amount").attr("class","online");
        $("#vr_count").attr("class","");
    });
    $("#vr_count").click(function(){
        $("#vr_infobox_title").html("Count");
        $("#vr_amount_count").html(vrCountShow);
        $("#vr_sup").html("");
        $("#vr_amount").attr("class","");
        $("#vr_count").attr("class","online");
    });
    $("#sms_amount").click(function(){
        $("#sms_infobox_title").html("Amount");
        $("#sms_amount_count").html(smsAmountShow);
        $("#sms_sup").html("M");
        $("#sms_amount").attr("class","online");
        $("#sms_count").attr("class","");
    });
    $("#sms_count").click(function(){
        $("#sms_infobox_title").html("Count");
        $("#sms_amount_count").html(smsCountShow);
        $("#sms_sup").html("M");
        $("#sms_amount").attr("class","");
        $("#sms_count").attr("class","online");
    });
    $("#pgw_amount").click(function(){
        $("#pgw_infobox_title").html("Amount");
        $("#pgw_amount_count").html(pgwAmountShow)
        $("#pgw_sup").html("M");
        $("#pgw_amount").attr("class","online");
        $("#pgw_count").attr("class","");
    });
    $("#pgw_count").click(function(){
        $("#pgw_infobox_title").html("Count");
        $("#pgw_amount_count").html(pgwCountShow);
        $("#pgw_sup").html("");
        $("#pgw_amount").attr("class","");
        $("#pgw_count").attr("class","online");
    });


</script>

<script type="text/javascript">
    function setVRInfoBoxFields(){
        $("#vrDateShow").html(vrDateShow);
        $("#vr_infobox_title").html("Amount");
        $("#vr_amount_count").html(vrAmountShow);
        $("#vr_sup").html("M");
        $("#vrKnob").val(vrKnob);
        $("#vrKnob").trigger('change');
        $("#vrCurrentMonthShow").html(vrCurrentMonthShow);
        $("#vrLastMonthShow").html(vrLastMonthShow);
        $("#vr_amount").attr("class","online");
        $("#vr_count").attr("class","");
        showUser("pgwdropclient");
        if(vrUpDownShow){
            $("#vrUpCaption").html("");
            $("#vrDownCaption").html((vrAmountShow-vrAmountAverage).toFixed(2) +" M");
            $("#vrUpDownShow").attr("src","dist/img/up.png");
        } else{
            $("#vrUpCaption").html((vrAmountShow-vrAmountAverage).toFixed(2) +" M");
            $("#vrDownCaption").html("");
            $("#vrUpDownShow").attr("src","dist/img/down.png");
        }
    }


    function setSMSInfoBoxFields(){
        $("#smsDateShow").html(smsDateShow);
        $("#sms_infobox_title").html("Amount");
        $("#sms_amount_count").html(smsAmountShow);
        $("#sms_sup").html("M");
        $("#smsKnob").val(smsKnob);
        $("#smsKnob").trigger('change');
        $("#smsCurrentMonthShow").html(smsCurrentMonthShow);
        $("#smsLastMonthShow").html(smsLastMonthShow);
        $("#sms_amount").attr("class","online");
        $("#sms_count").attr("class","");
        showUser("smsdropclient");
        if(smsUpDownShow){
            $("#smsUpCaption").html("");
            $("#smsDownCaption").html((smsAmountShow-smsAmountAverage).toFixed(2) +" M");
            $("#smsUpDownShow").attr("src","dist/img/up.png");
        } else{
            $("#smsUpCaption").html((smsAmountShow-smsAmountAverage).toFixed(2) +" M");
            $("#smsDownCaption").html("");
            $("#smsUpDownShow").attr("src","dist/img/down.png");
        }
    }

    function setPGWInfoBoxFields(){
        $("#pgwDateShow").html(pgwDateShow);
        $("#pgw_infobox_title").html("Amount");
        $("#pgw_amount_count").html(pgwAmountShow);
        $("#pgw_sup").html("M");
        // alert(pgwKnob);
        $("#pgwKnob").val(pgwKnob);
        $("#pgwKnob").trigger('change');
        $("#pgwCurrentMonthShow").html(pgwCurrentMonthShow);
        $("#pgwLastMonthShow").html(pgwLastMonthShow);
        $("#pgw_amount").attr("class","online");
        $("#pgw_count").attr("class","");
        showUser("pgwdropclient");
        if(pgwUpDownShow){
            $("#pgwUpCaption").html("");
            $("#pgwDownCaption").html((pgwAmountShow-pgwAmountAverage).toFixed(2) +" M");
            $("#pgwUpDownShow").attr("src","dist/img/up.png");
        } else{
            $("#pgwUpCaption").html((pgwAmountShow-pgwAmountAverage).toFixed(2) +" M");
            $("#pgwDownCaption").html("");
            $("#pgwUpDownShow").attr("src","dist/img/down.png");
        }
    }

</script>


<script type="text/javascript">

    $("#vr_0").click(function () {
        vrDateShow = lastSevenDayVRData[0][1];
        vrAmountShow = lastSevenDayVRData[0][3];
        vrCountShow = lastSevenDayVRData[0][2];
        vrCurrentMonthShow = lastSevenDayVRData[0][4];
        vrLastMonthShow = lastSevenDayVRData[0][5];
        vrUpDownShow = lastSevenDayVRData[0][6];
        getDate = lastSevenDayVRData[0][7];
        vrGetDate = lastSevenDayVRData[0][7];
        showUser("vrTargetChart");
        showUser("vrHourChart");
        $("#vr_d_new , #vr_w_new , #vr_m_new , #vr_3m_new , #vr_3mg_new").html("");
        $("#vr_d_active , #vr_w_active , #vr_m_active , #vr_3m_active , #vr_3mg_active").html("");
        $("#vr_d_inactive , #vr_w_inactive , #vr_m_inactive , #vr_3m_inactive , #vr_3mg_inactive").html("");
        showUser("vrnew");
        showUser("vractive");
        showUser("vrinactive");
        vrKnob = parseInt((vrCurrentMonthShow / vrLastMonthShow * 100) + 0.5);
        $("#vr_0").attr("class", "online");
        $("#vr_1,#vr_2,#vr_3,#vr_4,#vr_5,#vr_6").attr("class", "");
        setVRInfoBoxFields();
    });
    $("#vr_1").click(function () {
        vrDateShow = lastSevenDayVRData[1][1];
        vrAmountShow = lastSevenDayVRData[1][3];
        vrCountShow = lastSevenDayVRData[1][2];
        vrCurrentMonthShow = lastSevenDayVRData[1][4];
        vrLastMonthShow = lastSevenDayVRData[1][5];
        vrUpDownShow = lastSevenDayVRData[1][6];
        getDate = lastSevenDayVRData[1][7];
        vrGetDate = lastSevenDayVRData[1][7];
        showUser("vrTargetChart");
        showUser("vrHourChart");
        $("#vr_d_new , #vr_w_new , #vr_m_new , #vr_3m_new , #vr_3mg_new").html("");
        $("#vr_d_active , #vr_w_active , #vr_m_active , #vr_3m_active , #vr_3mg_active").html("");
        $("#vr_d_inactive , #vr_w_inactive , #vr_m_inactive , #vr_3m_inactive , #vr_3mg_inactive").html("");
        showUser("vrnew");
        showUser("vractive");
        showUser("vrinactive");
        vrKnob = parseInt((vrCurrentMonthShow / vrLastMonthShow * 100) + 0.5);
        $("#vr_1").attr("class", "online");
        $("#vr_0,#vr_2,#vr_3,#vr_4,#vr_5,#vr_6").attr("class", "");
        setVRInfoBoxFields();
    });
    $("#vr_2").click(function () {
        vrDateShow = lastSevenDayVRData[2][1];
        vrAmountShow = lastSevenDayVRData[2][3];
        vrCountShow = lastSevenDayVRData[2][2];
        getDate = lastSevenDayVRData[2][7];
        vrGetDate = lastSevenDayVRData[2][7];
        showUser("vrTargetChart");
        showUser("vrHourChart");
        $("#vr_d_new , #vr_w_new , #vr_m_new , #vr_3m_new , #vr_3mg_new").html("");
        $("#vr_d_active , #vr_w_active , #vr_m_active , #vr_3m_active , #vr_3mg_active").html("");
        $("#vr_d_inactive , #vr_w_inactive , #vr_m_inactive , #vr_3m_inactive , #vr_3mg_inactive").html("");
        showUser("vrnew");
        showUser("vractive");
        showUser("vrinactive");
        $("#vr_2").attr("class", "online");
        $("#vr_1,#vr_0,#vr_3,#vr_4,#vr_5,#vr_6").attr("class", "");
        setVRInfoBoxFields();
    });
    $("#vr_3").click(function () {
        vrDateShow = lastSevenDayVRData[3][1];
        vrAmountShow = lastSevenDayVRData[3][3];
        vrCountShow = lastSevenDayVRData[3][2];
        getDate = lastSevenDayVRData[3][7];
        vrGetDate = lastSevenDayVRData[3][7];
        showUser("vrTargetChart");
        showUser("vrHourChart");
        $("#vr_d_new , #vr_w_new , #vr_m_new , #vr_3m_new , #vr_3mg_new").html("");
        $("#vr_d_active , #vr_w_active , #vr_m_active , #vr_3m_active , #vr_3mg_active").html("");
        $("#vr_d_inactive , #vr_w_inactive , #vr_m_inactive , #vr_3m_inactive , #vr_3mg_inactive").html("");
        showUser("vrnew");
        showUser("vractive");
        showUser("vrinactive");
        $("#vr_3").attr("class", "online");
        $("#vr_1,#vr_2,#vr_0,#vr_4,#vr_5,#vr_6").attr("class", "");
        setVRInfoBoxFields();
    });
    $("#vr_4").click(function () {
        vrDateShow = lastSevenDayVRData[4][1];
        vrAmountShow = lastSevenDayVRData[4][3];
        vrCountShow = lastSevenDayVRData[4][2];
        getDate = lastSevenDayVRData[4][7];
        vrGetDate = lastSevenDayVRData[4][7];
        showUser("vrTargetChart");
        showUser("vrHourChart");
        $("#vr_d_new , #vr_w_new , #vr_m_new , #vr_3m_new , #vr_3mg_new").html("");
        $("#vr_d_active , #vr_w_active , #vr_m_active , #vr_3m_active , #vr_3mg_active").html("");
        $("#vr_d_inactive , #vr_w_inactive , #vr_m_inactive , #vr_3m_inactive , #vr_3mg_inactive").html("");
        showUser("vrnew");
        showUser("vractive");
        showUser("vrinactive");
        $("#vr_4").attr("class", "online");
        $("#vr_1,#vr_2,#vr_3,#vr_0,#vr_5,#vr_6").attr("class", "");
        setVRInfoBoxFields();
    });
    $("#vr_5").click(function () {
        vrDateShow = lastSevenDayVRData[5][1];
        vrAmountShow = lastSevenDayVRData[5][3];
        vrCountShow = lastSevenDayVRData[5][2];
        getDate = lastSevenDayVRData[5][7];
        vrGetDate = lastSevenDayVRData[5][7];
        showUser("vrTargetChart");
        showUser("vrHourChart");
        $("#vr_d_new , #vr_w_new , #vr_m_new , #vr_3m_new , #vr_3mg_new").html("");
        $("#vr_d_active , #vr_w_active , #vr_m_active , #vr_3m_active , #vr_3mg_active").html("");
        $("#vr_d_inactive , #vr_w_inactive , #vr_m_inactive , #vr_3m_inactive , #vr_3mg_inactive").html("");
        showUser("vrnew");
        showUser("vractive");
        showUser("vrinactive");
        $("#vr_5").attr("class", "online");
        $("#vr_1,#vr_2,#vr_3,#vr_4,#vr_0,#vr_6").attr("class", "");
        setVRInfoBoxFields();
    });
    $("#vr_6").click(function () {
        vrDateShow = lastSevenDayVRData[6][1];
        vrAmountShow = lastSevenDayVRData[6][3];
        vrCountShow = lastSevenDayVRData[6][2];
        getDate = lastSevenDayVRData[6][7];
        vrGetDate = lastSevenDayVRData[6][7];
        showUser("vrTargetChart");
        showUser("vrHourChart");
        $("#vr_d_new , #vr_w_new , #vr_m_new , #vr_3m_new , #vr_3mg_new").html("");
        $("#vr_d_active , #vr_w_active , #vr_m_active , #vr_3m_active , #vr_3mg_active").html("");
        $("#vr_d_inactive , #vr_w_inactive , #vr_m_inactive , #vr_3m_inactive , #vr_3mg_inactive").html("");
        showUser("vrnew");
        showUser("vractive");
        showUser("vrinactive");
        $("#vr_6").attr("class", "online");
        $("#vr_1,#vr_2,#vr_3,#vr_4,#vr_5,#vr_0").attr("class", "");
        setVRInfoBoxFields();
    });


    $("#sms_0").click(function(){
        smsDateShow = lastSevenDaySMSData[0][1];
        smsAmountShow = lastSevenDaySMSData[0][3];
        smsCountShow = lastSevenDaySMSData[0][2];
        getDate = lastSevenDaySMSData[0][7];
        smsGetDate = lastSevenDaySMSData[0][7];
        showUser("smsTargetChart");
        $("#sms_d_new , #sms_w_new , #sms_m_new , #sms_3m_new , #sms_3mg_new").html("");
        $("#sms_d_active , #sms_w_active , #sms_m_active , #sms_3m_active , #sms_3mg_active").html("");
        $("#sms_d_inactive , #sms_w_inactive , #sms_m_inactive , #sms_3m_inactive , #sms_3mg_inactive").html("");
        showUser("smsnew");
        showUser("smsactive");
        showUser("smsinactive");
        $("#sms_0").attr("class","online");
        $("#sms_1,#sms_2,#sms_3,#sms_4,#sms_5,#sms_6").attr("class","");
        setSMSInfoBoxFields();
    });
    $("#sms_1").click(function(){
        smsDateShow = lastSevenDaySMSData[1][1];
        smsAmountShow = lastSevenDaySMSData[1][3];
        smsCountShow = lastSevenDaySMSData[1][2];
        getDate = lastSevenDaySMSData[1][7];
        smsGetDate = lastSevenDaySMSData[1][7];
        showUser("smsTargetChart");
        $("#sms_d_new , #sms_w_new , #sms_m_new , #sms_3m_new , #sms_3mg_new").html("");
        $("#sms_d_active , #sms_w_active , #sms_m_active , #sms_3m_active , #sms_3mg_active").html("");
        $("#sms_d_inactive , #sms_w_inactive , #sms_m_inactive , #sms_3m_inactive , #sms_3mg_inactive").html("");
        showUser("smsnew");
        showUser("smsactive");
        showUser("smsinactive");
        $("#sms_1").attr("class","online");
        $("#sms_0,#sms_2,#sms_3,#sms_4,#sms_5,#sms_6").attr("class","");
        setSMSInfoBoxFields();
    });
    $("#sms_2").click(function(){
        smsDateShow = lastSevenDaySMSData[2][1];
        smsAmountShow = lastSevenDaySMSData[2][3];
        smsCountShow = lastSevenDaySMSData[2][2];
        getDate = lastSevenDaySMSData[2][7];
        smsGetDate = lastSevenDaySMSData[2][7];
        showUser("smsTargetChart");
        $("#sms_d_new , #sms_w_new , #sms_m_new , #sms_3m_new , #sms_3mg_new").html("");
        $("#sms_d_active , #sms_w_active , #sms_m_active , #sms_3m_active , #sms_3mg_active").html("");
        $("#sms_d_inactive , #sms_w_inactive , #sms_m_inactive , #sms_3m_inactive , #sms_3mg_inactive").html("");
        showUser("smsnew");
        showUser("smsactive");
        showUser("smsinactive");
        $("#sms_2").attr("class","online");
        $("#sms_1,#sms_0,#sms_3,#sms_4,#sms_5,#sms_6").attr("class","");
        setSMSInfoBoxFields();
    });
    $("#sms_3").click(function(){
        smsDateShow = lastSevenDaySMSData[3][1];
        smsAmountShow = lastSevenDaySMSData[3][3];
        smsCountShow = lastSevenDaySMSData[3][2];
        getDate = lastSevenDaySMSData[3][7];
        smsGetDate = lastSevenDaySMSData[3][7];
        showUser("smsTargetChart");
        $("#sms_d_new , #sms_w_new , #sms_m_new , #sms_3m_new , #sms_3mg_new").html("");
        $("#sms_d_active , #sms_w_active , #sms_m_active , #sms_3m_active , #sms_3mg_active").html("");
        $("#sms_d_inactive , #sms_w_inactive , #sms_m_inactive , #sms_3m_inactive , #sms_3mg_inactive").html("");
        showUser("smsnew");
        showUser("smsactive");
        showUser("smsinactive");
        $("#sms_3").attr("class","online");
        $("#sms_1,#sms_2,#sms_0,#sms_4,#sms_5,#sms_6").attr("class","");
        setSMSInfoBoxFields();
    });
    $("#sms_4").click(function(){
        smsDateShow = lastSevenDaySMSData[4][1];
        smsAmountShow = lastSevenDaySMSData[4][3];
        smsCountShow = lastSevenDaySMSData[4][2];
        getDate = lastSevenDaySMSData[4][7];
        smsGetDate = lastSevenDaySMSData[4][7];
        showUser("smsTargetChart");
        $("#sms_d_new , #sms_w_new , #sms_m_new , #sms_3m_new , #sms_3mg_new").html("");
        $("#sms_d_active , #sms_w_active , #sms_m_active , #sms_3m_active , #sms_3mg_active").html("");
        $("#sms_d_inactive , #sms_w_inactive , #sms_m_inactive , #sms_3m_inactive , #sms_3mg_inactive").html("");
        showUser("smsnew");
        showUser("smsactive");
        showUser("smsinactive");
        $("#sms_4").attr("class","online");
        $("#sms_1,#sms_2,#sms_3,#sms_0,#sms_5,#sms_6").attr("class","");
        setSMSInfoBoxFields();
    });
    $("#sms_5").click(function(){
        smsDateShow = lastSevenDaySMSData[5][1];
        smsAmountShow = lastSevenDaySMSData[5][3];
        smsCountShow = lastSevenDaySMSData[5][2];
        getDate = lastSevenDaySMSData[5][7];
        smsGetDate = lastSevenDaySMSData[5][7];
        showUser("smsTargetChart");
        $("#sms_d_new , #sms_w_new , #sms_m_new , #sms_3m_new , #sms_3mg_new").html("");
        $("#sms_d_active , #sms_w_active , #sms_m_active , #sms_3m_active , #sms_3mg_active").html("");
        $("#sms_d_inactive , #sms_w_inactive , #sms_m_inactive , #sms_3m_inactive , #sms_3mg_inactive").html("");
        showUser("smsnew");
        showUser("smsactive");
        showUser("smsinactive");
        $("#sms_5").attr("class","online");
        $("#sms_1,#sms_2,#sms_3,#sms_4,#sms_0,#sms_6").attr("class","");
        setSMSInfoBoxFields();
    });
    $("#sms_6").click(function(){
        smsDateShow = lastSevenDaySMSData[6][1];
        smsAmountShow = lastSevenDaySMSData[6][3];
        smsCountShow = lastSevenDaySMSData[6][2];
        getDate = lastSevenDaySMSData[6][7];
        smsGetDate = lastSevenDaySMSData[6][7];
        showUser("smsTargetChart");
        $("#sms_d_new , #sms_w_new , #sms_m_new , #sms_3m_new , #sms_3mg_new").html("");
        $("#sms_d_active , #sms_w_active , #sms_m_active , #sms_3m_active , #sms_3mg_active").html("");
        $("#sms_d_inactive , #sms_w_inactive , #sms_m_inactive , #sms_3m_inactive , #sms_3mg_inactive").html("");
        showUser("smsnew");
        showUser("smsactive");
        showUser("smsinactive");
        $("#sms_6").attr("class","online");
        $("#sms_1,#sms_2,#sms_3,#sms_4,#sms_5,#sms_0").attr("class","");
        setSMSInfoBoxFields();
    });

    $("#pgw_0").click(function(){
        pgwDateShow = lastSevenDayPGWData[0][1];
        pgwAmountShow = lastSevenDayPGWData[0][3];
        pgwCountShow = lastSevenDayPGWData[0][2];
        getDate = lastSevenDayPGWData[0][7];
        pgwGetDate = lastSevenDayPGWData[0][7];
        showUser("pgwTargetChart");
        showUser("pgwHourChart");
        $("#pgw_d_new , #pgw_w_new , #pgw_m_new , #pgw_3m_new , #pgw_3mg_new").html("");
        $("#pgw_d_active , #pgw_w_active , #pgw_m_active , #pgw_3m_active , #pgw_3mg_active").html("");
        $("#pgw_d_inactive , #pgw_w_inactive , #pgw_m_inactive , #pgw_3m_inactive , #pgw_3mg_inactive").html("");
        showUser("pgwnew");
        showUser("pgwactive");
        showUser("pgwinactive");
        $("#pgw_0").attr("class","online");
        $("#pgw_1,#pgw_2,#pgw_3,#pgw_4,#pgw_5,#pgw_6").attr("class","");
        setPGWInfoBoxFields();
    });
    $("#pgw_1").click(function(){
        pgwDateShow = lastSevenDayPGWData[1][1];
        pgwAmountShow = lastSevenDayPGWData[1][3];
        pgwCountShow = lastSevenDayPGWData[1][2];
        getDate = lastSevenDayPGWData[1][7];
        pgwGetDate = lastSevenDayPGWData[1][7];
        showUser("pgwTargetChart");
        showUser("pgwHourChart");
        $("#pgw_d_new , #pgw_w_new , #pgw_m_new , #pgw_3m_new , #pgw_3mg_new").html("");
        $("#pgw_d_active , #pgw_w_active , #pgw_m_active , #pgw_3m_active , #pgw_3mg_active").html("");
        $("#pgw_d_inactive , #pgw_w_inactive , #pgw_m_inactive , #pgw_3m_inactive , #pgw_3mg_inactive").html("");
        showUser("pgwnew");
        showUser("pgwactive");
        showUser("pgwinactive");
        $("#pgw_1").attr("class","online");
        $("#pgw_0,#pgw_2,#pgw_3,#pgw_4,#pgw_5,#pgw_6").attr("class","");
        setPGWInfoBoxFields();
    });
    $("#pgw_2").click(function(){
        pgwDateShow = lastSevenDayPGWData[2][1];
        pgwAmountShow = lastSevenDayPGWData[2][3];
        pgwCountShow = lastSevenDayPGWData[2][2];
        getDate = lastSevenDayPGWData[2][7];
        pgwGetDate = lastSevenDayPGWData[2][7];
        showUser("pgwTargetChart");
        showUser("pgwHourChart");
        $("#pgw_d_new , #pgw_w_new , #pgw_m_new , #pgw_3m_new , #pgw_3mg_new").html("");
        $("#pgw_d_active , #pgw_w_active , #pgw_m_active , #pgw_3m_active , #pgw_3mg_active").html("");
        $("#pgw_d_inactive , #pgw_w_inactive , #pgw_m_inactive , #pgw_3m_inactive , #pgw_3mg_inactive").html("");
        showUser("pgwnew");
        showUser("pgwactive");
        showUser("pgwinactive");
        $("#pgw_2").attr("class","online");
        $("#pgw_1,#pgw_0,#pgw_3,#pgw_4,#pgw_5,#pgw_6").attr("class","");
        setPGWInfoBoxFields();
    });
    $("#pgw_3").click(function(){
        pgwDateShow = lastSevenDayPGWData[3][1];
        pgwAmountShow = lastSevenDayPGWData[3][3];
        pgwCountShow = lastSevenDayPGWData[3][2];
        getDate = lastSevenDayPGWData[3][7];
        pgwGetDate = lastSevenDayPGWData[3][7];
        showUser("pgwTargetChart");
        showUser("pgwHourChart");
        $("#pgw_d_new , #pgw_w_new , #pgw_m_new , #pgw_3m_new , #pgw_3mg_new").html("");
        $("#pgw_d_active , #pgw_w_active , #pgw_m_active , #pgw_3m_active , #pgw_3mg_active").html("");
        $("#pgw_d_inactive , #pgw_w_inactive , #pgw_m_inactive , #pgw_3m_inactive , #pgw_3mg_inactive").html("");
        showUser("pgwnew");
        showUser("pgwactive");
        showUser("pgwinactive");
        $("#pgw_3").attr("class","online");
        $("#pgw_1,#pgw_2,#pgw_0,#pgw_4,#pgw_5,#pgw_6").attr("class","");
        setPGWInfoBoxFields();
    });
    $("#pgw_4").click(function(){
        pgwDateShow = lastSevenDayPGWData[4][1];
        pgwAmountShow = lastSevenDayPGWData[4][3];
        pgwCountShow = lastSevenDayPGWData[4][2];
        getDate = lastSevenDayPGWData[4][7];
        pgwGetDate = lastSevenDayPGWData[4][7];
        showUser("pgwTargetChart");
        showUser("pgwHourChart");
        $("#pgw_d_new , #pgw_w_new , #pgw_m_new , #pgw_3m_new , #pgw_3mg_new").html("");
        $("#pgw_d_active , #pgw_w_active , #pgw_m_active , #pgw_3m_active , #pgw_3mg_active").html("");
        $("#pgw_d_inactive , #pgw_w_inactive , #pgw_m_inactive , #pgw_3m_inactive , #pgw_3mg_inactive").html("");
        showUser("pgwnew");
        showUser("pgwactive");
        showUser("pgwinactive");
        $("#pgw_4").attr("class","online");
        $("#pgw_1,#pgw_2,#pgw_3,#pgw_0,#pgw_5,#pgw_6").attr("class","");
        setPGWInfoBoxFields();
    });
    $("#pgw_5").click(function(){
        pgwDateShow = lastSevenDayPGWData[5][1];
        pgwAmountShow = lastSevenDayPGWData[5][3];
        pgwCountShow = lastSevenDayPGWData[5][2];
        getDate = lastSevenDayPGWData[5][7];
        pgwGetDate = lastSevenDayPGWData[5][7];
        showUser("pgwTargetChart");
        showUser("pgwHourChart");
        $("#pgw_d_new , #pgw_w_new , #pgw_m_new , #pgw_3m_new , #pgw_3mg_new").html("");
        $("#pgw_d_active , #pgw_w_active , #pgw_m_active , #pgw_3m_active , #pgw_3mg_active").html("");
        $("#pgw_d_inactive , #pgw_w_inactive , #pgw_m_inactive , #pgw_3m_inactive , #pgw_3mg_inactive").html("");
        showUser("pgwnew");
        showUser("pgwactive");
        showUser("pgwinactive");
        $("#pgw_5").attr("class","online");
        $("#pgw_1,#pgw_2,#pgw_3,#pgw_4,#pgw_0,#pgw_6").attr("class","");
        setPGWInfoBoxFields();
    });
    $("#pgw_6").click(function(){
        pgwDateShow = lastSevenDayPGWData[6][1];
        pgwAmountShow = lastSevenDayPGWData[6][3];
        pgwCountShow = lastSevenDayPGWData[6][2];
        getDate = lastSevenDayPGWData[6][7];
        pgwGetDate = lastSevenDayPGWData[6][7];
        showUser("pgwTargetChart");
        showUser("pgwHourChart");
        $("#pgw_d_new , #pgw_w_new , #pgw_m_new , #pgw_3m_new , #pgw_3mg_new").html("");
        $("#pgw_d_active , #pgw_w_active , #pgw_m_active , #pgw_3m_active , #pgw_3mg_active").html("");
        $("#pgw_d_inactive , #pgw_w_inactive , #pgw_m_inactive , #pgw_3m_inactive , #pgw_3mg_inactive").html("");
        showUser("pgwnew");
        showUser("pgwactive");
        showUser("pgwinactive");
        $("#pgw_6").attr("class","online");
        $("#pgw_1,#pgw_2,#pgw_3,#pgw_4,#pgw_5,#pgw_0").attr("class","");
        setPGWInfoBoxFields();
    });
</script>



<script type="text/javascript">
    var resultVR;
    var resultSMS;
    var resultPGW;
    function areaChart(result,color,div){
        var maxValue = 0;
        if(div=="vrHourChart"){
            maxValue = 450000;
            resultVR=result;
        }else if(div=="smsWeekChart"){
            maxValue = 2500000;
            resultSMS=result;
        }else if(div=="pgwHourChart"){
            maxValue = 2500000;
            resultPGW=result;
        }
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = new google.visualization.DataTable(result);


            var options = {
                'chartArea':{'width':'98%', 'height':'50%'},
                colors: [color],
                legend: { position: 'none' },
                fontSize: 7,
                backgroundColor: { fill:'transparent' },
                hAxis: {
                    titleTextStyle: {color: '#4565'},
                    gridlines: {
                        color: 'transparent'
                    }
                },
                vAxis: {
                    minValue: 0,
                    maxValue: maxValue,
                    // ticks: [0, .5, 1],
                    textPosition: 'none',
                    gridlines: {
                        color: 'transparent'
                    }
                }
            };
            var chart = new google.visualization.AreaChart(document.getElementById(div));
            if(div=="smsWeekChart"){
                chart = new google.visualization.ColumnChart(document.getElementById(div));
            }
            chart.draw(data, options);
        }
    }

    function targetData(data, element){
        if(element=="vrTargetChart"){
            var ajaxResult = data;
            vrUpDownShow = ajaxResult[2];
            vrAmountAverage = ajaxResult[3];
            vrCurrentMonthShow = ajaxResult[0];
            vrLastMonthShow = ajaxResult[1];
            vrKnob = parseInt((vrCurrentMonthShow/vrLastMonthShow*100)+0.5);
            setVRInfoBoxFields();
        }
        if(element=="smsTargetChart"){
            var ajaxResult = data;
            smsUpDownShow = ajaxResult[2];
            smsAmountAverage = ajaxResult[3];
            smsCurrentMonthShow = ajaxResult[0];
            smsLastMonthShow = ajaxResult[1];
            smsKnob = parseInt((smsCurrentMonthShow/smsLastMonthShow*100)+0.5);
            setSMSInfoBoxFields();
        }
        if(element=="pgwTargetChart"){
            var ajaxResult = data;
            pgwUpDownShow = ajaxResult[2];
            pgwAmountAverage = ajaxResult[3];
            pgwCurrentMonthShow = ajaxResult[0];
            pgwLastMonthShow = ajaxResult[1];
            pgwKnob = parseInt((pgwCurrentMonthShow/pgwLastMonthShow*100)+0.5);
            setPGWInfoBoxFields();
        }
    }

    if($(window).width()>1400){
        $('.infoboxtextsize').css('font-size', '200%');
    }else if($(window).width()>1300){
        $('.infoboxtextsize').css('font-size', '160%');
    }else if($(window).width()>1200){
        $('.infoboxtextsize').css('font-size', '130%');
    }else if($(window).width()>850){
        $('.infoboxtextsize').css('font-size', '120%');
    }else{
        $('.infoboxtextsize').css('font-size', '100%');
    }

    if($(window).width()>1400){
        $('.knob-label').css('font-size', '100%');
    }else if($(window).width()>1300){
        $('.knob-label').css('font-size', '85%');
    }else if($(window).width()>1200){
        $('.knob-label').css('font-size', '70%');
    }else if($(window).width()>850){
        $('.knob-label').css('font-size', '60%');
    }else{
        $('.knob-label').css('font-size', '50%');
    }

    $(window).resize(function(){
        if($(window).width()>1400){
            $('.infoboxtextsize').css('font-size', '200%');
        }else if($(window).width()>1300){
            $('.infoboxtextsize').css('font-size', '160%');
        }else if($(window).width()>1200){
            $('.infoboxtextsize').css('font-size', '130%');
        }else if($(window).width()>850){
            $('.infoboxtextsize').css('font-size', '120%');
        }else{
            $('.infoboxtextsize').css('font-size', '100%');
        }

        if($(window).width()>1400){
            $('.knob-label').css('font-size', '100%');
        }else if($(window).width()>1300){
            $('.knob-label').css('font-size', '85%');
        }else if($(window).width()>1200){
            $('.knob-label').css('font-size', '70%');
        }else if($(window).width()>850){
            $('.knob-label').css('font-size', '60%');
        }else{
            $('.knob-label').css('font-size', '50%');
        }

        if(resultVR!=null)
            areaChart(resultVR,"#00c0ef","vrHourChart");
        if(resultSMS!=null)
            areaChart(resultSMS,"#00a65a","smsWeekChart");
        if(resultPGW!=null)
            areaChart(resultPGW,"#f39c12","pgwHourChart");
    });

    // =============================================================== new active inactive modal =========================================
    $('#vr_d_new,#vr_w_new,#vr_m_new,#vr_3m_new,#vr_3mg_new').click(
        function() {
            dashboardModal(vrGetDate, "newclienttable", $(this).attr('id'), "New Client");
        }
    );
    $('#sms_d_new,#sms_w_new,#sms_m_new,#sms_3m_new,#sms_3mg_new').click(
        function() {
            dashboardModal(smsGetDate, "newclienttable", $(this).attr('id'), "New Stakeholder");
        }
    );
    $('#pgw_d_new,#pgw_w_new,#pgw_m_new,#pgw_3m_new,#pgw_3mg_new').click(
        function() {
            dashboardModal(pgwGetDate, "newclienttable", $(this).attr('id'), "New Strid");
        }
    );
    $('#vr_d_active,#vr_w_active,#vr_m_active,#vr_3m_active,#vr_3mg_active').click(
        function() {
            dashboardModal(vrGetDate, "activeclienttable", $(this).attr('id'), "Active Client");
        }
    );
    $('#sms_d_active,#sms_w_active,#sms_m_active,#sms_3m_active,#sms_3mg_active').click(
        function() {
            dashboardModal(smsGetDate, "activeclienttable", $(this).attr('id'), "Active Stakeholder");
        }
    );
    $('#pgw_d_active,#pgw_w_active,#pgw_m_active,#pgw_3m_active,#pgw_3mg_active').click(
        function() {
            dashboardModal(pgwGetDate, "activeclienttable", $(this).attr('id'), "Inactive Strid");
        }
    );
    $('#vr_d_inactive,#vr_w_inactive,#vr_m_inactive,#vr_3m_inactive,#vr_3mg_inactive').click(
        function() {
            dashboardModal(vrGetDate, "inactiveclienttable", $(this).attr('id'), "Inactive Client");
        }
    );
    $('#sms_d_inactive,#sms_w_inactive,#sms_m_inactive,#sms_3m_inactive,#sms_3mg_inactive').click(
        function() {
            dashboardModal(smsGetDate, "inactiveclienttable", $(this).attr('id'), "Active Stakeholder");
        }
    );
    $('#pgw_d_inactive,#pgw_w_inactive,#pgw_m_inactive,#pgw_3m_inactive,#pgw_3mg_inactive').click(
        function() {
            dashboardModal(pgwGetDate, "inactiveclienttable", $(this).attr('id'), "Inactive Strid");
        }
    );


    $('#vrdropclient').click(
        function () {
            showUser("vrdropclientlist");
        }
    );
    $('#smsdropclient').click(
        function () {
            showUser("smsdropclientlist");
        }
    );
    $('#pgwdropclient').click(
        function () {
            showUser("pgwdropclientlist");
        }
    );

</script>

