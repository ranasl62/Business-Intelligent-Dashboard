<script>
    var kam_select=0;
    var stakeholder_select=0;
    var company_select=0;
$(document).ready(function() {
    $("#loading-div-background").css({ opacity: 0.8 });
    // ....................................For data Picker.................................
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
    // ....................................Select >> Option data load..................................
    // $.ajax({
    //     url: 'init',
    //     type: 'GET',
    //     dataType: "json",
    //     success:function(data){ 
    //         for (var i = 0; i < data['operator'].length; i++) {
    //             $('#operator').append('<option value=' + '\'' + data['operator'][i]['Operator'] + '\'' +'>' + data['operator'][i]['Operator'] + '</option>');
    //         }
    //        for (var i = 0; i < data['company'].length; i++) {
    //             $('#company').append('<option value=' + '\''+ data['company'][i]['company'] + '\''+ '>' + data['company'][i]['company'] + '</option>');
    //         }
    //         for (var i = 0; i < data['Stakeholder'].length; i++) {
    //             $('#stakeholder').append('<option value=' + '\''+data['Stakeholder'][i]['stakeholder']+'\'' + '>' + data['Stakeholder'][i]['stakeholder'] + '</option>');
    //         }
    //         for (var i = 0; i < data['KAM'].length; i++) {
    //             $('#kam').append('<option value=' + '\''+ data['KAM'][i]['KAM'] + '\''+ '>' + data['KAM'][i]['KAM'] + '</option>');
    //         }
    //     },
    //      error: function (){alert('error');}
    // });
});
// ....................................Select >> Option data load..................................
    function smsreport(){

         var company="";
         var KAM="";
         var stakeholder="";
         var operator="";
         var header="";

         if($('#company').val()!=null)company=$('#company').val()+'';         
         if($('#kam').val()!=null) KAM=$('#kam').val()+'';         
         if($('#stakeholder').val()!=null) stakeholder =$('#stakeholder').val()+'';
         if($('#operator').val()!=null) operator=$('#operator').val()+'';

         $('#myModal').modal('toggle');
        document.getElementById("tableData").innerHTML="<table id='dataTable' class='display nowrap' style='width:100%'> <thead> <tr> <th>Transdate</th> <th>Stakeholder</th> <th>Company</th> <th>KAM</th> <th>Operator</th> <th>Count</th> <th>Amount</th> </tr> </thead> <tfoot> <tr> <th>Transdate</th> <th>Stakeholder</th> <th>Company</th> <th>KAM</th> <th>Operator</th> <th>Count</th> <th>Amount</th> </tr> </tfoot> </table>";
    $.ajax({
        url: 'filterreport',
        type: 'GET',
        dataType: "json",
        data: {
           
            companies : company,         
            stakeholders : stakeholder,         
            kams : KAM,
            fromdate :startDate,         
            todate:endDate,
            operators:operator
        },
        success:function(data){    
            // console.log(data);
            if(data.length==0)alert("There is no data for this filter");
            else {
                $('#dataTable').DataTable( {
                    "data": data,
                    "columns": [
                        { 'data':"transdate" },
                        { 'data':"stakeholder" },
                        { 'data':"company" },
                        { 'data':"KAM" },
                        { 'data':"operator" },
                        { 'data':"cnt" ,render: $.fn.dataTable.render.number( ',', '.', 0, '' )},
                        { 'data':"amount",render: $.fn.dataTable.render.number( ',', '.', 0, '' )}
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
                        var count = api
                            .column( 5 )
                            .data()
                            .reduce( function (a, b) {
                                return parseFloat(a) + parseFloat(b);
                            }, 0 );
                        var amount = api
                            .column( 6 )
                            .data()
                            .reduce( function (a, b) {
                                return parseFloat(a) + parseFloat(b);
                            }, 0 );
                        // Update footer by showing the total with the reference of the column index
                        $( api.column( 0 ).footer() ).html('Total');
                        $( api.column( 1 ).footer() ).html("-------");
                        $( api.column( 2 ).footer() ).html("-------");
                        $( api.column( 3 ).footer() ).html("-------");
                        $( api.column( 4 ).footer() ).html("-------");
                        $( api.column( 5 ).footer() ).html(numberWithCommas(Math.round(count)));
                        $( api.column( 6 ).footer() ).html(numberWithCommas(Math.round(amount)));
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

function selector_update() {
    console.log(kam_select + " " + stakeholder_select + " " + company_select + "\n");
    console.log($("#company").val() + " " + $("#stakeholder").val() + " " + $("#kam").val() + "\n");
    $.ajax({
        url: 'init',
        type: 'GET',
        data: {
            kam: kam_select,
            stakeholder: stakeholder_select,
            company: company_select,
            company_val: $("#company").val(),
            stakeholder_val: $("#stakeholder").val(),
            kam_val: $("#kam").val(),
        },
        dataType: "json",
        success: function (data) {
            console.log(data);
            if (data['KAM'].length > 0) {
                // $$("#kam").selectpicker('deselectAll');
                document.getElementById('kam_updater').innerHTML = '';
                $(document).find("#kam_updater").html('<select id="kam" onchange="kam_onchange()" title="KAM" class="selectpicker form-control" data-live-search="true" multiple></select>');
                for (var i = 0; i < data['KAM'].length; i++) {
                    $('#kam').append('<option value=' + '\''+ data['KAM'][i]['KAM'] + '\''+ '>' + data['KAM'][i]['KAM'] + '</option>');
                }

                $("#kam").selectpicker('render');
            }
            if (data['Stakeholder'].length > 0) {
                // $('#stakeholder').selectpicker('deselectAll');
                document.getElementById('stakeholder_updater').innerHTML = '';
                $(document).find("#stakeholder_updater").html('<select id="stakeholder" onchange="stakeholder_onchange()" title="stakeholder" class="selectpicker form-control" data-live-search="true" multiple></select>');
                for (var i = 0; i < data['Stakeholder'].length; i++) {
                                $('#stakeholder').append('<option value=' + '\''+data['Stakeholder'][i]['stakeholder']+'\'' + '>' + data['Stakeholder'][i]['stakeholder'] + '</option>');
                }

                $('#stakeholder').selectpicker('render');
            }
            if (data['company'].length > 0) {
                //$('#company').selectpicker('deselectAll');
                document.getElementById('company_updater').innerHTML = '';
                $(document).find("#company_updater").html('<select id="company" onchange="company_onchange()" title="Company" class="selectpicker form-control" data-live-search="true" multiple></select>');
                for (var i = 0; i < data['company'].length; i++) {
                        $('#company').append('<option value=' + '\''+ data['company'][i]['company'] + '\''+ '>' + data['company'][i]['company'] + '</option>');
                    }
                $('#company').selectpicker('render');
            }
            // console.log(data);
        },
        error: function () {
            alert('error');
        }
    }).done(function (data) {
        // console.log(data);

    });
}


function company_onchange() {
    // rana();
    if (company_select == 0) company_select = Math.min(kam_select + stakeholder_select + 1, 3);
    if (!$('#company').val()) {
        if (company_select == 2) {
            // alert(2);
            if (kam_select == 3) kam_select = 2;
            if (stakeholder_select == 3) stakeholder_select = 2;
        }
        if (company_select == 1) {
            stakeholder_select = Math.max(0, stakeholder_select - 1);
            kam_select = Math.max(0, kam_select - 1);
            // alert(1);
        }
        company_select = 0;

    }
    // alert("bank");
    selector_update();

}


function stakeholder_onchange() {
    if (stakeholder_select == 0) stakeholder_select = Math.min(kam_select + company_select + 1, 3);
    if (!$('#stakeholder').val()) {
        if (stakeholder_select == 2) {
            if (kam_select == 3) kam_select = 2;
            if (company_select == 3) company_select = 2;
        }
        if (stakeholder_select == 1) {
            company_select = Math.max(0, company_select - 1);
            kam_select = Math.max(0, kam_select - 1);
        }
        stakeholder_select = 0;
    }
    // alert("stakeholder");

    selector_update();
}


function kam_onchange() {
    if (!kam_select) kam_select = Math.min(company_select + stakeholder_select + 1, 3);
    // console.log(kam_select);
    if (!$(document).find("#kam").val()) {
        console.log(kam_select);
        if (kam_select == 2) {
            if (stakeholder_select == 3) stakeholder_select = 2;
            if (company_select == 3) company_select = 2;
        }
        if (kam_select == 1) {
            company_select = Math.max(0, company_select - 1);
            stakeholder_select = Math.max(0, stakeholder_select - 1);
        }
        kam_select = 0;
    }

    selector_update();

}


</script>