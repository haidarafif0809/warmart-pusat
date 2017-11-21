@extends('layouts.app_pelanggan')
@section('content') 

@if (Agent::isMobile()) <!--JIKA DAKSES VIA HP/TAB-->

<div class="page-header header-small" data-parallax="true"" style="background-image: url('../image/background2.jpg');"> 
  <div class="container"> 
    <div class="row">
      <div class="col-md-8 col-md-offset-2">
        <div class="brand">
          <h3 class="title">PASAR MUSLIM INDONESIA</h3>
          <h6 class="title"> Segala Kemudahan Untuk Umat Muslim Berbelanja.</h6>
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
    <ul class="breadcrumb" style="font-size: 12px;margin-top: 10px">
      <li><a href="{{ url('/daftar-produk') }}">Home</a></li>
      <li><a href="{{ url('/pesanan') }}">Pesanan</a></li>
      <li class="active">Detail Pesanan </li>
    </ul>
    @else <!--JIKA DIAKSES VIA KOMPUTER-->
    <ul class="breadcrumb" style="margin-top: 10px">
      <li><a href="{{ url('/daftar-produk') }}">Home</a></li>
      <li><a href="{{ url('/pesanan') }}">Pesanan</a></li>
      <li class="active">Detail Pesanan </li>
    </ul>
    @endif 

    <div class="card-content"> 
      <h3 class="title text-center">Detail Pesanan</h3> 
      <div class="row">
        <div class="col-md-12"> 
          <div class="card">
            <div class="card-content">
              <div class="row">
                <div class="col-md-3">Order #{{ $pesanan_pelanggan->id }}</div>
                <div class="col-md-3">Di pesan pada {{ $pesanan_pelanggan->created_at }}</div>
                <div class="col-md-3">Total : RP {{ number_format($pesanan_pelanggan->subtotal,0,',','.') }}</div>
                <div class="col-md-3">Status : {!! $status_pesanan !!}</div>
              </div>  
            </div> 
          </div>  
        </div>
      </div>

      <div class="row">
        <div class="col-md-5"> 
          <div class="card"  data-background-color="rose">
            <div class="card-header card-header-text">
              <h6 class="card-title" style="color: black; padding-left: 10px">Alamat Pengirim</h6> <hr>
            </div>
            <h4 style="padding-left: 10px;"> {{ $pesanan_pelanggan->nama_pemesan }}</h4>
            <p style="padding-left: 10px;"> {{ $pesanan_pelanggan->alamat_pemesan }}</p>
            <p style="padding-left: 10px;"> {{ $pesanan_pelanggan->no_telp_pemesan }}</p>
          </div>
        </div>
        <div class="col-md-7"> 
          <div class="card"  data-background-color="rose">
            <div class="card-header card-header-text">
              <h6 class="card-title" style="color: black; padding-left: 10px">Rincian Pesanan</h6> <hr>
            </div>

            @if (Agent::isMobile()) <!--JIKA DAKSES VIA HP/TAB-->
            <div class="card-content table-responsive">  
              <div style="height: 123px; overflow-y: scroll;">
                <table class="table table-hover"> 
                  <tbody>     

                    @foreach($detail_pesanan_pelanggan as $detail_pesanan_pelanggans)
                    <tr style="margin-top:0px;margin-bottom: 0px;">
                      <td><a href="{{ url('detail-produk/'.$detail_pesanan_pelanggans->id_produk.'') }}">{{ $detail_pesanan_pelanggans->produk->nama_barang }}</a><br>Rp{{ number_format($detail_pesanan_pelanggans->produk->harga_jual,0,',','.') }}</td>
                      <td>x{{ $detail_pesanan_pelanggans->jumlah_produk }}</td>
                      <td></td> 
                    </tr>  
                    @endforeach

                  </tbody>
                </table>
              </div>
            </div>

            @else <!--JIKA DIAKSES VIA KOMPUTER-->
            <div class="card-content table-responsive"> 
              <table class="table">
                <thead> 
                  <td><b>PRODUK</b></td>
                  <td style="padding-left: 180px;"><b>JUMLAH</b></td>
                  <td style="padding-left: 150px;"><b>HARGA</b></td>  
                </thead> 
              </table>
              <div style="height: 123px; overflow-y: scroll;">
                <table class="table table-hover table-responsive table-bordered"> 
                  <tbody>     

                    @foreach($detail_pesanan_pelanggan as $detail_pesanan_pelanggans)
                    <tr style="margin-top:0px;margin-bottom: 0px;">
                      <td style="padding-left: 10px;"><a href="{{ url('detail-produk/'.$detail_pesanan_pelanggans->id_produk.'') }}">asdasdsa dsadh aks hdkasd{{ $detail_pesanan_pelanggans->produk->nama_barang }}</a></td>
                      <td style="padding-left: 100px;">{{ $detail_pesanan_pelanggans->jumlah_produk }}</td>
                      <td style="padding-left: 150px;">{{ number_format($detail_pesanan_pelanggans->produk->harga_jual,0,',','.') }}</td> 
                    </tr>  
                    @endforeach

                  </tbody>
                </table>
              </div>
            </div>
            @endif

            <div class="card-content table-responsive">  <hr>
              <table>
                <tbody>     
                  <tr><td width="40%"><h5><b>Total :</b></h5></td> <td> &nbsp;&nbsp;&nbsp;</td> <td><h5><b>RP {{ number_format($pesanan_pelanggan->subtotal,0,',','.') }}</b></h5></td></tr>
                </tbody>
              </table>
            </div>
          </div> 
        </div> 
      </div>

    </div>  
  </div>
</div> 
@endsection

@section('scripts')   
@endsection 