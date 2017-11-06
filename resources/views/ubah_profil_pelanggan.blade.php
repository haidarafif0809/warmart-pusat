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

    #card-ubah-profil{
        background: #FFFFFF;
        position: relative;
        z-index: 3;

        margin: -60px 30px 60px;
        border-radius: 6px;
        box-shadow: 0 16px 24px 2px rgba(0, 0, 0, 0.14), 0 6px 30px 5px rgba(0, 0, 0, 0.12), 0 8px 10px -5px rgba(0, 0, 0, 0.2);
    }
</style>

<body class="ecommerce-page">

    @if ($agent->isMobile()) <!--JIKA DAKSES VIA HP/TAB-->
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
                            <a href="{{ url('/ubah-profil') }}">
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
                        <a href="{{ url('/ubah-profil') }}">
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

@if ($agent->isMobile()) <!--JIKA DAKSES VIA HP/TAB-->

<br><br><br><br><br><br><br><br>
<div class="main main-raised" style="background-color: #E5E5E5">

    <div class="container">
        <h3 class="title text-center">{!! $nama_kategori !!}</h3>

        <div class="card card-raised card-form-horizontal">
            <div class="card-content">
                <form method="" action="">
                    <div class="row">
                        <div class="col-sm-10">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="material-icons">search</i>
                                </span>
                                <input type="email" id="cari_produk" value="" placeholder="Cari Produk.." class="form-control" />
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <button type="button" class="btn btn-block" style="background-color: #f44336">Cari</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12"> 
                <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="headingTwo">
                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            <h4 class="title">
                                KATEGORI
                                <i class="material-icons">keyboard_arrow_down</i>
                            </h4>
                        </a>
                    </div>
                    <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                        <div class="panel-body">

                            <ul class="nav" style="background-color: #f44336">                                        
                                <li><a style="color:white" href="{{route('daftar_produk.index')}}"><i class="material-icons">format_align_justify</i> SEMUA KATEGORI</a></li>
                            </ul>

                            <ul class="nav" style="background-color: #f44336">
                                $kategori_produk                        
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12"><br>
                <div class="row">
                    <!-- Menampilkan Produk -->
                    $daftar_produk 
                    <div class="col-md-12">
                        $produk_pagination
                    </div>
                </div>
            </div>
        </div>
    </div>

</div> <!-- end-main-raised -->
@else <!--JIKA DIAKSES VIA KOMPUTER-->
<div class="page-header header-filter header-small" data-parallax="true"" style="background-image: url('image/background2.jpg');">
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

<div class="container">
    <div class="card" id="card-ubah-profil">
        <div class="card-header card-header-icon" data-background-color="rose">
            <i class="material-icons">settings</i>
        </div>
        <div class="card-content">
            <h4 class="card-title"> Ubah Profil </h4>
            <div class="toolbar">
                {!! Form::model($user, ['url' => route('user.proses_ubah_profil_pelanggan'), 'method' => 'put', 'files'=>'true','class'=>'form-horizontal']) !!}
                <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                    {!! Form::label('name', 'Nama Pelanggan', ['class'=>'col-md-2 control-label']) !!}
                    <div class="col-md-10">
                        {!! Form::text('name', null, ['class'=>'form-control','required','autocomplete'=>'off', 'placeholder' => 'Nama Pelanggan', 'id' => 'nama_pelanggan']) !!}
                        {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>

                <div class="form-group{{ $errors->has('no_telp') ? ' has-error' : '' }}">
                    {!! Form::label('no_telp', 'No. Telpon', ['class'=>'col-md-2 control-label']) !!}
                    <div class="col-md-10">
                        {!! Form::number('no_telp', null, ['class'=>'form-control','required','autocomplete'=>'off', 'placeholder' => 'No. Telpon', 'id' => 'telpon_pelanggan']) !!}
                        {!! $errors->first('no_telp', '<p class="help-block" id="no_telp_error">:message</p>') !!}
                    </div>
                </div>

                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                    {!! Form::label('email', 'Email Pelanggan', ['class'=>'col-md-2 control-label']) !!}
                    <div class="col-md-10">
                        {!! Form::email('email', null, ['class'=>'form-control','autocomplete'=>'off', 'placeholder' => 'Email Pelanggan', 'id' => 'email_pelanggan']) !!}
                        {!! $errors->first('email', '<p class="help-block" id="email_error">:message</p>') !!}
                    </div>
                </div> 

                <div class="form-group{{ $errors->has('alamat') ? ' has-error' : '' }}">
                    {!! Form::label('alamat', 'Alamat Pelanggan', ['class'=>'col-md-2 control-label']) !!}
                    <div class="col-md-10">
                        {!! Form::text('alamat', null, ['class'=>'form-control','required','autocomplete'=>'off', 'placeholder' => 'Alamat Pelanggan', 'id' => 'alamat_pelanggan']) !!}
                        {!! $errors->first('alamat', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>

                <div class="form-group{{ $errors->has('tgl_lahir') ? ' has-error' : '' }}">
                    {!! Form::label('tgl_lahir', 'Tanggal Lahir', ['class'=>'col-md-2 control-label']) !!}
                    <div class="col-md-10"> 
                        @if (isset($pelanggan) && $pelanggan)
                        {!! Form::text('tgl_lahir', $tanggal, ['class'=>'form-control datepicker', 'id'=>'datepicker','placeholder'=>'Tanggal Lahir','readonly']) !!}
                        {!! $errors->first('tgl_lahir', '<p class="help-block">:message</p>') !!}
                        @else
                        {!! Form::text('tgl_lahir', null, ['class'=>'form-control datepicker', 'id'=>'datepicker','placeholder'=>'Tanggal Lahir','readonly']) !!}
                        {!! $errors->first('tgl_lahir', '<p class="help-block">:message</p>') !!}
                        @endif    
                    </div>
                </div>

                <div class="form-group{{ $errors->has('komunitas') ? ' has-error' : '' }}">
                    {!! Form::label('komunitas', 'Komunitas', ['class'=>'col-md-2 control-label']) !!}
                    <div class="col-md-10">
                        @if (isset($komunitas_pelanggan))
                        {!! Form::select('komunitas', [''=>'']+App\Komunitas::where('tipe_user','2')->pluck('name','id')->all(),$komunitas_pelanggan->komunitas_id, ['class'=>'form-control js-selectize-reguler', 'placeholder' => '--PILIH KOMUNITAS--','id'=>'pilih_komunitas']) !!}
                        {!! $errors->first('komunitas', '<p class="help-block">:message</p>') !!}
                        @else
                        {!! Form::select('komunitas', 
                        [''=>'']+App\Komunitas::where('tipe_user','2')->pluck('name','id')->all(),null
                        , ['class'=>'form-control js-selectize-reguler', 'placeholder' => '--PILIH KOMUNITAS--','id'=>'pilih_komunitas']) !!}
                        {!! $errors->first('komunitas', '<p class="help-block">:message</p>') !!}
                        @endif
                    </div>
                </div>
                {!! Form::hidden('id', $user->id, ['class'=>'form-control','autocomplete'=>'off']) !!}
                <div class="col-md-2"></div>
                <div class="col-md-4">
                    {!! Form::button('<i class="material-icons">save</i>Simpan Profil', ['class'=>'btn btn-rose ', 'type'=>'submit', 'id' => 'btnSimpanProfil']) !!}
                </div>

                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endif


<footer class="footer footer-black footer-big">
    <div class="container">

        <div class="content">
            <div class="row">
                <div class="col-md-4">
                    <h5>Tentang Kami</h5>
                    <p>Creative Tim is a startup that creates design tools that make the web development process faster and easier. </p> <p>We love the web and care deeply for how users interact with a digital product. We power businesses and individuals to create better looking web projects around the world. </p>
                </div>

                <div class="col-md-4">
                    <h5>Media Sosial</h5>
                    <div class="social-feed">
                        <div class="feed-line">
                            <i class="fa fa-twitter"></i>
                            <p>How to handle ethical disagreements with your clients.</p>
                        </div>
                        <div class="feed-line">
                            <i class="fa fa-twitter"></i>
                            <p>The tangible benefits of designing at 1x pixel density.</p>
                        </div>
                        <div class="feed-line">
                            <i class="fa fa-facebook-square"></i>
                            <p>A collection of 25 stunning sites that you can use for inspiration.</p>
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

<script type="text/javascript">
 $(document).ready(function(){
    $("#nama_pelanggan").focus();
});

 $('.datepicker').datepicker({
    format: 'yyyy-mm-dd', 
    autoclose: true,
});
 $(document).on('click', '#btnSimpanProfil', function(){
    swal("Berhasil!", "Profil Berhasil Diubah", "success");
});
</script>


</html>