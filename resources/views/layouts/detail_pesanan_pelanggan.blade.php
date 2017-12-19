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

<div class="page-header header-small" data-parallax="true"" style="background-image: url('../image/background2.jpg');">
  <div class="container">
    <div class="row">
      <div class="col-md-8 col-md-offset-2">
        <div class="brand">
          <h3 class="title">PASAR MUSLIM INDONESIA</h3>
        </div>
      </div>
    </div>
  </div>
</div>
@else <!--JIKA DIAKSES VIA KOMPUTER-->
<div class="page-header header-filter header-small" data-parallax="true" style="background-image: url('../image/background2.jpg');">
  <div class="container">
    <div class="row">
      <div class="col-md-8 col-md-offset-2">
        <div class="brand">
          <h1 class="title">PASAR MUSLIM INDONESIA</h1>
          <h4 class="title"> Segala Kemudahan Untuk Umat Muslim Berbelanja.</h4>
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
          <p class="text-danger"><b>Total: Rp. {{ number_format($pesanan_pelanggan->subtotal,0,',','.') }}</b></p>
          <p style="margin-top: 1px; margin-bottom: 1px;">Dipesan pada {{ $pesanan_pelanggan->WaktuPesan }}</p>
          <hr style="margin-top: 1x; margin-bottom: 1px;">

          <p> <b class="card-title" style="margin-top: 1px; margin-bottom: 1px;">Warung: {{ $pesanan_pelanggan->warung->name }}</b></p>
          <p style="margin-top: 1px; margin-bottom: 1px;">{{ $pesanan_pelanggan->warung->no_telpon }}</p>
          <p style="margin-top: 1px; margin-bottom: 1px;">{{ $lokasi_warung }}</p>
          <hr style="margin-top: 1x; margin-bottom: 1px;">

          <p> <b class="card-title" style="margin-top: 1px; margin-bottom: 1px;">Status Pesanan: </b> {!! $status_pesanan !!}</p>
          <hr style="margin-top: 1x; margin-bottom: 1px;">
        </div>
      </div>

      @foreach($detail_pesanan_pelanggan as $detail_pesanan_pelanggans)
      <div class="card" style="margin-bottom: 1px; margin-top: 1px;">
        <div class="row">
          <div class="col-md-12">

            <div class="row">
              <div class="col-xs-4">
                <div class="img-container" style="margin-bottom:10px;margin-top: 10px; margin-left: 10px; margin-right: 10px;">

                  @if($detail_pesanan_pelanggans->produk->foto != NULL)
                  <img src="../foto_produk/{{$detail_pesanan_pelanggans->produk->foto}}">
                  @else
                  <img src="../image/foto_default.png">
                  @endif

                </div>

              </div>

              <div class="col-xs-8">
                <p style="margin-bottom:1px;margin-top: 1px;"> <a href="{{ url('detail-produk/'.$detail_pesanan_pelanggans->id_produk.'') }}"><b>{{$detail_pesanan_pelanggans->NamaBarang}} </b></a></p>
                <p style="margin-bottom:1px;margin-top: 1px;"><b>Rp. {{ number_format($detail_pesanan_pelanggans->produk->harga_jual,0,',','.') }}</b></p>
                <b>{{ $detail_pesanan_pelanggans->jumlah_produk }} {{ $detail_pesanan_pelanggans->produk->satuan->nama_satuan }}</b>
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
              <b class="card-title" style="margin-top: 1px; margin-bottom: 1px;">Total</b>
              <b class="card-title text-danger pull-right" style="margin-top: 1px; margin-bottom: 1px;">Rp. {{ number_format($pesanan_pelanggan->subtotal,0,',','.') }}</b>
            </p>
            <b class="card-title" style="margin-top: 0px; margin-bottom: 1px;">Alamat Pengiriman</b>
            <p style="margin-top: 1px; margin-bottom: 1px;">{{ $pesanan_pelanggan->nama_pemesan }}</p>
            <p style="margin-top: 1px; margin-bottom: 1px;">{{ $pesanan_pelanggan->alamat_pemesan }}</p>
            <p style="margin-top: 1px; margin-bottom: 1px;">{{ $pesanan_pelanggan->no_telp_pemesan }}</p>
          </div>

        </div>
      </div>


      {{$pagination}}
      @else

      <div class="row">
        <div class="col-md-12">
          <div class="card" style="margin-bottom:1px;">
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
                <div class="col-md-6">  <h4 class="card-title" align="center" style="color: black;"> Produk</h4> </div>
                <div class="col-md-3">  <h4 class="card-title" style="color: black;"> Harga</h4> </div>
                <div class="col-md-3">  <h4 class="card-title" style="color: black;"> Jumlah</h4> </div>
              </div><hr  style="margin-bottom: 1px;">
            </div>

            @foreach($detail_pesanan_pelanggan as $detail_pesanan_pelanggans)
            <div class="card-content" style="padding-left: 5px; padding-top: 1px; padding-bottom: 1px; padding-right: 1px;">
              <div class="row">
                <div class="col-md-12">
                  <div class="row">

                    <div class="col-md-6">
                      <div class="row">
                        <div class="col-sm-4">
                          <div class="img-container"  style="padding-left: 5px; padding-top: 1px; padding-bottom: 1px; padding-right: 1px; width:100px;">

                            @if($detail_pesanan_pelanggans->produk->foto != NULL)
                            <img src="../foto_produk/{{$detail_pesanan_pelanggans->produk->foto}}">
                            @else
                            <img src="../image/foto_default.png">
                            @endif
                          </div>
                        </div>
                        <div class="col-sm-8">
                          <h5><a href="#"><b>{{$detail_pesanan_pelanggans->NamaBarang}} </b> </a> </h5>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-3">

                      <h5><b>Rp. {{number_format($detail_pesanan_pelanggans->produk->harga_jual,0,',','.') }}</b></h5>
                    </div>

                    <div class="col-md-3">

                      <h5><b>{{$detail_pesanan_pelanggans->jumlah_produk }} {{$detail_pesanan_pelanggans->produk->satuan->nama_satuan }}</b></h5>

                    </div>
                  </div>
                </div>
              </div>
            </div><hr>
            @endforeach

          </div>

          {{$pagination}}

        </div>

        <div class="col-md-5">
          <div class="card">
            <div class="card-content">

             <b class="card-title" style="margin-top: 1px; margin-bottom: 1px;">Warung</b>
             <p style="margin-top: 1px; margin-bottom: 1px;">{{ $pesanan_pelanggan->warung->name }}</p>
             <p style="margin-top: 1px; margin-bottom: 1px;">{{ $pesanan_pelanggan->warung->no_telpon }}</p>
             <p style="margin-top: 1px; margin-bottom: 1px;">{{ $lokasi_warung }}</p>
             <hr style="margin-top: 1x; margin-bottom: 1px;">

             <b class="card-title" style="margin-top: 1px; margin-bottom: 1px;">Alamat Pengiriman</b>
             <p style="margin-top: 1px; margin-bottom: 1px;">{{ $pesanan_pelanggan->nama_pemesan }}</p>
             <p style="margin-top: 1px; margin-bottom: 1px;">{{ $pesanan_pelanggan->no_telp_pemesan }}</p>
             <p style="margin-top: 1px; margin-bottom: 1px;">{{ $pesanan_pelanggan->alamat_pemesan }}</p>

             <hr style="margin-top: 1x; margin-bottom: 1px;">

             <b class="card-title" style="margin-top: 1px; margin-bottom: 1px;">Total</b>
             <p class="text-danger" style="margin-top: 1px; margin-bottom: 1px;"><b>Rp. {{ number_format($pesanan_pelanggan->subtotal,0,',','.') }}</b></p>

             <hr style="margin-top: 1x; margin-bottom: 1px;">
           </div>
         </div>

       </div>

       @endif

     </div>
   </div>
 </div>
 @endsection

 @section('scripts')
 @endsection
