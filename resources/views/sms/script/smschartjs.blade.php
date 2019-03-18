<script type="text/javascript">
    // ******************************************************************* Variables ******************************************************************************
    var mL = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    var days = ['Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednessday', 'Thursday', 'Friday'];
    var allOperation;
    var dateRange;
    var operatorOperation;
    var operatorOperationCount;
    var upDown;
    var upDownCount;
    var operatorName;
    var industry;
    var industries;
    var department1;
    var clientName;
    var operators;
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

    var resultYearWR;
    var resultYearWC;
    var resultIndustryWC;
    var resultOperatorWC;
    var resultImpactClient;
    var resultTopTC;
    var resultTopTK;

    var boolfordynamic;

    // ******************************************************** Initialize ***********************************************************************************
    function variableInitialize(){
        allOperation=0;
        allOperationCount=0;
        operatorOperation=0;
        operatorOperationCount=0;
        yearCount="";
        monthCount="";
        quarterCount="";
        dayCount="";
        year="";
        industries="";
        department1 ="";
        month="";
        quarter="";
        day="";
        industry="";
        operatorName="";
        clientName="";
        operators="";
        showUser("transactionamount","all");
        showUser("operator","operator");
        showUser("industry","industry");
        showUser("toptencompany","toptencompany");
        showUser("transactioncount","transactioncount");
        showUser("toptenkam","toptenkam");

        tittles= 'Amount by Year';
        tittlesCount= 'Count by Year';

        xtittles= 'year';
        xtittlesCount= 'year';

        upDown=1;
        upDownCount=1;


    }

    // ********************************************************** Dynamic Chart DynamicsCompressorNode **************************************************************************************
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

            $('#department_options :selected').each(function(){
                industry = $(this).val();
                department1 = $(this).val();
            });


            if(month_data.length==0 && week_day_data.length==0 && year_data.length==0 && department1.length==0){
                alert("Pick any month or day or year or department!!");
                return;
            }
            $(".loading").html("<div class=\""+"overlay\""+"><i class=\""+"fa fa-refresh fa-spin\""+"></i></div>");
            if(month_data.length==0 && week_day_data.length==0 && year_data.length==0 && department1.length!=0){
                operatorOperation=0;
                clientName="";
                year="";
                month="";
                quarter="";
                xtittles="year";
                tittles= 'Amount by Year: '+industry;
                operatorName="";
                showUser("transactionamount","all");
                return;
            }

            if(month_data.length==0 && week_day_data.length==0 && year_data.length==0){
                alert("Pick any month or day or year!!");
                return;
            }


            $(".loading").html("<div class=\""+"overlay\""+"><i class=\""+"fa fa-refresh fa-spin\""+"></i></div>");

            var ind="";
            var o="";
            i=1;
            if(industries.length!=0) {ind=i+". "+industries+" ";i++;}
            if(operators.length!=0){o=i+". "+operators;i++;}
            xtittlesAlls=ind+o;
            if(week_day_data.length!=0)xtittles="Day wise";
            else xtittles="Month wise";

            var url = "transactionamountfilter?year="+year_data+"&month="+month_data+"&day="+week_day_data+"&weekday="+week_data+"&industry="+industries+"&operator="+operators;
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
            url = element + "?operatorName=" + operatorName + "&industry="+industry+"&year=" + year + "&quarter=" + quarter + "&month=" + month;
        }

        $.ajax({
            url: url,
            type: 'GET',
            dataType: "json",
            
            success:function(data){
                console.log(data);
                if(element=="transactioncount") smsNumberOfTransactionChart(data,element,div);
                if(element=="transactionamount") all(data,element,div);
                // if(element=="vrTable") showVrTable(data,element,div);
                if(element=="operator") operator(data,element,div);
                if(element=="toptencompany") topTenCompany(data,element,div);
                if(element=="toptenkam") topTenKAM(data,element,div);
                if(element=="impactclient") drawChart(data,element,div);
                if(element=="industry") industrys(data,element,div);
                if(element=="department") vrDepartmentChart(data,element,div);
                // if(element=="hourwise") vrHourWiseChart(data,element);
            },
            error: function (){alert('error');}
        });

    }

    // ************************************* Generate Excel ************************************************
    function generateExcel(r) {
        var downloadLink;
        var filename="SMSData";
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

    // **************************************************** window resize wise call all charts ***********************************************************
    $(window).resize(function(){
        if(resultYearWR!=null){
            if(boolfordynamic == 'a')
                all(resultYearWR,"transactionamount","all");
            else if(boolfordynamic == 'b')
                alls(resultYearWR,"transactionamount","all");
        }
        if(resultYearWC!=null)
            smsNumberOfTransactionChart(resultYearWC, "transactioncount","transactioncount");
        if(resultIndustryWC!=null)
            industrys(resultIndustryWC,"industry","industry");
        if(resultOperatorWC!=null)
            operator(resultOperatorWC,"operator","operator");
        if(resultTopTC!=null)
            topTenCompany(resultTopTC,"toptencompany","toptencompany");
        if(resultTopTK!=null)
            topTenKAM(resultTopTK,"toptenkam","toptenkam");
    });

    // ***************************************************** Date Range **********************************************************************************
    $(function () {
        // $('#dataTable').DataTable();
        //Date range as a button
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

    // ******************************************************* Data Filter for Impact strid *****************************************************************
    function dataFilter(){
        var impactOperation;
        if($('#impactOperation').val()!=null){
            impactOperation=$('#impactOperation').val();
        }
        dateRange+="&operation="+impactOperation;
        $("#impactclient").html("<div class=\""+"overlay\""+"><i class=\""+"fa fa-refresh fa-spin\""+"></i></div>");
        // alert("<div class=\""+"overlay\""+"><i class=\""+"fa fa-refresh fa-spin\""+"></i></div>");
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
        smsNumberOfTransactionChart(resultYearWC, "transactioncount","chartModalBody");
    });
    $("#operatorChartBtn").click(function () {
        $('#chartModalTitle').html($('#operatorChartTitle').html());
        $('#chartModal').modal('toggle');
        operator(resultOperatorWC,"operator","chartModalBody");
    });
    $("#industryChartBtn").click(function () {
        $('#chartModalTitle').html($('#departmentChartTitle').html());
        $('#chartModal').modal('toggle');
        industrys(resultIndustryWC,"industry","chartModalBody");
    });

    $("#impactChartBtn").click(function () {
        $('#chartModalTitle').html($('#impactChartTitle').html());
        $('#chartModal').modal('toggle');
        drawChart(resultImpactClient,"impactclient","chartModalBody")
    });
    $("#toptenclientChartBtn").click(function () {
        $('#chartModalTitle').html($('#toptencompanyChartTitle').html());
        $('#chartModal').modal('toggle');
        topTenCompany(resultTopTC,"toptencompany","chartModalBody");
    });
    $("#toptenkamChartBtn").click(function () {
        $('#chartModalTitle').html($('#toptenkamChartTitle').html());
        $('#chartModal').modal('toggle');
        topTenKAM(resultTopTK,"toptenkam","chartModalBody");
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
            smsNumberOfTransactionChart(resultYearWC, "transactioncount","transactioncount");
        if(resultIndustryWC!=null)
            industry(resultIndustryWC,"industry","industry");
        if(resultOperatorWC!=null)
            operator(resultOperatorWC,"operator","operator");
        if(resultTopTC!=null)
            topTenCompany(resultTopTC,"toptencompany","toptencompany");
        if(resultTopTK!=null)
            topTenKAM(resultTopTK,"toptenkam","toptenkam");
    });


</script>

@include('sms.script.chart.smschartjsAmountCompare')
@include('sms.script.chart.smschartjsTransactionCountCompare')
@include('sms.script.chart.smschartjsTopTenCompany')
@include('sms.script.chart.smschartjsTopTenKAM')
@include('sms.script.chart.smschartjsOperator')
@include('sms.script.chart.smschartjsIndustry')
@include('sms.script.chart.smschartjsImpactClient')


<script>
    variableInitialize();
</script>