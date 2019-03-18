<script>
    function industrys(result,element,div){
        resultIndustryWC=result;
        google.setOnLoadCallback();
        var data = new google.visualization.DataTable(result);
        var options = {
            sliceVisibilityThreshold: 0,
            'chartArea':{'width':'100%', 'height':'80%'},
            pieHole: 0.6,
            fontSize: 9,
            legend: { position: 'bottom' },
            colors: ['#006400', '#8FBC8F', '#2E8B57', '#98FB98', '#00FA9A','#32CD32', '#9ACD32', '#BDB76B', '#EEE8AA']
        };
        var chart = new google.visualization.PieChart(document.getElementById(div));

        chart.draw(data, options);
        google.visualization.events.addListener(chart, 'select', selectHandler);


        function selectHandler(e) {
            var selection = chart.getSelection();
            $("#tableDataBox").hide();
            if(selection.length){
                $(".loading").html("<div class=\""+"overlay\""+"><i class=\""+"fa fa-refresh fa-spin\""+"></i></div>");
                var item = selection[0];
                operatorOperation=0;
                clientName="";
                department1 = "";
                year="";
                month="";
                quarter="";
                industry=data.getFormattedValue(item.row, 0);
                xtittles="year";
                tittles= 'Amount by Year: '+industry;
                operatorName="";
                showUser("transactionamount","all");
            }

        }
    }
</script>