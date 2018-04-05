<?php
$settingFooter = \App\SettingFooter::select()->first();
$foto_logo = \App\UserWarung::select()->where('tipe_user',4)->orderBy('id', 'asc')->limit(1)->first();


$jasa_pengirimans = \App\SettingJasaPengiriman::select('logo_jasa')->where('tampil_jasa_pengiriman', 1)->get();
$bank_transfers = \App\SettingTransferBank::select('logo_bank')->where('tampil_bank', 1)->get();
?>
<!DOCTYPE doctype html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  @if($setting_aplikasi->tipe_aplikasi == 0)
  <title>
    War-Mart.id
  </title>
  @else
  <title>
    {{$judul_warung = \App\SettingFooter::select()->first()->judul_warung}}
  </title>
  @endif

  <meta charset="utf-8"/>
  @if(\App\SettingAplikasi::select('tipe_aplikasi')->first()->tipe_aplikasi == 0)
  <link href="{{ asset('img/favicon.png') }}" rel="apple-touch-icon" sizes="76x76"/>
  <link href="{{ asset('img/favicon.png') }}" rel="icon" type="image/png"/>
  @else
  <link href="{{ asset('img/icon_topos.png') }}?v=1" rel="apple-touch-icon" sizes="76x76"/>
  <link href="{{ asset('img/icon_topos.png') }}?v=1" rel="icon" type="image/png"/>
  @endif
  <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible"/>
  <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport"/>
  <meta content="width=device-width" name="viewport"/>
  <!-- Bootstrap core CSS     -->
  <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet"/>
  <link href="{{ asset('css/selectize.bootstrap3.css') }}" rel="stylesheet">
  <link href="{{ asset('css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
  <!--     Fonts and icons     -->
  <link href="{{ asset('css/material-kit.css?v=1.2.0')}}" rel="stylesheet"/>
  <link href="{{ asset('assets/assets-for-demo/vertical-nav.css')}}" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" rel="stylesheet" type="text/css"/>
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet"/>
  {!! SEOMeta::generate() !!}
  {!! OpenGraph::generate() !!}
  {!! Twitter::generate() !!}
  <!-- MINIFIED -->
  {!! SEO::generate(true) !!}
  <!-- LUMEN -->
  {!! app('seotools')->generate() !!}
</link>
</head>
<style type="text/css">

.navbar .navbar-brand {
  position: relative;
  @if(Agent::isMobile())
  height: 50px;
  @else
  height: 75px;
  @endif
  line-height: 0px;
  color: inherit;
  padding: 10px 15px;
}

.list-produk {
  padding-left: 4px;
  padding-right: 4px;
}
.product-page .page-header .container {
  padding-top: 10vh;
}
.product-page .main-raised {
  padding-top: 0%;
}
.product-page .related-products .title {
  margin-bottom: 1px;
}
.product-page h2.title {
  margin-bottom: 0px;
  margin-top: 0px;
}

.card .card-image{
  height: auto; /*this makes sure to maintain the aspect ratio*/
  margin-top: 5px;
}
.img-produk{
  border-radius: 15px;
  margin-top: 10px;
}
p {
  margin: 0 0 0px;
}
.card-pricing {
  margin-bottom: 0px;
}
.tombolBeli {
  padding: 10px 0px;
  margin:0px;
}
.card-pricing .card-content {
  padding: 5px !important;
}
.card .footer {
  margin-top: 0px;
  font-family: Helvetica,Arial,sans-serif;
  font-weight: 400;
  line-height:1.2em;
  text-decoration: none;
  font-size:15px;
}

@font-face {
  font-family: "San Francisco";
  font-weight: 200;
  src: url("//applesocial.s3.amazonaws.com/assets/styles/fonts/sanfrancisco/sanfranciscodisplay-thin-webfont.woff2");
}


.flexFont {
  @if(Agent::isMobile())
  height:4em;
  @else
  height:3em;
  @endif
  padding:1%;
  margin: 5px;

}
.btnWarung {
  padding:1%;
  margin: 0px;
}

.smaller {
  font-size: 0.7em;
  background-color:red;
  width: 10em;
}
.buttonColor{
  @if($setting_aplikasi->tipe_aplikasi == "1") /*tipe-aplikasi == 1, aplikasi topos*/
  background-color: #2ac326;
  @else
  background-color: #01573e;
  @endif
}
.btn.btn-just-icon, .navbar .navbar-nav > li > a.btn.btn-just-icon {
  font-size: 15px;
  padding: 6px 5px;
  line-height: 1em;
}

.footer-big .social-feed i {
  font-size: 23.5px;
  display: table-cell;
  padding-right: 10px;
}
.img-jasa{
  padding: 0px 2px 2px;
}

</style>
<body class="product-page">
  @if(Agent::isMobile())
  <nav class="navbar navbar-default navbar-fixed-top " id="sectionsNav">
    @else
    <nav class="navbar navbar-default navbar-transparent navbar-fixed-top navbar-color-on-scroll" color-on-scroll=" " id="sectionsNav">
      @endif
      <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
          <button class="navbar-toggle" data-toggle="collapse" type="button">
            <span class="sr-only">
              Toggle navigation
            </span>
            <span class="icon-bar">
            </span>
            <span class="icon-bar">
            </span>
            <span class="icon-bar">
            </span>
          </button>
          @if($setting_aplikasi->tipe_aplikasi == 0)
          <a href="{{ url('/') }}">
            <img class="navbar-brand" src="{{asset('/assets/img/examples/warmart_logo.png')}}"/>
          </a>
          @else
          @if( $foto_logo->foto_ktp != null)
          <a href="{{ url('/') }}">
            <img class="navbar-brand" src="{{asset('/foto_ktp_user/'.$foto_logo->foto_ktp.'').'?v=1'}}"/>
          </a>
          @else
          <a href="{{ url('/') }}">
            <img class="navbar-brand" src="{{asset('/assets/img/examples/topos_logo.png'.'?v=3')}}"/>
          </a>
          @endif
          @endif

          @if(Agent::isMobile() && !Auth::check())
          <a class="navbar-brand pull-right" href="{{ url('/login') }}">
            <i class="material-icons">
              account_circle
            </i> 
          </a>
          <a class="navbar-brand pull-right" href="{{ url('/keranjang-belanja') }}">
            <i class="material-icons">
              shopping_cart
            </i>
            <b style="font-size: 15px" id="jumlah-keranjang" data-jumlah="{{ $cek_belanjaan }}" data-session="">
              | {{ $cek_belanjaan }}
            </b>
          </a>
          @endif
          @if(Agent::isMobile() && Auth::check() && Auth::user()->tipe_user == 3)
          <a class="navbar-brand pull-right" href="{{ url('/keranjang-belanja') }}">
            <i class="material-icons">
              shopping_cart
            </i>
            <b style="font-size: 15px" id="jumlah-keranjang" data-jumlah="{{ $cek_belanjaan }}" data-session="">
              | {{ $cek_belanjaan }}
            </b>
          </a>
          @endif
        </div>
        <div class="collapse navbar-collapse">
          <ul class="nav navbar-nav navbar-right">
            @if(Auth::check())
            <li class="dropdown button-container ">
              <a class="dropdown-toggle btn btn-rose btn-round" data-toggle="dropdown" href="#">
                <i class="material-icons">
                  account_circle
                </i>
                {{ Auth::user()->name}}
                <b class="caret">
                </b>
              </a>
              <ul class="dropdown-menu dropdown-with-icons">
                @if(Auth::user()->tipe_user == 3)
                <li style="color:black">
                  <a href="{{ url('/ubah-profil-pelanggan') }}">
                    <i class="material-icons">
                      settings
                    </i>
                    Ubah Profil
                  </a>
                </li>
                <li style="color:black">
                  <a href="{{ url('/ubah-password-pelanggan') }}">
                    <i class="material-icons">
                      lock_outline
                    </i>
                    Ubah Password
                  </a>
                </li>
                @endif
                <li>
                  <a href="{{ url('/logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="material-icons">
                      reply_all
                    </i>
                    Logout
                  </a>
                  <form action="{{ url('/logout') }}" id="logout-form" method="POST" style="display: none;">
                    {{ csrf_field() }}
                  </form>
                </li>
              </ul>
            </li>
            @if(Auth::user()->tipe_user == 3)
            <li class="button-container">
              <a href="{{ url('/pesanan') }}">
                <i class="material-icons">
                  archive
                </i>
                Pesanan
              </a>
            </li>
            @endif
            @endif
            @if(Auth::check() && (Auth::user()->tipe_user == 4 OR Auth::user()->tipe_user == 1 ))
            <li>
              <a href="{{ url('/dashboard')}}">
                <i class="material-icons">
                  dashboard
                </i>
                dashboard
              </a>
            </li>
            @endif
            <li>
              @if($setting_aplikasi->tipe_aplikasi == 0)
              <a href="https://info.war-mart.id">
                <i class="material-icons">
                  info
                </i>
                SUPPORT Warmart
              </a>
              @else
              @if(Auth::check())                            
              <a href="{{ url('/cara-memesan')}}" target='blank'>
                <i class="material-icons">
                  info
                </i>
                CARA MEMESAN
              </a>
              @else
              <a href="{{ url('/cara-pemesanan')}}" target='blank'>
                <i class="material-icons">
                  info
                </i>
                CARA MEMESAN
              </a>
              @endif
              @endif
            </li>
            <li>
              @if($setting_aplikasi->tipe_aplikasi == 0)
              <a href="{{ url('/tentang-warmart')}}">
                <i class="material-icons">
                  info
                </i>
                Tentang Warmart
              </a>
              @else
              <a href="<?=$settingFooter->about_link;?>">
                <i class="material-icons">
                  info
                </i>
                Tentang TOPOS
              </a>
              @endif
            </li>

            @if(!Auth::check())
            <li class="button-container">
              <a class="btn btn-rose btn-round" href="{{ url('/login')}}">
                <i class="material-icons">
                  account_circle
                </i>
                Masuk
              </a>
            </li>
            @endif
            @if (Auth::check() == false) 
            <li class="button-container">
              <a class="btn btn-round btn-rose" href="{{ url('/keranjang-belanja') }}">
                <i class="material-icons">
                  shopping_cart
                </i>
                Keranjang Belanja
                <b style="font-size: 15px" id="jumlah-keranjang" data-session="" data-jumlah="{{ $cek_belanjaan }}">
                  | {{ $cek_belanjaan }}
                </b>
              </a>
            </li>
            @elseif(Auth::check() && Auth::user()->tipe_user == 3)
            <li class="button-container">
              <a class="btn btn-round btn-rose" href="{{ url('/keranjang-belanja') }}">
                <i class="material-icons">
                  shopping_cart
                </i>
                Keranjang Belanja
                <b style="font-size: 15px" id="jumlah-keranjang" data-session="" data-jumlah="{{ $cek_belanjaan }}">
                  | {{ $cek_belanjaan }}
                </b>
              </a>
            </li>
            @endif
          </ul>
        </div>
      </div>
    </nav>


    @if($setting_aplikasi->tipe_aplikasi == 0)
    <div class="page-header header-filter header-small" data-parallax="false" style="background-image: url('../image/background2.jpg');">
      @else
      <div class="page-header header-small" data-parallax="true"" style="background-color: #2ac326">
        @endif
        <div class="container">
          <div class="row">
            <div class="col-md-8 col-md-offset-2">
              <div class="brand">

                @if($setting_aplikasi->tipe_aplikasi == 0)

                <h2 class="title text-center">
                  PASAR MUSLIM INDONESIA
                </h2>
                @if(!Agent::isMobile())
                <h4 class="title text-center">
                  Segala Kemudahan Untuk Umat Muslim Berbelanja.
                </h4>
                @endif

                @else

                <h2 class="title text-center">
                  <?=$settingFooter->judul_warung;?>
                </h2>
                @endif


              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="container">
        <div class="main main-raised main-product">
          <div class="row page-1">

            <div class="col-md-6 col-sm-6">
              @if($barang->foto != NULL)
              <img class="img-produk" src="{{ asset('foto_produk')}}/{{$barang->foto}}"/>
              @else
              <img class="img-produk" src="{{ asset('image')}}/foto_default.png"/>
              @endif
            </div>
            <div class="col-md-6 col-sm-6">
              <h2 class="title">
                {{ $barang->nama }}
              </h2>
              <h3 class="main-price h3">
                Rp. {{ number_format($barang->harga_jual,0,',','.') }}
              </h3>
              <a class="description" style="font-size: 20px;">
                <i class="material-icons" style="font-size: 30px;">
                  store
                </i>
                {{ $barang->warung->name }}
              </a>
              {!! substr($barang->deskripsi_produk, 0, 300) !!}
              <a aria-controls="collapseOne" aria-expanded="true" data-parent="#accordion" data-toggle="collapse" href="#collapseOne" role="button">
                <h4 class="panel-title">
                  <b>
                    Baca Selengkapnya...
                  </b>
                  <i class="material-icons">
                    keyboard_arrow_down
                  </i>
                </h4>
              </a>
            </div>
            @if(Auth::check() == false || Auth::check() && Auth::user()->tipe_user == 3)
            @if (Agent::isMobile())
            <!--JIKA DAKSES VIA HP/TAB-->
            <div class="row text-center">
              @if ($cek_produk == 0)
              <a class="btn btn-round buttonColor" disabled="" rel="tooltip" title="Stok Tidak Ada" data-id-produk='{{$barang->id}}' data-nama-produk="{{ $barang->nama }}">
                Beli Sekarang
                <i class="material-icons">
                  shopping_cart
                </i>
              </a>
              @else
              <button class="btn btn-round buttonColor" id="btnBeliSekarang" rel="tooltip" title="Tambah Ke Keranjang Belanja" data-id-produk='{{$barang->id}}' data-nama-produk="{{ $barang->nama }}">
                Beli Sekarang
                <i class="material-icons">
                  shopping_cart
                </i>
              </button>
              @endif
            </div>
            @else
            <div class="row text-right">
              @if ($cek_produk == 0)
              <a class="btn btn-round buttonColor" disabled="" rel="tooltip" title="Stok Tidak Ada" data-id-produk='{{$barang->id}}' data-nama-produk="{{ $barang->nama }}">
                Beli Sekarang
                <i class="material-icons">
                  shopping_cart
                </i>
              </a>
              @else
              <button class="btn btn-round buttonColor" id="btnBeliSekarang" rel="tooltip" title="Tambah Ke Keranjang Belanja" data-id-produk='{{$barang->id}}' data-nama-produk="{{ $barang->nama }}">

                Beli Sekarang
                <i class="material-icons">
                  shopping_cart
                </i>
              </button>
              @endif
            </div>
            @endif
            @endif

            <div class="col-sm-12 col-md-12">
              <div id="acordeon">
                <div class="panel-group" id="accordion">
                  <div class="panel panel-border panel-default">
                    <div class="panel-collapse collapse" id="collapseOne">
                      <div class="panel-body">
                        <div class="panel-body">
                          <hr style="border-width: 1px; border-color: black">
                          <h3 class="h3">
                            Detail Produk Dari {{$barang->nama}}
                          </h3>
                          <br>
                          {!!$barang->deskripsi_produk!!}
                        </br>
                      </hr>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!--  end acordeon -->
        </div>

      </div>

      <div class="related-products">
        <h3 class="text-center">
          Produk {{ title_case($barang->warung->name) }}
        </h3>
        <div class="row">

          <div class="col-md-12"><br>
            <div class="row">
              <div class="col-md-12">
                <!-- Menampilkan Produk -->
                <span id="span-produk"> {!! $daftar_produk_warung !!}</span>
              </div>

            </div>
          </div>

        </div>
      </div>

    </hr>
    <div class="related-products">
      <h3 class="text-center ">
        Produk Serupa 
      </h3>
      <div class="row">

        <div class="col-md-12"><br>
          <div class="row">
            <div class="col-md-12">
              <!-- Menampilkan Produk -->
              <span id="span-produk">  {!! $daftar_produk_sama !!}</span>
            </div>

          </div>
        </div>

      </div>
    </div>

    <hr>

  </div>
</div>


<footer class="footer footer-black footer-big" style="bottom: 0;">
  <div class="container">
    <div class="content">
      <div class="row">
        <div class="col-md-3">
          <h5 style="font-size: 14px; mar">
            Tentang Kami
          </h5>
          @if($setting_aplikasi->tipe_aplikasi == 0)
          <p>
            Warmart adalah marketplace warung muslim pertama di Indonesia. Kami menghubungkan usaha-usaha muslim dengan pelanggan seluruh Umat Islam di Indonesia. Jenis usaha yang dapat bergabung dengan Warmart diantaranya: Warung, Toko, Minimarket, Pedagang Kaki Lima, Bengkel, Rumah Makan, Klinik, Home Industri, Peternakan, Pertanian, Perikanan, Kerajinan, Fashion dan usaha lainya.
          </p>
          @else
          <p>
            <?=$settingFooter->
            about_us;?>
          </p>
          @endif
        </div>
        <div class="col-md-3">
          <h5 style="font-size: 14px; mar">
            Hubungi Kami
          </h5>
          <div class="social-feed">
            <div class="feed-line">
              <i class="fa fa-phone-square fa-5x">
              </i>
              <p>
                <?=$settingFooter->
                no_telp;?>
              </p>
            </div>
            <div class="feed-line">
              <i class="fa fa-home fa-5x"> </i>
              <p>
                <?=$settingFooter->
                alamat;?>
              </p>
            </div>
            <div class="feed-line">
              <i class="fa fa-envelope fa-5x">
              </i>
              <p>
                <?=$settingFooter->
                email;?>
              </p>
            </div>
            <div class="feed-line">
              <i class="fa fa-whatsapp fa-5x">
              </i>
              <p>
                <?=$settingFooter->
                whatsapp;?>
              </p>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          @if(Agent::isMobile())
          <div class="row">
            <div class="col-xs-6">
              <h5 style="font-size: 14px; mar">
                Ikuti Kami Di
              </h5>
              <button class="btn btn-just-icon btn-xs btn-facebook" style="margin : 0px">
                <a href="<?=$settingFooter->facebook;?>">
                  <i class="fa fa-facebook"> </i>
                </a> 
              </button>
              <button class="btn btn-just-icon btn-xs btn-twitter" style="margin : 0 0 0 5px">
                <a href="<?=$settingFooter->twitter;?>">
                  <i class="fa fa-twitter"> </i>
                </a> 
              </button>
              <button class="btn btn-just-icon btn-xs btn-instagram" style="margin : 0 0 0 5px">
                <a href="<?=$settingFooter->instagram;?>">
                  <i class="fa fa-instagram"> </i>
                </a> 
              </button>
              <button class="btn btn-just-icon btn-xs btn-google" style="margin : 0 0 0 5px">
                <a href="<?=$settingFooter->google_plus;?>" style="background-color: #d41700">
                  <i class="fa fa-google-plus"> </i>
                </a> 
              </button>
            </div>

            <div class="col-xs-6">
              <h5 style="font-size: 14px; mar">
                Download Apps
              </h5>
              <a href="<?=$settingFooter->play_store;?>">
                <img src="{{asset('image/gplaystore.png')}}">
              </a>  
            </div>
          </div>

          @else

          <h5 style="font-size: 14px; mar">
            Ikuti Kami Di
          </h5>
          <button class="btn btn-just-icon btn-xs btn-facebook" style="margin : 0px">
            <a href="<?=$settingFooter->facebook;?>">
              <i class="fa fa-facebook"> </i>
            </a> 
          </button>
          <button class="btn btn-just-icon btn-xs btn-twitter" style="margin : 0 0 0 5px">
            <a href="<?=$settingFooter->twitter;?>">
              <i class="fa fa-twitter"> </i>
            </a> 
          </button>
          <button class="btn btn-just-icon btn-xs btn-instagram" style="margin : 0 0 0 5px">
            <a href="<?=$settingFooter->instagram;?>">
              <i class="fa fa-instagram"> </i>
            </a> 
          </button>
          <button class="btn btn-just-icon btn-xs btn-google" style="margin : 0 0 0 5px">
            <a href="<?=$settingFooter->google_plus;?>" style="background-color: #d41700">
              <i class="fa fa-google-plus"> </i>
            </a> 
          </button>
          <h5 style="font-size: 14px; mar">
            Download Apps
          </h5>
          <a href="<?=$settingFooter->play_store;?>">
            <img src="{{asset('image/gplaystore.png')}}" style="max-width: 50%">
          </a>  
          @endif                                               
        </div>
        <div class="col-md-3">
          <div class="row" style="padding-left: 15px">
            <div class="col-md-12 col-xs-12" style="padding-left: 0px">
              <h5 style="font-size: 14px;" class="pull-left">
                Jasa Pengiriman
              </h5>
            </div>
            @foreach($jasa_pengirimans as $jasa_pengiriman)
            @if(Agent::isMobile())
            <img src="{{asset('jasa_pengiriman/'.$jasa_pengiriman->logo_jasa)}}" style="max-width: 50px" class="pull-left img-jasa">
            @else
            <img src="{{asset('jasa_pengiriman/'.$jasa_pengiriman->logo_jasa)}}" style="max-width: 65px" class="pull-left img-jasa">
            @endif
            @endforeach

            <div class="col-md-12 col-xs-12" style="padding-left: 0px">
              <h5 style="font-size: 14px;" class="pull-left">
                Metode Pembayaran
              </h5>
            </div>
            @foreach($bank_transfers as $bank_transfer)
            @if(Agent::isMobile())
            <img src="{{asset('jasa_pengiriman/'.$bank_transfer->logo_bank)}}" style="max-width: 50px" class="pull-left img-jasa">
            @else
            <img src="{{asset('jasa_pengiriman/'.$bank_transfer->logo_bank)}}" style="max-width: 65px" class="pull-left img-jasa">
            @endif
            @endforeach
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <hr style="margin: 5px" />

      <div class="copyright pull-right">
        Copyright ©
        <script>
          document.write(new Date().getFullYear())
        </script>
        <a href="https://andaglos.id/">
          PT. Andaglos Global Teknologi.
        </a>
      </div>
    </div>
  </footer>
  <!--Start of Tawk.to Script-->
  <script type="text/javascript">
    var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
    (function(){
      var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
      s1.async=true;
      s1.src='https://embed.tawk.to/5a051374bb0c3f433d4c84cd/default';
      s1.charset='UTF-8';
      s1.setAttribute('crossorigin','*');
      s0.parentNode.insertBefore(s1,s0);
    })();
  </script>
  <!--End of Tawk.to Script-->
</nav>
</body>
<!--   Core JS Files   -->
<script src="{{ asset('js/jquery-3.2.1.min.js') }}" type="text/javascript">
</script>
<script src="{{ asset('js/bootstrap.min.js') }}" type="text/javascript">
</script>
<script src="{{ asset('js/material.min.js') }}" type="text/javascript">
</script>
<script src="{{ asset('js/perfect-scrollbar.jquery.min.js') }}" type="text/javascript">
</script>
<!-- Library for adding dinamically elements -->
<script src="{{ asset('js/arrive.min.js') }}" type="text/javascript">
</script>
<!-- Forms Validations Plugin -->
<script src="{{ asset('js/jquery.validate.min.js') }}">
</script>
<!-- Promise Library for SweetAlert2 working on IE -->
<script src="{{ asset('js/es6-promise-auto.min.js') }}">
</script>
<!--  Plugin for Date Time Picker and Full Calendar Plugin-->
<script src="{{ asset('js/moment.min.js') }}">
</script>
<!--  Charts Plugin, full documentation here: https://gionkunz.github.io/chartist-js/ -->
<script src="{{ asset('js/chartist.min.js') }}">
</script>
<!--  Plugin for the Wizard, full documentation here: https://github.com/VinceG/twitter-bootstrap-wizard -->
<script src="{{ asset('js/jquery.bootstrap-wizard.js') }}">
</script>
<!--  Notifications Plugin, full documentation here: http://bootstrap-notify.remabledesigns.com/    -->
<script src="{{ asset('js/bootstrap-notify.js') }}">
</script>
<!--  Plugin for the DateTimePicker, full documentation here: https://eonasdan.github.io/bootstrap-datetimepicker/ -->
<script src="{{ asset('js/bootstrap-datetimepicker.js') }}">
</script>
<!--    Plugin for Select, full documentation here: http://silviomoreto.github.io/bootstrap-select   -->
<script src="{{ asset('js/bootstrap-selectpicker.js') }}">
</script>
<!-- Vector Map plugin, full documentation here: http://jvectormap.com/documentation/ -->
<script src="{{ asset('js/jquery-jvectormap.js') }}">
</script>
<!-- Sliders Plugin, full documentation here: https://refreshless.com/nouislider/ -->
<script src="{{ asset('js/nouislider.min.js') }}">
</script>
<!--  Plugin for Select, full documentation here: http://silviomoreto.github.io/bootstrap-select -->
<script src="{{ asset('js/jquery.select-bootstrap.js') }}">
</script>
<!--  DataTables.net Plugin, full documentation here: https://datatables.net/    -->
<script src="{{ asset('js/jquery.dataTables.js') }}">
</script>
<!-- Sweet Alert 2 plugin, full documentation here: https://limonte.github.io/sweetalert2/ -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.0.5/sweetalert2.all.min.js" type="text/javascript">
</script>
<!-- Plugin for Fileupload, full documentation here: http://www.jasny.net/bootstrap/javascript/#fileinput -->
<script src="{{ asset('js/jasny-bootstrap.min.js') }}">
</script>
<!--  Full Calendar Plugin, full documentation here: https://github.com/fullcalendar/fullcalendar    -->
<script src="{{ asset('js/fullcalendar.min.js') }}">
</script>
<!-- Plugin for Tags, full documentation here: https://github.com/bootstrap-tagsinput/bootstrap-tagsinputs  -->
<script src="{{ asset('js/jquery.tagsinput.js') }}">
</script>
<script src="{{ asset('js/bootstrap-datepicker.min.js') }}">
</script>
<script src="{{ asset('js/demo.js') }}">
</script>
<script src="{{ asset('js/material-kit.js?v=1.2.0')}}" type="text/javascript">
</script>
{{-- lazy load image --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-lazyload/10.3.5/lazyload.min.js">
</script>
<script type="text/javascript">
  var myLazyLoad = new LazyLoad();
</script>
<script type="text/javascript">


  $(document).on('click', '#btnBeliSekarang', function(){
    let nama_produk = $(this).attr("data-nama-produk");
    let id_produk = $(this).attr("data-id-produk");
    alert(nama_produk,id_produk);
  });

  function alert(nama_produk,id_produk){
    swal({
      title: nama_produk,
      text: 'Masukan Jumlah Produk',
      input: 'number',
      inputValue: 2,
      showCancelButton: true,
      confirmButtonText: 'OK',
      showLoaderOnConfirm: true,
      preConfirm: (jumlah_produk) => {
        return new Promise((resolve) => {
          setTimeout(() => {
            if (jumlah_produk === '') {
              swal.showValidationError(
                'Anda Belum memasukan Jumlah Produk'
                )
            }else if (jumlah_produk <= 0) {
             swal.showValidationError(
              'Masukan jumlah produk yang Valid'
              )
           }
           resolve()
         }, 500)
        })
      },
      allowOutsideClick: () => !swal.isLoading()
    }).then((result) => {
      if (result.value) {
        prosesTambahProduk(result.value,id_produk,nama_produk)            
      }
    })
  }

  function prosesTambahProduk(jumlah_produk,id_produk,nama_produk){

    $.get('{{ Url('/keranjang-belanja/tambah-produk-keranjang-belanja/') }}',{'_token': $('meta[name=csrf-token]').attr('content'),jumlah_produk:jumlah_produk,id_produk:id_produk}, function(data){

     var totalProduk = $("#jumlah-keranjang").attr("data-jumlah");
     var totalProduk = parseInt(totalProduk) + parseInt(data); 
     var sisa_jumlah_produk = "| "+totalProduk;
     $("#jumlah-keranjang").attr("data-jumlah",totalProduk);
     $("#jumlah-keranjang").text(sisa_jumlah_produk);
     swal({
      position: 'center',
      type: 'success',
      text: nama_produk+' Berhasil dimasukan ke Keranjang Belanja',
      showConfirmButton: false,
      timer: 2000
    })

   });

  }

</script>
<script type="text/javascript">
  flexFont = function () {
    @if(Agent::isMobile())
    var divs = document.getElementsByClassName("flexFont");
    for(var i = 0; i < divs.length; i++) {
      var relFontsize = divs[i].offsetWidth*0.13;
      divs[i].style.fontSize = relFontsize+'px';
    }
    @else
    var divs = document.getElementsByClassName("flexFont");
    for(var i = 0; i < divs.length; i++) {
      var relFontsize = divs[i].offsetWidth*0.06;
      divs[i].style.fontSize = relFontsize+'px';
    }

    @endif

  };

  window.onload = function(event) {
    flexFont();
  };
  window.onresize = function(event) {
    flexFont();
  };
</script>
</html>
