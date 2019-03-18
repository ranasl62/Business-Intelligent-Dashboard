<script>
    function pgwAssumptionChart(result, element,div){
        resultAssumption=result;
        google.setOnLoadCallback();
        var data = new google.visualization.DataTable(result);
        var options = { colors: ['#f39c12'],
            'chartArea':{'width':'100%'},
            animation: {
                duration: 500,
                easing: 'inAndOut',
                startup: true
            },
            fontSize: 9,
            legend: { position: 'none' },
            vAxis: {
                format: 'decimal',
                minValue: 0,
                // title: 'amount',
                textPosition: 'none'
            },
            annotations: {
                textStyle: {
                    // fontSize: 10,
                    color: 'black',
                }
            }

        };

        var formatter = new google.visualization.NumberFormat({pattern: '###.#M'});
        formatter.format(data, 2);
        var chart = new google.visualization.ColumnChart(document.getElementById(div));
        var view = new google.visualization.DataView(data);
        view.setColumns([0,1,
            {
                calc: "stringify",
                sourceColumn: 2,
                type: "string",
                role: "annotation",
            }
        ]);

        chart.draw(view, options);


    }
</script>