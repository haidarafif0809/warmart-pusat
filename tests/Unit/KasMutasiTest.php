<?php 
 
namespace Tests\Unit; 
 
use Tests\TestCase; 
use Illuminate\Foundation\Testing\RefreshDatabase; 
use Illuminate\Foundation\Testing\DatabaseMigrations; 
use Illuminate\Foundation\Testing\DatabaseTransactions; 
use Illuminate\Support\Facades\DB;
use App\KasMutasi; 
use App\TransaksiKas; 
use App\User; 
use URL; 
 
class KasMutasiTest extends TestCase 
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
 
 
    public function testCrudKasMutasi() 
    { 
    	$tahun_sekarang = date('Y'); 
        $bulan_sekarang = date('m'); 
        $tahun_terakhir = substr($tahun_sekarang, 2); 
       
      //mengecek jumlah karakter dari bulan sekarang 
        $cek_jumlah_bulan = strlen($bulan_sekarang); 
 
      //jika jumlah karakter dari bulannya sama dengan 1 maka di tambah 0 di depannya 
        if ($cek_jumlah_bulan == 1) { 
          $data_bulan_terakhir = "0".$bulan_sekarang; 
         } 
        else{ 
          $data_bulan_terakhir = $bulan_sekarang; 
         } 
       
      //ambil bulan dan no_faktur dari tanggal kas_mutasi terakhir 
         $kas_mutasi = KasMutasi::select([DB::raw('MONTH(created_at) bulan'), 'no_faktur'])
         							->where('id_warung',1)
         							->orderBy('id','DESC')->first(); 
 
 
         if ($kas_mutasi != NULL) { 
          $pisah_nomor = explode("/", $kas_mutasi->no_faktur); 
          $ambil_nomor = $pisah_nomor[0]; 
          $bulan_akhir = $kas_mutasi->bulan; 
         } 
         else{ 
          $ambil_nomor = 1; 
          $bulan_akhir = 13; 
         } 
          
      /*jika bulan terakhir dari kas_mutasi tidak sama dengan bulan sekarang,  
      maka nomor nya kembali mulai dari 1, jika tidak maka nomor terakhir ditambah dengan 1 
      */ 
        if ($bulan_akhir != $bulan_sekarang) { 
          $no_faktur = "1/KMT/".$data_bulan_terakhir."/".$tahun_terakhir; 
        } 
        else { 
          $nomor = 1 + $ambil_nomor ; 
          $no_faktur = $nomor."/KMT/".$data_bulan_terakhir."/".$tahun_terakhir; 
        } 
 

      // TEST INSERT KAS MUTASI 
         $kas_mutasi = KasMutasi::create([ 
            'no_faktur'     => $no_faktur, 
            'dari_kas'      => '3', 
            'ke_kas'        => '4', 
            'jumlah'        => '1000', 
            'keterangan'    => '-', 
            'id_warung'     => '1' 
        ]); 

      //CEK DATABASE 
      $this->assertDatabaseHas('kas_mutasis',[ 
        'no_faktur'     => $no_faktur, 'dari_kas'=> '3', 'ke_kas'=> '4', 'jumlah' => '1000', 'keterangan'=> '-','id_warung' => '1' 
      ]); 
 
            //CEK DATABASE 
      $this->assertDatabaseHas('transaksi_kas',[ 
        'no_faktur'         => $no_faktur, 
            'jenis_transaksi'   =>'kas_mutasi' , 
            'jumlah_keluar'     => '1000', 
            'kas'               => '3' 
      ]); 
 
            //CEK DATABASE 
      $this->assertDatabaseHas('transaksi_kas',[ 
         'no_faktur'         => $no_faktur, 
            'jenis_transaksi'   =>'kas_mutasi' , 
            'jumlah_masuk'      => '1000', 
            'kas'               => '4' 
      ]); 
 
 
      // TEST UPDATE KAS MUTASI 
      KasMutasi::find($kas_mutasi->id)->update([ 
        'dari_kas' => '3','ke_kas' => '4','jumlah' => '1500','keterangan' => '-','id_warung'=> '1' 
      ]); 
 
   
      //CEK DATABASE 
      $this->assertDatabaseHas('kas_mutasis',[ 
        'dari_kas' => '3','ke_kas' => '4','jumlah' => '1500','keterangan' => '-','id_warung'=> '1' 
      ]); 
 
            //CEK DATABASE 
      $this->assertDatabaseHas('transaksi_kas',[ 
        'jumlah_masuk' => '1500','kas' => '4' 
      ]); 
 
            //CEK DATABASE 
      $this->assertDatabaseHas('transaksi_kas',[ 
        'jumlah_keluar' => '1500','kas' => '3' 
      ]); 
 
      // TEST DELETE KAS MUTASI 
      KasMutasi::destroy($kas_mutasi->id); 
      // cek DATA BASE 
      $this->assertDatabaseMissing('kas_mutasis',[ 
        'dari_kas' => '3','ke_kas' => '4','jumlah' => '1500','keterangan' => '-','id_warung'=> '1' 
      ]); 
 
    
                 //CEK DATABASE 
      $this->assertDatabaseMissing('transaksi_kas',['no_faktur'=> $no_faktur 
      ]); 
 
            //CEK DATABASE 
      $this->assertDatabaseMissing('transaksi_kas',['no_faktur'=> $no_faktur 
      ]); 
     
    } 
 
    public function testHTTPtambahKasMutasi() 
    { 
 
        $user = User::find(5); 
        
        $tahun_sekarang = date('Y'); 
        $bulan_sekarang = date('m'); 
        $tahun_terakhir = substr($tahun_sekarang, 2); 
       
      //mengecek jumlah karakter dari bulan sekarang 
        $cek_jumlah_bulan = strlen($bulan_sekarang); 
 
      //jika jumlah karakter dari bulannya sama dengan 1 maka di tambah 0 di depannya 
        if ($cek_jumlah_bulan == 1) { 
          $data_bulan_terakhir = "0".$bulan_sekarang; 
         } 
        else{ 
          $data_bulan_terakhir = $bulan_sekarang; 
         } 
       
      //ambil bulan dan no_faktur dari tanggal kas_mutasi terakhir 
         $kas_mutasi = KasMutasi::select([DB::raw('MONTH(created_at) bulan'), 'no_faktur'])
         							->where('id_warung',1)
         							->orderBy('id','DESC')->first(); 
 
 
         if ($kas_mutasi != NULL) { 
          $pisah_nomor = explode("/", $kas_mutasi->no_faktur); 
          $ambil_nomor = $pisah_nomor[0]; 
          $bulan_akhir = $kas_mutasi->bulan; 
         } 
         else{ 
          $ambil_nomor = 1; 
          $bulan_akhir = 13; 
         } 
          
      /*jika bulan terakhir dari kas_mutasi tidak sama dengan bulan sekarang,  
      maka nomor nya kembali mulai dari 1, jika tidak maka nomor terakhir ditambah dengan 1 
      */ 
        if ($bulan_akhir != $bulan_sekarang) { 
          $no_faktur = "1/KMT/".$data_bulan_terakhir."/".$tahun_terakhir; 
        } 
        else { 
          $nomor = 1 + $ambil_nomor ; 
          $no_faktur = $nomor."/KMT/".$data_bulan_terakhir."/".$tahun_terakhir; 
        } 
  
 
        $response = $this->actingAs($user)->json('POST', route('kas_mutasi.store'),[ 
            'no_faktur'     => $no_faktur, 
            'dari_kas'      => '3', 
            'ke_kas'        => '4', 
            'jumlah'        => '10000', 
            'keterangan'    => '-', 
            'id_warung'     => '1' 
        ]); 
 
 
         
        $response->assertStatus(302) 
                 ->assertRedirect(route('kas_mutasi.index')); 
 
        $response2 = $this->get($response->headers->get('location'))->assertSee('<b>BERHASIL:</b> Memutasikan Kas Sejumlah <b>10000</b>'); 
 
           $this->assertDatabaseHas('kas_mutasis',[ 
            'no_faktur'     => $no_faktur, 
            'dari_kas'      => '3', 
            'ke_kas'        => '4', 
            'jumlah'        => '10000', 
            'keterangan'    => '-', 
            'id_warung'     => '1' 
            ]); 
 
                        //CEK DATABASE 
            $this->assertDatabaseHas('transaksi_kas',[ 
                'no_faktur'         => $no_faktur, 
                'jenis_transaksi'   =>'kas_mutasi' , 
                'jumlah_keluar'     => '10000', 
                'kas'               => '3' 
            ]); 
 
                    //CEK DATABASE 
            $this->assertDatabaseHas('transaksi_kas',[ 
                 'no_faktur'         => $no_faktur, 
                'jenis_transaksi'   =>'kas_mutasi' , 
                'jumlah_masuk'      => '10000', 
                'kas'               => '4' 
            ]); 
    } 

    public function testHTTPUpdateKasMutasi(){

    	        $user = User::find(5); 

        $tahun_sekarang = date('Y'); 
        $bulan_sekarang = date('m'); 
        $tahun_terakhir = substr($tahun_sekarang, 2); 
       
      //mengecek jumlah karakter dari bulan sekarang 
        $cek_jumlah_bulan = strlen($bulan_sekarang); 
 
      //jika jumlah karakter dari bulannya sama dengan 1 maka di tambah 0 di depannya 
        if ($cek_jumlah_bulan == 1) { 
          $data_bulan_terakhir = "0".$bulan_sekarang; 
         } 
        else{ 
          $data_bulan_terakhir = $bulan_sekarang; 
         } 
       
      //ambil bulan dan no_faktur dari tanggal kas_mutasi terakhir 
         $kas_mutasi = KasMutasi::select([DB::raw('MONTH(created_at) bulan'), 'no_faktur'])
         							->where('id_warung',1)
         							->orderBy('id','DESC')->first(); 
 
 
         if ($kas_mutasi != NULL) { 
          $pisah_nomor = explode("/", $kas_mutasi->no_faktur); 
          $ambil_nomor = $pisah_nomor[0]; 
          $bulan_akhir = $kas_mutasi->bulan; 
         } 
         else{ 
          $ambil_nomor = 1; 
          $bulan_akhir = 13; 
         } 
          
      /*jika bulan terakhir dari kas_mutasi tidak sama dengan bulan sekarang,  
      maka nomor nya kembali mulai dari 1, jika tidak maka nomor terakhir ditambah dengan 1 
      */ 
        if ($bulan_akhir != $bulan_sekarang) { 
          $no_faktur = "1/KMT/".$data_bulan_terakhir."/".$tahun_terakhir; 
        } 
        else { 
          $nomor = 1 + $ambil_nomor ; 
          $no_faktur = $nomor."/KMT/".$data_bulan_terakhir."/".$tahun_terakhir; 
        } 
 
       // TEST INSERT KAS MUTASI 
         $kas_mutasi = KasMutasi::create([ 
            'no_faktur'     => $no_faktur, 
            'dari_kas'      => '3', 
            'ke_kas'        => '4', 
            'jumlah'        => '1000', 
            'keterangan'    => '-', 
            'id_warung'     => '1' 
        ]); 
        
        $response = $this->actingAs($user)->get(route('kas_mutasi.edit',$kas_mutasi->id)); 
 
        $response->assertStatus(200) 
                ->assertSee('Edit Kas Mutasi'); 

     }
 
    public function testHTTPeditKasMutasi() 
    { 
 
        $user = User::find(5); 

        $tahun_sekarang = date('Y'); 
        $bulan_sekarang = date('m'); 
        $tahun_terakhir = substr($tahun_sekarang, 2); 
       
      //mengecek jumlah karakter dari bulan sekarang 
        $cek_jumlah_bulan = strlen($bulan_sekarang); 
 
      //jika jumlah karakter dari bulannya sama dengan 1 maka di tambah 0 di depannya 
        if ($cek_jumlah_bulan == 1) { 
          $data_bulan_terakhir = "0".$bulan_sekarang; 
         } 
        else{ 
          $data_bulan_terakhir = $bulan_sekarang; 
         } 
       
      //ambil bulan dan no_faktur dari tanggal kas_mutasi terakhir 
         $kas_mutasi = KasMutasi::select([DB::raw('MONTH(created_at) bulan'), 'no_faktur'])
         							->where('id_warung',1)
         							->orderBy('id','DESC')->first(); 
 
 
         if ($kas_mutasi != NULL) { 
          $pisah_nomor = explode("/", $kas_mutasi->no_faktur); 
          $ambil_nomor = $pisah_nomor[0]; 
          $bulan_akhir = $kas_mutasi->bulan; 
         } 
         else{ 
          $ambil_nomor = 1; 
          $bulan_akhir = 13; 
         } 
          
      /*jika bulan terakhir dari kas_mutasi tidak sama dengan bulan sekarang,  
      maka nomor nya kembali mulai dari 1, jika tidak maka nomor terakhir ditambah dengan 1 
      */ 
        if ($bulan_akhir != $bulan_sekarang) { 
          $no_faktur = "1/KMT/".$data_bulan_terakhir."/".$tahun_terakhir; 
        } 
        else { 
          $nomor = 1 + $ambil_nomor ; 
          $no_faktur = $nomor."/KMT/".$data_bulan_terakhir."/".$tahun_terakhir; 
        } 
 
       // TEST INSERT KAS MUTASI 
         $kas_mutasi = KasMutasi::create([ 
            'no_faktur'     => $no_faktur, 
            'dari_kas'      => '3', 
            'ke_kas'        => '4', 
            'jumlah'        => '1000', 
            'keterangan'    => '-', 
            'id_warung'     => '1' 
        ]); 
        
        $response = $this->actingAs($user)->json('POST', route('kas_mutasi.update',$kas_mutasi->id),[ 
            'dari_kas'      => '3', 
            'ke_kas'        => '4', 
            'jumlah'        => '1000', 
            'keterangan'    => '-', 
            'id_warung'     => '1', 
            '_method'       => 'PUT' 
        ]); 
 
        $response->assertStatus(302) 
                ->assertRedirect(route('kas_mutasi.index')); 
 
        $response2 = $this->get($response->headers->get('location'))->assertSee('<b>BERHASIL:</b> Mengubah Kas Mutasi '.$no_faktur.''); 
 
           $this->assertDatabaseHas('kas_mutasis',[ 
            'no_faktur'     => $no_faktur, 
            'dari_kas'      => '3', 
            'ke_kas'        => '4', 
            'jumlah'        => '1000', 
            'keterangan'    => '-', 
            'id_warung'     => '1', 
            ]); 
 
            //CEK DATABASE 
            $this->assertDatabaseHas('transaksi_kas',[ 
                'no_faktur'         => $no_faktur, 
                'jenis_transaksi'   =>'kas_mutasi' , 
                'jumlah_keluar'     => '1000', 
                'kas'               => '3' 
            ]); 
 
             //CEK DATABASE 
            $this->assertDatabaseHas('transaksi_kas',[ 
                 'no_faktur'         => $no_faktur, 
                'jenis_transaksi'   =>'kas_mutasi' , 
                'jumlah_masuk'      => '1000', 
                'kas'               => '4' 
            ]); 
     } 


      public function testHTTPhapusKasMutasi(){

        $user = User::find(5);

        $tahun_sekarang = date('Y'); 
        $bulan_sekarang = date('m'); 
        $tahun_terakhir = substr($tahun_sekarang, 2); 
       
      //mengecek jumlah karakter dari bulan sekarang 
        $cek_jumlah_bulan = strlen($bulan_sekarang); 
 
      //jika jumlah karakter dari bulannya sama dengan 1 maka di tambah 0 di depannya 
        if ($cek_jumlah_bulan == 1) { 
          $data_bulan_terakhir = "0".$bulan_sekarang; 
         } 
        else{ 
          $data_bulan_terakhir = $bulan_sekarang; 
         } 
       
      //ambil bulan dan no_faktur dari tanggal kas_mutasi terakhir 
         $kas_mutasi = KasMutasi::select([DB::raw('MONTH(created_at) bulan'), 'no_faktur'])
         							->where('id_warung',1)
         							->orderBy('id','DESC')->first(); 
 
 
         if ($kas_mutasi != NULL) { 
          $pisah_nomor = explode("/", $kas_mutasi->no_faktur); 
          $ambil_nomor = $pisah_nomor[0]; 
          $bulan_akhir = $kas_mutasi->bulan; 
         } 
         else{ 
          $ambil_nomor = 1; 
          $bulan_akhir = 13; 
         } 
          
      /*jika bulan terakhir dari kas_mutasi tidak sama dengan bulan sekarang,  
      maka nomor nya kembali mulai dari 1, jika tidak maka nomor terakhir ditambah dengan 1 
      */ 
        if ($bulan_akhir != $bulan_sekarang) { 
          $no_faktur = "1/KMT/".$data_bulan_terakhir."/".$tahun_terakhir; 
        } 
        else { 
          $nomor = 1 + $ambil_nomor ; 
          $no_faktur = $nomor."/KMT/".$data_bulan_terakhir."/".$tahun_terakhir; 
        } 
         
        // TEST INSERT KAS MUTASI 
        $kas_mutasi = KasMutasi::create([ 
            'no_faktur'     => $no_faktur, 
            'dari_kas'      => '3', 
            'ke_kas'        => '4', 
            'jumlah'        => '1000', 
            'keterangan'    => '-', 
            'id_warung'     => '1' 
        ]);
            

        $response = $this->actingAs($user)->json('POST', route('barang.destroy',$kas_mutasi->id) , ['_method' => 'DELETE']);


        $response = $this->get($response->headers->get('location'))->assertSee('Kas Mutasi '.$no_faktur.' Berhasil Di Hapus');

        $this->assertDatabaseMissing('kas_mutasis',['id' => $kas_mutasi->id]);
        $this->assertDatabaseMissing('transaksi_kas',['no_faktur' => $kas_mutasi->no_faktur]);


    }
} 

