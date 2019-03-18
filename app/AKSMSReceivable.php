<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AKSMSReceivable extends Model
{
    protected $table='AK_SMSReceivable';
    protected $guarded=[];
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps=false;
}
