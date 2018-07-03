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

	protected $fillable = ['kode_kas','nama_kas','status_kas', 'default_kas', 'id_bank', 'warung_id'];

	public function getTotalKasAttribute(){

		$sum_kas = TransaksiKas::select(DB::raw('SUM(jumlah_masuk - jumlah_keluar) as total_kas'))->where('kas', $this->id)
		->where('warung_id', Auth::user()->id_warung)->first();

		return 'Rp '. number_format($sum_kas->total_kas,0,',','.');
	}

	//QUERY DATAA KAS
	public function scopeDataKas($query_kas)
	{
		$query_kas = KaS::select(['kas.kode_kas', 'kas.nama_kas', 'kas.status_kas', 'kas.default_kas', 'kas.id', 'bank_warungs.atas_nama', 'bank_warungs.no_rek'])
		->leftJoin('bank_warungs', 'bank_warungs.id' , '=', 'kas.id_bank')
		->where('kas.warung_id', Auth::user()->id_warung);

		return $query_kas;
	}

}
