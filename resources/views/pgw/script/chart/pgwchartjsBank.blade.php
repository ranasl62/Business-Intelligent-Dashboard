<script>
    function bank(result,element,div){
        resultBankWC=result;
        google.setOnLoadCallback();
        var data = new google.visualization.DataTable(result);
        var options = {
            sliceVisibilityThreshold: 0,
            animation: {
                duration: 1500,
                easing: 'inAndOut',
                startup: true
            },
            'chartArea':{'width':'100%', 'height':'80%'},
            fontSize: 9,
            pieHole: 0.6,
            legend: { position: 'bottom' },
            colors: ['#98FB98', '#00FA9A', '#00FF7F', '#2E8B57', '#008000','#6B8E23', '#808000', '#ADFF2F', '#ADFF2F']
        };
        var options2 = {
            width: 500,
            height: 400,
            fontSize: 8,
            pieHole: 0.4,
        };
        //var chart = new google.visualization.DonutChart(document.getElementById("operator"));
        var chart = new google.visualization.PieChart(document.getElementById(div));
        chart.draw(data, options);
        google.visualization.events.addListener(chart, 'select', selectHandler);

        function selectHandler(e) {
            var selection = chart.getSelection();
            $("#tableDataBox").hide();
            if(selection.length){
                $(".loading").html("<div class=\""+"overlay\""+"><i class=\""+"fa fa-refresh fa-spin\""+"></i></div>");
                var item = selection[0];
                operatorName = data.getFormattedValue(item.row, 0);
                operatorOperation=0;
                year="";
                month="";
                quarter="";
                xtittles="year";
                cardTypes="";
                tittles= 'Amount by Year: '+operatorName;
                bankName=operatorName;
                showUser("transactionamount","all");
            }

        }

    }
</script>