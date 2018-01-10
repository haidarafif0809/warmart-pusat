<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Yajra\Auditable\AuditableTrait;

class PendaftarTopos extends Model
{

	use AuditableTrait;
	protected $fillable = ['name', 'no_telp', 'email', 'alamat', 'lama_berlangganan', 'berlaku_hingga', 'jenis_pembayaran', 'total', 'bank_id', 'no_rekening', 'atas_nama', 'warung_id'];
}
