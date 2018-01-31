<?php

namespace App\Http\Controllers;

use App\BankKomunitas;
use App\Customer;
use App\KeranjangBelanja;
use App\Komunitas;
use App\KomunitasCustomer;
use App\KomunitasPenggiat;
use App\LokasiPelanggan;
use App\SettingAplikasi;
use App\User;
use App\UserWarung;
use Auth;
use File;
use Illuminate\Http\Request;
use Indonesia;
use Intervention\Image\ImageManagerStatic as Image;
use Session;

class UbahProfilController extends Controller
{
//UBAH PROFIL USER PELANGGAN
    public function ubah_profil_pelanggan()
    {
        //PILIH USER -> LOGIN
        $user = Auth::user();
        //FOTO WARMART
        $logo_warmart = "" . asset('/assets/img/examples/warmart_logo.png') . "";
        //PELANGGAN, WARUNG, KOMUNITAS
        $pelanggan           = Customer::select(['id', 'email', 'password', 'name', 'alamat', 'wilayah', 'no_telp', 'tgl_lahir', 'tipe_user', 'status_konfirmasi'])->where('id', $user->id)->first();
        $komunitas_pelanggan = KomunitasCustomer::where('user_id', $user->id)->first();

        //DATA LOKASI PELANGGAN
        $lokasi_pelanggan = LokasiPelanggan::where('id_pelanggan', $user->id)->first();

        $provinsi = Indonesia::allProvinces()->pluck('name', 'id');

        if ($lokasi_pelanggan != null) {
            $kabupaten = Indonesia::allCities()->where('province_id', $lokasi_pelanggan->provinsi)->pluck('name', 'id');
            $kecamatan = Indonesia::allDistricts()->where('city_id', $lokasi_pelanggan->kabupaten)->pluck('name', 'id');
            $kelurahan = Indonesia::allVillages()->where('district_id', $lokasi_pelanggan->kecamatan)->pluck('name', 'id');
        } else {
            $kabupaten = "";
            $kecamatan = "";
            $kelurahan = "";
        }
        //SETTING APLIKASI
        $setting_aplikasi = SettingAplikasi::select('tipe_aplikasi')->first();

        $keranjang_belanjaan = KeranjangBelanja::with(['produk', 'pelanggan'])->where('id_pelanggan', Auth::user()->id)->get();
        $cek_belanjaan       = $keranjang_belanjaan->count();
        return view('ubah_profil.ubah_profil_pelanggan', ['user' => $pelanggan, 'pelanggan' => $pelanggan, 'komunitas_pelanggan' => $komunitas_pelanggan, 'cek_belanjaan' => $cek_belanjaan, 'logo_warmart' => $logo_warmart, 'lokasi_pelanggan' => $lokasi_pelanggan, 'provinsi' => $provinsi, 'kabupaten' => $kabupaten, 'kecamatan' => $kecamatan, 'kelurahan' => $kelurahan, 'setting_aplikasi' => $setting_aplikasi]);
    }

//UBAH PROFIL USER PELANGGAN
    public function proses_ubah_profil_pelanggan(Request $request)
    {
        //VALIDASI
        $this->validate($request, [
            'name'    => 'required',
            'no_telp' => 'required|unique:users,no_telp,' . $request->id,
            'email'   => 'unique:users,email,' . $request->id,
            'alamat'  => 'required',
        ]);
        //UPDATE USER PELANGGAN
        Customer::find($request->id)->update([
            'name'      => $request->name,
            'email'     => $request->email,
            'alamat'    => $request->alamat,
            'no_telp'   => $request->no_telp,
            'tgl_lahir' => $request->tgl_lahir,
        ]);

        //JIKA SEBELUMNYA SUDAH ADA DI KOMUNITAS
        if ($request['komunitas'] != "") {
            //HAPUS KOMUNITAS LAMA
            KomunitasCustomer::where('user_id', $request->id)->delete();
            LokasiPelanggan::where('id_pelanggan', $request->id)->delete();

            //INSERT KOMUNITAS BARU
            if (isset($request['komunitas'])) {
                KomunitasCustomer::create(['user_id' => $request->id, 'komunitas_id' => $request['komunitas']]);

                //UPDATE USER PELANGGAN
                LokasiPelanggan::create(['id_pelanggan' => $request->id, 'provinsi' => $request['provinsi'], 'kabupaten' => $request['kabupaten'], 'kecamatan' => $request['kecamatan'], 'kelurahan' => $request['kelurahan']]);

            }
        }

        return redirect()->route('daftar_produk.index');
    }

//UBAH PROFIL USER WARUNG
    public function ubah_profil_user_warung()
    {
        //PILIH USER -> LOGIN
        $user                            = Auth::user();
        $user_warung                     = UserWarung::find($user->id);
        $user_warung['nama']             = $user_warung->name;
        $user_warung['setting_aplikasi'] = SettingAplikasi::select('tipe_aplikasi')->first();

        if ($user_warung->id_warung != Auth::user()->id_warung) {
            Auth::logout();
            return response()->view('error.403');
        } else {
            return $user_warung;
        }
    }

//UBAH PROFIL USER WARUNG
    public function proses_ubah_profil_warung(Request $request)
    {
        $user_warung = UserWarung::find(Auth::user()->id);
        if ($user_warung->id_warung != Auth::user()->id_warung) {
            Auth::logout();
            return response()->view('error.403');
        } else {
            //VALIDASI
            $this->validate($request, [
                'name'     => 'required',
                'alamat'   => 'required',
                'email'    => 'required|without_spaces|unique:users,email,' . $request->id . ',id,id_warung,' . Auth::user()->id_warung,
                'no_telp'  => 'required|without_spaces|unique:users,no_telp,' . $request->id . ',id,id_warung,' . Auth::user()->id_warung,
                'foto_ktp' => 'image|max:3072',
            ]);

            //UPDATE USER WARUNG
            $user_warung->update([
                'name'    => $request->name,
                'email'   => $request->email,
                'no_telp' => $request->no_telp,
                'alamat'  => $request->alamat,
            ]);

            //UPDATE FOTO KTP
            if ($request->hasFile('foto_ktp')) {
                $foto_ktp = $request->file('foto_ktp');

                // Mengambil file yang diupload
                $uploaded_foto = $foto_ktp;
                // mengambil extension file
                $extension = $uploaded_foto->getClientOriginalExtension();

                // membuat nama file random berikut extension
                $filename     = str_random(40) . '.' . $extension;
                $image_resize = Image::make($foto_ktp->getRealPath());
                $image_resize->resize(null, 130, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $image_resize->save(public_path('foto_ktp_user/' . $filename));

                // hapus foto ktp lama, jika ada
                if ($user_warung->foto_ktp) {
                    $old_foto = $user_warung->foto_ktp;
                    $filepath = public_path() . DIRECTORY_SEPARATOR . 'foto_ktp_user'
                    . DIRECTORY_SEPARATOR . $user_warung->foto_ktp;
                    try {
                        File::delete($filepath);
                    } catch (FileNotFoundException $e) {
                        // Foto sudah dihapus/tidak ada
                    }
                }
                $user_warung->foto_ktp = $filename;

                $user_warung->save();
            }
        }
    }

//UBAH PROFIL USER KOMUNITAS
    public function ubah_profil_komunitas()
    {
        //PILIH USER -> LOGIN
        $user      = Auth::user();
        $komunitas = Komunitas::with(['kelurahan', 'warung', 'komunitas_penggiat', 'bank_komunitas'])->find($user->id);

        return view('ubah_profil.ubah_profil_komunitas')->with(compact('user', 'komunitas'));
    }

//UBAH PROFIL USER PELANGGAN
    public function proses_ubah_profil_komunitas(Request $request)
    {

        //end masukan data bank komunitas
        //VALIDASI
        $this->validate($request, [
            'email'       => 'required|without_spaces|unique:users,email,' . $request->id,
            'name'        => 'required|unique:users,name,' . $request->id,
            'alamat'      => 'required',
            'kelurahan'   => 'required',
            'no_telp'     => 'required|without_spaces|unique:users,no_telp,' . $request->id,
            'nama_bank'   => 'required',
            'no_rekening' => 'required',
            'an_rekening' => 'required',
            'id_warung'   => 'required',
        ]);

        //insert
        $komunitas = Komunitas::where('id', $request->id)->update([
            'email'     => $request->email,
            'name'      => $request->name,
            'alamat'    => $request->alamat,
            'wilayah'   => $request->kelurahan,
            'no_telp'   => $request->no_telp,
            'id_warung' => $request->id_warung,
        ]);

        $cek_komunitas_penggiat = KomunitasPenggiat::where('komunitas_id', $request->id)->count();

        //masukan data penggiat komunitas
        if ($cek_komunitas_penggiat == 0) {
            $komunitaspenggiat = KomunitasPenggiat::create([
                'nama_penggiat'   => $request->name_penggiat,
                'alamat_penggiat' => $request->alamat_penggiat,
                'komunitas_id'    => $request->id,
            ]);
        } else {
            if ($request->name_penggiat != "" and $request->alamat_penggiat != "") {
                $komunitaspenggiat = KomunitasPenggiat::where('komunitas_id', $request->id)->update([
                    'nama_penggiat'   => $request->name_penggiat,
                    'alamat_penggiat' => $request->alamat_penggiat,
                ]);
            }
        }

        $cek_bank_komunitas = BankKomunitas::where('komunitas_id', $request->id)->count();
        //masukan data bank komunitas
        if ($cek_bank_komunitas == 0) {
            $bankkomunitas = BankKomunitas::create([
                'nama_bank'    => $request->nama_bank,
                'no_rek'       => $request->no_rekening,
                'atas_nama'    => $request->an_rekening,
                'komunitas_id' => $request->id,
            ]);
        } else {
            if ($request->nama_bank != "" and $request->no_rekening != "" and $request->an_rekening != "") {
                $bankkomunitas = BankKomunitas::where('komunitas_id', $request->id)->update([
                    'nama_bank' => $request->nama_bank,
                    'no_rek'    => $request->no_rekening,
                    'atas_nama' => $request->an_rekening,
                ]);
            }

        }

        Session::flash("flash_notification", [
            "level"   => "success",
            "message" => "Profil Berhasil Di Ubah",
        ]);

        return redirect()->back();
    }

//UBAH PROFIL USER ADMIN
    public function ubah_profil_admin()
    {
        //PILIH USER -> LOGIN
        $user               = Auth::user();
        $user_admin         = UserWarung::find($user->id);
        $user_admin['nama'] = $user_admin->name;
        return $user_admin;
    }

//UBAH PROFIL USER ADMIN
    public function proses_ubah_profil_admin(Request $request)
    {
        //VALIDASI
        $this->validate($request, [
            'nama'    => 'required',
            'email'   => 'required|without_spaces|unique:users,email,' . $request->id,
            'no_telp' => 'required|without_spaces|unique:users,no_telp,' . $request->id,
            'alamat'  => 'required',

        ]);

        //UPDATE USER ADMIN
        $user_warung = User::where('id', $request->id)->update([
            'name'    => $request->nama,
            'email'   => $request->email,
            'no_telp' => $request->no_telp,
            'alamat'  => $request->alamat,
        ]);

    }

    //CARI WILAYAH
    public function cek_wilayah(Request $request)
    {
        $user = Auth::user();
        # Tarik ID_wilayah & tipe_wilayah
        $id_wilayah   = $request->id;
        $type_wilayah = $request->type;

        $lokasi_pelanggan = LokasiPelanggan::where('id_pelanggan', $user->id)->first();

        # Inisialisasi variabel berdasarkan masing-masing tabel dari model
        # dimana ID target sama dengan ID inputan
        $kabupaten = Indonesia::allCities()->where('province_id', $id_wilayah);
        $kecamatan = Indonesia::allDistricts()->where('city_id', $id_wilayah);
        $kelurahan = Indonesia::allVillages()->where('district_id', $id_wilayah);

        # Buat pilihan "Switch Case" berdasarkan variabel "type" dari form
        switch ($type_wilayah):
    # untuk kasus "kabupaten"
    case 'kabupaten':
        if ($lokasi_pelanggan != null) {
                $return = $this->editLokasi($lokasi_pelanggan, $type_wilayah);
        } else {
            $return = "<option value=''>--PILIH KABUPATEN--</option>";
        }
        # lakukan perulangan untuk tabel kabupaten lalu kirim
        foreach ($kabupaten as $kabupatens) {
            # isi nilai return
            $return .= "<option value='$kabupatens->id'>$kabupatens->name</option>";
            # kirim
        }
        return $return;
        break;
    # untuk kasus "kecamatan"
    case 'kecamatan':
        if ($lokasi_pelanggan != null) {
            $return = $this->editLokasi($lokasi_pelanggan, $type_wilayah);
        } else {
            $return = "<option value=''>--PILIH KECAMATAN--</option>";
        }
        foreach ($kecamatan as $kecamatans) {
            # isi nilai return
            $return .= "<option value='$kecamatans->id'>$kecamatans->name</option>";
            # kirim
        }
        return $return;
        break;
    # untuk kasus "kelurahan"
    case 'kelurahan':
        if ($lokasi_pelanggan != null) {
            $return = $this->editLokasi($lokasi_pelanggan, $type_wilayah);
        } else {
            $return = "<option value=''>--PILIH KELURAHAN--</option>";
        }
        foreach ($kelurahan as $kelurahans) {
            $return .= "<option value='$kelurahans->id'>$kelurahans->name</option>";
        }
        return $return;
        break;
        # pilihan berakhir
        endswitch;

    }

    public function editLokasi($lokasi_pelanggan, $type_wilayah)
    {
        //AMBIL DATA Lokasi pelanggan
        $lokasi_kabupaten = Indonesia::allCities()->where('id', $lokasi_pelanggan->kabupaten)->first();
        $lokasi_kecamatan = Indonesia::allDistricts()->where('id', $lokasi_pelanggan->kecamatan)->first();
        $lokasi_kelurahan = Indonesia::allVillages()->where('id', $lokasi_pelanggan->kelurahan)->first();
        //AMBIL DATA Lokasi pelanggan

        if ($type_wilayah == "kabupaten") {
            return "<option value='$lokasi_kabupaten->id'>$lokasi_kabupaten->name</option>";
        }
        if ($type_wilayah == "kecamatan") {
            return "<option value='$lokasi_kecamatan->id'>$lokasi_kecamatan->name</option>";
        }
        if ($type_wilayah == "kelurahan") {
            return "<option value='$lokasi_kelurahan->id'>$lokasi_kelurahan->name</option>";
        }
        throw new Exception('Tidak ada type ');
    }

}
