<script>
    var card_select=0;
    var stakeholder_select=0;
    var  bank_select=0;
$(document).ready(function() {
    $("#loading-div-background").css({opacity: 0.8});
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
            startDate = start.format('YYYY-MM-DD');
            endDate = end.format('YYYY-MM-DD');
            $('#daterange-btn').html(start.format('YYYY/MM/DD') + '-' + end.format('YYYY/MM/DD'));
        }
    );
    // ....................................Select >> Option data load..................................
//     $.ajax({
//         url: 'init',
//         type: 'GET',
//         dataType: "json",
//         success:function(data){
//             console.log(data);
//            for (var i = 0; i < data['card'].length; i++) {
//                 $('#card').append('<option value=' +'\''+ data['card'][i]['Card'] +'\''+ '>' + data['card'][i]['Card']  + '</option>');
//             }
//             for (var i = 0; i < data['stakeholder'].length; i++) {
//                 $('#stakeholder').append('<option value=' + '\''+ data['stakeholder'][i]['strid'] + '\''+ '>' + data['stakeholder'][i]['strid'] + '</option>');
//             }
//             for (var i = 0; i < data['bank'].length; i++) {
//                 $('#bank').append('<option value=' + '\''+data['bank'][i]['bname']+'\'' + '>' + data['bank'][i]['bname'] + '</option>');
//             }
//         },
//          error: function (){alert('error');}
//     });
});
// ....................................Select >> Option data load..................................
    function pgwreport() {

        var card = "";
        var bank = "";
        var stakeholder = "";
        var header = "";

        if ($('#stakeholder').val() != null) stakeholder = $('#stakeholder').val() + '';
        if ($('#bank').val() != null) bank = $('#bank').val() + '';
        if ($('#card').val() != null) card = $('#card').val() + '';
        $('#myModal').modal('toggle');
        document.getElementById("tableData").innerHTML = "<table id='dataTable' class='display nowrap' style='width:100%;'> <thead> <tr> <th>Transdate</th> <th>Bank</th> <th>Card</th> <th>Stakeholder Id</th> <th>Mamount</th><th>SSL Portion</th> <th>Bank Portion</th> <th>Store Portion</th> </tr> </thead> <tfoot> <tr> <th>Transdate</th> <th>Bank</th> <th>Card</th> <th>Stakeholder Id</th> <th>Mamount</th><th>SSL Portion</th> <th>Bank Portion</th> <th>Store Portion</th> </tr> </tfoot> </table>";
        $.ajax({
            url: 'filterreport',
            type: 'GET',
            dataType: "json",
            data: {
                stakeholders: stakeholder,
                cards: card,
                banks: bank,
                fromdate: startDate,
                todate: endDate,
            },
            success: function (data) {
                // console.log(data);
                if (data.length == 0) alert("There is no data for this filter");
                else {
                    $('#dataTable').DataTable({
                        "data": data,
                        "columns": [
                            {'data': "transdate"},
                            {'data': "bid"},
                            {'data': "cardtype"},
                            {'data': "strid"},
                            {'data': "mamount", render: $.fn.dataTable.render.number(',', '.', 0, '')},
                            {'data': "sslportion", render: $.fn.dataTable.render.number(',', '.', 0, '')},
                            {'data': "bankportion", render: $.fn.dataTable.render.number(',', '.', 0, '')},
                            {'data': "storeportion", render: $.fn.dataTable.render.number(',', '.', 0, '')}
                        ],
                        dom: 'Bfrtip',
                        aLengthMenu: [
                            [20, 50, 100, -1],
                            ['20 rows', '50 rows', '100 rows', 'Show all']
                        ],
                        iDisplayLength: 20,
                        buttons: [
                            'pageLength', 'copy', {
                                extend: 'csv',
                                messageTop: header,
                                footer: true
                            }, {
                                extend: 'excel',
                                messageTop: header,
                                footer: true
                            }, {
                                extend: 'print',
                                messageTop: header,
                                footer: true
                            }
                        ],
                        "footerCallback": function (row, data, start, end, display) {
                            var api = this.api(), data;
                            var mamount = api
                                .column(4)
                                .data()
                                .reduce(function (a, b) {
                                    return parseFloat(a) + parseFloat(b);
                                }, 0);
                            var sslportion = api
                                .column(5)
                                .data()
                                .reduce(function (a, b) {
                                    return parseFloat(a) + parseFloat(b);
                                }, 0);
                            var bankportion = api
                                .column(6)
                                .data()
                                .reduce(function (a, b) {
                                    return parseFloat(a) + parseFloat(b);
                                }, 0);
                            var storeportion = api
                                .column(7)
                                .data()
                                .reduce(function (a, b) {
                                    return parseFloat(a) + parseFloat(b);
                                }, 0);
                            // Update footer by showing the total with the reference of the column index
                            $(api.column(0).footer()).html('Total');
                            $(api.column(1).footer()).html("-------");
                            $(api.column(2).footer()).html("-------");
                            $(api.column(3).footer()).html("-------");
                            $(api.column(4).footer()).html(numberWithCommas(Math.round(mamount)));
                            $(api.column(5).footer()).html(numberWithCommas(Math.round(sslportion)));
                            $(api.column(6).footer()).html(numberWithCommas(Math.round(bankportion)));
                            $(api.column(7).footer()).html(numberWithCommas(Math.round(mamount)));
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
            error: function () {
                alert('error table');
                $('#myModal').modal('toggle');
                $('#tableDataBox').show();
            }

        });
    }


    function selector_update() {
        console.log(card_select + " " + stakeholder_select + " " + bank_select + "\n");
        console.log($("#bank").val() + " " + $("#stakeholder").val() + " " + $("#card").val() + "\n");
        $.ajax({
            url: 'init',
            type: 'GET',
            data: {
                card: card_select,
                stakeholder: stakeholder_select,
                bank: bank_select,
                bank_val: $("#bank").val(),
                stakeholder_val: $("#stakeholder").val(),
                card_val: $("#card").val(),
            },
            dataType: "json",
            success: function (data) {
                console.log(data);
                if (data['card'].length > 0) {
                    // $('#card').selectpicker('deselectAll');
                    document.getElementById('card_updater').innerHTML = '';
                    $(document).find("#card_updater").html('<select id="card" onchange="card_onchange()" title="card" class="selectpicker form-control" data-live-search="true" multiple></select>');
                    for (var i = 0; i < data['card'].length; i++) {
                        $('#card').append('<option value=' + data['card'][i]['card_id'] + '>' + data['card'][i]['card_name'] + '</option>');
                    }

                    $('#card').selectpicker('render');
                }
                if (data['stakeholder'].length > 0) {
                    // $('#stakeholder').selectpicker('deselectAll');
                    document.getElementById('stakeholder_updater').innerHTML = '';
                    $(document).find("#stakeholder_updater").html('<select id="stakeholder" onchange="stakeholder_onchange()" title="stakeholder" class="selectpicker form-control" data-live-search="true" multiple></select>');
                    // $(document).find("#stakeholder_updater").html('<select id="stakeholder" title="stakeholder" class="selectpicker form-control" data-live-search="true" multiple></select>');
                    for (var i = 0; i < data['stakeholder'].length; i++) {
                        $('#stakeholder').append('<option value=' + '\'' + data['stakeholder'][i]['stakeholder'] + '\'' + '>' + data['stakeholder'][i]['stakeholder'] + '</option>');
                    }

                    $('#stakeholder').selectpicker('render');
                }
                if (data['bank'].length > 0) {
                    //$('#bank').selectpicker('deselectAll');
                    document.getElementById('bank_updater').innerHTML = '';
                    $(document).find("#bank_updater").html('<select id="bank" onchange="bank_onchange()" title="bank" class="selectpicker form-control" data-live-search="true" multiple></select>');
                    for (var i = 0; i < data['bank'].length; i++) {
                        $('#bank').append('<option value=' + '\'' + data['bank'][i]['bank'] + '\'' + '>' + data['bank'][i]['bank'] + '</option>');
                    }
                    $('#bank').selectpicker('render');
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


    function bank_onchange() {
        // rana();
        if (bank_select == 0) bank_select = Math.min(card_select + stakeholder_select + 1, 3);
        if (!$('#bank').val()) {
            if (bank_select == 2) {
                // alert(2);
                if (card_select == 3) card_select = 2;
                if (stakeholder_select == 3) stakeholder_select = 2;
            }
            if (bank_select == 1) {
                stakeholder_select = Math.max(0, stakeholder_select - 1);
                card_select = Math.max(0, card_select - 1);
                // alert(1);
            }
            bank_select = 0;

        }
        // alert("bank");
       // selector_update();

    }


    function stakeholder_onchange() {
        if (stakeholder_select == 0) stakeholder_select = Math.min(card_select + bank_select + 1, 3);
        if (!$('#stakeholder').val()) {
            if (stakeholder_select == 2) {
                if (card_select == 3) card_select = 2;
                if (bank_select == 3) bank_select = 2;
            }
            if (stakeholder_select == 1) {
                bank_select = Math.max(0, bank_select - 1);
                card_select = Math.max(0, card_select - 1);
            }
            stakeholder_select = 0;
        }
        // alert("stakeholder");

       // selector_update();
    }


    function card_onchange() {
        if (!card_select) card_select = Math.min(bank_select + stakeholder_select + 1, 3);
        // console.log(card_select);
        if (!$(document).find('#card').val()) {
            console.log(card_select);
            if (card_select == 2) {
                if (stakeholder_select == 3) stakeholder_select = 2;
                if (bank_select == 3) bank_select = 2;
            }
            if (card_select == 1) {
                bank_select = Math.max(0, bank_select - 1);
                stakeholder_select = Math.max(0, stakeholder_select - 1);
            }
            card_select = 0;
        }

        //selector_update();

    }

</script>