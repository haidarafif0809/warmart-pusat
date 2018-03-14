<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SettingJasaPengiriman extends Model
{
    public $fillable = ['jasa_pengiriman', 'tampil_jasa_pengiriman', 'default_jasa_pengiriman', 'warung_id'];
}
