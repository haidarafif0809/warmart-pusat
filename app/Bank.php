<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;


class Bank extends Model
{

	use LogsActivity;

    //Kolom mana saja yg boleh diisi / update melalui method create atau update
    protected $fillable = ['nama_bank','atas_nama','no_rek'];



    
}
