<!doctype html>
<html lang="en">
<head>
    <title>War-Mart.id</title>
    <meta charset="utf-8" />
    <link rel="apple-touch-icon" sizes="76x76" href="img/apple-icon.png" />
    <link rel="icon" type="image/png" href="img/favicon.png" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />
    <!-- Bootstrap core CSS     -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet" />
    <!--  Material Dashboard CSS    -->
    <link href="{{ asset('css/material-dashboard.css?v=1.2.0') }}" rel="stylesheet" />

    <link href="{{ asset('css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
    <!--     Fonts and icons     -->
    <link href="{{ asset('css/material-kit.css?v=1.2.0')}}" rel="stylesheet"/>
    <link href="{{ asset('assets/assets-for-demo/vertical-nav.css')}}" rel="stylesheet" />

    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" />

    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
    {!! Twitter::generate() !!}

    <!-- MINIFIED -->
    {!! SEO::generate(true) !!}
    

    <!-- LUMEN -->
    {!! app('seotools')->generate() !!}

</head>

<style type="text/css">
    .navbar-nav .open .dropdown-menu{
        color: grey;
    }
</style>


<body class="ecommerce-page">

    @if (Agent::isMobile()) <!--JIKA DAKSES VIA HP/TAB-->
    <nav class="navbar navbar-default navbar-fixed-top navbar-color-on-scroll" color-on-scroll="100" id="sectionsNav">
        <div class="container">

            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="material-icons">person</i> {{ Auth::user()->name }} 
                        <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu dropdown-with-icons">
                        <li style="color:black">
                            <a href="{{ url('/keranjang-belanja') }}" target="_blank" class="warna-list">
                                <i class="material-icons">shopping_cart</i> Keranjang Belanja
                            </a>
                        </li>

                        <li style="color:black">
                            <a href="{{ url('/ubah-profil-pelanggan') }}">
                                <i class="material-icons">settings</i> Ubah Profil
                            </a>
                        </li>
                        <li style="color:black">
                            <a href="{{ url('/ubah-password') }}">
                                <i class="material-icons">lock_outline</i> Ubah Password
                            </a>
                        </li>
                        <li style="color:black">
                            <a href="{{ url('/logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"> 
                                <i class="material-icons">reply_all</i> Logout
                            </a>
                            <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </li>                            
                    </ul>
                </li>                    
            </ul>
        </div>
    </nav>
    @else
    <nav class="navbar navbar-default navbar-transparent navbar-fixed-top navbar-color-on-scroll" color-on-scroll="100" id="sectionsNav">
     <div class="container">
        <ul class="nav navbar-nav navbar-right">
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <i class="material-icons">person</i> {{ Auth::user()->name }}
                    <b class="caret"></b>
                </a>
                <ul class="dropdown-menu dropdown-with-icons">
                    <li>
                        <a href="{{ url('/ubah-profil-pelanggan') }}">
                            <i class="material-icons">settings</i> Ubah Profil
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/ubah-password') }}">
                            <i class="material-icons">lock_outline</i> Ubah Password
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"> 
                            <i class="material-icons">reply_all</i> Logout
                        </a>
                        <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </li>                             
                </ul>
            </li>

            <li class="button-container">
                <a href="{{ url('/keranjang-belanja') }}" target="_blank" class="btn btn-rose btn-round">
                    <i class="material-icons">shopping_cart</i> Keranjang Belanja
                </a>
            </li>

        </ul>
    </div>
</nav>
@endif 


@yield('content')



<footer class="footer footer-black footer-big">
    <div class="container">

        <div class="content">
            <div class="row">
                <div class="col-md-4">
                    <h5>Tentang Kami</h5>
                    <p>Warmart adalah marketplace warung muslim pertama di Indonesia. Kami menghubungkan usaha-usaha muslim dengan pelanggan seluruh Umat Islam di Indonesia. Jenis usaha yang dapat bergabung dengan Warmart diantaranya: Warung, Toko, Minimarket, Pedagang Kaki Lima, Bengkel, Rumah Makan, Klinik, Home Industri, Peternakan, Pertanian, Perikanan, Kerajinan, Fashion dan usaha lainya.</p>
                </div>

                <div class="col-md-4">
                    <h5>Contact Us</h5>
                    <div class="social-feed">
                        <div class="feed-line">
                            <i class="fa fa-phone-square"></i>
                            <p>+62-721-8050-299 <br>
                                Bandar Lampung, Indonesia
                                solusibisnis@andaglos.id</p>
                            </div>
                            <div class="feed-line">                            
                                <a href="https://id-id.facebook.com/andaglos/" target="blank"><i class="fa fa-facebook-square"></i> Andaglos</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <h5>Instagram</h5>
                        <div class="gallery-feed">
                        </div>

                    </div>
                </div>
            </div>


            <hr />

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
        Copyright &copy; <script>document.write(new Date().getFullYear())</script> <a href="https://andaglos.id/"> PT. Andaglos Global Teknologi.</a>
    </div>
</div>
</footer>
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
<!--    Plugin for Select, full documentation here: http://silviomoreto.github.io/bootstrap-select   -->
<script src="{{ asset('js/bootstrap-selectpicker.js') }}"></script>
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

<script src="{{ asset('js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('js/demo.js') }}"></script>
<script src="{{ asset('js/material-kit.js?v=1.2.0')}}" type="text/javascript"></script>

@yield('scripts')
</html>