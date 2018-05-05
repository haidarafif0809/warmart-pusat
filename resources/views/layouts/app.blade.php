<!DOCTYPE doctype html>
<html lang="en">
<!-- PILIH TIPE APLIKASI -->
<?php
use Jenssegers\Agent\Agent;
$agent = new Agent();
$warung_id = \App\SettingPembedaAplikasi::where('app_address', url('/'))->first()->warung_id;
$judul_warung = \App\SettingFooter::where('warung_id', $warung_id)->first()->judul_warung;
$optimasSeo = \App\SettingSeo::select(['content_keyword', 'content_description'])->where('warung_id',$warung_id)->first();
?>
<head>
    <meta charset="utf-8"/>
    @if(\App\SettingAplikasi::select('tipe_aplikasi')->first()->tipe_aplikasi == 0)
    <link rel="apple-touch-icon" sizes="76x76" href="img/favicon.png" />
    <link rel="icon" type="image/png" href="img/favicon.png" />
    @else
    <link rel="apple-touch-icon" sizes="76x76" href="img/icon_topos.png?v=1" />
    <link rel="icon" type="image/png" href="img/icon_topos.png?v=1" />
    @endif
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible"/>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport"/>
    <meta content="width=device-width" name="viewport"/>
    {{-- Optimasi SEO --}}
    <meta name="keywords" content="<?=$optimasSeo->content_keyword; ?>">
    <meta name="description" content="<?=$optimasSeo->content_description; ?>">
    @if(\App\SettingAplikasi::select('tipe_aplikasi')->first()->tipe_aplikasi == 0)
    <title>War-Mart.id</title>
    @else
    <title>{{$judul_warung}}</title>
    @endif
    <!-- CSRF Token -->
    <meta content="{{ csrf_token() }}" name="csrf-token">
    <!--TIPE USER-->
    <meta content="{{ Auth::user()->tipe_user }}" name="tipe-user">
    <!-- Bootstrap core CSS     -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet"/>
    <!--  Material Dashboard CSS    -->
    <link href="{{ asset('css/material-dashboard.css?v=1.2.0') }}" rel="stylesheet"/>
    <!--  CSS for Demo Purpose, don't include it in your project     -->
    <link href="{{ asset('css/selectize.bootstrap3.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
    <!--     Fonts and icons     -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Material+Icons" rel="stylesheet" type="text/css"/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</link>
</link>
</link>
</link>
</meta>
</meta>
</head>
<style type="text/css">

    .table>thead>tr>th, .table>tbody>tr>th, .table>tfoot>tr>th, .table>thead>tr>td, .table>tbody>tr>td, .table>tfoot>tr>td{
       padding: 1px;
   }

</style>
<body>
    <div class="wrapper" id="vue-app">



        <!--MODAL PINTAS TAMBAH KAS-->
        <div class="modal" id="modalTour" role="dialog" data-backdrop=""> 
            <div class="modal-dialog">
                <!-- Modal content--> 
                <div class="modal-content"> 
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"> <i class="material-icons">close</i></button> 
                        <h4 class="modal-title"> 
                            <div class="alert-icon"> 
                                Petunjuk Penggunaan
                            </div> 
                        </h4> 
                    </div>                         
                    <div class="modal-body">
                        <ol>
                            <router-link :to="'produk?tour'" class="menu-nav" data-dismiss="modal">
                                <li>Menambuat Produk Baru</li>
                            </router-link>
                        </ol>
                    </div>
                    <div class="modal-footer">  
                        <button type="button" class="btn btn-danger btn-xs" data-dismiss="modal"><i class="material-icons">close</i> Batal</button> 
                    </div> 
                </div>       
            </div> 
        </div> 
        <!-- / MODAL PINTAS TAMBAH KAS --> 

        <div class="sidebar" data-active-color="rose" data-background-color="black" data-image="">
                <!--
        Tip 1: You can change the color of active element of the sidebar using: data-active-color="purple | blue | green | orange | red | rose"
        Tip 2: you can also add an image using data-image tag
        Tip 3: you can change the color of the sidebar with data-background-color="white | black"
    -->

    <div class="logo">
       @if(\App\SettingAplikasi::select('tipe_aplikasi')->first()->tipe_aplikasi == 0)
       <a class="simple-text logo-mini" href="https://war-mart.id">
        WM
    </a>
    <a class="simple-text logo-normal" href="https://war-mart.id">
        WAR-MART.ID    
    </a>
    @else
    <a class="simple-text logo-mini" href="<?= url('/'); ?>">
        TP
    </a>
    <a class="simple-text logo-normal" href="<?= url('/'); ?>">
        {{strtoupper($judul_warung)}}    
    </a>
    @endif
</div>
<div class="sidebar-wrapper">
    <ul class="nav">
        <li>
            <a data-toggle="collapse" href="#logout">
                <i class="material-icons">
                    person
                </i>
                <p>
                    {{ Auth::user()->name }}
                    <b class="caret">
                    </b>
                </p>
            </a>
            <div class="collapse" id="logout">
                <ul class="nav">
                    <li>
                        @if(Auth::user()->tipe_user == 2 )
                        <a href="{{ url('/ubah-profil-komunitas') }}">
                            <span class="sidebar-mini">
                                UPU
                            </span>
                            <span class="sidebar-normal">
                             Ubah Profil
                         </span>
                     </a>
                     @elseif(Auth::user()->tipe_user == 1 )
                     <router-link :to="{name: 'ubahProfilAdmin'}" class="menu-nav">
                        <span class="sidebar-mini">
                            UP
                        </span>
                        <span class="sidebar-normal">
                         Ubah Profil
                     </span>
                 </router-link>
                 @endif
             </li>
             @if(Auth::user()->tipe_user == 4 AND Auth::user()->kasir_id == 0)
             <li>
                <router-link :to="{name: 'indexProfilWarung'}" class="menu-nav">
                    <span class="sidebar-mini">
                        UP
                    </span>
                    <span class="sidebar-normal">
                        Ubah Profil 
                    </span>
                </router-link>
            </li>
            @endif
            <li>
                @if(Auth::user()->tipe_user == 1 )

                <router-link :to="{name: 'ubahPasswordAdmin'}" class="menu-nav">
                    <span class="sidebar-mini">
                        UP
                    </span>
                    <span class="sidebar-normal">
                        Ubah Password
                    </span>
                </router-link>
                @else
                <router-link :to="{name: 'ubahPasswordUserWarung'}" class="menu-nav" v-on:click="closeMenu()">
                 <span class="sidebar-mini">
                    UP
                </span>
                <span class="sidebar-normal">
                    Ubah Password
                </span>
            </router-link>
            @endif
        </li>
        <li>
            <a href="{{ url('/logout') }}" onclick="event.preventDefault();
            document.getElementById('logout-form').submit();">
            <span class="sidebar-mini">
                L
            </span>
            <span class="sidebar-normal">
                Logout
            </span>
        </a>
        <form action="{{ url('/logout') }}" id="logout-form" method="POST" style="display: none;">
            {{ csrf_field() }}
        </form>
    </li>
</ul>
</div>
</li>
<li class="active">
    @if(Auth::user()->tipe_user == 1 OR Auth::user()->tipe_user == 4)
    <router-link :to="{name: 'indexDashboard'}" class="menu-nav" id="dashboard">
        <i class="material-icons">
            dashboard
        </i>
        <p>
            Dashboard
        </p>
    </router-link>
    @else
    <a href="{{ url('/dashboard')}}">
        <i class="material-icons">
            dashboard
        </i>
        <p>
            Dashboard
        </p>
    </a>
    @endif
</li>

@if(Auth::user()->tipe_user == 4 AND Auth::user()->konfirmasi_admin == 1 AND Auth::user()->foto_ktp != null AND \App\SettingAplikasi::select('tipe_aplikasi')->first()->tipe_aplikasi == 0 )
<!--PRODUK -->
@include('layouts.nav')
@elseif(Auth::user()->tipe_user == 4 AND Auth::user()->konfirmasi_admin == 1  AND \App\SettingAplikasi::select('tipe_aplikasi')->first()->tipe_aplikasi == 1 )

@include('layouts.nav')
@endif

<!--END MASTER DATA WARUNG -->
<!--MASTER DATA WARMART PUSAT-->
@if(Auth::user()->tipe_user == 1)

@if(Laratrust::can('lihat_master_data'))
<li>
    <a data-toggle="collapse" href="#pagesExamples">
        <i class="material-icons">
            image
        </i>
        <p>
            Master Data
            <b class="caret">
            </b>
        </p>
    </a>
    <div class="collapse" id="pagesExamples">
        <ul class="nav">
            @if(Laratrust::can('lihat_bank'))
            <li>
                <router-link :to="{name: 'indexBank'}" class="menu-nav">
                    <span class="sidebar-mini">
                        B
                    </span>
                    <span class="sidebar-normal">
                        Bank
                    </span>
                </router-link>
            </li>
            @endif
            @if(Laratrust::can('lihat_customer'))
            <li>
                <router-link :to="{name: 'indexCustomer'}" class="menu-nav">
                    <span class="sidebar-mini">
                        C
                    </span>
                    <span class="sidebar-normal">
                        Customer
                    </span>
                </router-link>
            </li>
            @endif
            @if(Laratrust::can('lihat_komunitas'))
            <li>
                <router-link :to="{name: 'indexKomunitas'}" class="menu-nav">
                    <span class="sidebar-mini">
                        K
                    </span>
                    <span class="sidebar-normal">
                        Komunitas
                    </span>
                </router-link>
            </li>
            @endif
                                    <!-- OTORITAS ADMIN
@if(Laratrust::can('lihat_otoritas'))
<li>
 <a href="{{ route('otoritas.index') }}">
  <span class="sidebar-mini">O</span>
  <span class="sidebar-normal">Otoritas</span>
</a>
</li>
@endif
-->
@if(Laratrust::can('lihat_user'))
<li>
    <router-link :to="{name: 'indexUser'}" class="menu-nav">
        <span class="sidebar-mini">
            U
        </span>
        <span class="sidebar-normal">
            User
        </span>
    </router-link>
</li>
<li>
    <router-link :to="{name: 'indexUserWarung'}" class="menu-nav">
        <span class="sidebar-mini">
            UW
        </span>
        <span class="sidebar-normal">
            User Warung
        </span>
    </router-link>
</li>
@endif
@if(Laratrust::can('lihat_warung'))
<li>
    <router-link :to="{name: 'indexWarung'}" class="menu-nav">
        <span class="sidebar-mini">
            W
        </span>
        <span class="sidebar-normal">
            Warung
        </span>
    </router-link>
</li>
@endif
<li>
    <router-link :to="{name: 'indexSatuan'}" class="menu-nav">
        <span class="sidebar-mini">
            S
        </span>
        <span class="sidebar-normal">
            Satuan
        </span>
    </router-link>
</li>

<li>
    <router-link :to="{name: 'indexKelompokProduk'}" class="menu-nav">
        <span class="sidebar-mini">
            KP
        </span>
        <span class="sidebar-normal">
            Kelompok Produk
        </span>
    </router-link>
</li>
@if(\App\SettingAplikasi::select('tipe_aplikasi')->first()->tipe_aplikasi == 1)
<li>
    <router-link :to="{name: 'listPendaftaranTopos'}" class="menu-nav">
        <span class="sidebar-mini">
            PT
        </span>
        <span class="sidebar-normal">
            Pendaftar Topos
        </span>
    </router-link>
</li>
@endif

</ul>
</div>
</li>
<li class="">
    <router-link :to="{name: 'indexError'}" class="menu-nav">
        <i class="material-icons">
            error
        </i>
        <p>
            Error Log
        </p>
    </router-link>
</li>
@endif
<!--end master data warmart pusat-->
@endif
<!--HALAMAN KOMUNITAS-->
@if(Auth::user()->tipe_user == 2 AND Auth::user()->konfirmasi_admin == 1)

@endif
</ul>
</div>
</div>
<div class="main-panel">
    <nav class="navbar navbar-transparent navbar-absolute">
        <div class="container-fluid">
            <div class="navbar-minimize">
                <button class="btn btn-round btn-white btn-fill btn-just-icon" id="minimizeSidebar">
                    <i class="material-icons visible-on-sidebar-regular">
                        more_vert
                    </i>
                    <i class="material-icons visible-on-sidebar-mini">
                        view_list
                    </i>
                </button>
            </div>
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

                @if ($agent->isMobile() && Auth::user()->tipe_user == 4) <!--JIKA DAKSES VIA HP/TAB-->
                <span id="sisa_waktu_demo" style="margin-left: 10px"></span>

                <span>                    
                    <a  href="{{ url('/halaman-warung/'.Auth::user()->id_warung.'') }}" class="btn btn-xs btn-rose" style="margin-left: 10px">Preview Online Shop</a>
                    @endif
                </span>
            </div>
            <div class="collapse navbar-collapse">
                <ul class="nav navbar-nav navbar-right">
                    <a href="{{ url('/halaman-warung/'.Auth::user()->id_warung.'') }}" class="btn btn-round btn-rose">Preview Online Shop</a>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <button id="btnHelp" class="btn btn-round btn-info" data-toggle="modal" data-target="#modalTour">
                        <i class="material-icons">help</i> Bantuan
                    </button>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    @if(Auth::user()->tipe_user == 4)
                    <b id="sisa_waktu_demo"></b>
                    @endif
                </ul>
            </div>

        </div>
    </nav>
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                @include('layouts._flash')
                @yield('content')
            </div>
        </div>
        <!-- end container fluid -->
    </div>
    <footer class="footer">
        <div class="container-fluid">
            <nav class="pull-left">
            </nav>
            <p class="copyright pull-right">
                ©
                <script type="text/javascript">
                    document.write(new Date().getFullYear())
                </script>
                <a href="https://andaglos.id">
                    PT Andaglos Global Teknologi
                </a>
            </p>
        </div>
    </footer>
</div>
</div>
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
</body>
<!--   Core JS Files   -->

<script src="{{ asset('js/app.js?v=1.222')}}" type="text/javascript">

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
<script src="{{ asset('js/sweetalert2.js') }}">
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
<!-- Material Dashboard javascript methods -->
<script src="{{ asset('js/material-dashboard.js?v=1.2.0') }}">
</script>
<!-- Material Dashboard DEMO methods, don't include it in your project! -->
<script src="{{ asset('js/demo.js') }}">
</script>
<script src="{{ asset('js/selectize.min.js') }}">
</script>
<script src="{{ asset('js/custom.js') }}">
</script>
<script src="{{ asset('js/ckeditor/ckeditor.js') }}">
</script>
<!-- SHORTCUT JS -->
<script src="{{ asset('js/shortcut.js') }}">
</script>
<script src="https://cdn.rawgit.com/chrisvfritz/5f0a639590d6e648933416f90ba7ae4e/raw/974aa47f8f9c5361c5233bd56be37db8ed765a09/currency-validator.js"></script>


<!--MENU YG SEDANG DI MIGRASI KE VUEJS TIDAK BISA DIAKSES SEMENTARA-->
<script type="text/javascript">
    $(document).on('click', '.vueJs', function(){
        swal("Pemberitahuan!", "Menu Tidak Dapat Diakses. Karena Dalam Proses Perbaikan!", "info");
    });
</script>
<script type="text/javascript">
    $(document).ready(function(){

        var setting_aplikasi = "{{\App\SettingAplikasi::select('tipe_aplikasi')->first()->tipe_aplikasi}}";

        if (setting_aplikasi == 1) {

            $.get('{{ Url("daftar-topos/cek-sisa-demo") }}',{'_token': $('meta[name=csrf-token]').attr('content')}, function(data){ 

                if (data.status_pembayaran < 2) {


                  if (data.sisa_waktu > 0) {

                    $("#sisa_waktu_demo").text("Masa Percobaan Anda Tinggal "+data.sisa_waktu+" Hari Lagi! ");
                    @if ($agent->isMobile() && Auth::user()->tipe_user == 4)
                    $("#sisa_waktu_demo").append("<a href='pendaftaran-topos/1' class='btn btn-xs btn-rose' target='blank' style='margin-left: 10px'>Daftar Sekarang!</a>");
                    @else
                    $("#sisa_waktu_demo").append("<a href='pendaftaran-topos/1' class='btn btn-round btn-rose' target='blank' style='margin-left: 10px'>Daftar Sekarang!</a>");
                    @endif 

                }else{  

                    $("#sisa_waktu_demo").text("Masa Percobaan Anda Sudah Habis!");

                    @if ($agent->isMobile() && Auth::user()->tipe_user == 4)
                    $("#sisa_waktu_demo").append("<a href='pendaftaran-topos/1' class='btn btn-xs btn-rose' target='blank'>Daftar Sekarang!</a>");
                    @else
                    $("#sisa_waktu_demo").append("<a href='pendaftaran-topos/1' class='btn btn-round btn-rose' target='blank'>Daftar Sekarang!</a>");
                    @endif

                    $(document).on('click', '.disabled-menu', function(){

                        swal({
                          text: "Menu Tidak Dapat Diakses. Karena Masa Percobaan Anda Sudah Habis!!",
                          type: 'info',
                          confirmButtonColor: '#3085d6',
                          confirmButtonText: 'OK',
                          confirmButtonClass: 'btn btn-success',
                          buttonsStyling: false,
                          allowEscapeKey  :false,   
                          allowOutsideClick :false
                      }).then(function () {
                        window.location.replace(window.location.origin+(window.location.pathname));
                    })

                  });
                }
            }

        }); 

        }

        $("#minimizeSidebar").click();  

        $(document).on('click', '.menu-nav', function(){
         $('.navbar-toggle ').click();
     });

    });
</script>
@yield('scripts')
</html>
