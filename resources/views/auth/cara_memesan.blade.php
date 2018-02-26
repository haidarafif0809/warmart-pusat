<!doctype html>
<html lang="en">
<?php 
$foto_logo = \App\UserWarung::select()->where('tipe_user',4)->orderBy('id', 'asc')->limit(1)->first();
$nama_toko = \App\Warung::select('name')->first()->name;
?>
<head>

    <meta charset="utf-8" />
    @if(\App\SettingAplikasi::select('tipe_aplikasi')->first()->tipe_aplikasi == 0)
    <link rel="apple-touch-icon" sizes="76x76" href="img/favicon.png" />
    <link rel="icon" type="image/png" href="img/favicon.png" />
    @else
    <link rel="apple-touch-icon" sizes="76x76" href="img/icon_topos.png?v=1" />
    <link rel="icon" type="image/png" href="img/icon_topos.png?v=1" />
    @endif
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    @if(\App\SettingAplikasi::select('tipe_aplikasi')->first()->tipe_aplikasi == 0)
    <title>War-Mart.id</title>
    @else
    <title>{{$nama_toko}}</title>
    @endif

    <meta name="viewport" content="width=device-width" />
    <!-- Bootstrap core CSS     -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet" />
    <!--  Material Dashboard CSS    -->
    <link href="{{ asset('css/material-dashboard.css?v=1.2.0') }}" rel="stylesheet" />
    
    <link href="{{ asset('css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
    <!--     Fonts and icons     -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Material+Icons" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="{{ asset('css/material-kit.css?v=1.2.0')}}" rel="stylesheet"/>
    <link href="{{ asset('assets/assets-for-demo/vertical-nav.css')}}" rel="stylesheet" />

</head>
<style type="text/css">
    .cara-memesan {
        @if(Agent::isMobile())
        max-width: 65%;
        @else
        max-width: 30%;
        @endif

        margin-bottom: 12px;
    }
    h3{
        margin-top: 0px
    }
    .navbar>.container .navbar-brand, .navbar>.container-fluid .navbar-brand {
        margin-left: 0px;
    }
</style>
<body class="off-canvas-sidebar">
    <nav class="navbar  navbar-fixed-top " color-on-scroll=" " id="sectionsNav" style="background-color:#2ac326;">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navigation-example-2">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <a class="navbar-brand" href="{{ url('/')}}"><img class="navbar-brand" src="{{asset('/foto_ktp_user/'.$foto_logo->foto_ktp.'').'?v=1'}}"/></a>

            </div>
            <div class="collapse navbar-collapse">
                <ul class="nav navbar-nav navbar-right"> 
                    <li>
                        <a data-toggle="collapse" href="#pagesExamples">
                            <p>
                                <i class="material-icons">person_add</i> Registrasi
                                <b class="caret"></b>
                            </p>
                        </a>
                        <div class="collapse" id="pagesExamples">
                            <ul class="nav">
                                <li class="">
                                    <a href="{{ url('/register-customer') }}">
                                        <i class="material-icons">person_add</i> Pelanggan
                                    </a>
                                </li>
                                @if(\App\SettingAplikasi::select('tipe_aplikasi')->first()->tipe_aplikasi == 0)
                                <li class="">
                                    <a href="{{ url('/register') }}">
                                        <i class="material-icons">people</i> Komunitas
                                    </a>
                                </li> 
                                <li class="">
                                    <a href="{{ url('/register-warung') }}">
                                        <i class="material-icons">store</i> Warung
                                    </a>
                                </li> 
                                @endif
                            </ul>
                        </div>
                    </li>

                    <li class=" active ">
                        <a href="{{ url('/login') }}">
                            <i class="material-icons">fingerprint</i> Login
                        </a>
                    </li>
                    
                </ul>
            </div>
        </div>
    </nav>
    <div class="wrapper wrapper-full-page">
        <div class="full-page login-page" filter-color="black" data-image="{{ asset('img/login_bg.jpg') }}">
            <!--   you can change the color of the filter page using: data-color="blue | purple | green | orange | red | rose " -->
            <div class="content">
                <div class="main main-raised">
                    <div class="section section-basic">
                        <div class="container" style="color:black;">

                            <center><b><h3 style="font-weight:bold;">CARA MEMESAN DI {{strtoupper($nama_toko)}} </h3></b></center>

                            <ol class="col-md-2">
                            </ol>
                            <ol class="col-md-8 col-xs-12">
                                {{-- 1 --}}
                                <center>
                                    <img class="cara-memesan" src="{{asset('/foto_cara_memesan/1.png').'?v=1'}}" />
                                </center>
                                <li style="margin-bottom: 30px">
                                    Untuk memulai pemesanan Anda harus mendaftar sebagai pelanggan, kemudian masuk. Atau jika Anda sudah terdaftar sebagai pelanggan silahkan masuk menggunakan nomor telpon dan kata sandi.
                                </li>
                                {{-- 2 --}}
                                <center>
                                    <img class="cara-memesan" src="{{asset('/foto_cara_memesan/2.png').'?v=1'}}"/>
                                </center>
                                <li style="margin-bottom: 30px">
                                    Silahkan jelajahi toko kami dan temukan produk yang Anda cari, lalu silahkan klik tombol <strong>"Beli Sekarang"</strong> untuk menambahkan produk kedalam keranjang belanja Anda.
                                </li>
                                {{-- 3 --}}
                                <center>                                    
                                    <img class="cara-memesan" src="{{asset('/foto_cara_memesan/3.png').'?v=1'}}"/>
                                </center>
                                <li style="margin-bottom: 30px">
                                    Anda dapat mengecek belanjaan Anda di keranjang belanja sebelum melakukan proses pemesanan. Anda juga dapat menambah dan mengurangi jumlah barang yang Anda beli dan Anda dapat melanjutkan belanja dengan klik tombol <strong>"Lanjutkan Belanja"</strong>. Jika sudah selesai silahkan klik tombol <strong>"Pembayaran"</strong>.
                                </li>
                                {{-- 4 --}}
                                <center>                                    
                                    <img class="cara-memesan" src="{{asset('/foto_cara_memesan/4.png').'?v=1'}}"/>
                                </center>
                                <li style="margin-bottom: 30px">
                                    Selesaikan pesanan Anda dengan mengisi form pengiriman dengan lengkap, lalu klik tombol <strong>"Selesai Pesanan"</strong>.
                                </li>
                                {{-- 5 --}}
                                <center>                                    
                                    <img class="cara-memesan" src="{{asset('/foto_cara_memesan/55.png').'?v=1'}}"/>
                                </center>
                                <li style="margin-bottom: 30px">
                                    Tahap selanjutnya, kami akan menghubungi Anda untuk mengkonfirmasi dan  memproses pesanan Anda.
                                </li>
                                {{-- 6 --}}
                                <center>                                    
                                    <img class="cara-memesan" src="{{asset('/foto_cara_memesan/77.png').'?v=1'}}"/>
                                </center>
                                <li>
                                    Silahkan menunggu pesanan Anda sampai ke alamat tujuan Anda. <strong>Terima Kasih sudah Berbelanja di {{$nama_toko}}.</strong>
                                </li>
                                
                            </ol>
                            <ol class="col-md-2">
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <footer class="footer">
                <div class="container">
                    <p class="copyright pull-right">
                        &copy;
                        <script>
                            document.write(new Date().getFullYear())
                        </script>
                        <a href="https://andaglos.id"> PT Andaglos Global Teknologi</a>
                    </p>
                </div>
            </footer>
        </div>
    </div>
</body>







<!--   Core JS Files   -->
<script src="{{ asset('js/jquery-3.2.1.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/bootstrap.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/material.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/perfect-scrollbar.jquery.min.js') }}" type="text/javascript"></script>
<!-- Library for adding dinamically elements -->
<script src="{{ asset('js/arrive.min.js') }}" type="text/javascript"></script>
<!-- Forms Validations Plugin -->
<script src="{{ asset('js/jquery.validate.min.js') }}"></script>
<!-- Promise Library for SweetAlert2 working on IE -->
<script src="{{ asset('js/es6-promise-auto.min.js') }}"></script>
<!--  Plugin for Date Time Picker and Full Calendar Plugin-->
<script src="{{ asset('js/moment.min.js') }}"></script>
<!--  Charts Plugin, full documentation here: https://gionkunz.github.io/chartist-js/ -->
<script src="{{ asset('js/chartist.min.js') }}"></script>
<!--  Plugin for the Wizard, full documentation here: https://github.com/VinceG/twitter-bootstrap-wizard -->
<script src="{{ asset('js/jquery.bootstrap-wizard.js') }}"></script>
<!--  Notifications Plugin, full documentation here: http://bootstrap-notify.remabledesigns.com/    -->
<script src="{{ asset('js/bootstrap-notify.js') }}"></script>
<!--  Plugin for the DateTimePicker, full documentation here: https://eonasdan.github.io/bootstrap-datetimepicker/ -->
<script src="{{ asset('js/bootstrap-datetimepicker.js') }}"></script>
<!-- Vector Map plugin, full documentation here: http://jvectormap.com/documentation/ -->
<script src="{{ asset('js/jquery-jvectormap.js') }}"></script>
<!-- Sliders Plugin, full documentation here: https://refreshless.com/nouislider/ -->
<script src="{{ asset('js/nouislider.min.js') }}"></script>
<!--  Google Maps Plugin    -->
<script src="https://maps.googleapis.com/maps/api/js?key=YOUR_KEY_HERE"></script>
<!--  Plugin for Select, full documentation here: http://silviomoreto.github.io/bootstrap-select -->
<script src="{{ asset('js/jquery.select-bootstrap.js') }}"></script>
<!--  DataTables.net Plugin, full documentation here: https://datatables.net/    -->
<script src="{{ asset('js/jquery.dataTables.js') }}"></script>
<!-- Sweet Alert 2 plugin, full documentation here: https://limonte.github.io/sweetalert2/ -->
<script src="{{ asset('js/sweetalert2.js') }}"></script>
<!-- Plugin for Fileupload, full documentation here: http://www.jasny.net/bootstrap/javascript/#fileinput -->
<script src="{{ asset('js/jasny-bootstrap.min.js') }}"></script>
<!--  Full Calendar Plugin, full documentation here: https://github.com/fullcalendar/fullcalendar    -->
<script src="{{ asset('js/fullcalendar.min.js') }}"></script>
<!-- Plugin for Tags, full documentation here: https://github.com/bootstrap-tagsinput/bootstrap-tagsinputs  -->
<script src="{{ asset('js/jquery.tagsinput.js') }}"></script>
<!-- Material Dashboard javascript methods -->
<script src="{{ asset('js/material-dashboard.js?v=1.2.0') }}"></script>

<script src="{{ asset('js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('js/demo.js') }}"></script> 
<script src="{{ asset('js/material-kit.js?v=1.2.0')}}" type="text/javascript"></script>

