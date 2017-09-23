<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    //Kolom mana saja yg boleh diisi / update melalui method create atau update
    protected $fillable = ['nama_bank','atas_nama','no_rek'];
}
