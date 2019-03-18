<script>
    var client_select=0;
    var kam_select=0;
    var department_select=0;
    var table;
$(document).ready(function() {
    $("#loading-div-background").css({ opacity: 0.8 });
    // ..................For data Picker.................
    $('#daterange-btn').daterangepicker(
             {
               ranges: {
                 'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                 'Last 7 Days': [moment().subtract(7, 'days'), moment().subtract(1, 'days')],
                 'Last 30 Days': [moment().subtract(30, 'days'), moment().subtract(1, 'days')],
                 'This Month': [moment().startOf('month'), moment().endOf('month')],
                 'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
               },
               startDate: moment().subtract(30, 'days'),
               endDate: moment()
             },
           function (start, end) {
             startDate=start.format('YYYY-MM-DD');
             endDate=end.format('YYYY-MM-DD');
             $('#daterange-btn').html(start.format('YYYY/MM/DD') + '-' + end.format('YYYY/MM/DD'));
           }
         );
    // ..................Select >> Option data load.................
    
    // $.ajax({
    //     url: 'init?',
    //     type: 'GET',
    //     dataType: "json",
    //     success:function(data){                    
    //        for (var i = 0; i < data['client'].length; i++) {
    //             $('#client').append('<option value=' + data['client'][i]['client_id'] + '>' + data['client'][i]['client_name'] + '</option>');
    //         }
    //         for (var i = 0; i < data['operator'].length; i++) {
    //             $('#operator').append('<option value=' + '\''+ data['operator'][i]['operator_id'] + '\''+ '>' + data['operator'][i]['operator_name'] + '</option>');
    //         }
    //         for (var i = 0; i < data['department'].length; i++) {
    //             $('#department').append('<option value=' + '\''+data['department'][i]['Department']+'\'' + '>' + data['department'][i]['Department'] + '</option>');
    //         }
    //         for (var i = 0; i < data['kam'].length; i++) {
    //            $('#kam').append('<option value=' + '\''+ data['kam'][i]['KAM'] + '\''+ '>' + data['kam'][i]['KAM'] + '</option>');
    //         }
    //     },
    //      error: function (){alert('error');}
    // });
});
// ..................Select >> Option data load.................
    function vrreport(){

         var department="";
         var KAM="";
         var client="";
         var operator="";
         var header="";

         if($('#department').val()!=null)department=$('#department').val()+'';
         if($('#kam').val()!=null) KAM=$('#kam').val()+'';
         if($('#client').val()!=null) client =$('#client').val()+'';
         if($('#operator').val()!=null) operator=$('#operator').val()+'';
         $('#myModal').modal('toggle');
        document.getElementById("tableData").innerHTML="<table id='dataTable' class='display' style='width:100%'><thead><tr><td>Transdate</td><td>Client</td><td>KAM</td><td>Department</td><td>Operator</td><td>Amount</td></tr></thead><tfoot><tr><td>Transdate</td><td>Client</td><td>KAM</td><td>Department</td><td>Operator</td><td>Amount</td></tr></tfoot></table>";
    $.ajax({
        url: 'filterreport',
        type: 'GET',
        dataType: "json",
        data: {
            departments : department,
            clients : client,
            kams : KAM,
            fromdate :startDate,
            todate:endDate,
            operators:operator
        },
        success:function(data){
            console.log(data);
            if(data.length==0)alert("There is no data for this filter");
            else {
                $('#dataTable').DataTable( {
                    "data": data,
                    "columns": [
                        { 'data':"transdate" },
                        { 'data':"client" },
                        { 'data':"KAM" },
                        { 'data':"department" },
                        { 'data':"operator" },
                        { 'data':"amount" ,render: $.fn.dataTable.render.number( ',', '.', 0, '' )}
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
                        var total = api
                            .column( 5 )
                            .data()
                            .reduce( function (a, b) {
                                return parseFloat(a) + parseFloat(b);
                            }, 0 );
                        // Update footer by showing the total with the reference of the column index
                        $( api.column( 0 ).footer() ).html('Total');
                        $( api.column( 1 ).footer() ).html("-");
                        $( api.column( 2 ).footer() ).html("-");
                        $( api.column( 3 ).footer() ).html("-");
                        $( api.column( 4 ).footer() ).html("-");
                        $( api.column( 5 ).footer() ).html(numberWithCommas(Math.round(total)));
                    }
                // {
                //     extend: 'excel',
                //         messageTop: 'The information in this table is copyright to Sirius Cybernetics Corp.'
                // },
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
function selector_update(){
    console.log(client_select+" "+department_select+" "+kam_select+"\n");
    console.log($("#kam").val()+" "+$("#department").val()+" "+$("#client").val()+"\n");
   $.ajax({
        url: 'init',
        type: 'GET',
        data:{
            client:client_select,
            department:department_select,
            kam:kam_select,
            kam_val:$("#kam").val(),
            department_val:$("#department").val(),
            client_val:$("#client").val(),
         },
        dataType: "json",
        success:function(data){
            console.log(data);
            if(data['client'].length>0){
                // $('#client').selectpicker('deselectAll');
                document.getElementById('client_updater').innerHTML='';
                $(document).find("#client_updater").html('<select id="client" onchange="client_onchange()" title="Client" class="selectpicker form-control" data-live-search="true" multiple></select>');
                for (var i = 0; i < data['client'].length; i++) {
                    $('#client').append('<option value=' + data['client'][i]['client_id'] + '>' + data['client'][i]['client_name'] + '</option>');
                }

                $('#client').selectpicker('render');
            }
            if(data['department'].length>0){
                // $('#department').selectpicker('deselectAll');
                document.getElementById('department_updater').innerHTML='';
                $(document).find("#department_updater").html('<select id="department" onchange="department_onchange()" title="Department" class="selectpicker form-control" data-live-search="true" multiple></select>');
                // $(document).find("#department_updater").html('<select id="department" title="Department" class="selectpicker form-control" data-live-search="true" multiple></select>');
                for (var i = 0; i < data['department'].length; i++) {
                    $('#department').append('<option value=' + '\''+data['department'][i]['Department']+'\'' + '>' + data['department'][i]['Department'] + '</option>');
                }

                $('#department').selectpicker('render');
            }
            if(data['kam'].length>0){
                //$('#kam').selectpicker('deselectAll');
                document.getElementById('kam_updater').innerHTML='';
                $(document).find("#kam_updater").html('<select id="kam" onchange="kam_onchange()" title="KAM" class="selectpicker form-control" data-live-search="true" multiple></select>');
                for (var i = 0; i < data['kam'].length; i++) {
                   $('#kam').append('<option value=' + '\''+ data['kam'][i]['KAM'] + '\''+ '>' + data['kam'][i]['KAM'] + '</option>');
                }
               $('#kam').selectpicker('render');
            }
        // console.log(data);
        },
         error: function (){alert('error');}
    }).done(function (data) {
        // console.log(data);

   });
}
function kam_onchange(){
    // rana();
    if(kam_select==0) kam_select=Math.min(client_select+department_select+1,3);
    if(!$('#kam').val()){
        if(kam_select==2){
            // alert(2);
            if(client_select==3)client_select=2;
            if(department_select==3)department_select=2;
        }
        if(kam_select==1){
            department_select=Math.max(0,department_select-1);
            client_select=Math.max(0,client_select-1);
            // alert(1);
        }
        kam_select=0;

    }
    // alert("kam");
        selector_update();

}
function department_onchange(){
    if(department_select==0) department_select=Math.min(client_select+kam_select+1,3);
    if(!$('#department').val()){
        if(department_select==2){
            if(client_select==3)client_select=2;
            if(kam_select==3)kam_select=2;
        }
        if(department_select==1){
            kam_select=Math.max(0,kam_select-1);
            client_select=Math.max(0,client_select-1);
        }
        department_select=0;
    }
    // alert("department");

    selector_update();
    }
function client_onchange(){
    if(!client_select) client_select=Math.min(kam_select+department_select+1,3);
    // console.log(client_select);
    if(!$(document).find('#client').val()){
        console.log(client_select);
        if(client_select==2){
            if(department_select==3)department_select=2;
            if(kam_select==3)kam_select=2;
        }
        if(client_select==1){
            kam_select=Math.max(0,kam_select-1);
            department_select=Math.max(0,department_select-1);
        }
        client_select=0;
    }

    selector_update();

}


function vr_summary_report(){
   var department="";
         var KAM="";
         var client="";
         var operator="";
         var header="";

         if($('#department').val()!=null)department=$('#department').val()+'';
         if($('#kam').val()!=null) KAM=$('#kam').val()+'';
         if($('#client').val()!=null) client =$('#client').val()+'';
         if($('#operator').val()!=null) operator=$('#operator').val()+'';
         $('#myModal').modal('toggle');
        document.getElementById("tableData").innerHTML="<table id='dataTable' class='display' style='width:100%'><thead><tr><td></td><td>Client</td><td>KAM</td><td>Department</td><td>Operator</td><td>Amount</td></tr></thead><tfoot><tr><td></td><td>Client</td><td>KAM</td><td>Department</td><td>Operator</td><td>Amount</td></tr></tfoot></table>";
    $.ajax({
        url: 'filter_summary_report',
        type: 'GET',
        dataType: "json",
        data: {
            departments : department,
            clients : client,
            kams : KAM,
            fromdate :startDate,
            todate:endDate,
            operators:operator
        },
        success:function(data){
            console.log(data);
            // if(data.length==0)alert("There is no data for this filter");
            // else {
            //     $('#dataTable').DataTable( {
            //         "data": data,
            //         "columns": [
            //             { 'data':function(){
            //                 return '<a onclick="expand_table(this)">+<a/>';
            //             } },
            //             { 'data':"client" },
            //             { 'data':"KAM" },
            //             { 'data':"department" },
            //             { 'data':"operator" },
            //             { 'data':"amount" ,render: $.fn.dataTable.render.number( ',', '.', 0, '' )}
            //         ],
            //         dom: 'Bfrtip',
            //         aLengthMenu: [
            //             [20, 50, 100, -1],
            //             [ '20 rows', '50 rows', '100 rows', 'Show all' ]
            //         ],
            //         iDisplayLength: 20,
            //         buttons: [
            //                 'pageLength','copy',{
            //                                     extend: 'csv',
            //                                     messageTop: header,
            //                                     footer:true
            //                                     },{
            //                                     extend: 'excel',
            //                                     messageTop: header,
            //                                     footer:true
            //                                     }, {
            //                                     extend: 'print',
            //                                     messageTop: header,
            //                                     footer:true
            //                                     }
            //             ],
            //         "footerCallback": function ( row, data, start, end, display ) {
            //             var api = this.api(), data;
            //             var total = api
            //                 .column( 5 )
            //                 .data()
            //                 .reduce( function (a, b) {
            //                     return parseFloat(a) + parseFloat(b);
            //                 }, 0 );
            //             // Update footer by showing the total with the reference of the column index
            //             $( api.column( 0 ).footer() ).html('Total');
            //             $( api.column( 1 ).footer() ).html("-");
            //             $( api.column( 2 ).footer() ).html("-");
            //             $( api.column( 3 ).footer() ).html("-");
            //             $( api.column( 4 ).footer() ).html("-");
            //             $( api.column( 5 ).footer() ).html(numberWithCommas(Math.round(total)));
            //         }
            //     // {
            //     //     extend: 'excel',
            //     //         messageTop: 'The information in this table is copyright to Sirius Cybernetics Corp.'
            //     // },
            //     });
            // }
         $('#myModal').modal('toggle');
         $('#tableDataBox').show();
        // },
        //  error: function (){
        //      alert('error table');
        //     $('#myModal').modal('toggle');
        //     $('#tableDataBox').show();
         }

    });           

}
   function expand_table(e) {
        var row = e.parentNode.parentNode;
        var rowIndex = row.rowIndex - 1;
        // $(row).find('td').each(function(data){

        // console.log($(row).find('td:eq('+data+')').text());
        // });
       
        if(!$(row).hasClass("opened")){
            // $(row).after(format ('a'));
            $(row).addClass("opened");
        }
        else {
            $(row).addClass("closed");
            $(row).removeClass("opened");
            $("#dataTable").find('tr:eq('+rowIndex+1+')').remove();
        }

    }
    // Add event listener for opening and closing details

</script>