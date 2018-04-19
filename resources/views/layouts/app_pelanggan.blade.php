<!DOCTYPE doctype html>
<html lang="en">
<head>
    <!-- PILIH TIPE APLIKASI -->
    <?php
    $session_id    = session()->getId();
    $session = Session::get('session_id');
    $setting_aplikasi = \App\SettingAplikasi::select('tipe_aplikasi')->first();

    $settingFooter = \App\SettingFooter::where('warung_id', \App\SettingPembedaAplikasi::where('app_address', url('/'))->first()->warung_id)->first();
    $jasa_pengirimans = \App\SettingJasaPengiriman::select('logo_jasa')->where('tampil_jasa_pengiriman', 1)->get();
    $bank_transfers = \App\SettingTransferBank::select('logo_bank')->where('tampil_bank', 1)->get();
    //Cek Address Aplikasi yg di Jalankan
    $address_current = url('/');

    $address_app = \App\SettingPembedaAplikasi::select(['warung_id', 'app_address'])->where('app_address', $address_current)->first();
    $google = \App\SettingFixel::select('id_pixel')->where('fixel','Google')->where('warung_id',$address_app->warung_id);
    $facebook = \App\SettingFixel::select('id_pixel')->where('fixel','Facebook')->where('warung_id',$address_app->warung_id);
    if ($google->count() > 0) {
        $fixelGoogle = $google->first()->id_pixel;
    }else{
        $fixelGoogle = "#";
    }

    if ($facebook->count() > 0) {
        $fixelFacebook = $facebook->first()->id_pixel;
    }else{
        $fixelFacebook = 0;
    }

    if ($address_current == $address_app->app_address) {
        $foto_logo = \App\UserWarung::select()->where('tipe_user',4)->where('id_warung', $address_app->warung_id)->orderBy('id', 'asc')->limit(1)->first();
    }else{
        $foto_logo = \App\UserWarung::select()->where('tipe_user',4)->orderBy('id', 'asc')->limit(1)->first();
    }
    ?>
    
    @if($setting_aplikasi->tipe_aplikasi == 0)
    <title>
        War-Mart.id
    </title>
    @else
    <title>
        {{$settingFooter->judul_warung}}
    </title>
    @endif
    <meta charset="utf-8"/>
    @if(\App\SettingAplikasi::select('tipe_aplikasi')->first()->tipe_aplikasi == 0)
    <link href="img/favicon.png" rel="apple-touch-icon" sizes="76x76"/>
    <link href="img/favicon.png" rel="icon" type="image/png"/>
    @else
    <link href="img/icon_topos.png?v=1" rel="apple-touch-icon" sizes="76x76"/>
    <link href="img/icon_topos.png?v=1" rel="icon" type="image/png"/>
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

    <!-- Facebook Pixel Code -->
    <script>
      !function(f,b,e,v,n,t,s)
      {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
          n.callMethod.apply(n,arguments):n.queue.push(arguments)};
          if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
          n.queue=[];t=b.createElement(e);t.async=!0;
          t.src=v;s=b.getElementsByTagName(e)[0];
          s.parentNode.insertBefore(t,s)}(window, document,'script',
              'https://connect.facebook.net/en_US/fbevents.js');
          fbq('init', {{$fixelFacebook}});
          fbq('track', 'PageView');
      </script>
      <noscript>
        <img height="1" width="1" style="display:none"
        src="https://www.facebook.com/tr?id=368766516938129&ev=PageView&noscript=1"/>
    </noscript>
    <!-- End Facebook Pixel Code -->

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-39670368-7"></script>
    <script>
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());

          gtag('config', {{$fixelGoogle}});
    </script>

    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
    {!! Twitter::generate() !!}
    <!-- MINIFIED -->
    {!! SEO::generate(true) !!}
    <!-- LUMEN -->
    {!! app('seotools')->generate() !!}
</link>
</link>
</head>
<style type="text/css">
.navbar-nav .open .dropdown-menu{
  color: grey;
}
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
body {
    @if($setting_aplikasi->tipe_aplikasi == 1)
    background-color: #2ac326;
    color: #3C4858;
    @endif
}
@keyframes spinner { 
  to {transform: rotate(360deg);} 
} 
.spinner:before { 
  content: ''; 
  box-sizing: border-box; 
  position: absolute; 
  top: 50%; 
  left: 50%; 
  width: 20px; 
  height: 20px; 
  margin-top: -10px; 
  margin-left: -10px; 
  border-radius: 50%; 
  border: 2px solid #ccc; 
  border-top-color: #333; 
  animation: spinner .6s linear infinite; 
} 
.selectizeLoading > .selectize-input, .selectizeLoading > .selectize-input > input
{
  cursor: wait !important;
  font-style: italic;
  background:
  url('http://www.hsi.com.hk/HSI-Net/pages/images/en/share/ajax-loader.gif')
  no-repeat
  center center;
}
.marginFrom{
    margin-bottom: 1px;
    margin-top: 1px;
}
.navbar>.container .navbar-brand, .navbar>.container-fluid .navbar-brand {
    margin-left: 0px;
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
<body class="ecommerce-page">
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
        @yield('content')
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
    <!-- Include Dexie -->
    <script src="https://unpkg.com/dexie@latest/dist/dexie.js"></script>

    <!--   Core JS Files   -->
    <script src="{{ asset('js/jquery-3.2.1.min.js') }}" type="text/javascript">
    </script>
    <script src="{{ asset('js/bootstrap.min.js') }}" type="text/javascript">
    </script>
    <script src="{{ asset('js/material.min.js') }}" type="text/javascript">
    </script>
    <script src="{{ asset('js/bootstrap-datepicker.min.js') }}">
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
    <!-- Sertakan polibill untuk Prompt ES6 (opsional) untuk browser IE11 dan Android -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/core-js/2.4.1/core.js">
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
    <script src="{{ asset('js/material-dashboard.js?v=1.2.0') }}">
    </script>
    <script src="{{ asset('js/demo.js') }}">
    </script>
    <script src="{{ asset('js/selectize.min.js') }}">
    </script>
    <script src="{{ asset('js/custom.js') }}">
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
@yield('scripts')
</html>
