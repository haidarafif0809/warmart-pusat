<?php
$settingFooter = \App\SettingFooter::where('warung_id', \App\SettingPembedaAplikasi::where('app_address', url('/'))->first()->warung_id)->
first();
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

@if($setting_aplikasi->tipe_aplikasi == 0)
<div class="page-header header-filter header-small" data-parallax="true" style="background-image: url('image/background2.jpg');">
  @else
  <div class="page-header header-small buttonColor" data-parallax="true">
    @endif

    @if (Agent::isMobile())
    <div class="container">
      <div class="row">
        <div class="col-md-8 col-md-offset-2">
          <div class="brand">

            @if($setting_aplikasi->tipe_aplikasi == 0)
            <h1 class="title"> PASAR MUSLIM INDONESIA </h1>
            <h4 class="title"> Segala Kemudahan Untuk Umat Muslim Berbelanja. </h4>
            @else
            <br>
            <h3 class="title"> <?=$settingFooter->judul_warung;?> </h3>
            @endif

          </div>
        </div>
      </div>
    </div>
    @else
    <div class="container">
      <div class="row">
        <div class="col-md-8 col-md-offset-2">
          <div class="brand">

            @if($setting_aplikasi->tipe_aplikasi == 0)
            <h1 class="title"> PASAR MUSLIM INDONESIA </h1>
            <h4 class="title"> Segala Kemudahan Untuk Umat Muslim Berbelanja. </h4>
            @else
            <h1 class="title"><?=$settingFooter->judul_warung;?></h1>
            @endif

          </div>
        </div>
      </div>
    </div>
    @endif
  </div>

  <div class="main main-raised">

    <div class="container">


      <ul class="breadcrumb" style="margin-top: 10px; margin-bottom: 10px;">
        <li><a href="{{ url('/daftar-produk') }}"><b>Home</b></a></li>
        <li><a href="{{ url('/keranjang-belanja') }}"><b>Keranjang Belanja</b></a></li>
        <li class="active"><b>Pembayaran Transfer</b></li>
      </ul>
      <div class="card-content">
        <div class="card">
          <div class="card-header">
            <h6 class="card-title" style="color: black; padding-left: 10px ;"> Pembayaran via Transfer</h6> <hr>
          </div>

          <div class="card-content">
            <p class="text-center" style="display: none" id="batas_pembayaran">Batas Pembayaran :<b id="timer"></b></p>      
            <p class="text-center">Jumlah tagihan :</p>
            <h4 class="text-center" ><b rel="tooltip" data-placement="bottom" title="Transfer tepat hingga 3 digit terakhir" id="jumlah_tagihan">Rp. {{number_format($pesanan_pelanggan->subtotal,0,',','.')}}</b></h4>
            <br><br>
            <br>
            <p class="text-center" style="font-weight: bold">Pembayaran dapat dilakukan ke Rekening Bank {{strtoupper($bank->nama_bank)}} ({{$bank->no_rek}}) a/n {{$bank->atas_nama}}</p>
            <center>   <a  href="{{ url('/daftar-produk') }}" type="button" class="btn btn-block buttonColor">Lanjut Belanja<i class="material-icons">keyboard_arrow_right</i></a></center>

          </div>
        </div>



      </div>
    </div>
  </div> <!-- end-main-raised -->
  @endsection


  @section('scripts')
  <script type="text/javascript">
    $(document).ready(function(){
      var countDownDate = new Date("{{$batas_pembayaran}}").getTime();
      var x = setInterval(function() {
        var now = new Date().getTime();

        var distance = countDownDate - now;

        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

        document.getElementById("timer").innerHTML = hours + " jam "
        + minutes + " menit " + seconds + " detik ";
        $("#batas_pembayaran").show();
        $("#jumlah_tagihan").mouseover();

        if (distance < 0) {
          clearInterval(x);
          document.getElementById("timer").innerHTML = "Waktu Pembayaran Anda Habis, Karena Telah Melebihi Batas Waktu yang ditentukan";
        }
      }, 1000);
    });
  </script>

  @endsection
