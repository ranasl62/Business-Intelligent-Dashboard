<script>
    function smsNumberOfTransactionChart(result, element,div){
        resultYearWC=result;
        google.setOnLoadCallback();
        var data = new google.visualization.DataTable(result);
        var options = { //colors: ['#12B6F3'],
            'chartArea':{'width':'100%'},
            animation: {
                duration: 500,
                easing: 'inAndOut',
                startup: true
            },
            fontSize: 9,
            legend: { position: 'none' },
            hAxis: {
                title: xtittlesCount,
            },
            vAxis: {
                format: 'decimal',
                minValue: 0,
                textPosition: 'none'
            },
            annotations: {
                textStyle: {
                    color: 'black',
                }
            }

        };

        var chart = new google.visualization.ColumnChart(document.getElementById(div));
        var view = new google.visualization.DataView(data);
        var formatter = new google.visualization.NumberFormat({pattern: '###.#M'});
        formatter.format(data, 2);
        view.setColumns([0,1,
            {
                calc: "stringify",
                sourceColumn: 2,
                type: "string",
                role: "annotation"
            },3
        ]);

        chart.draw(view, options);
        google.visualization.events.addListener(chart, 'select', selectHandler);

        function selectHandler(e) {
            var selection = chart.getSelection();
            if(selection.length){
                var item = selection[0];
                var str0;
                str0 = data.getFormattedValue(item.row, 0);
                operatorOperationCount=(operatorOperationCount+upDownCount)%5;
                if(operatorOperationCount==0){
                    yearCount="";
                    monthCount="";
                    quarterCount="";
                    tittlesCount= ' Yearly Plan';
                    xtittlesCount= 'year';

                }
                //Total Ammount of Selected YearCount's quarterCount MonthCount  VS Selected YearCount's quarterCount monthCount

                else if(operatorOperationCount==1){
                    yearCount=str0;
                    monthCount="";
                    quarterCount="YES";
                    tittlesCount= ' Quarterly Plan : '+yearCount;
                    xtittlesCount= 'quarters of : '+yearCount;
                }
                //Total Ammount of Selected YearCount's  MonthCount  VS Selected YearCount's  monthCount
                else if(operatorOperationCount==2){
                    monthCount="";
                    quarterCount="";
                    tittlesCount= ' Monthly Plan : '+yearCount;
                    xtittlesCount= 'months of : '+yearCount;
                }
                //Total Ammount of Selected YearCount => MonthCount => Day VS Selected YearCount => MonthCount => Day
                else if(operatorOperationCount==3){
                    monthCount=str0;
                    quarterCount="";
                    tittlesCount= ' Daily Plan of Month: '+str0;
                    xtittlesCount= 'days of : '+mL[str0-1]+', '+yearCount;
                }
                else {
                    yearCount="";
                    monthCount="";
                    quarterCount="";
                    tittlesCount= ' Yearly Plan';
                    xtittlesCount= 'year';
                    operatorOperationCount=0;
                }
                // alert(yearCount+"Y "+monthCount+"M "+quarterCount+"Q "+day+"D "+operatorOperationCount);
                if(operatorOperationCount!=4){
                    $("#tableDataBox").hide();
                    $("#transactioncount").html('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
                    showUser(element,div);
                }
            }
        }

    }
</script>