<?php
namespace App;
use Spatie\Activitylog\Models\Activity;
use Auth;
trait Log
{
    public  function log($logName=null,$description=null,$property=nulll,$model=null){
    	if($model!=null)
        activity($logName)
       ->causedBy(Auth::user()->id)
       ->performedOn($model)
       ->withProperties($property)
       ->log($description);
       else 
       	activity($logName)
       ->causedBy(Auth::user()->id)
       ->withProperties($property)
       ->log($description);
    }
}
?>