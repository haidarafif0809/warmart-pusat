<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Laravel\Scout\Searchable;
use Spatie\Activitylog\Traits\LogsActivity;
use Yajra\Auditable\AuditableTrait;

class Barang extends Model
{
    //
    use AuditableTrait;
    use LogsActivity;
    use Searchable;

    protected $fillable = ['kode_barang', 'kode_barcode', 'nama_barang', 'harga_beli', 'harga_jual', 'satuan_id', 'kategori_barang_id', 'status_aktif', 'hitung_stok', 'id_warung', 'deskripsi_produk', 'konfirmasi_admin', 'foto', 'harga_jual2', 'berat','foto_1','foto_2'];

    public function satuan()
    {
        return $this->hasOne('App\Satuan', 'id', 'satuan_id');
    }

    public function kategori_barang()
    {
        return $this->hasOne('App\KategoriBarang', 'id', 'kategori_barang_id');
    }

    public function warung()
    {
        return $this->hasOne('App\Warung', 'id', 'id_warung');
    }

    public function getHppAttribute()
    {

        $hpp_penjulan = Hpp::select('harga_unit')->where('id_produk', $this->id)->where('warung_id', Auth::user()->id_warung)
        ->where('jenis_hpp', 2)->orderBy('id', 'DESC');

        if ($hpp_penjulan->count() > 0) {
                $hpp_penjulan_terakhir =  $hpp_penjulan->first()->harga_unit;
        }else{
                $hpp_penjulan_terakhir = $this->harga_beli;
        }

           $hpp_terakhir = Hpp::select(['jenis_hpp'])->where('id_produk', $this->id)->where('warung_id', Auth::user()->id_warung)
                ->orderBy('created_at', 'DESC');

                if ($hpp_terakhir->count() > 0){
                        // HITUNG HPP
                        if ($hpp_terakhir->first()->jenis_hpp == 1) {

                            $hpp_masuk = Hpp::select(['harga_unit', 'jumlah_masuk'])->where('id_produk', $this->id)->where('warung_id', Auth::user()->id_warung)
                            ->where('jenis_hpp', 1)->orderBy('id', 'DESC')->first();

                            $stok_sekarang = $this->stok;
                            $stok_produk = $stok_sekarang - $hpp_masuk->jumlah_masuk;

                            //HPP
                            return $hpp_produk = ( ($hpp_penjulan_terakhir * $stok_produk) + ($hpp_masuk->harga_unit * $hpp_masuk->jumlah_masuk) ) / ($stok_produk + $hpp_masuk->jumlah_masuk);

                        }else{
                            //HPP
                            return $hpp_produk = $hpp_penjulan_terakhir;
                        }
                        // HITUNG HPP
            }else{
                return $hpp_produk = $hpp_penjulan_terakhir;
            }

    }


    public function getNamaAttribute()
    {
        return title_case($this->nama_barang);
    }

    public function getNamaProdukAttribute()
    {
        return title_case($this->nama_barang);
    }

    public function getRupiahAttribute()
    {
        return 'Rp ' . number_format($this->harga_jual, 0, ',', '.');
    }

    public function getStokAttribute()
    {

        $stok = Hpp::select([DB::raw('IFNULL(SUM(jumlah_masuk),0) - IFNULL(SUM(jumlah_keluar),0) as stok_produk')])->where('id_produk', $this->id)->first();

        return $stok->stok_produk;

    }

    // MUTASI STOK
    public function scopeDaftarProduk($query_mutasi_stok)
    {
        $query_mutasi_stok = Barang::select(['barangs.id', 'barangs.kode_barang', 'barangs.nama_barang', 'satuans.nama_satuan'])
        ->leftJoin('satuans', 'satuans.id', '=', 'barangs.satuan_id')
        ->where('barangs.id_warung', Auth::user()->id_warung);

        return $query_mutasi_stok;
    }

    // CARI MUTASI STOK
    public function scopeCariDaftarProduk($query_mutasi_stok, $request)
    {
        $search = $request->search;

        $query_mutasi_stok = Barang::select(['barangs.id', 'barangs.kode_barang', 'barangs.nama_barang', 'satuans.nama_satuan'])
        ->leftJoin('satuans', 'satuans.id', '=', 'barangs.satuan_id')
        ->where(function ($query) use ($search) {
            $query->orwhere('barangs.kode_barang', 'LIKE', '%' . $search . '%')
            ->orwhere('barangs.nama_barang', 'LIKE', '%' . $search . '%')
            ->orwhere('satuans.nama_satuan', 'LIKE', '%' . $search . '%');
        })
        ->where('barangs.id_warung', Auth::user()->id_warung);

        return $query_mutasi_stok;
    }

}
