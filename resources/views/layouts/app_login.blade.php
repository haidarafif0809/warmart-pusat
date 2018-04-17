<!doctype html>
<html lang="en">
<!-- PILIH TIPE APLIKASI -->
<?php
$setting_aplikasi = \App\SettingAplikasi::select('tipe_aplikasi')->first();
$foto_logo = \App\UserWarung::select()->where('tipe_user',4)->orderBy('id', 'asc')->limit(1)->first();
$judul_warung = \App\SettingFooter::select()->first()->judul_warung;
    //Cek Address Aplikasi yg di Jalankan
$address_current = url('/');

$address_app = \App\SettingPembedaAplikasi::select(['warung_id', 'app_address'])->where('app_address', $address_current)->first();
if ($address_current == $address_app->app_address) {
    $foto_logo = \App\UserWarung::select()->where('tipe_user',4)->where('id_warung', $address_app->warung_id)->orderBy('id', 'asc')->limit(1)->first();
}else{
    $foto_logo = \App\UserWarung::select()->where('tipe_user',4)->orderBy('id', 'asc')->limit(1)->first();
}
?>
<head>
    <meta charset="utf-8" />
    @if($setting_aplikasi->tipe_aplikasi == 0)
    <link rel="apple-touch-icon" sizes="76x76" href="img/favicon.png" />
    <link rel="icon" type="image/png" href="img/favicon.png" />
    @else
    <link rel="apple-touch-icon" sizes="76x76" href="img/icon_topos.png?v=1" />
    <link rel="icon" type="image/png" href="img/icon_topos.png?v=1" />
    @endif
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    
    @if($setting_aplikasi->tipe_aplikasi == 0)
    <title>War-Mart.id</title>
    @else
    <title>{{$judul_warung}}</title>
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
    <link href="{{ asset('css/selectize.bootstrap3.css') }}" rel="stylesheet">
</head>
<style type="text/css">
.navbar .navbar-brand {
    position: relative;
    height: 75px;
    line-height: 30px;
    color: inherit;
    padding: 10px 15px;
}
</style>
<body class="off-canvas-sidebar">


    <nav class="navbar navbar-primary navbar-transparent navbar-absolute">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navigation-example-2">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
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
                                @if($setting_aplikasi->tipe_aplikasi == 0)
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
                <div class="container">
                    <div class="row">


                        <div class="col-md-4 col-sm-6 col-md-offset-4 col-sm-offset-3">

                           @yield('content')
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
<script src="{{ asset('js/selectize.min.js') }}"></script> 
<script src="{{ asset('js/demo.js') }}"></script> 
<!-- Include Dexie -->
<script src="https://unpkg.com/dexie@latest/dist/dexie.js"></script>
<script type="text/javascript">
    $().ready(function() {
        demo.checkFullPageBackgroundImage();

        
    });
    $('.datepicker').datepicker({
        format: 'yyyy-mm-dd', 
        autoclose: true,
    });
</script>



<script type="text/javascript">
    $(document).ready(function(){
        $(document).on("click", "#ceklis_syarat", function(){
            var data_toogle = $(this).attr("data_toogle");
            if (data_toogle == 0) {
                $("#tombol_regist").attr("disabled",false);
                $("#ceklis_syarat").prop('checked', true);
                $(this).attr("data_toogle", 1);

            }
            else{
                $("#tombol_regist").attr("disabled",true);
                $("#ceklis_syarat").prop('checked', false);
                $(this).attr("data_toogle", 0);
            }
        });
    }); 
</script>



<script type="text/javascript">
    $(document).ready(function(){
        $(document).on("click", "#ceklis_syarat_warung", function(){
            var data_toogle = $(this).attr("data_toogle");
            if (data_toogle == 0) {
                $("#tombol_regist_warung").attr("disabled",false);
                $("#ceklis_syarat_warung").prop('checked', true);
                $(this).attr("data_toogle", 1);

            }
            else{
                $("#tombol_regist_warung").attr("disabled",true);
                $("#ceklis_syarat_warung").prop('checked', false);
                $(this).attr("data_toogle", 0);
            }
        });
    }); 
</script>

<script type="text/javascript">
    $(document).ready(function(){
        $(document).on("click", "#ceklis_syarat_komunitas", function(){
            var data_toogle = $(this).attr("data_toogle");
            if (data_toogle == 0) {
                $("#tombol_regist_komunitas").attr("disabled",false);
                $("#ceklis_syarat_komunitas").prop('checked', true);
                $(this).attr("data_toogle", 1);

            }
            else{
                $("#tombol_regist_komunitas").attr("disabled",true);
                $("#ceklis_syarat_komunitas").prop('checked', false);
                $(this).attr("data_toogle", 0);
            }
        });

    }); 
</script>



@yield('scripts')

</html>