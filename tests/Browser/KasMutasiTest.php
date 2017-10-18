<?php 
 
namespace Tests\Unit; 
 
use Tests\TestCase; 
use Illuminate\Foundation\Testing\RefreshDatabase; 
use Illuminate\Foundation\Testing\DatabaseMigrations; 
use Illuminate\Foundation\Testing\DatabaseTransactions; 
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
      // TEST INSERT KAS MUTASI 
         $kas_mutasi = KasMutasi::create([ 
            'no_faktur'     => '1/0001/Faktur/Test', 
            'dari_kas'      => '3', 
            'ke_kas'        => '4', 
            'jumlah'        => '1000', 
            'keterangan'    => '-', 
            'id_warung'     => '1' 
        ]); 
 
         //kas keluar  
         TransaksiKas::create([ 
            'no_faktur'         => '1/0001/Faktur/Test', 
            'jenis_transaksi'   =>'kas_mutasi' , 
            'jumlah_keluar'     => '1000', 
            'kas'               => '3']);  
         //kas masuk 
         TransaksiKas::create([ 
            'no_faktur'         => '1/0001/Faktur/Test', 
            'jenis_transaksi'   =>'kas_mutasi' , 
            'jumlah_masuk'      => '1000', 
            'kas'               => '4']); 
 
      //CEK DATABASE 
      $this->assertDatabaseHas('kas_mutasis',[ 
        'no_faktur'     => '1/0001/Faktur/Test', 'dari_kas'=> '3', 'ke_kas'=> '4', 'jumlah' => '1000', 'keterangan'=> '-','id_warung' => '1' 
      ]); 
 
            //CEK DATABASE 
      $this->assertDatabaseHas('transaksi_kas',[ 
        'no_faktur'         => '1/0001/Faktur/Test', 
            'jenis_transaksi'   =>'kas_mutasi' , 
            'jumlah_keluar'     => '1000', 
            'kas'               => '3' 
      ]); 
 
            //CEK DATABASE 
      $this->assertDatabaseHas('transaksi_kas',[ 
         'no_faktur'         => '1/0001/Faktur/Test', 
            'jenis_transaksi'   =>'kas_mutasi' , 
            'jumlah_masuk'      => '1000', 
            'kas'               => '4' 
      ]); 
 
 
      // TEST UPDATE KAS MUTASI 
      KasMutasi::find($kas_mutasi->id)->update([ 
        'dari_kas' => '5','ke_kas' => '7','jumlah' => '1500','keterangan' => '-','id_warung'=> '1' 
      ]); 
 
      // TEST TRANSAKSI KAS 
      TransaksiKas::where('no_faktur','1/0001/Faktur/Test')->where('jumlah_masuk','>',0)->update([ 
        'jumlah_masuk' => '1500','kas' => '5' 
      ]); 
 
 
      // TEST TRANSAKSI KAS 
      TransaksiKas::where('no_faktur','1/0001/Faktur/Test')->where('jumlah_keluar','>',0)->update([ 
        'jumlah_keluar' => '1500','kas' => '7' 
      ]); 
 
 
      //CEK DATABASE 
      $this->assertDatabaseHas('kas_mutasis',[ 
        'dari_kas' => '5','ke_kas' => '7','jumlah' => '1500','keterangan' => '-','id_warung'=> '1' 
      ]); 
 
            //CEK DATABASE 
      $this->assertDatabaseHas('transaksi_kas',[ 
        'jumlah_masuk' => '1500','kas' => '5' 
      ]); 
 
            //CEK DATABASE 
      $this->assertDatabaseHas('transaksi_kas',[ 
        'jumlah_keluar' => '1500','kas' => '7' 
      ]); 
 
      // TEST DELETE KAS MUTASI 
      KasMutasi::destroy($kas_mutasi->id); 
      // cek DATA BASE 
      $this->assertDatabaseMissing('kas_mutasis',[ 
        'dari_kas' => '5','ke_kas' => '7','jumlah' => '1500','keterangan' => '-','id_warung'=> '1' 
      ]); 
 
      $no_faktur = '1/0001/Faktur/Test'; 
      TransaksiKas::where('no_faktur',$no_faktur)->delete(); 
                 //CEK DATABASE 
      $this->assertDatabaseMissing('transaksi_kas',['no_faktur'=> $no_faktur 
      ]); 
 
            //CEK DATABASE 
      $this->assertDatabaseMissing('transaksi_kas',['no_faktur'=> $no_faktur 
      ]); 
     
    } 
 
    public function testHTTPtambahKasMutasi() 
    { 
 
        $user = User::find(4); 
        $no_faktur = KasMutasi::no_faktur(); 
 
        $response = $this->actingAs($user)->json('POST', route('kas_mutasi.store'),[ 
            'no_faktur'     => $no_faktur, 
            'dari_kas'      => '7', 
            'ke_kas'        => '9', 
            'jumlah'        => '10000', 
            'keterangan'    => '-', 
            'id_warung'     => '1' 
        ]); 
 
 
         
        $response->assertStatus(302) 
                 ->assertRedirect(route('kas_mutasi.index')); 
 
        $response2 = $this->get($response->headers->get('location'))->assertSee('<b>BERHASIL:</b> Memutasikan Kas Sejumlah <b>10000</b>'); 
 
           $this->assertDatabaseHas('kas_mutasis',[ 
            'no_faktur'     => $no_faktur, 
            'dari_kas'      => '7', 
            'ke_kas'        => '9', 
            'jumlah'        => '10000', 
            'keterangan'    => '-', 
            'id_warung'     => '1' 
            ]); 
 
                        //CEK DATABASE 
            $this->assertDatabaseHas('transaksi_kas',[ 
                'no_faktur'         => $no_faktur, 
                'jenis_transaksi'   =>'kas_mutasi' , 
                'jumlah_keluar'     => '10000', 
                'kas'               => '7' 
            ]); 
 
                    //CEK DATABASE 
            $this->assertDatabaseHas('transaksi_kas',[ 
                 'no_faktur'         => $no_faktur, 
                'jenis_transaksi'   =>'kas_mutasi' , 
                'jumlah_masuk'      => '10000', 
                'kas'               => '9' 
            ]); 
    } 
 
    public function testHTTPeditKasMutasi() 
    { 
 
        $user = User::find(4); 
        $no_faktur = KasMutasi::no_faktur(); 
 
        $response = $this->actingAs($user)->json('POST', route('kas_mutasi.update',19),[ 
            'dari_kas'      => '7', 
            'ke_kas'        => '9', 
            'jumlah'        => '1000', 
            'keterangan'    => '-', 
            'id_warung'     => '1', 
            '_method'       => 'PUT' 
        ]); 
 
        $response->assertStatus(302) 
                ->assertRedirect(route('kas_mutasi.index')); 
 
        $response2 = $this->get($response->headers->get('location'))->assertSee('<b>BERHASIL:</b> Mengubah Kas Mutasi $no_faktur'); 
 
           $this->assertDatabaseHas('kas_mutasis',[ 
            'no_faktur'     => $no_faktur, 
            'dari_kas'      => '7', 
            'ke_kas'        => '9', 
            'jumlah'        => '1000', 
            'keterangan'    => '-', 
            'id_warung'     => '1', 
            ]); 
 
                                   //CEK DATABASE 
            $this->assertDatabaseHas('transaksi_kas',[ 
                'no_faktur'         => $no_faktur, 
                'jenis_transaksi'   =>'kas_mutasi' , 
                'jumlah_keluar'     => '1000', 
                'kas'               => '7' 
            ]); 
 
                    //CEK DATABASE 
            $this->assertDatabaseHas('transaksi_kas',[ 
                 'no_faktur'         => $no_faktur, 
                'jenis_transaksi'   =>'kas_mutasi' , 
                'jumlah_masuk'      => '1000', 
                'kas'               => '9' 
            ]); 
     } 
} 

