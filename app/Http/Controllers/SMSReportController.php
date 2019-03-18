<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SMSData;
use App\SMSMaster;
use DB;
use App\Log;
class SMSReportController extends Controller
{
    use Log;
    public function index(){
        $data['operator']=SMSData::select('Operator')->distinct('Operator')->get();
        $data['Stakeholder']=SMSMaster::select('stakeholder')->distinct('stakeholder')->get();
        $data['company']=SMSMaster::select('company')->distinct('company')->get();
        $data['KAM']=SMSMaster::select('KAM')->distinct('KAM')->get();
        // return json_encode($data);
        $this->log('SMS Report','SMS Report ','SMS');
        $data['title']="SMS Report";
        return view('sms.smsreport',compact('data'));
    }
    public function init(Request $request){
        try{
            $data=[];
//            $data['company']=[];
            $data['Stakeholder']=[];
            $data['company']=[];
            $data['KAM']=[];
            
            if($request->company==1){
                if($request->kam==0 && $request->stakeholder==0){
                    $data['Stakeholder']=SMSMaster::select('stakeholder')->distinct('stakeholder')->whereIn('company',$request->company_val)->get();
                    $data['KAM']=SMSMaster::select('KAM')->distinct('KAM')->whereIn('company',$request->company_val)->get();
                }else if($request->kam==2){
                    $data['Stakeholder']=SMSMaster::select('stakeholder')->distinct('stakeholder')->whereIn('KAM',$request->kam_val)->whereIn('company',$request->company_val)->get();
                }else if($request->stakeholder==2){
                    $data['KAM']=SMSMaster::select('KAM')->distinct('KAM')->whereIn('stakeholder',$request->stakeholder_val)->whereIn('company',$request->company_val)->get();
                }

            }else if($request->kam==1){
                if($request->company==0 && $request->stakeholder==0){
                    $data['Stakeholder']=SMSMaster::select('stakeholder')->distinct('stakeholder')->whereIn('KAM',$request->kam_val)->get();
                    $data['company']=SMSMaster::select('company')->distinct('company')->whereIn('KAM',$request->kam_val)->get();

                }else if($request->company==2){
                    $data['Stakeholder']=SMSMaster::select('stakeholder')->distinct('stakeholder')->whereIn('company',$request->company_val)->whereIn('KAM',$request->kam_val)->get();

                }else if($request->stakeholder==2){
                    $data['company']=SMSMaster::select('company')->distinct('company')->whereIn('stakeholder',$request->stakeholder_val)->whereIn('KAM',$request->kam_val)->get();
                }
            }else if($request->stakeholder==1){
                if($request->kam==0 && $request->company==0) {
                    $data['company']=SMSMaster::select('company')->distinct('company')->whereIn('stakeholder',$request->stakeholder_val)->get();
                    $data['KAM']=SMSMaster::select('KAM')->distinct('KAM')->whereIn('stakeholder',$request->stakeholder_val)->get();
                }else if($request->company==2){
                    $data['KAM']=SMSMaster::select('KAM')->distinct('KAM')->whereIn('company',$request->company_val)->whereIn('stakeholder',$request->stakeholder_val)->get();
                }else if($request->kam==2){
                    $data['company']=SMSMaster::select('company')->distinct('company')->whereIn('KAM',$request->kam_val)->whereIn('stakeholder',$request->stakeholder_val)->get();
                }
            }
            $this->log('VR Report','VR Report ','VR');
            if($request->company==0 && $request->kam==0 && $request->stakeholder==0) {
                $data['Stakeholder']=SMSMaster::select('stakeholder')->distinct('stakeholder')->get();
                $data['company']=SMSMaster::select('company')->distinct('company')->get();
                $data['KAM']=SMSMaster::select('KAM')->distinct('KAM')->get();
            }
            return json_encode($data);
        }catch(\Exception $e){
            \Log::error($e->getMessage());
        }
    }

    public function SMSReport(Request $request){
        try{
        	// return json_encode($request->input('companies'));
        	$pdoparameter=[];
            $query="SELECT SMSData.transdate as transdate, SMSData.Stakeholder as stakeholder,
					smsMaster.company as company, smsMaster.KAM as KAM,SMSData.Operator as operator ,
					SMSData.amount as cnt, SMSData.sms_rate as amount FROM
					SMSData,smsMaster WHERE SMSData.Stakeholder=smsMaster.stakeholder ";

            if($request->input('fromdate')!=null && $request->input('todate')!=null){
                $query.=" and transdate BETWEEN ? and ? ";
                $pdoparameter[]=$request->input('fromdate');
                $pdoparameter[]=$request->input('todate');
            }

            if( $request->input('companies')!=null){
                
                $data=explode(",",$request->input('companies'));
                $query.=" and smsMaster.company IN (";
                foreach($data as $key => $value){
                    $pdoparameter[]=$value;
                    if($key==0)$query.=" ?";
                    else $query.=",?";
                }
                $query.=") ";        
            }

            if($request->input('stakeholders')!=null){
                $data=explode(',',$request->input('stakeholders'));
                $query.=" and SMSData.stakeholder IN (";
                foreach($data as $key => $value){
                    $pdoparameter[]=$value;
                    if($key==0)$query.=" ?";
                    else $query.=",?";
                }
                $query.=") ";
            }

            if($request->input('kams')!=null){
                $data=explode(',',$request->input('kams'));
                $query.=" and smsMaster.KAM IN (";
                foreach($data as $key => $value){
                    $pdoparameter[]=$value;
                    if($key==0)$query.=" ?";
                    else $query.=",?";
                }
                $query.=") ";

            }
            if($request->input('operators')!=null){
                $data=explode(',',$request->input('operators'));
                // return json_encode($data);
                $query.=" and SMSData.Operator IN (";
                foreach($data as $key => $value){
                    $pdoparameter[]=$value;
                    if($key==0)$query.=" ?";
                    else $query.=",?";
                }
                $query.=") ";

            }
            // $query.=" GROUP by  AK_VR_Daily.transdate,client.client_name,client.KAM,client.Department,operator.operator_name";
            DB::enableQueryLog();
            // return $pdoparameter;
            $result=DB::select($query,$pdoparameter);
            // return DB::getQueryLog();
            return json_encode($result);

        }catch(\Exception $e){
        	\Log::error($e->getMessage());
        }
    }
}
