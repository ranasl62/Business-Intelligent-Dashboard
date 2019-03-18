<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Log;
class DashboardController extends Controller
{
    use Log;

    public function index(){
        $this->log('Dashboard','Dashboard ',['VR','SMS','PGW']);
        $data['title']="Dashboard";
        return view('dashboard',compact('data'));
    }

//    ===================================================================================== Dashboard 7 days amount and count ============================================================
    public function init(){
        try{
            $days = array('Su','Mo', 'Tu', 'We','Th','Fr','Sa');
            DB::enableQueryLog();
//            ***************************** VR ***********************************
            $query="SELECT transdate as tdate, day(transdate) as day, sum(totalcnt) as count , SUM(Amount) as amount FROM `VRData` GROUP by transdate ORDER by transdate DESC LIMIT 7";
            $result=DB::select($query);
            $temp = array();
            $currentMonth = 0;
            $lastMonth = 0;
            $lastMonthTAE = 0;
            $i=0;
            foreach($result as $r) {
                $getDate = (string) $r->{"tdate"};
                $date = date_create($r->{'tdate'});
                $mdate = date_format($date,"l d M 'Y");
                $wday = $days[date_format($date,"w")];
                $day= (int) $r->{"day"};
                $month = date_format($date,"m") - 1;
                $year = date_format($date,"Y");
                $lday=cal_days_in_month(CAL_GREGORIAN,$month,$year);
                $count = number_format((int) $r->{'count'});
                $amount = number_format(($r->{'amount'}/1000000),2);

                $temp[] = array( $wday, $mdate, $count, $amount, number_format(($currentMonth/1000000),2), number_format(($lastMonth/1000000),2), $lastMonthTAE, $getDate );
                $i++;
            }
            $i=0;
            $data['vr']=$temp;
//                ****************************** SMS ********************************
            $query="SELECT transdate as tdate, day(transdate) as day , SUM(amount) as count , SUM(sms_rate) as amount FROM `SMSData` GROUP by transdate ORDER by transdate DESC LIMIT 7";
            $result=DB::select($query);
            $temp = array();
            $currentMonth = 0;
            $lastMonth = 0;
            $lastMonthTAE = 0;
            foreach($result as $r) {
                $getDate = (string) $r->{"tdate"};
                $date = date_create($r->{'tdate'});
                $mdate = date_format($date,"l d M 'Y");
                $wday = $days[date_format($date,"w")];
                $day= (int) $r->{"day"};
                $month = date_format($date,"m") - 1;
                $year = date_format($date,"Y");
                $lday=cal_days_in_month(CAL_GREGORIAN,$month+1,$year);
                $count = number_format(($r->{'count'}/1000000),2);
                $amount = number_format(($r->{'amount'}/1000000),2);

                $temp[] = array( $wday, $mdate, $count, $amount, number_format(($currentMonth/1000000),2), number_format(($lastMonth/1000000),2), $lastMonthTAE, $getDate );
                $i++;
            }
            $i=0;
            $data['sms']=$temp;

//            ********************************** PGW ********************************************
            $query="SELECT transdate as tdate, day(transdate) as day , sum(totalcnt) as count , SUM(mamount) as amount FROM `PGWData` GROUP by transdate ORDER by transdate DESC LIMIT 7";
            $result=DB::select($query);
            $temp = array();
            $currentMonth = 0;
            $lastMonth = 0;
            $lastMonthTAE = 0;
            foreach($result as $r) {
                $getDate = (string) $r->{"tdate"};
                $date = date_create($r->{'tdate'});
                $mdate = date_format($date,"l d M 'Y");
                $wday = $days[date_format($date,"w")];
                $day= (int) $r->{"day"};
                $month = date_format($date,"m") - 1;
                $year = date_format($date,"Y");
                $lday=cal_days_in_month(CAL_GREGORIAN,$month,$year);
                $count = number_format((int) $r->{'count'});
                $amount = number_format(($r->{'amount'}/1000000),2);

                $temp[] = array( $wday, $mdate, $count, $amount, number_format(($currentMonth/1000000),2), number_format(($lastMonth/1000000),2), $lastMonthTAE, $getDate );
                $i++;
            }
            $data['pgw']=$temp;
            return json_encode($data);
        }catch(Exception $e){
            \LOG::error($e-getMessage());
        }
    }

    //    ================================================================================= New Client =================================================
    public function  newClient(Request $request){
        $getDate = $request->input('getDate');
        $element = $request->input('element');
        try {
            $rows = array();
            $vr = array();
            $sms = array();
            $pgw = array();
            DB::enableQueryLog();
//            ***************************************************** VR new clients *******************************************
            if($element == "vrnew") {
                $query = "SELECT COUNT(DISTINCT(client_id)) as cnt FROM AK_VR_Daily WHERE transdate = :getDate and client_id not in (SELECT DISTINCT(client_id) FROM AK_VR_Daily WHERE transdate < :getDate1)";
                $result = DB::select($query, ['getDate' => $getDate, 'getDate1' => $getDate]);
                $vr['1d'] = $result[0]->{'cnt'};
                $query = "SELECT COUNT(DISTINCT(client_id)) as cnt FROM AK_VR_Daily WHERE transdate BETWEEN (:getDate- INTERVAL 7 day) and :getDate1  and client_id not in (SELECT DISTINCT(client_id) FROM AK_VR_Daily WHERE transdate < (:getDate2- INTERVAL 7 day))";
                $result = DB::select($query, ['getDate' => $getDate, 'getDate1' => $getDate, 'getDate2' => $getDate]);
                $vr['7d'] = $result[0]->{'cnt'};
                $query = "SELECT COUNT(DISTINCT(client_id)) as cnt FROM AK_VR_Daily WHERE transdate BETWEEN (:getDate- INTERVAL 30 day) and :getDate1 and client_id not in (SELECT DISTINCT(client_id) FROM AK_VR_Daily WHERE transdate < (:getDate2- INTERVAL 30 day))";
                $result = DB::select($query, ['getDate' => $getDate, 'getDate1' => $getDate, 'getDate2' => $getDate]);
                $vr['1m'] = $result[0]->{'cnt'};
                $query = "SELECT COUNT(DISTINCT(client_id)) as cnt FROM AK_VR_Daily WHERE transdate BETWEEN (:getDate- INTERVAL 30*3 day) and :getDate1 and client_id not in (SELECT DISTINCT(client_id) FROM AK_VR_Daily WHERE transdate < (:getDate2- INTERVAL 30*3 day))";
                $result = DB::select($query, ['getDate' => $getDate, 'getDate1' => $getDate, 'getDate2' => $getDate]);
                $vr['3m'] = $result[0]->{'cnt'};
                $query = "SELECT COUNT(DISTINCT(client_id)) as cnt FROM AK_VR_Daily WHERE transdate BETWEEN (:getDate- INTERVAL 30*6 day) and :getDate1 and client_id not in (SELECT DISTINCT(client_id) FROM AK_VR_Daily WHERE transdate < (:getDate2- INTERVAL 30*6 day))";
                $result = DB::select($query, ['getDate' => $getDate, 'getDate1' => $getDate, 'getDate2' => $getDate]);
                $vr['6m'] = $result[0]->{'cnt'};
                $rows['vr'] = $vr;
            }
//            ***************************************************** SMS new clients *******************************************
            if($element == "smsnew") {
                $query = "SELECT COUNT(DISTINCT(Stakeholder)) as cnt FROM `SMSData` WHERE transdate = :getDate and Stakeholder not in (SELECT DISTINCT(Stakeholder) FROM SMSData WHERE transdate < :getDate1)";
                $result = DB::select($query, ['getDate' => $getDate, 'getDate1' => $getDate]);
                $sms['1d'] = $result[0]->{'cnt'};
                $query = "SELECT COUNT(DISTINCT(Stakeholder)) as cnt FROM `SMSData` WHERE transdate BETWEEN (:getDate- INTERVAL 7 day) and :getDate1 and Stakeholder not in (SELECT DISTINCT(Stakeholder) FROM SMSData WHERE transdate < (:getDate2- INTERVAL 7 day))";
                $result = DB::select($query, ['getDate' => $getDate, 'getDate1' => $getDate, 'getDate2' => $getDate]);
                $sms['7d'] = $result[0]->{'cnt'};
                $query = "SELECT COUNT(DISTINCT(Stakeholder)) as cnt FROM `SMSData` WHERE transdate BETWEEN (:getDate- INTERVAL 30 day) and :getDate1 and Stakeholder not in (SELECT DISTINCT(Stakeholder) FROM SMSData WHERE transdate < (:getDate2- INTERVAL 30 day))";
                $result = DB::select($query, ['getDate' => $getDate, 'getDate1' => $getDate, 'getDate2' => $getDate]);
                $sms['1m'] = $result[0]->{'cnt'};
                $query = "SELECT COUNT(DISTINCT(Stakeholder)) as cnt FROM `SMSData` WHERE transdate BETWEEN (:getDate- INTERVAL 30*3 day) and :getDate1 and Stakeholder not in (SELECT DISTINCT(Stakeholder) FROM SMSData WHERE transdate < (:getDate2- INTERVAL 30*3 day))";
                $result = DB::select($query, ['getDate' => $getDate, 'getDate1' => $getDate, 'getDate2' => $getDate]);
                $sms['3m'] = $result[0]->{'cnt'};
                $query = "SELECT COUNT(DISTINCT(Stakeholder)) as cnt FROM `SMSData` WHERE transdate BETWEEN (:getDate- INTERVAL 30*6 day) and :getDate1 and Stakeholder not in (SELECT DISTINCT(Stakeholder) FROM SMSData WHERE transdate < (:getDate2- INTERVAL 30*6 day))";
                $result = DB::select($query, ['getDate' => $getDate, 'getDate1' => $getDate, 'getDate2' => $getDate]);
                $sms['6m'] = $result[0]->{'cnt'};
                $rows['sms'] = $sms;
            }
//            ***************************************************** PGW new clients *******************************************
            if($element == "pgwnew") {
                $query = "SELECT COUNT(DISTINCT(strid)) as cnt FROM AK_PGW_Daily WHERE transdate = :getDate and strid not in (SELECT DISTINCT(strid) FROM AK_PGW_Daily WHERE transdate < :getDate1)";
                $result = DB::select($query, ['getDate' => $getDate, 'getDate1' => $getDate]);
                $pgw['1d'] = $result[0]->{'cnt'};
                $query = "SELECT COUNT(DISTINCT(strid)) as cnt FROM AK_PGW_Daily WHERE transdate BETWEEN (:getDate- INTERVAL 7 day) and :getDate1 and strid not in (SELECT DISTINCT(strid) FROM AK_PGW_Daily WHERE transdate < (:getDate2- INTERVAL 7 day))";
                $result = DB::select($query, ['getDate' => $getDate, 'getDate1' => $getDate, 'getDate2' => $getDate]);
                $pgw['7d'] = $result[0]->{'cnt'};
                $query = "SELECT COUNT(DISTINCT(strid)) as cnt FROM AK_PGW_Daily WHERE transdate BETWEEN (:getDate- INTERVAL 30 day) and :getDate1 and strid not in (SELECT DISTINCT(strid) FROM AK_PGW_Daily WHERE transdate < (:getDate2- INTERVAL 30 day))";
                $result = DB::select($query, ['getDate' => $getDate, 'getDate1' => $getDate, 'getDate2' => $getDate]);
                $pgw['1m'] = $result[0]->{'cnt'};
                $query = "SELECT COUNT(DISTINCT(strid)) as cnt FROM AK_PGW_Daily WHERE transdate BETWEEN (:getDate- INTERVAL 30*3 day) and :getDate1 and strid not in (SELECT DISTINCT(strid) FROM AK_PGW_Daily WHERE transdate < (:getDate2- INTERVAL 30*3 day))";
                $result = DB::select($query, ['getDate' => $getDate, 'getDate1' => $getDate, 'getDate2' => $getDate]);
                $pgw['3m'] = $result[0]->{'cnt'};
                $query = "SELECT COUNT(DISTINCT(strid)) as cnt FROM AK_PGW_Daily WHERE transdate BETWEEN (:getDate- INTERVAL 30*6 day) and :getDate1 and strid not in (SELECT DISTINCT(strid) FROM AK_PGW_Daily WHERE transdate < (:getDate2- INTERVAL 30*6 day))";
                $result = DB::select($query, ['getDate' => $getDate, 'getDate1' => $getDate, 'getDate2' => $getDate]);
                $pgw['6m'] = $result[0]->{'cnt'};
                $rows['pgw'] = $pgw;
            }
            return json_encode($rows);
        }catch(Exception $e){
            \LOG::error($e-getMessage());
        }
    }

    //    ================================================================================= new Client Table =================================================
    public function  newClientTable(Request $request){
        $getDate = $request->input('getDate');
        $element = $request->input('element');
        try {
            $result = array();
            $row = array();

            DB::enableQueryLog();
//            ***************************************************** VR new clients  Table*******************************************
            if($element == "vr_d_new") {
                $query = "SELECT DISTINCT(client_id) as cnt FROM AK_VR_Daily WHERE transdate = :getDate and client_id not in (SELECT DISTINCT(client_id) FROM AK_VR_Daily WHERE transdate < :getDate1)";
                $result = DB::select($query, ['getDate' => $getDate, 'getDate1' => $getDate]);
            }
            if($element == "vr_w_new") {
                $query = "SELECT DISTINCT(client_id) as cnt FROM AK_VR_Daily WHERE transdate BETWEEN (:getDate- INTERVAL 7 day) and :getDate1  and client_id not in (SELECT DISTINCT(client_id) FROM AK_VR_Daily WHERE transdate < (:getDate2- INTERVAL 7 day))";
                $result = DB::select($query, ['getDate' => $getDate, 'getDate1' => $getDate, 'getDate2' => $getDate]);
            }
            if($element == "vr_m_new") {
                $query = "SELECT DISTINCT(client_id) as cnt FROM AK_VR_Daily WHERE transdate BETWEEN (:getDate- INTERVAL 30 day) and :getDate1  and client_id not in (SELECT DISTINCT(client_id) FROM AK_VR_Daily WHERE transdate < (:getDate2- INTERVAL 30 day))";
                $result = DB::select($query, ['getDate' => $getDate, 'getDate1' => $getDate, 'getDate2' => $getDate]);
            }
            if($element == "vr_3m_new") {
                $query = "SELECT DISTINCT(client_id) as cnt FROM AK_VR_Daily WHERE transdate BETWEEN (:getDate- INTERVAL 30*3 day) and :getDate1  and client_id not in (SELECT DISTINCT(client_id) FROM AK_VR_Daily WHERE transdate < (:getDate2- INTERVAL 30*3 day))";
                $result = DB::select($query, ['getDate' => $getDate, 'getDate1' => $getDate, 'getDate2' => $getDate]);
            }
            if($element == "vr_3mg_new") {
                $query = "SELECT DISTINCT(client_id) as cnt FROM AK_VR_Daily WHERE transdate BETWEEN (:getDate- INTERVAL 30*6 day) and :getDate1  and client_id not in (SELECT DISTINCT(client_id) FROM AK_VR_Daily WHERE transdate < (:getDate2- INTERVAL 30*6 day))";
                $result = DB::select($query, ['getDate' => $getDate, 'getDate1' => $getDate, 'getDate2' => $getDate]);
            }
//            ***************************************************** SMS new clients Table*******************************************
            if($element == "sms_d_new") {
                $query = "SELECT DISTINCT(Stakeholder) as cnt FROM `SMSData` WHERE transdate = :getDate and Stakeholder not in (SELECT DISTINCT(Stakeholder) FROM SMSData WHERE transdate < :getDate1)";
                $result = DB::select($query, ['getDate' => $getDate, 'getDate1' => $getDate]);
            }
            if($element == "sms_w_new") {
                $query = "SELECT DISTINCT(Stakeholder) as cnt FROM `SMSData` WHERE transdate BETWEEN (:getDate- INTERVAL 7 day) and :getDate1 and Stakeholder not in (SELECT DISTINCT(Stakeholder) FROM SMSData WHERE transdate < (:getDate2- INTERVAL 7 day))";
                $result = DB::select($query, ['getDate' => $getDate, 'getDate1' => $getDate, 'getDate2' => $getDate]);
            }
            if($element == "sms_m_new") {
                $query = "SELECT DISTINCT(Stakeholder) as cnt FROM `SMSData` WHERE transdate BETWEEN (:getDate- INTERVAL 30 day) and :getDate1 and Stakeholder not in (SELECT DISTINCT(Stakeholder) FROM SMSData WHERE transdate < (:getDate2- INTERVAL 30 day))";
                $result = DB::select($query, ['getDate' => $getDate, 'getDate1' => $getDate, 'getDate2' => $getDate]);
            }
            if($element == "sms_3m_new") {
                $query = "SELECT DISTINCT(Stakeholder) as cnt FROM `SMSData` WHERE transdate BETWEEN (:getDate- INTERVAL 30*3 day) and :getDate1 and Stakeholder not in (SELECT DISTINCT(Stakeholder) FROM SMSData WHERE transdate < (:getDate2- INTERVAL 30*3 day))";
                $result = DB::select($query, ['getDate' => $getDate, 'getDate1' => $getDate, 'getDate2' => $getDate]);
            }
            if($element == "sms_3mg_new") {
                $query = "SELECT DISTINCT(Stakeholder) as cnt FROM `SMSData` WHERE transdate BETWEEN (:getDate- INTERVAL 30*6 day) and :getDate1 and Stakeholder not in (SELECT DISTINCT(Stakeholder) FROM SMSData WHERE transdate < (:getDate2- INTERVAL 30*6 day))";
                $result = DB::select($query, ['getDate' => $getDate, 'getDate1' => $getDate, 'getDate2' => $getDate]);
            }
//            ***************************************************** PGW new clients Table*******************************************
            if($element == "pgw_d_new") {
                $query = "SELECT DISTINCT(strid) as cnt FROM AK_PGW_Daily WHERE transdate = :getDate and strid not in (SELECT DISTINCT(strid) FROM AK_PGW_Daily WHERE transdate < :getDate1)";
                $result = DB::select($query, ['getDate' => $getDate, 'getDate1' => $getDate]);
            }
            if($element == "pgw_w_new") {
                $query = "SELECT DISTINCT(strid) as cnt FROM AK_PGW_Daily WHERE transdate BETWEEN (:getDate- INTERVAL 7 day) and :getDate1 and strid not in (SELECT DISTINCT(strid) FROM AK_PGW_Daily WHERE transdate < (:getDate2- INTERVAL 7 day))";
                $result = DB::select($query, ['getDate' => $getDate, 'getDate1' => $getDate, 'getDate2' => $getDate]);
            }
            if($element == "pgw_m_new") {
                $query = "SELECT DISTINCT(strid) as cnt FROM AK_PGW_Daily WHERE transdate BETWEEN (:getDate- INTERVAL 30 day) and :getDate1 and strid not in (SELECT DISTINCT(strid) FROM AK_PGW_Daily WHERE transdate < (:getDate2- INTERVAL 30 day))";
                $result = DB::select($query, ['getDate' => $getDate, 'getDate1' => $getDate, 'getDate2' => $getDate]);
            }
            if($element == "pgw_3m_new") {
                $query = "SELECT DISTINCT(strid) as cnt FROM AK_PGW_Daily WHERE transdate BETWEEN (:getDate- INTERVAL 30*3 day) and :getDate1 and strid not in (SELECT DISTINCT(strid) FROM AK_PGW_Daily WHERE transdate < (:getDate2- INTERVAL 30*3 day))";
                $result = DB::select($query, ['getDate' => $getDate, 'getDate1' => $getDate, 'getDate2' => $getDate]);
            }
            if($element == "pgw_3mg_new") {
                $query = "SELECT DISTINCT(strid) as cnt FROM AK_PGW_Daily WHERE transdate BETWEEN (:getDate- INTERVAL 30*6 day) and :getDate1 and strid not in (SELECT DISTINCT(strid) FROM AK_PGW_Daily WHERE transdate < (:getDate2- INTERVAL 30*6 day))";
                $result = DB::select($query, ['getDate' => $getDate, 'getDate1' => $getDate, 'getDate2' => $getDate]);
            }

            if($element== "vr_d_new" || $element== "vr_w_new" || $element== "vr_m_new" || $element== "vr_3m_new" || $element== "vr_3mg_new"){
                foreach ($result as $r){
                    $query = "SELECT (SELECT client.client_name FROM client WHERE client.client_id = :client3) client,  a.st_date, (SELECT sum(amount) FROM AK_VR_Daily WHERE transdate= a.st_date and client_id=:client) st_amount ,a.en_date, (SELECT sum(amount) FROM AK_VR_Daily WHERE transdate= a.en_date and client_id= :client1) en_amount FROM (SELECT min(transdate) as st_date, max(transdate) en_date FROM AK_VR_Daily WHERE client_id= :client2 and transdate <= :getDate) a";
                    $result1 = DB::select($query, ['getDate' => $getDate, 'client' => $r->{'cnt'}, 'client1' => $r->{'cnt'}, 'client2' => $r->{'cnt'}, 'client3' => $r->{'cnt'}]);
                    foreach ($result1 as $r1){
                        $row[] = ['client' => $r1->{'client'}, 'st_date' => $r1->{'st_date'}, 'st_amount' => $r1->{'st_amount'}, 'en_date' => $r1->{'en_date'}, 'en_amount' => $r1->{'en_amount'}];
                    }
                }
            }
            elseif($element== "sms_d_new" || $element== "sms_w_new" || $element== "sms_m_new" || $element== "sms_3m_new" || $element== "sms_3mg_new"){
                foreach ($result as $r){
                    $query = "SELECT  a.st_date, (SELECT sum(SMSData.sms_rate) FROM SMSData WHERE transdate= a.st_date and Stakeholder=:client) st_amount ,a.en_date, (SELECT sum(SMSData.sms_rate) FROM SMSData WHERE transdate= a.en_date and Stakeholder= :client1) en_amount FROM (SELECT min(transdate) as st_date, max(transdate) en_date FROM SMSData WHERE Stakeholder = :client2 and transdate <= :getDate) a";
                    $result1 = DB::select($query, ['getDate' => $getDate, 'client' => $r->{'cnt'}, 'client1' => $r->{'cnt'}, 'client2' => $r->{'cnt'}]);
                    foreach ($result1 as $r1){
                        $row[] = ['client' => $r->{'cnt'}, 'st_date' => $r1->{'st_date'}, 'st_amount' => $r1->{'st_amount'}, 'en_date' => $r1->{'en_date'}, 'en_amount' => $r1->{'en_amount'}];
                    }
                }
            }
            elseif($element== "pgw_d_new" || $element== "pgw_w_new" || $element== "pgw_m_new" || $element== "pgw_3m_new" || $element== "pgw_3mg_new"){
                foreach ($result as $r){
                    $query = "SELECT  a.st_date, (SELECT sum(mamount) FROM AK_PGW_Daily WHERE transdate= a.st_date and strid=:client) st_amount ,a.en_date, (SELECT sum(mamount) FROM AK_PGW_Daily WHERE transdate= a.en_date and strid= :client1) en_amount FROM (SELECT min(transdate) as st_date, max(transdate) en_date FROM AK_PGW_Daily WHERE strid= :client2 and transdate <= :getDate) a";
                    $result1 = DB::select($query, ['getDate' => $getDate, 'client' => $r->{'cnt'}, 'client1' => $r->{'cnt'}, 'client2' => $r->{'cnt'}]);
                    foreach ($result1 as $r1){
                        $row[] = ['client' => $r->{'cnt'}, 'st_date' => $r1->{'st_date'}, 'st_amount' => $r1->{'st_amount'}, 'en_date' => $r1->{'en_date'}, 'en_amount' => $r1->{'en_amount'}];
                    }
                }
            }

            return json_encode($row);
        }catch(Exception $e){
            \LOG::error($e-getMessage());
        }
    }


//    ================================================================================= Active Client =================================================
    public function  activeClient(Request $request){
        $getDate = $request->input('getDate');
        $element = $request->input('element');
        try {
            $rows = array();
            $vr = array();
            $sms = array();
            $pgw = array();
            DB::enableQueryLog();
//            ***************************************************** VR active clients *******************************************
            if($element == "vractive") {
                $query = "SELECT COUNT(DISTINCT(client_id)) as cnt FROM AK_VR_Daily WHERE transdate = :getDate";
                $result = DB::select($query, ['getDate' => $getDate]);
                $vr['1d'] = $result[0]->{'cnt'};
                $query = "SELECT COUNT(DISTINCT(client_id)) as cnt FROM AK_VR_Daily WHERE transdate BETWEEN (:getDate- INTERVAL 7 day) and :getDate1";
                $result = DB::select($query, ['getDate' => $getDate, 'getDate1' => $getDate]);
                $vr['7d'] = $result[0]->{'cnt'};
                $query = "SELECT COUNT(DISTINCT(client_id)) as cnt FROM AK_VR_Daily WHERE transdate BETWEEN (:getDate- INTERVAL 30 day) and :getDate1";
                $result = DB::select($query, ['getDate' => $getDate, 'getDate1' => $getDate]);
                $vr['1m'] = $result[0]->{'cnt'};
                $query = "SELECT COUNT(DISTINCT(client_id)) as cnt FROM AK_VR_Daily WHERE transdate BETWEEN (:getDate- INTERVAL 30*3 day) and :getDate1";
                $result = DB::select($query, ['getDate' => $getDate, 'getDate1' => $getDate]);
                $vr['3m'] = $result[0]->{'cnt'};
                $query = "SELECT COUNT(DISTINCT(client_id)) as cnt FROM AK_VR_Daily WHERE transdate BETWEEN (:getDate- INTERVAL 30*6 day) and :getDate1";
                $result = DB::select($query, ['getDate' => $getDate, 'getDate1' => $getDate]);
                $vr['6m'] = $result[0]->{'cnt'};
                $rows['vr'] = $vr;
            }
//            ***************************************************** SMS active clients *******************************************
            if($element == "smsactive") {
                $query = "SELECT COUNT(DISTINCT(Stakeholder)) as cnt FROM `SMSData` WHERE transdate = :getDate";
                $result = DB::select($query, ['getDate' => $getDate]);
                $sms['1d'] = $result[0]->{'cnt'};
                $query = "SELECT COUNT(DISTINCT(Stakeholder)) as cnt FROM `SMSData` WHERE transdate BETWEEN (:getDate- INTERVAL 7 day) and :getDate1";
                $result = DB::select($query, ['getDate' => $getDate, 'getDate1' => $getDate]);
                $sms['7d'] = $result[0]->{'cnt'};
                $query = "SELECT COUNT(DISTINCT(Stakeholder)) as cnt FROM `SMSData` WHERE transdate BETWEEN (:getDate- INTERVAL 30 day) and :getDate1";
                $result = DB::select($query, ['getDate' => $getDate, 'getDate1' => $getDate]);
                $sms['1m'] = $result[0]->{'cnt'};
                $query = "SELECT COUNT(DISTINCT(Stakeholder)) as cnt FROM `SMSData` WHERE transdate BETWEEN (:getDate- INTERVAL 30*3 day) and :getDate1";
                $result = DB::select($query, ['getDate' => $getDate, 'getDate1' => $getDate]);
                $sms['3m'] = $result[0]->{'cnt'};
                $query = "SELECT COUNT(DISTINCT(Stakeholder)) as cnt FROM `SMSData` WHERE transdate BETWEEN (:getDate- INTERVAL 30*6 day) and :getDate1";
                $result = DB::select($query, ['getDate' => $getDate, 'getDate1' => $getDate]);
                $sms['6m'] = $result[0]->{'cnt'};
                $rows['sms'] = $sms;
            }
//            ***************************************************** PGW active clients *******************************************
            if($element == "pgwactive") {
                $query = "SELECT COUNT(DISTINCT(strid)) as cnt FROM AK_PGW_Daily WHERE transdate = :getDate";
                $result = DB::select($query, ['getDate' => $getDate]);
                $pgw['1d'] = $result[0]->{'cnt'};
                $query = "SELECT COUNT(DISTINCT(strid)) as cnt FROM AK_PGW_Daily WHERE transdate BETWEEN (:getDate- INTERVAL 7 day) and :getDate1";
                $result = DB::select($query, ['getDate' => $getDate, 'getDate1' => $getDate]);
                $pgw['7d'] = $result[0]->{'cnt'};
                $query = "SELECT COUNT(DISTINCT(strid)) as cnt FROM AK_PGW_Daily WHERE transdate BETWEEN (:getDate- INTERVAL 30 day) and :getDate1";
                $result = DB::select($query, ['getDate' => $getDate, 'getDate1' => $getDate]);
                $pgw['1m'] = $result[0]->{'cnt'};
                $query = "SELECT COUNT(DISTINCT(strid)) as cnt FROM AK_PGW_Daily WHERE transdate BETWEEN (:getDate- INTERVAL 30*3 day) and :getDate1";
                $result = DB::select($query, ['getDate' => $getDate, 'getDate1' => $getDate]);
                $pgw['3m'] = $result[0]->{'cnt'};
                $query = "SELECT COUNT(DISTINCT(strid)) as cnt FROM AK_PGW_Daily WHERE transdate BETWEEN (:getDate- INTERVAL 30*6 day) and :getDate1";
                $result = DB::select($query, ['getDate' => $getDate, 'getDate1' => $getDate]);
                $pgw['6m'] = $result[0]->{'cnt'};
                $rows['pgw'] = $pgw;
            }
//            dd($rows);
            return json_encode($rows);
        }catch(Exception $e){
            \LOG::error($e-getMessage());
        }
    }

    //    ================================================================================= Active Client Table =================================================
    public function  activeClientTable(Request $request){
        $getDate = $request->input('getDate');
        $element = $request->input('element');
        try {
            $result = array();
            $row = array();

            DB::enableQueryLog();
//            ***************************************************** VR active clients  Table*******************************************
            if($element == "vr_d_active") {
                $query = "SELECT DISTINCT(client_id) as cnt FROM AK_VR_Daily WHERE transdate = :getDate";
                $result = DB::select($query, ['getDate' => $getDate]);
            }
            if($element == "vr_w_active") {
                $query = "SELECT DISTINCT(client_id) as cnt FROM AK_VR_Daily WHERE transdate BETWEEN (:getDate- INTERVAL 7 day) and :getDate1";
                $result = DB::select($query, ['getDate' => $getDate, 'getDate1' => $getDate]);
            }
            if($element == "vr_m_active") {
                $query = "SELECT DISTINCT(client_id) as cnt FROM AK_VR_Daily WHERE transdate BETWEEN (:getDate- INTERVAL 30 day) and :getDate1";
                $result = DB::select($query, ['getDate' => $getDate, 'getDate1' => $getDate]);
            }
            if($element == "vr_3m_active") {
                $query = "SELECT DISTINCT(client_id) as cnt FROM AK_VR_Daily WHERE transdate BETWEEN (:getDate- INTERVAL 30*3 day) and :getDate1";
                $result = DB::select($query, ['getDate' => $getDate, 'getDate1' => $getDate]);
            }
            if($element == "vr_3mg_active") {
                $query = "SELECT DISTINCT(client_id) as cnt FROM AK_VR_Daily WHERE transdate BETWEEN (:getDate- INTERVAL 30*6 day) and :getDate1";
                $result = DB::select($query, ['getDate' => $getDate, 'getDate1' => $getDate]);
            }
//            ***************************************************** SMS active clients Table*******************************************
            if($element == "sms_d_active") {
                $query = "SELECT DISTINCT(Stakeholder) as cnt FROM `SMSData` WHERE transdate = :getDate";
                $result = DB::select($query, ['getDate' => $getDate]);
            }
            if($element == "sms_w_active") {
                $query = "SELECT DISTINCT(Stakeholder) as cnt FROM `SMSData` WHERE transdate BETWEEN (:getDate- INTERVAL 7 day) and :getDate1";
                $result = DB::select($query, ['getDate' => $getDate, 'getDate1' => $getDate]);
            }
            if($element == "sms_m_active") {
                $query = "SELECT DISTINCT(Stakeholder) as cnt FROM `SMSData` WHERE transdate BETWEEN (:getDate- INTERVAL 30 day) and :getDate1";
                $result = DB::select($query, ['getDate' => $getDate, 'getDate1' => $getDate]);
            }
            if($element == "sms_3m_active") {
                $query = "SELECT DISTINCT(Stakeholder) as cnt FROM `SMSData` WHERE transdate BETWEEN (:getDate- INTERVAL 30*3 day) and :getDate1";
                $result = DB::select($query, ['getDate' => $getDate, 'getDate1' => $getDate]);
            }
            if($element == "sms_3mg_active") {
                $query = "SELECT DISTINCT(Stakeholder) as cnt FROM `SMSData` WHERE transdate BETWEEN (:getDate- INTERVAL 30*6 day) and :getDate1";
                $result = DB::select($query, ['getDate' => $getDate, 'getDate1' => $getDate]);
            }
//            ***************************************************** PGW active clients Table*******************************************
            if($element == "pgw_d_active") {
                $query = "SELECT DISTINCT(strid) as cnt FROM AK_PGW_Daily WHERE transdate = :getDate";
                $result = DB::select($query, ['getDate' => $getDate]);
            }
            if($element == "pgw_w_active") {
                $query = "SELECT DISTINCT(strid) as cnt FROM AK_PGW_Daily WHERE transdate BETWEEN (:getDate- INTERVAL 7 day) and :getDate1";
                $result = DB::select($query, ['getDate' => $getDate, 'getDate1' => $getDate]);
            }
            if($element == "pgw_m_active") {
                $query = "SELECT DISTINCT(strid) as cnt FROM AK_PGW_Daily WHERE transdate BETWEEN (:getDate- INTERVAL 30 day) and :getDate1";
                $result = DB::select($query, ['getDate' => $getDate, 'getDate1' => $getDate]);
            }
            if($element == "pgw_3m_active") {
                $query = "SELECT DISTINCT(strid) as cnt FROM AK_PGW_Daily WHERE transdate BETWEEN (:getDate- INTERVAL 30*3 day) and :getDate1";
                $result = DB::select($query, ['getDate' => $getDate, 'getDate1' => $getDate]);
            }
            if($element == "pgw_3mg_active") {
                $query = "SELECT DISTINCT(strid) as cnt FROM AK_PGW_Daily WHERE transdate BETWEEN (:getDate- INTERVAL 30*6 day) and :getDate1";
                $result = DB::select($query, ['getDate' => $getDate, 'getDate1' => $getDate]);
            }

            if($element== "vr_d_active" || $element== "vr_w_active" || $element== "vr_m_active" || $element== "vr_3m_active" || $element== "vr_3mg_active"){
                foreach ($result as $r){
                    $query = "SELECT (SELECT client.client_name FROM client WHERE client.client_id = :client3) client, a.st_date, (SELECT sum(amount) FROM AK_VR_Daily WHERE transdate= a.st_date and client_id=:client) st_amount ,a.en_date, (SELECT sum(amount) FROM AK_VR_Daily WHERE transdate= a.en_date and client_id= :client1) en_amount FROM (SELECT min(transdate) as st_date, max(transdate) en_date FROM AK_VR_Daily WHERE client_id= :client2 and transdate <= :getDate) a";
                    $result1 = DB::select($query, ['getDate' => $getDate, 'client' => $r->{'cnt'}, 'client1' => $r->{'cnt'}, 'client2' => $r->{'cnt'}, 'client3' => $r->{'cnt'}]);
                    foreach ($result1 as $r1){
                        $row[] = ['client' => $r1->{'client'}, 'st_date' => $r1->{'st_date'}, 'st_amount' => $r1->{'st_amount'}, 'en_date' => $r1->{'en_date'}, 'en_amount' => $r1->{'en_amount'}];
                    }
                }
            }
            elseif($element== "sms_d_active" || $element== "sms_w_active" || $element== "sms_m_active" || $element== "sms_3m_active" || $element== "sms_3mg_active"){
                foreach ($result as $r){
                    $query = "SELECT  a.st_date, (SELECT sum(SMSData.sms_rate) FROM SMSData WHERE transdate= a.st_date and Stakeholder=:client) st_amount ,a.en_date, (SELECT sum(SMSData.sms_rate) FROM SMSData WHERE transdate= a.en_date and Stakeholder= :client1) en_amount FROM (SELECT min(transdate) as st_date, max(transdate) en_date FROM SMSData WHERE Stakeholder = :client2 and transdate <= :getDate) a";
                    $result1 = DB::select($query, ['getDate' => $getDate, 'client' => $r->{'cnt'}, 'client1' => $r->{'cnt'}, 'client2' => $r->{'cnt'}]);
                    foreach ($result1 as $r1){
                        $row[] = ['client' => $r->{'cnt'}, 'st_date' => $r1->{'st_date'}, 'st_amount' => $r1->{'st_amount'}, 'en_date' => $r1->{'en_date'}, 'en_amount' => $r1->{'en_amount'}];
                    }
                }
            }
            elseif($element== "pgw_d_active" || $element== "pgw_w_active" || $element== "pgw_m_active" || $element== "pgw_3m_active" || $element== "pgw_3mg_active"){
                foreach ($result as $r){
                    $query = "SELECT  a.st_date, (SELECT sum(mamount) FROM AK_PGW_Daily WHERE transdate= a.st_date and strid=:client) st_amount ,a.en_date, (SELECT sum(mamount) FROM AK_PGW_Daily WHERE transdate= a.en_date and strid= :client1) en_amount FROM (SELECT min(transdate) as st_date, max(transdate) en_date FROM AK_PGW_Daily WHERE strid= :client2 and transdate <= :getDate) a";
                    $result1 = DB::select($query, ['getDate' => $getDate, 'client' => $r->{'cnt'}, 'client1' => $r->{'cnt'}, 'client2' => $r->{'cnt'}]);
                    foreach ($result1 as $r1){
                        $row[] = ['client' => $r->{'cnt'}, 'st_date' => $r1->{'st_date'}, 'st_amount' => $r1->{'st_amount'}, 'en_date' => $r1->{'en_date'}, 'en_amount' => $r1->{'en_amount'}];
                    }
                }
            }

            return json_encode($row);
        }catch(Exception $e){
            \LOG::error($e-getMessage());
        }
    }



    //    ================================================================================= Inactive Client =================================================
    public function  inactiveClient(Request $request){
        $getDate = $request->input('getDate');
        $element = $request->input('element');
        try {
            $rows = array();
            $vr = array();
            $sms = array();
            $pgw = array();
            DB::enableQueryLog();
//            ***************************************************** VR inactive clients *******************************************
            if($element == "vrinactive") {
                $query = "SELECT COUNT(client_id) as cnt FROM `client` WHERE client_id not in (SELECT DISTINCT(client_id) as cnt FROM AK_VR_Daily WHERE transdate = :getDate)";
                $result = DB::select($query,['getDate' => $getDate]);
                $vr['1d'] = $result[0]->{'cnt'};
                $query = "SELECT COUNT(client_id) as cnt FROM `client` WHERE client_id not in (SELECT DISTINCT(client_id) as cnt FROM AK_VR_Daily WHERE transdate BETWEEN (:getDate- INTERVAL 7 day) and :getDate1)";
                $result = DB::select($query, ['getDate' => $getDate, 'getDate1' => $getDate]);
                $vr['7d'] = $result[0]->{'cnt'};
                $query = "SELECT COUNT(client_id) as cnt FROM `client` WHERE client_id not in (SELECT DISTINCT(client_id) as cnt FROM AK_VR_Daily WHERE transdate BETWEEN (:getDate- INTERVAL 30 day) and :getDate1)";
                $result = DB::select($query, ['getDate' => $getDate, 'getDate1' => $getDate]);
                $vr['1m'] = $result[0]->{'cnt'};
                $query = "SELECT COUNT(client_id) as cnt FROM `client` WHERE client_id not in (SELECT DISTINCT(client_id) as cnt FROM AK_VR_Daily WHERE transdate BETWEEN (:getDate- INTERVAL 30*3 day) and :getDate1)";
                $result = DB::select($query, ['getDate' => $getDate, 'getDate1' => $getDate]);
                $vr['3m'] = $result[0]->{'cnt'};
                $query = "SELECT COUNT(client_id) as cnt FROM `client` WHERE client_id not in (SELECT DISTINCT(client_id) as cnt FROM AK_VR_Daily WHERE transdate BETWEEN (:getDate- INTERVAL 30*6 day) and :getDate1)";
                $result = DB::select($query, ['getDate' => $getDate, 'getDate1' => $getDate]);
                $vr['6m'] = $result[0]->{'cnt'};
                $rows['vr'] = $vr;
            }
//            ***************************************************** SMS inactive clients *******************************************
            if($element == "smsinactive") {
                $query = "SELECT COUNT(`stakeholder`) as cnt FROM `smsMaster` WHERE stakeholder not in(SELECT DISTINCT(Stakeholder) FROM `SMSData` WHERE transdate = :getDate)";
                $result = DB::select($query, ['getDate' => $getDate]);
                $sms['1d'] = $result[0]->{'cnt'};
                $query = "SELECT COUNT(`stakeholder`) as cnt FROM `smsMaster` WHERE stakeholder not in(SELECT DISTINCT(Stakeholder) FROM `SMSData` WHERE transdate BETWEEN (:getDate- INTERVAL 7 day) and :getDate1)";
                $result = DB::select($query, ['getDate' => $getDate, 'getDate1' => $getDate]);
                $sms['7d'] = $result[0]->{'cnt'};
                $query = "SELECT COUNT(`stakeholder`) as cnt FROM `smsMaster` WHERE stakeholder not in(SELECT DISTINCT(Stakeholder) FROM `SMSData` WHERE transdate BETWEEN (:getDate- INTERVAL 30 day) and :getDate1)";
                $result = DB::select($query, ['getDate' => $getDate, 'getDate1' => $getDate]);
                $sms['1m'] = $result[0]->{'cnt'};
                $query = "SELECT COUNT(`stakeholder`) as cnt FROM `smsMaster` WHERE stakeholder not in(SELECT DISTINCT(Stakeholder) FROM `SMSData` WHERE transdate BETWEEN (:getDate- INTERVAL 30*3 day) and :getDate1)";
                $result = DB::select($query, ['getDate' => $getDate, 'getDate1' => $getDate]);
                $sms['3m'] = $result[0]->{'cnt'};
                $query = "SELECT COUNT(`stakeholder`) as cnt FROM `smsMaster` WHERE stakeholder not in(SELECT DISTINCT(Stakeholder) FROM `SMSData` WHERE transdate BETWEEN (:getDate- INTERVAL 30*6 day) and :getDate1)";
                $result = DB::select($query, ['getDate' => $getDate, 'getDate1' => $getDate]);
                $sms['6m'] = $result[0]->{'cnt'};
                $rows['sms'] = $sms;
            }
//            ***************************************************** PGW inactive clients *******************************************
            if($element == "pgwinactive") {
                $query = "SELECT COUNT(strid) as cnt FROM `pgwMaster` WHERE strid not in(SELECT DISTINCT(strid) FROM AK_PGW_Daily WHERE transdate = :getDate)";
                $result = DB::select($query, ['getDate' => $getDate]);
                $pgw['1d'] = $result[0]->{'cnt'};
                $query = "SELECT COUNT(strid) as cnt FROM `pgwMaster` WHERE strid not in(SELECT DISTINCT(strid) FROM AK_PGW_Daily WHERE  transdate BETWEEN (:getDate- INTERVAL 7 day) and :getDate1)";
                $result = DB::select($query, ['getDate' => $getDate, 'getDate1' => $getDate]);
                $pgw['7d'] = $result[0]->{'cnt'};
                $query = "SELECT COUNT(strid) as cnt FROM `pgwMaster` WHERE strid not in(SELECT DISTINCT(strid) FROM AK_PGW_Daily WHERE  transdate BETWEEN (:getDate- INTERVAL 30 day) and :getDate1)";
                $result = DB::select($query, ['getDate' => $getDate, 'getDate1' => $getDate]);
                $pgw['1m'] = $result[0]->{'cnt'};
                $query = "SELECT COUNT(strid) as cnt FROM `pgwMaster` WHERE strid not in(SELECT DISTINCT(strid) FROM AK_PGW_Daily WHERE  transdate BETWEEN (:getDate- INTERVAL 30*3 day) and :getDate1)";
                $result = DB::select($query, ['getDate' => $getDate, 'getDate1' => $getDate]);
                $pgw['3m'] = $result[0]->{'cnt'};
                $query = "SELECT COUNT(strid) as cnt FROM `pgwMaster` WHERE strid not in(SELECT DISTINCT(strid) FROM AK_PGW_Daily WHERE  transdate BETWEEN (:getDate- INTERVAL 30*6 day) and :getDate1)";
                $result = DB::select($query, ['getDate' => $getDate, 'getDate1' => $getDate]);
                $pgw['6m'] = $result[0]->{'cnt'};
                $rows['pgw'] = $pgw;
            }
//            dd($rows);
            return json_encode($rows);
        }catch(Exception $e){
            \LOG::error($e-getMessage());
        }
    }


    //    ================================================================================= Inctive Client Table =================================================
    public function  inactiveClientTable(Request $request){
        $getDate = $request->input('getDate');
        $element = $request->input('element');
        try {
            $result = array();
            $row = array();

            DB::enableQueryLog();
//            ***************************************************** VR inactive clients  Table*******************************************
            if($element == "vr_d_inactive") {
                $query = "SELECT client_id as cnt FROM `client` WHERE client_id not in (SELECT DISTINCT(client_id) as cnt FROM AK_VR_Daily WHERE transdate = :getDate)";
                $result = DB::select($query, ['getDate' => $getDate]);
            }
            if($element == "vr_w_inactive") {
                $query = "SELECT client_id as cnt FROM `client` WHERE client_id not in (SELECT DISTINCT(client_id) as cnt FROM AK_VR_Daily WHERE transdate BETWEEN (:getDate- INTERVAL 7 day) and :getDate1)";
                $result = DB::select($query, ['getDate' => $getDate, 'getDate1' => $getDate]);
            }
            if($element == "vr_m_inactive") {
                $query = "SELECT client_id as cnt FROM `client` WHERE client_id not in (SELECT DISTINCT(client_id) as cnt FROM AK_VR_Daily WHERE transdate BETWEEN (:getDate- INTERVAL 30 day) and :getDate1)";
                $result = DB::select($query, ['getDate' => $getDate, 'getDate1' => $getDate]);
            }
            if($element == "vr_3m_inactive") {
                $query = "SELECT client_id as cnt FROM `client` WHERE client_id not in (SELECT DISTINCT(client_id) as cnt FROM AK_VR_Daily WHERE transdate BETWEEN (:getDate- INTERVAL 30*3 day) and :getDate1)";
                $result = DB::select($query, ['getDate' => $getDate, 'getDate1' => $getDate]);
            }
            if($element == "vr_3mg_inactive") {
                $query = "SELECT client_id as cnt FROM `client` WHERE client_id not in (SELECT DISTINCT(client_id) as cnt FROM AK_VR_Daily WHERE transdate BETWEEN (:getDate- INTERVAL 30*6 day) and :getDate1)";
                $result = DB::select($query, ['getDate' => $getDate, 'getDate1' => $getDate]);
            }
//            ***************************************************** SMS inactive clients Table*******************************************
            if($element == "sms_d_inactive") {
                $query = "SELECT `stakeholder` as cnt FROM `smsMaster` WHERE stakeholder not in(SELECT DISTINCT(Stakeholder) FROM `SMSData` WHERE transdate = :getDate)";
                $result = DB::select($query, ['getDate' => $getDate]);
            }
            if($element == "sms_w_inactive") {
                $query = "SELECT `stakeholder` as cnt FROM `smsMaster` WHERE stakeholder not in(SELECT DISTINCT(Stakeholder) FROM `SMSData` WHERE transdate BETWEEN (:getDate- INTERVAL 7 day) and :getDate1)";
                $result = DB::select($query, ['getDate' => $getDate, 'getDate1' => $getDate]);
            }
            if($element == "sms_m_inactive") {
                $query = "SELECT `stakeholder` as cnt FROM `smsMaster` WHERE stakeholder not in(SELECT DISTINCT(Stakeholder) FROM `SMSData` WHERE transdate BETWEEN (:getDate- INTERVAL 30 day) and :getDate1)";
                $result = DB::select($query, ['getDate' => $getDate, 'getDate1' => $getDate]);
            }
            if($element == "sms_3m_inactive") {
                $query = "SELECT `stakeholder` as cnt FROM `smsMaster` WHERE stakeholder not in(SELECT DISTINCT(Stakeholder) FROM `SMSData` WHERE transdate BETWEEN (:getDate- INTERVAL 30*3 day) and :getDate1)";
                $result = DB::select($query, ['getDate' => $getDate, 'getDate1' => $getDate]);
            }
            if($element == "sms_3mg_inactive") {
                $query = "SELECT `stakeholder` as cnt FROM `smsMaster` WHERE stakeholder not in(SELECT DISTINCT(Stakeholder) FROM `SMSData` WHERE transdate BETWEEN (:getDate- INTERVAL 30*6 day) and :getDate1)";
                $result = DB::select($query, ['getDate' => $getDate, 'getDate1' => $getDate]);
            }
//            ***************************************************** PGW inactive clients Table*******************************************
            if($element == "pgw_d_inactive") {
                $query = "SELECT strid as cnt FROM `pgwMaster` WHERE strid not in(SELECT DISTINCT(strid) FROM AK_PGW_Daily WHERE transdate = :getDate)";
                $result = DB::select($query, ['getDate' => $getDate]);
            }
            if($element == "pgw_w_inactive") {
                $query = "SELECT strid as cnt FROM `pgwMaster` WHERE strid not in(SELECT DISTINCT(strid) FROM AK_PGW_Daily WHERE  transdate BETWEEN (:getDate- INTERVAL 7 day) and :getDate1)";
                $result = DB::select($query, ['getDate' => $getDate, 'getDate1' => $getDate]);
            }
            if($element == "pgw_m_inactive") {
                $query = "SELECT strid as cnt FROM `pgwMaster` WHERE strid not in(SELECT DISTINCT(strid) FROM AK_PGW_Daily WHERE  transdate BETWEEN (:getDate- INTERVAL 30 day) and :getDate1)";
                $result = DB::select($query, ['getDate' => $getDate, 'getDate1' => $getDate]);
            }
            if($element == "pgw_3m_inactive") {
                $query = "SELECT strid as cnt FROM `pgwMaster` WHERE strid not in(SELECT DISTINCT(strid) FROM AK_PGW_Daily WHERE  transdate BETWEEN (:getDate- INTERVAL 30*3 day) and :getDate1)";
                $result = DB::select($query, ['getDate' => $getDate, 'getDate1' => $getDate]);
            }
            if($element == "pgw_3mg_inactive") {
                $query = "SELECT strid as cnt FROM `pgwMaster` WHERE strid not in(SELECT DISTINCT(strid) FROM AK_PGW_Daily WHERE  transdate BETWEEN (:getDate- INTERVAL 30*6 day) and :getDate1)";
                $result = DB::select($query, ['getDate' => $getDate, 'getDate1' => $getDate]);
            }

            if($element== "vr_d_inactive" || $element== "vr_w_inactive" || $element== "vr_m_inactive" || $element== "vr_3m_inactive" || $element== "vr_3mg_inactive"){
                foreach ($result as $r){
                    $query = "SELECT (SELECT client.client_name FROM client WHERE client.client_id = :client3) client,  a.st_date, (SELECT sum(amount) FROM AK_VR_Daily WHERE transdate= a.st_date and client_id=:client) st_amount ,a.en_date, (SELECT sum(amount) FROM AK_VR_Daily WHERE transdate= a.en_date and client_id= :client1) en_amount FROM (SELECT min(transdate) as st_date, max(transdate) en_date FROM AK_VR_Daily WHERE client_id= :client2 and transdate <= :getDate) a";
                    $result1 = DB::select($query, ['getDate' => $getDate, 'client' => $r->{'cnt'}, 'client1' => $r->{'cnt'}, 'client2' => $r->{'cnt'}, 'client3' => $r->{'cnt'}]);
                    foreach ($result1 as $r1){

                        $row[] = ['client' => $r1->{'client'}, 'st_date' => $r1->{'st_date'}, 'st_amount' => $r1->{'st_amount'}, 'en_date' => $r1->{'en_date'}, 'en_amount' => $r1->{'en_amount'}];
                    }
                }
            }
            elseif($element== "sms_d_inactive" || $element== "sms_w_inactive" || $element== "sms_m_inactive" || $element== "sms_3m_inactive" || $element== "sms_3mg_inactive"){
                foreach ($result as $r){
                    $query = "SELECT  a.st_date, (SELECT sum(SMSData.sms_rate) FROM SMSData WHERE transdate= a.st_date and Stakeholder=:client) st_amount ,a.en_date, (SELECT sum(SMSData.sms_rate) FROM SMSData WHERE transdate= a.en_date and Stakeholder= :client1) en_amount FROM (SELECT min(transdate) as st_date, max(transdate) en_date FROM SMSData WHERE Stakeholder = :client2 and transdate <= :getDate) a";
                    $result1 = DB::select($query, ['getDate' => $getDate, 'client' => $r->{'cnt'}, 'client1' => $r->{'cnt'}, 'client2' => $r->{'cnt'}]);
                    foreach ($result1 as $r1){
                        $row[] = ['client' => $r->{'cnt'}, 'st_date' => $r1->{'st_date'}, 'st_amount' => $r1->{'st_amount'}, 'en_date' => $r1->{'en_date'}, 'en_amount' => $r1->{'en_amount'}];
                    }
                }
            }
            elseif($element== "pgw_d_inactive" || $element== "pgw_w_inactive" || $element== "pgw_m_inactive" || $element== "pgw_3m_inactive" || $element== "pgw_3mg_inactive"){
                foreach ($result as $r){
                    $query = "SELECT  a.st_date, (SELECT sum(mamount) FROM AK_PGW_Daily WHERE transdate= a.st_date and strid=:client) st_amount ,a.en_date, (SELECT sum(mamount) FROM AK_PGW_Daily WHERE transdate= a.en_date and strid= :client1) en_amount FROM (SELECT min(transdate) as st_date, max(transdate) en_date FROM AK_PGW_Daily WHERE strid= :client2 and transdate <= :getDate) a";
                    $result1 = DB::select($query, ['getDate' => $getDate, 'client' => $r->{'cnt'}, 'client1' => $r->{'cnt'}, 'client2' => $r->{'cnt'}]);
                    foreach ($result1 as $r1){
                        $row[] = ['client' => $r->{'cnt'}, 'st_date' => $r1->{'st_date'}, 'st_amount' => $r1->{'st_amount'}, 'en_date' => $r1->{'en_date'}, 'en_amount' => $r1->{'en_amount'}];
                    }
                }
            }

            return json_encode($row);
        }catch(Exception $e){
            \LOG::error($e-getMessage());
        }
    }


//    =================================================================================== Dashboard Charts and Knobs ==========================================================================
    public function data(Request $request){
        
        $getDate = $request->input('getDate');
        $element = $request->input('element');


        $date = date_create($getDate);
        $month = date_format($date,"m") - 1;
        $day = date_format($date,"d");
        $year = date_format($date,"Y");
        $lday=cal_days_in_month(CAL_GREGORIAN,$month,$year);

        try{
            DB::enableQueryLog();
            $query="";
            $pdoparameter=[];
            if($element=="vrHourChart"){ //********************************* VR Hourly Chart ************************
                $query = "SELECT Hrs+1 as hour, sum(Amount) as amount FROM `VRData` WHERE transdate = :getDate GROUP BY Hrs";
                $pdoparameter=['getDate' => $getDate];
            }
            elseif ($element=="smsWeekChart") { //************************* SMS Weekly Chart *******************
                $query = "SELECT transdate as hour, SUM(sms_rate) as amount FROM `SMSData` GROUP by transdate ORDER by transdate DESC LIMIT 7";
            }
            elseif ($element=="pgwHourChart") { //************************* PGW Hourly Chart ************************
                $query = "SELECT Hrs+1 as hour, sum(mamount) as amount FROM `PGWData` WHERE transdate = :getDate GROUP BY Hrs";
                $pdoparameter=['getDate' => $getDate];
            }
            elseif ($element=="vrTargetChart"){ //***************************** VR Knob *******************************
                $query = "SELECT (SELECT sum(Amount) FROM AK_VR_Daily WHERE transdate BETWEEN DATE_FORMAT(:getDate ,'%Y-%m-01') AND :getDate1 ) as currentMonth , (SELECT sum(Amount) FROM AK_VR_Daily WHERE year(transdate)=year(:getDate2 - INTERVAL 1 month) and month(transdate)=month(:getDate3 - INTERVAL 1 month) ) as lastMonth, (SELECT SUM(Amount) FROM AK_VR_Daily WHERE transdate = :getDate4) as today FROM `AK_VR_Daily` LIMIT 1";
                $pdoparameter=['getDate' => $getDate,'getDate1' => $getDate,'getDate2' => $getDate,'getDate3' => $getDate,'getDate4' => $getDate];
            }
            elseif ($element=="smsTargetChart") { //*************************** SMS Knob *******************************
                $query = "SELECT transdate as tdate , (SELECT sum(sms_rate) FROM SMSData WHERE transdate BETWEEN DATE_FORMAT(:getDate ,'%Y-%m-01') AND :getDate1 ) as currentMonth , (SELECT sum(sms_rate) FROM SMSData WHERE year(transdate)=year(:getDate2 - INTERVAL 1 month) and month(transdate)=month(:getDate3 - INTERVAL 1 month) ) as lastMonth, (SELECT SUM(sms_rate) FROM SMSData WHERE transdate = :getDate4) as today FROM `SMSData` LIMIT 1";
                $pdoparameter=['getDate' => $getDate,'getDate1' => $getDate,'getDate2' => $getDate,'getDate3' => $getDate,'getDate4' => $getDate];
            }
            elseif ($element=="pgwTargetChart") { //**************************** PGW Knob ********************************
                $query = "SELECT transdate as tdate, (SELECT sum(mamount) FROM AK_PGW_Daily WHERE transdate BETWEEN DATE_FORMAT(:getDate ,'%Y-%m-01') AND :getDate1 ) as currentMonth , (SELECT sum(mamount) FROM AK_PGW_Daily WHERE year(transdate)=year(:getDate2 - INTERVAL 1 month) and month(transdate)=month(:getDate3 - INTERVAL 1 month) ) as lastMonth, (SELECT SUM(mamount) FROM AK_PGW_Daily WHERE transdate = :getDate4) as today FROM `AK_PGW_Daily` LIMIT 1";
                $pdoparameter=['getDate' => $getDate,'getDate1' => $getDate,'getDate2' => $getDate,'getDate3' => $getDate,'getDate4' => $getDate];
            }
            $result=DB::select($query,$pdoparameter);


            if($element=="vrHourChart" || $element=="smsWeekChart" || $element=="pgwHourChart"){
                $rows = array();
                $table = array();
                $table['cols'] = array(

                    array('label' => 'Hour', 'type' => 'string'),
                    array('label' => 'Amount', 'type' => 'number')

                );
                if($element=="smsWeekChart"){

                    foreach($result as $r){
                        $rows[] = array('c' => array(array('v' => (string) $r->{'hour'}),array('v' => (int) $r->{'amount'})));
                    }
                    $rows = array_reverse($rows);

                } else{
                    foreach($result as $r){
                        $rows[] = array('c' => array(array('v' => (string) $r->{'hour'}),array('v' => (int) $r->{'amount'})));
                    }
                }

                $table['rows'] = $rows;

                $jsonTable = json_encode($table);

                return $jsonTable;
            }
            else{
                $currentMonth = 0;
                $lastMonth = 0;
                $today = 0;

                foreach($result as $r) {
                    $currentMonth = $r->{'currentMonth'};
                    $lastMonth = $r->{'lastMonth'};
                    $today = $r->{'today'};
                }
//                dd([$currentMonth,$lastMonth,$today,$lday]);
                $lastMonthTAE = (int) ($lastMonth/$lday);
                if($today>$lastMonthTAE) $lastMonthTAE = 1;
                else $lastMonthTAE = 0;
                $temp = array( number_format(($currentMonth/1000000),2), number_format(($lastMonth/1000000),2), $lastMonthTAE, number_format((($lastMonth/$lday)/1000000),2) );
                return json_encode($temp);
            }

        }catch(Exception $e){
            \LOG::error($e-getMessage());
        }
    }


    //    ================================================================================= Drop Client =================================================
    public function  dropClient(Request $request){
        $getDate = $request->input('getDate');
        $element = $request->input('element');
        try {
            $result = array();
            $row = array();

            DB::enableQueryLog();

            if($element == "vrdropclient" || $element == "vrdropclientlist")
                $query = "SELECT   t.*, 
                          IF(t.active >= 30 AND t.AON > 30 AND t.Tsize >= 1000,10,
                          IF(t.active >= 24 AND t.AON BETWEEN 24 AND 29 AND t.Tsize <= 1000,8,
                          IF(t.active >= 24 AND t.AON BETWEEN 24 AND 29 AND t.Tsize >= 1001,9,
                          IF(t.active >= 16 AND t.AON BETWEEN 16 AND 24 AND t.Tsize >= 1001,7,
                          IF(t.active >= 16 AND t.AON BETWEEN 16 AND 24 AND t.Tsize <= 1000,6,
                          IF(t.active >= 8 AND t.AON BETWEEN 8 AND 15 AND t.Tsize >= 1001,5,
                          IF(t.active >= 8 AND t.AON BETWEEN 8 AND 15 AND t.Tsize <= 1000,4,
                          IF(t.active >= 3 AND t.AON BETWEEN 3 AND 7 AND t.Tsize >= 1001,3,
                          IF(t.active >= 3 AND t.AON BETWEEN 3 AND 7 AND t.Tsize <= 1000,2,
                          1))))))))) score
                        FROM 
                        (SELECT a.client_id client, b.st_date, b.en_date, DATE(:getDate) - b.st_date Active, a.AON, a.amount, a.Tsize
                        FROM 
                        (SELECT client_id, COUNT(DISTINCT transdate) AON,COUNT(1) COUNT, SUM(amount) amount, ROUND(SUM(amount)/COUNT(1),0) Tsize
                        FROM  AK_VR_Daily 
                        WHERE transdate BETWEEN (:getDate1 - INTERVAL 30 DAY) and :getDate2
                        GROUP BY client_id) a
                        LEFT JOIN AK_Agg_VR b ON a.client_id = b.st_client_id) t";

            if($element == "smsdropclient" || $element == "smsdropclientlist")
                $query = "SELECT   t.*, 
                          IF(t.active >= 30 AND t.AON > 30 AND t.Tsize >= 1000,10,
                          IF(t.active >= 24 AND t.AON BETWEEN 24 AND 29 AND t.Tsize <= 1000,8,
                          IF(t.active >= 24 AND t.AON BETWEEN 24 AND 29 AND t.Tsize >= 1001,9,
                          IF(t.active >= 16 AND t.AON BETWEEN 16 AND 24 AND t.Tsize >= 1001,7,
                          IF(t.active >= 16 AND t.AON BETWEEN 16 AND 24 AND t.Tsize <= 1000,6,
                          IF(t.active >= 8 AND t.AON BETWEEN 8 AND 15 AND t.Tsize >= 1001,5,
                          IF(t.active >= 8 AND t.AON BETWEEN 8 AND 15 AND t.Tsize <= 1000,4,
                          IF(t.active >= 3 AND t.AON BETWEEN 3 AND 7 AND t.Tsize >= 1001,3,
                          IF(t.active >= 3 AND t.AON BETWEEN 3 AND 7 AND t.Tsize <= 1000,2,
                          1))))))))) score
                        FROM 
                        (SELECT a.Stakeholder client, b.st_date, b.en_date, DATE(:getDate) - b.st_date Active, a.AON, a.amount, a.Tsize
                        FROM 
                        (SELECT Stakeholder, COUNT(DISTINCT transdate) AON,COUNT(1) COUNT, SUM(amount) amount, ROUND(SUM(amount)/COUNT(1),0) Tsize
                        FROM  SMSData 
                        WHERE transdate BETWEEN (:getDate1 - INTERVAL 30 DAY) and :getDate2
                        GROUP BY Stakeholder) a
                        LEFT JOIN AK_Agg_SMS b ON a.Stakeholder = b.st_Stakeholder) t";

            if($element == "pgwdropclient" || $element == "pgwdropclientlist")
                $query = "SELECT   t.*, 
                          IF(t.active >= 30 AND t.AON > 30 AND t.Tsize >= 1000,10,
                          IF(t.active >= 24 AND t.AON BETWEEN 24 AND 29 AND t.Tsize <= 1000,8,
                          IF(t.active >= 24 AND t.AON BETWEEN 24 AND 29 AND t.Tsize >= 1001,9,
                          IF(t.active >= 16 AND t.AON BETWEEN 16 AND 24 AND t.Tsize >= 1001,7,
                          IF(t.active >= 16 AND t.AON BETWEEN 16 AND 24 AND t.Tsize <= 1000,6,
                          IF(t.active >= 8 AND t.AON BETWEEN 8 AND 15 AND t.Tsize >= 1001,5,
                          IF(t.active >= 8 AND t.AON BETWEEN 8 AND 15 AND t.Tsize <= 1000,4,
                          IF(t.active >= 3 AND t.AON BETWEEN 3 AND 7 AND t.Tsize >= 1001,3,
                          IF(t.active >= 3 AND t.AON BETWEEN 3 AND 7 AND t.Tsize <= 1000,2,
                          1))))))))) score
                        FROM 
                        (SELECT a.strid client, b.st_date, b.en_date, DATE(:getDate) - b.st_date Active, a.AON, a.Amount, a.bank, a.sslpart, a.Tsize
                        FROM 
                        (SELECT strid, COUNT(DISTINCT transdate) AON,COUNT(1) COUNT, SUM(mamount) amount, SUM(bankportion) bank, SUM(sslportion) SSLpart, ROUND(SUM(mamount)/COUNT(1),0) Tsize
                        FROM  AK_PGW_Daily 
                        WHERE transdate BETWEEN (:getDate1 - INTERVAL 30 DAY) and :getDate2
                        GROUP BY strid) a
                        LEFT JOIN AK_Agg_PGW b ON a.strid = b.st_strid) t";

            $result = DB::select($query, ['getDate' => $getDate, 'getDate1' => $getDate, 'getDate2' => $getDate]);

//            usort($result, function($a, $b) {
//                return $b->{'score'} <=> $a->{'score'};
//            });
//            echo date('Y-m-d',(strtotime ( '-0 day' , strtotime ( $getDate) ) ));
            $countDropClient = 0;
            $rowCount = count($result);

            foreach ($result as $r){
//                dd(json_encode($r));
                if($r->{'score'}==10){
                    if( Date($r->{'en_date'}) < date('Y-m-d',(strtotime ( '-0 day' , strtotime ( $getDate) ) )) ){
                        $countDropClient++;
//                        $row[] = $r->get() ;
                        $row[] = $r;
                    }
                }
                elseif($r->{'score'}==9){
                    if( Date($r->{'en_date'}) < date('Y-m-d',(strtotime ( '-6 day' , strtotime ( $getDate) ) )) ){
                        $countDropClient++;
                        $row[] = $r;
//                        $row[] = [ 'strid' => $r->{'strid'}, 'amount' => $r->{'Amount'} ];
                    }
                }
                elseif($r->{'score'}==8){
                    if( Date($r->{'en_date'}) < date('Y-m-d',(strtotime ( '-6 day' , strtotime ( $getDate) ) )) ){
                        $countDropClient++;
                        $row[] = $r;
//                        $row[] = [ 'strid' => $r->{'strid'}, 'amount' => $r->{'Amount'} ];
                    }
                }
                elseif($r->{'score'}==7){
                    if( Date($r->{'en_date'}) < date('Y-m-d',(strtotime ( '-14 day' , strtotime ( $getDate) ) )) ){
                        $countDropClient++;
                        $row[] = $r;
//                        $row[] = [ 'strid' => $r->{'strid'}, 'amount' => $r->{'Amount'} ];
                    }
                }
                elseif($r->{'score'}==6){
                    if( Date($r->{'en_date'}) < date('Y-m-d',(strtotime ( '-14 day' , strtotime ( $getDate) ) )) ){
                        $countDropClient++;
                        $row[] = $r;
//                        $row[] = [ 'strid' => $r->{'strid'}, 'amount' => $r->{'Amount'} ];
                    }
                }
                elseif($r->{'score'}==5){
                    if( Date($r->{'en_date'}) < date('Y-m-d',(strtotime ( '-22 day' , strtotime ( $getDate) ) )) ){
                        $countDropClient++;
                        $row[] = $r;
//                        $row[] = [ 'strid' => $r->{'strid'}, 'amount' => $r->{'Amount'} ];
                    }
                }
                elseif($r->{'score'}==4){
                    if( Date($r->{'en_date'}) < date('Y-m-d',(strtotime ( '-22 day' , strtotime ( $getDate) ) )) ){
                        $countDropClient++;
                        $row[] = $r;
//                        $row[] = [ 'strid' => $r->{'strid'}, 'amount' => $r->{'Amount'} ];
                    }
                }
                elseif($r->{'score'}==3){
                    if( Date($r->{'en_date'}) < date('Y-m-d',(strtotime ( '-27 day' , strtotime ( $getDate) ) )) ){
                        $countDropClient++;
                        $row[] = $r;
//                        $row[] = [ 'strid' => $r->{'strid'}, 'amount' => $r->{'Amount'} ];
                    }
                }
                elseif($r->{'score'}==2){
                    if( Date($r->{'en_date'}) < date('Y-m-d',(strtotime ( '-27 day' , strtotime ( $getDate) ) )) ){
                        $countDropClient++;
                        $row[] = $r;
//                        $row[] = [ 'strid' => $r->{'strid'}, 'amount' => $r->{'Amount'} ];
                    }
                }
                elseif($r->{'score'}==1){
                    if( Date($r->{'en_date'}) < date('Y-m-d',(strtotime ( '-29 day' , strtotime ( $getDate) ) )) ){
                        $countDropClient++;
                        $row[] = $r;
//                        $row[] = [ 'strid' => $r->{'strid'}, 'amount' => $r->{'Amount'} ];
                    }
                }
            }

            if($element == "pgwdropclientlist" || $element == "smsdropclientlist"|| $element == "vrdropclientlist") return json_encode($row);

            return json_encode(['totalCount' => $rowCount, 'dropCount' => $countDropClient]);
//
        }catch(Exception $e){
            \LOG::error($e-getMessage());
        }
    }
}
