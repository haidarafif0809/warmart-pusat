<?php

namespace App\Http\Controllers;

use App\Kas;
use App\KasMasuk;
use App\KategoriTransaksi;
use App\TransaksiKas;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Html\Builder;

class KasMasukController extends Controller
{

    public function __construct()
    {
        $this->middleware('user-must-warung');
    }
    //PROSES MENAMPILKAN KAS MASUK
    public function index(Request $request, Builder $htmlBuilder)
    {
        return view('kas_masuk.index')->with(compact('html'));
    }

    public function dataPagination($kas_masuk, $kas_masuk_array)
    {

        $respons['current_page']   = $kas_masuk->currentPage();
        $respons['data']           = $kas_masuk_array;
        $respons['first_page_url'] = url('/kas/view?page=' . $kas_masuk->firstItem());
        $respons['from']           = 1;
        $respons['last_page']      = $kas_masuk->lastPage();
        $respons['last_page_url']  = url('/kas/view?page=' . $kas_masuk->lastPage());
        $respons['next_page_url']  = $kas_masuk->nextPageUrl();
        $respons['path']           = url('/kas/view');
        $respons['per_page']       = $kas_masuk->perPage();
        $respons['prev_page_url']  = $kas_masuk->previousPageUrl();
        $respons['to']             = $kas_masuk->perPage();
        $respons['total']          = $kas_masuk->total();

        return $respons;
    }

    public function view()
    {
        $kas_masuk = KasMasuk::select('kas_masuks.id as id', 'kas_masuks.no_faktur as no_faktur', 'kas.nama_kas as nama_kas', 'kategori_transaksis.nama_kategori_transaksi as nama_kategori_transaksi', 'kas_masuks.jumlah as jumlah', 'kas_masuks.keterangan as keterangan', 'kas_masuks.kas as kas')->leftJoin('kas', 'kas_masuks.kas', '=', 'kas.id')
            ->leftJoin('kategori_transaksis', 'kas_masuks.kategori', '=', 'kategori_transaksis.id')->orwhere('kas_masuks.id_warung', Auth::user()->id_warung)->orderBy('kas_masuks.id', 'desc')->paginate(10);
        $kas_masuk_array = array();
        foreach ($kas_masuk as $kas_masuks) {

            $sisa_kas = TransaksiKas::select(DB::raw('SUM(jumlah_masuk - jumlah_keluar) as total_kas'))
                ->where('kas', $kas_masuks->kas)
                ->where('warung_id', Auth::user()->id_warung)
                ->where('no_faktur', '!=', $kas_masuks->no_faktur)
                ->first();

            if ($sisa_kas->total_kas < 0) {
                $status_transaksi = 1;
            } else {
                $status_transaksi = 0;
            }

            array_push($kas_masuk_array, ['kas_masuk' => $kas_masuks, 'status_transaksi' => $status_transaksi]);
        }
        //DATA PAGINATION
        $respons = $this->dataPagination($kas_masuk, $kas_masuk_array);
        return response()->json($respons);
    }
    public function pencarian(Request $request)
    {
        $search = $request->search;
        //query pencarian
        $kas_masuk = KasMasuk::select('kas_masuks.id as id', 'kas_masuks.no_faktur as no_faktur', 'kas.nama_kas as nama_kas', 'kategori_transaksis.nama_kategori_transaksi as nama_kategori_transaksi', 'kas_masuks.jumlah as jumlah', 'kas_masuks.keterangan as keterangan')->leftJoin('kas', 'kas_masuks.kas', '=', 'kas.id')
            ->leftJoin('kategori_transaksis', 'kas_masuks.kategori', '=', 'kategori_transaksis.id')
            ->where('kas_masuks.id_warung', Auth::user()->id_warung)
            ->where(function ($query) use ($search) {
// search
                $query->where('kas.nama_kas', 'LIKE', '%' . $search . '%')
                    ->orWhere('kategori_transaksis.nama_kategori_transaksi', 'LIKE', '%' . $search . '%')
                    ->orWhere('kas_masuks.jumlah', 'LIKE', '%' . $search . '%')
                    ->orWhere('kas_masuks.keterangan', 'LIKE', '%' . $search . '%')
                    ->orWhere('kas_masuks.no_faktur', 'LIKE', '%' . $search . '%');
            })->paginate(10);

        $kas_masuk_array = array();
        foreach ($kas_masuk as $kas_masuks) {
            array_push($kas_masuk_array, ['kas_masuk' => $kas_masuks]);
        }

        //DATA PAGINATION
        $respons = $this->dataPagination($kas_masuk, $kas_masuk_array);
        return response()->json($respons);
    }

    public function create()
    {
        //MENAMPILKAN KAS
        $data_kas = DB::table('kas')
            ->where('warung_id', Auth::user()->id_warung)
            ->pluck('nama_kas', 'id');

        //MENAMPILKAN KATEGORI TRANSAKSI
        $data_kategori_transaksi = DB::table('kategori_transaksis')
            ->where('id_warung', Auth::user()->id_warung)
            ->pluck('nama_kategori_transaksi', 'id');

        return view('kas_masuk.create', ['data_kategori_transaksi' => $data_kategori_transaksi, 'data_kas' => $data_kas]);
    }

    public function pilih_kas()
    {
        $kas = Kas::select('id', 'nama_kas')->where('warung_id', Auth::user()->id_warung)->get();
        return response()->json($kas);
    }

    public function pilih_kategori()
    {
        $kategori_transaksi = KategoriTransaksi::select('id', 'nama_kategori_transaksi')->where('id_warung', Auth::user()->id_warung)->get();
        return response()->json($kategori_transaksi);
    }

    //PROSES TAMBAH KAS MASUK
    public function store(Request $request)
    {
        $this->validate($request, [
            'kas'        => 'required',
            'kategori'   => 'required',
            'jumlah'     => 'required|numeric',
            'keterangan' => 'max:150',
        ]);

        if ($request->keterangan == "") {
            $keterangan = "-";
        } else {
            $keterangan = $request->keterangan;
        }

        if (Auth::user()->id_warung != "") {
            $id_warung = Auth::user()->id_warung;
            $no_faktur = KasMasuk::no_faktur($id_warung);
            $kas       = KasMasuk::create(['no_faktur' => $no_faktur, 'kas' => $request->kas, 'kategori' => $request->kategori, 'jumlah' => $request->jumlah, 'keterangan' => $keterangan, 'id_warung' => Auth::user()->id_warung]);

            //PROSES MEMBUAT TRANSAKSI KAS
            TransaksiKas::create(['no_faktur' => $no_faktur, 'jenis_transaksi' => 'kas_masuk', 'jumlah_masuk' => $request->jumlah, 'kas' => $request->kas, 'warung_id' => Auth::user()->id_warung]);
        } else {
            Auth::logout();
            return response()->view('error.403');
        }
    }

    //PROSES KE HALAMAN EDIT
    public function edit($id)
    {
        $id_warung = Auth::user()->id_warung;
        $kas_masuk = KasMasuk::find($id);
        //MENAMPILKAN KAS
        $data_kas = DB::table('kas')
            ->where('warung_id', Auth::user()->id_warung)
            ->pluck('nama_kas', 'id');

        //MENAMPILKAN KATEGORI TRANSAKSI
        $data_kategori_transaksi = DB::table('kategori_transaksis')
            ->where('id_warung', Auth::user()->id_warung)
            ->pluck('nama_kategori_transaksi', 'id');

        if ($id_warung == $kas_masuk->id_warung) {
            return view('kas_masuk.edit', ['data_kategori_transaksi' => $data_kategori_transaksi, 'data_kas' => $data_kas])->with(compact('kas_masuk'));
        } else {
            Auth::logout();
            return response()->view('error.403');
        }
    }

    public function show($id)
    {

        $id_warung = Auth::user()->id_warung;
        $kas_masuk = KasMasuk::find($id);

        return $kas_masuk;
    }

    //PROSES EDIT KAS Masuk
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'kategori'   => 'required',
            'jumlah'     => 'required|numeric',
            'keterangan' => 'max:150',
        ]);

        if ($request->keterangan == "") {
            $keterangan = "-";
        } else {
            $keterangan = $request->keterangan;
        }

        $kas_masuk = KasMasuk::find($id);
        $id_warung = Auth::user()->id_warung;

        if ($id_warung == $kas_masuk->id_warung) {

            $kas = KasMasuk::find($id)->update(['kategori' => $request->kategori, 'jumlah' => $request->jumlah, 'keterangan' => $keterangan]);
            //PROSES UPDATE TRANSAKSI KAS
            TransaksiKas::where('no_faktur', $kas_masuk->no_faktur)->update(['jumlah_masuk' => $request->jumlah]);

        } else {
            Auth::logout();
            return response()->view('error.403');
        }
    }

    public function destroy($id)
    {
        $kas_masuk = KasMasuk::find($id);

        if ($kas_masuk->id_warung != Auth::user()->id_warung) {
            Auth::logout();
            return response()->view('error.403');
        } else {
            TransaksiKas::where('no_faktur', $kas_masuk->no_faktur)->delete();
            // jika gagal hapus
            KasMasuk::destroy($id);
            // redirect back
        }
    }

    public function cekKasTerpakai($id)
    {
        $kas_masuk = KasMasuk::find($id);
        // hitung kas
        $sisa_kas = TransaksiKas::select(DB::raw('SUM(jumlah_masuk - jumlah_keluar) as total_kas'))
            ->where('kas', $kas_masuk->kas)
            ->where('warung_id', Auth::user()->id_warung)
            ->where('no_faktur', '!=', $kas_masuk->no_faktur)
            ->first();
        $jumlah_kas = $sisa_kas->total_kas;
        return $jumlah_kas;

    }
}
