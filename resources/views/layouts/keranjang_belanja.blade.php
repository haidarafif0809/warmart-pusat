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
                      <h3 class="title"> PASAR MUSLIM INDONESIA </h1>
                          <h4 class="title"> Segala Kemudahan Untuk Umat Muslim Berbelanja. </h4>
                          @else
                          <h3 class="title"> TOKO ONLINE DAN POS </h1>
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
                              <h4 class="title"> Segala Kemudahan Untuk Umat Muslim Berbelanja. </h4>
                              @else
                              <h1 class="title">TOKO ONLINE DAN POS</h3>
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
                                            <a class="btn btn-block" href="{{ url('/daftar-produk') }}" style="background-color: #01573e" type="button">
                                                Lanjut Belanja
                                                <i class="material-icons">
                                                    keyboard_arrow_right
                                                </i>
                                            </a>
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
                                            <div class="col-sm-3 col-xs-3">
                                                <p align="right">
                                                    Jumlah
                                                </p>
                                            </div>
                                            <div class="col-sm-3 col-xs-3">
                                                <p align="right">
                                                    Subtotal
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {!! $produk_belanjaan !!}
                                <div class="card" style="margin-bottom: 1px; margin-top: 1px;">
                                    <div class="col-md-12" style="color:red; font-weight: bold">
                                        <div class="col-md-6 col-xs-6">
                                            TOTAL
                                        </div>
                                        <div class="col-md-2 col-xs-2" style="padding-left:40px; padding-right:0px">
                                            {{ $jumlah_produk->total_produk }}
                                        </div>
                                        <div class="col-md-4 col-xs-4" style="padding-left:40px; padding-right:0px">
                                            {{ $subtotal }}
                                        </div>
                                    </div>
                                </div>
                                <center>
                                    <a class="btn btn-round" href="{{ url('/selesaikan-pemesanan') }}" style="background-color: #01573e">
                                        Lanjut Ke Pembayaran
                                        <i class="material-icons">
                                            keyboard_arrow_right
                                        </i>
                                    </a>
                                </center>
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
                                                        <td width="60%">
                                                            <b>
                                                                Total Produk
                                                            </b>
                                                        </td>
                                                        <td>
                                                            :
                                                        </td>
                                                        <td>
                                                            <b>
                                                                {{ $cek_belanjaan }}
                                                            </b>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td width="60%">
                                                            <b>
                                                                Subtotal
                                                            </b>
                                                        </td>
                                                        <td>
                                                            :
                                                        </td>
                                                        <td>
                                                            <b>
                                                                RP {{ $subtotal }}
                                                            </b>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </hr>
                                    </div>
                                </div>
                                <a class="btn btn-round" href="{{ url('/selesaikan-pemesanan') }}" style="background-color: #01573e">
                                    Lanjut Ke Pembayaran
                                    <i class="material-icons">
                                        keyboard_arrow_right
                                    </i>
                                </a>
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
