<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Client;
use App\Operator;
use DB;
use App\Log;
class VRReportController extends Controller
{
    use Log;
    //-----------------------for load view ------------------------------
    
    public function index(){
        $this->log('VR Report','VR Report ','VR');
        $data['title']="VR Report";
        $data['client']=Client::select('client_id','client_name')->get();
        $data['operator']=Operator::select('operator_id','operator_name')->distinct('operator_name')->get();
        $data['department']=Client::select('Department')->distinct('Department')->where('Department','!=','null')->where('Department','!=','#N/A')->Where('Department','!=','0')->get();
        $data['kam']=Client::select('KAM')->distinct('KAM')->where('KAM','!=','null')->where('KAM','!=','#N/A')->Where('KAM','!=','0')->get();
        // return json_encode($data);
        return view('vr.vrreport',compact('data'));
    }

    //-----------------------for initial data load (select option) ------------------------------
    
    public function init(Request $request){
        try{
            $data=[];
            $data['client']=[];
            $data['operator']=[];
            $data['department']=[];
            $data['kam']=[];
            if($request->client==1){
               if($request->kam==0 && $request->department==0){
                   $data['department']=Client::select('Department')->distinct('Department')->where('Department','!=','null')->where('Department','!=','#N/A')->Where('Department','!=','0')->whereIn('client_id',$request->client_val)->get();
                   $data['kam']=Client::select('KAM')->distinct('KAM')->where('KAM','!=','null')->where('KAM','!=','#N/A')->Where('KAM','!=','0')->whereIn('client_id',$request->client_val)->get();

               }else if($request->kam==2){
                   $data['department']=Client::select('Department')->distinct('Department')->where('Department','!=','null')->where('Department','!=','#N/A')->Where('Department','!=','0')->whereIn('client_id',$request->client_val)->whereIn('kam',$request->kam_val)->get();

               }else if($request->department==2){
                   $data['kam']=Client::select('KAM')->distinct('KAM')->where('KAM','!=','null')->where('KAM','!=','#N/A')->Where('KAM','!=','0')->whereIn('client_id',$request->client_val)->whereIn('department',$request->department_val)->get();

               }

            }else if($request->kam==1){
                if($request->client==0 && $request->department==0){
                    $data['client']=Client::select('client_id','client_name')->whereIn('kam',$request->kam_val)->get();
                    $data['department']=Client::select('Department')->distinct('Department')->where('Department','!=','null')->where('Department','!=','#N/A')->Where('Department','!=','0')->whereIn('kam',$request->kam_val)->get();


                }else if($request->client==2){
                    $data['department']=Client::select('Department')->distinct('Department')->where('Department','!=','null')->where('Department','!=','#N/A')->Where('Department','!=','0')->whereIn('kam',$request->kam_val)->whereIn('client_id',$request->client_val)->get();

                }else if($request->department==2){
                    $data['client']=Client::select('client_id','client_name')->whereIn('kam',$request->kam_val)->whereIn('department',$request->department_val)->get();

                }
            }else if($request->department==1){
                if($request->kam==0 && $request->client==0) {
                    $data['client'] = Client::select('client_id', 'client_name')->whereIn('department', $request->department_val)->get();
                    $data['kam'] = Client::select('KAM')->distinct('KAM')->where('KAM', '!=', 'null')->where('KAM', '!=', '#N/A')->Where('KAM', '!=', '0')->whereIn('department', $request->department_val)->get();
                }else if($request->client==2){
                    $data['kam']=Client::select('KAM')->distinct('KAM')->where('KAM','!=','null')->where('KAM','!=','#N/A')->Where('KAM','!=','0')->whereIn('department',$request->department_val)->whereIn('client_id',$request->client_val)->get();
                }else if($request->kam==2){
                    $data['client']=Client::select('client_id','client_name')->whereIn('department',$request->department_val)->whereIn('kam',$request->kam_val)->get();

                }
            }
            $this->log('VR Report','VR Report ','VR');
            if($request->client==0 && $request->kam==0 && $request->department==0) {
                $data['client']=Client::select('client_id','client_name')->get();
//                $data['operator']=Operator::select('operator_id','operator_name')->distinct('operator_name')->get();
                $data  ['department']=Client::select('Department')->distinct('Department')->where('Department','!=','null')->where('Department','!=','#N/A')->Where('Department','!=','0')->get();
                $data['kam']=Client::select('KAM')->distinct('KAM')->where('KAM','!=','null')->where('KAM','!=','#N/A')->Where('KAM','!=','0')->get();
            }
            return json_encode($data);
        }catch(Exception $e){
            \LOG::error($e-getMessage());
        }
    }
    //-----------------------VR summary report after Filter data ------------------------------

    public function VRSummaryReport(Request $request){
        
        try{
            $pdoparameter=[];
            $query="Select sum(AK_VR_Daily.Amount) as amount ";

            if($request->input('fromdate')!=null && $request->input('todate')!=null){
                $query.=" and transdate BETWEEN ? and ? ";
                $pdoparameter[]=$request->input('fromdate');
                $pdoparameter[]=$request->input('todate');
            }
            $groupby=' group by ';
            $from="from ";
            $where="where AK_VR_Daily.client_id=client.client_id ";
            if( $request->input('clients')!=null){
                $query=" client.client_name as client,";
                $groupby.=" client";
                $from.=" client ";
  
            }

            if($request->input('departments')!=null){
                $query.="client.Department as department";
                $groupby.=" department";
                if($request->input('clients')==null)$from.=" client ";
            }

            if($request->input('kams')!=null){
                $query.=" client.KAM kam ";
                $groupby.=" kam";
                if($request->input('clients')==null)$from.=" client ";
            }

            if($request->input('operators')!=null){


            }
            $query.="FROM `AK_VR_Daily`,client,operator 
            WHERE AK_VR_Daily.client_id=client.client_id and AK_VR_Daily.operator_id=operator.operator_id ";
            if( $request->input('clients')!=null){
                
                $data=explode(",",$request->input('clients'));
                // return $data;
                $query.=" and client.client_id IN (";
                foreach($data as $key => $value){
                    $pdoparameter[]=$value;
                    if($key==0)$query.=" ?";
                    else $query.=",?";
                }
                $query.=") ";        
            }

            if($request->input('departments')!=null){
                $data=explode(',',$request->input('departments'));
                $query.=" and client.Department IN (";
                foreach($data as $key => $value){
                    $pdoparameter[]=$value;
                    if($key==0)$query.=" ?";
                    else $query.=",?";
                }
                $query.=") ";
            }

            if($request->input('kams')!=null){
                $data=explode(',',$request->input('kams'));
                $query.=" and client.KAM IN (";
                foreach($data as $key => $value){
                    $pdoparameter[]=$value;
                    if($key==0)$query.=" ?";
                    else $query.=",?";
                }
                $query.=") ";

            }

            if($request->input('operators')!=null){
                $data=explode(',',$request->input('operators'));
                $query.=" and operator.operator_id IN (";
                foreach($data as $key => $value){
                    $pdoparameter[]=$value;
                    if($key==0)$query.=" ?";
                    else $query.=",?";
                }
                $query.=") ";

            }
            // $query.=" GROUP by                                                                                                                                                                                                                                                                                                                                                                  client.client_name,client.KAM,client.Department,operator.operator_name";
            DB::enableQueryLog();
            // return $pdoparameter;
            $result=DB::select($query,$pdoparameter);
            // return DB::getQueryLog();
            return json_encode($result);
    }catch(\Exception $e){
        Log::error($e->getMessage());
    }        
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            }                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            //-----------------------VR report after Filter data ------------------------------

    public function VRReport(Request $request){
        try{
            $pdoparameter=[];
            $query="Select AK_VR_Daily.transdate as transdate, client.client_name as client, 
                        client.KAM as KAM, client.Department as department, operator.operator_name as operator, 
                        sum(AK_VR_Daily.Amount) as amount FROM `AK_VR_Daily`,client,operator 
                        WHERE AK_VR_Daily.client_id=client.client_id and AK_VR_Daily.operator_id=operator.operator_id ";

            if($request->input('fromdate')!=null && $request->input('todate')!=null){
                $query.=" and transdate BETWEEN ? and ? ";
                $pdoparameter[]=$request->input('fromdate');
                $pdoparameter[]=$request->input('todate');
            }

            if( $request->input('clients')!=null){
                
                $data=explode(",",$request->input('clients'));
                // return $data;
                $query.=" and client.client_id IN (";
                foreach($data as $key => $value){
                    $pdoparameter[]=$value;
                    if($key==0)$query.=" ?";
                    else $query.=",?";
                }
                $query.=") ";        
            }

            if($request->input('departments')!=null){
                $data=explode(',',$request->input('departments'));
                $query.=" and client.Department IN (";
                foreach($data as $key => $value){
                    $pdoparameter[]=$value;
                    if($key==0)$query.=" ?";
                    else $query.=",?";
                }
                $query.=") ";
            }

            if($request->input('kams')!=null){
                $data=explode(',',$request->input('kams'));
                $query.=" and client.KAM IN (";
                foreach($data as $key => $value){
                    $pdoparameter[]=$value;
                    if($key==0)$query.=" ?";
                    else $query.=",?";
                }
                $query.=") ";

            }

            if($request->input('operators')!=null){
                $data=explode(',',$request->input('operators'));
                $query.=" and operator.operator_id IN (";
                foreach($data as $key => $value){
                    $pdoparameter[]=$value;
                    if($key==0)$query.=" ?";
                    else $query.=",?";
                }
                $query.=") ";

            }
            $query.=" GROUP by  AK_VR_Daily.transdate,client.client_name,client.KAM,client.Department,operator.operator_name";
            DB::enableQueryLog();
            // return $pdoparameter;
            $result=DB::select($query,$pdoparameter);
            // return DB::getQueryLog();
            return json_encode($result);
    }catch(\Exception $e){
        Log::error($e->getMessage());
    }
}
}
