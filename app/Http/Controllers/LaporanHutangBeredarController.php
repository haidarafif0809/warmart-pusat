<?php

namespace App\Http\Controllers;


use App\SettingAplikasi;
use App\Suplier;
use App\TransaksiHutang;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;
use Yajra\Datatables\Html\Builder;
use Excel;


class LaporanHutangBeredarController extends Controller
{
    //

        //VIEW DAN PENCARIAN dataSupplierHutang
     public function prosesHutangBeredar(Request $request)
    {
        $session_id    = session()->getId();
        $user_warung   = Auth::user()->id_warung;        
        if ($request->suplier == "semua") {
            $request['suplier'] = "";
        };
        $data_supplier_hutang = TransaksiHutang::getDataHutangBeredar($request)->paginate(10);

        $array         = array();
        foreach ($data_supplier_hutang as $data_supplier_hutangs) {
            array_push($array, [
                'id'             		=> $data_supplier_hutangs->id,
                'waktu'                 => $data_supplier_hutangs->Waktu,
                'no_faktur'				=> $data_supplier_hutangs->no_faktur,
                'suplier'               => $data_supplier_hutangs->nama_suplier,
                'nilai_transaksi'       => $data_supplier_hutangs->total,
                'pembayaran'       		=> $data_supplier_hutangs->pembayaran,
                'sisa_hutang'       	=> $data_supplier_hutangs->sisa_hutang,
                'jatuh_tempo'           => $data_supplier_hutangs->tanggal_jt_tempo,
                'umur_hutang'       	=> $data_supplier_hutangs->usia_hutang,
                'petugas'               => $data_supplier_hutangs->name,
             ]);
        }

        $url     = '/laporan-hutang-beredar/view'; 
        $respons = $this->dataPagination($data_supplier_hutang, $array,$url); 
        return response()->json($respons); 
    }

            //VIEW DAN PENCARIAN dataSupplierHutang
     public function pencarianHutangBeredar(Request $request)
    {
        $session_id    = session()->getId();
        $user_warung   = Auth::user()->id_warung;        
        if ($request->suplier == "semua") {
            $request['suplier'] = "";
        };
        $data_supplier_hutang = TransaksiHutang::cariDataHutangBeredar($request)->paginate(10);

        $array         = array();
        foreach ($data_supplier_hutang as $data_supplier_hutangs) {
            array_push($array, [
                'id'                    => $data_supplier_hutangs->id,
                'waktu'                 => $data_supplier_hutangs->Waktu,
                'no_faktur'             => $data_supplier_hutangs->no_faktur,
                'suplier'               => $data_supplier_hutangs->nama_suplier,
                'nilai_transaksi'       => $data_supplier_hutangs->total,
                'pembayaran'            => $data_supplier_hutangs->pembayaran,
                'sisa_hutang'           => $data_supplier_hutangs->sisa_hutang,
                'jatuh_tempo'           => $data_supplier_hutangs->tanggal_jt_tempo,
                'umur_hutang'           => $data_supplier_hutangs->usia_hutang,
                'petugas'               => $data_supplier_hutangs->name,
             ]);
        }

        $url     = '/laporan-hutang-beredar/view'; 
        $respons = $this->dataPagination($data_supplier_hutang, $array,$url); 
        return response()->json($respons); 
    }

        public function totalHutangBeredar(Request $request)
    {
        // TOTAL KESELURUHAN
        if ($request->suplier == "semua") {
            $request['suplier'] = "";
        };
        $total_hutang_beredar = TransaksiHutang::totalHutangBeredar($request);
        $total_sisa_hutang = TransaksiHutang::totalSisaHutang($request)->get();

        $jumlah_masuk = 0;
        $jumlah_keluar = 0;
        $array       = array();
        foreach ($total_sisa_hutang as $total_sisa_hutangs) {
                $jumlah_keluar = $total_sisa_hutangs->pembayaran++;
                $jumlah_masuk = $total_sisa_hutangs->nilai_transaksi++;

        }

                if ($total_hutang_beredar->count() == 0) {
                        $nilai_transaksi = 0;
                        $pembayaran      = 0;
                        $sisa_hutang     = 0;           
                }else{
                        $nilai_transaksi = $total_hutang_beredar->first()->nilai_transaksi - $jumlah_masuk;
                        $pembayaran      = $total_hutang_beredar->first()->pembayaran - $jumlah_keluar;
                        $sisa_hutang     = $total_hutang_beredar->first()->sisa_hutang;     
                }

            	$respons['nilai_transaksi']  =  $nilai_transaksi;
            	$respons['pembayaran']       =  $pembayaran;
            	$respons['sisa_hutang']      =  $sisa_hutang;
 
       
        return response()->json($respons);
    }

            public function dataPagination($data_supplier_hutang, $array,$url) 
    { 
        //DATA PAGINATION
        $respons['current_page']   = $data_supplier_hutang->currentPage();
        $respons['data']           = $array;
        $respons['first_page_url'] = url($url.'?page=' . $data_supplier_hutang->firstItem());
        $respons['from']           = 1;
        $respons['last_page']      = $data_supplier_hutang->lastPage();
        $respons['last_page_url']  = url($url.'?page=' . $data_supplier_hutang->lastPage());
        $respons['next_page_url']  = $data_supplier_hutang->nextPageUrl();
        $respons['path']           = url($url);
        $respons['per_page']       = $data_supplier_hutang->perPage();
        $respons['prev_page_url']  = $data_supplier_hutang->previousPageUrl();
        $respons['to']             = $data_supplier_hutang->perPage();
        $respons['total']          = $data_supplier_hutang->total();
        //DATA PAGINATION
        return $respons; 
    } 

   //DOWNLOAD EXCEL - LAPORAN LABA KOTOR /PELANGGAN
    public function downloadExcel(Request $request, $dari_tanggal, $sampai_tanggal, $suplier)
    {
        $request['dari_tanggal']   = $dari_tanggal;
        $request['sampai_tanggal'] = $sampai_tanggal;
        if ($suplier == "semua") {
            $request['suplier'] = "";
        };

         $data_supplier_hutang = TransaksiHutang::getDataHutangBeredar($request)->paginate(10);

         $total_penjualan = TransaksiHutang::totalHutangBeredar($request);

        Excel::create('Laporan Hutang Beredar', function ($excel) use ($request, $data_supplier_hutang,$total_penjualan) {
            // Set property
            $excel->sheet('Laporan Hutang Beredar', function ($sheet) use ($request, $data_supplier_hutang,$total_penjualan) {
                $row = 1;
                $sheet->row($row, [
                    'LAPORAN HUTANG BEREDAR',
                ]);

                $row   = 3;
                $sheet = $this->labelSheet($sheet, $row);


                foreach ($data_supplier_hutang as $data_supplier_hutangs) {

                  $sheet->row(++$row, [
                    $data_supplier_hutangs->Waktu,
                    $data_supplier_hutangs->no_faktur,
                    $data_supplier_hutangs->nama_suplier,
                    $data_supplier_hutangs->total,
                    $data_supplier_hutangs->pembayaran,
                    $data_supplier_hutangs->sisa_hutang,
                    $data_supplier_hutangs->tanggal_jt_tempo,
                    $data_supplier_hutangs->usia_hutang." Hari",
                    $data_supplier_hutangs->name,
                ]);

              }
//PERHITUNGAN TOTAL HUTANG BEREDAR
                if ($total_penjualan->count() == 0) {
                        $nilai_transaksi = 0;
                        $pembayaran      = 0;
                        $sisa_hutang     = 0;           
                }else{
                        $nilai_transaksi = $total_penjualan->first()->nilai_transaksi;
                        $pembayaran      = $total_penjualan->first()->pembayaran;
                        $sisa_hutang     = $total_penjualan->first()->sisa_hutang;     
                }
              $row = ++$row;
                    $sheet->row(++$row, [
                        'TOTAL',
                        '',
                        '',
                        $nilai_transaksi,
                        $pembayaran,
                        $sisa_hutang,
                        '',
                        '',
                        ''
                    ]);

          });
        })->export('xls');
    }

        public function labelSheet($sheet, $row)
    {
        $sheet->row($row, [
            'Waktu',
            'No Transaksi',
            'Supplier',
            'Nilai Transaksi',
            'Dibayar',
            'Nilai Hutang',
            'Jatuh Tempo',
            'Umur Hutang',
            'Petugas',
        ]);
        return $sheet;
    }

}
