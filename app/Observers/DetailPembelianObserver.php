<?php 
namespace App\Observers;

use Illuminate\Support\Facades\DB;
use App\DetailPembelian;
use App\Hpp;
use App\EditTbsPembelian;
use App\Barang;
use Auth;
use Session;

class DetailPembelianObserver
{
	public function creating(DetailPembelian $DetailPembelian){

		$total_nilai = $DetailPembelian->jumlah_produk *  $DetailPembelian->harga_produk; 

		Hpp::create(['no_faktur' => $DetailPembelian->no_faktur, 'id_produk' => $DetailPembelian->id_produk, 'jenis_transaksi' => 'pembelian', 'jumlah_masuk' => $DetailPembelian->jumlah_produk, 'harga_unit' => $DetailPembelian->harga_produk, 'total_nilai' => $total_nilai, 'jenis_hpp' => '1','warung_id'=>$DetailPembelian->warung_id]);

		return true;
		
		
   	} // OBERVERS CREATING

    //HAPUS ITEM MASUK
   	public function deleting(DetailPembelian $DetailPembelian){

   		// AMBIL TOTAL PRODUK UNTUK FAKTUR INI , PERPRODUK DAN PER WARUNG YG LOGIN
   		$edit_tbs_pembelian = EditTbsPembelian::select([DB::raw('SUM(jumlah_produk) as total_produk')])->where('no_faktur',$DetailPembelian->no_faktur)->where('id_produk', $DetailPembelian->id_produk)->where('warung_id',$DetailPembelian->warung_id);

   		// AMBIL STOK SAAT INI DAN STOK DARI FAKTUR INI JANGAN DIHITUNG
   		$stok = Hpp::select([DB::raw('IFNULL(SUM(jumlah_masuk),0) - IFNULL(SUM(jumlah_keluar),0) as stok_produk')])->where('no_faktur' ,'!=',$DetailPembelian->no_faktur)->where('id_produk', $DetailPembelian->id_produk)
   		->where('warung_id', $DetailPembelian->warung_id)->first();

   		// STOK PRODUK 
   		$data = $edit_tbs_pembelian->first()->total_produk + $stok->stok_produk;  

   		// JIKA TOTAL TBS PMBELIAN PRODUK DARI FAKTUR INI KURANG DARI NOL
   		if ($edit_tbs_pembelian->first()->total_produk == 0) {

   			// JIKA PRODUK INI DIHAPUS DAN STOKNYA JADI MINUS ATAU KURANG DARI NOL
   			if ($stok->stok_produk < 0) {
   				// MUNCUL ALERT 
               $barang = Barang::where('id',$DetailPembelian->id_produk)->where('id_warung',$DetailPembelian->warung_id)->first();
               $pesan_alert = 
               '<div class="container-fluid">
               <div class="alert-icon">
               <i class="material-icons">error</i>
               </div>
               <b>Gagal : Stok "'.$barang->nama_barang.'" Tidak Mencukupi Jika Anda Hapus</b>
               </div>';

               Session::flash("flash_notification", [
                 "level"     => "danger",
                 "message"   => $pesan_alert
              ]);

               return false;
            }
   			else{ // JIKA TIDAK
   				// SELECT HPP UNTUK FAKTUR INI, PRODUK INI, JENIS HPP == 1 DAN WARUNG INI
   				$hpp = Hpp::where('no_faktur', $DetailPembelian->no_faktur)->where('id_produk', $DetailPembelian->id_produk)->where('jenis_hpp', 1)
   				->where('warung_id', $DetailPembelian->warung_id);

   				// DELETE HPP
   				if (!$hpp->delete()) {
   					return false;
   				}
   				else{
   					return true;
   				} 
   			} 

   			// JIKA TOTAL TBS EDIT PEMBELIAN LEBIH DARI NOL
   		}elseif ($edit_tbs_pembelian->count() > 0) {
   			// JIKA STOK PRODUK KURANG DARI NOL
   			if ($data < 0) {
   				// MUNCUL ALERT
               $barang = Barang::where('id',$DetailPembelian->id_produk)->where('id_warung',$DetailPembelian->warung_id)->first();
               $pesan_alert = 
               '<div class="container-fluid">
               <div class="alert-icon">
               <i class="material-icons">error</i>
               </div>
               <b>Gagal : Stok "'.$barang->nama_barang.'" Tidak Mencukupi </b>
               </div>';

               Session::flash("flash_notification", [
                "level"     => "danger",
                "message"   => $pesan_alert
             ]);

               return false;
            }
   			else{ // JIKA TIDAK
   				  // SELECT HPP UNTUK FAKTUR INI, PRODUK INI, JENIS HPP == 1 DAN WARUNG INI
   				$hpp = Hpp::where('no_faktur', $DetailPembelian->no_faktur)->where('id_produk', $DetailPembelian->id_produk)->where('jenis_hpp', 1)
   				->where('warung_id', $DetailPembelian->warung_id);
   				// HAPUS HPP
   				if (!$hpp->delete()) {
   					return false;
   				}
   				else{
   					return true;
   				} 
   			}

   		}// Elseif ($edit_tbs_pembelian->count() > 0)

   	}/// METHODE DELETING

   }