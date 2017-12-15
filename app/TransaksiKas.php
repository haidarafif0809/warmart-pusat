<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TransaksiKas extends Model
{

    protected $fillable = ['no_faktur', 'jenis_transaksi', 'tipe_transaksi', 'jumlah_masuk', 'jumlah_keluar', 'kas', 'warung_id'];

//HITUNGA TOTAL KAS
    public static function total_kas($request)
    {
        $sum_kas = TransaksiKas::select(DB::raw('SUM(jumlah_masuk - jumlah_keluar) as total_kas'))->where('kas', $request->kas)
            ->where('warung_id', Auth::user()->id_warung)->first();
        return $sum_kas->total_kas;
    }
}
