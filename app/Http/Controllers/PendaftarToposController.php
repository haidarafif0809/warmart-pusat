<?php

namespace App\Http\Controllers;

use App\PendaftarTopos;
use App\Warung;
use App\Bank;
use App\BankWarung;
use App\SettingAplikasi;
use App\UserWarung;
use App\Kas;
use App\Role;
use Auth;
use DateTime;
use Session;
use Notification;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Intervention\Image\ImageManagerStatic as Image;
use App\Notifications\PendaftaranTopos;
use App\Notifications\PembayaranTopos;
use GuzzleHttp\Client;

class PendaftarToposController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function paginationData($pendaftar_topos, $array, $url)
    {

        //DATA PAGINATION
        $respons['current_page']   = $pendaftar_topos->currentPage();
        $respons['data']           = $array;
        $respons['first_page_url'] = url($url . '?page=' . $pendaftar_topos->firstItem());
        $respons['from']           = 1;
        $respons['last_page']      = $pendaftar_topos->lastPage();
        $respons['last_page_url']  = url($url . '?page=' . $pendaftar_topos->lastPage());
        $respons['next_page_url']  = $pendaftar_topos->nextPageUrl();
        $respons['path']           = url($url);
        $respons['per_page']       = $pendaftar_topos->perPage();
        $respons['prev_page_url']  = $pendaftar_topos->previousPageUrl();
        $respons['to']             = $pendaftar_topos->perPage();
        $respons['total']          = $pendaftar_topos->total();
        //DATA PAGINATION

        return $respons;
    }
    public function paginationPencarianData($pendaftar_topos, $array, $url, $search)
    {
        //DATA PAGINATION
        $respons['current_page']   = $pendaftar_topos->currentPage();
        $respons['data']           = $array;
        $respons['first_page_url'] = url($url . '?page=' . $pendaftar_topos->firstItem() . '&search=' . $search);
        $respons['from']           = 1;
        $respons['last_page']      = $pendaftar_topos->lastPage();
        $respons['last_page_url']  = url($url . '?page=' . $pendaftar_topos->lastPage() . '&search=' . $search);
        $respons['next_page_url']  = $pendaftar_topos->nextPageUrl();
        $respons['path']           = url($url);
        $respons['per_page']       = $pendaftar_topos->perPage();
        $respons['prev_page_url']  = $pendaftar_topos->previousPageUrl();
        $respons['to']             = $pendaftar_topos->perPage();
        $respons['total']          = $pendaftar_topos->total();
        //DATA PAGINATION

        return $respons;
    }


    public function view()
    {
        $pendaftaran_topos = PendaftarTopos::with(['bank'])->orderBy('id', 'desc')->paginate(10);
        $array       = array();

        foreach ($pendaftaran_topos as $pendaftaran_toposs) {
          array_push($array, ['pendaftar_topos'=>$pendaftaran_toposs]);
      }

      $url     = '/daftar-topos/view';
      $respons = $this->paginationData($pendaftaran_topos, $array, $url);

      return response()->json($respons);
  }


  public function pencarian(Request $request)
  {

    $pendaftaran_topos = PendaftarTopos::with(['bank'])->where(function ($query) use ($request) {

        $query->orWhere('name', 'LIKE', $request->search . '%')
        ->orWhere('email', 'LIKE', $request->search . '%')
        ->orWhere('no_telp', 'LIKE', $request->search . '%');
    })->orderBy('id', 'desc')->paginate(10);
    $array       = array();

    foreach ($pendaftaran_topos as $pendaftaran_toposs) {
      array_push($array, ['pendaftar_topos'=>$pendaftaran_toposs]);
  }

  $url    = '/daftar-topos/pencarian';
  $search = $request->search;

  $respons = $this->paginationPencarianData($pendaftaran_topos, $array, $url, $search);

  return response()->json($respons);
}

public function viewDetailUserTopos($id){

    $pendaftaran_topos = PendaftarTopos::with(['bank'])->whereId($id)->orderBy('id', 'desc')->paginate(10);
    $array       = array();

    foreach ($pendaftaran_topos as $pendaftaran_toposs) {
      array_push($array, ['pendaftar_topos'=>$pendaftaran_toposs]);
  }

  $url     = '/daftar-topos/view-detail-user-topos/'.$id;
  $respons = $this->paginationData($pendaftaran_topos, $array, $url);

  return response()->json($respons);
}

public function pencarianDetailUserTopos(Request $request, $id){

    $pendaftaran_topos = PendaftarTopos::with(['bank'])->whereId($id)->where(function ($query) use ($request) {
        $query->orWhere('name', 'LIKE', $request->search . '%')
        ->orWhere('lama_berlangganan', 'LIKE', $request->search . '%')
        ->orWhere('berlaku_hingga', 'LIKE', $request->search . '%');
    })->orderBy('id', 'desc')->paginate(10);
    $array       = array();

    foreach ($pendaftaran_topos as $pendaftaran_toposs) {
      array_push($array, ['pendaftar_topos'=>$pendaftaran_toposs]);
  }

  $url    = '/daftar-topos/pencarian-detail-user-topos/'.$id;
  $search = $request->search;

  $respons = $this->paginationPencarianData($pendaftaran_topos, $array, $url, $search);

  return response()->json($respons);
}

public function index()
{
        //
    $pendaftar_topos = PendaftarTopos::with('bank')->where('warung_id',Auth::user()->id_warung);

    if ($pendaftar_topos->count() > 0) {

        $waktu_daftar = date($pendaftar_topos->first()->created_at);
        $date = date_create($waktu_daftar);
            date_add($date, date_interval_create_from_date_string('4 hours'));// hanya diberi waktu 4 jam
            $batas_pendaftaran = date_format($date, 'Y-m-d H:i:s');

            $respons['status_pembayaran'] = $pendaftar_topos->first()->status_pembayaran;
            $respons['waktu_daftar'] = $waktu_daftar;
            $respons['batas_pendaftaran'] = $batas_pendaftaran;
            $respons['status_daftar'] = $pendaftar_topos->count();
            $respons['no_rekening'] = $pendaftar_topos->first()->no_rekening;
            $respons['atas_nama'] = $pendaftar_topos->first()->atas_nama;
            $respons['nama_bank'] = $pendaftar_topos->first()->bank->nama_bank;
            $respons['total'] = $pendaftar_topos->first()->total;
            $respons['id'] = $pendaftar_topos->first()->id;

            return response()->json($respons);

        }else{

            $status_daftar = 0;
            $respons['status_daftar'] = $status_daftar;
            return response()->json($respons);

        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate($request, [
            'name'    => 'required',
            'no_telpon' => 'required|without_spaces|unique:pendaftar_topos,no_telp,',
            'email'       => 'required|without_spaces|unique:users,email|email',
            'alamat'  => 'required',
            'lama_berlangganan' => 'required',
            'berlaku_hingga' => 'required',
            'pembayaran' => 'required',
            'total' => 'required',
            'tujuan_transfer' => 'required',
            'no_rek_transfer' => 'required',
            'atas_nama' => 'required'
        ]);

        $bank     = explode("|", $request->tujuan_transfer);
        $bank_id  = $bank[0];
        
        $pendaftar_topos = PendaftarTopos::create([
            'name'      => $request->name,
            'no_telp'   => $request->no_telpon,
            'alamat'     => $request->alamat,
            'email'     => $request->email,
            'lama_berlangganan'    => $request->lama_berlangganan,
            'berlaku_hingga'    => $request->berlaku_hingga,
            'jenis_pembayaran'    => $request->pembayaran,
            'total'    => $request->total,
            'bank_id'    => $bank_id,
            'no_rekening'    => $request->no_rek_transfer,
            'atas_nama'    => $request->atas_nama,
            'warung_id'    => Auth::user()->id_warung

        ]);

        // arahkan ke methode smsPendaftaran()
        $this->smsPendaftaran($pendaftar_topos);
        // Notification slack
        Notification::send(PendaftarTopos::first(), new PendaftaranTopos($pendaftar_topos)); 


        return response(200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

            //validate
        $this->validate($request, [
            'foto'               => 'required|image|max:3072',
        ]);

        $this->uploadBuktiPembayaran($id,$request);
        return response(200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function pendaftaranTopos($id){    

        if ($id == 0 OR $id > 3) {
            return response()->view('error.404');
        }else{
            return view('daftar_topos.index',['id'=>$id]);
        }
    }
    public function prosesDaftarTopos(Request $request){   

        if (Auth::check() == false) {
            $kode_verifikasi = rand(1111, 9999);
            // PENDAFTARAN WARUNG
            $this->validate($request, [
                'email'       => 'required|without_spaces|unique:users,email|email',
                'name'        => 'required',
                'nama_warung' => 'required',
                'no_telp'     => 'required|numeric|without_spaces|unique:users,no_telp',
                'alamat'      => 'required',
                'lama_berlangganan' => 'required',
                'berlaku_hingga' => 'required',
                'total' => 'required',
                'tujuan_transfer' => 'required',
                'no_rek_transfer' => 'required',
                'atas_nama' => 'required'
            ]);

            //MASTER WARUNG
            $warung = Warung::create([
                'name'      => $request->nama_warung,
                'alamat'    => $request->alamat,
                'no_telpon' => $request->no_telp,
                'email' => $request->email,
                'wilayah'   => "-",
            ]);

            //INSERT BANK WARUNG
            $bank_warung = BankWarung::create([
                'nama_bank' => "-",
                'atas_nama' => "-",
                'no_rek'    => "-",
                'warung_id' => $warung->id,
            ]);

            $password = bcrypt(123456);
            //USER WARUNG
            $user = UserWarung::create([
                'name'              => $request->name,
                'email'              => $request->email,
                'password'          => $password,
                'alamat'            => $request->alamat,
                'no_telp'           => $request->no_telp,
                'id_warung'         => $warung->id,
                'tipe_user'         => 4,
                'status_konfirmasi' => 0,
                'kode_verifikasi'   => $kode_verifikasi,
                'konfirmasi_admin'  => 1,
            ]);

            // KAS WARUNG

            Kas::create(['kode_kas' => 'K01', 'nama_kas' => 'Kas Warung', 'status_kas' => 1, 'default_kas' => 1, 'warung_id' => $warung->id]);

            $userWarungRole = Role::where('name', 'warung')->first();
            $user->attachRole($userWarungRole);

            $bank     = explode("|", $request->tujuan_transfer);
            $bank_id  = $bank[0];

            $pendaftar_topos = PendaftarTopos::create([
                'name'      => $request->nama_warung,
                'email'      => $request->email,
                'no_telp'   => $request->no_telp,
                'alamat'     => $request->alamat,
                'lama_berlangganan'    => $request->lama_berlangganan,
                'berlaku_hingga'    => $request->berlaku_hingga,
                'jenis_pembayaran'    => "ATM/BANK TRANSFER",
                'total'    => str_replace('.','',$request->total) ,
                'bank_id'    => $bank_id,
                'no_rekening'    => $request->no_rek_transfer,
                'atas_nama'    => $request->atas_nama,
                'warung_id'    => $warung->id

            ]);

        // arahkan ke methode smsPendaftaran()
            $this->smsPendaftaran($pendaftar_topos);

        // kirim Notification ke Slack
            Notification::send(PendaftarTopos::first(), new PendaftaranTopos($pendaftar_topos)); 
        }else{

                        // PENDAFTARAN WARUNG
            $this->validate($request, [
                'email'       => 'required|without_spaces|unique:pendaftar_topos,email|email',
                'name'        => 'required',
                'nama_warung' => 'required',
                'no_telp'     => 'required|numeric|without_spaces|unique:pendaftar_topos,no_telp',
                'alamat'      => 'required',
                'lama_berlangganan' => 'required',
                'berlaku_hingga' => 'required',
                'total' => 'required',
                'tujuan_transfer' => 'required',
                'no_rek_transfer' => 'required',
                'atas_nama' => 'required'
            ]);

            $bank     = explode("|", $request->tujuan_transfer);
            $bank_id  = $bank[0];

            $pendaftar_topos = PendaftarTopos::create([
                'name'      => $request->nama_warung,
                'email'      => $request->email,
                'no_telp'   => $request->no_telp,
                'alamat'     => $request->alamat,
                'lama_berlangganan'    => $request->lama_berlangganan,
                'berlaku_hingga'    => $request->berlaku_hingga,
                'jenis_pembayaran'    => "ATM/BANK TRANSFER",
                'total'    => str_replace('.','',$request->total) ,
                'bank_id'    => $bank_id,
                'no_rekening'    => $request->no_rek_transfer,
                'atas_nama'    => $request->atas_nama,
                'warung_id'    => Auth::user()->id_warung

            ]);

        // arahkan ke methode smsPendaftaran()
            $this->smsPendaftaran($pendaftar_topos);

        // kirim Notification ke Slack
            Notification::send(PendaftarTopos::first(), new PendaftaranTopos($pendaftar_topos)); 
        }


        return redirect('/kirim-bukti-pembayaran/'.$pendaftar_topos->id);

    }

    public function kirimBuktiPembayaran($id){      


        $pendaftar_topos     = PendaftarTopos::with('bank')->whereId($id);
        if ($pendaftar_topos->count() == 0 ) {
            return response()->view('error.404');
        }else{
            $tanggal_sekarang = date('d/m/Y H:i:s');
            $batas_pendaftaran =  $this->batasPendaftaran($pendaftar_topos->first()->created_at);

            if ($batas_pendaftaran >= $tanggal_sekarang) {


                if ($pendaftar_topos->first()->status_pembayaran == 0) {

                    Session::flash("flash_notification", [ 
                        "alert" => 'warning',
                        "icon" => 'error_outline',
                        "judul" => 'PERHATIAN',
                        "message" => 'Kami Sudah Mengirimkan SMS ke Nomor <b>'.$pendaftar_topos->first()->no_telp.'</b>. <br>Mohon Selesaikan Pembayaran Sebelum <b>'.$batas_pendaftaran.'</b> Sebesar <b>Rp. '. number_format($pendaftar_topos->first()->total, 0, ',', '.').'</b> melalui <b>'.$pendaftar_topos->first()->jenis_pembayaran.'</b> Ke Rekening: <br>
                        <table>
                        <tbody>
                        <tr><td width="50%"><font><b>Bank</b></font></td> <td> :&nbsp;</td> <td><font> <b>'.$pendaftar_topos->first()->bank->nama_bank.'</b></font></tr>
                        <tr><td width="50%"><font><b>No. Rekening</b></font></td> <td> :&nbsp;</td> <td c><font> <b>'.$pendaftar_topos->first()->no_rekening.'</b></font> </tr>
                        <tr><td  width="50%"><font><b>Atas Nama</b></font></td> <td> :&nbsp;</td> <td><font> <b>'.$pendaftar_topos->first()->atas_nama.'</b></font>  </td></tr>
                        </tbody>
                        </table>'
                    ]);

                }else{

                    Session::flash("flash_notification", [ 
                        "alert" => 'success',
                        "icon" => 'error_outline',
                        "judul" => 'Info',
                        "message" => 'Terima Kasih Telah Melakukan Pembayaran, Aplikasi Yang Anda Pesan Akan di Proses. Kami Akan Segera Menghubungi Anda'
                    ]);

                }

            }else{
                Session::flash("flash_notification", [ 
                    "alert" => 'danger',
                    "icon" => 'error_outline',
                    "judul" => 'Info',
                    "message" => 'Waktu Pembayaran Anda Habis, Karena Telah Melebihi Batas Waktu yang ditentukan'
                ]);
            }
            return view('daftar_topos.kirim_bukti_pembayaran', ['pendaftar_topos'=>$pendaftar_topos->first()    ]);
        }
    }


    public function uploadBuktiPembayaran($id,$request){

        $update_pendafataran_topos = PendaftarTopos::find($id);
        $update_pendafataran_topos->update(['keterangan'=>$request->keterangan,'status_pembayaran'=>1]);
        if ($request->hasFile('foto')) {

                // Mengambil file yang diupload
            $foto          = $request->file('foto');
            $uploaded_foto = $foto;
                // mengambil extension file
            $extension = $uploaded_foto->getClientOriginalExtension();
                // membuat nama file random berikut extension
            $filename     = str_random(40) . '.' . $extension;
            $image_resize = Image::make($foto->getRealPath());
            $image_resize->fit(300);
            $image_resize->save(public_path('foto_bukti_pembayaran/' . $filename));
                // hapus foto_home lama, jika ada
            if ($update_pendafataran_topos->foto) {
                $old_foto = $update_pendafataran_topos->foto;
                $filepath = public_path() . DIRECTORY_SEPARATOR . 'foto_bukti_pembayaran'
                . DIRECTORY_SEPARATOR . $update_pendafataran_topos->foto;
                try {
                    File::delete($filepath);
                } catch (FileNotFoundException $e) {
                        // File sudah dihapus/tidak ada
                }
            }
            $update_pendafataran_topos->foto = $filename;
            $update_pendafataran_topos->save();
        }
        Notification::send(PendaftarTopos::first(), new PembayaranTopos($update_pendafataran_topos)); 
    }



    public function smsPendaftaran($pendaftar_topos){
                // url_form_upload_bukti_pembayaran 
        $url_form_upload_bukti_pembayaran = url('/kirim-bukti-pembayaran/'.$pendaftar_topos->id);
        $total = number_format($pendaftar_topos->total, 0, ',', '.');

        // menghitung batas waktu pengiriman bukti pembayaran
        $batas_pendaftaran =  $this->batasPendaftaran($pendaftar_topos->created_at);
        //085709064029
        // isi pesan SMS
        $isi_pesan = urlencode('Terima Kasih Telah Mendaftar di Topos, Mohon Selesaikan Pembayaran Sebelum '.$batas_pendaftaran.' Sebesar Rp. '. $total .' melalui '.$pendaftar_topos->jenis_pembayaran.' Ke Rekening '.$pendaftar_topos->bank->nama_bank.' dengan No. Rekening '.$pendaftar_topos->no_rekening.' Atas Nama '.$pendaftar_topos->atas_nama.'. Jika Anda Sudah Menyelesaikan Pembayaran Silakan Kunjungi Laman Ini '.$url_form_upload_bukti_pembayaran);
        // no telp
        $no_telpon = $pendaftar_topos->no_telp;
        // arahkan ke methode kirimSms()
        $this->kirimSms($no_telpon,$isi_pesan);
    }


    public function kirimSms($nomor_tujuan,$isi_pesan){

        $userkey      = env('USERKEY');
        $passkey      = env('PASSKEY');

        if (env('STATUS_SMS') == 1) {
                $client = new Client(); //GuzzleHttp\Client
                $result = $client->get('https://reguler.zenziva.net/apps/smsapi.php?userkey=' . $userkey . '&passkey=' . $passkey . '&nohp=' . $nomor_tujuan . '&pesan=' . $isi_pesan . '');

            }
        }

        public function batasPendaftaran($waktu_daftar){

            $waktu_daftar = date($waktu_daftar);
            $date = date_create($waktu_daftar);
            date_add($date, date_interval_create_from_date_string('4 hours'));// hanya diberi waktu 4 jam
            $batas_pendaftaran = date_format($date, 'd/m/Y H:i:s'); 

            return $batas_pendaftaran;
        }
        public function prosesKirimBuktiPembayaran(Request $request,$id){        
            //validate
            $this->validate($request, [
                'foto'               => 'required|image|max:3072',
            ]);

            $this->uploadBuktiPembayaran($id,$request);
            return back();
        }

        public function dataWarung(){

           return Warung::find(Auth::user()->id_warung);
       }
       public function dataBank(){
        $bank = Bank::all();
        return response()->json($bank);
    }

    public function konfirmasi($id){

        $update_pendafataran_topos = PendaftarTopos::find($id);
        $update_pendafataran_topos->update(['status_pembayaran'=> '2']);

        // isi pesan SMS
        $isi_pesan = urlencode('Topos : Terima Kasih Telah Melakukan Pembayaran,  Pembayaran Anda Sudah Kami Terima. Petugas Kami Sedang Menyiapkan Aplikasi Untuk Anda');
        // no telp
        $no_telpon = $update_pendafataran_topos->no_telp;
        // arahkan ke methode kirimSms()
        $this->kirimSms($no_telpon,$isi_pesan);

        return response(200);
    }
    public function cekSisaDemo(){

      $warung = Warung::select(DB::raw(' datediff(DATE_ADD(created_at, INTERVAL 14 DAY), current_date()) as sisa_waktu '))->where('id',Auth::user()->id_warung )->first();
      $sisa_waktu = $warung->sisa_waktu;
      $pendaftar_topos = PendaftarTopos::select('status_pembayaran','id')->where('warung_id',Auth::user()->id_warung);

      if ($pendaftar_topos->count() == 0) {
        $status_pembayaran = 0;   
        $respons['id'] = 0;    
        $respons['status_pembayaran'] = $status_pembayaran;
        $respons['sisa_waktu'] = $sisa_waktu;
    }else{        
       $status_pembayaran = $pendaftar_topos->first()->status_pembayaran;

       $respons['id'] = $pendaftar_topos->first()->id;
       $respons['status_pembayaran'] = $status_pembayaran;
       $respons['sisa_waktu'] = $sisa_waktu;
   }


   return response()->json($respons);
}

}
