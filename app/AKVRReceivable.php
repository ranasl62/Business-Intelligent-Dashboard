<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AKVRReceivable extends Model
{
    protected $table="AK_VRReceivable";
    protected $guarded=[];
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps=false;
}
