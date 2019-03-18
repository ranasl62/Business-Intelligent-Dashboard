@extends('layouts.app')
@permission('PGW-Report')
@section('content')
<div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                PGW Report
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
                      <div class="col-md-2 form-group">
                          <button class="btn btn-default form-control" id="daterange-btn">
                            <i class="fa fa-calendar"></i> Select Date Range
                            <i class="fa fa-caret-down"></i>
                          </button>  
                      </div>  
                      <div class="col-md-3 form-group">
                          <select id="stakeholder" title="Stakeholder" onchange="stakeholder_onchange()" class="selectpicker form-control" data-live-search="true" multiple>
                                @forelse ($data['stakeholder'] as $item)
                                <option value="{{$item['strid']}}">{{$item['strid']}}</option>
                            @empty
                                  <option>NoData</option>
                            @endforelse   
                        </select>  
                      </div>
                      <div class="col-md-3 form-group">
                          <select id="bank" title="Bank" onchange="bank_onchange()" class="selectpicker form-control" data-live-search="true" multiple>
                                @forelse ($data['bank'] as $item)
                                <option value="{{$item['bname']}}">{{$item['bname']}}</option>
                            @empty
                                  <option>NoData</option>
                            @endforelse   
                          </select>
                      </div>
                      <div class="col-md-3 form-group">
                          <select id="card" title="Card" onchange="card_onchange()" class="selectpicker form-control" data-live-search="true" multiple>
                            @forelse ($data['card'] as $item)
                                <option value="{{$item['Card']}}">{{$item['Card']}}</option>
                            @empty
                                  <option>NoData</option>
                            @endforelse        
                        </select>  
                      </div>
                      <div class="col-md-1 form-group">
                          <button  class="btn btn-success form-control" onclick="pgwreport()">Search</button>
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
                            <h3 class="box-title">Source Data Table</h3>
                            <div class="box-tools pull-right">
                                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                <!-- <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button> -->
                            </div>
                        </div>
                        <div id="tableData" class="box-body table-responsive" style="width:100%; margin:0%; padding:1%"></div>
                    </div>
                </div>
            </div> <!-- row close -->

        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->
    @include('pgw.script.pgwreportjs')
@endsection
@endpermission