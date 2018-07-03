<?php

namespace App;

use Illuminate\Database\Eloquent\Model; 
use Illuminate\Support\Facades\DB;  
use Spatie\Activitylog\Traits\LogsActivity; 
use Yajra\Auditable\AuditableTrait; 
use Auth; 

class SettingPenjualanPos extends Model
{

	protected $fillable = ['jumlah_produk','stok','harga_jual','id_warung']; 
}
