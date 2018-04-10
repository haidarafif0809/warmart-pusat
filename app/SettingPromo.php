<?php

namespace App;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Yajra\Auditable\AuditableTrait;
use Auth;


class SettingPromo extends Model
{
	use Notifiable;
	protected $fillable = ['id_produk', 'baner_promo', 'harga_coret','id_warung','dari_tanggal','sampai_tanggal','jenis_promo','status'];
	protected $primaryKey = 'id_setting_promo';

	public function barang()
	{
		return $this->hasOne('App\Barang', 'id', 'id_produk');
	}

    public function tanggalSql($tangal)
    {
        $date        = date_create($tangal);
        $date_format = date_format($date, "Y-m-d");
        return $date_format;
    }


	    // DATA PENJUALAN PIUTANG
    public static function settingPromoTanggal($produks)
    {

        $query_setting = SettingPromo::select(['setting_promos.dari_tanggal','setting_promos.sampai_tanggal'])
            ->leftJoin('waktu_setting_promos', 'waktu_setting_promos.id_setting_promo', '=', 'setting_promos.id_setting_promo')
            ->leftJoin('filter_setting_promos', 'filter_setting_promos.id', '=', 'waktu_setting_promos.waktu_promo')
            ->where('setting_promos.id_produk', '=', $produks->id);

        return $query_setting;
    }

    // DATA PENJUALAN PIUTANG
    public static function settingPromoData($produks,$dari_tanggal,$sampai_tanggal)
    {

        $query_setting = SettingPromo::select(['setting_promos.harga_coret as harga_coret','filter_setting_promos.name as name'])
            ->leftJoin('waktu_setting_promos', 'waktu_setting_promos.id_setting_promo', '=', 'setting_promos.id_setting_promo')
            ->leftJoin('filter_setting_promos', 'filter_setting_promos.id', '=', 'waktu_setting_promos.waktu_promo')
            ->where('setting_promos.id_produk', '=', $produks->id)
            ->where(DB::raw('CURDATE()'), '>=',$dari_tanggal)
            ->where(DB::raw('CURDATE()'), '<=',$sampai_tanggal);

        return $query_setting;
    }
}
