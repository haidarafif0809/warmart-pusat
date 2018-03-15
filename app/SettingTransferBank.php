<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SettingTransferBank extends Model
{
    public $fillable = ['nama_bank', 'tampil_bank', 'default_bank', 'warung_id'];
}
