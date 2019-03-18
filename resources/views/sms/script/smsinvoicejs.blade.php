<script>
    var table;
         var company;
         var month;
         var year;
         var header;
         var paidFunction=false;
         var detailsFunction=false;
         var invoiceFunction=false;
         var unpaidFunction=false;
         var fcompany;
         var finvoice_id;
         var fmonths;
         var fyears;
         var frunning_ar;
         const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    // ....................................Select >> Option data load..................................
    // ....................................Select >> Option data load..................................
        for (var i = 0; i < 12; i++) {
            $('#month').append('<option value=' + '\'' + monthNames[i] + '\'' +'>' + monthNames[i] + '</option>');
        }
    // $.ajax({
    //     url: 'init/invoice',
    //     type: 'GET',
    //     dataType: "json",

    //     success:function(data){  
    //     // console.log(data);            
    //         for (var i = 0; i < data['month'].length; i++) {
    //             $('#month').append('<option value=' + '\'' + data['month'][i]['month'] + '\'' +'>' + data['month'][i]['month'] + '</option>');
    //             // console.log(data['month'][i]['month']);
    //         }
    //        for (var i = 0; i < data['company'].length; i++) {
    //             $('#company').append('<option value=' + '\''+ data['company'][i]['company'] + '\''+ '>' + data['company'][i]['company'] + '</option>');
    //         }
    //         for (var i = 0; i < data['year'].length; i++) {
    //             $('#year').append('<option value=' + '\''+data['year'][i]['year']+'\'' + '>' + data['year'][i]['year'] + '</option>');
    //         }
    //     },
    //      error: function (){alert('error');}
    // });

    function selectData(){

         if($('#company').val()!=null)company=$('#company').val()+'';         
         if($('#month').val()!=null) month=$('#month').val()+'';         
         if($('#year').val()!=null) year =$('#year').val()+'';
         $('#myModal').modal('toggle');
    }
// ....................................Select >> Option data load..................................
    function summaryInvoiceTable(){
        if(!invoiceFunction && !unpaidFunction && !paidFunction){
            selectData();
            if(company!=null)header="company: "+company+'\n';
            if(month!=null)header+=" for "+month;
            if(year!=null)header+=" '"+year;
        }else {
            company=fcompany;
            header="For: "+fcompany+"\n"+"Month: "+fmonths+"' "+fyears;  
            month=fmonths;
            year=fyears;
        }               
        // alert(company+" "+month+" "+year);
        document.getElementById("tableData").innerHTML="<table id='dataTable' class='display nowrap' style='width:100%'> <thead> <tr> <th>Month</th><th>company</th> <th>Stakeholder</th> <th>SMS Type</th> <th>Unit Price</th><th>SMS Count</th><th>Amount</th><th>SD Amount</th><th>Amount with SD </th><th>Vat Amount</th><th>Amount with Vat</th><th>SarCharge Amount</th><th>Invoice Amount</th> </tr> </thead> <tfoot> <tr> <th>Month</th><th>company</th> <th>Stakeholder</th> <th>SMS Type</th> <th>Unit Price</th><th>SMS Count</th><th>Amount</th><th>SD Amount</th><th>Amount with SD </th><th>Vat Amount</th><th>Amount with Vat</th><th>SarCharge Amount</th><th>Invoice Amount</th> </tr> </tfoot> </table>";
    $.ajax({
        url: 'invoice/summary/',
        type: 'GET',                                      
        dataType: "json",
        data: {
            
            company : company,         
            years : year,         
            months : month
        },
        success:function(data){    
            // console.log(data);
            if(data.length==0)alert("There is no data for this filter");
            else {
                $('#dataTable').DataTable( {
                    "data": data,
                    "columns": [
                        { 'data':"month" ,render:function(data,type,row){
                            return data+"' "+row['year'];
                        }},
                        { 'data':"company" },
                        { 'data':"stakeholder" },
                        { 'data':"smstype" },
                        { 'data':"unit_price" ,render: $.fn.dataTable.render.number( ',', '.', 4, '' )},
                        { 'data':"smscount" ,render: $.fn.dataTable.render.number( ',', '.', 0, '' )},
                        { 'data':"amount" ,render: $.fn.dataTable.render.number( ',', '.', 2, '' )},
                        { 'data':"sd_amount" ,render: $.fn.dataTable.render.number( ',', '.', 2, '' )},
                        { 'data':"amount_with_sd" ,render: $.fn.dataTable.render.number( ',', '.', 2, '' )},
                        { 'data':"vat_amount" ,render: $.fn.dataTable.render.number( ',', '.', 2, '' )},
                        { 'data':"amount_with_vat" ,render: $.fn.dataTable.render.number( ',', '.', 2, '' )},
                        { 'data':"sarcharge_amount" ,render: $.fn.dataTable.render.number( ',', '.', 2, '' )},
                        { 'data':"invoice_amount" ,render: $.fn.dataTable.render.number( ',', '.', 2, '' )}
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
                    ]
                    , "footerCallback": function ( row, data, start, end, display ) {
                        var api = this.api(), data;
                        var a5 = api
                            .column( 5 )
                            .data()
                            .reduce( function (a, b) {
                                return parseFloat(a) + parseFloat(b);
                            }, 0 );
                        var a6 = api
                            .column( 6 )
                            .data()
                            .reduce( function (a, b) {
                                return parseFloat(a) + parseFloat(b);
                            }, 0 );
                        var a7 = api
                            .column( 7 )
                            .data()
                            .reduce( function (a, b) {
                                return parseFloat(a) + parseFloat(b);
                            }, 0 );
                        var a8 = api
                            .column( 8 )
                            .data()
                            .reduce( function (a, b) {
                                return parseFloat(a) + parseFloat(b);
                            }, 0 );
                        var a9 = api
                            .column( 9 )
                            .data()
                            .reduce( function (a, b) {
                                return parseFloat(a) + parseFloat(b);
                            }, 0 );
                        var a10 = api
                            .column( 10 )
                            .data()
                            .reduce( function (a, b) {
                                return parseFloat(a) + parseFloat(b);
                            }, 0 );
                        var a11 = api
                            .column( 11 )
                            .data()
                            .reduce( function (a, b) {
                                return parseFloat(a) + parseFloat(b);
                            }, 0 );
                        var a12 = api
                            .column( 12 )
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
                        $( api.column( 5 ).footer() ).html(numberWithCommas(Math.round(a5)));
                        $( api.column( 6 ).footer() ).html(numberWithCommas(Math.round(a6)));
                        $( api.column( 7 ).footer() ).html(numberWithCommas(Math.round(a7)));
                        $( api.column( 8 ).footer() ).html(numberWithCommas(Math.round(a8)));
                        $( api.column( 9 ).footer() ).html(numberWithCommas(Math.round(a9)));
                        $( api.column( 10 ).footer() ).html(numberWithCommas(Math.round(a10)));
                        $( api.column( 11 ).footer() ).html(numberWithCommas(Math.round(a11)));
                        $( api.column( 12 ).footer() ).html(numberWithCommas(Math.round(a12)));
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
//.................................................Invoice........................
function invoice(){
        invoiceFunction=true;
         if($('#company').val()!=null)company=$('#company').val()+'';         
         if($('#month').val()!=null) month=$('#month').val()+'';         
         if($('#year').val()!=null) year =$('#year').val()+'';
         $('#myModal').modal('toggle');
         if(company!=null)header="company: "+company+'\n';
         if(month!=null)header+=" for "+month;
         if(year!=null)header+=" '"+year;;
                
        document.getElementById("tableData").innerHTML="<table id='dataTable' class='display nowrap' style='width:100%'> <thead> <tr> <th>Month</th><th>Year</th><th>company</th><th>Invoice ID</th><th>Receivable</th><th>Collection Date</th><th>Collection</th><th>Running AR</th><th>Status</th><th>Action</th></tr> </thead> <tfoot><tr> <th>Month</th><th>Year</th><th>company</th><th>Invoice ID</th><th>Receivable</th><th>Collection Date</th><th>Collection</th><th>Running AR</th><th>Status</th><th>Action</th></tr> </tfoot> </table>";
    $.ajax({
        url: 'invoice/invoice',
        type: 'GET',                                      
        dataType: "json",
        data: {
            
            company : company,         
            years : year,         
            months : month
        },
        success:function(data){    
            // console.log(data);
            if(data.length==0)alert("There is no data for this filter");
            else {
                $('#dataTable').DataTable( {
                    "data": data,
                    "columns": [
                        { 'data':"month"},
                        { 'data':"year" },
                        { 'data':"company" },
                        { 'data':"invoice_id" },
                        { 'data':"receivable" ,render: $.fn.dataTable.render.number( ',', '.', 0, '' )},
                        { 'data':"collection_date" },
                        { 'data':"collection" ,render: $.fn.dataTable.render.number( ',', '.', 2, '' )},
                        { 'data':"running_ar" ,render: $.fn.dataTable.render.number( ',', '.', 2, '' )},
                        { 'data':"status" ,render: function(data,type,row){if(data==0)return "Pending";return "Paid";}},
                        { 'data':'status',
                                render: function ( data, type, row ){
                                        if(data==0)return '<ul class="list-inline"><li  onclick="payment(this)"  style="width:45%;" class="list-inline-item fa fa-credit-card btn btn-link"> Pay</li>  <li onclick="details(this)" style="width:45%; "class="list-inline-item invoiceview glyphicon glyphicon-file btn btn-link">Details</li></ul>'
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
                    ]
                    , "footerCallback": function ( row, data, start, end, display ) {
                        var api = this.api(), data;
                        var a4 = api
                            .column( 4 )
                            .data()
                            .reduce( function (a, b) {
                                return parseFloat(a) + parseFloat(b);
                            }, 0 );
                        
                        var a6 = api
                            .column( 6 )
                            .data()
                            .reduce( function (a, b) {
                                return parseFloat(a) + parseFloat(b);
                            }, 0 );
                        var a7 = api
                            .column( 7 )
                            .data()
                            .reduce( function (a, b) {
                                return parseFloat(a) + parseFloat(b);
                            }, 0 );
                        // var a9 = api
                        //     .column( 9 )
                        //     .data()
                        //     .reduce( function (a, b) {
                        //         return parseFloat(a) + parseFloat(b);
                        //     }, 0 );
                        // Update footer by showing the total with the reference of the column index
                        $( api.column( 0 ).footer() ).html('Total');
                        $( api.column( 1 ).footer() ).html("-----");
                        $( api.column( 2 ).footer() ).html("-----");
                        $( api.column( 3 ).footer() ).html("-----");
                        $( api.column( 4 ).footer() ).html(numberWithCommas(Math.round(a4)));
                        $( api.column( 5 ).footer() ).html("-----");
                        $( api.column( 6 ).footer() ).html(numberWithCommas(Math.round(a6)));
                        $( api.column( 7 ).footer() ).html(numberWithCommas(Math.round(a7)));
                        $( api.column( 8 ).footer() ).html("-----");
                        $( api.column( 9 ).footer() ).html("-----");
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
//...................................................Paid............................
function paid(){
        paidFunction=true;
         if($('#company').val()!=null)company=$('#company').val()+'';         
         if($('#month').val()!=null) month=$('#month').val()+'';         
         if($('#year').val()!=null) year =$('#year').val()+'';
         $('#myModal').modal('toggle');
         if(company!=null)header="company: "+company+'\n';
         if(month!=null)header+=" for "+month;
         if(year!=null)header+=" '"+year;;
                
        document.getElementById("tableData").innerHTML="<table id='dataTable' class='display nowrap' style='width:100%'> <thead> <tr> <th>Month</th><th>Year</th><th>company</th><th>Invoice ID</th><th>Receivable</th><th>Collection Date</th><th>Collection</th><th>Running AR</th><th>Status</th><th>Action</th></tr> </thead> <tfoot><tr> <th>Month</th><th>Year</th><th>company</th><th>Invoice ID</th><th>Receivable</th><th>Collection Date</th><th>Collection</th><th>Running AR</th><th>Status</th><th>Action</th></tr> </tfoot> </table>";
    $.ajax({
        url: 'invoice/paid',
        type: 'GET',                                      
        dataType: "json",
        data: {
            
            company : company,         
            years : year,         
            months : month
        },
        success:function(data){    
            // console.log(data);
            if(data.length==0)alert("There is no data for this filter");
            else {
                $('#dataTable').DataTable( {
                    "data": data,
                    "columns": [
                        { 'data':"month"},
                        { 'data':"year" },
                        { 'data':"company" },
                        { 'data':"invoice_id" },
                        { 'data':"receivable" ,render: $.fn.dataTable.render.number( ',', '.', 0, '' )},
                        { 'data':"collection_date" },
                        { 'data':"collection" ,render: $.fn.dataTable.render.number( ',', '.', 2, '' )},
                        { 'data':"running_ar" ,render: $.fn.dataTable.render.number( ',', '.', 2, '' )},
                        { 'data':"status" ,render: function(data,type,row){if(data==0)return "Pending";return "Paid";}},
                        { 'data':'status',
                                render: function ( data, type, row ){
                                        if(data==0)return '<ul class="list-inline"><li  onclick="payment(this)"  style="width:45%;" class="list-inline-item fa fa-credit-card btn btn-link"> Pay</li>  <li onclick="details(this)" style="width:45%; "class="list-inline-item invoiceview glyphicon glyphicon-file btn btn-link">Details</li></ul>'
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
                    ]
                    , "footerCallback": function ( row, data, start, end, display ) {
                        var api = this.api(), data;
                        var a4 = api
                            .column( 4 )
                            .data()
                            .reduce( function (a, b) {
                                return parseFloat(a) + parseFloat(b);
                            }, 0 );
                        
                        var a6 = api
                            .column( 6 )
                            .data()
                            .reduce( function (a, b) {
                                return parseFloat(a) + parseFloat(b);
                            }, 0 );
                        var a7 = api
                            .column( 7 )
                            .data()
                            .reduce( function (a, b) {
                                return parseFloat(a) + parseFloat(b);
                            }, 0 );
                        // var a9 = api
                        //     .column( 9 )
                        //     .data()
                        //     .reduce( function (a, b) {
                        //         return parseFloat(a) + parseFloat(b);
                        //     }, 0 );
                        // Update footer by showing the total with the reference of the column index
                        $( api.column( 0 ).footer() ).html('Total');
                        $( api.column( 1 ).footer() ).html("-----");
                        $( api.column( 2 ).footer() ).html("-----");
                        $( api.column( 3 ).footer() ).html("-----");
                        $( api.column( 4 ).footer() ).html(numberWithCommas(Math.round(a4)));
                        $( api.column( 5 ).footer() ).html("-----");
                        $( api.column( 6 ).footer() ).html(numberWithCommas(Math.round(a6)));
                        $( api.column( 7 ).footer() ).html(numberWithCommas(Math.round(a7)));
                        $( api.column( 8 ).footer() ).html("-----");
                        $( api.column( 9 ).footer() ).html("-----");
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
//------------------------------------------------Unpaid-----------------------------
function unpaid(){
        unpaidFunction=true;
         if($('#company').val()!=null)company=$('#company').val()+'';         
         if($('#month').val()!=null) month=$('#month').val()+'';         
         if($('#year').val()!=null) year =$('#year').val()+'';
         $('#myModal').modal('toggle');
         if(company!=null)header="company: "+company+'\n';
         if(month!=null)header+=" for "+month;
         if(year!=null)header+=" '"+year;;
                
        document.getElementById("tableData").innerHTML="<table id='dataTable' class='display nowrap' style='width:100%'> <thead> <tr> <th>Month</th><th>Year</th><th>company</th><th>Invoice ID</th><th>Receivable</th><th>Collection Date</th><th>Collection</th><th>Running AR</th><th>Status</th><th>Action</th></tr> </thead> <tfoot><tr> <th>Month</th><th>Year</th><th>company</th><th>Invoice ID</th><th>Receivable</th><th>Collection Date</th><th>Collection</th><th>Running AR</th><th>Status</th><th>Action</th></tr> </tfoot> </table>";
    $.ajax({
        url: 'invoice/unpaid',
        type: 'GET',                                      
        dataType: "json",
        data: {            
            company : company,         
            years : year,         
            months : month
        },
        success:function(data){    
            // console.log(data);
            if(data.length==0)alert("There is no data for this filter");
            else {
                $('#dataTable').DataTable( {
                    "data": data,
                    "columns": [
                        { 'data':"month"},
                        { 'data':"year" },
                        { 'data':"company" },
                        { 'data':"invoice_id" },
                        { 'data':"receivable" ,render: $.fn.dataTable.render.number( ',', '.', 0, '' )},
                        { 'data':"collection_date" },
                        { 'data':"collection" ,render: $.fn.dataTable.render.number( ',', '.', 2, '' )},
                        { 'data':"running_ar" ,render: $.fn.dataTable.render.number( ',', '.', 2, '' )},
                        { 'data':"status" ,render: function(data,type,row){if(data==0)return "Pending";return "Paid";}},
                        { 'data':'status',
                                render: function ( data, type, row ){
                                        if(data==0)return '<ul class="list-inline"><li  onclick="payment(this)"  style="width:45%;" class="list-inline-item fa fa-credit-card btn btn-link"> Pay</li>  <li onclick="details(this)" style="width:45%; "class="list-inline-item invoiceview glyphicon glyphicon-file btn btn-link">Details</li></ul>'
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
                    ]
                    , "footerCallback": function ( row, data, start, end, display ) {
                        var api = this.api(), data;
                        var a4 = api
                            .column( 4 )
                            .data()
                            .reduce( function (a, b) {
                                return parseFloat(a) + parseFloat(b);
                            }, 0 );
                        
                        var a6 = api
                            .column( 6 )
                            .data()
                            .reduce( function (a, b) {
                                return parseFloat(a) + parseFloat(b);
                            }, 0 );
                        var a7 = api
                            .column( 7 )
                            .data()
                            .reduce( function (a, b) {
                                return parseFloat(a) + parseFloat(b);
                            }, 0 );
                        // var a9 = api
                        //     .column( 9 )
                        //     .data()
                        //     .reduce( function (a, b) {
                        //         return parseFloat(a) + parseFloat(b);
                        //     }, 0 );
                        // Update footer by showing the total with the reference of the column index
                        $( api.column( 0 ).footer() ).html('Total');
                        $( api.column( 1 ).footer() ).html("-----");
                        $( api.column( 2 ).footer() ).html("-----");
                        $( api.column( 3 ).footer() ).html("-----");
                        $( api.column( 4 ).footer() ).html(numberWithCommas(Math.round(a4)));
                        $( api.column( 5 ).footer() ).html("-----");
                        $( api.column( 6 ).footer() ).html(numberWithCommas(Math.round(a6)));
                        $( api.column( 7 ).footer() ).html(numberWithCommas(Math.round(a7)));
                        $( api.column( 8 ).footer() ).html("-----");
                        $( api.column( 9 ).footer() ).html("-----");
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
function payment(e) {
        var row = e.parentNode.parentNode.parentNode;
        var rowIndex = row.rowIndex - 1;
        fcompany = row.cells[2].innerHTML;
        finvoice_id = row.cells[4].innerHTML;
        fmonths = row.cells[0].innerHTML;
        fyears = row.cells[1].innerHTML;
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
        // alert(fcompany+" "+fmonths+" "+fyears+" "+$("#addInvoiceId").val()
            // +" "+$("#addPaymentDate1").val()+" "+$("#addPaymentAmount").val());
         // for(var i=0;i<12;i++)if(monthNames[i]==fmonths)fmonths=i;
         $(".confirm").modal('toggle');
        $.ajax({
            url:'updatepayment/invoice',
            type:'GET',
            data:{
                company:fcompany,
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
    fmonths = row.cells[0].innerHTML;
    fyears= row.cells[1].innerHTML;
    fcompany = row.cells[2].innerHTML;
    summaryInvoiceTable();
    $('#myModal').modal('toggle');  
    paidFunction=false;
    unpaidFunction=false;
    invoiceFunction=false;
    client=month=year='';
    } 
    // $('#dataTable').dataTable({
    //     "autoWidth": true
    // });

</script>