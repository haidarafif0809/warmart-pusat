<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use App\KasKeluar;
use App\TransaksiKas;
use App\User;
use URL;

class KasKeluarTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A basic test example.
     *
     * @return void
     */

       protected function setUp() {
        parent::setUp();
        // kode untuk menset base url nya jadi localhost
        //   karena kalau gak localhost jadi tidak bisa jalan testing http nya
        //  selalu responnya 404 
        URL::forceRootUrl('http://localhost');    
    }

    public function testExample()
    {
        $this->assertTrue(true);
    }

//CrudTest
    public function testCrudKasKeluar()
    {   
    	//MEMBUAT NO FAKTUR
    	$bulan_sekarang = date('m');

    	$tahun_sekarang = date('Y');
    	$tahun_terakhir = substr($tahun_sekarang, 2);

    	//mengecek jumlah karakter dari bulan sekarang
    	$cek_jumlah_bulan = strlen($bulan_sekarang);

    	//jika jumlah karakter dari bulannya sama dengan 1 maka di tambah 0 di depannya
    	if ($cek_jumlah_bulan == 1) {
    		$bulan_terakhir = "0".$bulan_sekarang;
    	}
    	else{
    		$bulan_terakhir = $bulan_sekarang;
    	}

    	//ambil bulan dan no_faktur dari tanggal kas_keluar terakhir
        $kas_keluar = KasKeluar::select([DB::raw('MONTH(created_at) bulan'), 'no_faktur'])->orderBy('id','DESC')->first();

        if ($kas_keluar != NULL) {
          $pisah_nomor = explode("/", $kas_keluar->no_faktur);
          $ambil_nomor = $pisah_nomor[0];
          $bulan_akhir = $kas_keluar->bulan;
        }
        else{
          $ambil_nomor = 1;
          $bulan_akhir = 13;
        }
         
	    /*jika bulan terakhir dari kas_keluar tidak sama dengan bulan sekarang, 
	      maka nomor nya kembali mulai dari 1, jika tidak maka nomor terakhir ditambah dengan 1
	    */
        if ($bulan_akhir != $bulan_sekarang) {
          $no_faktur = "1/KK/".$bulan_terakhir."/".$tahun_terakhir;
        }
        else {
          $nomor = 1 + $ambil_nomor ;
          $no_faktur = $nomor."/KK/".$bulan_terakhir."/".$tahun_terakhir;
        }

	        // TEST INSERT KAS KELUAR
	        $kas_test = KasKeluar::create(['no_faktur' => $no_faktur, 'kas' => '2', 'kategori' => '1', 'jumlah' => '50000', 'keterangan' => 'Makan Karyawan']);
	        $transaksi_kas = TransaksiKas::create(['no_faktur' => $no_faktur, 'jenis_transaksi'=>'kas_keluar', 'jumlah_keluar' => '50000', 'kas' => '2']);

	        //CEK DATABASE
	        $data_kas_keluar = $this->assertDatabaseHas('kas_keluars',['no_faktur' => $no_faktur, 'kas' => '2', 'kategori' => '1', 'jumlah' => '50000', 'keterangan' => 'Makan Karyawan']);
	        $data_transaksi_kas = $this->assertDatabaseHas('transaksi_kas',['no_faktur' => $no_faktur, 'jenis_transaksi'=>'kas_keluar', 'jumlah_keluar' => '50000', 'kas' => '2']);

	        // TEST UPDATE KAS KELUAR
	        KasKeluar::find($kas_test->id)->update(['kas' => '2', 'kategori' => '1', 'jumlah' => '75000', 'keterangan' => 'Makan 3 Karyawan']);
	        TransaksiKas::where('no_faktur', $kas_test->no_faktur)->update(['jumlah_keluar' => '75000', 'kas' => '2']);
	        
	        //CEK DATABASE        
	        $cek_kas_keluar = $this->assertDatabaseHas('kas_keluars',['kas' => '2', 'kategori' => '1', 'jumlah' => '75000', 'keterangan' => 'Makan 3 Karyawan']);
	        $cek_transaksi_kas = $this->assertDatabaseHas('transaksi_kas',['jumlah_keluar' => '75000', 'kas' => '2']);

	        $user = User::find(1);
	        $response = $this->actingAs($user)->json('POST', route('kas_keluar.destroy',$kas_test->id), ['_method' => 'DELETE']);

	        KasKeluar::destroy($kas_test->id);
	        TransaksiKas::where('no_faktur', $kas_test->no_faktur)->delete();

	        $cek_kas = $this->assertDatabaseMissing('kas_keluars',['kas' => '2', 'kategori' => '1', 'jumlah' => '75000', 'keterangan' => 'Makan 3 Karyawan']);
	        $cek_transaksi = $this->assertDatabaseMissing('transaksi_kas',['jenis_transaksi'=>'kas_keluar', 'jumlah_keluar' => '50000', 'kas' => '2']);
    }

//HttpTest
    //TAMBAH KAS KELUAR
    public function testHTTPTambahKasKeluar() {

        $user = User::find(1);

        $response = $this->actingAs($user)->json('POST', route('kas_keluar.store'), [
         'kas' 		  => '2',
         'kategori'   => '1',
         'jumlah' 	  => '50000',
         'keterangan' => 'Makan Karyawan'
        ]);

        //MEMBUAT NO FAKTUR
    	$bulan_sekarang = date('m');

    	$tahun_sekarang = date('Y');
    	$tahun_terakhir = substr($tahun_sekarang, 2);

    	//mengecek jumlah karakter dari bulan sekarang
    	$cek_jumlah_bulan = strlen($bulan_sekarang);

    	//jika jumlah karakter dari bulannya sama dengan 1 maka di tambah 0 di depannya
    	if ($cek_jumlah_bulan == 1) {
    		$bulan_terakhir = "0".$bulan_sekarang;
    	}
    	else{
    		$bulan_terakhir = $bulan_sekarang;
    	}

    	//ambil bulan dan no_faktur dari tanggal kas_keluar terakhir
        $kas_keluar = KasKeluar::select([DB::raw('MONTH(created_at) bulan'), 'no_faktur'])->orderBy('id','DESC')->first();

        if ($kas_keluar != NULL) {
          $pisah_nomor = explode("/", $kas_keluar->no_faktur);
          $ambil_nomor = $pisah_nomor[0];
          $bulan_akhir = $kas_keluar->bulan;
        }
        else{
          $ambil_nomor = 1;
          $bulan_akhir = 13;
        }
         
	    /*jika bulan terakhir dari kas_keluar tidak sama dengan bulan sekarang, 
	      maka nomor nya kembali mulai dari 1, jika tidak maka nomor terakhir ditambah dengan 1
	    */
        if ($bulan_akhir != $bulan_sekarang) {
          $no_faktur = "1/KK/".$bulan_terakhir."/".$tahun_terakhir;
        }
        else {
          $nomor = 1 + $ambil_nomor ;
          $no_faktur = $nomor."/KK/".$bulan_terakhir."/".$tahun_terakhir;
        }

	        $response->assertStatus(302)
	                 ->assertRedirect(route('kas_keluar.index'));
	        
	        //TAMBAH KAS KELUAR
	        $kas = KasKeluar::create(['no_faktur' => $no_faktur, 'kas' => '2', 'kategori' => '1', 'jumlah' => '50000', 'keterangan' => 'Makan Karyawan']);
	        $transaksi_kas = TransaksiKas::create(['no_faktur' => $no_faktur, 'jenis_transaksi'=>'kas_keluar', 'jumlah_keluar' => '50000', 'kas' => '2']);

	        $response2 = $this->get($response->headers->get('location'))->assertSee('Sukses : Berhasil Menambah Transaksi Kas Keluar Sebesar "50000"');

	        //CEK DB TABLE KAS
	        $response_kas = $this->assertDatabaseHas("kas_keluars",['no_faktur' => $no_faktur, 'kas' => '2', 'kategori' => '1', 'jumlah' => '50000', 'keterangan' => 'Makan Karyawan']);
	        $data_transaksi_kas = $this->assertDatabaseHas('transaksi_kas',['no_faktur' => $no_faktur, 'jenis_transaksi'=>'kas_keluar', 'jumlah_keluar' => '50000', 'kas' => '2']);
    }

    //HAPUS KAS KELUAR
    public function testHTTPHapusKasKeluar() {

    	//MEMBUAT NO FAKTUR
    	$bulan_sekarang = date('m');

    	$tahun_sekarang = date('Y');
    	$tahun_terakhir = substr($tahun_sekarang, 2);

    	//mengecek jumlah karakter dari bulan sekarang
    	$cek_jumlah_bulan = strlen($bulan_sekarang);

    	//jika jumlah karakter dari bulannya sama dengan 1 maka di tambah 0 di depannya
    	if ($cek_jumlah_bulan == 1) {
    		$bulan_terakhir = "0".$bulan_sekarang;
    	}
    	else{
    		$bulan_terakhir = $bulan_sekarang;
    	}

    	//ambil bulan dan no_faktur dari tanggal kas_keluar terakhir
        $kas_keluar = KasKeluar::select([DB::raw('MONTH(created_at) bulan'), 'no_faktur'])->orderBy('id','DESC')->first();

        if ($kas_keluar != NULL) {
          $pisah_nomor = explode("/", $kas_keluar->no_faktur);
          $ambil_nomor = $pisah_nomor[0];
          $bulan_akhir = $kas_keluar->bulan;
        }
        else{
          $ambil_nomor = 1;
          $bulan_akhir = 13;
        }
         
	    /*jika bulan terakhir dari kas_keluar tidak sama dengan bulan sekarang, 
	      maka nomor nya kembali mulai dari 1, jika tidak maka nomor terakhir ditambah dengan 1
	    */
        if ($bulan_akhir != $bulan_sekarang) {
          $no_faktur = "1/KK/".$bulan_terakhir."/".$tahun_terakhir;
        }
        else {
          $nomor = 1 + $ambil_nomor ;
          $no_faktur = $nomor."/KK/".$bulan_terakhir."/".$tahun_terakhir;
        }
        
	        //TAMBAH KAS
	        $kas = KasKeluar::create(['no_faktur' => $no_faktur, 'kas' => '2', 'kategori' => '1', 'jumlah' => '50000', 'keterangan' => 'Makan Karyawan']);
		    $transaksi_kas = TransaksiKas::create(['no_faktur' => $no_faktur, 'jenis_transaksi'=>'kas_keluar', 'jumlah_keluar' => '50000', 'kas' => '2']);

	        $user = User::find(1);

	        $response = $this->actingAs($user)->json('POST', route('kas_keluar.destroy',$kas->id), ['_method' => 'DELETE']);
	        TransaksiKas::where('no_faktur', $kas->no_faktur)->delete();

	        $this->get($response->headers->get('location'))->assertSee('Sukses : Berhasil Menghapus Transaksi Kas Keluar "'.$no_faktur.'"');              
        
    }

    //HALAMAN MENU EDIT KAS
    public function testHTTPUpdateKasKeluar(){

    	//MEMBUAT NO FAKTUR
    	$bulan_sekarang = date('m');

    	$tahun_sekarang = date('Y');
    	$tahun_terakhir = substr($tahun_sekarang, 2);

    	//mengecek jumlah karakter dari bulan sekarang
    	$cek_jumlah_bulan = strlen($bulan_sekarang);

    	//jika jumlah karakter dari bulannya sama dengan 1 maka di tambah 0 di depannya
    	if ($cek_jumlah_bulan == 1) {
    		$bulan_terakhir = "0".$bulan_sekarang;
    	}
    	else{
    		$bulan_terakhir = $bulan_sekarang;
    	}

    	//ambil bulan dan no_faktur dari tanggal kas_keluar terakhir
        $kas_keluar = KasKeluar::select([DB::raw('MONTH(created_at) bulan'), 'no_faktur'])->orderBy('id','DESC')->first();

        if ($kas_keluar != NULL) {
          $pisah_nomor = explode("/", $kas_keluar->no_faktur);
          $ambil_nomor = $pisah_nomor[0];
          $bulan_akhir = $kas_keluar->bulan;
        }
        else{
          $ambil_nomor = 1;
          $bulan_akhir = 13;
        }
         
	    /*jika bulan terakhir dari kas_keluar tidak sama dengan bulan sekarang, 
	      maka nomor nya kembali mulai dari 1, jika tidak maka nomor terakhir ditambah dengan 1
	    */
        if ($bulan_akhir != $bulan_sekarang) {
          $no_faktur = "1/KK/".$bulan_terakhir."/".$tahun_terakhir;
        }
        else {
          $nomor = 1 + $ambil_nomor ;
          $no_faktur = $nomor."/KK/".$bulan_terakhir."/".$tahun_terakhir;
        }

        //TAMBAH KAS
	        $kas = KasKeluar::create(['no_faktur' => $no_faktur, 'kas' => '2', 'kategori' => '1', 'jumlah' => '50000', 'keterangan' => 'Makan Karyawan']);

	        $user = User::find(1);

	        $response = $this->actingAs($user)->get(route('kas_keluar.edit',$kas->id));

	        $response->assertStatus(200)
                 	->assertSee('Edit Kas Keluar');     
    }

    //PROSES EDIT KAS
    public function testHTTPEditKasKeluar(){

    	//MEMBUAT NO FAKTUR
    	$bulan_sekarang = date('m');

    	$tahun_sekarang = date('Y');
    	$tahun_terakhir = substr($tahun_sekarang, 2);

    	//mengecek jumlah karakter dari bulan sekarang
    	$cek_jumlah_bulan = strlen($bulan_sekarang);

    	//jika jumlah karakter dari bulannya sama dengan 1 maka di tambah 0 di depannya
    	if ($cek_jumlah_bulan == 1) {
    		$bulan_terakhir = "0".$bulan_sekarang;
    	}
    	else{
    		$bulan_terakhir = $bulan_sekarang;
    	}

    	//ambil bulan dan no_faktur dari tanggal kas_keluar terakhir
        $kas_keluar = KasKeluar::select([DB::raw('MONTH(created_at) bulan'), 'no_faktur'])->orderBy('id','DESC')->first();

        if ($kas_keluar != NULL) {
          $pisah_nomor = explode("/", $kas_keluar->no_faktur);
          $ambil_nomor = $pisah_nomor[0];
          $bulan_akhir = $kas_keluar->bulan;
        }
        else{
          $ambil_nomor = 1;
          $bulan_akhir = 13;
        }
         
	    /*jika bulan terakhir dari kas_keluar tidak sama dengan bulan sekarang, 
	      maka nomor nya kembali mulai dari 1, jika tidak maka nomor terakhir ditambah dengan 1
	    */
        if ($bulan_akhir != $bulan_sekarang) {
          $no_faktur = "1/KK/".$bulan_terakhir."/".$tahun_terakhir;
        }
        else {
          $nomor = 1 + $ambil_nomor ;
          $no_faktur = $nomor."/KK/".$bulan_terakhir."/".$tahun_terakhir;
        }

	        //TAMBAH KAS
	        $kas = KasKeluar::create(['no_faktur' => $no_faktur, 'kas' => '2', 'kategori' => '1', 'jumlah' => '50000', 'keterangan' => 'Makan Karyawan']);

	        //login user -> admin
	        $user = User::find(1);

	        $response = $this->actingAs($user)->json('POST', route('kas_keluar.update',$kas->id), ['_method' => 'PUT', 'kas' => '2', 'kategori' => '1', 'jumlah' => '50000', 'keterangan' => 'Makan Karyawan']);

	        $response->assertStatus(302)
	                 ->assertRedirect(route('kas_keluar.index'));

	        $response2 = $this->get($response->headers->get('location'))->assertSee('Sukses : Berhasil Mengubah Transaksi Kas Keluar "'.$no_faktur.'"');
     
    }
}
