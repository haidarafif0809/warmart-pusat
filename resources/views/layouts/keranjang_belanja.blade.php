@extends('layouts.app_pelanggan')
@section('content') 

<div class="page-header header-filter header-small" data-parallax="true" style="background-image: url('image/background2.jpg');">

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

<div class="main main-raised" > 
  <div class="container">
    <div class="card-content"> 
      <h3 class="title text-center">Keranjang Belanjaan</h3>
      <div class="row">
       <ul class="breadcrumb" style="margin-top:10px ">
        <li><a href="{{ url('/daftar-produk') }}">Home</a></li>
        <li class="active">Keranjang Belanja</li>
      </ul>

      @if($cek_belanjaan == 0)
      <div class="card">

        <div class="col-md-12">

          <center>
            <h3>Keranjang Belanjaan Anda Kosong, Silahkan Berbelanja.</h3>
            <a  href="{{ url('/daftar-produk') }}" type="button" class="btn btn-block" style="background-color: #01573e">Lanjut Belanja<i class="material-icons">keyboard_arrow_right</i></a>
          </center> 
        </div>
      </div>
      @else
      <div class="row">
        @if ($agent->isMobile()) <!--JIKA DAKSES VIA HP/TAB-->
        <div class="col-md-4"> 
          <table class="table table-shopping">
            <thead >
              <tr class="card" style="width: 725px;" >
                <th class="text-center"></th>
                <th style="padding-left: 20%"><b>Produk</b></th>   
                <th style="padding-left: 125%"><b>Harga Produk</b></th> 
                <th style="padding-left: 135%"><b>Kuantitas</b></th> 
              </tr>
            </thead>
            <tbody>        
              {!! $produk_belanjaan !!}
            </tbody>
          </table> 
        </div>
        @else
        <div class="col-md-8"> 
          <div class="table-responsive">
            <div class="card"  style="width: 725px;" >
              <div class="card-header card-header-text"> 
                <h6>&nbsp;&nbsp;&nbsp;<b>Produk</b> <b style="padding-left: 315px">Harga Produk</b> <b style="padding-left: 50px">Jumlah</b></h6> 
              </div>
            </div>
            <table class="table table-shopping"> 
              <tbody>        
                {!! $produk_belanjaan !!}
              </tbody>
            </table> 
          </div>
        </div>
        @endif 

        <div class="col-md-4">

          <div class="card">
            <div class="card-header card-header-text">
              <h6 class="card-title" style="color: black; padding-left: 10px"> Rincian Pesanan</h6> <hr>
            </div>
            <div class="card-content table-responsive"> 
              <table>
                <tbody>      
                  <tr><td width="50%">Jumlah Produk </td> <td>: &nbsp;&nbsp;&nbsp;</td> <td>{{ $jumlah_produk->total_produk }}</td></tr>
                  <tr><td width="50%">Subtotal </td> <td>: &nbsp;&nbsp;&nbsp;</td> <td>Rp. {{ $subtotal }}</td></tr>
                </tbody>
              </table><hr>
              <table>
                <tbody>     
                  <tr><td width="40%"><h5><b>Total :</b></h5></td> <td> &nbsp;&nbsp;&nbsp;</td> <td><h5><b>RP {{ $subtotal }}</b></h5></td></tr>
                </tbody>
              </table>
            </div>
          </div>
          <a href="{{ url('/selesaikan-pemesanan') }}" type="button" class="btn btn-round pull-right"  style="background-color: #01573e">Lanjut Ke Pembayaran <i class="material-icons">keyboard_arrow_right</i></a>
        </div>
      </div>
    </div>
    @endif
  </div>
</div>
</div>  
</div> <!-- end-main-raised -->
@endsection

@section('scripts') 
<script type="text/javascript"> 
 $(document).on('click', '#btnHapusProduk', function(){
  swal({
    title: "Produk Berhasil Di Hapus!", 
    showConfirmButton :  false,
    type: "success",
  });
</script>
@endsection 