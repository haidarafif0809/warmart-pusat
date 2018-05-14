<?php

namespace App\Http\Controllers;

use App\Penjualan;
use App\PenjualanPos;
use App\SettingAplikasi;
use App\Warung;
use App\User;
use App\Customer;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Excel;
use Jenssegers\Agent\Agent;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Mail;
use App\Events\EventSendWhatsapp;
use Whatsapi;
use WhatsapiTool;

class LaporanBucketSizeController extends Controller
{
    public function __construct()
    {
        $this->middleware('user-must-warung');
    }

    public function random_color_part()
    {
        return str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT);
    }

    public function random_color()
    {
        return '#' . $this->random_color_part() . $this->random_color_part() . $this->random_color_part();
    }

    public function newBucketSize(Request $request, $dari_tanggal, $sampai_tanggal, $kelipatan){
        $agent    = new Agent();
        $satu      = 1;
        $kelipatan = $request->kelipatan;

        $data_penjualan_pos       = PenjualanPos::select([DB::raw('MAX(total) as total')])->where('warung_id', Auth::user()->id_warung)->first();
        $total_penjualan_terbesar = $kelipatan + $data_penjualan_pos->total;

        $jumlahKelipatan = round($total_penjualan_terbesar / $kelipatan);

        $total_faktur = [];
        $nested_array = [];
        $data = [];
        for ($i=1; $i <= $jumlahKelipatan; $i++) { 

            $data_kelipatan = ($i * $kelipatan);
            $faktur_penjualan = PenjualanPos::with('pelanggan')->countFaktur($request)->whereBetween('total', array($satu, $data_kelipatan))->orderBy('pelanggan_id','desc');
            if ($faktur_penjualan->count() != 0) {

                $respons['labels'][]    = $data_kelipatan / 1000 . " K";
                array_push($data,$faktur_penjualan->get());
                array_push($nested_array, $faktur_penjualan->count());       

            }

            $satu += $request->kelipatan;
        } 

        array_push($total_faktur,$nested_array);
        $respons['series'] = $total_faktur;
        $respons['data'] = $data;
        $respons['warung'] = Warung::select('name')->where('id',Auth::user()->id_warung)->first()->name;

        if ($agent->isMobile()) {
            $respons['agent'] = false;
        } else {
            $respons['agent'] = true;
        }
        return response()->json($respons);
    }

    public function newBucketSizeOnline(Request $request, $dari_tanggal, $sampai_tanggal, $kelipatan){
        $agent    = new Agent();
        $satu      = 1;
        $kelipatan = $request->kelipatan;

        $data_penjualan_pos       = Penjualan::select([DB::raw('MAX(total) as total')])->where('id_warung', Auth::user()->id_warung)->first();
        $total_penjualan_terbesar = $kelipatan + $data_penjualan_pos->total;

        $jumlahKelipatan = round($total_penjualan_terbesar / $kelipatan);

        $total_faktur = [];
        $nested_array = [];
        $data = [];
        for ($i=1; $i <= $jumlahKelipatan; $i++) { 

            $data_kelipatan = ($i * $kelipatan);
            $faktur_penjualan = Penjualan::with(['pelanggan','pesanan_pelanggan'])->countFaktur($request)->whereBetween('total', array($satu, $kelipatan))->orderBy('id_pelanggan','desc');
            if ($faktur_penjualan->count() != 0) {
                $respons['labels'][]    = $data_kelipatan / 1000 . " K";
                array_push($data,$faktur_penjualan->get());
                array_push($nested_array, $faktur_penjualan->count());
            }

            $satu += $request->kelipatan;
        } 

        array_push($total_faktur,$nested_array);
        $respons['data'] = $data;
        $respons['series'] = $total_faktur;
        $respons['warung'] = Warung::select('name')->where('id',Auth::user()->id_warung)->first()->name;
        if ($agent->isMobile()) {
            $respons['agent'] = false;
        } else {
            $respons['agent'] = true;
        }
        return response()->json($respons);
    }

    public function prosesLaporanBucketSize(Request $request, $dari_tanggal, $sampai_tanggal, $kelipatan)
    {
        $satu      = 1;
        $kelipatan = $request->kelipatan;

        $data_penjualan_pos       = PenjualanPos::select([DB::raw('MAX(total) as total')])->where('warung_id', Auth::user()->id_warung)->first();
        $total_penjualan_terbesar = $kelipatan + $data_penjualan_pos->total;

        while ($kelipatan <= $total_penjualan_terbesar) {
            $total_faktur_kelipatan = PenjualanPos::countFaktur($request)->whereBetween('total', array($satu, $kelipatan));

            $respons['kelipatan'][]    = $satu . " - " . $kelipatan;
            $respons['total_faktur'][] = $total_faktur_kelipatan->count();
            $respons['color'][]        = $this->random_color();

            $data_kelipatan = $request->kelipatan;
            $kelipatan += $data_kelipatan;
            $satu += $data_kelipatan;
        }

        return response()->json($respons);
    }

    public function prosesLaporanBucketSizeData(Request $request)
    {
        $satu      = 1;
        $kelipatan = $request->kelipatan;

        $data_penjualan_pos       = PenjualanPos::select([DB::raw('MAX(total) as total')])->where('warung_id', Auth::user()->id_warung)->first();
        $total_penjualan_terbesar = $kelipatan + $data_penjualan_pos->total;

        $array = array();
        while ($kelipatan <= $total_penjualan_terbesar) {
            $total_faktur_kelipatan = PenjualanPos::countFaktur($request)->whereBetween('total', array($satu, $kelipatan));

            $respons['kelipatan']    = $satu . " - " . $kelipatan;
            $respons['total_faktur'] = $total_faktur_kelipatan->count();
            $respons['color']        = $this->random_color();

            array_push($array,$respons);

            $data_kelipatan = $request->kelipatan;
            $kelipatan += $data_kelipatan;
            $satu += $data_kelipatan;
        }

        return response()->json($array);
    }

    public function prosesLaporanBucketSizeOnlineData(Request $request)
    {
        $satu      = 1;
        $kelipatan = $request->kelipatan;

        $data_penjualan_pos       = Penjualan::select([DB::raw('MAX(total) as total')])->where('id_warung', Auth::user()->id_warung)->first();
        $total_penjualan_terbesar = $kelipatan + $data_penjualan_pos->total;

        $array = array();
        while ($kelipatan <= $total_penjualan_terbesar) {
            $total_faktur_kelipatan = Penjualan::countFaktur($request)->whereBetween('total', array($satu, $kelipatan))->first()->id_pesanan;

            $respons['kelipatan']    = $satu . " - " . $kelipatan;
            $respons['total_faktur'] = $total_faktur_kelipatan;

            array_push($array,$respons);

            $data_kelipatan = $request->kelipatan;
            $kelipatan += $data_kelipatan;
            $satu += $data_kelipatan;
        }

        return response()->json($array);
    }

    public function prosesLaporanBucketSizeOnline(Request $request, $dari_tanggal, $sampai_tanggal, $kelipatan)
    {
        $satu      = 1;
        $kelipatan = intval($request->kelipatan);

        $data_penjualan_pos       = Penjualan::select([DB::raw('MAX(total) as total')])->where('id_warung', Auth::user()->id_warung)->first();
        $total_penjualan_terbesar = $kelipatan + $data_penjualan_pos->total;

        while ($kelipatan <= $total_penjualan_terbesar) {
            $total_faktur_kelipatan = Penjualan::countFaktur($request)->whereBetween('total', array($satu, $kelipatan))->first()->id_pesanan;

            $respons['kelipatan'][]    = $satu . " - " . $kelipatan;
            $respons['total_faktur'][] = $total_faktur_kelipatan;
            $respons['color'][]        = $this->random_color();

            $data_kelipatan = $request->kelipatan;
            $kelipatan += $data_kelipatan;
            $satu += $data_kelipatan;
        }

        return response()->json($respons);
    }

    public function labelSheet($sheet, $row) 
    { 
        $sheet->row($row, [ 
            'Kelipatan', 
            'Jumlah Terjual', 
        ]); 
        return $sheet; 
    } 

                //DOWNLOAD EXCEL - LAPORAN PENJUALAN  
    public function downloadLaporanPos(Request $request, $dari_tanggal, $sampai_tanggal, $kelipatan) 
    { 
        $request['dari_tanggal']   = $dari_tanggal; 
        $request['sampai_tanggal'] = $sampai_tanggal; 
        $request['kelipatan'] = $kelipatan; 

        $satu      = 1; 
        $kelipatan = $request->kelipatan; 

        $data_penjualan_pos       = PenjualanPos::select([DB::raw('MAX(total) as total')])->where('warung_id', Auth::user()->id_warung)->first(); 
        $total_penjualan_terbesar = $satu + $data_penjualan_pos->total; 

        Excel::create('Laporan Bucket Size POS', function ($excel) use ($request, $satu,$data_penjualan_pos,$total_penjualan_terbesar,$kelipatan) { 
                        // Set property 
            $excel->sheet('Laporan Bucket Size POS', function ($sheet) use ($request,$satu,$data_penjualan_pos,$total_penjualan_terbesar,$kelipatan) { 
                $row = 1; 
                $sheet->row($row, [ 
                    'LAPORAN PENJUALAN POS', 
                ]); 

                $row   = 3; 
                $sheet = $this->labelSheet($sheet, $row); 

                while ($kelipatan <= $total_penjualan_terbesar) { 
                    $total_faktur_kelipatan = PenjualanPos::countFaktur($request)->whereBetween('total', array($satu, $kelipatan))->first()->no_faktur; 

                    $sheet->row(++$row, [ 
                       $satu . " - " . $kelipatan, 
                       $total_faktur_kelipatan, 
                   ]); 

                    $data_kelipatan = $request->kelipatan; 
                    $kelipatan += $data_kelipatan; 
                    $satu += $data_kelipatan; 
                } 


            }); 
        })->export('xls'); 

    } 

                    //DOWNLOAD EXCEL - LAPORAN PENJUALAN  
    public function downloadLaporanOnline(Request $request, $dari_tanggal, $sampai_tanggal, $kelipatan) 
    { 
        $request['dari_tanggal']   = $dari_tanggal; 
        $request['sampai_tanggal'] = $sampai_tanggal; 
        $request['kelipatan'] = $kelipatan; 

        $satu      = 1; 
        $kelipatan = $request->kelipatan; 

        $data_penjualan_pos       = Penjualan::select([DB::raw('MAX(total) as total')])->where('id_warung', Auth::user()->id_warung)->first(); 
        $total_penjualan_terbesar = $kelipatan + $data_penjualan_pos->total; 

        Excel::create('Laporan Bucket Size Online', function ($excel) use ($request, $satu,$data_penjualan_pos,$total_penjualan_terbesar,$kelipatan) { 
                        // Set property 
            $excel->sheet('Laporan Bucket Size Online', function ($sheet) use ($request,$satu,$data_penjualan_pos,$total_penjualan_terbesar,$kelipatan) { 
                $row = 1; 
                $sheet->row($row, [ 
                    'LAPORAN PENJUALAN ONLINE', 
                ]); 

                $row   = 3; 
                $sheet = $this->labelSheet($sheet, $row); 

                while ($kelipatan <= $total_penjualan_terbesar) { 
                    $total_faktur_kelipatan = Penjualan::countFaktur($request)->whereBetween('total', array($satu, $kelipatan))->first()->id_pesanan; 

                    $sheet->row(++$row, [ 
                       $satu . " - " . $kelipatan, 
                       $total_faktur_kelipatan, 
                   ]); 

                    $data_kelipatan = $request->kelipatan; 
                    $kelipatan += $data_kelipatan; 
                    $satu += $data_kelipatan; 
                } 


            }); 
        })->export('xls'); 

    }

    public function cetakLaporan(Request $request, $dari_tanggal, $sampai_tanggal, $kelipatan){
        //SETTING APLIKASI
        $setting_aplikasi = SettingAplikasi::select('tipe_aplikasi')->first();

        $request['dari_tanggal']   = $dari_tanggal; 
        $request['sampai_tanggal'] = $sampai_tanggal; 
        $request['kelipatan'] = $kelipatan; 


        $satu      = 1;
        $kelipatan = intval($request->kelipatan);

        $data_penjualan_pos       = PenjualanPos::select([DB::raw('MAX(total) as total')])->where('warung_id', Auth::user()->id_warung)->first();
        $total_penjualan_terbesar = $kelipatan + $data_penjualan_pos->total;

        $data_warung        = Warung::where('id', Auth::user()->id_warung)->first();

        return view('laporan.cetak_laporan_bucket_size_pos',
            [
                'request'                   => $request,
                'satu'                      => $satu,
                'kelipatan'                 => $kelipatan,
                'data_penjualan_pos'        => $data_penjualan_pos,
                'total_penjualan_terbesar'  => $total_penjualan_terbesar,
                'data_warung'               => $data_warung,
                'dari_tanggal'              => $dari_tanggal,
                'sampai_tanggal'            => $sampai_tanggal,
                'setting_aplikasi'          => $setting_aplikasi,
            ])->with(compact('html'));

    } 


    public function cetakLaporanOnline(Request $request, $dari_tanggal, $sampai_tanggal, $kelipatan){
        //SETTING APLIKASI
        $setting_aplikasi = SettingAplikasi::select('tipe_aplikasi')->first();

        $request['dari_tanggal']   = $dari_tanggal; 
        $request['sampai_tanggal'] = $sampai_tanggal; 
        $request['kelipatan'] = $kelipatan; 


        $satu      = 1;
        $kelipatan = intval($request->kelipatan);

        $data_penjualan_pos       = Penjualan::select([DB::raw('MAX(total) as total')])->where('id_warung', Auth::user()->id_warung)->first();
        $total_penjualan_terbesar = $kelipatan + $data_penjualan_pos->total;

        $data_warung        = Warung::where('id', Auth::user()->id_warung)->first();

        return view('laporan.cetak_laporan_bucket_size_online',
            [
                'request'                   => $request,
                'satu'                      => $satu,
                'kelipatan'                 => $kelipatan,
                'data_penjualan_pos'        => $data_penjualan_pos,
                'total_penjualan_terbesar'  => $total_penjualan_terbesar,
                'data_warung'               => $data_warung,
                'dari_tanggal'              => $dari_tanggal,
                'sampai_tanggal'            => $sampai_tanggal,
                'setting_aplikasi'          => $setting_aplikasi,
            ])->with(compact('html'));

    } 

    public function kirimPesan(Request $request){

              // // proses tambah user
        $this->validate($request, [
            'pelanggan'    => 'required',
            'kirim_pesan_via'   => 'required',
            'produk'  => 'required',
            'pesan' => 'required',
        ]);

        foreach ($request->pelanggan as $pelanggan) {

            foreach ($request->kirim_pesan_via as $kirim_pesan_via) {
                // return $kirim_pesan_via == 1 ?  $this->kirimSMS($pelanggan,$request->produk,$request->pesan) : $this->kirimEmail($pelanggan,$request->produk,$request->pesan);
                if ($kirim_pesan_via == 1) {
                    $this->kirimSMS($pelanggan,$request->produk,$request->pesan);
                }else{
                    $this->kirimEmail($pelanggan,$request->produk,$request->pesan);
                }   

            }
        }

    }

    public function kirimSMS($pelanggan,$produk,$pesan){

     $nomor_tujuan = User::select('no_telp')->where('id',$pelanggan)->first()->no_telp; 
     $userkey    = env('USERKEY');
     $passkey    = env('PASSKEY');

     if (env('STATUS_SMS') == 1) {
            $client = new Client(); //GuzzleHttp\Client
            $result = $client->get('https://reguler.zenziva.net/apps/smsapi.php?userkey=' . $userkey . '&passkey=' . $passkey . '&nohp=' . $nomor_tujuan . '&pesan=' . $pesan . '');
            echo $nomor_tujuan;
        }

    }

    public function kirimEmail($pelanggan,$produk,$pesan){

     $pelanggan = Customer::with('warung')->find($pelanggan);

     Mail::send('auth.emails.email_promo', compact('pelanggan','produk','pesan'), function ($message) use($pelanggan) {

        $message->from('verifikasi@andaglos.id', $pelanggan->warung->name);
        $message->to($pelanggan->email, $pelanggan->warung->name)->subject('Promo');

    });

     return $pelanggan->email;
 }

 public function testWA(){
   $phone_number = '+6285658780793'; // Your phone number including country code
   $type = "sms";
   $response = WhatsapiTool::requestCode($phone_number, $type);

   event(new EventSendWhatsapp());

}

}
