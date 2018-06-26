<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Error extends Model
{
    //


    protected $table = 'tracker_errors';



   	public function log(){ 

        return $this->hasOne('App\TrackerLog','error_id','id'); 
    
    } 
    
}
