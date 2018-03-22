<?php

namespace App;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Yajra\Auditable\AuditableTrait;

class SettingPromo extends Model
{
	use Notifiable;
	use AuditableTrait;
	protected $fillable = ['id_produk', 'baner_promo', 'harga_coret'];

	public function barang()
	{
		return $this->hasOne('App\Barang', 'id', 'id_produk');
	}
}
