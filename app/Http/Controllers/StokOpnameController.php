<?php

namespace App\Http\Controllers;

use App\DetailPembelian;
use App\Hpp;
use App\StokOpname;
use Auth;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laratrust;

class StokOpnameController extends Controller
{
    public function dataPagination($data_stok_opname, $array_stok_opname)
    {

        $respons['current_page']   = $data_stok_opname->currentPage();
        $respons['data']           = $array_stok_opname;
        $respons['otoritas']      = $this->otoritasStokOpname();
        $respons['first_page_url'] = url('/stok-  opname/view?page=' . $data_stok_opname->firstItem());
        $respons['from']           = 1;
        $respons['last_page']      = $data_stok_opname->lastPage();
        $respons['last_page_url']  = url('/stok-  opname/view?page=' . $data_stok_opname->lastPage());
        $respons['next_page_url']  = $data_stok_opname->nextPageUrl();
        $respons['path']           = url('/stok-  opname/view');
        $respons['per_page']       = $data_stok_opname->perPage();
        $respons['prev_page_url']  = $data_stok_opname->previousPageUrl();
        $respons['to']             = $data_stok_opname->perPage();
        $respons['total']          = $data_stok_opname->total();

        return $respons;
    }

    public function foreachStokOpname($data_stok_opname)
    {

        $array_stok_opname = array();
        foreach ($data_stok_opname as $stok_opname) {
            $nama_produk = title_case($stok_opname->nama_barang);

            array_push($array_stok_opname, ['stok_opname' => $stok_opname, 'nama_produk' => $nama_produk]);
        }

        //DATA PAGINATION
        $respons = $this->dataPagination($data_stok_opname, $array_stok_opname);

        return $respons;
    }

    public function labelSheet($sheet, $row)
    {
        $sheet->row($row, [
            'No. Faktur',
            'Produk',
            'Stok Komputer',
            'Stok Fisik',
            'Selisih Fisik',
            'Harga',
            'Selisih Harga',
            'Tanggal',
        ]);
        return $sheet;
    }

    public function view()
    {
        $data_stok_opname = StokOpname::dataStokOpname()->paginate(10);
        $respons          = $this->foreachStokOpname($data_stok_opname);
        return response()->json($respons);
    }

    public function pencarian(Request $request)
    {
        $data_stok_opname = StokOpname::cariDataStokOpname($request)->paginate(10);
        $respons          = $this->foreachStokOpname($data_stok_opname);
        return response()->json($respons);
    }

    public function filterPeriode(Request $request)
    {
        $data_stok_opname = StokOpname::dataFilterStokOpname($request)->paginate(10);
        $respons          = $this->foreachStokOpname($data_stok_opname);
        return response()->json($respons);
    }

    public function totalStokOpname(Request $request)
    {
        //SUBTOTAL STOK OPNAME FILTER
        $sub_total_stok_opname = StokOpname::subtotalStokOpname($request)->first();

        return $sub_total_stok_opname;
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        $warung_id     = Auth::user()->id_warung;
        $no_faktur     = StokOpname::no_faktur($warung_id);
        $stok_sekarang = Hpp::stok_produk($request->produk);
        $selisih_fisik = $request->jumlah_produk - $stok_sekarang;

        if ($selisih_fisik < 0) {
            // Harga Hpp
            $harga = Hpp::hargaHpp($request->produk, $warung_id);
        } else {
            // Harga Produk / Pembelian
            $data_pembelian = DetailPembelian::hargaProduk($request->produk, $warung_id);
            if ($data_pembelian->count() > 0) {
                $harga = $data_pembelian->first()->harga_produk;
            } else {
                $harga = $request->harga_produk;
            }
        }

        $total_selisih = $harga * $selisih_fisik;

        $this->validate($request, [
            'jumlah_produk' => 'required',
        ]);

        $stok_opname = StokOpname::create([
            'no_faktur'     => $no_faktur,
            'produk_id'     => $request->produk,
            'stok_sekarang' => $stok_sekarang,
            'jumlah_fisik'  => $request->jumlah_produk,
            'selisih_fisik' => $selisih_fisik,
            'harga'         => $harga,
            'total'         => $total_selisih,
            'warung_id'     => $warung_id,
            'keterangan'    => "Tes #0",
        ]);

        DB::commit();
        return response(200);
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        $data_stok_opname = StokOpname::find($id);
        $selisih_fisik    = $request->jumlah_produk - $data_stok_opname->stok_sekarang;
        $total            = $data_stok_opname->harga * $selisih_fisik;

        $this->validate($request, [
            'jumlah_produk' => 'required',
        ]);
        $data_stok_opname->update([
            'jumlah_fisik'  => $request->jumlah_produk,
            'selisih_fisik' => $selisih_fisik,
            'total'         => $total,
        ]);

        DB::commit();
        return response(200);

    }

    public function destroy($id)
    {
        if (!StokOpname::destroy($id)) {
            return 0;
        } else {
            return response(200);
        }
    }

    public function downloadExcelFaktur($id)
    {
        $stok_opname = StokOpname::dataStokOpnamePerfaktur($id)->first();
        Excel::create('Stok Opname Faktur', function ($excel) use ($stok_opname) {
            // Set property
            $excel->sheet('Stok Opname Faktur', function ($sheet) use ($stok_opname) {
                $row = 1;
                $sheet->row($row, [
                    'STOK OPNAME /FAKTUR : ' . $stok_opname->no_faktur,
                ]);

                $row   = 3;
                $sheet = $this->labelSheet($sheet, $row);

                $sheet->row(++$row, [
                    $stok_opname->no_faktur,
                    title_case($stok_opname->nama_barang),
                    $stok_opname->stok_sekarang,
                    $stok_opname->jumlah_fisik,
                    $stok_opname->selisih_fisik,
                    $stok_opname->harga,
                    $stok_opname->total,
                    $stok_opname->created_at,
                ]);

            });
        })->export('xls');
    }

    public function downloadExcelPeriode(Request $request, $dari_tanggal, $sampai_tanggal)
    {
        $request['dari_tanggal']   = $dari_tanggal;
        $request['sampai_tanggal'] = $sampai_tanggal;
        $stok_opname               = StokOpname::dataFilterStokOpname($request)->get();
        Excel::create('Stok Opname Faktur', function ($excel) use ($stok_opname, $request) {
            // Set property
            $excel->sheet('Stok Opname Faktur', function ($sheet) use ($stok_opname, $request) {
                $row = 1;
                $sheet->row($row, [
                    'STOK OPNAME /PERIODE : ' . $request->dari_tanggal . ' - ' . $request->sampai_tanggal,
                ]);

                $row   = 3;
                $sheet = $this->labelSheet($sheet, $row);

                foreach ($stok_opname as $stok_opnames) {
                    $sheet->row(++$row, [
                        $stok_opnames->no_faktur,
                        title_case($stok_opnames->nama_barang),
                        $stok_opnames->stok_sekarang,
                        $stok_opnames->jumlah_fisik,
                        $stok_opnames->selisih_fisik,
                        $stok_opnames->harga,
                        $stok_opnames->total,
                        $stok_opnames->created_at,
                    ]);
                }

                $total_stok_opname = $this->totalStokOpname($request);
                $row               = ++$row;
                $sheet->row(++$row, [
                    'TOTAL',
                    '',
                    '',
                    '',
                    $total_stok_opname->selisih_fisik,
                    '',
                    $total_stok_opname->total_selisih,
                    '',
                ]);

            });
        })->export('xls');
    }


    public function otoritasStokOpname(){

        if (Laratrust::can('tambah_stok_opname')) {
            $tambah_stok_opname = 1;
        }else{
            $tambah_stok_opname = 0;            
        }
        if (Laratrust::can('edit_stok_opname')) {
            $edit_stok_opname = 1;
        }else{
            $edit_stok_opname = 0;            
        }
        if (Laratrust::can('hapus_stok_opname')) {
            $hapus_stok_opname = 1;
        }else{
            $hapus_stok_opname = 0;            
        }
        $respons['tambah_stok_opname'] = $tambah_stok_opname;
        $respons['edit_stok_opname'] = $edit_stok_opname;
        $respons['hapus_stok_opname'] = $hapus_stok_opname;

        return response()->json($respons);
    }
}
