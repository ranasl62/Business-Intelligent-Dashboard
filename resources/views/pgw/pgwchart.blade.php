@extends('layouts.app')
@permission('PGW-Chart')
@section('content')
<div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                PGW Transaction
            </h1>
        </section>

    <!-- Main content -->
    <section class="content">

        <!-- row start -->
        <div class="row">
            <div class="col-md-1 col-sm-1 col-lg-1 col-xs-1">
                <div class="row-fluid">
                    {!!Form::select('year_options', $data['year_options'], null, ['id' => 'year_options','title'=>"Year Selector",'data-width'=>"90px",'data-live-search'=>"true",'multiple'=>'multiple','class' => 'form-control selectpicker'])!!}
                </div>
            </div>
            <div class="col-md-1 col-sm-1 col-lg-1 col-xs-1">
                <div class="row-fluid">
                    <select id="month_options" title="Month Selector" class="selectpicker" data-width="90px" data-live-search="true" multiple>
                    </select>
                </div>
            </div>
            <div class="col-md-1 col-sm-1 col-lg-1 col-xs-1">
                <div class="row-fluid">
                    <select id="week_options" title="Day Selector" class="selectpicker" data-width="90px"  data-live-search="true" multiple>
                    </select>
                </div>
            </div>

            <div class="col-md-2 col-sm-2 col-lg-2 col-xs-2">
                <button id="filterMain" type="button" class="btn btn-info btn-block">Filter</button>
            </div>
            <div class="col-md-1 col-sm-1 col-lg-1 col-xs-1">
                <button id="resetMain" type="button" class="btn btn-danger btn-block">Reset</button>
            </div>
        </div> <!-- /.row -->

        <!-- row start -->
        <div class="row">
            <div class="col-md-6">
                <!-- AREA CHART -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h4 class="box-title" id="dynamicChartTitle"></h4>
                        <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            <button class="btn btn-box-tool" id="dynamicChartBtn" ><i class="fa fa-clone"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div>
                        </div>
                        <div class="chart">

                            <div class="loading" id="all" style="height:200px">
                                <div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>
                            </div>
                            <!-- <div id="vrHourWiseChart" style="height:200px"><b></b></div> -->

                        </div>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->

            </div><!-- /.col (LEFT) -->
            <div class="col-md-6">
                <!-- Line CHART -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h4 class="box-title" id="countChartTitle">PGW Number of Transactions</h4>
                        <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            <button class="btn btn-box-tool" id="countChartBtn" ><i class="fa fa-clone"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <!-- <div id="buttonstatus" class="col-md-4">Drill Down Mood On</div> -->
                        <div><!-- <input type="button" class="btn btn-primary" id="button" value="Press for Drill Up" onclick="functionUpDown()"> --><!--  <input type="button" id="button" class="btn btn-primary" value="Show Table"> -->
                        </div>
                        <div class="chart">
                            <div id="pgwNumberOfTransactionChart" style="height:200px">
                                <div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>
                            </div>
                            <!-- <canvas id="areaChart" style="height:250px"></canvas> -->
                        </div>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div><!-- /.col (LEFT) -->

        </div><!-- /.row -->



        <!-- row start -->
        <div class="row">
            {{--<div style="visibility: hidden" class="col-lg-4 col-xs-6">--}}
                {{--<div class="box box-primary">--}}
                    {{--<div class="box-header with-border">--}}
                        {{--<h4 class="box-title">Easy Client (Current Month)</h4>--}}
                        {{--<div class="box-tools pull-right">--}}
                            {{--<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>--}}
                            {{--<button class="btn btn-box-tool" id="easyChartBtn" ><i class="fa fa-clone"></i></button>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="box-body">--}}
                        {{--<div>--}}
                        {{--</div>--}}
                        {{--<div class="chart">--}}
                            {{--<div id="vrEasyNoneasyChart" style="height:200px"><b></b></div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}

            <div class="col-lg-6 col-xs-6">
                <!-- LINE CHART -->
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h4 class="box-title" id="bankChartTitle">Bank Wise Revenue (Current Month)</h4>
                        <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            <button class="btn btn-box-tool" id="bankChartBtn" ><i class="fa fa-clone"></i></button>
                            <!-- <button class="btn btn-box-tool" data-widget="expand"><i class="fa fa-plus"></i></button> -->
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="chart">
                            <div id="operator" style="height:200px">
                                <div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>
                            </div>
                            <!-- <canvas id="lineChart" style="height:250px"></canvas> -->
                        </div>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div><!-- /.col (RIGHT) -->

            <div class="col-lg-6 col-xs-6">
                <!-- LINE CHART -->
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h4 class="box-title" id="cardtypeChartTitle">Card Type Wise (Current Month)</h4>
                        <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            <button class="btn btn-box-tool" id="cardtypeChartBtn" ><i class="fa fa-clone"></i></button>
                            <!-- <button class="btn btn-box-tool" data-widget="expand"><i class="fa fa-plus"></i></button> -->
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="chart">
                            <div id="pgwCardTypeChart" style="height:200px">
                                <div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>
                            </div>
                            <!-- <canvas id="lineChart" style="height:250px"></canvas> -->
                        </div>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div><!-- /.col (RIGHT) -->
        </div><!-- /.row -->

        <!-- row start -->
        <div class="row" id="tableDataBox">
            <div class="col-md-12">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h4 class="box-title">Source Data Table</h4>
                        <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            {{--<button class="btn btn-box-tool" id="dynamicChartBtn" ><i class="fa fa-clone"></i></button>--}}
                            <!-- <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button> -->
                        </div>
                    </div>
                    <div id="tableData" class="box-body">
                        <table id="dataTable" class="table table-bordered table-hover"></table>
                    </div>
                    <div class="box-body" >
                        <button id="tableToExcel" class="btn btn-success" onclick="generateExcel('tableData')">Export as Excel</button>
                    </div>
                </div>
            </div>
        </div> <!-- row close -->

        <!-- row start -->
        <div class="row">
            <div class="col-md-12">
                <!-- LINE CHART -->
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h4 class="box-title" id="impactChartTitle">Analyze Revenue</h4>
                        <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            <button class="btn btn-box-tool" id="impactChartBtn" ><i class="fa fa-clone"></i></button>
                            <!-- <button class="btn btn-box-tool" id="cloneBtn2" href="#myModal2" data-backdrop="false" data-toggle="modal"><i class="fa fa-clone"></i></button> -->
                            <!-- <button class="btn btn-box-tool" data-widget="expand"><i class="fa fa-plus"></i></button> -->
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="chart">
                            <button class="btn btn-default" id="daterange-btn">
                                <i class="fa fa-calendar"></i> Select Two Date
                                <i class="fa fa-caret-down"></i>
                            </button>
                            <select id="impactOperation" title="Impact Operation" class="selectpicker">
                                <option value="day">DAY</option>
                                <option value="month">MONTH</option>
                                <option value="year">YEAR</option>
                            </select>
                            <button  class="btn btn-success" onclick="dataFilter()">VIEW</button>


                            <div id="impactclient" style="height:200px"><b></b></div>

                        </div>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->

            </div>
        </div> <!-- /.row -->


        <!-- row start -->
        <div class="row">
            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h4 class="box-title" id="toptencompanyChartTitle">Top Ten Company (Current Month) </h4>
                        <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            <button class="btn btn-box-tool" id="toptenclientChartBtn" ><i class="fa fa-clone"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div>
                        </div>
                        <div class="chart">
                            <div id="toptenclient" style="height:200px">
                                <div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>
                            </div>
                        </div>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div><!-- /.col (LEFT) -->
            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h4 class="box-title" id="assumptionChartTitle">Assumption Amount (Current Month's Rest of Days) </h4>
                        <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            <button class="btn btn-box-tool" id="assumptionChartBtn" ><i class="fa fa-clone"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div>
                        </div>`
                        <div class="chart">
                            <div id="assumption" style="height:200px">
                                <div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>
                            </div>
                        </div>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div><!-- /.col (LEFT) -->


        </div><!-- /.row -->

        <!-- row start -->
        <div class="row">

        </div><!-- /.row -->

    </section><!-- /.content -->
    </div><!-- /.content-wrapper -->
    @include('pgw.script.pgwchartjs')
   @endsection
@endpermission