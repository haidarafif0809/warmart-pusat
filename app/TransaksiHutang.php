<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laratrust\Traits\LaratrustUserTrait;
use Yajra\Auditable\AuditableTrait;

class TransaksiHutang extends Model
{

	use LaratrustUserTrait;
	use AuditableTrait;

	protected $fillable = ['jenis_transaksi','suplier_id','no_faktur','jumlah_masuk','jumlah_keluar','warung_id'];

}
