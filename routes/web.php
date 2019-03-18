<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('clear-all', function () {
    \Illuminate\Support\Facades\Artisan::call('view:clear');
    \Illuminate\Support\Facades\Artisan::call('config:clear');
    \Illuminate\Support\Facades\Artisan::call('cache:clear');
    \Illuminate\Support\Facades\Artisan::call('config:cache');
    \Illuminate\Support\Facades\Artisan::call('clear-compiled');
    \Illuminate\Support\Facades\Artisan::call('route:clear');
    dd('Cached Cleared');
});

//============================Guest Route group==================================
Route::get('/', function () {
    return redirect('home');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
//Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
//Route::get('/dashboard/data', 'DashboardController@data');
//Route::get('/dashboard/init', 'DashboardController@init');
Route::group(['prefix' => 'dashboard'], function(){
    Route::get('/', 'DashboardController@index')->name('dashboard');
    Route::get('/data', 'DashboardController@data');
    Route::get('/init', 'DashboardController@init');
    Route::get('/dropclient', 'DashboardController@dropClient');
    Route::get('/newclient', 'DashboardController@newClient');
    Route::get('/activeclient', 'DashboardController@activeClient');
    Route::get('/inactiveclient', 'DashboardController@inactiveClient');
    Route::get('/newclienttable', 'DashboardController@newClientTable');
    Route::get('/activeclienttable', 'DashboardController@activeClientTable');
    Route::get('/inactiveclienttable', 'DashboardController@inactiveClientTable');
});
//===================================Auth Route Group===========================
Route::group(['middleware' => ['auth']], function () {
    
    Route::group(['prefix' => 'vr'], function(){
        // .............................. Route For VR Report Initial.....................................//
        // .............................. Route For VRChart...............................................//
        Route::get('/chart', 'VRChartController@index')->middleware('permission:VR-Chart');
        // .............................. Route For VR Report.............................................//
        Route::get('/report', 'VRReportController@index')->middleware('permission:VR-Report');
        // .............................. Route For VR Invoice............................................//
        Route::get('/invoice', 'VRInvoiceController@index');
        
        // .........................................GET JSON Data Using Ajax Call for VR Report......................................................//
        Route::get('/filterreport','VRReportController@VRReport')->middleware('permission:VR-Report');
        Route::get('/filter_summary_report','VRReportController@VRSummaryReport')->middleware('permission:VR-Report');
        
        Route::get('/init', 'VRReportController@init')->middleware('permission:VR-Report');
// ...............................................................................................//

        Route::get('init/invoice', 'VRInvoiceController@init')->middleware('permission:VR-Invoice|VR-Invoice-Payment');
// ............GET JSON Data Using Ajax Call for VR Invoice......................................................//

    });

    Route::group(['prefix' => 'sms'], function(){
        // .............................. Route For SMS Chart.............................................//
        Route::get('/chart', 'SMSChartController@index');
        // .............................. Route For SMS Report............................................//
        Route::get('/report', 'SMSReportController@index');
        // .............................. Route For SMS Invoice...........................................//
        Route::get('/invoice', 'SMSInvoiceController@index');
    });

    Route::group(['prefix' => 'pgw'], function(){
        // .............................. Route For PGW Chart.............................................//
        Route::get('/chart', 'PGWChartController@index');
        // .............................. Route For PGW Report............................................//
        Route::get('/report', 'PGWReportController@index');
        // .............................. Route For PGW Invoice...........................................//
        Route::get('/invoice', 'PGWInvoiceController@index');
    });





// ..........................GET JSON Data Using Ajax Call for VR Chart...........................//
    Route::group(['prefix' => 'vr',  'middleware' => 'permission:VR-Chart'], function(){
        Route::get('/toptenkam','VRChartController@VRTopTenKamChart');
        Route::get('/toptenclient','VRChartController@VRTopTenClientChart');
        Route::get('/transactionamount','VRChartController@VRDynamicChart');
        Route::get('/transactionamountfilter','VRChartController@VRDynamicChartFilter');
        Route::get('/operator','VRChartController@VROpearatorChart');
        Route::get('/transactioncount','VRChartController@VRTransactionCountChart');
        Route::get('/impactclient','VRChartController@VRImpactClientChart');
        Route::get('/hourwise','VRChartController@VRHourWiseChart');
        Route::get('/easy','VRChartController@VREasyChart');
        Route::get('/department','VRChartController@VRDepartmentChart');
//        Route::get('/dynamicchartcompare','VRChartController@VRDynamicChartCompareChart');
//        Route::get('/amountcompare','VRChartController@VRDailyChart');
    });
//Route::get('/vr/toptenclientcmg','VRChartController@VRTopTenClientBCMGChart');

// ...............................................................................................//


    Route::group(['prefix' => 'vr',  'middleware' => 'permission:VR-Invoice'], function(){

        Route::get('/summaryinvoice/invoice','VRInvoiceController@VRSummaryInvoice');
        Route::get('/operatorwiseinvoice/invoice','VRInvoiceController@VROperatorWiseInvoice');
        Route::get('/easyinvoice/invoice','VRInvoiceController@VREasyInvoice');
        Route::get('/easyqubee/invoice','VRInvoiceController@VREasyQubeeInvoice');
    });

    Route::group(['prefix' => 'vr',  'middleware' => 'permission:VR-Invoice-Payment'], function(){

        Route::get('/paidinvoice/invoice','VRInvoiceController@VRPaidInvoice');
        Route::get('/unpaidinvoice/invoice','VRInvoiceController@VRUnpaidInvoice');
        Route::get('/updatepayment/invoice','VRInvoiceController@VRUpdatePayment');
        Route::get('/invoice/invoice','VRInvoiceController@VRInvoice');
    });
// ...............................................................................................//

// .........................................GET JSON Data Using Ajax Call for SMS Chart......................................................//
    Route::group(['prefix' => 'sms',  'middleware' => 'permission:SMS-Chart'], function(){
        Route::get('/toptenkam','SMSChartController@SMSTopTenKamChart');
        Route::get('/toptencompany','SMSChartController@SMSTopTenCompanyChart');
        Route::get('/transactionamount','SMSChartController@SMSDynamicChart');
        Route::get('/transactionamountfilter','SMSChartController@SMSDynamicChartFilter');
        Route::get('/operator','SMSChartController@SMSOpearatorChart');
        Route::get('/transactioncount','SMSChartController@SMSTransactionCountChart');
        Route::get('/impactclient','SMSChartController@SMSImpactClientChart');
        Route::get('/industry','SMSChartController@SMSIndustryChart');
    //    Route::get('/hourwise','SMSChartController@SMSHourWiseChart');

    });

// ...............................................................................................//

// .........................................GET JSON Data Using Ajax Call for SMS Report......................................................//
    Route::group(['prefix' => 'sms',  'middleware' => 'permission:SMS-Report'], function(){

        Route::get('/init', 'SMSReportController@init');
        Route::get('/filterreport', 'SMSReportController@SMSReport');
    });

// ...............................................................................................//

// .........................................GET JSON Data Using Ajax Call for SMS Invoice......................................................//
    Route::group(['prefix' => 'sms',  'middleware' => 'permission:SMS-Invoice'], function(){

        Route::get('/init/invoice', 'SMSInvoiceController@init');
        Route::get('/invoice/summary/', 'SMSInvoiceController@SMSSummaryInvoice');
        Route::get('/invoice/invoice/', 'SMSInvoiceController@SMSInvoice');
        Route::get('/invoice/paid/', 'SMSInvoiceController@SMSPaid');
        Route::get('/invoice/unpaid/', 'SMSInvoiceController@SMSUnpaid');
        Route::get('/updatepayment/invoice', 'SMSInvoiceController@SMSPayment');
    });


// ...............................................................................................//


// .........................................GET JSON Data Using Ajax Call for PWG Chart......................................................//
    Route::group(['prefix' => 'pgw',  'middleware' => 'permission:PGW-Chart'], function(){
        Route::get('/toptencompany','PGWChartController@PGWTopTenCompanyChart');
        Route::get('/transactionamount','PGWChartController@PGWDynamicChart');
        Route::get('/transactionamountfilter','PGWChartController@PGWDynamicChartFilter');
        Route::get('/bank','PGWChartController@PGWBankChart');
        Route::get('/transactioncount','PGWChartController@PGWTransactionCountChart');
        Route::get('/impactclient','PGWChartController@PGWImpactClientChart');
        Route::get('/hourwise','PGWChartController@PGWHourWiseChart');
        Route::get('/easy','PGWChartController@PGWEasyChart');
        Route::get('/cardtype','PGWChartController@PGWCardTypeChart');
        Route::get('/assumption','PGWChartController@PGWAssumptionChart');
    //    Route::get('/dynamicchartcompare','PGWChartController@PGWDynamicChartCompareChart');
    //    Route::get('/amountcompare','PGWChartController@PGWDailyChart');

    });

// ...............................................................................................//

// ....................GET JSON Data Using Ajax Call for PGW Report..............................................//
    Route::group(['prefix' => 'pgw',  'middleware' => 'permission:PGW-Chart'], function(){

        Route::get('/init', 'PGWReportController@init');
        Route::get('/filterreport', 'PGWReportController@PGWReport');
    });


// ...............................................................................................//

// .........................................GET JSON Data Using Ajax Call for PGW Invoice......................................................//
    Route::group(['prefix' => 'pgw',  'middleware' => 'permission:PGW-Invoice'], function(){

        Route::get('/invoice', 'PGWInvoiceController@index');
    });

// ...............................................................................................//
    Route::resource('role', 'RoleController')->middleware('permission:Admin-Permission');
    Route::resource('user', 'UserController');

// ....................File Upload.........................................//
    Route::group(['prefix' => 'file',  'middleware' => 'permission:File-Upload'], function(){

        Route::get('/', 'FileUploadController@index');
        Route::post('/upload/excel', 'FileUploadController@uploadExcel');
        Route::post('/upload/word', 'FileUploadController@uploadWord');
    });
});
