<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AKCardMaster;
use App\AKPGWDaily;
use App\Bank;
use DB;
use App\Log;
class PGWReportController extends Controller
{
    use Log;
    public function index(){
        $this->log('PGW Report','PGW Report ','PGW');
        $data['title']="PGW Report";
        $data['stakeholder']=AKPGWDaily::select('strid')->distinct('strid')->get();
        $data['bank']=Bank::select('bname')->distinct('bname')->get();
        $data['card']=AKCardMaster::select('Card')->distinct('Card')->get();
        return view('pgw.pgwreport',compact('data'));
    }
        //-----------------------for initial data load (select option) ------------------------------
    
    public function init(Request $request){
        try{

            $data=[];
            $data['bank']=[];
            $data['operator']=[];
            $data['stakeholder']=[];
            $data['card']=[];
            if($request->bank==1){
                if($request->card==0 && $request->stakeholder==0){
                    $data['stakeholder']=bank::select('stakeholder')->distinct('stakeholder')->where('stakeholder','!=','null')->where('stakeholder','!=','#N/A')->Where('stakeholder','!=','0')->whereIn('bank',$request->bank_val)->get();
                    $data['card']=bank::select('card')->distinct('card')->where('card','!=','null')->where('card','!=','#N/A')->Where('card','!=','0')->whereIn('bank',$request->bank_val)->get();

                }else if($request->card==2){
                    $data['stakeholder']=bank::select('stakeholder')->distinct('stakeholder')->where('stakeholder','!=','null')->where('stakeholder','!=','#N/A')->Where('stakeholder','!=','0')->whereIn('bank',$request->bank_val)->whereIn('card',$request->card_val)->get();

                }else if($request->stakeholder==2){
                    $data['card']=bank::select('card')->distinct('card')->where('card','!=','null')->where('card','!=','#N/A')->Where('card','!=','0')->whereIn('bank',$request->bank_val)->whereIn('stakeholder',$request->stakeholder_val)->get();

                }

            }else if($request->card==1){
                if($request->bank==0 && $request->stakeholder==0){
                    $data['bank']=bank::select('bank','bank_name')->whereIn('card',$request->card_val)->get();
                    $data['stakeholder']=bank::select('stakeholder')->distinct('stakeholder')->where('stakeholder','!=','null')->where('stakeholder','!=','#N/A')->Where('stakeholder','!=','0')->whereIn('card',$request->card_val)->get();


                }else if($request->bank==2){
                    $data['stakeholder']=bank::select('stakeholder')->distinct('stakeholder')->where('stakeholder','!=','null')->where('stakeholder','!=','#N/A')->Where('stakeholder','!=','0')->whereIn('card',$request->card_val)->whereIn('bank',$request->bank_val)->get();

                }else if($request->stakeholder==2){
                    $data['bank']=bank::select('bank','bank_name')->whereIn('card',$request->card_val)->whereIn('stakeholder',$request->stakeholder_val)->get();

                }
            }else if($request->stakeholder==1){
                if($request->card==0 && $request->bank==0) {
                    $data['bank'] = bank::select('bank', 'bank_name')->whereIn('stakeholder', $request->stakeholder_val)->get();
                    $data['card'] = bank::select('card')->distinct('card')->where('card', '!=', 'null')->where('card', '!=', '#N/A')->Where('card', '!=', '0')->whereIn('stakeholder', $request->stakeholder_val)->get();
                }else if($request->bank==2){
                    $data['card']=bank::select('card')->distinct('card')->where('card','!=','null')->where('card','!=','#N/A')->Where('card','!=','0')->whereIn('stakeholder',$request->stakeholder_val)->whereIn('bank',$request->bank_val)->get();
                }else if($request->card==2){
                    $data['bank']=bank::select('bank','bank_name')->whereIn('stakeholder',$request->stakeholder_val)->whereIn('card',$request->card_val)->get();

                }
            }
            $this->log('PGW Report','PGW Report ','PGW');
            if($request->bank==0 && $request->card==0 && $request->stakeholder==0) {
                $data['bank']=Bank::select('bank','bank_name')->get();
//                $data['operator']=Operator::select('operator_id','operator_name')->distinct('operator_name')->get();
                $data['stakeholder']=bank::select('stakeholder')->distinct('stakeholder')->where('stakeholder','!=','null')->where('stakeholder','!=','#N/A')->Where('stakeholder','!=','0')->get();
                $data['card']=bank::select('card')->distinct('card')->where('card','!=','null')->where('card','!=','#N/A')->Where('card','!=','0')->get();
            }

//
//
//        	// $data=json_encode([1,2]);
//            $data['stakeholder']=AKPGWDaily::select('strid')->distinct('strid')->get();
//            $data['bank']=Bank::select('bname')->distinct('bname')->get();
//            $data['card']=AKCardMaster::select('Card')->distinct('Card')->get();
            return json_encode($data);
        }catch(Exception $e){
            \LOG::error($e->getMessage());
        }
    }
     //-----------------------VR report after Filter data ------------------------------

    public function PGWReport(Request $request){
        try{
            $pdoparameter=[];
            $query="SELECT AK_PGW_Daily.transdate as transdate, bank.bname as bid, AK_Card_Master.Card 	 as cardtype, AK_PGW_Daily.strid as strid, sum(AK_PGW_Daily.mamount) as mamount, sum( AK_PGW_Daily.sslportion) as sslportion,sum(AK_PGW_Daily.bankportion) as bankportion, sum(AK_PGW_Daily.storeportion) as storeportion FROM `AK_PGW_Daily`, AK_Card_Master,bank WHERE AK_PGW_Daily.cardtype=AK_Card_Master.Card_type and AK_PGW_Daily.bid=bank.bid ";

            if($request->input('fromdate')!=null && $request->input('todate')!=null){
                $query.=" and transdate BETWEEN ? and ? ";
                $pdoparameter[]=$request->input('fromdate');
                $pdoparameter[]=$request->input('todate');
            }
            if( $request->input('stakeholders')!=null){                
                $data=explode(",",$request->input('stakeholders'));
                $query.=" and AK_PGW_Daily.strid IN (";
                foreach($data as $key => $value){
                    $pdoparameter[]=$value;
                    if($key==0)$query.=" ?";
                    else $query.=",?";
                }
                $query.=") ";        
            }

            if($request->input('banks')!=null){
                $data=explode(',',$request->input('banks'));
                $query.=" and bank.bname IN (";
                foreach($data as $key => $value){
                    $pdoparameter[]=$value;
                    if($key==0)$query.=" ?";
                    else $query.=",?";
                }
                $query.=") ";
            }

            if($request->input('cards')!=null){
                $data=explode(',',$request->input('cards'));
                $query.=" and AK_Card_Master.Card IN (";
                foreach($data as $key => $value){
                    $pdoparameter[]=$value;
                    if($key==0)$query.=" ?";
                    else $query.=",?";
                }
                $query.=") ";

            }

            $query.="  GROUP BY transdate,bank.bname,AK_Card_Master.Card,strid";
            DB::enableQueryLog();
            $result=DB::select($query,$pdoparameter);
            return json_encode($result);
    }catch(\Exception $e){
        Log::error($e->getMessage());
    }
}
}
