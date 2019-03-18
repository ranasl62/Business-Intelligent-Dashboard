@extends('layouts.app')
@section('content')
@permission('VR-Report')
<div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                VR Report
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
                      <div class="col-md-2 form-group" id="client_updater">
                          <select id="client" onchange="client_onchange()" title="Client" class="selectpicker form-control" data-live-search="true" multiple>
                                @forelse ($data['client'] as $item)
                                    <option value={{$item['client_id']}}>{{$item['client_name']}}</option>
                                @empty
                                    <option>NoData</option>
                                @endforelse
                            </select>  
                      </div>
                      <div class="col-md-2 form-group" id="department_updater">
                          <select id="department" onchange="department_onchange()" title="Department" class="selectpicker form-control" data-live-search="true" multiple>
                            @forelse ($data['department'] as $item)
                                <option value="{{$item['Department']}}">{{$item['Department']}}</option>
                            @empty
                                  <option>NoData</option>
                            @endforelse
                          </select>
                      </div>
                      <div class="col-md-2 form-group" id="kam_updater">
                          <select id="kam" onchange="kam_onchange()" title="KAM" class="selectpicker form-control" data-live-search="true" multiple>
                            @forelse ($data['kam'] as $item)
                                <option value="{{$item['KAM']}}">{{$item['KAM']}}</option>
                            @empty
                                  <option>NoData</option>
                            @endforelse</select>  
                      </div>
                      <div class="col-md-2 form-group">
                          <select id="operator" title="Operator" class="selectpicker form-control" data-live-search="true" multiple>
                                @forelse ($data['operator'] as $item)
                                <option value={{$item['operator_id']}}>{{$item['operator_name']}}</option>
                            @empty
                                  <option>NoData</option>
                            @endforelse
                          </select>
                      </div>
                      <div class="col-md-1 form-group">
                          <button  class="btn btn-danger form-control" onclick="vr_summary_report()">Summary</button>
                      </div>
                      <div class="col-md-1 form-group">
                          <button  class="btn btn-success form-control" onclick="vrreport()">Details</button>
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
                        <div id="tableData" class="box-body table-responsive" style="width: 100%; margin: 0; padding: 1%;">

                        </div>
                    </div>
                </div>
            </div> <!-- row close -->

        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->
    @include('vr.script.vrreportjs')
    @endpermission
@endsection
