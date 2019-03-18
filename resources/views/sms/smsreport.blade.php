@extends('layouts.app')
@section('content')
@permission('SMS-Report')
<div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                SMS Report
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
                      <div class="col-md-2 form-group" id="stakeholder_updater">
                          <select id="stakeholder" title="Stakeholder" onchange="stakeholder_onchange()" class="selectpicker form-control" data-live-search="true" multiple>
                            @forelse ($data['Stakeholder'] as $item)
                                <option value="{{$item['stakeholder']}}">{{$item['stakeholder']}}</option>
                              @empty
                                  <option>NoData</option>
                              @endforelse       
                        </select>  
                      </div>
                      <div class="col-md-2 form-group" id="company_updater">
                          <select id="company" title="Company" onchange="company_onchange()" class="selectpicker form-control" data-live-search="true" multiple>
                            @forelse ($data['company'] as $item)
                                <option value="{{$item['company']}}">{{$item['company']}}</option>
                              @empty
                                  <option>NoData</option>
                              @endforelse   
                          </select>
                      </div>
                      <div class="col-md-2 form-group" id="kam_updater">
                          <select id="kam" title="KAM" onchange="kam_onchange()" class="selectpicker form-control" data-live-search="true" multiple>
                              @forelse ($data['KAM'] as $item)
                                <option value="{{$item['KAM']}}">{{$item['KAM']}}</option>
                              @empty
                                  <option>NoData</option>
                              @endforelse      
                        </select>  
                      </div>
                      <div class="col-md-2 form-group">
                          <select id="operator" title="Operator" class="selectpicker form-control" data-live-search="true" multiple>
                              @forelse ($data['operator'] as $item)
                                <option value="{{$item['Operator']}}">{{$item['Operator']}}</option>
                              @empty
                                  <option>NoData</option>
                              @endforelse
                          </select>
                      </div>
                      <div class="col-md-2 form-group">
                          <button  class="btn btn-success form-control" onclick="smsreport()">Search</button>
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
                        <div id="tableData" class="box-body table-responsive" style="width:100%; margin:0%; padding:1%"></div>
                    </div>
                </div>
            </div> <!-- row close -->

        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->
    @include('sms.script.smsreportjs')
    @endpermission
@endsection