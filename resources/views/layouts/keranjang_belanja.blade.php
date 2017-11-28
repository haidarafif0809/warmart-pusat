@extends('layouts.app_pelanggan')
@section('content') 

<style type="text/css">
.flexFont {
  @if(Agent::isMobile())
  height:3em;
  @else  
  height:2em;
  @endif
  padding:1%;
  margin: 10px;

}

.smaller {
  font-size: 0.7em;
  background-color:red;
  width: 10em;
}

</style>

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

<div class="main main-raised"> 
 @if ($agent->isMobile()) <!--JIKA DAKSES VIA HP/TAB-->
 <div class="container" style="margin-left: 5px; margin-right: 5px;">
  @else

  <div class="container">
    @endif
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
      @if ($agent->isMobile()) <!--JIKA DAKSES VIA HP/TAB-->
      <div class="card" style="margin-bottom: 1px; margin-top: 1px;">
        <div class="row">
          <div class="col-md-12">
            <div class="col-sm-6 col-xs-6">Pesanan Saya</div>

            <div class="col-sm-6 col-xs-6"><p align="right">Jumlah</p></div>
          </div>
        </div>
      </div>

      {!! $produk_belanjaan !!}

      <div class="card" style="margin-bottom: 1px; margin-top: 1px;">

        <div class="col-md-12">
          <div class="col-sm-6 col-xs-6">Total Produk </div>

          <div class="col-sm-6 col-xs-6"><p align="right" class="text-danger"><b>{{ $jumlah_produk->total_produk }}</b></p></div>
        </div>

        <div class="col-md-12">
          <div class="col-sm-6 col-xs-6">Subtotal</div>

          <div class="col-sm-6 col-xs-6"><p align="right" class="text-danger"><b>Rp. {{ $subtotal }}</b></p></div>
        </div>
      </div>

      <center><a href="{{ url('/selesaikan-pemesanan') }}" class="btn btn-round" style="background-color: #01573e">Lanjut Ke Pembayaran <i class="material-icons">keyboard_arrow_right</i></a></center>

      @else

      <div class="row">
        <div class="col-md-8"> 

          <div class="card">
            <div class="card-header">

              <div class="row">
                <div class="col-md-6">  <h4 class="card-title" style="color: black;"> Produk</h4> </div>
                <div class="col-md-3">  <h4 class="card-title" style="color: black;"> Harga</h4> </div>
                <div class="col-md-3">  <h4 class="card-title" style="color: black;"> Jumlah</h4> </div>
              </div><hr>
            </div>
            {!! $produk_belanjaan !!}
          </div>

        </div> 

        <div class="col-md-4">

          <div class="card" style="margin-bottom: 1px;">
            <div class="card-header">
              <h6 class="card-title" style="color: black; padding-left: 10px"> Rincian Pesanan</h6> <hr>
            </div>
            <div class="card-content table-responsive"> 
              <table>
                <tbody>      
                  <tr><td width="50%"><b>Total Produk</b> </td> <td>: &nbsp;&nbsp;&nbsp;</td> <td> <b>{{ $jumlah_produk->total_produk }}</b></td></tr>
                  <tr><td width="50%"><b>Subtotal</b> </td> <td>: &nbsp;&nbsp;&nbsp;</td> <td><b>Rp. {{ $subtotal }}</b></td></tr>
                </tbody>
              </table><hr>
              <table>
                <tbody>     
                  <tr><td width="40%"><h5><b>Total :</b></h5></td> <td> &nbsp;&nbsp;&nbsp;</td> <td><h5><b>RP {{ $subtotal }}</b></h5></td></tr>
                </tbody>
              </table>
            </div>
          </div>
          <a href="{{ url('/selesaikan-pemesanan') }}" type="button" class="btn btn-round pull-left"  style="background-color: #01573e">Lanjut Ke Pembayaran <i class="material-icons">keyboard_arrow_right</i></a>
        </div>


        @endif

      </div>
    </div>
    @endif
  </div>
</div>
</div>  
</div>
@endsection

@section('scripts') 
<script type="text/javascript"> 
  $(document).on('click', '#btnHapusProduk', function () { 
    var id = $(this).attr("data-id");
    var nama = $(this).attr("data-nama");

    swal({
      html: "Anda Yakin Ingin Menghapus Produk <b>"+nama+"</b> Dari Keranjang Belanja ?",
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Ya',
      cancelButtonText: 'Batal'
    }).then((result) => {
      if (result.value) {
        var url_hapus_produk_keranjang_belanja = window.location.origin + (window.location.pathname).replace("keranjang-belanja", "keranjang-belanja/hapus-produk-keranjang-belanja/"+id);
        window.location.href=url_hapus_produk_keranjang_belanja;
        swal({
          html :  "Produk <b>"+nama+"</b> Berhasil Dihapus Dari Keranjang Belanjaan", 
          showConfirmButton :  false,
          type: "success",    
          timer: 10000,
          onOpen: () => {
            swal.showLoading()
          }
        });

      }
    })

  });

</script>
@endsection 