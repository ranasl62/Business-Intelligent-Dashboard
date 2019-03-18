<?php

namespace App\Http\Controllers;

use App\SMSData;
use App\SMSMaster;
use Illuminate\Http\Request;
use DB;
use App\Log;
class SMSChartController extends Controller
{
    use Log;
    public function index(){
        $this->log('SMS Chart','SMS Chart ','SMS');
        $data['title']="SMS Transaction";
        DB::enableQueryLog();
        $year = DB::select("SELECT DISTINCT(year(transdate)) as year FROM SMSData");
        $year_options = [];
        foreach ($year as $y){
            $year_options[$y->{'year'}]=$y->{'year'};
        }
        $data['year_options'] = $year_options;
        $department = SMSMaster::select('Department')->distinct()->get()->toArray();
        $department_options = [];
        foreach ($department as $d){
            $department_options[$d['Department']]=$d['Department'];
        }
        $data['department_options'] = $department_options;
//        dd($data);
        return view('sms.smschart',compact('data'));
    }


    //.......................JSON Data For Top Ten Kam in SMS................................
    public function SMSTopTenKamChart(){
        try{
            $query = "SELECT a.kam as KAM, SUM(SMSData.sms_rate) as Amount FROM SMSData join (SELECT DISTINCT(smsMaster.stakeholder) Stakeholder, smsMaster.KAM kam FROM smsMaster) a on a.Stakeholder=SMSData.stakeholder WHERE year(SMSData.transdate)=year(CURDATE()-interval 1 day) and month(SMSData.transdate)=month(CURDATE()-interval 1 day) GROUP by a.kam ORDER by amount DESC LIMIT 10";
            DB::enableQueryLog();
            $result=DB::select($query);
            $rows = array();
            $table = array();
            $table['cols'] = array(

                array('label' => 'Client Id', 'type' => 'string'),
                array('label' => 'Total Amount', 'type' => 'number'),
                array('label' => 'Total Amount', 'type' => 'number')

            );
            /* Extract the information from $result */
            foreach($result as $r)
                $rows[] = array('c' => array(
                    array('v' => (string) $r->{'KAM'}),
                    array('v' => (int) $r->{'Amount'}),
                    array('v' => ((int) $r->{'Amount'})/1000000)
                ));



            $table['rows'] = $rows;

            $jsonTable = json_encode($table);

            return $jsonTable;
        }catch(Exception $e){
            \LOG::error($e-getMessage());
        }
    }

    //.......................JSON Data For Top Ten Company in SMS................................
    public function SMSTopTenCompanyChart(){
        try{
            $query = "SELECT Stakeholder, SUM(SMSData.sms_rate) as Amount FROM SMSData where year(SMSData.transdate)=year(CURDATE()-interval 1 day) and month(SMSData.transdate)=month(CURDATE()-interval 1 day) GROUP by Stakeholder ORDER by amount DESC LIMIT 10";
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
                    array('v' => (string) $r->{'Stakeholder'}),
                    array('v' => (int) $r->{'Amount'}),
                    array('v' => ((int) $r->{'Amount'})/1000000)
                ));

            $table['rows'] = $rows;

            $jsonTable = json_encode($table);

            return $jsonTable;
        }catch(Exception $e){
            \LOG::error($e-getMessage());
        }
    }

    //.......................JSON Data For Operator wise Chart for current month donut chart in SMS................................
    public function SMSOpearatorChart(){
        try{
            $query = "SELECT SUM(SMSData.amount) as amount, SMSData.Operator as operator FROM SMSData WHERE year(SMSData.transdate)= year(CURDATE() - INTERVAL 1 day) and month(SMSData.transdate)= month(CURDATE() - INTERVAL 1 day) group by SMSData.Operator";
//            $query = "SELECT SUM(SMSData.amount), operator.operator_name FROM SMSData JOIN operator where year(SMSData.transdate)= year(CURDATE() - INTERVAL 1 day) and month(SMSData.transdate)= month(CURDATE() - INTERVAL 1 day) and operator.operator_id=SMSData.operator_id group by operator.operator_name";
            DB::enableQueryLog();
            $result=DB::select($query);
            $rows = array();
            $table = array();
            $table['cols'] = array(
                array('label' => 'Operator Name', 'type' => 'string'),
                array('label' => 'Total Amount', 'type' => 'number')
            );
            /* Extract the information from $result */
            foreach($result as $r)
                $rows[] = array('c' => array(array('v' => (string) $r->{'operator'}),array('v' => (int) $r->{'amount'})));

            $table['rows'] = $rows;

            $jsonTable = json_encode($table);

            return $jsonTable;
        }catch(Exception $e){
            \LOG::error($e-getMessage());
        }
    }

    //.......................JSON Data For Industry wise chart in SMS................................
    public function SMSIndustryChart(){
        try{
            $query = "SELECT SUM(SMSData.sms_rate) as Amount, a.Department as id FROM SMSData JOIN (SELECT DISTINCT(smsMaster.stakeholder) stakeholder, smsMaster.Department Department FROM smsMaster) a on a.stakeholder=SMSData.Stakeholder where year(SMSData.transdate)= year(CURDATE() - INTERVAL 1 day) and month(SMSData.transdate)= month(CURDATE() - INTERVAL 1 day) group by a.Department";
            DB::enableQueryLog();
            $result=DB::select($query);
            $rows = array();
            $table = array();
            $table['cols'] = array(
                array('label' => 'Industry', 'type' => 'string'),
                array('label' => 'Total Amount', 'type' => 'number')
            );
            /* Extract the information from $result */
            foreach($result as $r) {
                $rows[] = array('c' => array(array('v' => (string)$r->{'id'}), array('v' => (int)$r->{'Amount'})));
            }

            $table['rows'] = $rows;
            $jsonTable = json_encode($table);

            return $jsonTable;
        }catch(Exception $e){
            \LOG::error($e-getMessage());
        }
    }

    //.......................JSON Data For SMS Stakeholder who are mostly impact in negatively or positively................................
    public function SMSImpactClientChart(Request $request){
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
                $resultToquery="SELECT  SMSData.transdate id, sum(SMSData.amount) amount FROM `SMSData` WHERE SMSData.transdate= :toDate  group by id";
                $resultTopdoparameter=['toDate'=>$toDate];
                $resultFromquery="SELECT SMSData.transdate id, sum(SMSData.amount) amount FROM `SMSData` WHERE SMSData.transdate=(:fromDate)  group by id";
                $resultFrompdoparameter=['fromDate'=>$fromDate];
                $resultMid1query="select concat(substring(l.id,1,10),', ',substring(smsMaster.Department,1,4)) id,l.amount from (SELECT ifnull(k.c1,k.c2) id,ifnull(k.m1,0)-ifnull(k.m2,0) amount from (SELECT * from (SELECT SMSData.Stakeholder c1,sum(amount) m1 from SMSData where transdate =:toDate GROUP BY SMSData.Stakeholder) b left JOIN (SELECT SMSData.Stakeholder c2,sum(amount) m2 from SMSData where transdate =(:fromDate) GROUP BY SMSData.Stakeholder) a on a.c2=b.c1 UNION ALL SELECT * from (SELECT SMSData.Stakeholder c1,sum(amount) m1 from SMSData where transdate =:toDate1 GROUP BY SMSData.Stakeholder) b RIGHT JOIN (SELECT SMSData.Stakeholder c2,sum(amount) m2 from SMSData where transdate =(:fromDate1) GROUP BY SMSData.Stakeholder) a on a.c2=b.c1 where b.c1 is null) k ORDER by amount DESC LIMIT 4) l join smsMaster on smsMaster.stakeholder=l.id";
                $resultMid1pdoparameter=['toDate'=>$toDate,'toDate1'=>$toDate,'fromDate'=>$fromDate,'fromDate1'=>$fromDate];
                $resultMid2query="select concat(substring(l.id,1,10),', ',substring(smsMaster.Department,1,4)) id,l.amount from (SELECT ifnull(k.c1,k.c2) id,ifnull(k.m1,0)-ifnull(k.m2,0) amount from (SELECT * from (SELECT SMSData.Stakeholder c1,sum(amount) m1 from SMSData where transdate =:toDate GROUP BY SMSData.Stakeholder) b left JOIN (SELECT SMSData.Stakeholder c2,sum(amount) m2 from SMSData where transdate =(:fromDate) GROUP BY SMSData.Stakeholder) a on a.c2=b.c1 UNION ALL SELECT * from (SELECT SMSData.Stakeholder c1,sum(amount) m1 from SMSData where transdate =:toDate1 GROUP BY SMSData.Stakeholder) b RIGHT JOIN (SELECT SMSData.Stakeholder c2,sum(amount) m2 from SMSData where transdate =(:fromDate1) GROUP BY SMSData.Stakeholder) a on a.c2=b.c1 where b.c1 is null) k ORDER by amount ASC LIMIT 4) l join smsMaster on smsMaster.stakeholder=l.id";
                $resultMid2pdoparameter=['toDate'=>$toDate,'toDate1'=>$toDate,'fromDate'=>$fromDate,'fromDate1'=>$fromDate];

            }
            elseif($fromDate!=null && $toDate!=null && $operation=="month"){
                $resultToquery="SELECT  month(SMSData.transdate) id, sum(SMSData.amount) amount FROM `SMSData` WHERE year(transdate) =year(:toDate) and month(transdate) =month(:toDate1) group by id";
                $resultTopdoparameter=['toDate'=>$toDate,'toDate1'=>$toDate];
                $resultFromquery="SELECT month(SMSData.transdate) id, sum(SMSData.amount) amount FROM `SMSData` WHERE year(transdate) =year(:fromDate) and month(transdate) =month(:fromDate1) group by id";
                $resultFrompdoparameter=['fromDate'=>$fromDate,'fromDate1'=>$fromDate];
                $resultMid1query="select concat(substring(l.id,1,10),', ',substring(smsMaster.Department,1,4)) id,l.amount from (SELECT ifnull(k.c1,k.c2) id,ifnull(k.m1,0)-ifnull(k.m2,0) amount from (SELECT * from (SELECT SMSData.Stakeholder c1,sum(amount) m1 from SMSData where year(transdate) =year(:toDate) and month(transdate) =month(:toDate1) GROUP BY SMSData.Stakeholder) b left JOIN (SELECT SMSData.Stakeholder c2,sum(amount) m2 from SMSData where year(transdate) =year(:fromDate) and month(transdate) =month(:fromDate1) GROUP BY SMSData.Stakeholder) a on a.c2=b.c1 UNION ALL SELECT * from (SELECT SMSData.Stakeholder c1,sum(amount) m1 from SMSData where year(transdate) =year(:toDate2) and month(transdate) =month(:toDate3) GROUP BY SMSData.Stakeholder) b RIGHT JOIN (SELECT SMSData.Stakeholder c2,sum(amount) m2 from SMSData where year(transdate) =year(:fromDate2) and month(transdate) =month(:fromDate3) GROUP BY SMSData.Stakeholder) a on a.c2=b.c1 where b.c1 is null) k ORDER by amount DESC LIMIT 4) l join smsMaster on smsMaster.stakeholder=l.id";
                $resultMid1pdoparameter=['toDate'=>$toDate,'toDate1'=>$toDate,'toDate2'=>$toDate,'toDate3'=>$toDate,'fromDate'=>$fromDate,'fromDate1'=>$fromDate,'fromDate2'=>$fromDate,'fromDate3'=>$fromDate];
                $resultMid2query="select concat(substring(l.id,1,10),', ',substring(smsMaster.Department,1,4)) id,l.amount from (SELECT ifnull(k.c1,k.c2) id,ifnull(k.m1,0)-ifnull(k.m2,0) amount from (SELECT * from (SELECT SMSData.Stakeholder c1,sum(amount) m1 from SMSData where year(transdate) =year(:toDate) and month(transdate) =month(:toDate1) GROUP BY SMSData.Stakeholder) b left JOIN (SELECT SMSData.Stakeholder c2,sum(amount) m2 from SMSData where year(transdate) =year(:fromDate) and month(transdate) =month(:fromDate1) GROUP BY SMSData.Stakeholder) a on a.c2=b.c1 UNION ALL SELECT * from (SELECT SMSData.Stakeholder c1,sum(amount) m1 from SMSData where year(transdate) =year(:toDate2) and month(transdate) =month(:toDate3) GROUP BY SMSData.Stakeholder) b RIGHT JOIN (SELECT SMSData.Stakeholder c2,sum(amount) m2 from SMSData where year(transdate) =year(:fromDate2) and month(transdate) =month(:fromDate3) GROUP BY SMSData.Stakeholder) a on a.c2=b.c1 where b.c1 is null) k ORDER by amount ASC LIMIT 4) l join smsMaster on smsMaster.stakeholder=l.id";
                $resultMid2pdoparameter=['toDate'=>$toDate,'toDate1'=>$toDate,'toDate2'=>$toDate,'toDate3'=>$toDate,'fromDate'=>$fromDate,'fromDate1'=>$fromDate,'fromDate2'=>$fromDate,'fromDate3'=>$fromDate];

            }
            elseif($fromDate!=null && $toDate!=null && $operation=="year"){
                $resultToquery="SELECT  year(SMSData.transdate) id, sum(SMSData.amount) amount FROM `SMSData` WHERE year(transdate) =year(:toDate) group by id";
                $resultTopdoparameter=['toDate'=>$toDate];
                $resultFromquery="SELECT year(SMSData.transdate) id, sum(SMSData.amount) amount FROM `SMSData` WHERE year(transdate) =year(:fromDate) group by id";
                $resultFrompdoparameter=['fromDate'=>$fromDate];
                $resultMid1query="select concat(substring(l.id,1,10),', ',substring(smsMaster.Department,1,4)) id,l.amount from (SELECT ifnull(k.c1,k.c2) id,ifnull(k.m1,0)-ifnull(k.m2,0) amount from (SELECT * from (SELECT SMSData.Stakeholder c1,sum(amount) m1 from SMSData where year(transdate) =year(:toDate) GROUP BY SMSData.Stakeholder) b left JOIN (SELECT SMSData.Stakeholder c2,sum(amount) m2 from SMSData where year(transdate) =year(:fromDate) GROUP BY SMSData.Stakeholder) a on a.c2=b.c1 UNION ALL SELECT * from (SELECT SMSData.Stakeholder c1,sum(amount) m1 from SMSData where year(transdate) =year(:toDate1)  GROUP BY SMSData.Stakeholder) b RIGHT JOIN (SELECT SMSData.Stakeholder c2,sum(amount) m2 from SMSData where year(transdate) =year(:fromDate1)  GROUP BY SMSData.Stakeholder) a on a.c2=b.c1 where b.c1 is null) k ORDER by amount DESC LIMIT 4) l join smsMaster on smsMaster.stakeholder=l.id";
                $resultMid1pdoparameter=['toDate'=>$toDate,'toDate1'=>$toDate,'fromDate'=>$fromDate,'fromDate1'=>$fromDate];
                $resultMid2query="select concat(substring(l.id,1,10),', ',substring(smsMaster.Department,1,4)) id,l.amount from (SELECT ifnull(k.c1,k.c2) id,ifnull(k.m1,0)-ifnull(k.m2,0) amount from (SELECT * from (SELECT SMSData.Stakeholder c1,sum(amount) m1 from SMSData where year(transdate) =year(:toDate) GROUP BY SMSData.Stakeholder) b left JOIN (SELECT SMSData.Stakeholder c2,sum(amount) m2 from SMSData where year(transdate) =year(:fromDate) GROUP BY SMSData.Stakeholder) a on a.c2=b.c1 UNION ALL SELECT * from (SELECT SMSData.Stakeholder c1,sum(amount) m1 from SMSData where year(transdate) =year(:toDate1)  GROUP BY SMSData.Stakeholder) b RIGHT JOIN (SELECT SMSData.Stakeholder c2,sum(amount) m2 from SMSData where year(transdate) =year(:fromDate1)  GROUP BY SMSData.Stakeholder) a on a.c2=b.c1 where b.c1 is null) k ORDER by amount ASC LIMIT 4) l join smsMaster on smsMaster.stakeholder=l.id";
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
                    array('v' => (string) "<div style=\"text-align:left;min-width: 220px;padding-left: 10px;\">".$lastdate." Amount: <b>".number_format( (int) $result->{'amount'}+$top)."</b></div>")
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
                    if($operation=="day"){
                        $queryLastAmount ="SELECT SUM(amount) as amount FROM `SMSData` WHERE Stakeholder=:id and transdate=:fromDate";
                        $queryFirstAmount ="SELECT SUM(amount) as amount FROM `SMSData` WHERE Stakeholder=:id and transdate=:toDate";
                        $resultLastAmount=DB::select($queryLastAmount,['fromDate'=>$fromDate,'id'=>$value['id']]);
                        $resultFirstAmount=DB::select($queryFirstAmount,['toDate'=>$toDate,'id'=>$value['id']]);
                    }elseif ($operation=="month") {
                        $queryLastAmount ="SELECT SUM(amount) as amount FROM `SMSData` WHERE Stakeholder=:id and year(transdate)=year(:fromDate) and month(transdate)=month(:fromDate1)";
                        $queryFirstAmount ="SELECT SUM(amount) as amount FROM `SMSData` WHERE Stakeholder=:id and year(transdate)=year(:toDate) and month(transdate)=month(:toDate1)";
                        $resultLastAmount=DB::select($queryLastAmount,['fromDate'=>$fromDate, 'fromDate1'=>$fromDate,'id'=>$value['id']]);
                        $resultFirstAmount=DB::select($queryFirstAmount,['toDate'=>$toDate, 'toDate1'=>$toDate,'id'=>$value['id']]);
                    }elseif ($operation=="year") {
                        $queryLastAmount ="SELECT SUM(amount) as amount FROM `SMSData` WHERE Stakeholder=:id and year(transdate)=year(:fromDate)";
                        $queryFirstAmount ="SELECT SUM(amount) as amount FROM `SMSData` WHERE Stakeholder=:id and year(transdate)=year(:toDate)";
                        $resultLastAmount=DB::select($queryLastAmount,['fromDate'=>$fromDate,'id'=>$value['id']]);
                        $resultFirstAmount=DB::select($queryFirstAmount,['toDate'=>$toDate,'id'=>$value['id']]);
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
                    array('v' => (string) "<div style=\"text-align:left;min-width: 220px;padding-left: 10px;\">Stakeholder: <b>".$value['id']."</b><br/>"
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

    //.......................JSON Data For  SMS Transaction count in SMS................................
    public function SMSTransactionCountChart(Request $request){
        try{
            $year=$request->input('year');
            $month=$request->input('month');
            $quarter=$request->input('quarter');
            $operator=$request->input('operatorName');
            $year=str_replace(',', '', $year);
            $query="";
            $pdoparameter=[];

            if($year==null && $quarter==null && $month==null){
                $query="SELECT YEAR(transdate) as id, sum(amount) as amount FROM SMSData GROUP by id";
            }
            elseif ($year!=null && $quarter!=null && $month==null) {
                $query = "SELECT QUARTER(transdate) as id, sum(amount) as amount FROM SMSData WHERE YEAR(transdate)=:year GROUP by id";
                $pdoparameter=['year'=>$year];
            }
            elseif ($year!=null && $quarter==null && $month==null) {
                $query = "SELECT MONTH(transdate) as id, sum(amount) as amount FROM SMSData WHERE YEAR(transdate)=:year GROUP by id";
                $pdoparameter=['year'=>$year];
            }
            elseif ($year!=null && $quarter==null && $month!=null) {
                $query = "SELECT DAY(transdate) as id, sum(amount) as amount FROM SMSData WHERE YEAR(transdate)=:year and MONTH(transdate)=:month GROUP by id";
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
                    array('v' => (int) $r->{'amount'}/1000000),
                    array('v' => "#6EDDF9")
                ));

            if( $year != null && $month != null){
                $query3 = "SELECT COUNT(transdate) cnt FROM SMSData WHERE (SELECT year(max(transdate)) FROM SMSData) = :year and (SELECT month(max(transdate)) FROM SMSData) = :month";
                $result3=DB::select($query3, ['year'=>$year, 'month'=>$month]);
                if($result3[0]->{'cnt'}){
                    $query4 = "SELECT transdate a, weekday(transdate) weekday, sum(amount) amount, (SELECT sum(amount) FROM SMSData WHERE transdate = (a - INTERVAL 1 day)) priviousday , (100 + ( 100 * ((SELECT sum(amount) FROM SMSData WHERE transdate = (a - INTERVAL 7 day)) - (SELECT sum(amount) FROM SMSData WHERE transdate = (a - INTERVAL 8 day))) / (SELECT sum(amount) FROM SMSData WHERE transdate = (a - INTERVAL 8 day)) ) ) per FROM SMSData WHERE transdate > (SELECT max(transdate) - INTERVAL 7 day FROM SMSData) GROUP by transdate";
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


    //.......................JSON Data For SMS daily wise chart in SMS................................
    public function SMSDynamicChart(Request $request){
        $year=$request->input('year');
        $month=$request->input('month');
        $quarter=$request->input('quarter');
        $operator=$request->input('operatorName');
        $industry=$request->input('industry');
//        echo $operator." <br/>".$industry;
        $department1=$request->input('department1');
        $year=str_replace(',', '', $year);
        $query="";
        $query2="";
        $result2="";
        DB::enableQueryLog();

        if($year==null && $quarter==null && $month==null){
            $query = "SELECT YEAR(transdate) as id, SUM(SMSData.sms_rate) as amount FROM SMSData GROUP by id";
            $result=DB::select($query);
        }
        elseif ($year!=null && $quarter!=null && $month==null) {
            $query = "SELECT QUARTER(transdate) as id, SUM(SMSData.sms_rate) as amount FROM SMSData WHERE YEAR(transdate)=:year GROUP by id";
            $result=DB::select($query, ['year'=>$year]);
        }
        elseif ($year!=null && $quarter==null && $month==null) {
            $query = "SELECT MONTH(transdate) as id, SUM(SMSData.sms_rate) as amount FROM SMSData WHERE YEAR(transdate)=:year GROUP by id";
            $result=DB::select($query, ['year'=>$year]);
        }
        elseif ($year!=null && $quarter==null && $month!=null) {
            $query = "SELECT DAY(transdate) as id, SUM(SMSData.sms_rate) as amount FROM SMSData WHERE YEAR(transdate)=:year and MONTH(transdate)=:month GROUP by id";
            $result=DB::select($query, ['year'=>$year,'month'=>$month]);
        }

        
        if($industry!=null){
            if($year==null && $quarter==null && $month==null){
                $query2 = "SELECT YEAR(SMSData.transdate) as id, SUM(SMSData.sms_rate) as amount FROM SMSData JOIN (SELECT DISTINCT(smsMaster.stakeholder) Stakeholder, smsMaster.Department Department FROM smsMaster) a where a.Stakeholder=SMSData.stakeholder and a.Department=:industry GROUP by id";
                $result2=DB::select($query2, ['industry'=>$industry]);
            }
            elseif ($year!=null && $quarter!=null && $month==null) {
                $query2 = "SELECT QUARTER(SMSData.transdate) as id, SUM(SMSData.sms_rate) as amount FROM SMSData JOIN (SELECT DISTINCT(smsMaster.stakeholder) Stakeholder, smsMaster.Department Department FROM smsMaster) a where a.Stakeholder=SMSData.stakeholder and a.Department=:industry and YEAR(transdate)=:year GROUP by id";
                $result2=DB::select($query2, ['industry'=>$industry, 'year'=>$year]);
            }
            elseif ($year!=null && $quarter==null && $month==null) {
                $query2 = "SELECT MONTH(SMSData.transdate) as id, SUM(SMSData.sms_rate) as amount FROM SMSData JOIN (SELECT DISTINCT(smsMaster.stakeholder) Stakeholder, smsMaster.Department Department FROM smsMaster) a where a.Stakeholder=SMSData.stakeholder and a.Department=:industry and YEAR(transdate)=:year GROUP by id";
                $result2=DB::select($query2, ['industry'=>$industry, 'year'=>$year]);
            }
            elseif ($year!=null && $quarter==null && $month!=null) {
                $query2 = "SELECT DAY(SMSData.transdate) as id, SUM(SMSData.sms_rate) as amount FROM SMSData JOIN (SELECT DISTINCT(smsMaster.stakeholder) Stakeholder, smsMaster.Department Department FROM smsMaster) a where a.Stakeholder=SMSData.stakeholder and a.Department=:industry and YEAR(transdate)=:year and MONTH(SMSData.transdate)=:month GROUP by id";
                $result2=DB::select($query2, ['industry'=>$industry, 'year'=>$year, 'month'=>$month]);
            }
        }

        else if($operator=="Local"){

            if($year==null && $quarter==null && $month==null){
                $query2 = "SELECT YEAR(f.transdate) id,SUM(SMSData.sms_rate) amount from ( select t.transdate,t.amount from SMSData t inner join smsMaster c on t.Stakeholder=c.stakeholder WHERE c.stakeholder_type='Local') f group by id";
                $result2=DB::select($query2);
            }
            elseif ($year!=null && $quarter!=null && $month==null) {
                $query2 = "SELECT QUARTER(f.transdate) id,SUM(SMSData.sms_rate) amount from ( select t.transdate,t.amount from SMSData t inner join smsMaster c on t.Stakeholder=c.stakeholder WHERE c.stakeholder_type='Local' and Year(t.transdate)=:year) f group by id";
                $result2=DB::select($query2, ['year'=>$year]);
            }
            elseif ($year!=null && $quarter==null && $month==null) {
                $query2 = "SELECT Month(f.transdate) id,SUM(SMSData.sms_rate) amount from ( select t.transdate,t.amount from SMSData t inner join smsMaster c on t.Stakeholder=c.stakeholder WHERE c.stakeholder_type='Local' and Year(t.transdate)=:year) f group by id";
                $result2=DB::select($query2, ['year'=>$year]);
            }
            elseif ($year!=null && $quarter==null && $month!=null) {
                $query2 = "SELECT day(f.transdate) id,SUM(SMSData.sms_rate) amount from ( select t.transdate,t.amount from SMSData t inner join smsMaster c on t.Stakeholder=c.stakeholder WHERE c.stakeholder_type='Local' and Year(t.transdate)=:year and Month(transdate)=:month) f group by id";
                $result2=DB::select($query2, ['year'=>$year, 'month'=>$month]);
            }


        }

        else if ($operator=="International"){
            if($year==null && $quarter==null && $month==null){
                $query2 = "SELECT YEAR(f.transdate) id,SUM(SMSData.sms_rate) amount from ( select t.transdate,t.amount from SMSData t inner join smsMaster c on t.Stakeholder=c.stakeholder WHERE c.stakeholder_type='International') f group by id";
                $result2=DB::select($query2);
            }
            elseif ($year!=null && $quarter!=null && $month==null) {
                $query2 = "SELECT QUARTER(f.transdate) id,SUM(SMSData.sms_rate) amount from ( select t.transdate,t.amount from SMSData t inner join smsMaster c on t.Stakeholder=c.stakeholder WHERE c.stakeholder_type='International' and Year(t.transdate)=:year) f group by id";
                $result2=DB::select($query2, ['year'=>$year]);
            }
            elseif ($year!=null && $quarter==null && $month==null) {
                $query2 = "SELECT Month(f.transdate) id,SUM(SMSData.sms_rate) amount from ( select t.transdate,t.amount from SMSData t inner join smsMaster c on t.Stakeholder=c.stakeholder WHERE c.stakeholder_type='International' and Year(t.transdate)=:year) f group by id";
                $result2=DB::select($query2, ['year'=>$year]);
            }
            elseif ($year!=null && $quarter==null && $month!=null) {
                $query2 = "SELECT day(f.transdate) id,SUM(SMSData.sms_rate) amount from ( select t.transdate,t.amount from SMSData t inner join smsMaster c on t.Stakeholder=c.stakeholder WHERE c.stakeholder_type='International' and Year(t.transdate)=:year and Month(transdate)=:month) f group by id";
                $result2=DB::select($query2, ['year'=>$year, 'month'=>$month]);
            }
        }
        else {
            if($year==null && $quarter==null && $month==null && $operator!=null){
                $query2 = "SELECT YEAR(transdate) as id, SUM(SMSData.sms_rate) as amount FROM SMSData WHERE Operator=:operator GROUP by id";
                $result2=DB::select($query2, ['operator'=>$operator]);
            }
            elseif ($year!=null && $quarter!=null && $month==null && $operator!=null) {
                $query2 = "SELECT QUARTER(transdate) as id, SUM(SMSData.sms_rate) as amount FROM SMSData WHERE Operator=:operator and YEAR(transdate)=:year GROUP by id";
                $result2=DB::select($query2, ['operator'=>$operator, 'year'=>$year]);
            }
            elseif ($year!=null && $quarter==null && $month==null && $operator!=null) {
                $query2 = "SELECT MONTH(transdate) as id, SUM(SMSData.sms_rate) as amount FROM SMSData WHERE  Operator=:operator and YEAR(transdate)=:year GROUP by id";
                $result2=DB::select($query2, ['operator'=>$operator, 'year'=>$year]);
            }
            elseif ($year!=null && $quarter==null && $month!=null && $operator!=null) {
                $query2 = "SELECT DAY(transdate) as id, SUM(SMSData.sms_rate) as amount FROM SMSData WHERE  Operator=:operator and YEAR(transdate)=:year and MONTH(transdate)=:month GROUP by id";
                $result2=DB::select($query2, ['operator'=>$operator, 'year'=>$year, 'month'=>$month]);
            }
        }


        $masterTable = array();
        $rows = array();
        $table = array();
        $table2 = array();
        $rows2 = array();

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
        if( $year != null && $month != null && $operator==null && $industry==null){
            $query3 = "SELECT COUNT(transdate) cnt FROM `SMSData` WHERE (SELECT year(max(transdate)) FROM SMSData) = :year and (SELECT month(max(transdate)) FROM SMSData) = :month";
            $result3=DB::select($query3, ['year'=>$year, 'month'=>$month]);
            if($result3[0]->{'cnt'}){
                $query4 = "SELECT transdate a, weekday(transdate) weekday, sum(sms_rate) amount, (SELECT sum(sms_rate) FROM SMSData WHERE transdate = (a - INTERVAL 1 day)) priviousday , (100 + ( 100 * ((SELECT sum(sms_rate) FROM SMSData WHERE transdate = (a - INTERVAL 7 day)) - (SELECT sum(sms_rate) FROM SMSData WHERE transdate = (a - INTERVAL 8 day))) / (SELECT sum(sms_rate) FROM SMSData WHERE transdate = (a - INTERVAL 8 day)) ) ) per FROM SMSData WHERE transdate > (SELECT max(transdate) - INTERVAL 7 day FROM SMSData) GROUP by transdate";
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

        if($operator!=null || $industry!=null){
            $table2['cols'] = array(
                array('label' => 'id', 'type' => 'string'),
                array('label' => $request->input('operatorName').$request->input('industry').' Amount', 'type' => 'number'),
                array('label' => 'h', 'type' => 'number')
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

        if($department1!=null){
            $masterTable['data'] = array(
                $table2, $table
            );
        }
        else
            $masterTable['data'] = array(
                $table, $table2
            );



        // convert data into JSON format
        $jsonTable = json_encode($masterTable);
        return $jsonTable;

    }

    //.......................JSON Data For VR chart in SMS which compare multiple day,month,year................................
    public function SMSDynamicChartFilter(Request $request){
        $pdoparameter = [];
        $year=$request->input('year');
        $month=$request->input('month');
        $day=$request->input('day');
        $week_day=$request->input('weekday');
        $operator=$request->input('operator');
        $industry=$request->input('industry');
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

        if($request->input('operator')!=null){
            $operator ="( :operator )";
            $pdoparameter['operator']=$request->input('operator');
        }

        if($request->input('industry')!=null){
            $industry ="( :industry )";
            $pdoparameter['industry']=$request->input('industry');
        }

//        if($request->input('easy')!=null){
//            $easy ="( :easy )";
//            $pdoparameter['easy']=$request->input('easy');
//        }


        DB::enableQueryLog();
        if($month==null && $year==null && $day!=null){
            $string = "select day(SMSData.transdate) as day,sum( SMSData.sms_rate) as amount from SMSData ";
            if($industry!=null)  $string.=", smsMaster ";
            $string.=" where Day(SMSData.transdate) in ".$day." ";
            if($operator!=null)  $string.=" and SMSData.Operator=".$operator." ";
            if($industry!=null)  $string.=" and smsMaster.Department=".$industry." and smsMaster.stakeholder=SMSData.Stakeholder ";
            $string.=" Group by day";
            $result = DB::select($string, $pdoparameter);

            $rows = array();
            $table = array();
            $tempArray=array();
            $tempArray[]=array('label' => 'id', 'type' => 'string');
            //$resultMonth=$connection->query("Select distinct(day(SMSData.transdate)) as day from SMSData where day(SMSData.transdate) in (".$day.") group by day");
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
                $temp[] = array('v' => $r->{'amount'});
                $temp[] = array('v' => ($r->{'amount'}/1000000));
                $rows[] = array('c' => $temp);
            }
            $table['rows'] = $rows;
            //  var_dump($table);
            $jsonTable = json_encode($table);
            echo $jsonTable;
        }
        else if($day==null && $year==null && $month!=null){
            $string = "select month(SMSData.transdate) as month, sum( SMSData.sms_rate) as amount from SMSData ";
            if($industry!=null)  $string.=", smsMaster ";
            $string.=" where Month(SMSData.transdate) in ".$month." ";
            if($operator!=null)  $string.=" and SMSData.Operator=".$operator." ";
            if($industry!=null)  $string.=" and smsMaster.Department=".$industry." and smsMaster.stakeholder=SMSData.Stakeholder ";

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
                $temp[] = array('v' => $r->{'amount'});
                $temp[] = array('v' => ($r->{'amount'}/1000000));
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
            $string = "select YEAR(SMSData.transdate) as y, sum( SMSData.sms_rate) as amount from SMSData ";
            if($industry!=null)  $string.=", smsMaster ";
            $string.=" where YEAR(SMSData.transdate) in ".$year." ";
            if($operator!=null)  $string.=" and SMSData.Operator=".$operator." ";
            if($industry!=null)  $string.=" and smsMaster.Department=".$industry." and smsMaster.stakeholder=SMSData.Stakeholder ";

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
                $temp[] = array('v' => $r->{'amount'});
                $temp[] = array('v' => ($r->{'amount'}/1000000));
                $rows[] = array('c' => $temp);
            }
            $table['rows'] = $rows;
            //   var_dump($table);
            $jsonTable = json_encode($table);
            return $jsonTable;

        }
        else if ($year==null && $day!=null && $month!=null){
            $string = "select month(SMSData.transdate) as month,Day(SMSData.transdate) as day, sum( SMSData.sms_rate) as amount from SMSData ";
            if($industry!=null)  $string.=", smsMaster ";
            $string.=" where Day(SMSData.transdate) in ".$day." ";
            $string.=" and Month(SMSData.transdate) in ".$month." ";
            if($operator!=null)  $string.=" and SMSData.Operator=".$operator." ";
            if($industry!=null)  $string.=" and smsMaster.Department=".$industry." and smsMaster.stakeholder=SMSData.Stakeholder ";

            $string.=" Group by day,month";
            $result = DB::select($string, $pdoparameter);

            $rows = array();
            $table = array();
            $tempArray=array();
            $tempArray[]=array('label' => 'id', 'type' => 'string');
            $queryparameter="";
            $query = "Select distinct(day(SMSData.transdate)) as day from SMSData where day(SMSData.transdate) in ".$day." ";
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
                $tempArray[$r->{'month'}][$r->{'day'}]=(int)$r->{'amount'};
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
            $string = "select year(SMSData.transdate) as year,day(SMSData.transdate) as day, sum( SMSData.sms_rate) as amount from SMSData ";
            if($industry!=null)  $string.=", smsMaster ";
            $string.=" where year(SMSData.transdate) in ".$year." ";
            $string.=" and day(SMSData.transdate) in ".$day." ";
            if($operator!=null)  $string.=" and SMSData.Operator=".$operator." ";
            if($industry!=null)  $string.=" and smsMaster.Department=".$industry." and smsMaster.stakeholder=SMSData.Stakeholder ";

            $string.=" Group by year,day";
            $result = DB::select($string, $pdoparameter);
//            dd($result);
            $rows = array();
            $table = array();
            $tempArray=array();
            $tempArray[]=array('label' => 'id', 'type' => 'string');
//            $resultMonth=$connection->query("Select distinct(day(SMSData.transdate)) as day from SMSData where day(SMSData.transdate) in ".$day." ");
            $queryparameter="";
            $query = "Select distinct(day(SMSData.transdate)) as day from SMSData where day(SMSData.transdate) in ".$day." ";
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
                $tempArray[$r->{'year'}][$r->{'day'}]=(int)$r->{'amount'};
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
            $string = "select year(SMSData.transdate) as year,month(SMSData.transdate) as month, sum( SMSData.sms_rate) as amount from SMSData ";
            if($industry!=null)  $string.=", smsMaster ";
            $string.=" where year(SMSData.transdate) in ".$year." ";
            $string.=" and Month(SMSData.transdate) in ".$month." ";
            if($operator!=null)  $string.=" and SMSData.Operator=".$operator." ";
            if($industry!=null)  $string.=" and smsMaster.Department=".$industry." and smsMaster.stakeholder=SMSData.Stakeholder ";

            $string.=" Group by year,month";
            $result = DB::select($string, $pdoparameter);

            $rows = array();
            $table = array();
            $tempArray=array();
            $tempArray[]=array('label' => 'id', 'type' => 'string');
//            $resultMonth=$connection->query("Select distinct(month(SMSData.transdate)) as month from SMSData where month(SMSData.transdate) in (".$month.") ");
            $queryparameter="";
            $query = "Select distinct(month(SMSData.transdate)) as month from SMSData where month(SMSData.transdate) in ".$month." ";
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
                $tempArray[$r->{'year'}][$r->{'month'}]=(int)$r->{'amount'};
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
            $query = "Select distinct(day(SMSData.transdate)) as day from SMSData where day(SMSData.transdate) in ".$day." ";
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
                $string = "select year(SMSData.transdate) as year,month(SMSData.transdate) as month,Day(SMSData.transdate) as day, sum( SMSData.sms_rate) as amount from SMSData ";
                if($industry!=null)  $string.=", smsMaster ";
                $string.=" where Day(SMSData.transdate) in ".$day." ";
                $string.=" and Month(SMSData.transdate) in ".$month." ";
                $string.=" and year(SMSData.transdate) = :year";
                $pdoparameter['year'] = $r2;
                if($operator!=null)  $string.=" and SMSData.Operator=".$operator." ";
                if($industry!=null)  $string.=" and smsMaster.Department=".$industry." and smsMaster.stakeholder=SMSData.Stakeholder ";

                $string.=" Group by year,month,day";
                $result = DB::select($string, $pdoparameter);
//                dd($result);
                foreach($result as $r) {
                    $tempArray[$r->{'month'}][$r->{'day'}]=(int)$r->{'amount'};
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
