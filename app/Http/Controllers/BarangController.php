<?php

namespace App\Http\Controllers;

use App\Barang;
use App\Hpp;
use App\KategoriBarang;
use App\Satuan;
use App\SettingAplikasi;
use App\User;
use Auth;
use File;
use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;
use Jenssegers\Agent\Agent;
use Yajra\Datatables\Html\Builder;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('user-must-warung');
    }

    public function index(Request $request, Builder $htmlBuilder)
    {
        return view('barang.index')->with(compact('html'));
    }

    public function tandaPemisahTitik($angka)
    {
        return number_format($angka, 0, ',', '.');
    }

    public function statusProduk($id)
    {
        $cek_produk = Hpp::where('id_produk', $id)->count();
        if ($cek_produk > 0) {
            $status_produk = 1;
        } else {
            $status_produk = 0;
        }

        return $status_produk;
    }

    public function dataPagination($data_produk, $array_produk)
    {

        $respons['current_page']   = $data_produk->currentPage();
        $respons['data']           = $array_produk;
        $respons['first_page_url'] = url('/produk/view?page=' . $data_produk->firstItem());
        $respons['from']           = 1;
        $respons['last_page']      = $data_produk->lastPage();
        $respons['last_page_url']  = url('/produk/view?page=' . $data_produk->lastPage());
        $respons['next_page_url']  = $data_produk->nextPageUrl();
        $respons['path']           = url('/produk/view');
        $respons['per_page']       = $data_produk->perPage();
        $respons['prev_page_url']  = $data_produk->previousPageUrl();
        $respons['to']             = $data_produk->perPage();
        $respons['total']          = $data_produk->total();

        return $respons;
    }

    public function view()
    {
        $data_produk  = Barang::with(['satuan', 'kategori_barang'])->where('id_warung', Auth::user()->id_warung)->orderBy('id', 'desc')->paginate(10);
        $array_produk = array();
        foreach ($data_produk as $produk) {

            $status_produk = $this->statusProduk($produk->id);
            array_push($array_produk, [
                'produk'        => $produk,
                'harga_jual'    => $this->tandaPemisahTitik($produk->harga_jual),
                'harga_jual2'   => $this->tandaPemisahTitik($produk->harga_jual2),
                'harga_beli'    => $this->tandaPemisahTitik($produk->harga_beli),
                'nama_produk'   => $produk->NamaProduk,
                'status_produk' => $status_produk,
            ]);
        }

        //DATA PAGINATION
        $respons = $this->dataPagination($data_produk, $array_produk);
        return response()->json($respons);
    }

    public function pencarian(Request $request)
    {
        $data_produk = Barang::with(['satuan', 'kategori_barang'])->where('id_warung', Auth::user()->id_warung)->where(function ($query) use ($request) {
            $query->orwhere('kode_barang', 'LIKE', '%' . $request->search . '%')
                ->orwhere('kode_barcode', 'LIKE', '%' . $request->search . '%')
                ->orwhere('nama_barang', 'LIKE', '%' . $request->search . '%')
                ->orwhere('harga_beli', 'LIKE', '%' . $request->search . '%')
                ->orwhere('harga_jual', 'LIKE', '%' . $request->search . '%')
                ->orwhere('harga_jual2', 'LIKE', '%' . $request->search . '%');
        })->orderBy('id', 'desc')->paginate(10);
        $array_produk = array();
        foreach ($data_produk as $produk) {

            $status_produk = $this->statusProduk($produk);
            array_push($array_produk, [
                'produk'        => $produk,
                'harga_jual'    => $this->tandaPemisahTitik($produk->harga_jual),
                'harga_jual2'   => $this->tandaPemisahTitik($produk->harga_jual2),
                'harga_beli'    => $this->tandaPemisahTitik($produk->harga_beli),
                'nama_produk'   => $produk->NamaProduk,
                'status_produk' => $status_produk,
            ]);
        }

        //DATA PAGINATION
        $respons = $this->dataPagination($data_produk, $array_produk);
        return response()->json($respons);
    }

    public function pilih_kategori()
    {
        $kategori = KategoriBarang::all();
        return response()->json($kategori);
    }

    public function pilih_satuan()
    {
        $satuan = Satuan::all();
        return response()->json($satuan);
    }

    public function data_agent()
    {
        $agent = new Agent();
        if ($agent->isMobile()) {
            $data_agent = 0;
        } else {
            $data_agent = 1;
        }
        return response()->json($data_agent);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        if (Auth::user()->id_warung == '') {
            Auth::logout();
            return response()->view('error.403');
        } else {

            return view('barang.create');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Auth::user()->id_warung == '') {
            Auth::logout();
            return response()->view('error.403');
        } else {
            $this->validate($request, [
                'kode_barcode'       => 'nullable|unique:barangs,kode_barcode,NULL,id,id_warung,' . Auth::user()->id_warung . '|max:50',
                'kode_barang'        => 'required|unique:barangs,kode_barang,NULL,id,id_warung,' . Auth::user()->id_warung . '|max:50',
                'nama_barang'        => 'required|max:300',
                'harga_beli'         => 'required|numeric|digits_between:1,11',
                'harga_jual'         => 'required|numeric|digits_between:1,11',
                'harga_jual2'        => 'nullable|numeric|digits_between:1,11',
                'perkiraan_berat'    => 'nullable|numeric',
                'kategori_barang_id' => 'required|exists:kategori_barangs,id',
                'satuan_id'          => 'required|exists:satuans,id',
                'foto'               => 'image|max:3072',
            ]);

            if ($request->perkiraan_berat == "" or $request->perkiraan_berat == 0) {
                $perkiraan_berat = 1000;
            } else {
                $perkiraan_berat = $request->perkiraan_berat;
            }

            $insert_barang = Barang::create([
                'kode_barang'        => $request->kode_barang,
                'kode_barcode'       => $request->kode_barcode,
                'nama_barang'        => strtolower($request->nama_barang),
                'harga_beli'         => $request->harga_beli,
                'harga_jual'         => $request->harga_jual,
                'harga_jual2'        => $request->harga_jual2,
                'berat'              => $perkiraan_berat,
                'satuan_id'          => $request->satuan_id,
                'kategori_barang_id' => $request->kategori_barang_id,
                'deskripsi_produk'   => $request->deskripsi_produk,
                'status_aktif'       => $request->status_aktif,
                'hitung_stok'        => $request->hitung_stok,
                'konfirmasi_admin'   => 1,
                'id_warung'          => Auth::user()->id_warung]);

            if ($request->hasFile('foto')) {
                $foto = $request->file('foto');

                if (is_array($foto) || is_object($foto)) {
                    // Mengambil file yang diupload
                    $uploaded_foto = $foto;
                    // mengambil extension file
                    $extension = $uploaded_foto->getClientOriginalExtension();
                    // membuat nama file random berikut extension
                    $filename     = str_random(40) . '.' . $extension;
                    $image_resize = Image::make($foto->getRealPath());
                    $image_resize->fit(300);
                    $image_resize->save(public_path('foto_produk/' . $filename));
                    $insert_barang->foto = $filename;
                    // menyimpan field foto_kamar di database kamar dengan filename yang baru dibuat
                    $insert_barang->save();
                }

            }

        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $produk = Barang::find($id);
        return $produk;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $barang = Barang::find($id);
        if ($barang->id_warung != Auth::user()->id_warung) {
            Auth::logout();
            return response()->view('error.403');
        } else {
            return view('barang.edit')->with(compact('barang'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        $update_barang = Barang::find($request->id);
        if ($update_barang->id_warung != Auth::user()->id_warung) {
            Auth::logout();
            return response()->view('error.403');
        } else {

            //validate
            $this->validate($request, [
                'kode_barcode'       => 'nullable|max:50|unique:barangs,kode_barcode,' . $request->id . ',id,id_warung,' . Auth::user()->id_warung,
                'kode_barang'        => 'required|max:50|unique:barangs,kode_barang,' . $request->id . ',id,id_warung,' . Auth::user()->id_warung,
                'nama_barang'        => 'required|max:300',
                'harga_beli'         => 'required|numeric|digits_between:1,11',
                'harga_jual'         => 'required|numeric|digits_between:1,11',
                'harga_jual2'        => 'nullable|numeric|digits_between:1,11',
                'berat'              => 'nullable|numeric',
                'kategori_barang_id' => 'required|exists:kategori_barangs,id',
                'satuan_id'          => 'required|exists:satuans,id',
                'foto'               => 'image|max:3072',

            ]);

            if ($request->status_aktif == "1" || $request->status_aktif == "true") {
                $status_aktif = 1;
            } else {
                $status_aktif = 0;
            }

            if ($request->hitung_stok == "1" || $request->hitung_stok == "true") {
                $hitung_stok = 1;
            } else {
                $hitung_stok = 0;
            }
            if ($request->berat == "" or $request->berat == 0) {
                $berat = 1000;
            } else {
                $berat = $request->berat;
            }
            $update_barang->update([
                'kode_barang'        => $request->kode_barang,
                'kode_barcode'       => $request->kode_barcode,
                'nama_barang'        => strtolower($request->nama_barang),
                'harga_beli'         => $request->harga_beli,
                'harga_jual'         => $request->harga_jual,
                'harga_jual2'        => $request->harga_jual2,
                'berat'              => $berat,
                'satuan_id'          => $request->satuan_id,
                'kategori_barang_id' => $request->kategori_barang_id,
                'deskripsi_produk'   => $request->deskripsi_produk,
                'status_aktif'       => $status_aktif,
                'hitung_stok'        => $hitung_stok,
                'konfirmasi_admin'   => 1,
                'id_warung'          => Auth::user()->id_warung,
            ]);

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
                $image_resize->save(public_path('foto_produk/' . $filename));
                // hapus foto_home lama, jika ada
                if ($update_barang->foto) {
                    $old_foto = $update_barang->foto;
                    $filepath = public_path() . DIRECTORY_SEPARATOR . 'foto_produk'
                    . DIRECTORY_SEPARATOR . $update_barang->foto;
                    try {
                        File::delete($filepath);
                    } catch (FileNotFoundException $e) {
                        // File sudah dihapus/tidak ada
                    }
                }
                $update_barang->foto = $filename;
                $update_barang->save();
            }

        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $request->id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // hapus
        $barang = Barang::find($id);

        if ($barang->id_warung != Auth::user()->id_warung) {
            Auth::logout();
            return response()->view('error.403');
        } else {
            Barang::destroy($id);
        }
    }

    //HALAMAN DETAIL PRODUK
    public function detail_produk($id)
    {
        $barang = Barang::find($id);
        if ($barang->id_warung != Auth::user()->id_warung) {
            Auth::logout();
            return response()->view('error.403');
        } else {
            return view('barang.detail_produk', ['id' => $id, 'deskripsi_produk' => $barang->deskripsi_produk])->with(compact('barang'));
        }
    }

    public function update_deskripsi_produk(Request $request)
    {
        $update_deskripsi_produk = Barang::where('id', $request->id)->update([
            'deskripsi_produk' => $request->deskripsi_produk,
        ]);
    }

    //LIHAT DESKRIPSI PRODUK
    public function lihat_deskripsi_produk($id)
    {
        $lihat_deskripsi_produk = Barang::find($id);
        $nama_produk            = $lihat_deskripsi_produk->nama_barang;
        $setting_aplikasi       = SettingAplikasi::select('tipe_aplikasi')->first();
        $foto_latar_belakang    = "background-image: asset('image/background2.jpg');";
        $agent                  = new Agent();

        if ($lihat_deskripsi_produk->id_warung != Auth::user()->id_warung) {
            Auth::logout();
            return response()->view('error.403');
        } else {
            return view('barang.lihat_deskripsi_produk', ['id' => $id, 'lihat_deskripsi_produk' => $lihat_deskripsi_produk, 'nama_produk' => $nama_produk, 'setting_aplikasi' => $setting_aplikasi, 'foto_latar_belakang' => $foto_latar_belakang, 'agent' => $agent]);
        }
    }

    public function pilihProduk()
    {
        $produk = Barang::where('id_warung', Auth::user()->id_warung)->get();
        $array  = array();
        foreach ($produk as $produks) {
            array_push($array, [
                'id'          => $produks->id,
                'nama_produk' => title_case($produks->nama_barang),
                'kode_produk' => $produks->kode_barang,
                'barcode'     => $produks->kode_barcode,
                'hitung_stok' => $produks->hitung_stok,
                'produk'      => $produks->id . "|" . title_case($produks->nama_barang) . "|" . $produks->harga_beli . "|" . $produks->harga_jual . "|" . $produks->satuan_id . "|" . $produks->harga_jual2]);

        }

        return response()->json($array);
    }
}
