<?php

namespace App\Http\Controllers;

use App\PendaftarTopos;
use App\Warung;
use App\Bank;
use Auth;
use DateTime;
use Session;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Intervention\Image\ImageManagerStatic as Image;


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
            'nama_subdomain'    => 'required',
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
            'nama_subdomain'      => $request->nama_subdomain,
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


    public function dataWarung(){

     return Warung::find(Auth::user()->id_warung);
 }
 public function dataBank(){
    $bank = Bank::all();
    return response()->json($bank);
}
}
