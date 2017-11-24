<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Yajra\Auditable\AuditableTrait;
use Illuminate\Support\Facades\DB;
use Auth;

class Kas extends Model
{
	use AuditableTrait;
	use LogsActivity;

	protected $fillable = ['kode_kas','nama_kas','status_kas', 'default_kas', 'warung_id'];

	public function getTotalKasAttribute(){

		$sum_kas = TransaksiKas::select(DB::raw('SUM(jumlah_masuk - jumlah_keluar) as total_kas'))->where('kas', $this->id)
		->where('warung_id', Auth::user()->id_warung)->first();

		return 'Rp '. number_format($sum_kas->total_kas,0,',','.');
	}

}
