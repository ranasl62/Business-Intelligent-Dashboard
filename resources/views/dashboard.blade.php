@extends('layouts.app')
<?php $a=1;?>
@permission('DashBoard-Permission')
@section('content')
<?php $a=2;?>
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            {{--<h1>{{$data['title']}}</h1>--}}
        </section>

        <!-- Main content -->
        <section class="content">
            <!-- Small boxes (Stat box) -->
            <div class="row">
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div>
                        <div class="info-box">
                            <div class="col-md-4 col-sm-4 col-xs-4 bg-aqua infoboxtextsize" >
                                <div id="vr_infobox_title" style="font-size: 70%">Amount</div>
                                <b id="vr_amount_count"> </b> <sup id="vr_sup" style="font-size: 50%;">M</sup>
                            </div>
                            <div class="col-md-8 col-sm-8 col-xs-8">
                                <span style="font-size: 100%; font-family: arial; font-weight: bold;">Virtual Recharge</span><br/>
                                <span id="vrDateShow"></span>
                                <table>
                                    <td>
                                        <div class="icon" style="text-align: center">
                                            <figure >
                                                <figcaption id="vrUpCaption"></figcaption>
                                                <img id="vrUpDownShow" src="" height="25"/>
                                                <figcaption id="vrDownCaption"></figcaption>
                                            </figure>
                                        </div>
                                    </td>
                                    <td>
                        <span  style="font-size:80%; padding-left: 20px; font-family:verdana; color: #00c0ef;">
                          <a href="#" id="vr_amount" class="online" style="color: #00c0ef">AMT</a>
                          <b>|</b>
                          <a href="#" id="vr_count" style="color: #00c0ef">CNT</a>
                        </span>
                                    </td>

                                </table>
                            </div>
                            <ul id="vr_day_list"></ul>
                        </div>

                    </div>

                </div><!-- /.col -->

                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <div class="col-md-4 col-sm-4 col-xs-4 bg-green infoboxtextsize" >
                            <div id="sms_infobox_title" style="font-size: 70%">Amount</div>
                            <b id="sms_amount_count"> </b> <sup id="sms_sup" style="font-size: 50%;">M</sup>
                        </div>
                        <div class="col-md-8 col-sm-8 col-xs-8">
                            <span style="font-size: 100%; font-family: arial; font-weight: bold;">iSMS</span><br/>
                            <span id="smsDateShow"> </span>
                            <table>
                                <td>
                                    <div class="icon"  style="text-align: center">
                                        <figcaption id="smsUpCaption"></figcaption>
                                        <img id="smsUpDownShow" src="" height="25" />
                                        <figcaption id="smsDownCaption"></figcaption>
                                    </div>
                                </td>
                                <td>
                      <span  style="font-size:80%; padding-left: 20px; font-family:verdana; color: #00a65a ">
                        <a href="#" id="sms_amount" class="online" style="color: #00a65a">AMT</a>
                        <b>|</b>
                        <a href="#" id="sms_count" style="color: #00a65a">CNT</a>
                      </span>
                                </td>
                            </table>
                        </div>
                        <ul id="sms_day_list"></ul>
                    </div>
                </div><!-- /.col -->

                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <div class="col-md-4 col-sm-4 col-xs-4 bg-yellow infoboxtextsize" >
                            <div id="pgw_infobox_title" style="font-size: 70%">Amount</div>
                            <b id="pgw_amount_count" > </b> <sup id="pgw_sup" style="font-size: 50%;">M</sup>
                        </div>
                        <div class="col-md-8 col-sm-8 col-xs-8">
                            <span style="font-size: 100%; font-family: arial; font-weight: bold;">SSLCOMMERZ</span><br/>
                            <span id="pgwDateShow"> </span> <br/>
                            <span id="pgwDateShow"> </span>
                            <table>
                                <td>
                                    <div class="icon"  style="text-align: center">
                                        <figcaption id="pgwUpCaption"></figcaption>
                                        <img id="pgwUpDownShow" src="" height="25" />
                                        <figcaption id="pgwDownCaption"></figcaption>
                                    </div>
                                </td>
                                <td>
                                    <span  style="padding-left: 20px; font-family:verdana; font-size:80%; color: #f39c12">
                                        <a href="#" id="pgw_amount" class="online" style="color: #f39c12">AMT</a>
                                        <b>|</b>
                                        <a href="#" id="pgw_count" style="color: #f39c12">CNT</a>
                                    </span>
                                </td>
                            </table>
                        </div>
                        <ul id="pgw_day_list"></ul>
                    </div>
                </div><!-- /.col -->
            </div>


            <div class="row" style="padding-bottom: 10px">
                <div class="col-md-4 col-sm-6 col-xs-12" >
                    <!-- <div class="col-md-4 col-sm-6 col-xs-12" style="padding-bottom: 20px"> -->
                    <!-- <div class="box box box-solid"> -->
                    <div id="vrHourChart" class="chart" style="height:70px;"></div>
                    <!-- </div> -->
                </div>
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <!-- <div class="box box box-solid"> -->
                    <div id="smsWeekChart" class="chart" style="height:70px"></div>
                    <!-- </div> -->
                </div>
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <!-- <div class="box box box-solid"> -->
                    <div id="pgwHourChart" class="chart" style="height:70px"></div>
                    <!-- </div> -->
                </div>
            </div> <!-- /row -->


            <div class="row" style="padding-bottom: 10px">
                <div class="col-md-4 col-sm-6 col-xs-12" >
                    <div class="row" style="color: #979696; text-align: center; font-size: 14px">
                        <div class="col-md-2"></div>
                        <div class="col-md-2">D</div>
                        <div class="col-md-2">W</div>
                        <div class="col-md-2">1 M</div>
                        <div class="col-md-2">3 M</div>
                        <div class="col-md-2">6 M</div>
                    </div>
                    <div class="row" style="height: 2px;">
                        <div class="col-md-2" style="height: 2px;"></div>
                        <div class="col-md-10" style="height: 2px;border-bottom: 1px solid #00c0ef;"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-2" style="color: #979696">New</div>
                        <b style="text-align: right; font-size: 12px">
                            <div id="vr_d_new" class="col-md-2 pointer"></div>
                            <div id="vr_w_new" class="col-md-2 pointer"></div>
                            <div id="vr_m_new" class="col-md-2 pointer"></div>
                            <div id="vr_3m_new" class="col-md-2 pointer"></div>
                            <div id="vr_3mg_new" class="col-md-2 pointer"></div>
                        </b>
                    </div>
                    <div class="row">
                        <div class="col-md-2" style="color: #979696">Active</div>
                        <b style="text-align: right; font-size: 12px">
                            <div id="vr_d_active" class="col-md-2 pointer"></div>
                            <div id="vr_w_active" class="col-md-2 pointer"></div>
                            <div id="vr_m_active" class="col-md-2 pointer"></div>
                            <div id="vr_3m_active" class="col-md-2 pointer"></div>
                            <div id="vr_3mg_active" class="col-md-2 pointer"></div>
                        </b>
                    </div>
                    <div class="row">
                        <div class="col-md-2" style="color: #979696">Inactive</div>
                        <div style="text-align: right; font-size: 12px">
                            <div id="vr_d_inactive" class="col-md-2 pointer"></div>
                            <div id="vr_w_inactive" class="col-md-2 pointer"></div>
                            <div id="vr_m_inactive" class="col-md-2 pointer"></div>
                            <div id="vr_3m_inactive" class="col-md-2 pointer"></div>
                            <div id="vr_3mg_inactive" class="col-md-2 pointer"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-xs-12" >
                    <div class="row" style="color: #979696;text-align: center; font-size: 14px">
                        <div class="col-md-2"></div>
                        <div class="col-md-2">D</div>
                        <div class="col-md-2">W</div>
                        <div class="col-md-2">1 M</div>
                        <div class="col-md-2">3 M</div>
                        <div class="col-md-2">6 M</div>
                    </div>
                    <div class="row" style="height: 2px;">
                        <div class="col-md-2" style="height: 2px;"></div>
                        <div class="col-md-10" style="height: 2px;border-bottom: 1px solid #00a65a;"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-2" style="color: #979696">New</div>
                        <b style="text-align: right; font-size: 12px">
                            <div id="sms_d_new" class="col-md-2 pointer"></div>
                            <div id="sms_w_new" class="col-md-2 pointer"></div>
                            <div id="sms_m_new" class="col-md-2 pointer"></div>
                            <div id="sms_3m_new" class="col-md-2 pointer"></div>
                            <div id="sms_3mg_new" class="col-md-2 pointer"></div>
                        </b>
                    </div>
                    <div class="row">
                        <div class="col-md-2" style="color: #979696">Active</div>
                        <b style="text-align: right; font-size: 12px">
                            <div id="sms_d_active" class="col-md-2 pointer"></div>
                            <div id="sms_w_active" class="col-md-2 pointer"></div>
                            <div id="sms_m_active" class="col-md-2 pointer"></div>
                            <div id="sms_3m_active" class="col-md-2 pointer"></div>
                            <div id="sms_3mg_active" class="col-md-2 pointer"></div>
                        </b>
                    </div>
                    <div class="row">
                        <div class="col-md-2" style="color: #979696">Inactive</div>
                        <div style="text-align: right; font-size: 12px">
                            <div id="sms_d_inactive" class="col-md-2 pointer"></div>
                            <div id="sms_w_inactive" class="col-md-2 pointer"></div>
                            <div id="sms_m_inactive" class="col-md-2 pointer"></div>
                            <div id="sms_3m_inactive" class="col-md-2 pointer"></div>
                            <div id="sms_3mg_inactive" class="col-md-2 pointer"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-xs-12" >
                    <div class="row" style="color: #979696;text-align: center; font-size: 14px">
                        <div class="col-md-2"></div>
                        <div class="col-md-2">D</div>
                        <div class="col-md-2">W</div>
                        <div class="col-md-2">1 M</div>
                        <div class="col-md-2">3 M</div>
                        <div class="col-md-2">6 M</div>
                    </div>
                    <div class="row" style="height: 2px;">
                        <div class="col-md-2" style="height: 2px;"></div>
                        <div class="col-md-10" style="height: 2px;border-bottom: 1px solid #f39c12;"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-2" style="color: #979696">New</div>
                        <b style="text-align: right; font-size: 12px">
                            <div id="pgw_d_new" class="col-md-2 pointer"></div>
                            <div id="pgw_w_new" class="col-md-2 pointer"></div>
                            <div id="pgw_m_new" class="col-md-2 pointer"></div>
                            <div id="pgw_3m_new" class="col-md-2 pointer"></div>
                            <div id="pgw_3mg_new" class="col-md-2 pointer"></div>
                        </b>
                    </div>
                    <div class="row">
                        <div class="col-md-2" style="color: #979696">Active</div>
                        <b style="text-align: right; font-size: 12px">
                            <div id="pgw_d_active" class="col-md-2 pointer"></div>
                            <div id="pgw_w_active" class="col-md-2 pointer"></div>
                            <div id="pgw_m_active" class="col-md-2 pointer"></div>
                            <div id="pgw_3m_active" class="col-md-2 pointer"></div>
                            <div id="pgw_3mg_active" class="col-md-2 pointer"></div>
                        </b>
                    </div>
                    <div class="row">
                        <div class="col-md-2" style="color: #979696">Inactive</div>
                        <div style="text-align: right; font-size: 12px">
                            <div id="pgw_d_inactive" class="col-md-2 pointer"></div>
                            <div id="pgw_w_inactive" class="col-md-2 pointer"></div>
                            <div id="pgw_m_inactive" class="col-md-2 pointer"></div>
                            <div id="pgw_3m_inactive" class="col-md-2 pointer"></div>
                            <div id="pgw_3mg_inactive" class="col-md-2 pointer"></div>
                        </div>
                    </div>
                </div>
            </div> <!-- /row -->

            <row>
                <div class="col-md-4">
                    <div class="col-md-2">Drop</div>
                    <div class="col-md-10 progress">
                        <div id="vrdropclient" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="40"
                             aria-valuemin="0" aria-valuemax="100" >
                            40
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="col-md-2">Drop</div>
                    <div class="col-md-10 progress">
                        <div id="smsdropclient" class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40"
                             aria-valuemin="0" aria-valuemax="100" >
                            10
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="col-md-2">Drop</div>
                    <div class="col-md-10 progress">
                        <div id="pgwdropclient" class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="40"
                             aria-valuemin="0" aria-valuemax="100" >

                        </div>
                    </div>
                </div>
            </row>

            <!-- row -->
            <div class="row">
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <!-- jQuery Knob -->
                    <!-- <div class="box box-solid"> -->
                    <div class="box-body">
                        <!-- ./col -->
                        <div class="text-center">
                            <input type="text" class="knob" id="vrKnob" value="" data-width="120" data-height="120" data-fgColor="#00C0EF" data-bgColor="rgba(0, 192, 239, 0.1)" >
                            <div class="knob-label">
                                <span>Current Month: <span id="vrCurrentMonthShow"></span> M </span>
                                <b style="padding-left: 5px">|</b>
                                <span style="padding-left: 5px">Last Month: <span id="vrLastMonthShow"></span> M </span>
                            </div>
                        </div>
                    </div>
                    <!-- </div> -->
                </div>

                <div class="col-md-4 col-sm-6 col-xs-12">
                    <!-- jQuery Knob -->
                    <!-- <div class="box box-solid"> -->
                    <div class="box-body">
                        <!-- ./col -->
                        <div class="text-center">
                            <input type="text" class="knob" id="smsKnob" value="" data-width="120" data-height="120" data-fgColor="#00A65A" data-bgColor="rgba(0, 166, 90, 0.1)">
                            <div class="knob-label">
                                <span>Current Month: <span id="smsCurrentMonthShow"></span> M </span>
                                <b style="padding-left: 5px">|</b>
                                <span style="padding-left: 5px">Last Month: <span id="smsLastMonthShow"></span> M </span>
                            </div>

                        </div>

                    </div>
                    <!-- </div> -->
                </div>


                <div class="col-md-4 col-sm-6 col-xs-12">
                    <!-- jQuery Knob -->
                    <!-- <div class="box box-solid"> -->
                    <div class="box-body">
                        <!-- ./col -->
                        <div class="text-center">
                            <input type="text" class="knob" id="pgwKnob" value="" data-width="120" data-height="120" data-fgColor="#F39C12" data-bgColor="rgba(243, 156, 18, 0.1)">
                            <div class="knob-label">
                                <span>Current Month: <span id="pgwCurrentMonthShow"></span> M </span>
                                <b style="padding-left: 5px">|</b>
                                <span style="padding-left: 5px">Last Month: <span id="pgwLastMonthShow"></span> M </span>
                            </div>
                        </div>

                    </div>
                    <!-- </div> -->
                </div>
            </div><!-- /.row -->

        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->

    @include('dashboardjs')
@endsection
@endpermission
@if($a==1)
@section('content')
   <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            {{--<h1>{{$data['title']}}</h1>--}}
        </section>

        <!-- Main content -->
        <section class="content">
            <!-- Small boxes (Stat box) -->
            <div class="row text-mddle">
              <div class="container">
                <img class="img-fluid mb-5 d-block mx-auto" src="img/profile.png" alt="">
                <h1 class="text-uppercase mb-0 text-center">Welcome to SSl Wireless BI</h1>
                <hr class="star-light">
                <h2 class="font-weight-light mb-0 text-center">Developed by Revenue Assurance Team <span>(SSL Wireless)</span></h2>
             </div>
            </div><!-- /.row -->

        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->
@endsection
@endif