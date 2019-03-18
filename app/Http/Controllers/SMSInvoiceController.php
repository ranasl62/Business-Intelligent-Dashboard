<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SMSMaster;
use App\SMSData;
use DB;
use App\Log;
class SMSInvoiceController extends Controller
{
	use Log;
    public function index(){
        $this->log('SMS Invoice','SMS Invoice ','SMS');
        $data['title']="SMS Invoice";
		$data['company']=SMSMaster::select("company")->distinct("company")->get();
		$data['month']=SMSData::select(DB::raw('MonthName(transdate) month'))->distinct("month")->get();
		$data['year']=SMSData::select(DB::raw('YEAR(transdate) year'))->distinct("year")->get();
        return view('sms.smsinvoice',compact('data'));
    }
    public function init(){
    	// return json_encode($data);
    }
    //============================SMS Summary Invoice========================
    public function SMSSummaryInvoice(Request $request){
    	try{
    		 // return "Rana"
	    	$pdoparameter=[];
	    	$query="SELECT smsMaster.company,smsMaster.stakeholder,s.smstype   ,smsMaster.stakeholder_type,s.month,s.year,if(smsMaster.SD_RAT=-1,0,smsMaster.SD_RAT),if(smsMaster.VAT_RAT=-1,0,smsMaster.VAT_RAT),if(smsMaster.Sarcharge_1=-1,0,smsMaster.Sarcharge_1),smsMaster.sms_rate unit_price,s.smscount smscount,s.netamount amount,
			(s.netamount*if(smsMaster.SD_RAT=-1,0,smsMaster.SD_RAT)) sd_amount,
			(s.netamount*if(smsMaster.SD_RAT=-1,0,smsMaster.SD_RAT) +s.netamount) amount_with_sd,
			(s.netamount*if(smsMaster.SD_RAT=-1,0,smsMaster.SD_RAT) +s.netamount)*(if(smsMaster.VAT_RAT=-1,0,smsMaster.VAT_RAT)) vat_amount,
			(s.netamount*if(smsMaster.SD_RAT=-1,0,smsMaster.SD_RAT) +s.netamount)*(if(smsMaster.VAT_RAT=-1,0,smsMaster.VAT_RAT))+(s.netamount*if(smsMaster.SD_RAT=-1,0,smsMaster.SD_RAT) +s.netamount) amount_with_vat,
			(s.netamount)*(if(smsMaster.Sarcharge_1=-1,0,smsMaster.Sarcharge_1)) sarcharge_amount,
			((s.netamount*if(smsMaster.SD_RAT=-1,0,smsMaster.SD_RAT) +s.netamount)*(if(smsMaster.VAT_RAT=-1,0,smsMaster.VAT_RAT))+(s.netamount*if(smsMaster.SD_RAT=-1,0,smsMaster.SD_RAT) +s.netamount) +
			(s.netamount)*(if(smsMaster.Sarcharge_1=-1,0,smsMaster.Sarcharge_1)))  invoice_amount
			from (SELECT sum(SMSData.amount) smscount,MonthName(SMSData.transdate) month,year(SMSData.transdate) year,sum(SMSData.sms_rate) netamount,SMSData.Stakeholder stakeholder,(case when SMSData.Operator!='INTERNATIONAL' then 'Local' when SMSData.Operator='INTERNATIONAL' then 'International' end) smstype from SMSData ";
	    	$a=0;

	            if($request->input('months')!=null){
	            	if($a==0)$query.=" where ";
	            	else $query.=" and ";
	                $data=explode(',',$request->input('months'));
	                $query.="  MonthName(SMSData.transdate) IN (";
	                foreach($data as $key => $value){
	                    $pdoparameter[]=$value;
	                    if($key==0)$query.=" ?";
	                    else $query.=",?";
	                }
	                $query.=") ";
	                $a++;

	            }

	            if($request->input('years')!=null){
	            	if($a==0)$query.=" where ";
	            	else $query.=" and ";
	                $data=explode(',',$request->input('years'));
	                $query.="  YEAR(SMSData.transdate) IN (";
	                foreach($data as $key => $value){
	                    $pdoparameter[]=$value;
	                    if($key==0)$query.=" ?";
	                    else $query.=",?";
	                }
	                $query.=") ";

	            }
	    	$query.=" GROUP BY SMSData.Stakeholder,MonthName(SMSData.transdate), smstype,year(SMSData.transdate)) s join smsMaster on smsMaster.stakeholder=s.stakeholder and (((LCASE(s.smstype)='international' and LCASE(smsMaster.stakeholder_type)='intenational')) or ((LCASE(s.smstype)!='international' and LCASE(smsMaster.stakeholder_type)!='intenational')))";
    		if( $request->input('company')!=null){
                $query.=" and smsMaster.company IN (?)";
                $pdoparameter[]=$request->input('company');
                
            }
	    	// return $query;
	    	$result=DB::select($query,$pdoparameter);
	        return json_encode($result);
    	}catch(\Exception $e){
    		\Log::error($e->getMessage());
    }


    }
    //============================SMS Invoice========================
    public function SMSInvoice(Request $request){
    	   	try{
    		 
	    		$month=$request->input("months");
    	   		$year=$request->input("years");
    	   		$company=$request->input("company");

		    	$result=DB::table('AK_SMSReceivable')
	                ->when($month, function ($query , $month){
	                    return $query->where('month', $month);
	                })
	                ->when($year,function($query, $year){
	                	return $query->where('year',$year);
	                })
	                ->when($company,function($query, $company){
	                	return $query->where('company',$company);
	                })
	                ->get();
	        return json_encode($result);
    	}catch(\Exception $e){
    		\Log::error($e->getMessage());
    }

    }
    //============================SMS Paid Invoice========================

    public function SMSPaid(Request $request){
    	   	try{
    	   		$month=$request->input("months");
    	   		$year=$request->input("years");
    	   		$company=$request->input("company");

		    	$result=DB::table('AK_SMSReceivable')
		    		->where('status',1)
	                ->when($month, function ($query , $month){
	                    return $query->where('month', $month);
	                })
	                ->when($year,function($query, $year){
	                	return $query->where('year',$year);
	                })
	                ->when($company,function($query, $company){
	                	return $query->where('company',$company);
	                })
	                ->get();
	        return json_encode($result);
    	}catch(\Exception $e){
    		\Log::error($e->getMessage());
    }

    }
    //============================SMS Unpaid Invoice========================

    public function SMSUnpaid(Request $request){
    	   	try{
	    		$month=$request->input("months");
	    		// $date = date_parse($month);
	    		// $month=$data['month'];
    	   		$year=$request->input("years");
    	   		$company=$request->input("company");

		    	$result=DB::table('AK_SMSReceivable')
		    		->where('status',0)
	                ->when($month, function ($query , $month){
	                    return $query->where('month', $month);
	                })
	                ->when($year,function($query, $year){
	                	return $query->where('year',$year);
	                })
	                ->when($company,function($query, $company){
	                	return $query->where('company',$company);
	                })
	                ->get();
	        return json_encode($result);
    	}catch(\Exception $e){
    		\Log::error($e->getMessage());
    }

    }
    //============================SMS Unpaid Invoice========================
    public function SMSPayment(Request $request){
    	   	try{
	    	$parameter=[];
	        $parameter[]=$request->input('invoice_id')?$request->input('invoice_id'):"";
	        $parameter[]=(double)$request->input('amount');
	        $parameter[]=$request->input('date');
	        $parameter[]=$request->input('company');
	        $parameter[]=$request->input('month');
	        $parameter[]=(int)$request->input('year');
	        if((int)$request->input('amount')<0)return "Amount should Positive!!!";
	        $query='UPDATE AK_SMSReceivable set invoice_id=? ,
	              AK_SMSReceivable.collection=AK_SMSReceivable.collection+?,
	              AK_SMSReceivable.running_ar=AK_SMSReceivable.receivable-AK_SMSReceivable.collection,
	              AK_SMSReceivable.collection_date=? , 
	              status=(case when AK_SMSReceivable.receivable-AK_SMSReceivable.collection>0.99 
	              then 0 else 1 end)where
	              AK_SMSReceivable.company=? and 
	              AK_SMSReceivable.month=? and
	              AK_SMSReceivable.year=?';
        DB::statement($query,$parameter);
        return "Payment Update Successfully";
    	}catch(\Exception $e){
    		\Log::error($e->getMessage());
    }

    }

}
