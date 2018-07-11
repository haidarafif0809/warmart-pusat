<?php
$settingFooter = \App\SettingFooter::where('warung_id', \App\SettingPembedaAplikasi::where('app_address', url('/'))->first()->warung_id)->first();
?>
@extends('layouts.app_pelanggan')
@section('content')

<style type="text/css">
.page-header.header-small {
  height: 35vh;
  min-height: 35vh;
}
.ecommerce-page .page-header .container {
  padding-top: 10vh;
}
h4 {
  @if(Agent::isMobile())
  font-size: 1.2em;
  line-height: 1.4em;
  margin: 20px 0 10px;
  @endif
}
.panel .panel-heading {
  background-color: transparent;
  border-bottom: 2px solid #ddd;
  padding: 5px 0px 5px 0px;
}
.buttonColor{
  background-color: #2ac326  
}
.validationProvinsi{
  display: none;
  color: red;
}
.validationKota{
  display: none;
  color: red;
}
.validationAlamat{
  display: none;
  color: red;
}
#formAlamat{
  display: none;
}
.modal {
  overflow-y:auto;
}
</style>
<?php
$setting_aplikasi = \App\SettingAplikasi::select('tipe_aplikasi')->first();
?>
@if (Agent::isMobile()) <!--JIKA DAKSES VIA HP/TAB-->

@if($setting_aplikasi->tipe_aplikasi == 0)
<div class="page-header header-small" data-parallax="true"" style="background-image: url('../image/background2.jpg');">
  @else
  <div class="page-header header-small buttonColor" data-parallax="true">
    @endif

    <div class="container">
      <div class="row">
        <div class="col-md-8 col-md-offset-2">
          <div class="brand">

            @if($setting_aplikasi->tipe_aplikasi == 0)
            <h3 class="title">PASAR MUSLIM INDONESIA</h3>
            @else
            <h3 class="title"> <?=$settingFooter->judul_warung;?> </h3>
            @endif

          </div>
        </div>
      </div>
    </div>
  </div>
  @else <!--JIKA DIAKSES VIA KOMPUTER-->

  @if($setting_aplikasi->tipe_aplikasi == 0)
  <div class="page-header header-filter header-small" data-parallax="true"" style="background-image: url('../image/background2.jpg');">
    @else
    <div class="page-header header-small buttonColor" data-parallax="true">
      @endif
      <div class="container">
        <div class="row">
          <div class="col-md-8 col-md-offset-2">
            <div class="brand">

              @if($setting_aplikasi->tipe_aplikasi == 0)
              <h1 class="title">PASAR MUSLIM INDONESIA</h1>
              <h4 class="title"> Segala Kemudahan Untuk Umat Muslim Berbelanja.</h4>
              @else
              <h1 class="title"><?=$settingFooter->judul_warung;?></h1>
              @endif

            </div>
          </div>
        </div>
      </div>
    </div>

    @endif


    <div class="main main-raised">
      <div class="container">
        @if (Agent::isMobile()) <!--JIKA DAKSES VIA HP/TAB-->
        <ul class="breadcrumb" style="font-size: 12px;margin-top: 10px; margin-bottom: 1px;">
          <li><a href="{{ url('/daftar-produk') }}"><b>Home</b></a></li>
          <li><a href="{{ url('/pesanan') }}"><b>Pesanan</b></a></li>
          <li class="active"><b>Detail Pesanan </b></li>
        </ul>
        @else <!--JIKA DIAKSES VIA KOMPUTER-->
        <ul class="breadcrumb" style="margin-top: 10px; margin-bottom: 1px;">
          <li><a href="{{ url('/daftar-produk') }}"><b>Home</b></a></li>
          <li><a href="{{ url('/pesanan') }}"><b>Pesanan</b></a></li>
          <li class="active"><b>Detail Pesanan</b> </li>
        </ul>
        @endif

        <div class="card-content">
          <h3 class="title text-center" style="margin-top: 1px; margin-bottom: 1px;">Detail Pesanan</h3>

          @if (Agent::isMobile()) <!--JIKA DAKSES VIA HP/TAB-->

          <div class="card" style="margin-bottom: 5px; margin-top: 1px;">
            <div class="card-content">
              <b class="card-title" style="margin-top: 1px; margin-bottom: 1px;">Pesanan #{{ $pesanan_pelanggan->id }}</b>
              <p style="margin-top: 1px; margin-bottom: 1px;">Dipesan {{ $pesanan_pelanggan->WaktuPesan }}</p>
              <hr style="margin-top: 1x; margin-bottom: 1px;">

              <b class="card-title" style="margin-top: 1px; margin-bottom: 1px;">Pengiriman</b>
              <div class="row">
                <div class="col-sm-6 col-xs-4">Kurir :</div>
                <div class="col-sm-6 col-xs-8"><p align="right">{{ $pesanan_pelanggan->kurir }}</p></div>
                <div class="col-sm-6 col-xs-4">Service :</div>
                <div class="col-sm-6 col-xs-8"><p align="right">  {{ $service }}</p></div>
                <div class="col-sm-6 col-xs-8">Waktu Pengiriman :</div>
                <div class="col-sm-6 col-xs-4"><p align="right">{{ $waktu_pengiriman }}</p></div>
              </div>
              <hr style="margin-top: 1x; margin-bottom: 1px;">

              @if($setting_aplikasi->tipe_aplikasi == 0)
              <p> <b class="card-title" style="margin-top: 1px; margin-bottom: 1px;">Warung: {{ $pesanan_pelanggan->warung->name }}</b></p>
              @else
              <p> <b class="card-title" style="margin-top: 1px; margin-bottom: 1px;">{{ $pesanan_pelanggan->warung->name }}</b></p>
              @endif
              <p style="margin-top: 1px; margin-bottom: 1px;">{{ $pesanan_pelanggan->warung->no_telpon }}</p>
              <p style="margin-top: 1px; margin-bottom: 1px;">{{ $lokasi_warung }}</p>
              <hr style="margin-top: 1x; margin-bottom: 1px;">

              <b class="card-title" style="margin-top: 0px; margin-bottom: 1px;">Alamat Pengiriman</b>
              <p style="margin-top: 1px; margin-bottom: 1px;">{{ $pesanan_pelanggan->nama_pemesan }}</p>
              <p style="margin-top: 1px; margin-bottom: 1px;">{{ $pesanan_pelanggan->alamat_pemesan }}</p>
              <p style="margin-top: 1px; margin-bottom: 1px;">{{ $pesanan_pelanggan->no_telp_pemesan }}</p>

              <hr style="margin-top: 1x; margin-bottom: 1px;">
              <p> <b class="card-title" style="margin-top: 1px; margin-bottom: 1px;">Status Pesanan: </b> {!! $status_pesanan !!}</p>
              @if($pesanan_pelanggan->konfirmasi_pesanan == 0)
              <span style="color:purple; font-size: 16;" id="btnBatal"><i class="fa fa-info-circle" aria-hidden="true"> <a href="#" style="font-size: 12px" data-id="{{$pesanan_pelanggan->id}}"></i> PEMBATALAN PESANAN </a> </span>
              @elseif($pesanan_pelanggan->konfirmasi_pesanan == 4)
              <span style="color:purple; font-size: 16;" id="btnLanjut"><i class="fa fa-info-circle" aria-hidden="true"> <a href="#" style="font-size: 12px" data-id="{{$pesanan_pelanggan->id}}"></i> LANJUTKAN PESANAN </a> </span>
              @endif
              <hr style="margin-top: 1x; margin-bottom: 1px;">
              <p> <b class="card-title" style="margin-top: 1px; margin-bottom: 1px;">Metode Pembayaran </b> </p><p>{!! $pesanan_pelanggan->metode_pembayaran !!}</p>
              <hr style="margin-top: 1x; margin-bottom: 1px;">
            </div>
          </div>

          @foreach($detail_pesanan_pelanggan as $detail_pesanan_pelanggans)
          <div class="card" style="margin-bottom: 1px; margin-top: 1px;">
            <div class="row">
              <div class="col-md-12">

                <div class="row">
                  <div class="col-xs-4" style="padding-right:0px">
                    <div class="img-container" style="margin:10px;">

                      @if($detail_pesanan_pelanggans->produk->foto != NULL)
                      <img src="{{Url('/foto_produk')}}/{{$detail_pesanan_pelanggans->produk->foto}}">
                      @else
                      <img src="{{Url('/image/foto_default.png')}}">
                      @endif

                    </div>
                  </div>

                  <div class="col-xs-8" style="padding-left:0px; padding-right:0px; padding-top:10px">
                    <b> <a href="{{ url('detail-produk/'.$detail_pesanan_pelanggans->id_produk.'') }}"> {{$detail_pesanan_pelanggans->NamaBarang}} </a></b>
                    <p style="font-size: 15px">  {{ number_format($detail_pesanan_pelanggans->produk->harga_jual,0,',','.') }} x {{ $detail_pesanan_pelanggans->jumlah_produk }} {{ $detail_pesanan_pelanggans->produk->satuan->nama_satuan }}</p>
                    <p style="font-weight: bold; margin-top:10px; font-size: 14px; color:red;">Rp. {{ number_format($detail_pesanan_pelanggans->produk->harga_jual * $detail_pesanan_pelanggans->jumlah_produk,0,',','.') }}</p>
                  </div>
                </div>

              </div>
            </div>

          </div>
          @endforeach


          <div class="card" style="margin-bottom: 5px; margin-top: 1px;">
            <div class="card-content">
              <div class="row">
                <p>
                  <b class="card-title" style="margin-top: 1px; margin-bottom: 1px;">Subtotal</b>
                  <b class="card-title text-danger pull-right" style="margin-top: 1px; margin-bottom: 1px;">Rp. {{ number_format($pesanan_pelanggan->subtotal - $pesanan_pelanggan->biaya_kirim,0,',','.') }}</b><br>
                </p>
                <p>
                  <b class="card-title" style="margin-top: 1px; margin-bottom: 1px;">Biaya Kirim</b>
                  <b class="card-title text-danger pull-right" style="margin-top: 1px; margin-bottom: 1px;">Rp. {{ number_format($pesanan_pelanggan->biaya_kirim,0,',','.') }}</b><br>
                </p>
                <p>
                  <b class="card-title" style="margin-top: 1px; margin-bottom: 1px;">Total</b>
                  <b class="card-title text-danger pull-right" style="margin-top: 1px; margin-bottom: 1px;">Rp. {{ number_format($pesanan_pelanggan->subtotal,0,',','.') }}</b>
                </p>
              </div>

            </div>
          </div>


          {{$pagination}}
          @else

          <div class="row">
            <div class="col-md-12">
              <div class="card" style="margin-bottom:5px;">
                <div class="card-content">
                  <div class="row">
                    <div class="col-md-2"><b>Order #{{ $pesanan_pelanggan->id }}</b></div>
                    <div class="col-md-3"><b>Waktu Pesan : {{ $pesanan_pelanggan->WaktuPesan }}</b></div>
                    <div class="col-md-3"><b>Total : Rp. {{ number_format($pesanan_pelanggan->subtotal,0,',','.') }}</b></div>
                    <div class="col-md-3"><b>Status : {!! $status_pesanan !!}</b></div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="row">

            <div class="col-md-7">
              <div class="card" style="margin-bottom: 1px;">
                <div class="card-header" style="margin-bottom: 1px;">

                  <div class="row" style="margin-bottom: 1px;">
                    <div class="col-md-5">  <h4 align="center" class="card-title" style="color: black;"> Produk</h4> </div>
                    <div class="col-md-2">  <h4 class="card-title" style="color: black; text-align: right"> Harga</h4> </div>
                    <div class="col-md-2">  <h4 class="card-title" style="color: black;"> Jumlah</h4> </div>
                    <div class="col-md-2">  <h4 class="card-title" style="color: black; padding-left: 0px;"> Subtotal</h4> </div>
                  </div><hr style="margin-top: 0px; margin-bottom: 0px;">
                </div>

                @foreach($detail_pesanan_pelanggan as $detail_pesanan_pelanggans)
                <div class="card-content" style="padding-left: 5px; padding-top: 1px; padding-bottom: 1px; padding-right: 1px;">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="row">

                        <div class="col-md-5">
                          <div class="row">
                            <div class="col-md-4" style="padding-left:0px; padding-right: 0px">
                              <div class="img-container"  style="padding-left: 5px; padding-top: 1px; padding-bottom: 1px; padding-right: 1px; width:75px;">
                                @if($detail_pesanan_pelanggans->produk->foto != NULL)
                                <img src="{{Url('/foto_produk')}}/{{$detail_pesanan_pelanggans->produk->foto}}">
                                @else
                                <img src="{{Url('/image/foto_default.png')}}">
                                @endif
                              </div>
                            </div>
                            <div class="col-md-8" style="padding-left:0px; padding-right: 0px">
                              <h6><a href="#"><b>{{$detail_pesanan_pelanggans->NamaBarang}} </b> </a> </h6>
                            </div>
                          </div>
                        </div>

                        <div class="col-md-2" style="padding-left:0px; padding-right: 0px; text-align:right;">
                          <h6 align="right"><b>Rp. {{number_format($detail_pesanan_pelanggans->produk->harga_jual,0,',','.') }}</b></h6>
                        </div>

                        <div class="col-md-2" style="padding-left:0px; padding-right: 0px; text-align: center;">
                          <h6><b>{{$detail_pesanan_pelanggans->jumlah_produk }} {{$detail_pesanan_pelanggans->produk->satuan->nama_satuan }}</b></h6>
                        </div>
                        <div class="col-md-2" style="padding-left:0px; padding-right: 0px; text-align:right;">
                          <h6 align="right"><b> Rp. {{number_format($detail_pesanan_pelanggans->produk->harga_jual * $detail_pesanan_pelanggans->jumlah_produk,0,',','.') }} </b></h6>
                        </div>
                      </div>
                    </div>
                  </div>
                </div><hr style="margin-top: 0px; margin-bottom: 0px;">
                @endforeach

              </div>

              {{$pagination}}

            </div>

            <div class="col-md-5">
              <div class="card">
                <div class="card-content">

                  @if($setting_aplikasi->tipe_aplikasi == 0)
                  <b class="card-title" style="margin-top: 1px; margin-bottom: 1px;">Warung</b>
                  @else
                  <b class="card-title" style="margin-top: 1px; margin-bottom: 1px;">Alamat Kami</b>
                  @endif
                  <p style="margin-top: 1px; margin-bottom: 1px;">{{ $pesanan_pelanggan->warung->name }}</p>
                  <p style="margin-top: 1px; margin-bottom: 1px;">{{ $pesanan_pelanggan->warung->no_telpon }}</p>
                  <p style="margin-top: 1px; margin-bottom: 1px;">{{ $lokasi_warung }}</p>
                  <hr style="margin-top: 1x; margin-bottom: 1px;">

                  <b class="card-title" style="margin-top: 1px; margin-bottom: 1px;">Alamat Pengiriman</b>
                  <p style="margin-top: 1px; margin-bottom: 1px;">{{ $pesanan_pelanggan->nama_pemesan }}</p>
                  <p style="margin-top: 1px; margin-bottom: 1px;">{{ $pesanan_pelanggan->no_telp_pemesan }}</p>
                  <p style="margin-top: 1px; margin-bottom: 1px;">{{ $pesanan_pelanggan->alamat_pemesan }}</p>

                  <hr style="margin-top: 1x; margin-bottom: 1px;">

                  <b class="card-title" style="margin-top: 1px; margin-bottom: 1px;">Subtotal</b>
                  <p class="text-danger pull-right" style="margin-top: 1px; margin-bottom: 1px;"><b>Rp. {{ number_format($pesanan_pelanggan->subtotal - $pesanan_pelanggan->biaya_kirim,0,',','.') }}</b></p>
                  <hr style="margin-top: 1x; margin-bottom: 1px;">
                  <b class="card-title" style="margin-top: 1px; margin-bottom: 1px;">Biaya Kirim</b>
                  <p class="text-danger pull-right" style="margin-top: 1px; margin-bottom: 1px;"><b>Rp. {{ number_format($pesanan_pelanggan->biaya_kirim,0,',','.') }}</b></p>
                  <hr style="margin-top: 1x; margin-bottom: 1px;">
                  <b class="card-title" style="margin-top: 1px; margin-bottom: 1px;">Total</b>
                  <p class="text-danger pull-right" style="margin-top: 1px; margin-bottom: 1px;"><b>Rp. {{ number_format($pesanan_pelanggan->subtotal,0,',','.') }}</b></p>
                  <hr style="margin-top: 1x; margin-bottom: 1px;">
                  @if($pesanan_pelanggan->konfirmasi_pesanan == 0)
                  <span style="color:purple; font-size: 16;" id="btnBatal"><i class="fa fa-info-circle" aria-hidden="true"> <a href="#" style="font-size: 12px" data-id="{{$pesanan_pelanggan->id}}"></i> PEMBATALAN PESANAN </a> </span>
                  @elseif($pesanan_pelanggan->konfirmasi_pesanan == 4)
                  <span style="color:purple; font-size: 16;" id="btnLanjut"><i class="fa fa-info-circle" aria-hidden="true"> <a href="#" style="font-size: 12px" data-id="{{$pesanan_pelanggan->id}}"></i> LANJUTKAN PESANAN </a> </span>
                  @endif
                </div>
              </div>

            </div>

            @endif

          </div>
        </div>
      </div>
      @endsection

      @section('scripts')
      <script type="text/javascript">
        $(document).on('click', '#btnBatal', function () {
          batalPesanan();
        });

        function batalPesanan(){
          swal({
            title: "Konfirmasi Batal!",
            text: "Yakin Ingin Membatalkan Pesanan Ini?",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Batal',
            confirmButtonText: 'Ya',
          }).then((result) => {
            if (result.value) {
              var urlBatalPesanan = window.location.origin + (window.location.pathname).replace("pesanan-detail/", "batal-pesanan-pelanggan/");
              window.location.href=urlBatalPesanan;
              swal({
                text :  "Pesanan Berhasil Dibatalkan!",
                showConfirmButton :  false,
                type: "success",
                timer: 10000,
                onOpen: () => {
                  swal.showLoading()
                }
              });
            }
          });
        }
      </script>
      <script type="text/javascript">
        $(document).on('click', '#btnLanjut', function () {
          lanjutPesanan();
        });

        function lanjutPesanan(){
          swal({
            title: "Konfirmasi Lanjut!",
            text: "Yakin Ingin Melanjutkan Pesanan Ini?",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Batal',
            confirmButtonText: 'Ya',
          }).then((result) => {
            if (result.value) {
              var urlLanjutPesanan = window.location.origin + (window.location.pathname).replace("pesanan-detail/", "lanjut-pesanan-pelanggan/");
              window.location.href=urlLanjutPesanan;
              swal({
                text :  "Pesanan Berhasil Dilanjutkan!",
                showConfirmButton :  false,
                type: "success",
                timer: 10000,
                onOpen: () => {
                  swal.showLoading()
                }
              });
            }
          })
        }
      </script>
      @endsection
