<script>
         var table;
         var client;
         var month;
         var year;
         var header;
         var paidFunction=false;
         var detailsFunction=false;
         var invoiceFunction=false;
         var unpaidFunction=false;
         var fclients;
         var finvoice_id;
         var fmonths;
         var fyears;
         var frunning_ar;
         var clientNameToId=[];
         var clientIdtoName=[];
         const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    // ....................................Select >> Option data load..................................
            for (var i = 0; i < 12; i++) {
                $('#month').append('<option value=' + '\'' + monthNames[i] + '\'' +'>' + monthNames[i] + '</option>');
            }
    $.ajax({
        url: 'init/invoice',
        type: 'GET',
        dataType: "json",
        success:function(data){  
            // alert("OK");
           for (var i = 0; i < data['client'].length; i++) {
                // $('#client').append('<option value=' + '\''+ data['client'][i]['client_id'] + '\''+ '>' + data['client'][i]['client_name'] + '</option>');
                clientNameToId[data['client'][i]['client_name']]=data['client'][i]['client_id'];
                clientIdtoName[data['client'][i]['client_id']]=data['client'][i]['client_name'];
            }
            // for (var i = 0; i < data['year'].length; i++) {
            //     $('#year').append('<option value=' + '\''+data['year'][i]['year']+'\'' + '>' + data['year'][i]['year'] + '</option>');
            // }
        },
         error: function (){alert('aaa');}
    });
// ....................................Select >> Option data load..................................
    function selectData(){

         if($('#client').val()!=null)client=$('#client').val()+'';         
         if($('#month').val()!=null) month=$('#month').val()+'';         
         if($('#year').val()!=null) year =$('#year').val()+'';
         $('#myModal').modal('toggle');
    }
//=================================Summary Invoce ============================================
    function summaryInvoiceTable(){
        if(!invoiceFunction && !unpaidFunction && !paidFunction){
            selectData();
            header="";
            if(client.length)header="client: "+clientIdtoName[client]+'\n';else header="ALL Clients \n";
            if(month.length)header+=" for "+month; else header+=" 12 Months";
            if(year.length)header+=" '"+year; else header += " All Year";
        }else {
            header="";
            var client_for_header=fclients;
            fclients=clientNameToId[fclients];
            if(client_for_header.length)header="client: "+client_for_header+'\n';
            if(fmonths.length)header+=" for "+fmonths;
            if(fyears.length)header+=" '"+fyears;
            month=fmonths;
            year=fyears;
            client=fclients;
        }

        document.getElementById("tableData").innerHTML="<table id='dataTable' class='display' style='width:100%'><thead><tr><th>Month</th><th>Client Name</th><th>Operator</th><th>Amount</th><th>Commission</th><th>AIT</th><th>SSL Portion</th><th>Client Portion</th><th>AR Amount</th><th>% of Telco Com</th><th>% of SSL Com</th><th>% of Client Com</th></tr></thead><tfoot><tr><th>Month</th><th>Client Name</th><th>Operator</th><th>Amount</th><th>Commission</th><th>AIT</th><th>SSL Portion</th><th>Client Portion</th><th>AR Amount</th><th>% of Telco Com</th><th>% of SSL Com</th><th>% of Client Com</th></tr></tfoot></table>";

            $.ajax({
            url: 'summaryinvoice/invoice',
            type: 'GET',
            dataType: "json",
            data: {
                clients : client,         
                years : year,         
                months : month
            },
            success:function(data){    
                if(data.length==0)alert("There is no data for this filter");
                else {
                    table=$('#dataTable').DataTable( {
                        "data": data,
                        "columns": [
                            { 'data':"month" },
                            { 'data':"client_name" },
                            { 'data':"operator_name" },
                            { 'data':"amount",render: $.fn.dataTable.render.number( ',', '.', 0, '' )},
                            { 'data':"commission" ,render: $.fn.dataTable.render.number( ',', '.', 0, '' )},
                            { 'data':"ait",render: $.fn.dataTable.render.number( ',', '.', 0, '' ) },
                            { 'data':"sslportion" ,render: $.fn.dataTable.render.number( ',', '.', 0, '' )},
                            { 'data':"clientportion" ,render: $.fn.dataTable.render.number( ',', '.', 0, '' )},
                            { 'data':"aramount" ,render: $.fn.dataTable.render.number( ',', '.', 0, '' )},
                            { 'data':"client_commission",render: function(data, type, row){
                                    return parseFloat(data * 100).toFixed(4) + "%";
                            }},
                            { 'data':"ssl_rate" ,render: function(data, type, row){
                                    return parseFloat(data * 100).toFixed(4) + "%";
                            }},
                            { 'data':"clicent_rate",render: function(data, type, row){
                                    return parseFloat(data * 100).toFixed(4) + "%";
                            } }
                        ],
                        dom: 'Bfrtip',
                        // responsive: true,
                        aLengthMenu: [
                            [20, 50, 100, -1],
                            [ '20 rows', '50 rows', '100 rows', 'Show all' ]
                        ],
                        iDisplayLength: 20,
                        buttons: [
                            'pageLength','copy',{
                                                extend: 'csv',
                                                messageTop: header,
                                                footer:true
                                                },{
                                                extend: 'excel',
                                                messageTop: header,
                                                footer:true
                                                }, {
                                                extend: 'print',
                                                messageTop: header,
                                                footer:true
                                                }
                        ]
                        ,"footerCallback": function ( row, data, start, end, display ) {
                            var api = this.api(), data;
                            var cnt=0;
                            var amount = api
                                .column( 3 )
                                .data()
                                .reduce( function (a, b) {
                                    cnt++;
                                    return parseFloat(a) + parseFloat(b);
                                }, 0 );
                            var commission = api
                                .column( 4 )
                                .data()
                                .reduce( function (a, b) {
                                    return parseFloat(a) + parseFloat(b);
                                }, 0 );
                            var ait = api
                                .column( 5 )
                                .data()
                                .reduce( function (a, b) {
                                    return parseFloat(a) + parseFloat(b);
                                }, 0 );
                            var sslportion = api
                                .column( 6 )
                                .data()
                                .reduce( function (a, b) {
                                    return parseFloat(a) + parseFloat(b);
                                }, 0 );
                            var clientportion = api
                                .column( 7 )
                                .data()
                                .reduce( function (a, b) {
                                    return parseFloat(a) + parseFloat(b);
                                }, 0 );
                            var aramount = api
                                .column( 8 )
                                .data()
                                .reduce( function (a, b) {
                                    return parseFloat(a) + parseFloat(b);
                                }, 0 );
                            var telcocom = api
                                .column( 9 )
                                .data()
                                .reduce( function (a, b) {
                                    return parseFloat(a) + parseFloat(b);
                                }, 0 );
                            var sslcom = api
                                .column( 10 )
                                .data()
                                .reduce( function (a, b) {
                                    return a+ b;
                                }, 0 );
                            var clientcom = api
                                .column( 11 )
                                .data()
                                .reduce( function (a, b) {
                                    return parseFloat(a) + parseFloat(b);
                                }, 0 );
                            // Update footer by showing the total with the reference of the column index
                            $( api.column( 0 ).footer() ).html('Total');
                            $( api.column( 1 ).footer() ).html("-----");
                            $( api.column( 2 ).footer() ).html("-----");
                            $( api.column( 3 ).footer() ).html(numberWithCommas(Math.round(amount)));
                            $( api.column( 4 ).footer() ).html(numberWithCommas(Math.round(commission)));
                            $( api.column( 5 ).footer() ).html(numberWithCommas(Math.round(ait)));
                            $( api.column( 6 ).footer() ).html(numberWithCommas(Math.round(sslportion)));
                            $( api.column( 7 ).footer() ).html(numberWithCommas(Math.round(clientportion)));
                            $( api.column( 8 ).footer() ).html(numberWithCommas(Math.round(aramount)));
                            $( api.column( 9 ).footer() ).html(parseFloat((telcocom * 100.0)/cnt).toFixed(4) + "%");
                            $( api.column( 10 ).footer() ).html(parseFloat((sslcom * 100.0)/cnt).toFixed(4) + "%");
                            $( api.column( 11 ).footer() ).html(parseFloat((clientcom * 100.0)/cnt).toFixed(4) +"%");
                        }
              
                    });
                }
             $('#myModal').modal('toggle');
             $('#tableDataBox').show();
            },
             error: function (){
                 alert('error table');
                $('#myModal').modal('toggle');
                $('#tableDataBox').show();
             }
             
        });
    

    }
//=================================Invoice for paid & Unpaid client=====================================
    function invoice(){
        invoiceFunction=true;
         selectData();
         for(var i=0;i<12;i++)if(monthNames[i]==month)month=i;
         header="";
            if(client.length)header="client: "+clientIdtoName[client]+'\n';else header="ALL Clients \n";
            if(month.length)header+=" for "+month; else header+=" 12 Months";
            if(year.length)header+=" '"+year; else header += " All Year";

        document.getElementById("tableData").innerHTML="<table id='dataTable'class='display' style='width:100%; cellspacing='0' '><thead><tr><th>Client</th><th>Invoice ID</th><th>Month</th><th>Year</th><th>Collection Date</th><th>Receivable</th><th>Collection</th><th>Runnig AR</th><th>Status</th><th>Action</th></tr></thead><tfoot><tr><th>Client</th><th>Invoice ID</th><th>Month</th><th>Year</th><th>Collection Date</th><th>Receivable</th><th>Collection</th><th>Runnig AR</th><th>Status</th><th>Action</th></tr></tfoot></table>";

           $.ajax({
            url: 'invoice/invoice',
            type: 'GET',
            dataType: "json",
            data: {
                clients : client,         
                years : year,         
                months : month
            },
            success:function(data){    
                if(data.length==0)alert("There is no data for this filter");
                else {
                    table=$('#dataTable').DataTable( {
                        "data": data,
                        "columns": [
                            { 'data':"client_name" },
                            { 'data':"invoice_id" },
                            { 'data':"month",render:function(data, type, row){return monthNames[data-1];} },
                            { 'data':"year" },
                            { 'data':"collection_date" },
                            { 'data':"receivable" ,render: $.fn.dataTable.render.number( ',', '.', 0, '' )},
                            { 'data':"collection",render: $.fn.dataTable.render.number( ',', '.', 0, '' ) },
                            { 'data':"running_ar",render: $.fn.dataTable.render.number( ',', '.', 0, '' ) },
                            { 'data':"status" },
                            { 'data':'status',
                                render: function ( data, type, row ){
                                        if(data==='Pending')return '<ul class="list-inline"><li  onclick="payment(this)"  style="width:45%;" class="list-inline-item fa fa-credit-card btn btn-link"> Pay</li>  <li onclick="details(this)" style="width:45%; "class="list-inline-item invoiceview glyphicon glyphicon-file btn btn-link">Details</li></ul>'
                                        else return '<ul class="list-inline"><li  onclick="details(this)" style="width:90%;" class="invoiceview glyphicon glyphicon-file btn btn-link">Details</li></ul>'
                                        
                                        }
                             }
                        ],
                        dom: 'Bfrtip',
                        aLengthMenu: [
                            [20, 50, 100, -1],
                            [ '20 rows', '50 rows', '100 rows', 'Show all' ]
                        ],
                        iDisplayLength: 20,
                        buttons: [
                            'pageLength','copy',{
                                                extend: 'csv',
                                                messageTop: header,
                                                footer:true
                                                },{
                                                extend: 'excel',
                                                messageTop: header,
                                                footer:true
                                                }, {
                                                extend: 'print',
                                                messageTop: header,
                                                footer:true
                                                }
                        ],
                        "footerCallback": function ( row, data, start, end, display ) {
                            var api = this.api(), data;
                            var receivable = api
                                .column( 5 )
                                .data()
                                .reduce( function (a, b) {
                                    return parseFloat(a) + parseFloat(b);
                                }, 0 );
                            var collection = api
                                .column( 6 )
                                .data()
                                .reduce( function (a, b) {
                                    return parseFloat(a) + parseFloat(b);
                                }, 0 );
                            var running_ar = api
                                .column( 7 )
                                .data()
                                .reduce( function (a, b) {
                                    return parseFloat(a) + parseFloat(b);
                                }, 0 );
                            // Update footer by showing the total with the reference of the column index
                            $( api.column( 0 ).footer() ).html('Total');
                            $( api.column( 1 ).footer() ).html("-----");
                            $( api.column( 2 ).footer() ).html("-----");
                            $( api.column( 3 ).footer() ).html("-----");
                            $( api.column( 4 ).footer() ).html("-----");
                            $( api.column( 5 ).footer() ).html(numberWithCommas(Math.round(receivable)));
                            $( api.column( 6 ).footer() ).html(numberWithCommas(Math.round(collection)));
                            $( api.column( 7 ).footer() ).html(numberWithCommas(Math.round(running_ar)));
                            $( api.column( 8 ).footer() ).html('-----');
                            $( api.column( 9 ).footer() ).html('-----');
                        }              
                    });
                }
             $('#myModal').modal('toggle');
             $('#tableDataBox').show();
            },
             error: function (){
                 alert('error table');
                $('#myModal').modal('toggle');
                $('#tableDataBox').show();
             }
             
        });
    invoiceFunction=true;
    }
//==========================================Operator wise Invoice=======================================

    function operatorWiseInvoice(){
                 selectData();

         if(client!=null)header="client: "+client+'\n';
         if(month!=null)header+=" for "+month;
         if(year!=null)header+=" '"+year;

        document.getElementById("tableData").innerHTML="<table id='dataTable' class='display' style='width:100%'><thead><tr><th>Month</th><th>Operator Name</th><th>Amount</th><th>Operator Rate</th><th>Operator Commission</th><th>Net Amount</th></tr></thead><tfoot><tr><th>Month</th><th>Operator Name</th><th>Amount</th><th>Operator Rate</th><th>Operator Commission</th><th>Net Amount</th></tr></tfoot></table>";

           $.ajax({
            url: 'operatorwiseinvoice/invoice',
            type: 'GET',
            dataType: "json",
            data: {
                years : year,         
                months : month
            },
            success:function(data){    
                if(data.length==0)alert("There is no data for this filter");
                else {
                    table=$('#dataTable').DataTable( {
                        "data": data,
                        "columns": [
                            { 'data':"month" },
                            { 'data':"operator_name" },
                            { 'data':"amount" ,render: $.fn.dataTable.render.number( ',', '.', 0, '' ) },
                            { 'data':"rate" ,render:function(data, type, row){return parseFloat(data * 100).toFixed(4) + "%";}},
                            { 'data':"operator_com" ,render: $.fn.dataTable.render.number( ',', '.', 0, '' )},
                            { 'data':"net_amount",render: $.fn.dataTable.render.number( ',', '.', 0, '' ) }
                        ],
                        dom: 'Bfrtip',
                        aLengthMenu: [
                            [20, 50, 100, -1],
                            [ '20 rows', '50 rows', '100 rows', 'Show all' ]
                        ],
                        iDisplayLength: 20,
                        buttons: [
                            'pageLength','copy',{
                                                extend: 'csv',
                                                messageTop: header,
                                                footer:true
                                                },{
                                                extend: 'excel',
                                                messageTop: header,
                                                footer:true
                                                }, {
                                                extend: 'print',
                                                messageTop: header,
                                                footer:true
                                                }
                        ],
                        "footerCallback": function ( row, data, start, end, display ) {
                            var api = this.api(), data;
                            var amount = api
                                .column( 2 )
                                .data()
                                .reduce( function (a, b) {
                                    return parseFloat(a) + parseFloat(b);
                                }, 0 );
                            var operatorrate = api
                                .column( 3 )
                                .data()
                                .reduce( function (a, b) {
                                    return parseFloat(a) + parseFloat(b);
                                }, 0 );
                            var operatorcomm = api
                                .column( 4 )
                                .data()
                                .reduce( function (a, b) {
                                    return parseFloat(a) + parseFloat(b);
                                }, 0 );
                            var netamount = api
                                .column( 5 )
                                .data()
                                .reduce( function (a, b) {
                                    return parseFloat(a) + parseFloat(b);
                                }, 0 );
                            // Update footer by showing the total with the reference of the column index
                            $( api.column( 0 ).footer() ).html('Total');
                            $( api.column( 1 ).footer() ).html("-------");
                            $( api.column( 2 ).footer() ).html(numberWithCommas(Math.round(amount)));
                            $( api.column( 3 ).footer() ).html(parseFloat(operatorrate*100.00).toFixed(4)+"%");
                            $( api.column( 4 ).footer() ).html(numberWithCommas(Math.round(operatorcomm)));
                            $( api.column( 5 ).footer() ).html(numberWithCommas(Math.round(netamount)));
                        }
              
                    });
                }
             $('#myModal').modal('toggle');
             $('#tableDataBox').show();
            },
             error: function (){
                 alert('error table');
                $('#myModal').modal('toggle');
                $('#tableDataBox').show();
             }
             
        });

    }
    function easyClientInvoice(){
        selectData();
        header="";
            // if(client.length)header="client: "sclientIdtoName[client]+'\n';else header="ALL Clients \n";
            if(month.length)header+=" For "+month; else header+=" 12 Months";
            if(year.length)header+=" '"+year; else header += " All Year";
        document.getElementById("tableData").innerHTML="<table id='dataTable' class='display' style='width:100%'><thead><tr><th>Month</th><th>Client</th><th>Operator</th><th>Amount</th></tr></thead><tfoot><tr><th>Month</th><th>Client</th><th>Operator</th><th>Amount</th></tr></tfoot></table>";

            $.ajax({
            url: 'easyinvoice/invoice',
            type: 'GET',
            dataType: "json",
            data: {
                years : year,         
                months : month
            },
            success:function(data){    
                if(data.length==0)alert("There is no data for this filter");
                else {
                    table = $('#dataTable').DataTable( {
                        "data": data,
                        "columns": [
                            { 'data':"month" },
                            { 'data':"client_name" },
                            { 'data':"operator_name" },
                            { 'data':"amount",render: $.fn.dataTable.render.number( ',', '.', 0, '' ) }
                        ],
                        dom: 'Bfrtip',
                        aLengthMenu: [
                            [20, 50, 100, -1],
                            [ '20 rows', '50 rows', '100 rows', 'Show all' ]
                        ],
                        iDisplayLength: 20,
                        buttons: [
                            'pageLength','copy',{
                                                extend: 'csv',
                                                messageTop: header,
                                                footer:true
                                                },{
                                                extend: 'excel',
                                                messageTop: header,
                                                footer:true
                                                }, {
                                                extend: 'print',
                                                messageTop: header,
                                                footer:true
                                                }
                        ],
                        "footerCallback": function ( row, data, start, end, display ) {
                            var api = this.api(), data;
                            var amount = api
                                .column( 3 )
                                .data()
                                .reduce( function (a, b) {
                                    return parseFloat(a) + parseFloat(b);
                                }, 0 );
                            // Update footer by showing the total with the reference of the column index
                            $( api.column( 0 ).footer() ).html('Total');
                            $( api.column( 1 ).footer() ).html("------");
                            $( api.column( 2 ).footer() ).html('------');
                            $( api.column( 3 ).footer() ).html(numberWithCommas(Math.round(amount)));
                        }
              
                    });
                }
             $('#myModal').modal('toggle');
             $('#tableDataBox').show();
            },
             error: function (){
                 alert('error table');
                $('#myModal').modal('toggle');
                $('#tableDataBox').show();
             }
             
        });

    }
//===============================================easyQubee Invoice=======================================

    function easyqubee(){
        selectData();

        header="";
            // if(client.length)header="client: "+clientIdtoName[client]+'\n';else header="ALL Clients \n";
            if(month.length)header+=" For "+month; else header+=" 12 Months";
            if(year.length)header+=" '"+year; else header += " All Year";

        document.getElementById("tableData").innerHTML="<table id='dataTable' class='display' style='width:100%'><thead><tr><th>Month</th><th>Client</th><th>Operator</th><th>Amount</th></tr></thead><tfoot><tr><th>Month</th><th>Client</th><th>Operator</th><th>Amount</th></tr></tfoot></table>";
           $.ajax({
            url: 'easyqubee/invoice',
            type: 'GET',
            dataType: "json",
            data: {
                years : year,         
                months : month
            },
            success:function(data){    
                if(data.length==0)alert("There is no data for this filter");
                else {
                    table = $('#dataTable').DataTable( {
                        "data": data,
                        "columns": [
                            { 'data':"month" },
                            { 'data':"client_name" },
                            { 'data':"operator_name" },
                            { 'data':"amount" }
                        ],
                        dom: 'Bfrtip',
                        aLengthMenu: [
                            [20, 50, 100, -1],
                            [ '20 rows', '50 rows', '100 rows', 'Show all' ]
                        ],
                        iDisplayLength: 20,
                        buttons: [
                            'pageLength','copy',{
                                                extend: 'csv',
                                                messageTop: header,
                                                footer:true
                                                },{
                                                extend: 'excel',
                                                messageTop: header,
                                                footer:true
                                                }, {
                                                extend: 'print',
                                                messageTop: header,
                                                footer:true
                                                }
                        ],
                        "footerCallback": function ( row, data, start, end, display ) {
                            var api = this.api(), data;
                            var amount = api
                                .column( 3 )
                                .data()
                                .reduce( function (a, b) {
                                    return parseFloat(a) + parseFloat(b);
                                }, 0 );
                            // Update footer by showing the total with the reference of the column index
                            $( api.column( 0 ).footer() ).html('Total');
                            $( api.column( 1 ).footer() ).html("-------");
                            $( api.column( 2 ).footer() ).html("-------");
                            $( api.column( 3 ).footer() ).html(numberWithCommas(Math.round(amount)));
                        }
              
                    });
                }
             $('#myModal').modal('toggle');
             $('#tableDataBox').show();
            },
             error: function (){
                 alert('error table');
                $('#myModal').modal('toggle');
                $('#tableDataBox').show();
             }
             
        });
    }
//=================================================Paid Invoice========================================

    function paidInfo(){
        paidFunction=true;
         selectData();
         var month_for_header=month;
         for(var i=0;i<12;i++)if(monthNames[i]==month)month=i;
            header="";
            if(client.length)header="client: "+clientIdtoName[client]+'\n';else header="ALL Clients \n";
            if(month_for_header.length)header+=" for "+month_for_header; else header+=" 12 Months";
            if(year.length)header+=" '"+year; else header += " All Year";
        document.getElementById("tableData").innerHTML="<table id='dataTable' class='display' style='width:100%'><thead><tr><th>Client</th><th>Invoice ID</th><th>Month</th><th>Year</th><th>Collection Date</th><th>Receivable</th><th>Collection</th><th>Runnig AR</th><th>Status</th><th>Action</th></tr></thead><tfoot><tr><th>Client</th><th>Invoice ID</th><th>Month</th><th>Year</th><th>Collection Date</th><th>Receivable</th><th>Collection</th><th>Runnig AR</th><th>Status</th><th>Action</th></tr></tfoot></table>";

           $.ajax({
            url: 'paidinvoice/invoice',
            type: 'GET',
            dataType: "json",
            data: {
                clients : client,         
                years : year,         
                months : month
            },
            success:function(data){    
                if(data.length==0)alert("There is no data for this filter");
                else {
                   table = $('#dataTable').DataTable( {
                        "data": data,
                        "columns": [
                            { 'data':"client_name" },
                            { 'data':"invoice_id" },
                            { 'data':"month",render:function(data, type, row){return monthNames[data-1];} },
                            { 'data':"year" },
                            { 'data':"collection_date" },
                            { 'data':"receivable" ,render: $.fn.dataTable.render.number( ',', '.', 0, '' )},
                            { 'data':"collection",render: $.fn.dataTable.render.number( ',', '.', 0, '' ) },
                            { 'data':"running_ar",render: $.fn.dataTable.render.number( ',', '.', 0, '' ) },
                            { 'data':"status" },
                            { 'data':'status',
                                render: function ( data, type, row ){
                                        if(data==='Pending')return '<ul class="list-inline"><li  onclick="payment(this)"  style="width:45%;" class="list-inline-item fa fa-credit-card btn btn-link"> Pay</li>  <li onclick="details(this)" style="width:45%; "class="list-inline-item invoiceview glyphicon glyphicon-file btn btn-link">Details</li></ul>'
                                        else return '<ul class="list-inline"><li  onclick="details(this)" style="width:90%;" class="invoiceview glyphicon glyphicon-file btn btn-link">Details</li></ul>'
                                        
                                        }
                             }
                        ],
                        dom: 'Bfrtip',
                        aLengthMenu: [
                            [20, 50, 100, -1],
                            [ '20 rows', '50 rows', '100 rows', 'Show all' ]
                        ],
                        iDisplayLength: 20,
                        buttons: [
                            'pageLength','copy',{
                                                extend: 'csv',
                                                messageTop: header,
                                                footer:true
                                                },{
                                                extend: 'excel',
                                                messageTop: header,
                                                footer:true
                                                },{
                                                extend: 'print',
                                                messageTop: header,
                                                footer:true
                                                }
                        ],
                        "footerCallback": function ( row, data, start, end, display ) {
                            var api = this.api(), data;
                            var receivable = api
                                .column( 5 )
                                .data()
                                .reduce( function (a, b) {
                                    return parseFloat(a) + parseFloat(b);
                                }, 0 );
                            var collection = api
                                .column( 6 )
                                .data()
                                .reduce( function (a, b) {
                                    return parseFloat(a) + parseFloat(b);
                                }, 0 );
                            var running_ar = api
                                .column( 7 )
                                .data()
                                .reduce( function (a, b) {
                                    return parseFloat(a) + parseFloat(b);
                                }, 0 );
                            // Update footer by showing the total with the reference of the column index
                            $( api.column( 0 ).footer() ).html('Total');
                            $( api.column( 1 ).footer() ).html("-----");
                            $( api.column( 2 ).footer() ).html("-----");
                            $( api.column( 3 ).footer() ).html("-----");
                            $( api.column( 4 ).footer() ).html("-----");
                            $( api.column( 5 ).footer() ).html(numberWithCommas(Math.round(receivable)));
                            $( api.column( 6 ).footer() ).html(numberWithCommas(Math.round(collection)));
                            $( api.column( 7 ).footer() ).html(numberWithCommas(Math.round(running_ar)));
                            $( api.column( 8 ).footer() ).html('-----');
                            $( api.column( 9 ).footer() ).html('-----');
                        }              
                    });
                }
             $('#myModal').modal('toggle');
             $('#tableDataBox').show();
            },
             error: function (){
                 alert('error table');
                $('#myModal').modal('toggle');
                $('#tableDataBox').show();
             }
             
        });
    }
//=================================================Unpaid Invoice========================================
    function unpaidInfo(){
        unpaidFunction=true;
        selectData();
        var month_for_header=month;
         for(var i=0;i<12;i++)if(monthNames[i]==month)month=i+1;
         header="";
            if(client.length)header="client: "+clientIdtoName[client]+'\n';else header="ALL Clients \n";
            if(month_for_header.length)header+=" for "+month_for_header; else header+=" 12 Months";
            if(year.length)header+=" '"+year; else header += " All Year";
         document.getElementById("tableData").innerHTML="";
        document.getElementById("tableData").innerHTML="<table id='dataTable' class='display' style='width:100%'><thead><tr><th>Client</th><th>Invoice ID</th><th>Month</th><th>Year</th><th>Collection Date</th><th>Receivable</th><th>Collection</th><th>Runnig AR</th><th>Status</th><th>Action</th></tr></thead><tfoot><tr><th>Client</th><th>Invoice ID</th><th>Month</th><th>Year</th><th>Collection Date</th><th>Receivable</th><th>Collection</th><th>Runnig AR</th><th>Status</th><th>Action</th></tr></tfoot></table>";

           $.ajax({
            url: 'unpaidinvoice/invoice',
            type: 'GET',
            dataType: "json",
            data: {
                clients : client,         
                years : year,         
                months : month
            },
            success:function(data){    
                if(data.length==0)alert("There is no data for this filter");
                else {
                    table=$('#dataTable').DataTable( {
                        "data": data,
                        "columns": [
                            { 'data':"client_name" },
                            { 'data':"invoice_id" },
                            { 'data':"month",render:function(data, type, row){return monthNames[data-1];} },
                            { 'data':"year" },
                            { 'data':"collection_date" },
                            { 'data':"receivable" ,render: $.fn.dataTable.render.number( ',', '.', 0, '' )},
                            { 'data':"collection",render: $.fn.dataTable.render.number( ',', '.', 0, '' ) },
                            { 'data':"running_ar",render: $.fn.dataTable.render.number( ',', '.', 0, '' ) },
                            { 'data':"status" },
                            { 'data':'status',
                                render: function ( data, type, row ){
                                        if(data==='Pending')return '<ul class="list-inline"><li  onclick="payment(this)"  style="width:45%;" class="list-inline-item fa fa-credit-card btn btn-link"> Pay</li>  <li onclick="details(this)" style="width:45%; "class="list-inline-item invoiceview glyphicon glyphicon-file btn btn-link">Details</li></ul>'
                                        else return '<ul class="list-inline"><li  onclick="details(this)" style="width:90%;" class="invoiceview glyphicon glyphicon-file btn btn-link">Details</li></ul>'
                                        
                                        }
                             }
                        ],
                        dom: 'Bfrtip',
                        aLengthMenu: [
                            [20, 50, 100, -1],
                            [ '20 rows', '50 rows', '100 rows', 'Show all' ]
                        ],
                        iDisplayLength: 20,
                        // paging: false,
                        buttons: [
                            'pageLength','copy',{
                                                extend: 'csv',
                                                messageTop: header,
                                                footer:true
                                                },{
                                                extend: 'excel',
                                                messageTop: header,
                                                footer:true
                                                }, {
                                                extend: 'print',
                                                messageTop: header,
                                                footer:true
                                                }
                        ],
                        "footerCallback": function ( row, data, start, end, display ) {
                            var api = this.api(), data;
                            var receivable = api
                                .column( 5 )
                                .data()
                                .reduce( function (a, b) {
                                    return parseFloat(a) + parseFloat(b);
                                }, 0 );
                            var collection = api
                                .column( 6 )
                                .data()
                                .reduce( function (a, b) {
                                    return parseFloat(a) + parseFloat(b);
                                }, 0 );
                            var running_ar = api
                                .column( 7 )
                                .data()
                                .reduce( function (a, b) {
                                    return parseFloat(a) + parseFloat(b);
                                }, 0 );
                            // Update footer by showing the total with the reference of the column index
                            $( api.column( 0 ).footer() ).html('Total');
                            $( api.column( 1 ).footer() ).html("-----");
                            $( api.column( 2 ).footer() ).html("-----");
                            $( api.column( 3 ).footer() ).html("-----");
                            $( api.column( 4 ).footer() ).html("-----");
                            $( api.column( 5 ).footer() ).html(numberWithCommas(Math.round(receivable)));
                            $( api.column( 6 ).footer() ).html(numberWithCommas(Math.round(collection)));
                            $( api.column( 7 ).footer() ).html(numberWithCommas(Math.round(running_ar)));
                            $( api.column( 8 ).footer() ).html('-----');
                            $( api.column( 9 ).footer() ).html('-----');
                        }              
                    });
                }
             $('#myModal').modal('toggle');
             $('#tableDataBox').show();
            },
             error: function (){
                 alert('error table');
                $('#myModal').modal('toggle');
                $('#tableDataBox').show();
             }
             
        });
    }
//==============================Paid Button Invoice=======================================
 
function payment(e) {
        var row = e.parentNode.parentNode.parentNode;
        var rowIndex = row.rowIndex - 1;
        fclients = row.cells[0].innerHTML;
        finvoice_id = row.cells[1].innerHTML;
        fmonths = row.cells[2].innerHTML;
        fyears = row.cells[3].innerHTML;
        frunning_ar = row.cells[7].innerHTML;
        
        
        $("#addPaymentAmount").val(frunning_ar);
        $("#addInvoiceAmount").val(frunning_ar);
        $("#addInvoiceId").val(finvoice_id);
        $(".payment").modal('toggle');
        $('form').submit(function() {
            return false;
        });

       
    }
    function actionForPayment(){
        if($("#addInvoiceId").val().length==0 || $("#addPaymentDate").val().length==0  || $("#addInvoiceAmount").val().length==0  || $("#addPaymentAmount").val().length==0 ){
            alert("Empty field not allow");
        }else {
            $(".payment").modal('toggle');
            $("#addInvoiceId1").val($("#addInvoiceId").val());
            $("#addPaymentDate1").val($("#addPaymentDate").val());
            $("#addInvoiceAmount1").val($("#addInvoiceAmount").val());
            $("#addPaymentAmount1").val($("#addPaymentAmount").val());
            $(".confirm").modal('toggle');
            $('form').submit(function() {
                return false;
            });
        }

    }
    function actionForConfirmPayment(){
        alert(clientNameToId[fclients]+" "+fclients);
         for(var i=0;i<12;i++)if(monthNames[i]==fmonths)fmonths=i;
         $(".confirm").modal('toggle');
        $.ajax({
            url:'updatepayment/invoice',
            type:'GET',
            data:{
                client:clientNameToId[fclients],
                month:fmonths,
                year:fyears,
                invoice_id:$("#addInvoiceId").val(),
                date:$("#addPaymentDate1").val(),
                amount:$("#addPaymentAmount").val()

            },
            success:function(data){
                alert(data);
                if(paidFunction==true)paidInfo();
                if(unpaidFunction==true)unpaidInfo();
                if(invoiceFunction==true)invoice();
                $('form').submit(function() {
                    return false;
                });
            },
            error:function(){
                $(".confirm").modal('toggle');
                alert('Something went wrong!!');

            }

        });
    }
    function updateAgain(){
        $(".payment").modal('toggle');
        $(".confirm").modal('toggle');
        $('form').submit(function() {
        return false;
        });
    }
    function cancelPayment(){
        $(".payment").modal('toggle');
        $('form').submit(function() {
        return false;
        });
    }
//=====================================Unpaid Invoice=======================================
 
    function details(e) {
    var row = e.parentNode.parentNode.parentNode;
    var rowIndex = row.rowIndex - 1;
    fclients = row.cells[0].innerHTML;
    fmonths = row.cells[2].innerHTML;
    fyears= row.cells[3].innerHTML;
    $('#myModal').modal('toggle');  
    summaryInvoiceTable();
    paidFunction=false;
    unpaidFunction=false;
    invoiceFunction=false;
    client=month=year='';
    } 
</script>