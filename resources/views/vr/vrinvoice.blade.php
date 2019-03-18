@extends('layouts.app')
@section('content')
<div id="modalForPayment" class="modal payment" style="width:100%; margin:0%; padding:0%">
        <div class="modal-dialog animated">
            <div class="modal-content" style="width:100%">
                <form class="form-horizontal" style="width:100%">
                    <div class="modal-header" style="background-color:#218973">
                        <strong>Payment Details</strong>
                    </div>

                    <div class="modal-body" style="background-color:#e5eaea; width:100%;padding:10%">

                                    <form class="form">
                                        <div class="form-group">
                                        <label for="addInvoiceAmount">Invoice Amount</label>
                                        <input type="text" id="addInvoiceAmount" class="form-control" disabled="">
                                        </div>
                                        <div class="form-group">
                                        <label for="addPaymentDate">Payment Date</label>
                                        <input type="date" id="addPaymentDate" class="form-control" value="2018-10-15" required="true">
                                        </div>
                                        <div class="form-group">
                                        <label for="addInvoiceId">Invoice ID</label>
                                        <input type="text" id="addInvoiceId" class="form-control" placeholder="Example SSL/18/071089" required="true">
                                        </div>
                                        <div class="form-group">
                                        <label for="addPaymentAmount">BDT</label>
                                        <input type="number" id="addPaymentAmount" class="form-control" placeholder="Example 82476539465" required="true">
                                        </div>
                                    </form>

                    </div>

                    <div class="modal-footer" style="background-color:#218973">
                        <button class="btn btn-default" onclick="cancelPayment()">Cancel</button>
                        <button class="btn btn-info" onclick="actionForPayment()">Payment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="confirm" class="modal confirm" style="width:100%; margin:0%; padding:0%">
            <div class="modal-dialog animated">
                <div class="modal-content" style="width:100%">
                    <form class="form-horizontal" method="get">
                        <div class="modal-header" style="background-color:#f28730">
                            <strong>Confirm Payment Details</strong>
                        </div>
    
                        <div  class="modal-body" style="background-color:#e5eaea; width:100%;padding:10%">
                            

                                    <form class="form">
                                        <div class="form-group">
                                        <label for="addInvoiceAmount1">Invoice Amount</label>
                                        <input type="text" id="addInvoiceAmount1" class="form-control" disabled>
                                        </div>
                                        <div class="form-group">
                                        <label for="addPaymentDate1">Payment Date</label>
                                        <input type="date" id="addPaymentDate1" class="form-control" value="2018-10-15" required="true" disabled>
                                        </div>
                                        <div class="form-group">
                                        <label for="addInvoiceId1">Invoice ID</label>
                                        <input type="text" id="addInvoiceId1" class="form-control" placeholder="Example SSL/18/071089" required="true" disabled>
                                        </div>
                                        <div class="form-group">
                                        <label for="addPaymentAmount1">BDT</label>
                                        <input type="number" id="addPaymentAmount1" class="form-control" placeholder="Example 82476539465" required="true" disabled>
                                        </div>
                                    </form>
                                
                        </div>
    
                        <div class="modal-footer" style="background-color:#f28730">
                            <button class="btn btn-default" onclick="updateAgain()">Update</button>
                            <button class="btn btn-info" onclick="actionForConfirmPayment()">Confirm</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
<div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                VR Invoice
            </h1>
        </section>
            
        <!-- Main content -->
        <section class="content">
          <div class="row">
                <div class="col-md-12">
                  <div class="box box-primary">
                    <div class="box-header">
                      <h4 class="box-title">Filters</h4>
                    </div>
                    <div class="box-body form-row">
                      <div class="col-md-3 form-group">
                          <select id="client" title="Client" class="selectpicker form-control" data-live-search="true">
                            @forelse ($data['client'] as $item)
                                <option value={{$item->client_id}}>{{$item->client_name}}</option>
                            @empty
                                  <option>NoData</option>
                            @endforelse 
                          </select>  
                      </div>
                      <div class="col-md-3 form-group">

                          <select id="month" title="Month" class="selectpicker form-control" data-live-search="true"></select>
                      </div>
                      <div class="col-md-3 form-group">
                          <select id="year" title="Year" class="selectpicker form-control" data-live-search="true">
                            @forelse ($data['year'] as $item)
                                <option value="{{$item['year']}}">{{$item['year']}}</option>
                            @empty
                                  <option>NoData</option>
                            @endforelse      
                        </select>  
                      </div>
                      <div class="col-md-3 dropdown btn-group-vertical">
                        <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown"style="width:90%;">
                          Select Invoice Type<span class="caret"></span>
                        </button>
                          <ul class="dropdown-menu" style="background-color:rgba(255, 255, 255, 0);width:90%; padding-left:5%; padding-right:3%;">
                            @permission('VR-Invoice')
                            <li><button  class="btn btn-primary" onclick="summaryInvoiceTable()" style="Width:100%;">Summary Invoice</button></li>

                            {{-- <li class="divider" style="Width:100%;"></li> --}}

                           <li><button  class="btn btn-primary" onclick="operatorWiseInvoice()" style="Width:100%;">Operator Wise Invoice</button></li>

                            {{-- <li class="divider" style="Width:100%;"></li>  --}}

                            <li><button  class="btn btn-primary" onclick="easyClientInvoice()" style="Width:100%;">Easy Operator</button></li>

                            {{-- <li class="divider" style="Width:100%;"></li> --}}

                            <li><button  class="btn btn-primary" onclick="easyqubee()" style="Width:100%;">Easy Qubee</button></li>
                            @endpermission
                            @permission('VR-Invoice-Payment')
                            {{-- <li class="divider" style="Width:100%;"></li> --}}
                            <li><button  class="btn btn-primary" onclick="invoice()" style="Width:100%;">Invoice</button></li>

                            {{-- <li class="divider" style="Width:100%;"></li> --}}

                            <li><button  class="btn btn-primary" onclick="paidInfo()" style="Width:100%;">Paid Invoice</button></li>

                            {{-- <li class="divider" style="Width:100%;"></li> --}}
                            
                            <li><button  class="btn btn-primary" onclick="unpaidInfo()" style="Width:100%;">Unpaid Invoice</button></li>
                            @endpermission
                          </ul>
                      </div>
                    </div>
                  </div><!-- /.box -->
                </div>  
          </div> <!-- row close -->

            <!-- row start -->
            <div class="row" id="tableDataBox">
                <div class="col-md-12">
                    <div class="box box-success">
                        <div class="box-header with-border">
                            <!-- <h3 class="box-title">Source Data Table</h3> -->
                            <div class="box-tools pull-right">
                                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                <!-- <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button> -->
                            </div>
                        </div>
                        <div id="tableData" class="box-body table-responsive" style="width: 100%; margin: 0; padding: 1%;">
                        </div>
                    </div>
                </div>
            </div> <!-- row close -->

        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->
    @include('vr.script.vrinvoicejs')
@endsection
