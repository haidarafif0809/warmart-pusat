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

<div class="main main-raised" style="background-color: #E5E5E5"> 
  <div class="container">
    <div class="card-content"> 
      <h3 class="title text-center">Keranjang Belanjaan</h3>
      <div class="row">
        <div class="card" id="card-ubah-profil"><br>
          <ul class="breadcrumb">
            <li><a href="{{ url('/daftar-produk') }}">Home</a></li>
            <li class="active">Keranjang Belanjaan</li>
          </ul>
        </div>
        @if($cek_belanjaan == 0)
        <div class="card">
          <div class="col-md-12">
            <center>
              <h3>Keranjang Belanjaan Anda Kosong,Silahkan Berbelanja.</h3>
              <a  href="{{ url('/daftar-produk') }}" type="button" class="btn btn-block" style="background-color: #f44336">Lanjut Belanja<i class="material-icons">keyboard_arrow_right</i></a>
            </center> 
          </div>
        </div>
        @else
        <div class="row">
          @if ($agent->isMobile()) <!--JIKA DAKSES VIA HP/TAB-->
          <div class="col-md-4"> 
            <table class="table table-shopping">
              <thead >
                <tr class="card" style="width: 725px;">
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
              <table class="table table-shopping">
                <thead >
                  <tr class="card" style="width: 725px;">
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
          </div>
          @endif 

          <div class="col-md-4">

            <div class="card">
              <div class="card-header card-header-text" data-background-color=''  style="background-color: #f44336">
                <h6 class="card-title">Rincian Pesanan</h6> 
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
            <button type="button" class="btn btn-round pull-right" style="background-color: #f44336">Lanjut Ke Pembayaran <i class="material-icons">keyboard_arrow_right</i></button>
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