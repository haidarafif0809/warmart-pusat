<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SettingVerifikasi extends Model
{
    public $fillable = ['id_warung', 'email', 'no_telp'];
}
