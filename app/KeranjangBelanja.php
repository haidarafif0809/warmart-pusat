<?php

namespace App;

use Auth;
use DB;
use Illuminate\Database\Eloquent\Model;
use Session;

class KeranjangBelanja extends Model
{
    //
    protected $fillable   = ['id_produk', 'id_pelanggan', 'jumlah_produk', 'session_id', 'warung_id','harga_produk'];
    protected $primaryKey = 'id_keranjang_belanja';
    // relasi ke produk
    public function produk()
    {
        return $this->hasOne('App\Barang', 'id', 'id_produk');
    }
    // relasi ke pelanggan
    public function pelanggan()
    {
        return $this->hasOne('App\User', 'id', 'id_pelanggan');
    }

    public function scopeJumlahBelanja($query)
    {
        if(!Session::get('session_id')){
            $session_id    = session()->getId();
        }else{
            $session_id = Session::get('session_id');
        }

        if (Auth::check() == false) {
            return $query->where('session_id', $session_id)->count();
        }else{
            return $query->where('id_pelanggan', Auth::user()->id)->count();
        }
    }

    public function getNamaProdukAttribute()
    {
        return title_case($this->produk->nama_barang);

    }

    public function getNamaProdukMobileAttribute()
    {
        if (strlen(strip_tags($this->produk->nama_barang)) <= 33) {

            $nama_produk = title_case(strip_tags($this->produk->nama_barang));
        } else {

            $nama_produk = title_case('' . strip_tags(substr($this->produk->nama_barang, 0, 30)) . '...');
        }

        return $nama_produk;
    }

    public function scopeKeranjangBelanjaPelanggan($query,$warung_id)
    {

        $query->select('keranjang_belanjas.id_keranjang_belanja AS id_keranjang_belanja', 'keranjang_belanjas.id_produk AS id_produk', 'keranjang_belanjas.jumlah_produk AS jumlah_produk', 'keranjang_belanjas.harga_produk AS harga_jual', 'barangs.id_warung AS id_warung','barangs.nama_barang AS nama_barang','barangs.foto AS foto')
        ->leftJoin('barangs', 'keranjang_belanjas.id_produk', '=', 'barangs.id')
        ->where('id_pelanggan', Auth::user()->id)->where('warung_id',$warung_id)->orderBy('barangs.id_warung');

        return $query;

    }

    public function scopeKeranjangBelanjaSession($query,$session_id,$warung_id)
    {

        $query->select('keranjang_belanjas.id_keranjang_belanja AS id_keranjang_belanja', 'keranjang_belanjas.id_produk AS id_produk', 'keranjang_belanjas.jumlah_produk AS jumlah_produk', 'keranjang_belanjas.harga_produk AS harga_jual', 'barangs.id_warung AS id_warung','barangs.nama_barang AS nama_barang','barangs.foto AS foto')
        ->leftJoin('barangs', 'keranjang_belanjas.id_produk', '=', 'barangs.id')
        ->where('session_id', $session_id)->where('warung_id',$warung_id)->orderBy('barangs.id_warung');

        return $query;

    }

    public function scopeHitungTotalPesanan($query, $id_warung)
    {

        $query->select(DB::raw('SUM(keranjang_belanjas.jumlah_produk) as total_produk'), DB::raw('SUM(keranjang_belanjas.jumlah_produk * keranjang_belanjas.harga_produk) as total_pesanan'))
        ->leftJoin('barangs', 'keranjang_belanjas.id_produk', '=', 'barangs.id')
        ->where('id_pelanggan', Auth::user()->id)
        ->where('barangs.id_warung', $id_warung);

        return $query;

    }

    public function scopeHitungTotalPesananSession($query, $id_warung,$session_id)
    {

        $query->select(DB::raw('SUM(keranjang_belanjas.jumlah_produk) as total_produk'), DB::raw('SUM(keranjang_belanjas.jumlah_produk * keranjang_belanjas.harga_produk) as total_pesanan'))
        ->leftJoin('barangs', 'keranjang_belanjas.id_produk', '=', 'barangs.id')
        ->where('session_id', $session_id)
        ->where('barangs.id_warung', $id_warung);

        return $query;

    }
}
