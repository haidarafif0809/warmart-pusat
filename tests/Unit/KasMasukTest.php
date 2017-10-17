<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use App\KasMasuk;
use App\TransaksiKas;
use App\User;
use URL;

class KasMasukTest extends TestCase
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
    public function testCrudKasMasuk()
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

    	//ambil bulan dan no_faktur dari tanggal kas_masuk terakhir
        $kas_masuk = KasMasuk::select([DB::raw('MONTH(created_at) bulan'), 'no_faktur'])->where('id_warung','1')->orderBy('id','DESC')->first();

        if ($kas_masuk != NULL) {
          $pisah_nomor = explode("/", $kas_masuk->no_faktur);
          $ambil_nomor = $pisah_nomor[0];
          $bulan_akhir = $kas_masuk->bulan;
        }
        else{
          $ambil_nomor = 1;
          $bulan_akhir = 13;
        }
         
	    /*jika bulan terakhir dari kas_masuk tidak sama dengan bulan sekarang, 
	      maka nomor nya kembali mulai dari 1, jika tidak maka nomor terakhir ditambah dengan 1
	    */
        if ($bulan_akhir != $bulan_sekarang) {
          $no_faktur = "1/KK/".$bulan_terakhir."/".$tahun_terakhir;
        }
        else {
          $nomor = 1 + $ambil_nomor ;
          $no_faktur = $nomor."/KK/".$bulan_terakhir."/".$tahun_terakhir;
        }

	        // TEST INSERT Kas Masuk
	        $kas_test = KasMasuk::create(['no_faktur' => $no_faktur, 'kas' => '2', 'kategori' => '1', 'jumlah' => '50000', 'keterangan' => 'Makan Karyawan','id_warung'=>'1']);
	        $transaksi_kas = TransaksiKas::create(['no_faktur' => $no_faktur, 'jenis_transaksi'=>'kas_masuk', 'jumlah_keluar' => '50000', 'kas' => '2','warung_id'=>'1']);

	        //CEK DATABASE
	        $data_kas_masuk = $this->assertDatabaseHas('kas_masuks',['no_faktur' => $no_faktur, 'kas' => '2', 'kategori' => '1', 'jumlah' => '50000', 'keterangan' => 'Makan Karyawan','id_warung'=>'1']);
	        $data_transaksi_kas = $this->assertDatabaseHas('transaksi_kas',['no_faktur' => $no_faktur, 'jenis_transaksi'=>'kas_masuk', 'jumlah_keluar' => '50000', 'kas' => '2','warung_id'=>'1']);

	        // TEST UPDATE Kas Masuk
	        KasMasuk::find($kas_test->id)->update(['kas' => '2', 'kategori' => '1', 'jumlah' => '75000', 'keterangan' => 'Makan 3 Karyawan','id_warung'=>'1']);
	        TransaksiKas::where('no_faktur', $kas_test->no_faktur)->update(['jumlah_keluar' => '75000', 'kas' => '2','warung_id'=>'1']);
	        
	        //CEK DATABASE        
	        $cek_kas_masuk = $this->assertDatabaseHas('kas_masuks',['kas' => '2', 'kategori' => '1', 'jumlah' => '75000', 'keterangan' => 'Makan 3 Karyawan','id_warung'=>'1']);
	        $cek_transaksi_kas = $this->assertDatabaseHas('transaksi_kas',['jumlah_keluar' => '75000', 'kas' => '2','warung_id'=>'1']);

	        $user = User::find(1);
	        $response = $this->actingAs($user)->json('POST', route('kas_masuk.destroy',$kas_test->id), ['_method' => 'DELETE']);

	        KasMasuk::destroy($kas_test->id);
	        TransaksiKas::where('no_faktur', $kas_test->no_faktur)->delete();

	        $cek_kas = $this->assertDatabaseMissing('kas_masuks',['kas' => '2', 'kategori' => '1', 'jumlah' => '75000', 'keterangan' => 'Makan 3 Karyawan','id_warung'=>'1']);
	        $cek_transaksi = $this->assertDatabaseMissing('transaksi_kas',['jenis_transaksi'=>'kas_masuk', 'jumlah_keluar' => '50000', 'kas' => '2','warung_id'=>'1']);
    }

    //TAMBAH Kas Masuk
    public function testHTTPTambahKasMasuk() {

        $user = User::find(4);

        $response = $this->actingAs($user)->json('POST', route('kas_masuk.store'), [
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

    	//ambil bulan dan no_faktur dari tanggal kas_masuk terakhir
        $kas_masuk = KasMasuk::select([DB::raw('MONTH(created_at) bulan'), 'no_faktur'])->where('id_warung','1')->orderBy('id','DESC')->first();

        if ($kas_masuk != NULL) {
          $pisah_nomor = explode("/", $kas_masuk->no_faktur);
          $ambil_nomor = $pisah_nomor[0];
          $bulan_akhir = $kas_masuk->bulan;
        }
        else{
          $ambil_nomor = 1;
          $bulan_akhir = 13;
        }
         
	    /*jika bulan terakhir dari kas_masuk tidak sama dengan bulan sekarang, 
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
	                 ->assertRedirect(route('kas_masuk.index'));
	        
	        //TAMBAH Kas Masuk
	        $kas = KasMasuk::create(['no_faktur' => $no_faktur, 'kas' => '2', 'kategori' => '1', 'jumlah' => '50000', 'keterangan' => 'Makan Karyawan','id_warung'=>'1']);
	        $transaksi_kas = TransaksiKas::create(['no_faktur' => $no_faktur, 'jenis_transaksi'=>'kas_masuk', 'jumlah_keluar' => '50000', 'kas' => '2','warung_id'=>'1']);

	        $response2 = $this->get($response->headers->get('location'))->assertSee('Berhasil Menambah Transaksi Kas Masuk Sebesar "50000"');

	        //CEK DB TABLE KAS
	        $response_kas = $this->assertDatabaseHas("kas_masuks",['no_faktur' => $no_faktur, 'kas' => '2', 'kategori' => '1', 'jumlah' => '50000', 'keterangan' => 'Makan Karyawan','id_warung'=>'1']);
	        $data_transaksi_kas = $this->assertDatabaseHas('transaksi_kas',['no_faktur' => $no_faktur, 'jenis_transaksi'=>'kas_masuk', 'jumlah_keluar' => '50000', 'kas' => '2','warung_id'=>'1']);
    }

    //HAPUS Kas Masuk
    public function testHTTPHapusKasMasuk() {

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

    	//ambil bulan dan no_faktur dari tanggal kas_masuk terakhir
        $kas_masuk = KasMasuk::select([DB::raw('MONTH(created_at) bulan'), 'no_faktur'])->where('id_warung','1')->orderBy('id','DESC')->first();

        if ($kas_masuk != NULL) {
          $pisah_nomor = explode("/", $kas_masuk->no_faktur);
          $ambil_nomor = $pisah_nomor[0];
          $bulan_akhir = $kas_masuk->bulan;
        }
        else{
          $ambil_nomor = 1;
          $bulan_akhir = 13;
        }
         
	    /*jika bulan terakhir dari kas_masuk tidak sama dengan bulan sekarang, 
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
	        $kas = KasMasuk::create(['no_faktur' => $no_faktur, 'kas' => '2', 'kategori' => '1', 'jumlah' => '50000', 'keterangan' => 'Makan Karyawan','id_warung'=>'1']);
		    $transaksi_kas = TransaksiKas::create(['no_faktur' => $no_faktur, 'jenis_transaksi'=>'kas_masuk', 'jumlah_keluar' => '50000', 'kas' => '2','warung_id'=>'1']);

	        $user = User::find(4);

	        $response = $this->actingAs($user)->json('POST', route('kas_masuk.destroy',$kas->id), ['_method' => 'DELETE']);
	        TransaksiKas::where('no_faktur', $kas->no_faktur)->delete();

	        $this->get($response->headers->get('location'))->assertSee(' Berhasil Menghapus Transaksi Kas Masuk "'.$no_faktur.'"'); 
    }

    //HALAMAN MENU EDIT KAS
    public function testHTTPUpdateKasMasuk(){

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

    	//ambil bulan dan no_faktur dari tanggal kas_masuk terakhir
        $kas_masuk = KasMasuk::select([DB::raw('MONTH(created_at) bulan'), 'no_faktur'])->where('id_warung','1')->orderBy('id','DESC')->first();

        if ($kas_masuk != NULL) {
          $pisah_nomor = explode("/", $kas_masuk->no_faktur);
          $ambil_nomor = $pisah_nomor[0];
          $bulan_akhir = $kas_masuk->bulan;
        }
        else{
          $ambil_nomor = 1;
          $bulan_akhir = 13;
        }
         
	    /*jika bulan terakhir dari kas_masuk tidak sama dengan bulan sekarang, 
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
	        $kas = KasMasuk::create(['no_faktur' => $no_faktur, 'kas' => '2', 'kategori' => '1', 'jumlah' => '50000', 'keterangan' => 'Makan Karyawan','id_warung'=>'1']);

	        $user = User::find(4);

	        $response = $this->actingAs($user)->get(route('kas_masuk.edit',$kas->id));

	        $response->assertStatus(200)
                 	->assertSee('Edit Kas Masuk');     
    }

    //PROSES EDIT KAS
    public function testHTTPEditKasMasuk(){

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

    	//ambil bulan dan no_faktur dari tanggal kas_masuk terakhir
        $kas_masuk = KasMasuk::select([DB::raw('MONTH(created_at) bulan'), 'no_faktur'])->where('id_warung','1')->orderBy('id','DESC')->first();

        if ($kas_masuk != NULL) {
          $pisah_nomor = explode("/", $kas_masuk->no_faktur);
          $ambil_nomor = $pisah_nomor[0];
          $bulan_akhir = $kas_masuk->bulan;
        }
        else{
          $ambil_nomor = 1;
          $bulan_akhir = 13;
        }
         
	    /*jika bulan terakhir dari kas_masuk tidak sama dengan bulan sekarang, 
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
	        $kas_masuk1 = KasMasuk::create(['no_faktur' => $no_faktur, 'kas' => '2', 'kategori' => '1', 'jumlah' => '50000', 'keterangan' => 'Makan Karyawan','id_warung'=>'1']);

	        //login user -> admin
	        $user = User::find(4);

	        $response = $this->actingAs($user)->json('POST', route('kas_masuk.update',$kas_masuk1->id), ['_method' => 'PUT', 'kas' => '1', 'kategori' => '2', 'jumlah' => '50000', 'keterangan' => 'Makan Karyawan','id_warung'=>'1']);

	        $response->assertStatus(302)
	                 ->assertRedirect(route('kas_masuk.index'));

	        $response2 = $this->get($response->headers->get('location'))->assertSee('Berhasil Mengubah Transaksi Kas Keluar "'.$kas_masuk1->no_faktur.'"');
     
    }
}
