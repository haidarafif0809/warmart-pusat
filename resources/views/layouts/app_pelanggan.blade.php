<!DOCTYPE doctype html>
<html lang="en">
    <head>
        <title>
            War-Mart.id
        </title>
        <meta charset="utf-8"/>
        <link href="{{ asset('img/favicon.png') }}" rel="apple-touch-icon" sizes="76x76"/>
        <link href="{{ asset('img/favicon.png') }}" rel="icon" type="image/png"/>
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
        </link>
    </head>
    <style type="text/css">
        .navbar-nav .open .dropdown-menu{
  color: grey;
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
                        <a href="{{ url('/') }}">
                            <img class="navbar-brand" src="{{asset('/assets/img/examples/warmart_logo.png')}}"/>
                        </a>
                        @if(Agent::isMobile() && !Auth::check())
                        <a class="navbar-brand pull-right" href="{{ url('/login') }}">
                            <i class="material-icons">
                                account_circle
                            </i>
                            Masuk
                        </a>
                        @endif
                        @if(Agent::isMobile() && Auth::check() && Auth::user()->tipe_user == 3)
                        <a class="navbar-brand pull-right" href="{{ url('/keranjang-belanja') }}">
                            <i class="material-icons">
                                shopping_cart
                            </i>
                            <b style="font-size: 15px">
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
                                <a href="https://info.war-mart.id">
                                    <i class="material-icons">
                                        info
                                    </i>
                                    SUPPORT Warmart
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('/tentang-warmart')}}">
                                    <i class="material-icons">
                                        info
                                    </i>
                                    Tentang Warmart
                                </a>
                            </li>
                            @if(Auth::check() && Auth::user()->tipe_user == 3)
                            <li class="button-container">
                                <a class="btn btn-round btn-rose" href="{{ url('/keranjang-belanja') }}">
                                    <i class="material-icons">
                                        shopping_cart
                                    </i>
                                    Keranjang Belanja
                                    <b style="font-size: 15px">
                                        | {{ $cek_belanjaan }}
                                    </b>
                                </a>
                            </li>
                            @endif




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
                        </ul>
                    </div>
                </div>
            </nav>
            @yield('content')
            <footer class="footer footer-black footer-big">
                <div class="container">
                    <div class="content">
                        <div class="row">
                            <div class="col-md-4">
                                <h5>
                                    Tentang Kami
                                </h5>
                                <p>
                                    Warmart adalah marketplace warung muslim pertama di Indonesia. Kami menghubungkan usaha-usaha muslim dengan pelanggan seluruh Umat Islam di Indonesia. Jenis usaha yang dapat bergabung dengan Warmart diantaranya: Warung, Toko, Minimarket, Pedagang Kaki Lima, Bengkel, Rumah Makan, Klinik, Home Industri, Peternakan, Pertanian, Perikanan, Kerajinan, Fashion dan usaha lainya.
                                </p>
                            </div>
                            <div class="col-md-4">
                                <h5>
                                    Contact Us
                                </h5>
                                <div class="social-feed">
                                    <div class="feed-line">
                                        <i class="fa fa-phone-square">
                                        </i>
                                        <p>
                                            +62-721-8050-299
                                            <br>
                                                Bandar Lampung, Indonesia
                  solusibisnis@andaglos.id
                                            </br>
                                        </p>
                                    </div>
                                    <div class="feed-line">
                                        <i class="fa fa-phone-square">
                                        </i>
                                        <p>
                                            Whatsapp
                                            <br>
                                                +62-811-728-549
                                            </br>
                                        </p>
                                    </div>
                                    <div class="feed-line">
                                        <a href="https://id-id.facebook.com/andaglos/" target="blank">
                                            <i class="fa fa-facebook-square">
                                            </i>
                                            Andaglos
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <h5>
                                    Instagram
                                </h5>
                                <div class="gallery-feed">
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr/>
                    <ul class="pull-left">
                        <li>
                            <a href="#pablo">
                                Blog
                            </a>
                        </li>
                        <li>
                            <a href="#pablo">
                                Presentation
                            </a>
                        </li>
                        <li>
                            <a href="#pablo">
                                Discover
                            </a>
                        </li>
                        <li>
                            <a href="#pablo">
                                Payment
                            </a>
                        </li>
                        <li>
                            <a href="#pablo">
                                Contact Us
                            </a>
                        </li>
                    </ul>
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
  swal({
    text :  "Produk Berhasil Di Tambahkan Ke Keranjang Belanja",
    showConfirmButton :  false,
    type: "success",
    timer: 10000,
    onOpen: () => {
      swal.showLoading()
    }
  });
});
    </script>
    @yield('scripts')
</html>
