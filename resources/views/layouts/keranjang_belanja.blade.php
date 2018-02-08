@extends('layouts.app_pelanggan')
@section('content')
<?php
$settingFooter = \App\SettingFooter::select()->first();
?>
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
.card .card-content {
  padding: 0px 10px;
}
h6 {
  font-size: 1.15em;
  text-transform: uppercase;
  font-weight: 500;
}
.backgroundColor {
  background-color: #2ac326
}
.page-header.header-small {
  height: 35vh;
  min-height: 35vh;
}
.ecommerce-page .page-header .container {
  @if(Agent::isMobile())
  padding-top: 7vh;
  @else
  padding-top: 10vh;
  @endif
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
  @if($setting_aplikasi->tipe_aplikasi == "1") /*tipe-aplikasi == 1, aplikasi topos*/
  background-color: #2ac326;
  @else
  background-color: #01573e;
  @endif
}
</style>

@if($setting_aplikasi->tipe_aplikasi == 0)
<div class="page-header header-filter header-small" data-parallax="true" style="background-image: url('image/background2.jpg');">
  @else
  <div class="page-header header-small backgroundColor" data-parallax="true">
    @endif
    @if (Agent::isMobile())
    <div class="container">
      <div class="row">
        <div class="col-md-8 col-md-offset-2">
          <div class="brand">

            @if($setting_aplikasi->tipe_aplikasi == 0)
            <h3 class="title"> PASAR MUSLIM INDONESIA </h3>
            <h4 class="title"> Segala Kemudahan Untuk Umat Muslim Berbelanja. </h4>
            @else
            <h3 class="title"><?=$settingFooter->judul_warung;?></h3>
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
        <h1 class="title"> PASAR MUSLIM INDONESIA </h3>
          <h3 class="title"> Segala Kemudahan Untuk Umat Muslim Berbelanja. </h3>
          @else
          <h1 class="title"><?=$settingFooter->judul_warung;?> </h3>
              @endif

          </div>
      </div>
  </div>
</div>
@endif
</div>
<div class="main main-raised">
    @if ($agent->isMobile())
    <!--JIKA DAKSES VIA HP/TAB-->
    <div class="container" style="margin-left: 5px; margin-right: 5px;">
      @else
      <div class="container">
        @endif
        <div class="card-content">
          <h3 class="title text-center">
            Keranjang Belanjaan
        </h3>
        <div class="row">
            <ul class="breadcrumb" style="margin-top:10px ">
              <li>
                <a href="{{ url('/daftar-produk') }}">
                  Home
              </a>
          </li>
          <li class="active">
            Keranjang Belanja
        </li>
    </ul>
    @if($cek_belanjaan == 0)
    <div class="card">
      <div class="col-md-12">
        <center>
          <h3>
            Keranjang Belanjaan Anda Kosong, Silahkan Berbelanja.
        </h3>

        <a  href="{{ url('/daftar-produk') }}" type="button" class="btn btn-block buttonColor">Lanjut Belanja<i class="material-icons">keyboard_arrow_right</i></a>

    </center>
</div>
</div>
@else
@if ($agent->isMobile())
<!--JIKA DAKSES VIA HP/TAB-->
<div class="card" style="margin-bottom: 1px; margin-top: 1px;">
  <div class="row">
    <div class="col-md-12">
      <div class="col-sm-6 col-xs-6">
        Pesanan Saya
    </div>
</div>
</div>
</div>
{!! $produk_belanjaan !!}


@if($setting_aplikasi->tipe_aplikasi == 0)
<a class="btn btn-round" href="{{ url('/daftar-produk') }}" style="background-color: #01573e">
  Lanjut Belanja
</a>
<a class="btn btn-round" href="{{ url('/selesaikan-pemesanan') }}" style="background-color: #01573e">
  Pembayaran
  <i class="material-icons">
    keyboard_arrow_right
</i>
</a>
@else
<a class="btn btn-round backgroundColor" href="{{ url('/daftar-produk') }}" >
  Lanjut Belanja
</a>
<a class="btn btn-round backgroundColor" href="{{ url('/selesaikan-pemesanan') }}" >
  Pembayaran
  <i class="material-icons">
    keyboard_arrow_right
</i>
</a>
@endif
@else
<div class="row">
  <div class="col-md-8">
    <div class="card" style="margin-bottom: 5px; padding-bottom: 15px;">
      <div class="card-header">
        <div class="row">
          <div class="col-md-3">
            <h4 align="center" class="card-title" style="color: black;">
              Produk
          </h4>
      </div>
      <div class="col-md-3">
        <h4 class="card-title" style="color: black; text-align: right">
          Harga
      </h4>
  </div>
  <div class="col-md-3">
    <h4 class="card-title" style="color: black;">
      Jumlah
  </h4>
</div>
<div class="col-md-3">
    <h4 class="card-title" style="color: black; padding-left: 25px;">
      Subtotal
  </h4>
</div>
</div><hr style="margin-top: 0px; margin-bottom: 0px;">
</div>
{!! $produk_belanjaan !!}
</div>
</div>
<div class="col-md-4">
    <div class="card" style="margin-bottom: 5px; padding-bottom: 15px;">
      <div class="card-header">
        <h6 class="card-title" style="color: black; padding-left: 10px">
          Rincian Pesanan
      </h6>
      <hr>
  </hr>
</div>
<div class="card-content table-responsive">
  <table>
    <tbody>
      <tr>
        <td width="60%"><b>Total Item</b></td>
        <td>:</td>
        <td>&nbsp;<b id="totalProduk" data-totalProduk="{{ $cek_belanjaan }}">{{ $cek_belanjaan }}</b>
        </td>
    </tr>
    <tr>
        <td width="60%"><b>Subtotal</b></td>
        <td>:</td>
        <td>&nbsp;<b id="subtotal" data-subtotal="{{$subtotal}}">Rp. {{ $subtotal }}<b></td>
        </tr>
    </tbody>
</table>
</hr>
</div>
</div>

@if($setting_aplikasi->tipe_aplikasi == 0)
<a class="btn btn-round" href="{{ url('/daftar-produk') }}" style="background-color: #01573e">
  Lanjut Belanja
  <i class="material-icons">
    shopping_cart
</i>
</a>
<a class="btn btn-round" href="{{ url('/selesaikan-pemesanan') }}" style="background-color: #01573e">
  Pembayaran
  <i class="material-icons">
    keyboard_arrow_right
</i>
</a>
@else
<a class="btn btn-round backgroundColor" href="{{ url('/daftar-produk') }}">
  Lanjut Belanja
  <i class="material-icons">
    shopping_cart
</i>
</a>
<a class="btn btn-round backgroundColor" href="{{ url('/selesaikan-pemesanan') }}">
  Pembayaran
  <i class="material-icons">
    keyboard_arrow_right
</i>
</a>
@endif
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
  });

});

    $(document).on('click', '.tambahProduk', function () { 
      var id = $(this).attr("data-id"); 
      var jumlah_produk = $("#jumlahProdukKeranjang-"+id).text(); 
      var hargaProdukKeranjang = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#hargaProdukKeranjang-"+id).text())))); 
      var subtotalProdukKeranjang = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#subtotalProdukKeranjang-"+id).attr("data-subtotal"))))); 
      var subtotal = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#subtotal").attr("data-subtotal"))))); 
      
      var tambahProduk = parseInt(jumlah_produk) + 1; 
      var tambahSubtotal = parseInt(subtotalProdukKeranjang) + parseInt(hargaProdukKeranjang); 
      var tambahSubtotalKesuluruhan = parseInt(hargaProdukKeranjang) + parseInt(subtotal); 
      
      $("#subtotalProdukKeranjang-"+id).addClass('spinner'); 
      $("#subtotalProdukKeranjang-"+id).text('');
      $("#subtotal").addClass('spinner'); 
      $("#subtotal").text(''); 
      
      $.get('{{ Url("keranjang-belanja/tambah-jumlah-produk-keranjang-belanja") }}',{'_token': $('meta[name=csrf-token]').attr('content'),id:id}, function(resp){  

        if (resp.respons == 0) {
            displayHasil(id,jumlah_produk,subtotalProdukKeranjang,subtotal);
            alertStokTidakCukup();
        }else{
            displayHasil(id,tambahProduk,tambahSubtotal,tambahSubtotalKesuluruhan);
        }
    }); 

      function displayHasil(id,tambahProduk,tambahSubtotal,tambahSubtotalKesuluruhan){

        var tambahSubtotal = parseInt(tambahSubtotal);
        var tambahSubtotalKesuluruhan = parseInt(tambahSubtotalKesuluruhan);

        $("#subtotalProdukKeranjang-"+id).removeClass('spinner'); 
        $("#subtotal").removeClass('spinner'); 
        $("#jumlahProdukKeranjang-"+id).text(tambahProduk); 
        $("#subtotalProdukKeranjang-"+id).text(tambahSubtotal.format(0, 3, '.', ',')); 
        $("#subtotalProdukKeranjang-"+id).attr("data-subtotal",tambahSubtotal);
        $("#subtotal").text("Rp. "+tambahSubtotalKesuluruhan.format(0, 3, '.', ',')); 
        $("#subtotal").attr("data-subtotal",tambahSubtotalKesuluruhan); 
    }

    function alertStokTidakCukup(){
     swal({
        text: 'Stok Produk Ini Sudah Habis!'
    });
 }


}); 



</script>
@endsection
