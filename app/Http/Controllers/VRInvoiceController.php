<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Client;
use App\AKVRDaily;
use App\VRClientComm;
use DB;
use App\Log;

class VRInvoiceController extends Controller
{
  use Log;
    //.......................JSON Data For Top Ten Kam in VR................................
    public function __construct(){

    }
    public function index(){
        $this->log('VR Invoice','VR Invoice ','VR');
        $data['title']="VR Invoice";
        $data['client']=DB::table("client as a")->join('VR_Client_Comm as b', 'a.client_id','=','b.client_id')->distinct("client_id")->selectRaw('a.client_id as client_id,a.client_name as client_name')->get();
        // $data['month']=AKVRDaily::select(DB::raw('MonthName(transdate) month, month(transdate) m'))->distinct("month")->get();
        $data['year']=AKVRDaily::select(DB::raw('YEAR(transdate) year'))->distinct("YEAR(transdate)")->get();
        // return json_encode($data);
        return view('vr.vrinvoice',compact('data'));
    }
    //.......................JSON Data For Selection option................................
    public function init(){
        $data['client']=DB::table("client as a")->join('VR_Client_Comm as b', 'a.client_id','=','b.client_id')->distinct("client_id")->selectRaw('a.client_id client_id,a.client_name client_name')->get();
        // $data['month']=AKVRDaily::select(DB::raw('MonthName(transdate) month, month(transdate) m'))->distinct("month")->get();
        // $data['year']=AKVRDaily::select(DB::raw('YEAR(transdate) year'))->distinct("month")->get();
        return json_encode($data);
    }
    //.......................JSON Data For Top Ten Kam in VR................................

    public function VRSummaryInvoice(Request $request){
        try{
            $parameter=[];
             $querys="SELECT operator.operator_name as operator_name,operator.operator_id as operator_id1,
             MonthName(AK_VR_Daily.transdate) as month, sum(AK_VR_Daily.amount) as amount,AK_VR_Daily.client_id client_id1,
             client.client_name as client_name,
            sum((CASE
                 WHEN VR_Client_Comm.client_commission>0 THEN VR_Client_Comm.client_commission*AK_VR_Daily.amount
                 WHEN VR_Client_Comm.client_commission=0 THEN operator.rate*AK_VR_Daily.amount
             END)) as commission,
             sum((CASE
                 WHEN VR_Client_Comm.client_commission>0 THEN VR_Client_Comm.client_commission*AK_VR_Daily.amount
                 WHEN VR_Client_Comm.client_commission=0 THEN operator.rate*AK_VR_Daily.amount
             END)*0.1) as ait,
             sum((CASE
                 WHEN VR_Client_Comm.client_commission>0 THEN VR_Client_Comm.client_commission*AK_VR_Daily.amount
                 WHEN VR_Client_Comm.client_commission=0 THEN operator.rate*AK_VR_Daily.amount
             END)*(0.9*VR_Client_Comm.ssl_rate)) as sslportion,
             sum((CASE
                 WHEN VR_Client_Comm.client_commission>0 THEN VR_Client_Comm.client_commission*AK_VR_Daily.amount
                 WHEN VR_Client_Comm.client_commission=0 THEN operator.rate*AK_VR_Daily.amount
             END)*(0.9*VR_Client_Comm.clicent_rate)) as clientportion ,
             sum(AK_VR_Daily.amount-(CASE
                 WHEN VR_Client_Comm.client_commission>0 THEN VR_Client_Comm.client_commission*AK_VR_Daily.amount
                 WHEN VR_Client_Comm.client_commission=0 THEN operator.rate*AK_VR_Daily.amount
             END)*(0.9*VR_Client_Comm.clicent_rate)) as aramount
             from operator,AK_VR_Daily,VR_Client_Comm,client where AK_VR_Daily.client_id=client.client_id
             and VR_Client_Comm.client_id =AK_VR_Daily.client_id and AK_VR_Daily.operator_id=operator.operator_id
             and VR_Client_Comm.operator_id = AK_VR_Daily.operator_id ";

             if($request->input('clients')){
                $querys.=" and AK_VR_Daily.client_id= ? ";
                $parameter[]=$request->input('clients');
            }
             if($request->input('months')){
                $querys.=" and MonthName(AK_VR_Daily.transdate)= ? ";
                $parameter[]=$request->input('months');
            }
             if($request->input('years')){
                $querys.=" and YEAR(AK_VR_Daily.transdate)= ? ";
                $parameter[]=$request->input('years');
            }

            $querys.=" Group by MonthName(AK_VR_Daily.transdate),AK_VR_Daily.client_id,operator.operator_id";
            $querys="select *from (".$querys." ) a left join VR_Client_Comm b on a.client_id1=b.client_id and a.operator_id1=b.operator_id";
            // return json_encode([$querys]);
            $result=DB::select($querys,$parameter);
            return json_encode($result);
        }catch(\Exception $e){
            \Log::error($e->getMessage());
        }
    }
    //.......................JSON Data For Top Ten Kam in VR................................

    public function VRUpdatePayment(Request $request){
      try{
        $parameter=[];
        $parameter[]=$request->input('invoice_id')?$request->input('invoice_id'):"";
        $parameter[]=(double)$request->input('amount');
        $parameter[]=$request->input('date');
        $parameter[]=(int)$request->input('client');
        $parameter[]=(int)$request->input('month')+1;
        $parameter[]=(int)$request->input('year');
        if((int)$request->input('amount')<0)return "Amount should Positive!!!";
        $query='UPDATE AK_VRReceivable set invoice_id=? ,
              AK_VRReceivable.collection=AK_VRReceivable.collection+?,
              AK_VRReceivable.running_ar=AK_VRReceivable.receivable-AK_VRReceivable.collection,
              AK_VRReceivable.collection_date=? , 
              status=(case when AK_VRReceivable.receivable-AK_VRReceivable.collection>0.99 
              then 0 else 1 end)where
              AK_VRReceivable.client_id=? and 
              AK_VRReceivable.month=? and
              AK_VRReceivable.year=?';
        DB::statement($query,$parameter);
        return "Payment Update Successfully";
    }catch(\Exception $e){
      \Log::error($e->getMessage());
    }
    }    


    //.......................JSON Data For Top Ten Kam in VR................................

    public function VRInvoice(Request $request){
        // return json_encode([$request->input('years'),$request->input('months')]);
        try{
            $parameter=[];
            $query="Select client.client_name,
            AK_VRReceivable.invoice_id,
            AK_VRReceivable.year,
            AK_VRReceivable.month,
            AK_VRReceivable.receivable,
            if(AK_VRReceivable.collection_date='2000-01-01','',AK_VRReceivable.collection_date) AS  collection_date,
              AK_VRReceivable.receivable as masud,
              AK_VRReceivable.collection,
              AK_VRReceivable.running_ar,
            if(AK_VRReceivable.status=false,'Pending','Paid') status
             FROM AK_VRReceivable 
             left join client on AK_VRReceivable.client_id = client.client_id
             where client.client_id is not null ";
            if($request->input('clients')!=null){
                $query.=" and AK_VRReceivable.client_id=? ";
                $parameter[]=$request->input('clients');
            }
            if($request->input('months')!=null){
               $query.=" and AK_VRReceivable.month= ? ";              
               $parameter[]=$request->input('months')+1;
            }
            if($request->input('years')!=null){
              $query.=" and AK_VRReceivable.year=? ";
              $parameter[]=$request->input('years');
            }
            // return $query;
            $result=DB::select($query,$parameter);
            return json_encode($result);
        }catch(\Exception $e){
            \Log::error($e->getMessage());
        }
    }

    //.......................JSON Data For Top Ten Kam in VR................................


    public function VROperatorWiseInvoice(Request $request){
        try{
            $parameter=[];
            $query="SELECT operator.operator_name ,sum(AK_VR_Daily.amount) amount, MonthName(AK_VR_Daily.transdate) month,month(AK_VR_Daily.transdate) m, operator.rate,sum(AK_VR_Daily.amount)*(operator.rate) operator_com,sum(AK_VR_Daily.amount)-sum(AK_VR_Daily.amount)*(operator.rate) net_amount FROM AK_VR_Daily,operator where operator.operator_id=AK_VR_Daily.operator_id ";

            if($request->input('months')!=null){
                $query.=" and MonthName(AK_VR_Daily.transdate)= ? ";
                $parameter[]=$request->input('months');
            }
            if($request->input('years')!=null){
                $query.=" and year(AK_VR_Daily.transdate)=? ";
                $parameter[]=$request->input('years');
            }
            $query.=" GROUP BY m, MonthName(AK_VR_Daily.transdate),AK_VR_Daily.operator_id";
            $result = DB::select($query,$parameter);
            // return json_encode([$parameter]);
            return json_encode($result);
    }catch(\Exception $e){
        \Log::error($e->getMessage());
    }
    }

    //.......................JSON Data For Top Ten Kam in VR................................

    public function VREasyInvoice(Request $request){
        try{
            $parameter=[];
            $query="SELECT client.client_name,operator.operator_name,sum(AK_VR_Daily.amount) amount , MonthName(AK_VR_Daily.transdate) month,month(AK_VR_Daily.transdate) m FROM client,operator,AK_VR_Daily where operator.operator_id=AK_VR_Daily.operator_id and client.client_id=AK_VR_Daily.client_id and client.easy_client!=0 and operator.operator_id!=7";

            if($request->input('months')!=null){
                $query.=" and MonthName(AK_VR_Daily.transdate)=? ";
                $parameter[]=$request->input('months');
              }

            if($request->input('years')!=null){
                $query.=" and year(AK_VR_Daily.transdate)=? ";
                $parameter[]=$request->input('years');
              }
            $query.=" GROUP BY m, MonthName(AK_VR_Daily.transdate) , operator.operator_id,client.client_id";
            // return $query;
            $result=DB::select($query,$parameter);
            return $result;
    }catch(\Exception $e){
        \Log::error($e->getMessage());
    }
        
    }

    //.......................JSON Data For Top Ten Kam in VR Invoice................................

    public function VRPaidInvoice(Request $request){
        try{
            $parameter=[];
            $query="Select client.client_name,
            AK_VRReceivable.invoice_id,
            AK_VRReceivable.year,
            AK_VRReceivable.month,
            AK_VRReceivable.receivable,
            if(AK_VRReceivable.collection_date='2000-01-01','',AK_VRReceivable.collection_date) AS  collection_date,
              AK_VRReceivable.receivable as masud,
              AK_VRReceivable.collection,
              AK_VRReceivable.running_ar,
            if(AK_VRReceivable.status=false,'Pending','Paid') status
             FROM AK_VRReceivable 
             left join client on AK_VRReceivable.client_id = client.client_id
             where client.client_id is not null and AK_VRReceivable.status=true ";
            if($request->input('clients')!=null){
                $query.=" and AK_VRReceivable.client_id=? ";
                $parameter[]=$request->input('clients');
            }
            if($request->input('months')!=null){
               $query.=" and AK_VRReceivable.month= ? ";              
               $parameter[]=$request->input('months');
            }
            if($request->input('years')!=null){
              $query.=" and AK_VRReceivable.year=? ";
              $parameter[]=$request->input('years');
            }
            $result=DB::select($query,$parameter);
            return json_encode($result);
        }catch(\Exception $e){
            \Log::error($e->getMessage());
        }
    }

    //.......................JSON Data For Top Ten Kam in VR Invoice................................

    public function VRUnpaidInvoice(Request $request){
        try{
            $parameter=[];
            $query="Select 'test' test,client.client_name,
            AK_VRReceivable.invoice_id,
            AK_VRReceivable.year,
            AK_VRReceivable.month,
            AK_VRReceivable.receivable,
            if(AK_VRReceivable.collection_date='2000-01-01','',AK_VRReceivable.collection_date) AS  collection_date,
              AK_VRReceivable.receivable as masud,
              AK_VRReceivable.collection,
              AK_VRReceivable.running_ar,
            if(AK_VRReceivable.status=false,'Pending','Paid') status
             FROM AK_VRReceivable 
             left join client on AK_VRReceivable.client_id = client.client_id
             where client.client_id is not null and AK_VRReceivable.status=false ";
            if($request->input('clients')!=null){
                $query.=" and AK_VRReceivable.client_id=? ";
                $parameter[]=$request->input('clients');
            }
            if($request->input('months')!=null){
               $query.=" and AK_VRReceivable.month= ? ";              
               $parameter[]=$request->input('months');
            }
            if($request->input('years')!=null){
              $query.=" and AK_VRReceivable.year=? ";
              $parameter[]=$request->input('years');
            }
            // return $query;
            $result=DB::select($query,$parameter);
            return json_encode($result);
        }catch(\Exception $e){
            \Log::error($e->getMessage());
        }
    }

    //.......................JSON Data For Easy Qubee in VR Invoice................................

    public function VREasyQubeeInvoice(Request $request){
        try{
            $parameter=[];
            $query="SELECT client.client_name,operator.operator_name,sum(AK_VR_Daily.amount) amount , MonthName(AK_VR_Daily.transdate) month,month(AK_VR_Daily.transdate) m FROM client,operator,AK_VR_Daily where operator.operator_id=AK_VR_Daily.operator_id and client.client_id=AK_VR_Daily.client_id and client.easy_client!=0 and operator.operator_id=7";

            if($request->input('months')!=null){
                $query.=" and MonthName(AK_VR_Daily.transdate)=? ";
                $parameter[]=$request->input('months');
              }

            if($request->input('years')!=null){
                $query.=" and year(AK_VR_Daily.transdate)=? ";
                $parameter[]=$request->input('years');

              }
            $query.=" GROUP BY m, MonthName(AK_VR_Daily.transdate) , operator.operator_id,client.client_id";
            return DB::select($query,$parameter);
        }catch(\Exception $e){
            \Log::error($e->getMessage());
        }
    }


 

}
