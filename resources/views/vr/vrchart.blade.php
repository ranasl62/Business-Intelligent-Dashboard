@extends('layouts.app')
@permission('VR-Chart')
@section('content')
<div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                VR Transaction
            </h1>
        </section>

    <!-- Main content -->
    <section class="content">

        <!-- row start -->
        <div class="row">
            <div class="col-md-1">
                <div class="row-fluid">
                    {!!Form::select('year_options', $data['year_options'], null, ['id' => 'year_options','title'=>"Year Selector",'data-width'=>"90px",'data-live-search'=>"true",'multiple'=>'multiple','class' => 'form-control selectpicker'])!!}
                </div>
            </div>
            <div class="col-md-1">
                <div class="row-fluid">
                    <select id="month_options" title="Month Selector" class="selectpicker" data-width="90px" data-live-search="true" multiple>

                    </select>
                </div>
            </div>
            <div class="col-md-1">
                <div class="row-fluid">
                    <select id="week_options" title="Day Selector" class="selectpicker" data-width="90px"  data-live-search="true" multiple>
                    </select>
                </div>
            </div>
            <div class="col-md-1">
                <div class="row-fluid">
                    {!!Form::select('department_options', $data['department_options'], null, ['id' => 'department_options','title'=>"Department Selector",'data-width'=>"90px",'data-live-search'=>"true",'class' => 'form-control selectpicker'])!!}
                </div>
            </div>
            <div class="col-md-1">
                <button id="filterMain" type="button" class="btn btn-info btn-block">Filter</button>
            </div>
            <div class="col-md-1">
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
                            <button class="btn btn-box-tool" class="modalBtn" id="dynamicChartBtn" ><i class="fa fa-clone"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div>
                        </div>
                        <div class="chart">

                            <div class="loading" id="all" style="height:200px">
                                <div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>
                            </div>
                        </div>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->

            </div><!-- /.col (LEFT) -->
            <div class="col-md-6">
                <!-- Line CHART -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h4 class="box-title" id="countChartTitle">VR Number of Transactions</h4>
                        <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            <button class="btn btn-box-tool" class="modalBtn" id="countChartBtn" ><i class="fa fa-clone"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div>
                        </div>
                        <div class="chart">
                            <div id="vrNumberOfTransactionChart" style="height:200px">
                                <div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>
                            </div>
                        </div>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div><!-- /.col (LEFT) -->
        </div><!-- /.row -->


        <!-- row start -->
        <div class="row">
            <div class="col-lg-4 col-xs-6">
                <!-- Pie CHART -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h4 class="box-title" id="easyChartTitle">Easy Client (Current Month)</h4>
                        <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            <button class="btn btn-box-tool" class="modalBtn" id="easyChartBtn" ><i class="fa fa-clone"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div>
                        </div>
                        <div class="chart">
                            <div id="vrEasyNoneasyChart" style="height:200px">
                                <div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>
                            </div>
                        </div>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div><!-- /.col (LEFT) -->

            <div class="col-lg-4 col-xs-6">
                <!-- LINE CHART -->
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h4 class="box-title" id="operatorChartTitle">Operator Wise (Current Month)</h4>
                        <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            <button class="btn btn-box-tool" class="modalBtn" id="operatorChartBtn" ><i class="fa fa-clone"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="chart">
                            <div id="operator" style="height:200px">
                                <div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>
                            </div>
                        </div>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div><!-- /.col (RIGHT) -->

            <div class="col-lg-4 col-xs-6">
                <!-- LINE CHART -->
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h4 class="box-title" id="departmentChartTitle">Department Wise (Current Month)</h4>
                        <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            <button class="btn btn-box-tool" class="modalBtn" id="departmentChartBtn" ><i class="fa fa-clone"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="chart">
                            <div id="department" style="height:200px">
                                <div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>
                            </div>
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
                            <button class="btn btn-box-tool" class="modalBtn" id="impactChartBtn" ><i class="fa fa-clone"></i></button>
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
                <!-- Pie CHART -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h4 class="box-title" id="toptenclientChartTitle">Top Ten Client (Current Month)</h4>
                        <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            <button class="btn btn-box-tool" class="modalBtn" id="toptenclientChartBtn" ><i class="fa fa-clone"></i></button>
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

            <div class="col-md-6" id="hide">
                <!-- LINE CHART -->
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h4 class="box-title" id="toptenkamChartTitle">Top Ten KAM (Current Month)</h4>
                        <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            <button class="btn btn-box-tool" class="modalBtn" id="toptenkamChartBtn" ><i class="fa fa-clone"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="chart">
                            <div id="vrTopTenKamChart" style="height:200px">
                                <div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>
                            </div>
                        </div>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div><!-- /.col (RIGHT) -->
        </div><!-- /.row -->

        <!-- row start -->
        <div class="row">


        </div><!-- /.row -->




    </section><!-- /.content -->
    </div><!-- /.content-wrapper -->
    @include('vr.script.vrchartjs')
@endsection
@endpermission