<?php

namespace App\Http\Controllers;

use App\Bank;
use Illuminate\Http\Request;
use DB;
use App\Log;
class PGWChartController extends Controller
{
    use Log;
    public function index(){
        $this->log('PGW Chart','PGW Chart ','PGW');
        $data['title']="PGW Transaction";
        DB::enableQueryLog();
        $year = DB::select("SELECT DISTINCT(year(transdate)) as year FROM AK_PGW_Daily");
        $year_options = [];
        foreach ($year as $y){
            $year_options[$y->{'year'}]=$y->{'year'};
        }
        $data['year_options'] = $year_options;
        return view('pgw.pgwchart',compact('data'));
    }

    //.......................JSON Data For Top Ten Kam in PGW................................
//    public function PGWTopTenKamChart(){
//        try{
//            $query = "SELECT PGWMaster.KAM as KAM, SUM(PGWData.PGW_rate) as Amount FROM PGWData JOIN PGWMaster where PGWMaster.stakeholder=PGWData.Stakeholder and year(PGWData.transdate)=year(CURDATE()-interval 1 day) and month(PGWData.transdate)=month(CURDATE()-interval 1 day) GROUP by PGWMaster.KAM ORDER by amount DESC LIMIT 10";
//            DB::enableQueryLog();
//            $result=DB::select($query);
//            $rows = array();
//            $table = array();
//            $table['cols'] = array(
//
//                array('label' => 'Client Id', 'type' => 'string'),
//                array('label' => 'Total Amount', 'type' => 'number'),
//                array('label' => 'Total Amount', 'type' => 'number')
//
//            );
//            /* Extract the information from $result */
//            foreach($result as $r)
//                $rows[] = array('c' => array(
//                    array('v' => (string) $r->{'KAM'}),
//                    array('v' => (int) $r->{'Amount'}),
//                    array('v' => ((int) $r->{'Amount'})/1000000)
//                ));
//
//
//
//            $table['rows'] = $rows;
//
//            $jsonTable = json_encode($table);
//
//            return $jsonTable;
//        }catch(Exception $e){
//            \LOG::error($e-getMessage());
//        }
//    }

    //.......................JSON Data For Top Ten Company in PGW................................
    public function PGWTopTenCompanyChart(){
        try{
            $query = "SELECT pgwMaster.company_name, SUM(AK_PGW_Daily.mamount) as amount FROM AK_PGW_Daily JOIN pgwMaster where pgwMaster.strid=AK_PGW_Daily.strid and year(AK_PGW_Daily.transdate)=year(CURDATE()-interval 1 day) and month(AK_PGW_Daily.transdate)=month(CURDATE()-interval 1 day) GROUP by pgwMaster.company_name ORDER by amount DESC LIMIT 10";
            DB::enableQueryLog();
            $result=DB::select($query);
            $rows = array();
            $table = array();
            $table['cols'] = array(

                array('label' => 'Company', 'type' => 'string'),
                array('label' => 'Total Amount', 'type' => 'number'),
                array('label' => 'Total Amount', 'type' => 'number')

            );
            /* Extract the information from $result */
            foreach($result as $r)
                $rows[] = array('c' => array(
                    array('v' => (string) $r->{'company_name'}),
                    array('v' => (int) $r->{'amount'}),
                    array('v' => ((int) $r->{'amount'})/1000000)
                ));

            $table['rows'] = $rows;

            $jsonTable = json_encode($table);

            return $jsonTable;
        }catch(Exception $e){
            \LOG::error($e-getMessage());
        }
    }

    //.......................JSON Data For Top Ten Company in PGW................................
    public function PGWAssumptionChart(){
        try{
            $query = "SELECT transdate a, weekday(transdate) weekday, sum(mamount) amount, (SELECT sum(mamount) FROM AK_PGW_Daily WHERE transdate = (a - INTERVAL 1 day)) priviousday , (100 + ( 100 * ((SELECT sum(mamount) FROM AK_PGW_Daily WHERE transdate = (a - INTERVAL 7 day)) - (SELECT sum(mamount) FROM AK_PGW_Daily WHERE transdate = (a - INTERVAL 8 day))) / (SELECT sum(mamount) FROM AK_PGW_Daily WHERE transdate = (a - INTERVAL 8 day)) ) ) per FROM AK_PGW_Daily WHERE transdate > (SELECT max(transdate) - INTERVAL 7 day FROM AK_PGW_Daily) GROUP by transdate"assumptionassumptionassumptionassumptionassumptionassumptionassumptionassumptionassumptionassumptionassumptionassumptionassumptionassumptionassumptionassumptionassumptionassumptionassumption;
            DB::enableQueryLog();
            $result=DB::select($query);
            $weekdayper = [];
            $lastDate = "";
            $lastAmount = 0;
            $lastweekday = "";
            foreach($result as $r){
                $weekdayper[$r->{'weekday'}] = $r->{'per'};
                $lastDate = $r->{'a'};
                $lastAmount = $r->{'amount'};
                $lastweekday = $r->{'weekday'};
            }

            $date = date_create($lastDate);
            $cday = date_format($date,"d");
            $month = date_format($date,"m") - 1;
            $year = date_format($date,"Y");
            $lday=cal_days_in_month(CAL_GREGORIAN,$month,$year);

            $rows = array();
            $table = array();
            $table['cols'] = array(

                array('label' => 'Company', 'type' => 'string'),
                array('label' => 'Total Amount', 'type' => 'number'),
                array('label' => 'Total Amount', 'type' => 'number')

            );
            for($i=$cday+1;$i<=$lday;$i++){
                $lastweekday++;
                $lastweekday%=7;
                $lastAmount = ($lastAmount/100)*$weekdayper[$lastweekday];
                $rows[] = array('c' => array(
                    array('v' => (string) $i),
                    array('v' => (int) $lastAmount),
                    array('v' => ((int) $lastAmount)/1000000)
                ));
            }

            $table['rows'] = $rows;

            $jsonTable = json_encode($table);

            return $jsonTable;
        }catch(Exception $e){
            \LOG::error($e-getMessage());
        }
    }

    //.......................JSON Data For Bank wise Chart for current month donut chart in PGW................................
    public function PGWBankChart(){
        try{
            $query = "SELECT SUM(AK_PGW_Daily.mamount) as amount, bank.bname FROM AK_PGW_Daily JOIN bank where year(AK_PGW_Daily.transdate)= year(CURDATE() - INTERVAL 1 day) and month(AK_PGW_Daily.transdate)= month(CURDATE() - INTERVAL 1 day) and bank.bid=AK_PGW_Daily.bid group by bank.bname";
            DB::enableQueryLog();
            $result=DB::select($query);
            $rows = array();
            $table = array();
            $table['cols'] = array(
                array('label' => 'Bank Name', 'type' => 'string'),
                array('label' => 'Total Amount', 'type' => 'number')
            );
            /* Extract the information from $result */
            foreach($result as $r)
                $rows[] = array('c' => array(array('v' => (string) $r->{'bname'}),array('v' => (int) $r->{'amount'})));

            $table['rows'] = $rows;

            $jsonTable = json_encode($table);

            return $jsonTable;
        }catch(Exception $e){
            \LOG::error($e-getMessage());
        }
    }

    //.......................JSON Data For Card Type wise chart in PGW................................
    public function PGWCardTypeChart(){
        try{
            $query = "SELECT AK_Card_Master.Card id, sum(mamount) as amount FROM AK_Card_Master join AK_PGW_Daily on AK_Card_Master.Card_type=AK_PGW_Daily.cardtype WHERE year(AK_PGW_Daily.transdate)= year(CURDATE() - INTERVAL 1 day) and month(AK_PGW_Daily.transdate)= month(CURDATE() - INTERVAL 1 day) GROUP by id";
            DB::enableQueryLog();
            $result=DB::select($query);
            $rows = array();
            $table = array();
            $table['cols'] = array(

                array('label' => 'Card Type', 'type' => 'string'),
                array('label' => 'Total Amount', 'type' => 'number')

            );
            /* Extract the information from $result */
            foreach($result as $r) {
                $rows[] = array('c' => array(array('v' => (string)$r->{'id'}), array('v' => (int)$r->{'amount'})));
            }

            $table['rows'] = $rows;
            $jsonTable = json_encode($table);

            return $jsonTable;
        }catch(Exception $e){
            \LOG::error($e-getMessage());
        }
    }

    //.......................JSON Data For PGW Stakeholder who are mostly impact in negatively or positively................................
    public function PGWImpactClientChart(Request $request){
        try{
            $fromDate=$request->input('fromDate');
            $toDate=$request->input('toDate');
            $operation=$request->input('operation');


            $resultToquery="";$resultFromquery="";$resultMid1query="";$resultMid2query="";
            $totalAmountDiff=0;$amountDiff=0;$others=0;
            $resultTopdoparameter=[];
            $resultFrompdoparameter=[];
            $resultMid1pdoparameter=[];
            $resultMid2pdoparameter=[];
            DB::enableQueryLog();
            if($fromDate!=null && $toDate!=null && $operation=="day"){
                $resultToquery="SELECT  AK_PGW_Daily.transdate id, sum(AK_PGW_Daily.mamount) amount FROM `AK_PGW_Daily` WHERE AK_PGW_Daily.transdate=:toDate  group by id";
                $resultTopdoparameter=['toDate'=>$toDate];
                $resultFromquery="SELECT AK_PGW_Daily.transdate id, sum(AK_PGW_Daily.mamount) amount FROM `AK_PGW_Daily` WHERE AK_PGW_Daily.transdate=(:fromDate)  group by id";
                $resultFrompdoparameter=['fromDate'=>$fromDate];
                $resultMid1query="SELECT ifnull(k.c1,k.c2) id,ifnull(k.m1,0)-ifnull(k.m2,0) amount from (SELECT * from (SELECT AK_PGW_Daily.strid c1,sum(mamount) m1 from AK_PGW_Daily where transdate =:toDate1 GROUP BY AK_PGW_Daily.strid) b left JOIN (SELECT AK_PGW_Daily.strid c2,sum(mamount) m2 from AK_PGW_Daily where transdate =(:fromDate) GROUP BY AK_PGW_Daily.strid) a on a.c2=b.c1 UNION ALL SELECT * from (SELECT AK_PGW_Daily.strid c1,sum(mamount) m1 from AK_PGW_Daily where transdate =:toDate GROUP BY AK_PGW_Daily.strid) b RIGHT JOIN (SELECT AK_PGW_Daily.strid c2,sum(mamount) m2 from AK_PGW_Daily where transdate =(:fromDate1) GROUP BY AK_PGW_Daily.strid) a on a.c2=b.c1 where b.c1 is null) k ORDER by amount DESC LIMIT 4";
                $resultMid1pdoparameter=['toDate'=>$toDate,'toDate1'=>$toDate,'fromDate'=>$fromDate,'fromDate1'=>$fromDate];
                $resultMid2query="SELECT ifnull(k.c1,k.c2) id,ifnull(k.m1,0)-ifnull(k.m2,0) amount from (SELECT * from (SELECT AK_PGW_Daily.strid c1,sum(mamount) m1 from AK_PGW_Daily where transdate =:toDate GROUP BY AK_PGW_Daily.strid) b left JOIN (SELECT AK_PGW_Daily.strid c2,sum(mamount) m2 from AK_PGW_Daily where transdate =(:fromDate ) GROUP BY AK_PGW_Daily.strid) a on a.c2=b.c1 UNION ALL SELECT * from (SELECT AK_PGW_Daily.strid c1,sum(mamount) m1 from AK_PGW_Daily where transdate =:toDate1 GROUP BY AK_PGW_Daily.strid) b RIGHT JOIN (SELECT AK_PGW_Daily.strid c2,sum(mamount) m2 from AK_PGW_Daily where transdate =(:fromDate1 ) GROUP BY AK_PGW_Daily.strid) a on a.c2=b.c1 where b.c1 is null) k ORDER by amount ASC LIMIT 4";
                $resultMid2pdoparameter=['toDate'=>$toDate,'toDate1'=>$toDate,'fromDate'=>$fromDate,'fromDate1'=>$fromDate];

            }
            elseif($fromDate!=null && $toDate!=null && $operation=="month"){
                $resultToquery="SELECT  month(AK_PGW_Daily.transdate) id, sum(AK_PGW_Daily.mamount) amount FROM `AK_PGW_Daily` WHERE year(transdate) =year(:toDate1) and month(transdate) =month(:toDate) group by id";
                $resultTopdoparameter=['toDate'=>$toDate,'toDate1'=>$toDate];
                $resultFromquery="SELECT month(AK_PGW_Daily.transdate) id, sum(AK_PGW_Daily.mamount) amount FROM `AK_PGW_Daily` WHERE year(transdate) =year(:fromDate ) and month(transdate) =month(:fromDate1 ) group by id";
                $resultFrompdoparameter=['fromDate'=>$fromDate,'fromDate1'=>$fromDate];
                $resultMid1query="SELECT ifnull(k.c1,k.c2) id,ifnull(k.m1,0)-ifnull(k.m2,0) amount from (SELECT * from (SELECT AK_PGW_Daily.strid c1,sum(mamount) m1 from AK_PGW_Daily where year(transdate) =year(:toDate) and month(transdate) =month(:toDate1) GROUP BY AK_PGW_Daily.strid) b left JOIN (SELECT AK_PGW_Daily.strid c2,sum(mamount) m2 from AK_PGW_Daily where year(transdate) =year(:fromDate ) and month(transdate) =month(:fromDate1 ) GROUP BY AK_PGW_Daily.strid) a on a.c2=b.c1 UNION ALL SELECT * from (SELECT AK_PGW_Daily.strid c1,sum(mamount) m1 from AK_PGW_Daily where year(transdate) =year(:toDate2) and month(transdate) =month(:toDate3) GROUP BY AK_PGW_Daily.strid) b RIGHT JOIN (SELECT AK_PGW_Daily.strid c2,sum(mamount) m2 from AK_PGW_Daily where year(transdate) =year(:fromDate2 ) and month(transdate) =month(:fromDate3 ) GROUP BY AK_PGW_Daily.strid) a on a.c2=b.c1 where b.c1 is null) k ORDER by amount DESC LIMIT 4";
                $resultMid1pdoparameter=['toDate'=>$toDate,'toDate1'=>$toDate,'toDate2'=>$toDate,'toDate3'=>$toDate,'fromDate'=>$fromDate,'fromDate1'=>$fromDate,'fromDate2'=>$fromDate,'fromDate3'=>$fromDate];
                $resultMid2query="SELECT ifnull(k.c1,k.c2) id,ifnull(k.m1,0)-ifnull(k.m2,0) amount from (SELECT * from (SELECT AK_PGW_Daily.strid c1,sum(mamount) m1 from AK_PGW_Daily where year(transdate) =year(:toDate) and month(transdate) =month(:toDate1) GROUP BY AK_PGW_Daily.strid) b left JOIN (SELECT AK_PGW_Daily.strid c2,sum(mamount) m2 from AK_PGW_Daily where year(transdate) =year(:fromDate2 ) and month(transdate) =month(:fromDate3 ) GROUP BY AK_PGW_Daily.strid) a on a.c2=b.c1 UNION ALL SELECT * from (SELECT AK_PGW_Daily.strid c1,sum(mamount) m1 from AK_PGW_Daily where year(transdate) =year(:toDate2) and month(transdate) =month(:toDate3) GROUP BY AK_PGW_Daily.strid) b RIGHT JOIN (SELECT AK_PGW_Daily.strid c2,sum(mamount) m2 from AK_PGW_Daily where year(transdate) =year(:fromDate ) and month(transdate) =month(:fromDate1 ) GROUP BY AK_PGW_Daily.strid) a on a.c2=b.c1 where b.c1 is null) k ORDER by amount ASC LIMIT 4";
                $resultMid2pdoparameter=['toDate'=>$toDate,'toDate1'=>$toDate,'toDate2'=>$toDate,'toDate3'=>$toDate,'fromDate'=>$fromDate,'fromDate1'=>$fromDate,'fromDate2'=>$fromDate,'fromDate3'=>$fromDate];

            }
            elseif($fromDate!=null && $toDate!=null && $operation=="year"){
                $resultToquery="SELECT  year(AK_PGW_Daily.transdate) id, sum(AK_PGW_Daily.mamount) amount FROM `AK_PGW_Daily` WHERE year(transdate) =year(:toDate) group by id";
                $resultTopdoparameter=['toDate'=>$toDate];
                $resultFromquery="SELECT year(AK_PGW_Daily.transdate) id, sum(AK_PGW_Daily.mamount) amount FROM `AK_PGW_Daily` WHERE year(transdate) =year(:fromDate) group by id";
                $resultFrompdoparameter=['fromDate'=>$fromDate];
                $resultMid1query="SELECT ifnull(k.c1,k.c2) id,ifnull(k.m1,0)-ifnull(k.m2,0) amount from (SELECT * from (SELECT AK_PGW_Daily.strid c1,sum(mamount) m1 from AK_PGW_Daily where year(transdate) =year(:toDate) GROUP BY AK_PGW_Daily.strid) b left JOIN (SELECT AK_PGW_Daily.strid c2,sum(mamount) m2 from AK_PGW_Daily where year(transdate) =year(:fromDate) GROUP BY AK_PGW_Daily.strid) a on a.c2=b.c1 UNION ALL SELECT * from (SELECT AK_PGW_Daily.strid c1,sum(mamount) m1 from AK_PGW_Daily where year(transdate) =year(:toDate1)  GROUP BY AK_PGW_Daily.strid) b RIGHT JOIN (SELECT AK_PGW_Daily.strid c2,sum(mamount) m2 from AK_PGW_Daily where year(transdate) =year(:fromDate1)  GROUP BY AK_PGW_Daily.strid) a on a.c2=b.c1 where b.c1 is null) k ORDER by amount DESC LIMIT 4";
                $resultMid1pdoparameter=['toDate'=>$toDate,'toDate1'=>$toDate,'fromDate'=>$fromDate,'fromDate1'=>$fromDate];
                $resultMid2query="SELECT ifnull(k.c1,k.c2) id,ifnull(k.m1,0)-ifnull(k.m2,0) amount from (SELECT * from (SELECT AK_PGW_Daily.strid c1,sum(mamount) m1 from AK_PGW_Daily where year(transdate) =year(:toDate) GROUP BY AK_PGW_Daily.strid) b left JOIN (SELECT AK_PGW_Daily.strid c2,sum(mamount) m2 from AK_PGW_Daily where year(transdate) =year(:fromDate1) GROUP BY AK_PGW_Daily.strid) a on a.c2=b.c1 UNION ALL SELECT * from (SELECT AK_PGW_Daily.strid c1,sum(mamount) m1 from AK_PGW_Daily where year(transdate) =year(:toDate1)  GROUP BY AK_PGW_Daily.strid) b RIGHT JOIN (SELECT AK_PGW_Daily.strid c2,sum(mamount) m2 from AK_PGW_Daily where year(transdate) =year(:fromDate)  GROUP BY AK_PGW_Daily.strid) a on a.c2=b.c1 where b.c1 is null) k ORDER by amount ASC LIMIT 4";
                $resultMid2pdoparameter=['toDate'=>$toDate,'toDate1'=>$toDate,'fromDate'=>$fromDate,'fromDate1'=>$fromDate];
            }
            $resultTo=DB::select($resultToquery,$resultTopdoparameter);
            $resultFrom=DB::select($resultFromquery,$resultFrompdoparameter);
            $resultMid1=DB::select($resultMid1query,$resultMid1pdoparameter);
            $resultMid2=DB::select($resultMid2query,$resultMid2pdoparameter);

            $rows = array();
            $table = array();

//            dd($resultTo);

            $table['cols'] = array(
                array('label' => 'id', 'type' => 'string'),
                array('label' => 'bottom', 'type' => 'number'),
                array('label' => 'bottom1', 'type' => 'number'),
                array('label' => 'top', 'type' => 'number'),
                array('label' => 'top1', 'type' => 'number'),
                array('role' => 'style', 'type' => 'string'),
                array('role' => 'tooltip', 'type' => 'string', 'p' => array('html' => true))
            );
            $top=0;$firstdate="";$firstamount="";
            $lastdate="";$lastamount="";
            foreach ($resultTo as $result) {
                $totalAmountDiff= (int) $result->{'amount'};
                $firstdate=$result->{'id'};
                if($operation=="month")$firstdate=$result->{'id'}.", ".date('Y', strtotime($toDate));
                $firstamount= (int) $result->{'amount'};
            }
            foreach ($resultFrom as $result) {
                $lastamount= (int) $result->{'amount'};
                $totalAmountDiff-= (int) $result->{'amount'};
                $lastdate=$result->{'id'};
                if($operation=="month")$lastdate=$result->{'id'}.", ".date('Y', strtotime($fromDate));

                $rows[] = array('c' => array(
                    array('v' => (string) $lastdate),
                    array('v' => (double) $top),
                    array('v' => (double) $top),
                    array('v' => (double) $result->{'amount'}+$top),
                    array('v' => (double) $result->{'amount'}+$top),
                    array('v' => (string) "#2ea9b2"),
                    array('v' => (string) "<div style=\"text-align:left;min-width: 220px; padding-left: 10px;\">".$lastdate." Amount: <b>".number_format( (int) $result->{'amount'}+$top)."</b></div>")
                ));
                $top+= (int) $result->{'amount'};
            }
            $sortArray = [];
            foreach ($resultMid1 as $result) {
                $sortArray[] = array(
                    'id' => (string) $result->{'id'},
                    'amount' => (double) $result->{'amount'},
                    'color' => (string) "#2ea232"
                );
                $amountDiff+=$result->{'amount'};

            }
            foreach ($resultMid2 as $result) {
                $sortArray[] = array(
                    'id' => (string) $result->{'id'},
                    'amount' => (double) $result->{'amount'},
                    'color' => (string) "#fe0b1b"
                );
                $amountDiff+=$result->{'amount'};

            }
            $sortArray[] = array(
                'id' => (string) "Others",
                'amount' => (double) $totalAmountDiff-$amountDiff,
                'color' => (string) "#f39c12"
            );
            usort($sortArray, function($a, $b) {return $b['amount'] <=> $a['amount'];});

            foreach ($sortArray as $value){
                $resultLastAmount="";
                $resultFirstAmount="";
                $tempfirstamount=0;
                $templastamount=0;
                $amount = $value['amount'];
                if($value['id']!='Others') {
                    if ($operation == "day") {
                        $queryLastAmount = "SELECT SUM(mamount) as amount FROM `AK_PGW_Daily` WHERE strid=:id and transdate=:fromDate";
                        $queryFirstAmount = "SELECT SUM(mamount) as amount FROM `AK_PGW_Daily` WHERE strid=:id and transdate=:toDate";
                        $resultLastAmount = DB::select($queryLastAmount, ['fromDate' => $fromDate, 'id' => $value['id']]);
                        $resultFirstAmount = DB::select($queryFirstAmount, ['toDate' => $toDate, 'id' => $value['id']]);
                    } elseif ($operation == "month") {
                        $queryLastAmount = "SELECT SUM(mamount) as amount FROM `AK_PGW_Daily` WHERE strid=:id and year(transdate)=year(:fromDate) and month(transdate)=month(:fromDate1)";
                        $queryFirstAmount = "SELECT SUM(mamount) as amount FROM `AK_PGW_Daily` WHERE strid=:id and year(transdate)=year(:toDate) and month(transdate)=month(:toDate1)";
                        $resultLastAmount = DB::select($queryLastAmount, ['fromDate' => $fromDate, 'fromDate1' => $fromDate, 'id' => $value['id']]);
                        $resultFirstAmount = DB::select($queryFirstAmount, ['toDate' => $toDate, 'toDate1' => $toDate, 'id' => $value['id']]);
                    } elseif ($operation == "year") {
                        $queryLastAmount = "SELECT SUM(mamount) as amount FROM `AK_PGW_Daily` WHERE strid=:id and year(transdate)=year(:fromDate)";
                        $queryFirstAmount = "SELECT SUM(mamount) as amount FROM `AK_PGW_Daily` WHERE strid=:id and year(transdate)=year(:toDate)";
                        $resultLastAmount = DB::select($queryLastAmount, ['fromDate' => $fromDate, 'id' => $value['id']]);
                        $resultFirstAmount = DB::select($queryFirstAmount, ['toDate' => $toDate, 'id' => $value['id']]);
                    }

                    foreach ($resultFirstAmount as $r) {
                        $tempfirstamount = (int) $r->{'amount'};
                    }
                    foreach ($resultLastAmount as $r) {
                        $templastamount = (int) $r->{'amount'};
                    }
                }else{
                    $tempfirstamount = (int) $top+$amount;
                    $templastamount = (int) $top;
                }
                $rows[] = array('c' => array(
                    array('v' => (string) $value['id']),
                    array('v' => (double) $top),
                    array('v' => (double) $top),
                    array('v' => (double) $amount+$top),
                    array('v' => (double) $amount+$top),
                    array('v' => (string) $value['color']),
                    array('v' => (string) "<div style=\"text-align:left;min-width: 220px;padding-left: 10px;\">strid: <b>".$value['id']."</b><br/>"
                        .$lastdate." amount: <b>".number_format($templastamount)."</b><br/>"
                        .$firstdate." amount: <b>".number_format($tempfirstamount)."</b><br/>"
                        ."Amount change: <b>".number_format($amount)."</b> (<b>".number_format((float)(($amount/$lastamount)*100), 2, '.', '')."%</b>)<br/></div>")
                ));
                $top+= (int) $amount;
            }


            $rows[] = array('c' => array(
                array('v' => (string) $firstdate),
                array('v' => (double) 0),
                array('v' => (double) 0),
                array('v' => (double) $firstamount),
                array('v' => (double) $firstamount),
                array('v' => (string) "#2ea9b2"),
                array('v' => (string) "<div style=\"text-align:left;min-width: 220px;padding-left: 10px;\">".$firstdate." Amount: <b>".number_format($firstamount)."</b> (<b>".number_format((float)((($firstamount/$lastamount)-1)*100), 2, '.', '')."%</b>)<br/></div>")
            ));

            $table['rows'] = $rows;

            // // convert data into JSON format
            $jsonTable = json_encode($table);
            return $jsonTable;
        }catch(Exception $e){
            \LOG::error($e-getMessage());
        }
    }


    //.......................JSON Data For  PGW Transaction count in PGW................................
    public function PGWTransactionCountChart(Request $request){
        try{
            $year=$request->input('year');
            $month=$request->input('month');
            $quarter=$request->input('quarter');
            $year=str_replace(',', '', $year);
            $query="";
            $pdoparameter=[];

            if($year==null && $quarter==null && $month==null){
                $query="SELECT YEAR(transdate) as id, sum(totalcnt) as amount FROM AK_PGW_Daily GROUP by id";
            }
            elseif ($year!=null && $quarter!=null && $month==null) {
                $query = "SELECT QUARTER(transdate) as id, sum(totalcnt) as amount FROM AK_PGW_Daily WHERE YEAR(transdate)=:year GROUP by id";
                $pdoparameter=['year'=>$year];
            }
            elseif ($year!=null && $quarter==null && $month==null) {
                $query = "SELECT MONTH(transdate) as id, sum(totalcnt) as amount FROM AK_PGW_Daily WHERE YEAR(transdate)=:year GROUP by id";
                $pdoparameter=['year'=>$year];
            }
            elseif ($year!=null && $quarter==null && $month!=null) {
                $query = "SELECT DAY(transdate) as id, sum(totalcnt) as amount FROM AK_PGW_Daily WHERE YEAR(transdate)=:year and MONTH(transdate)=:month GROUP by id";
                $pdoparameter=['year'=>$year, 'month'=>$month];
            }

            DB::enableQueryLog();
            $result=DB::select($query,$pdoparameter);

            $rows = array();
            $table = array();

            $table['cols'] = array(
                array('label' => 'id', 'type' => 'string'),
                array('label' => 'Count', 'type' => 'number'),
                array('label' => 'Count', 'type' => 'number'),
                array('role' => 'style', 'type' => 'string'),
            );

            foreach($result as $r)
                $rows[] = array('c' => array(
                    array('v' => (string) $r->{'id'}),
                    array('v' => (int) $r->{'amount'}),
                    array('v' => (int) $r->{'amount'}/1000),
                    array('v' => "#6EDDF9")
                ));
            if( $year != null && $month != null){
                $query3 = "SELECT COUNT(transdate) cnt FROM `AK_PGW_Daily` WHERE (SELECT year(max(transdate)) FROM AK_PGW_Daily) = :year and (SELECT month(max(transdate)) FROM AK_PGW_Daily) = :month";
                $result3=DB::select($query3, ['year'=>$year, 'month'=>$month]);
                if($result3[0]->{'cnt'}){
                    $query4 = "SELECT transdate a, weekday(transdate) weekday, sum(totalcnt) amount, (SELECT sum(totalcnt) FROM AK_PGW_Daily WHERE transdate = (a - INTERVAL 1 day)) priviousday , (100 + ( 100 * ((SELECT sum(totalcnt) FROM AK_PGW_Daily WHERE transdate = (a - INTERVAL 7 day)) - (SELECT sum(totalcnt) FROM AK_PGW_Daily WHERE transdate = (a - INTERVAL 8 day))) / (SELECT sum(totalcnt) FROM AK_PGW_Daily WHERE transdate = (a - INTERVAL 8 day)) ) ) per FROM AK_PGW_Daily WHERE transdate > (SELECT max(transdate) - INTERVAL 7 day FROM AK_PGW_Daily) GROUP by transdate";
                    DB::enableQueryLog();
                    $result4 = DB::select($query4);
                    $weekdayper = [];
                    $lastDate = "";
                    $lastAmount = 0;
                    $lastweekday = "";
                    foreach($result4 as $r4){
                        $weekdayper[$r4->{'weekday'}] = $r4->{'per'};
                        $lastDate = $r4->{'a'};
                        $lastAmount = $r4->{'amount'};
                        $lastweekday = $r4->{'weekday'};
                    }

                    $date = date_create($lastDate);
                    $cday = date_format($date,"d");
                    $month = date_format($date,"m") - 1;
                    $year = date_format($date,"Y");
                    $lday=cal_days_in_month(CAL_GREGORIAN,$month,$year);

                    $j = 0; $lastAmountStore = $lastAmount;
                    for($i=$cday+1;$i<=$lday;$i++){
                        if($j%7==0) $lastAmount = $lastAmountStore;
                        $j++;
                        $lastweekday++;
                        $lastweekday%=7;
                        $lastAmount = ($lastAmount/100)*$weekdayper[$lastweekday];
                        $rows[] = array('c' => array(
                            array('v' => (string) $i),
                            array('v' => (int) $lastAmount),
                            array('v' => ((int) $lastAmount)/1000),
                            array('v' => "#D7F7FE")
                        ));
                    }
                }
            }

            $table['rows'] = $rows;

            // convert data into JSON format
            $jsonTable = json_encode($table);
            return $jsonTable;
        }catch(Exception $e){
            \LOG::error($e-getMessage());
        }
    }


    //.......................JSON Data For PGW daily wise chart in PGW................................
    public function PGWDynamicChart(Request $request){
        $year=$request->input('year');
        $month=$request->input('month');
        $quarter=$request->input('quarter');
        $operator=$request->input('operatorName');
        $cardType=$request->input('cardType');
        $year=str_replace(',', '', $year);

        $result="";
        $result2="";
        DB::enableQueryLog();

        if($year==null && $quarter==null && $month==null){
            $query = "SELECT YEAR(transdate) as id, SUM(mamount) as amount FROM AK_PGW_Daily GROUP by id";
            $result=DB::select($query);
        }
        elseif ($year!=null && $quarter!=null && $month==null) {
            $query = "SELECT QUARTER(transdate) as id, SUM(mamount) as amount FROM AK_PGW_Daily WHERE YEAR(transdate)=:year GROUP by id";
            $result=DB::select($query, ['year'=>$year]);
        }
        elseif ($year!=null && $quarter==null && $month==null) {
            $query = "SELECT MONTH(transdate) as id, SUM(mamount) as amount FROM AK_PGW_Daily WHERE YEAR(transdate)=:year GROUP by id";
            $result=DB::select($query, ['year'=>$year]);
        }
        elseif ($year!=null && $quarter==null && $month!=null) {
            $query = "SELECT DAY(transdate) as id, SUM(mamount) as amount FROM AK_PGW_Daily WHERE YEAR(transdate)=:year and MONTH(transdate)=:month GROUP by id";
            $result=DB::select($query, ['year'=>$year,'month'=>$month]);
        }


        if($cardType!=null){
            if($year==null && $quarter==null && $month==null){
                $query2 = "SELECT YEAR(transdate) as id, SUM(mamount) as amount FROM AK_PGW_Daily left join AK_Card_Master on AK_PGW_Daily.cardtype = AK_Card_Master.Card_type WHERE AK_Card_Master.Card=:cardtype GROUP by id";
                $result2=DB::select($query2, ['cardtype'=>$cardType]);
            }
            elseif ($year!=null && $quarter!=null && $month==null) {
                $query2 = "SELECT QUARTER(transdate) as id, SUM(mamount) as amount FROM AK_PGW_Daily left join AK_Card_Master on AK_PGW_Daily.cardtype = AK_Card_Master.Card_type WHERE AK_Card_Master.Card=:cardtype and YEAR(transdate)=:year GROUP by id";
                $result2=DB::select($query2, ['year'=>$year,'cardtype'=>$cardType]);
            }
            elseif ($year!=null && $quarter==null && $month==null) {
                $query2 = "SELECT MONTH(transdate) as id, SUM(mamount) as amount FROM AK_PGW_Daily left join AK_Card_Master on AK_PGW_Daily.cardtype = AK_Card_Master.Card_type WHERE AK_Card_Master.Card=:cardtype and YEAR(transdate)=:year GROUP by id";
                $result2=DB::select($query2, ['year'=>$year,'cardtype'=>$cardType]);
            }
            elseif ($year!=null && $quarter==null && $month!=null) {
                $query2 = "SELECT DAY(transdate) as id, SUM(mamount) as amount FROM AK_PGW_Daily left join AK_Card_Master on AK_PGW_Daily.cardtype = AK_Card_Master.Card_type WHERE AK_Card_Master.Card=:cardtype and  YEAR(transdate)=:year and MONTH(transdate)=:month GROUP by id";
                $result2=DB::select($query2, ['year'=>$year, 'month'=>$month, 'cardtype'=>$cardType]);
            }
        }
        else if($operator=="Easy"){
            if($year==null && $quarter==null && $month==null){
                $query2 = "SELECT YEAR(f.transdate) id,sum(f.mamount) amount from ( select t.transdate,t.mamount from AK_PGW_Daily t inner join pgwMaster c on t.strid=c.strid WHERE c.easy!='0') f group by id";
                $result2=DB::select($query2);
            }
            elseif ($year!=null && $quarter!=null && $month==null) {
                $query2 = "SELECT QUARTER(f.transdate) id,sum(f.mamount) amount from ( select t.transdate,t.mamount from AK_PGW_Daily t inner join pgwMaster c on t.strid=c.strid WHERE c.easy!='0' and Year(t.transdate)=:year) f group by id ";
                $result2=DB::select($query2, ['year'=>$year]);
            }
            elseif ($year!=null && $quarter==null && $month==null) {
                $query2 = "SELECT Month(f.transdate) id,sum(f.mamount) amount from ( select t.transdate,t.mamount from AK_PGW_Daily t inner join pgwMaster c on t.strid=c.strid WHERE c.easy!='0' and Year(t.transdate)=:year) f group by id ";
                $result2=DB::select($query2, ['year'=>$year]);
            }
            elseif ($year!=null && $quarter==null && $month!=null) {
                $query2 = "SELECT day(f.transdate) id,sum(f.mamount) amount from ( select t.transdate,t.mamount from AK_PGW_Daily t inner join pgwMaster c on t.strid=c.strid WHERE c.easy!='0' and Year(t.transdate)=:year and Month(transdate)=:month) f group by id ";
                $result2=DB::select($query2, ['year'=>$year, 'month'=>$month]);
            }
        }
        else if ($operator=="Others"){
            if($year==null && $quarter==null && $month==null){
                $query2 = "SELECT YEAR(f.transdate) id,sum(f.mamount) amount from ( select t.transdate,t.mamount from AK_PGW_Daily t inner join pgwMaster c on t.strid=c.strid WHERE c.easy='0') f group by id";
                $result2=DB::select($query2);
            }
            elseif ($year!=null && $quarter!=null && $month==null) {
                $query2 = "SELECT QUARTER(f.transdate) id,sum(f.mamount) amount from ( select t.transdate,t.mamount from AK_PGW_Daily t inner join pgwMaster c on t.strid=c.strid WHERE c.easy='0' and Year(t.transdate)=:year) f group by id ";
                $result2=DB::select($query2, ['year'=>$year]);
            }
            elseif ($year!=null && $quarter==null && $month==null) {
                $query2 = "SELECT Month(f.transdate) id,sum(f.mamount) amount from ( select t.transdate,t.mamount from AK_PGW_Daily t inner join pgwMaster c on t.strid=c.strid WHERE c.easy='0' and Year(t.transdate)=:year) f group by id ";
                $result2=DB::select($query2, ['year'=>$year]);
            }
            elseif ($year!=null && $quarter==null && $month!=null) {
                $query2 = "SELECT day(f.transdate) id,sum(f.mamount) amount from ( select t.transdate,t.mamount from AK_PGW_Daily t inner join pgwMaster c on t.strid=c.strid WHERE c.easy='0' and Year(t.transdate)=:year and Month(transdate)=:month) f group by id ";
                $result2=DB::select($query2, ['year'=>$year, 'month'=>$month]);
            }

        }
        else {
            $test=Bank::all();
            $operatorList="";
            foreach ($test as $value) {
                $operatorList[$value->{'bname'}]=$value->{'bid'};
            }

            if(isset($operatorList[$operator]))
                $operator= (int)($operatorList[$operator]+'0');

            if($year==null && $quarter==null && $month==null && $operator!=null){
                $query2 = "SELECT YEAR(transdate) as id, SUM(mamount) as amount FROM AK_PGW_Daily WHERE bid=:bid GROUP by id";
                $result2=DB::select($query2, ['bid'=>$operator]);
            }
            elseif ($year!=null && $quarter!=null && $month==null && $operator!=null) {
                $query2 = "SELECT QUARTER(transdate) as id, SUM(mamount) as amount FROM AK_PGW_Daily WHERE bid=:bid and YEAR(transdate)=:year GROUP by id";
                $result2=DB::select($query2, ['year'=>$year, 'bid'=>$operator]);
            }
            elseif ($year!=null && $quarter==null && $month==null && $operator!=null) {
                $query2 = "SELECT MONTH(transdate) as id, SUM(mamount) as amount FROM AK_PGW_Daily WHERE  bid=:bid and YEAR(transdate)=:year GROUP by id";
                $result2=DB::select($query2, ['year'=>$year, 'bid'=>$operator]);
            }
            elseif ($year!=null && $quarter==null && $month!=null && $operator!=null) {
                $query2 = "SELECT DAY(transdate) as id, SUM(mamount) as amount FROM AK_PGW_Daily WHERE  bid=:bid and YEAR(transdate)=:year and MONTH(transdate)=:month GROUP by id";
                $result2=DB::select($query2, ['year'=>$year, 'month'=>$month, 'bid'=>$operator]);
            }
        }

        $masterTable = array();
        // $mTable = array();
        $rows = array();
        $table = array();
        $table2 = array();
        $rows2 = array();
        // print_r($result2);
        $table['cols'] = array(
            array('label' => 'id', 'type' => 'string'),
            array('label' => 'Total Amount', 'type' => 'number'),
            array('label' => 'hello', 'type' => 'number'),
            array('role' => 'style', 'type' => 'string')
        );

        foreach($result as $r) {
            $temp = array();
            $temp[] = array('v' => (string) $r->{'id'});
            $n = ((int) $r->{'amount'});
            $temp[] = array('v' => $n);
            $temp[] = array('v' => ($n/1000000));
            $temp[] = array('v' => "#FAC40F");
            $rows[] = array('c' => $temp);
        }
        if( $year != null && $month != null && $operator==null && $cardType==null){
            $query3 = "SELECT COUNT(transdate) cnt FROM `AK_PGW_Daily` WHERE (SELECT year(max(transdate)) FROM AK_PGW_Daily) = :year and (SELECT month(max(transdate)) FROM AK_PGW_Daily) = :month";
            $result3=DB::select($query3, ['year'=>$year, 'month'=>$month]);
            if($result3[0]->{'cnt'}){
                $query4 = "SELECT transdate a, weekday(transdate) weekday, sum(mamount) amount, (SELECT sum(mamount) FROM AK_PGW_Daily WHERE transdate = (a - INTERVAL 1 day)) priviousday , (100 + ( 100 * ((SELECT sum(mamount) FROM AK_PGW_Daily WHERE transdate = (a - INTERVAL 7 day)) - (SELECT sum(mamount) FROM AK_PGW_Daily WHERE transdate = (a - INTERVAL 8 day))) / (SELECT sum(mamount) FROM AK_PGW_Daily WHERE transdate = (a - INTERVAL 8 day)) ) ) per FROM AK_PGW_Daily WHERE transdate > (SELECT max(transdate) - INTERVAL 7 day FROM AK_PGW_Daily) GROUP by transdate";
                DB::enableQueryLog();
                $result4 = DB::select($query4);
                $weekdayper = [];
                $lastDate = "";
                $lastAmount = 0;
                $lastweekday = "";
                foreach($result4 as $r4){
                    $weekdayper[$r4->{'weekday'}] = $r4->{'per'};
                    $lastDate = $r4->{'a'};
                    $lastAmount = $r4->{'amount'};
                    $lastweekday = $r4->{'weekday'};
                }

                $date = date_create($lastDate);
                $cday = date_format($date,"d");
                $month = date_format($date,"m") - 1;
                $year = date_format($date,"Y");
                $lday=cal_days_in_month(CAL_GREGORIAN,$month,$year);

                $j = 0; $lastAmountStore = $lastAmount;
                for($i=$cday+1;$i<=$lday;$i++){
                    if($j%7==0) $lastAmount = $lastAmountStore;
                    $j++;
                    $lastweekday++;
                    $lastweekday%=7;
                    $lastAmount = ($lastAmount/100)*$weekdayper[$lastweekday];
                    $rows[] = array('c' => array(
                        array('v' => (string) $i),
                        array('v' => (int) $lastAmount),
                        array('v' => ((int) $lastAmount)/1000000),
                        array('v' => "#F9E79F")
                    ));
                }
            }
        }

        $table['rows'] = $rows;

        if($operator!=null || $cardType!=null){
            $table2['cols'] = array(
                array('label' => 'id', 'type' => 'string'),
                array('label' => $request->input('operatorName').$request->input('cardType').' Amount', 'type' => 'number'),
                array('label' => 'h', 'type' => 'number'),
            );

            foreach($result2 as $r) {
                $temp = array();
                $temp[] = array('v' => (string) $r->{'id'});
                $n = ((int) $r->{'amount'});

                $temp[] = array('v' => $n);
                $temp[] = array('v' => ($n/1000000));
                $rows2[] = array('c' => $temp);

            }
            $table2['rows'] = $rows2;
        }
        $masterTable['data'] = array(
            $table, $table2
        );


        // convert data into JSON format
        $jsonTable = json_encode($masterTable);
        return $jsonTable;

    }

    //.......................JSON Data For  PGW Transaction count in PGW................................
    public function PGWEasyChart(Request $request){
        try{
            $query = "SELECT SUM(AK_PGW_Daily.mamount) as amount, pgwMaster.easy as id FROM AK_PGW_Daily JOIN pgwMaster where year(AK_PGW_Daily.transdate)= year(CURDATE() - INTERVAL 1 day) and month(AK_PGW_Daily.transdate)= month(CURDATE() - INTERVAL 1 day) AND pgwMaster.strid=AK_PGW_Daily.strid group by pgwMaster.easy";
            DB::enableQueryLog();
            $result=DB::select($query);

            $easy=0;
            $nonEasy=0;
            foreach($result as $r) {
                $id=(string) $r->{'id'};
                if($id=='0'){
                    $easy+=$r->{'amount'};
                }
                else {
                    $nonEasy+=$r->{'amount'};
                }

            }
            $rows = array();
            $table = array();
            $table['cols'] = array(

                array('label' => 'Easy Non Easy', 'type' => 'string'),
                array('label' => 'Total Amount', 'type' => 'number')

            );
            $rows[] = array('c' => array(array('v' => "Others"),array('v' => (int)$easy)));
            $rows[] = array('c' => array(array('v' => "Easy"),array('v' => (int)$nonEasy)));



            $table['rows'] = $rows;

            $jsonTable = json_encode($table);
            return $jsonTable;
        }catch(Exception $e){
            \LOG::error($e-getMessage());
        }
    }


    //.......................JSON Data For PGW chart in PGW which compare multiple day,month,year................................
    public function PGWDynamicChartFilter(Request $request){
        $pdoparameter = [];
        $year=$request->input('year');
        $month=$request->input('month');
        $day=$request->input('day');
        $week_day=$request->input('weekday');
        $easy=$request->input('easy');
        $bank=$request->input('bankName');
        $cardType=$request->input('cardType');
        $month_data = explode(",", $month);
        $day_data = explode(",", $week_day);
        $year_data = explode(",", $year);


        if($request->input('month')!=null){
            $data=explode(',',$request->input('month'));
            $month="(";
            foreach($data as $key => $value){
                $pdoparameter['month'.$key]=$value;
                if($key==0)$month.=' :month'.$key;
                else $month.=',:month'.$key;
            }
            $month.=") ";
        }


        if($request->input('day')!=null){
            $data=explode(',',$request->input('day'));
            $day="(";
            foreach($data as $key => $value){
                $pdoparameter['day'.$key]=$value;
                if($key==0)$day.=' :day'.$key;
                else $day.=',:day'.$key;
            }
            $day.=") ";
        }

        if($request->input('weekday')!=null){
            $data=explode(',',$request->input('weekday'));
            $week_day="(";
            foreach($data as $key => $value){
                $pdoparameter['weekday'.$key]=$value;
                if($key==0)$week_day.=' :weekday'.$key;
                else $week_day.=',:weekday'.$key;
            }
            $week_day.=") ";
        }

        if($request->input('bankName')!=null){
            $bank ="( :bank )";
            $pdoparameter['bank']=$request->input('bankName');
        }

        if($request->input('cardType')!=null){
            $cardType ="( :cardType )";
            $pdoparameter['cardType']=$request->input('cardType');
        }

        if($request->input('easy')!=null){
            $easy ="( :easy )";
            $pdoparameter['easy']=$request->input('easy');
        }


        DB::enableQueryLog();
        if($month==null && $year==null && $day!=null){
            $string = "select day(AK_PGW_Daily.transdate) as day,sum(AK_PGW_Daily.mamount) as mamount from AK_PGW_Daily ";
            if($bank!=null)  $string.=", bank ";
            if($cardType!=null||$easy!=null)  $string.=", pgwMaster ";
            $string.=" where Day(AK_PGW_Daily.transdate) in ".$day." ";
            if($bank!=null)  $string.=" and bank.bid=".$bank." and bank.bid=AK_PGW_Daily.bid ";
            if($cardType!=null)  $string.=" and pgwMaster.cardtype=".$cardType." and pgwMaster.strid=AK_PGW_Daily.strid ";
            if($easy!=null){
                if($easy=="Others")$string.=" and pgwMaster.easy=0 ";
                if($easy=="Easy")$string.=" and pgwMaster.easy!=0 ";
            }
            $string.=" Group by day";
            $result = DB::select($string, $pdoparameter);

            $rows = array();
            $table = array();
            $tempArray=array();
            $tempArray[]=array('label' => 'id', 'type' => 'string');
            //$resultMonth=$connection->query("Select distinct(day(AK_PGW_Daily.transdate)) as day from AK_PGW_Daily where day(AK_PGW_Daily.transdate) in (".$day.") group by day");
            $tempArray[]=array('label' => " Amount  ", 'type' => 'number');
            $tempArray[]=array('label' => " Amount  ", 'type' => 'number');

            $table['cols']=$tempArray;
            $tempArray=array();
            // foreach($result as $r) {
            //   $tempArray[$r->{'day']][$r->{'month']]=(int)$r->{'amount'];
            // }
            $temp=array();
            foreach($result as $r){
                $temp=array();
                $temp[] = array('v' => (string) $r->{'day'});
                $temp[] = array('v' => $r->{'mamount'});
                $temp[] = array('v' => ($r->{'mamount'}/1000000));
                $rows[] = array('c' => $temp);
            }
            $table['rows'] = $rows;
            //  var_dump($table);
            $jsonTable = json_encode($table);
            echo $jsonTable;
        }
        else if($day==null && $year==null && $month!=null){
            $string = "select month(AK_PGW_Daily.transdate) as month, sum(AK_PGW_Daily.mamount) as mamount from AK_PGW_Daily ";
            if($bank!=null)  $string.=", bank ";
            if($cardType!=null||$easy!=null)  $string.=", pgwMaster ";
            $string.=" where Month(AK_PGW_Daily.transdate) in ".$month." ";
            if($bank!=null)  $string.=" and bank.bid=".$bank." and bank.bid=AK_PGW_Daily.bid ";
            if($cardType!=null)  $string.=" and pgwMaster.cardtype=".$cardType." and pgwMaster.strid=AK_PGW_Daily.strid ";
            if($easy!=null){
                if($easy=="Others")$string.=" and pgwMaster.easy=0 ";
                if($easy=="Easy")$string.=" and pgwMaster.easy!=0 ";
            }
            $string.=" Group by month";
            $result = DB::select($string, $pdoparameter);

            $rows = array();
            $table = array();
            $tempArray=array();
            $tempArray[]=array('label' => 'id', 'type' => 'string');
            $tempArray[]=array('label' => " Amount  ", 'type' => 'number');
            $tempArray[]=array('label' => " Amount  ", 'type' => 'number');
            $table['cols']=$tempArray;
            $temp=array();
            foreach($result as $r){
                $temp=array();
                $temp[] = array('v' => (string) $r->{'month'});
                $temp[] = array('v' => $r->{'mamount'});
                $temp[] = array('v' => ($r->{'mamount'}/1000000));
                $rows[] = array('c' => $temp);
            }
            $table['rows'] = $rows;
            //   var_dump($table);
            $jsonTable = json_encode($table);
            echo $jsonTable;

        }
        else if($day==null && $month==null && $year!=null){
            if($request->input('year')!=null){
                $data=explode(',',$request->input('year'));
                $year="(";
                foreach($data as $key => $value){
                    $pdoparameter['year'.$key]=$value;
                    if($key==0)$year.=' :year'.$key;
                    else $year.=',:year'.$key;
                }
                $year.=") ";
            }
            $string = "select YEAR(AK_PGW_Daily.transdate) as y, sum(AK_PGW_Daily.mamount) as mamount from AK_PGW_Daily ";
            if($bank!=null)  $string.=", bank ";
            if($cardType!=null||$easy!=null)  $string.=", pgwMaster ";
            $string.=" where YEAR(AK_PGW_Daily.transdate) in ".$year." ";
            if($bank!=null)  $string.=" and bank.bid=".$bank." and bank.bid=AK_PGW_Daily.bid ";
            if($cardType!=null)  $string.=" and pgwMaster.cardtype=".$cardType." and pgwMaster.strid=AK_PGW_Daily.strid ";
            if($easy!=null){
                if($easy=="Others")$string.=" and pgwMaster.easy=0 ";
                if($easy=="Easy")$string.=" and pgwMaster.easy!=0 ";
            }
            $string.=" Group by y";
            // echo $string . "<br/>";
            // $result="";
            $result = DB::select($string, $pdoparameter);
            // print_r($result);
            // echo $year;
            $rows = array();
            $table = array();
            $tempArray=array();
            $tempArray[]=array('label' => 'id', 'type' => 'string');
            $tempArray[]=array('label' => " Amount  ", 'type' => 'number');
            $tempArray[]=array('label' => " Amount  ", 'type' => 'number');
            $table['cols']=$tempArray;
            // $temp=array();
            // var_dump($result);
            foreach($result as $r){
                $temp=array();
                $temp[] = array('v' => (string) $r->{'y'});
                $temp[] = array('v' => $r->{'mamount'});
                $temp[] = array('v' => ($r->{'mamount'}/1000000));
                $rows[] = array('c' => $temp);
            }
            $table['rows'] = $rows;
            //   var_dump($table);
            $jsonTable = json_encode($table);
            return $jsonTable;

        }
        else if ($year==null && $day!=null && $month!=null){
            $string = "select month(AK_PGW_Daily.transdate) as month,Day(AK_PGW_Daily.transdate) as day, sum(AK_PGW_Daily.mamount) as mamount from AK_PGW_Daily ";
            if($bank!=null)  $string.=", bank ";
            if($cardType!=null||$easy!=null)  $string.=", pgwMaster ";
            $string.=" where Day(AK_PGW_Daily.transdate) in ".$day." ";
            $string.=" and Month(AK_PGW_Daily.transdate) in ".$month." ";
            if($bank!=null)  $string.=" and bank.bid=".$bank." and bank.bid=AK_PGW_Daily.bid ";
            if($cardType!=null)  $string.=" and pgwMaster.cardtype=".$cardType." and pgwMaster.strid=AK_PGW_Daily.strid ";
            if($easy!=null){
                if($easy=="Others")$string.=" and pgwMaster.easy=0 ";
                if($easy=="Easy")$string.=" and pgwMaster.easy!=0 ";
            }
            $string.=" Group by day,month";
            $result = DB::select($string, $pdoparameter);

            $rows = array();
            $table = array();
            $tempArray=array();
            $tempArray[]=array('label' => 'id', 'type' => 'string');
            $queryparameter="";
            $query = "Select distinct(day(AK_PGW_Daily.transdate)) as day from AK_PGW_Daily where day(AK_PGW_Daily.transdate) in ".$day." ";
            if($request->input('day')!=null){
                $data=explode(',',$request->input('day'));
                foreach($data as $key => $value){
                    $queryparameter['day'.$key]=$value;
                }
            }
            $resultMonth=DB::select($query, $queryparameter);
            foreach($resultMonth as $m){
                $tempArray[]=array('label' => "Day: ".$m->{'day'}." Amount  ", 'type' => 'number');
                $tempArray[]=array('label' => "Day: ".$m->{'day'}." Amount  ", 'type' => 'number');
            }
            $table['cols']=$tempArray;
            $tempArray=array();
            foreach($result as $r) {
                $tempArray[$r->{'month'}][$r->{'day'}]=(int)$r->{'mamount'};
            }
            foreach($tempArray as $key => $r){
                $temp=array();

                $temp[] = array('v' => (string) $key);
                foreach($r as $r1){
                    $temp[] = array('v' => $r1);
                    $temp[] = array('v' => ($r1/1000000));
                }
                $rows[] = array('c' => $temp);
            }
            $table['rows'] = $rows;
            //  var_dump($table);
            $jsonTable = json_encode($table);
            return $jsonTable;

        }
        else if ($year!=null && $day!=null && $month==null){
            if($request->input('year')!=null){
                $data=explode(',',$request->input('year'));
                $year="(";
                foreach($data as $key => $value){
                    $pdoparameter['year'.$key]=$value;
                    if($key==0)$year.=' :year'.$key;
                    else $year.=',:year'.$key;
                }
                $year.=") ";
            }
            $string = "select year(AK_PGW_Daily.transdate) as year,day(AK_PGW_Daily.transdate) as day, sum(AK_PGW_Daily.mamount) as mamount from AK_PGW_Daily ";
            if($bank!=null)  $string.=", bank ";
            if($cardType!=null||$easy!=null)  $string.=", pgwMaster ";
            $string.=" where year(AK_PGW_Daily.transdate) in ".$year." ";
            $string.=" and day(AK_PGW_Daily.transdate) in ".$day." ";
            if($bank!=null)  $string.=" and bank.bid=".$bank." and bank.bid=AK_PGW_Daily.bid ";
            if($cardType!=null)  $string.=" and pgwMaster.cardtype=".$cardType." and pgwMaster.strid=AK_PGW_Daily.strid ";
            if($easy!=null){
                if($easy=="Others")$string.=" and pgwMaster.easy=0 ";
                if($easy=="Easy")$string.=" and pgwMaster.easy!=0 ";
            }
            $string.=" Group by year,day";
            $result = DB::select($string, $pdoparameter);
//            dd($result);
            $rows = array();
            $table = array();
            $tempArray=array();
            $tempArray[]=array('label' => 'id', 'type' => 'string');
//            $resultMonth=$connection->query("Select distinct(day(AK_PGW_Daily.transdate)) as day from AK_PGW_Daily where day(AK_PGW_Daily.transdate) in ".$day." ");
            $queryparameter="";
            $query = "Select distinct(day(AK_PGW_Daily.transdate)) as day from AK_PGW_Daily where day(AK_PGW_Daily.transdate) in ".$day." ";
            if($request->input('day')!=null){
                $data=explode(',',$request->input('day'));
                foreach($data as $key => $value){
                    $queryparameter['day'.$key]=$value;
                }
            }
            $resultMonth=DB::select($query, $queryparameter);
            foreach($resultMonth as $m){
                $tempArray[]=array('label' => "Day: ".$m->{'day'}." Amount  ", 'type' => 'number');
                $tempArray[]=array('label' => "Day: ".$m->{'day'}." Amount  ", 'type' => 'number');
            }
            $table['cols']=$tempArray;
            $tempArray=array();
            foreach($result as $r) {
                $tempArray[$r->{'year'}][$r->{'day'}]=(int)$r->{'mamount'};
            }
            foreach($tempArray as $key => $r){
                $temp=array();
                $temp[] = array('v' => (string) $key);
                foreach($r as $r1){
                    $temp[] = array('v' => $r1);
                    $temp[] = array('v' => ($r1/1000000));
                }
                $rows[] = array('c' => $temp);

            }
            $table['rows'] = $rows;
            //  var_dump($table);
            $jsonTable = json_encode($table);
            return $jsonTable;


        }
        else if ($year!=null && $day==null && $month!=null){
            if($request->input('year')!=null){
                $data=explode(',',$request->input('year'));
                $year="(";
                foreach($data as $key => $value){
                    $pdoparameter['year'.$key]=$value;
                    if($key==0)$year.=' :year'.$key;
                    else $year.=',:year'.$key;
                }
                $year.=") ";
            }
            $string = "select year(AK_PGW_Daily.transdate) as year,month(AK_PGW_Daily.transdate) as month, sum(AK_PGW_Daily.mamount) as mamount from AK_PGW_Daily ";
            if($bank!=null)  $string.=", bank ";
            if($cardType!=null||$easy!=null)  $string.=", pgwMaster ";
            $string.=" where year(AK_PGW_Daily.transdate) in ".$year." ";
            $string.=" and Month(AK_PGW_Daily.transdate) in ".$month." ";
            if($bank!=null)  $string.=" and bank.bid=".$bank." and bank.bid=AK_PGW_Daily.bid ";
            if($cardType!=null)  $string.=" and pgwMaster.cardtype=".$cardType." and pgwMaster.strid=AK_PGW_Daily.strid ";
            if($easy!=null){
                if($easy=="Others")$string.=" and pgwMaster.easy=0 ";
                if($easy=="Easy")$string.=" and pgwMaster.easy!=0 ";
            }
            $string.=" Group by year,month";
            $result = DB::select($string, $pdoparameter);

            $rows = array();
            $table = array();
            $tempArray=array();
            $tempArray[]=array('label' => 'id', 'type' => 'string');
//            $resultMonth=$connection->query("Select distinct(month(AK_PGW_Daily.transdate)) as month from AK_PGW_Daily where month(AK_PGW_Daily.transdate) in (".$month.") ");
            $queryparameter="";
            $query = "Select distinct(month(AK_PGW_Daily.transdate)) as month from AK_PGW_Daily where month(AK_PGW_Daily.transdate) in ".$month." ";
            if($request->input('month')!=null){
                $data=explode(',',$request->input('month'));
                foreach($data as $key => $value){
                    $queryparameter['month'.$key]=$value;
                }
            }
            $resultMonth=DB::select($query, $queryparameter);
            foreach($resultMonth as $m){
                $tempArray[]=array('label' => "Month: ".$m->{'month'}." Amount  ", 'type' => 'number');
                $tempArray[]=array('label' => "Month: ".$m->{'month'}." Amount  ", 'type' => 'number');
            }
            $table['cols']=$tempArray;
            $tempArray=array();
            foreach($result as $r) {
                $tempArray[$r->{'year'}][$r->{'month'}]=(int)$r->{'mamount'};
            }
            foreach($tempArray as $key => $r){
                $temp=array();
                $temp[] = array('v' => (string) $key);
                foreach($r as $r1){
                    $temp[] = array('v' => $r1);
                    $temp[] = array('v' => ($r1/1000000));
                }
                $rows[] = array('c' => $temp);

            }
            $table['rows'] = $rows;
            //  var_dump($table);
            $jsonTable = json_encode($table);
            return $jsonTable;

        }
        else if ($year!=null && $day!=null && $month!=null){
            $rows = array();
            $table = array();
            $tempArray=array();
            $tempArray[]=array('label' => 'id', 'type' => 'string');
            $queryparameter="";
            $query = "Select distinct(day(AK_PGW_Daily.transdate)) as day from AK_PGW_Daily where day(AK_PGW_Daily.transdate) in ".$day." ";
            if($request->input('day')!=null){
                $data=explode(',',$request->input('day'));
                foreach($data as $key => $value){
                    $queryparameter['day'.$key]=$value;
                }
            }
            $resultMonth=DB::select($query, $queryparameter);
            foreach($resultMonth as $m){
                $tempArray[]=array('label' => "Day: ".$m->{'day'}." Amount  ", 'type' => 'number');
                $tempArray[]=array('label' => "Day: ".$m->{'day'}." Amount  ", 'type' => 'number');
            }
            $table['cols']=$tempArray;
            $tempArray=array();
            foreach($year_data as $r2){
                $string = "select year(AK_PGW_Daily.transdate) as year,month(AK_PGW_Daily.transdate) as month,Day(AK_PGW_Daily.transdate) as day, sum(AK_PGW_Daily.mamount) as mamount from AK_PGW_Daily ";
                if($bank!=null)  $string.=", bank ";
                if($cardType!=null||$easy!=null)  $string.=", pgwMaster ";
                $string.=" where Day(AK_PGW_Daily.transdate) in ".$day." ";
                $string.=" and Month(AK_PGW_Daily.transdate) in ".$month." ";
                $string.=" and year(AK_PGW_Daily.transdate) = :year";
                $pdoparameter['year'] = $r2;
                if($bank!=null)  $string.=" and bank.bid=".$bank." and bank.bid=AK_PGW_Daily.bid ";
                if($cardType!=null)  $string.=" and pgwMaster.cardtype=".$cardType." and pgwMaster.strid=AK_PGW_Daily.strid ";
                if($easy!=null){
                    if($easy=="Others")$string.=" and pgwMaster.easy=0 ";
                    if($easy=="Easy")$string.=" and pgwMaster.easy!=0 ";
                }
                $string.=" Group by year,month,day";
                $result = DB::select($string, $pdoparameter);
//                dd($result);
                foreach($result as $r) {
                    $tempArray[$r->{'month'}][$r->{'day'}]=(int)$r->{'mamount'};
                }
                foreach($tempArray as $key => $r){
                    $temp=array();

                    $temp[] = array('v' => (string) $key.", ".$r2);
                    foreach($r as $r1){
                        $temp[] = array('v' => $r1);
                        $temp[] = array('v' => ($r1/1000000));
                    }
                    $rows[] = array('c' => $temp);
                    $temp=array();
                }
            }
            $table['rows'] = $rows;
            //  var_dump($table);
            $jsonTable = json_encode($table);
            return $jsonTable;

        }



    }
    

}
