<script>
    // *************************************** Variables *****************************************************
    var mL = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    var days = ['Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednessday', 'Thursday', 'Friday'];
    var allOperation;
    var mL = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    var days = ['Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednessday', 'Thursday', 'Friday'];
    var dateRange;
    var allOperation;
    var operatorOperation;
    var operatorOperationCount;
    var upDown;
    var upDownCount;
    var operatorName;
    var clientName;
    var cardType;
    var cardTypes;
    var year;
    var month;
    var quarter;
    var day;
    var yearCount;
    var monthCount;
    var quarterCount;
    var dayCount;
    var tittles;
    var xtittles;
    var tittlesCount;
    var xtittlesCount;
    var operators;
    var departments;
    var easy;
    var xtittlesAlls;
    var bankName;

    // ******************************************** chart result store variables ************************************
    var resultYearWR;
    var resultYearWC;
    var resultEasyCC;
    var resultBankWC;
    var resultCardTypeWC;
    var resultImpactClient;
    var resultHourWVR;
    var resultTopTC;
    var resultAssumption;

    var boolfordynamic;


    // ******************************************** Initialized ****************************************************
    function variableInitialize(){
        allOperation=0;
        // allOperationCount=0;
        operatorOperation=0;
        operatorOperationCount=0;
        yearCount="";
        monthCount="";
        quarterCount="";
        dayCount="";
        year="";
        month="";
        quarter="";
        day="";
        operatorName="";
        cardType="";
        cardTypes="";
        clientName="";
        operators="";
        departments="";
        easy="";
        xtittlesAlls="";
        bankName="";
        boolfordynamic = 'a';
        showUser("transactionamount","all");
        showUser("transactioncount","pgwNumberOfTransactionChart");
        showUser("bank","operator");
        showUser("cardtype","pgwCardTypeChart");
        showUser("toptencompany","toptenclient");
        showUser("assumption","assumption");

        tittles= 'Amount by Year';
        tittlesCount= 'Count by Year';

        xtittles= 'year';
        xtittlesCount= 'year';

        upDown=1;
        upDownCount=1;
    }

    // ********************************************************** Dynamic Chart filter ****************************************
    $(function(){
        var fMonth="";
        var i;
        for( i=0;i<12;i++){
            fMonth += '<option value="'+(i+1)+'">'+mL[i]+'</option>';
        }

        $("#month_options").html(fMonth);
        fMonth="";
        for( i=0;i<31;i++){
            fMonth+="<option value="+(i+1)+">"+(i+1)+"</option>";
        }
        $("#week_options").html(fMonth);

        $("#filterMain").click(function(){
            var year_data="";
            i=0;

            $('#year_options :selected').each(function(){
                if(i==0)
                    year_data+=$(this).val();
                else
                    year_data+=","+$(this).val();
                i=1;
            });



            var month_data="";
            i=0;
            $('#month_options :selected').each(function(){
                if(i==0)
                    month_data+=$(this).val();
                else
                    month_data+=","+$(this).val();
                i=1;
            });

            var week_data="";
            i=0;
            $('#week_day_options :selected').each(function(){
                if(i==0)
                    week_data+=$(this).val();
                else
                    week_data+=","+$(this).val();
                i=1;
            });
            var week_day_data="";
            i=0;
            $('#week_options :selected').each(function(){
                if(i==0)
                    week_day_data+=$(this).val();
                else
                    week_day_data+=","+$(this).val();
                i=1;
            });

            if(month_data.length==0 && week_day_data.length==0 && year_data.length==0){
                alert("Pick any month or day or year!!");
                return;
            }


            $(".loading").html("<div class=\""+"overlay\""+"><i class=\""+"fa fa-refresh fa-spin\""+"></i></div>");

            var d="";
            var e="";
            var o="";
            i=1;
            if(cardTypes.length!=0) {d=i+". "+cardTypes+" ";i++;}
            if(easy.length!=0){e=i+". "+easy+" ";i++;}
            if(bankName.length!=0){o=i+". "+bankName;i++;}
            xtittlesAlls=d+e+o;
            if(week_day_data.length!=0)xtittles="Day wise";
            else xtittles="Month wise";

            var url = "transactionamountfilter?year="+year_data+"&month="+month_data+"&day="+week_day_data+"&weekday="+week_data+"&easy="+easy+"&card="+cardTypes+"&bank="+bankName;
            $.ajax({
                url: url,
                type: 'GET',
                dataType: "json",
                
                success:function(data){
                    $(".loading").empty();
                    alls(data,"all","all");
                },
                error: function (){alert('error');}
            });

        });

        $("#resetMain").click(function(){
            $('.selectpicker').val('');
            $('.selectpicker').selectpicker('refresh');
            variableInitialize();
        });

    });

    // ************************************* Google chart loader ******************************
    google.load('visualization', '1', {'packages':['corechart']});

    // **************************************** DataTable *****************************************
    function showVrTable(result){
        document.getElementById("dataTable").innerHTML=result;
        $('#dataTable').DataTable({
            aLengthMenu: [
                [25, 50, 100, 200, -1],
                [25, 50, 100, 200, "All"]
            ],
            iDisplayLength: -1
        });
        $('#tableDataBox').show();
    }

    // ************************************************* showUser function For all ajax ***************************
    function showUser(element,div) {
        var url = "";

        if(element=="impactclient"){
            url = "impactclient?"+dateRange;
        }
        else if(element=="transactioncount"){
            url = element + "?year="+yearCount+"&quarter="+quarterCount+"&month="+monthCount ;
        }
        else {
            url = element + "?operatorName=" + operatorName + "&cardType="+cardTypes+"&year=" + year + "&quarter=" + quarter + "&month=" + month;
        }

        $.ajax({
            url: url,
            type: 'GET',
            dataType: "json",
            
            success:function(data){
                console.log(data);
                if(element=="transactionamount") all(data,element,div);
                if(element=="transactioncount") pgwNumberOfTransactionChart(data,element,div);
                if(element=="impactclient") drawChart(data,element,div);
                if(element=="easy") pgwEasyNoneasyChart(data,element,div);
                if(element=="bank") bank(data,element,div);
                if(element=="toptencompany") pgwTopTenCompany(data,element,div);
                if(element=="vrTable") showVrTable(data,element,div);
                if(element=="cardtype") pgwCardTypeChart(data,element,div);
                if(element=="assumption") pgwAssumptionChart(data, element,div);
            },
            error: function (){alert('error');}
        });

    }

    // ************************************* Generate Excel ************************************************
    function generateExcel(r) {
        var downloadLink;
        var filename="PGWData";
        var dataType = 'application/vnd.ms-excel';
        var tableSelect = document.getElementById(r);
        var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');

        // Specify file name
        filename = filename?filename+'.xls':'excel_data.xls';

        // Create download link element
        downloadLink = document.createElement("a");

        document.body.appendChild(downloadLink);

        if(navigator.msSaveOrOpenBlob){
            var blob = new Blob(['\ufeff', tableHTML], {
                type: dataType
            });
            navigator.msSaveOrOpenBlob( blob, filename);
        }else{
            // Create a link to the file
            downloadLink.href = 'data:' + dataType + ', ' + tableHTML;

            // Setting the file name
            downloadLink.download = filename;

            //triggering the function
            downloadLink.click();
        }
    }


    // ************************************************** Window resize wise call all charts ***************************************
    $(window).resize(function(){
        if(resultYearWR!=null){
            if(boolfordynamic == 'a')
                all(resultYearWR,"transactionamount","all");
            else if(boolfordynamic == 'b')
                alls(resultYearWR,"transactionamount","all");
        }
        if(resultYearWC!=null)
            pgwNumberOfTransactionChart(resultYearWC,"transactioncount","pgwNumberOfTransactionChart");
        if(resultEasyCC!=null)
            pgwEasyNoneasyChart(resultEasyCC,"easy","vrEasyNoneasyChart");
        if(resultBankWC!=null)
            bank(resultBankWC,"bank","operator");
        if(resultCardTypeWC!=null)
            pgwCardTypeChart(resultCardTypeWC,"cardtype","pgwCardTypeChart");
        if(resultTopTC!=null)
            pgwTopTenCompany(resultTopTC,"toptencompany","toptenclient");

    });

    $(function () {
        //Date range as a button
        // ********************************************* Date Range Picker *****************************************************
        $('#daterange-btn').daterangepicker(
            {
                startDate: moment().subtract(30, 'days'),
                endDate: moment()
            },
            function (start, end) {
                var today = new Date();
                if(today<end){
                    alert("please select two dates before today date");
                }else{
                    dateRange= 'fromDate='+start.format('YYYY-MM-DD') + '&toDate=' + end.format('YYYY-MM-DD');
                    $('#daterange-btn').html(start.format('YYYY-MM-DD') + ' and ' + end.format('YYYY-MM-DD'));
                }
            }
        );
    });

    // ********************************************* PGW impact strid *******************************************************
    function dataFilter(){
        var impactOperation;
        if($('#impactOperation').val()!=null){
            impactOperation=$('#impactOperation').val();
        }
        dateRange+="&operation="+impactOperation;
        $("#impactclient").html("<div class=\""+"overlay\""+"><i class=\""+"fa fa-refresh fa-spin\""+"></i></div>");

        showUser("impactclient","impactclient");
    }

    // ************************************ Modal ********************************************
    $("#dynamicChartBtn").click(function () {
        $('#chartModalTitle').html($('#dynamicChartTitle').html());
        $('#chartModal').modal('toggle');
        if(boolfordynamic == 'a')
            all(resultYearWR,"transactionamount","chartModalBody");
        else if(boolfordynamic == 'b')
            alls(resultYearWR,"transactionamount","chartModalBody");
    });
    $("#countChartBtn").click(function () {
        $('#chartModalTitle').html($('#countChartTitle').html());
        $('#chartModal').modal('toggle');
        pgwNumberOfTransactionChart(resultYearWC, "transactioncount","chartModalBody");
    });
    $("#bankChartBtn").click(function () {
        $('#chartModalTitle').html($('#bankChartTitle').html());
        $('#chartModal').modal('toggle');
        bank(resultBankWC,"bank","chartModalBody");
    });
    $("#cardtypeChartBtn").click(function () {
        $('#chartModalTitle').html($('#cardtypeChartTitle').html());
        $('#chartModal').modal('toggle');
        pgwCardTypeChart(resultCardTypeWC,"cardtype","chartModalBody");
    });
    $("#easyChartBtn").click(function () {
        $('#chartModalTitle').html($('#easyChartTitle').html());
        $('#chartModal').modal('toggle');
        pgwEasyNoneasyChart(resultEasyCC,"easy","chartModalBody");
    });
    $("#impactChartBtn").click(function () {
        $('#chartModalTitle').html($('#impactChartTitle').html());
        $('#chartModal').modal('toggle');
        drawChart(resultImpactClient,"impactclient","chartModalBody")
    });
    $("#toptenclientChartBtn").click(function () {
        $('#chartModalTitle').html($('#toptencompanyChartTitle').html());
        $('#chartModal').modal('toggle');
        pgwTopTenCompany(resultTopTC,"toptencompany","chartModalBody");
    });



    $("#modalClose").click(function () {
        $('#chartModal').modal('toggle');
        $("#chartModalBody").html("");
        if(resultYearWR!=null){
            if(boolfordynamic == 'a')
                all(resultYearWR,"transactionamount","all");
            else if(boolfordynamic == 'b')
                alls(resultYearWR,"transactionamount","all");
        }
        if(resultYearWC!=null)
            pgwNumberOfTransactionChart(resultYearWC,"transactioncount","pgwNumberOfTransactionChart");
        if(resultEasyCC!=null)
            pgwEasyNoneasyChart(resultEasyCC,"easy","vrEasyNoneasyChart");
        if(resultBankWC!=null)
            bank(resultBankWC,"bank","operator");
        if(resultCardTypeWC!=null)
            pgwCardTypeChart(resultCardTypeWC,"cardtype","pgwCardTypeChart");
        if(resultTopTC!=null)
            pgwTopTenCompany(resultTopTC,"toptencompany","toptenclient");

    });

</script>


@include('pgw.script.chart.pgwchartjsAmountCompare')
@include('pgw.script.chart.pgwchartjsTransactionCountCompare')
@include('pgw.script.chart.pgwchartjsEasy')
@include('pgw.script.chart.pgwchartjsBank')
@include('pgw.script.chart.pgwchartjsCardType')
@include('pgw.script.chart.pgwchartjsTopTenCompany')
@include('pgw.script.chart.pgwchartjsImpactClient')
@include('pgw.script.chart.pgwchartjsAssumption')


<script>
    variableInitialize();
</script>