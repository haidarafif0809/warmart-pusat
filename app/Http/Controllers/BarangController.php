<?php

namespace App\Http\Controllers;

use App\Barang;
use App\Hpp;
use App\KategoriBarang;
use App\Satuan;
use App\SatuanKonversi;
use App\SettingAplikasi;
use App\User;
use Auth;
use Excel;
use File;
use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;
use Jenssegers\Agent\Agent;
use PHPExcel_Style_Fill;
use Validator;
use Yajra\Datatables\Html\Builder;
use Laratrust;


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

    public function labelSheet($sheet, $row)
    {
        $sheet->row($row, [
            'Kode Barcode',
            'Kode Produk',
            'Nama Produk',
            'Kategori',
            'Satuan',
            'Harga Beli',
            'Harga Jual',
            'Harga Jual 2',
            'Perkiraan Berat',
            'Hitung Stok',
            'Status',
            'Deskripsi Produk',
        ]);
        return $sheet;
    }

    public function kolomWajib()
    {
        return [
            'B', 'C', 'D', 'E', 'F', 'G', 'J', 'K',
        ];
    }

    public function dataPagination($data_produk, $array_produk)
    {

        $respons['current_page']   = $data_produk->currentPage();
        $respons['data']           = $array_produk;
        $respons['otoritas']           = $this->otoritasProduk();
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

        $array = array();
        foreach ($satuan as $satuans) {
            array_push($array, [
                'satuan'      => $satuans->id . "|" . strtoupper($satuans->nama_satuan),
                'nama_satuan' => strtoupper($satuans->nama_satuan),
                'id_satuan'   => strtoupper($satuans->id),
            ]);
        }

        return response()->json($array);
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

            $satuan    = explode("|", $request->satuan_id);
            $id_satuan = $satuan[0];

            $insert_barang = Barang::create([
                'kode_barang'        => $request->kode_barang,
                'kode_barcode'       => $request->kode_barcode,
                'nama_barang'        => strtolower($request->nama_barang),
                'harga_beli'         => $request->harga_beli,
                'harga_jual'         => $request->harga_jual,
                'harga_jual2'        => $request->harga_jual2,
                'berat'              => $perkiraan_berat,
                'satuan_id'          => $id_satuan,
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

    public function satuanKonversi(Request $request)
    {
        foreach ($request->data as $key => $value) {
            $id_produk     = Barang::select('id')->latest()->first()->id;
            $insert_satuan = SatuanKonversi::create([
                'id_satuan'           => $value['id_satuan'],
                'id_produk'           => $id_produk,
                'jumlah_konversi'     => $value['jumlah_produk'],
                'harga_jual_konversi' => $value['harga_jual'],
                'satuan_dasar'        => $value['satuan_dasar'],
                'warung_id'           => Auth::user()->id_warung,
            ]);
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
        $produk = Barang::select(['barangs.id', 'barangs.kode_barang', 'barangs.kode_barcode', 'barangs.nama_barang', 'barangs.harga_beli', 'barangs.harga_jual', 'barangs.satuan_id', 'barangs.kategori_barang_id', 'barangs.status_aktif', 'barangs.foto', 'barangs.hitung_stok', 'barangs.id_warung', 'barangs.created_by', 'barangs.updated_by', 'barangs.created_at', 'barangs.updated_at', 'barangs.deskripsi_produk', 'barangs.konfirmasi_admin', 'barangs.harga_jual2', 'barangs.berat', 'satuans.nama_satuan'])
        ->leftJoin('satuans', 'satuans.id', '=', 'barangs.satuan_id')->where('barangs.id', $id)->first();
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
        $produk = Barang::where('id_warung', Auth::user()->id_warung)->inRandomOrder()->get();
        $array  = array();
        foreach ($produk as $produks) {
            array_push($array, [
                'id'           => $produks->id,
                'nama_produk'  => title_case($produks->nama_barang),
                'kode_produk'  => $produks->kode_barang,
                'barcode'      => $produks->kode_barcode,
                'hitung_stok'  => $produks->hitung_stok,
                'status_aktif' => $produks->status_aktif,
                'produk'       => $produks->id . "|" . title_case($produks->nama_barang) . "|" . $produks->harga_beli . "|" . $produks->harga_jual . "|" . $produks->satuan_id . "|" . $produks->harga_jual2]);

        }

        return response()->json($array);
    }

    //DOWNLAOD TEMPLATE
    public function downloadTemplate()
    {
        Excel::create('Template Import Produk', function ($excel) {

            $excel->sheet('Template Import Produk', function ($sheet) {
                $koloms = $this->kolomWajib();
                // BACKGROUND COLOR - Kolom Wajib Disi
                foreach ($koloms as $kolom) {
                    $sheet->getStyle($kolom . '1')->applyFromArray(array(
                        'fill' => array(
                            'type'  => PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array('rgb' => '90CAF9'),
                        ),
                    ));
                }

                $row   = 1;
                $sheet = $this->labelSheet($sheet, $row);

                $sheet->row(++$row, [
                    '10021542101421',
                    'SNTRKRN001',
                    'Kaos Santri Keren',
                    'Kaos',
                    'PCS',
                    '95000',
                    '150000',
                    '0',
                    '200',
                    'Isi 1 Atau 0, Ket. 1 = Hitung Stok dan 0 = Tidak Hitung Stok',
                    'Isi 1 Atau 0, Ket. 1 = Aktif dan 0 = Tidak Aktif',
                    'Bahan Cotton Combed 24 S',
                ]);

            });
        })->download('xlsx');
    }

    public function importExcel(Request $request)
    {

        $warung_id = Auth::user()->id_warung;
        // validasi untuk memastikan file yang diupload adalah excel
        $this->validate($request, ['excel' => 'required|mimes:xls,xlsx']);
        // ambil file yang baru diupload
        $excel = $request->file('excel');
        // baca sheet pertama
        $excels = Excel::selectSheetsByIndex(0)->load($excel, function ($reader) {
        })->get();

        // rule untuk validasi setiap row pada file excel
        $rowRules = [
            'Kode Barcode'    => 'nullable|unique:barangs,kode_barcode,NULL,id,id_warung,' . $warung_id . '|max:50',
            'Kode Produk'     => 'required|unique:barangs,kode_barang,NULL,id,id_warung,' . $warung_id . '|max:50',
            'Nama Produk'     => 'required|max:300',
            'Harga Beli'      => 'required|numeric|digits_between:1,11',
            'Harga Jual'      => 'required|numeric|digits_between:1,11',
            'Harga Jual 2'    => 'numeric|digits_between:1,11',
            'Kategori'        => 'required|exists:kategori_barangs,id',
            'Satuan'          => 'required|exists:satuans,id',
            'Hitung Stok'     => 'required',
            'Status'          => 'required',
            'Perkiraan Berat' => 'numeic',
        ];
        // Catat semua id buku baru
        // ID ini kita butuhkan untuk menghitung total buku yang berhasil diimport
        $produk_id  = [];
        $errors     = ['hitungStok' => [], 'status' => []];
        $lineErrors = [];
        $no         = 1;

        // looping setiap baris, mulai dari baris ke 2 (karena baris ke 1 adalah nama kolom)
        foreach ($excels as $row) {
            $no++;
            // Mengubah Hitung Stok Menajdi lowerCase (Huruf Kecil Semua)
            $hitungStok = trim(strtolower($row['hitung_stok']));
            if (!empty($row['hitung_stok'])) {
                if ($hitungStok !== '1' && $hitungStok !== '0') {
                    $errors['hitungStok'][] = [
                        'line'    => $no,
                        'message' => 'Nilai Dari Kolom <strong>Hitung Stok</strong> Hanya Boleh Berisi 1 atau 0.',
                    ];
                    $lineErrors[] = $no;
                }
            } else {
                $errors['hitungStok'][] = [
                    'line'    => $no,
                    'message' => 'Nilai Dari Kolom <strong>Hitung Stok</strong> Tidak Boleh Kosong',
                ];
                $lineErrors[] = $no;
            }
            // Mengubah Status Menajdi lowerCase (Huruf Kecil Semua)
            $status = trim(strtolower($row['status']));
            if (!empty($row['status'])) {
                if ($status !== '1' && $status !== '0') {
                    $errors['status'][] = [
                        'line_status'    => $no,
                        'message_status' => 'Nilai Dari Kolom <strong>Status</strong> Hanya Boleh Berisi 1 atau 0.',
                    ];
                    $lineErrors[] = $no;
                }
            } else {
                $errors['status'][] = [
                    'line_status'    => $no,
                    'message_status' => 'Nilai Dari Kolom <strong>Status</strong> Tidak Boleh Kosong',
                ];
                $lineErrors[] = $no;
            }
        }

        // Perulang kedua, digunakan untuk menambahkan data produk jika tidak terjadi error.
        foreach ($excels as $row) {
            // JIKA PRODUK SUDAH ADA DI DB MAKA TIDAK DIIMPORT
            $data_produk = Barang::select(['kode_barang', 'kode_barcode', 'nama_barang'])
            ->where(function ($query) use ($row) {
                $query->orwhere('kode_barang', $row['kode_produk'])
                ->orwhere('kode_barcode', $row['kode_barcode'])
                ->orwhere('nama_barang', $row['nama_produk']);
            });

            if ($data_produk->count() > 0) {
                continue;
            }

            // Jika terjadi error, maka perintah dihentikan sehingga tidak ada data yg di insert ke database
            if (count($errors['hitungStok']) != '' || count($errors['status']) != '') {
                // Buat variable tipe array, dengan index pesanError.
                $pesan = ['pesanError' => '', 'pesanErrorStatus' => ''];

                // Memasukan nilai error yg terjadi, kedalam variabel $pesan yg sudah kita buat tadi.
                foreach ($errors['hitungStok'] as $key => $value) {
                    if ($value['line'] == end($lineErrors)) {
                        $pesan['pesanError'] .= 'Baris Ke <strong>' . $value['line'] . '</strong> ' . $value['message'];
                    } else {
                        $pesan['pesanError'] .= 'Baris Ke <strong>' . $value['line'] . '</strong> ' . $value['message'] . ' <br>';
                    }
                }

                foreach ($errors['status'] as $key => $value) {
                    if ($value['line_status'] == end($lineErrors)) {
                        $pesan['pesanErrorStatus'] .= 'Baris Ke <strong>' . $value['line_status'] . '</strong> ' . $value['message_status'];
                    } else {
                        $pesan['pesanErrorStatus'] .= 'Baris Ke <strong>' . $value['line_status'] . '</strong> ' . $value['message_status'] . ' <br>';
                    }
                }

                return response()->json($pesan);
            }

            // Membuat validasi untuk row di excel, disini kita ubah baris yang sedang di proses menjadi array.
            $validator   = Validator::make($row->toArray(), $rowRules);
            $db_satuan   = Satuan::select(['id', 'nama_satuan'])->where('nama_satuan', $row['satuan']);
            $db_kategori = KategoriBarang::select(['id', 'nama_kategori_barang'])->where('nama_kategori_barang', $row['kategori']);
            // SATUAN
            if ($db_satuan->count() > 0) {
                //Jika Satuan sudah ada maka, tinggal pakai ID nya saja
                $satuan = $db_satuan->first()->id;
            } else {
                //Jika Satuan belum ada maka kita buat dulu satuan baru
                $data_satuan = Satuan::create([
                    'nama_satuan' => $row['satuan'],
                ]);
                $satuan = $data_satuan->id;
            }
            // KATEGORI
            if ($db_kategori->count() > 0) {
                //Jika Kategori sudah ada maka, tinggal pakai ID nya saja
                $kategori = $db_kategori->first()->id;
            } else {
                //Jika Kategori belum ada maka kita buat dulu Kategori baru
                $data_kategori = KategoriBarang::create([
                    'nama_kategori_barang' => $row['kategori'],
                ]);
                $kategori = $data_kategori->id;
            }
            //PERKIRAN BERAT
            $perkiraan_berat = ($row['perkiraan_berat'] == '' or $row['perkiraan_berat'] == 0 ? 1000 : $row['perkiraan_berat']);

            // Insert Detail Item Masuk
            $produk = Barang::create([
                'kode_barang'        => $row['kode_produk'],
                'kode_barcode'       => $row['kode_barcode'],
                'nama_barang'        => strtolower($row['nama_produk']),
                'harga_beli'         => $row['harga_beli'],
                'harga_jual'         => $row['harga_jual'],
                'harga_jual2'        => $row['harga_jual_2'],
                'berat'              => $perkiraan_berat,
                'satuan_id'          => $satuan,
                'kategori_barang_id' => $kategori,
                'deskripsi_produk'   => $row['deskripsi_produk'],
                'status_aktif'       => $row['status'],
                'hitung_stok'        => $row['hitung_stok'],
                'konfirmasi_admin'   => 1,
                'id_warung'          => $warung_id,
            ]);

        }
// Hitung Jumlah Produk Yang Diimport
        $hitung_produk['jumlahProduk'] = $no - 1;

        return response()->json($hitung_produk);
    }

    public function editSatuanKonversi($id)
    {
        $satuan_konversis = SatuanKonversi::select(['satuan_konversis.id_satuan', 'satuan_konversis.satuan_dasar', 'satuan_konversis.id_produk', 'satuan_konversis.jumlah_konversi', 'satuan_konversis.harga_jual_konversi', 'konversi.nama_satuan AS nama_konversi', 'dasar.nama_satuan AS nama_dasar'])
        ->leftJoin('satuans as konversi', 'konversi.id', '=', 'satuan_konversis.id_satuan')
        ->leftJoin('satuans as dasar', 'dasar.id', '=', 'satuan_konversis.satuan_dasar')
        ->where('id_produk', $id)->get();

        return response()->json($satuan_konversis);
    }

    public function prosesEditSatuanKonversi(Request $request, $id)
    {
        //HAPUS SATUAN KONVERSI LAMA
        $data_satuan_konversis = SatuanKonversi::where('id_produk', $id)->get();

        foreach ($data_satuan_konversis as $data_satuan_konversi) {
            $hapus_konversi = SatuanKonversi::destroy($data_satuan_konversi->id_satuan_konversi);
        }

        //INSERT SATUAN KONVERSI BARU
        foreach ($request->data as $key => $value) {
            $insert_satuan = SatuanKonversi::create([
                'id_satuan'           => $value['id_satuan'],
                'id_produk'           => $id,
                'jumlah_konversi'     => $value['jumlah_konversi'],
                'harga_jual_konversi' => $value['harga_jual_konversi'],
                'satuan_dasar'        => $value['satuan_dasar'],
                'warung_id'           => Auth::user()->id_warung,
            ]);
        }
    }

    // DOWNLOAD EXCEL DAFTAR PRODUK
    public function downloadExcel()
    {

        $produks = Barang::select()->get();
        Excel::create('Daftar Produk', function ($excel) use ($produks) {

            $excel->sheet("Produk", function ($sheet) use ($produks) {

                $row = 1;
                $sheet->row($row, [
                    'Barcode',
                    'Kode',
                    'Nama',
                    'Satuan',
                    'Harga Beli',
                    'Harga Jual 1',
                    'Harga Jual 2',
                    'Status',
                    'Kategori',
                ]);

                foreach ($produks as $produk) {

                    $satuan          = Satuan::where('id', $produk->satuan_id)->first();
                    $harga_beli      = (is_null($produk->harga_beli) ? 0 : $produk->harga_beli);
                    $harga_jual      = (is_null($produk->harga_jual) ? 0 : $produk->harga_jual);
                    $harga_jual      = (is_null($produk->harga_jual) ? 0 : $produk->harga_jual);
                    $harga_jual2     = (is_null($produk->harga_jual2) ? 0 : $produk->harga_jual2);
                    $status_aktif    = ($produk->status_aktif == 1 ? 'Aktif' : 'Tidak Aktif');
                    $kategori_produk = KategoriBarang::where('id', $produk->kategori_barang_id)->first();

                    $sheet->row(++$row, [

                        // convert to integer
                        (int) $produk->kode_barcode,
                        $produk->kode_barang,
                        $produk->nama_barang,
                        $satuan->nama_satuan,
                        $harga_beli,
                        $harga_jual,
                        $harga_jual2,
                        $status_aktif,
                        $kategori_produk->nama_kategori_barang,
                    ]);
                }
            });
        })->download('xls');
    }

    public function otoritasProduk(){

        if (Laratrust::can('tambah_produk')) {
            $tambah_produk = 1;
        }else{
            $tambah_produk = 0;            
        }
        if (Laratrust::can('edit_produk')) {
            $edit_produk = 1;
        }else{
            $edit_produk = 0;            
        }
        if (Laratrust::can('hapus_produk')) {
            $hapus_produk = 1;
        }else{
            $hapus_produk = 0;            
        }
        $respons['tambah_produk'] = $tambah_produk;
        $respons['edit_produk'] = $edit_produk;
        $respons['hapus_produk'] = $hapus_produk;

        return response()->json($respons);
    }
}
