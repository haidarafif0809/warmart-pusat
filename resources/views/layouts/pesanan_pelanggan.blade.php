<?php
$settingFooter = \App\SettingFooter::select()->
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
</style>
@if (Agent::isMobile()) <!--JIKA DAKSES VIA HP/TAB-->

<div class="page-header header-small" data-parallax="true"" style="background-image: url('./image/background2.jpg');">
  <div class="container">
    <div class="row">
      <div class="col-md-8 col-md-offset-2">
        <div class="brand">

          @if($setting_aplikasi->tipe_aplikasi == 0)
          <h3 class="title">PASAR MUSLIM INDONESIA</h3>
          @else
          <h3 class="title"><?=$settingFooter->judul_warung;?></h3>
          @endif

        </div>
      </div>
    </div>
  </div>
</div>
@else <!--JIKA DIAKSES VIA KOMPUTER-->

@if($setting_aplikasi->tipe_aplikasi == 0)
<div class="page-header header-filter header-small" data-parallax="true" style="background-image: url('./image/background2.jpg');">
  @else
  <div class="page-header header-small" data-parallax="true" style="background-color: #2ac326">
    @endif

    <div class="container">
      <div class="row">
        <div class="col-md-8 col-md-offset-2">
          <div class="brand">

            @if($setting_aplikasi->tipe_aplikasi == 0)
            <h1 class="title">PASAR MUSLIM INDONESIA</h1>
            <h3 class="title"> Segala Kemudahan Untuk Umat Muslim Berbelanja.</h3>
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
      <ul class="breadcrumb" style="margin-top: 10px">
        <li><a href="{{ url('/daftar-produk') }}">Home</a></li>
        <li class="active">Pesanan</li>
      </ul>
      <div class="card-content">
        <h3 class="title text-center">Pesanan</h3>
        @if($cek_pesanan == 0)
        <div class="card">

          <div class="col-md-12">

            <center>
              <h3>Pesanan Anda Kosong, Silahkan Berbelanja.</h3>
              @if($setting_aplikasi->tipe_aplikasi == 0)
              <a  href="{{ url('/daftar-produk') }}" type="button" class="btn btn-block" style="background-color: #01573e">Lanjut Belanja<i class="material-icons">keyboard_arrow_right</i></a>
              @else
              <a  href="{{ url('/daftar-produk') }}" type="button" class="btn btn-block" style="background-color: #2ac326">Lanjut Belanja<i class="material-icons">keyboard_arrow_right</i></a>
              @endif
            </center>
          </div>
        </div>
        @else
        @if (Agent::isMobile()) <!--JIKA DAKSES VIA HP/TAB-->
        {!! $produk_pesanan_mobile !!}
        {{$pagination_pesanan }}
        @else <!--JIKA DIAKSES VIA KOMPUTER-->
        <div class="card">
          <div class="col-md-12">

            <div class="content">
              <div class="container-fluid">
                <div class="row">
                  <div class="col-md-12">
                    <div class="card card-plain">
                      <div class="card-header card-header-icon" data-background-color="rose">
                      </div>
                      <div class="card-content">
                        <table class="table table-hover table-responsive">
                          <thead>
                            <th>Pesanan</th>
                            <th>Waktu Pesan </th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Warung</th>
                          </thead>
                          <tbody>
                            {!! $produk_pesanan_komputer !!}
                          </tbody>
                        </table>
                        {{$pagination_pesanan }}
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        @endif
        @endif
      </div> <!-- end-main-raised -->
    </div>
  </div>
  @endsection

  @section('scripts')
  @endsection
