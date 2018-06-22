<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TrackerLog extends Model
{
    //


    protected $table = 'tracker_log';


    public function route_path(){ 

        return $this->hasOne('App\RoutePathLog','id','route_path_id'); 
    
    } 

}
