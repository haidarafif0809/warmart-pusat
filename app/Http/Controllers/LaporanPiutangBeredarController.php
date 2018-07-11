<?php

namespace App\Http\Controllers;

use App\SettingAplikasi;
use App\Pelanggan;
use App\TransaksiPiutang;
use App\Warung;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;
use Yajra\Datatables\Html\Builder;
use Excel;

class LaporanPiutangBeredarController extends Controller
{
    //
   //VIEW DAN PENCARIAN dataLaporanPiutang
   public function prosesPiutangBeredar(Request $request)
   {
	    $session_id    = session()->getId();
	    $user_warung   = Auth::user()->id_warung;        
	    if ($request->pelanggan == "semua") {
	        $request['pelanggan'] = "";
	    };

	    // jika yg dipilh adalah laporan beredar 
	    if ($request->pilih_laporan == 1) {
	        $data_pelanggan_piutang = TransaksiPiutang::getDataPiutangBeredar($request)->havingRaw('IFNULL(SUM(transaksi_piutangs.jumlah_masuk),0) - IFNULL(SUM(transaksi_piutangs.jumlah_keluar),0) > 0')->paginate(10);
	    }else{
	        $data_pelanggan_piutang = TransaksiPiutang::getDataPiutangBeredar($request)->havingRaw('IFNULL(SUM(transaksi_piutangs.jumlah_keluar),0) > 0')->paginate(10);
	    }

	    $array         = array();
	    foreach ($data_pelanggan_piutang as $data_pelanggan_piutangs) {
	    	if ($data_pelanggan_piutangs->pelanggan_id == '0') {
                $pelanggan = 'Umum';
            } else {
                $pelanggan = $data_pelanggan_piutangs->nama_pelanggan;
            }

	        array_push($array, [
	            'id'             		=> $data_pelanggan_piutangs->id,
	            'waktu'                 => $data_pelanggan_piutangs->Waktu,
	            'no_faktur'				=> $data_pelanggan_piutangs->no_faktur,
	            'pelanggan'            	=> $pelanggan,
	            'nilai_transaksi'       => $data_pelanggan_piutangs->total,
	            'pembayaran'       		=> $data_pelanggan_piutangs->pembayaran,
	            'sisa_piutang'       	=> $data_pelanggan_piutangs->sisa_piutang,
	            'jatuh_tempo'           => $data_pelanggan_piutangs->tanggal_jt_tempo,
	            'umur_piutang'       	=> $data_pelanggan_piutangs->usia_piutang,
	            'petugas'               => $data_pelanggan_piutangs->name,
	        ]);
	    }

	    $url     = '/laporan-piutang-beredar/view'; 
	    $respons = $this->dataPagination($data_pelanggan_piutang, $array,$url); 
	    return response()->json($respons); 
	}

	public function pencarianPiutangBeredar(Request $request)
	{
	    $session_id    = session()->getId();
	    $user_warung   = Auth::user()->id_warung;        
	    if ($request->pelanggan == "semua") {
	        $request['pelanggan'] = "";
	    };
	        // jika yg dipilh adalah laporan beredar 
	    if ($request->pilih_laporan == 1) {
	        $data_pelanggan_piutang = TransaksiPiutang::cariDataPiutangBeredar($request)->havingRaw('IFNULL(SUM(transaksi_piutangs.jumlah_masuk),0) - IFNULL(SUM(transaksi_piutangs.jumlah_keluar),0) > 0')->paginate(10);
	    }else{
	        $data_pelanggan_piutang = TransaksiPiutang::cariDataPiutangBeredar($request)->havingRaw('IFNULL(SUM(transaksi_piutangs.jumlah_keluar),0) > 0')->paginate(10);
	    }

	    $array         = array();
	    foreach ($data_pelanggan_piutang as $data_pelanggan_piutangs) {
	    		if ($data_pelanggan_piutangs->pelanggan_id == '0') {
	                $pelanggan = 'Umum';
	            } else {
	                $pelanggan = $data_pelanggan_piutangs->nama_pelanggan;
	            }
	        array_push($array, [
	            'id'                    => $data_pelanggan_piutangs->id,
	            'waktu'                 => $data_pelanggan_piutangs->Waktu,
	            'no_faktur'             => $data_pelanggan_piutangs->no_faktur,
	            'pelanggan'             => $pelanggan,
	            'nilai_transaksi'       => $data_pelanggan_piutangs->total,
	            'pembayaran'            => $data_pelanggan_piutangs->pembayaran,
	            'sisa_piutang'           => $data_pelanggan_piutangs->sisa_piutang,
	            'jatuh_tempo'           => $data_pelanggan_piutangs->tanggal_jt_tempo,
	            'umur_piutang'           => $data_pelanggan_piutangs->usia_piutang,
	            'petugas'               => $data_pelanggan_piutangs->name,
	        ]);
	    }

	    $url     = '/laporan-piutang-beredar/view'; 
	    $respons = $this->dataPagination($data_pelanggan_piutang, $array,$url); 
	    return response()->json($respons); 
	}


	public function totalPiutangBeredar(Request $request)
	{
	        // TOTAL KESELURUHAN
		    if ($request->pelanggan == "semua") {
		        $request['pelanggan'] = "";
		    };
	        // jika yg dipilh adalah laporan beredar 

	    	if ($request->pilih_laporan == 1) {
		        $total_piutang_beredar = TransaksiPiutang::getDataPiutangBeredar($request)->havingRaw('IFNULL(SUM(transaksi_piutangs.jumlah_masuk),0) - IFNULL(SUM(transaksi_piutangs.jumlah_keluar),0) > 0')->get();
		    }else{
		        $total_piutang_beredar = TransaksiPiutang::getDataPiutangBeredar($request)->havingRaw('IFNULL(SUM(transaksi_piutangs.jumlah_keluar),0) > 0')->get();
		    }

	    $jumlah_masuk = 0;
	    $jumlah_keluar = 0;
	    $sisa_piutang = 0;
	    foreach ($total_piutang_beredar as $total_piutang_beredars) {
	        $jumlah_keluar += $total_piutang_beredars->pembayaran;
	        $jumlah_masuk += $total_piutang_beredars->total;
	        $sisa_piutang += $total_piutang_beredars->sisa_piutang;
	    } 
	    $respons['nilai_transaksi']  =  $jumlah_masuk;
	    $respons['pembayaran']       =  $jumlah_keluar;
	    $respons['sisa_piutang']      =  $sisa_piutang;


	    return response()->json($respons);
	}

	public function dataPagination($data_pelanggan_piutang, $array,$url) 
	{ 
	        //DATA PAGINATION
	    $respons['current_page']   = $data_pelanggan_piutang->currentPage();
	    $respons['data']           = $array;
	    $respons['first_page_url'] = url($url.'?page=' . $data_pelanggan_piutang->firstItem());
	    $respons['from']           = 1;
	    $respons['last_page']      = $data_pelanggan_piutang->lastPage();
	    $respons['last_page_url']  = url($url.'?page=' . $data_pelanggan_piutang->lastPage());
	    $respons['next_page_url']  = $data_pelanggan_piutang->nextPageUrl();
	    $respons['path']           = url($url);
	    $respons['per_page']       = $data_pelanggan_piutang->perPage();
	    $respons['prev_page_url']  = $data_pelanggan_piutang->previousPageUrl();
	    $respons['to']             = $data_pelanggan_piutang->perPage();
	    $respons['total']          = $data_pelanggan_piutang->total();
	        //DATA PAGINATION
	    return $respons; 
	}

	   //DOWNLOAD EXCEL
	public function downloadExcel(Request $request, $dari_tanggal, $sampai_tanggal, $pelanggan, $laporan)
	{
	    $request['dari_tanggal']   = $dari_tanggal;
	    $request['sampai_tanggal'] = $sampai_tanggal;
	    if ($pelanggan == "semua") {
	        $request['pelanggan'] = "";
	    };
	        // jika yg dipilh adalah laporan beredar 
	    if ($laporan == 1) {
	        $data_pelanggan_piutang = TransaksiPiutang::getDataPiutangBeredar($request)->havingRaw('IFNULL(SUM(transaksi_piutangs.jumlah_masuk),0) - IFNULL(SUM(transaksi_piutangs.jumlah_keluar),0) > 0')->get();
	    }else{
	        $data_pelanggan_piutang = TransaksiPiutang::getDataPiutangBeredar($request)->havingRaw('IFNULL(SUM(transaksi_piutangs.jumlah_keluar),0) > 0')->get();
	    }

	    $jumlah_masuk = 0;
	    $jumlah_keluar = 0;
	    $sisa_piutang = 0;
	    foreach ($data_pelanggan_piutang as $data_pelanggan_piutangs) {
	        $jumlah_keluar += $data_pelanggan_piutangs->pembayaran;
	        $jumlah_masuk += $data_pelanggan_piutangs->total;
	        $sisa_piutang += $data_pelanggan_piutangs->sisa_piutang;
	    }

	    Excel::create('Laporan Piutang', function ($excel) use ($request, $data_pelanggan_piutang,$jumlah_masuk,$jumlah_keluar,$sisa_piutang,$laporan) {
	            // Set property
	        $excel->sheet('Laporan Piutang', function ($sheet) use ($request, $data_pelanggan_piutang,$jumlah_masuk,$jumlah_keluar,$sisa_piutang,$laporan) {
	            $row = 1;
	            if ($laporan == 1) {
	                $sheet->row($row, [
	                    'LAPORAN PIUTANG BEREDAR',
	                ]);
	            }else{
	              $sheet->row($row, [
	                'LAPORAN PIUTANG TERBAYAR',
	            ]);
	          }

	          $row   = 3;
	          $sheet = $this->labelSheet($sheet, $row);


	          foreach ($data_pelanggan_piutang as $data_pelanggan_piutangs) {
		          if ($data_pelanggan_piutangs->pelanggan_id == '0') {
		                $pelanggan = 'Umum';
		           } else {
		                $pelanggan = $data_pelanggan_piutangs->nama_pelanggan;
		           }
	              $sheet->row(++$row, [
	                $data_pelanggan_piutangs->Waktu,
	                $data_pelanggan_piutangs->no_faktur,
	                $pelanggan,
	                $data_pelanggan_piutangs->total,
	                $data_pelanggan_piutangs->pembayaran,
	                $data_pelanggan_piutangs->sisa_piutang,
	                $this->tanggal($data_pelanggan_piutangs->tanggal_jt_tempo),
	                $data_pelanggan_piutangs->usia_piutang." Hari",
	                $data_pelanggan_piutangs->name,
	            ]);

	          }
	//PERHITUNGAN TOTAL PIUTANG BEREDAR
	          $row = ++$row;
	          $sheet->row(++$row, [
	            'TOTAL',
	            '',
	            '',
	            $jumlah_masuk,
	            $jumlah_keluar,
	            $sisa_piutang,
	            '',
	            '',
	            ''
	        ]);

	      });
	    })->export('xls');
	}

	public function tanggal($tangal)
	{
	    $date        = date_create($tangal);
	    $date_format = date_format($date, "d-m-Y");
	    return $date_format;
	}

	public function labelSheet($sheet, $row)
	{
	    $sheet->row($row, [
	        'Waktu',
	        'No Transaksi',
	        'Pelanggan',
	        'Nilai Transaksi',
	        'Dibayar',
	        'Nilai Piutang',
	        'Jatuh Tempo',
	        'Umur Piutang',
	        'Petugas',
	    ]);
	    return $sheet;
	}

	public function cetakLaporan(Request $request, $dari_tanggal, $sampai_tanggal, $pelanggan, $laporan)
{
        //SETTING APLIKASI
    $setting_aplikasi = SettingAplikasi::select('tipe_aplikasi')->first();

    $request['dari_tanggal']   = $dari_tanggal;
    $request['sampai_tanggal'] = $sampai_tanggal;

    if ($pelanggan == "semua") {
        $request['pelanggan'] = "";
    };
        // jika yg dipilh adalah laporan beredar 
    if ($laporan == 1) {
        $data_pelanggan_piutang = TransaksiPiutang::getDataPiutangBeredar($request)->havingRaw('IFNULL(SUM(transaksi_piutangs.jumlah_masuk),0) - IFNULL(SUM(transaksi_piutangs.jumlah_keluar),0) > 0')->get();
    }else{
        $data_pelanggan_piutang = TransaksiPiutang::getDataPiutangBeredar($request)->havingRaw('IFNULL(SUM(transaksi_piutangs.jumlah_keluar),0) > 0')->get();
    }

    $jumlah_masuk = 0;
    $jumlah_keluar = 0;
    $sisa_piutang = 0;
    foreach ($data_pelanggan_piutang as $data_pelanggan_piutangs) {
        $jumlah_keluar += $data_pelanggan_piutangs->pembayaran;
        $jumlah_masuk += $data_pelanggan_piutangs->total;
        $sisa_piutang += $data_pelanggan_piutangs->sisa_piutang;
    }

    $data_warung              = Warung::where('id', Auth::user()->id_warung)->first();

    return view('laporan.cetak_laporan_piutang_beredar',
        [
            'data_pelanggan_piutang'    => $data_pelanggan_piutang,
            'nilai_transaksi'           => $jumlah_masuk,
            'pembayaran'                => $jumlah_keluar,
            'sisa_piutang'              => $sisa_piutang,                                
            'data_warung'               => $data_warung,
            'dari_tanggal'              => $this->tanggal($dari_tanggal),
            'sampai_tanggal'            => $this->tanggal($sampai_tanggal),
            'setting_aplikasi'          => $setting_aplikasi,
            'petugas'                   => Auth::user()->name,
            'laporan'                   => $laporan
        ])->with(compact('html'));
}

}
