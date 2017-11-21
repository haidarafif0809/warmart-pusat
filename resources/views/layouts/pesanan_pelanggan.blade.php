@extends('layouts.app_pelanggan')
@section('content') 

@if (Agent::isMobile()) <!--JIKA DAKSES VIA HP/TAB-->

<div class="page-header header-small" data-parallax="true"" style="background-image: url('./image/background2.jpg');"> 
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
<div class="page-header header-filter header-small" data-parallax="true" style="background-image: url('./image/background2.jpg');"> 
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
            <a  href="{{ url('/daftar-produk') }}" type="button" class="btn btn-block" style="background-color: #01573e">Lanjut Belanja<i class="material-icons">keyboard_arrow_right</i></a>
          </center> 
        </div>
      </div>
      @else
      @if (Agent::isMobile()) <!--JIKA DAKSES VIA HP/TAB-->
      {!! $produk_pesanan_mobile !!}

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
                          <th>Di pesan pada </th>
                          <th>Total</th>
                          <th>Status</th>
                        </thead>
                        <tbody>
                          {!! $produk_pesanan_komputer !!}
                        </tbody>
                      </table>
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