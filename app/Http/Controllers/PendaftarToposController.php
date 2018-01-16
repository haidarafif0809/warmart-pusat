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


class PendaftarToposController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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
            'email'   => 'nullable|unique:users,email',
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

    public function pendaftaranTopos($id){    

        if ($id == 0 OR $id > 3) {
            return response()->view('error.404');
        }else{
            return view('daftar_topos.index',['id'=>$id]);
        }
    }
    public function prosesDaftarTopos(Request $request){     
        $kode_verifikasi = rand(1111, 9999);
            // PENDAFTARAN WARUNG
        $this->validate($request, [
            'email'       => 'nullable|without_spaces|unique:users,email|email',
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
            'name'      => $request->name,
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
        Notification::send(PendaftarTopos::first(), new PendaftaranTopos($pendaftar_topos)); 

        return redirect('/kirim-bukti-pembayaran/'.$pendaftar_topos->id);

    }

    public function kirimBuktiPembayaran($id){      


        $pendaftar_topos     = PendaftarTopos::with('bank')->whereId($id);
        if ($pendaftar_topos->count() == 0 ) {
            return response()->view('error.404');
        }else{

            $waktu_daftar = date($pendaftar_topos->first()->created_at);
            $date = date_create($waktu_daftar);
            date_add($date, date_interval_create_from_date_string('4 hours'));// hanya diberi waktu 4 jam
            $batas_pendaftaran = date_format($date, 'd/m/Y H:i:s');

            if ($pendaftar_topos->first()->status_pembayaran == 0) {

                Session::flash("flash_notification", [ 
                    "alert" => 'warning',
                    "icon" => 'error_outline',
                    "judul" => 'PERHATIAN',
                    "message" => 'Mohon Selesaikan Pembayaran Sebelum <b>'.$batas_pendaftaran.'</b> Sebesar <b>Rp. '. number_format($pendaftar_topos->first()->total, 0, ',', '.').'</b> melalui <b>'.$pendaftar_topos->first()->jenis_pembayaran.'</b> Ke Rekening: <br>
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
                    "message" => 'Terima Kasih Telah Mengirimkan Bukti Pembayaran, Aplikasi Yang Anda Pesan Akan di Proses. Kami Akan Segera Menghubungi Anda'
                ]);

            }

            return view('daftar_topos.kirim_bukti_pembayaran', ['pendaftar_topos'=>$pendaftar_topos->first()]);
        }
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
}
