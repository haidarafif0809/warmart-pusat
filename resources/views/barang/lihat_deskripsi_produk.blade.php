<style type="text/css">
    .list-produk {

        padding-left: 4px;
        padding-right: 4px;

    }
    .card .card-image{

        height: auto; /*this makes sure to maintain the aspect ratio*/
        margin-top: 0px;
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
    .buttonColor{
        @if($setting_aplikasi->tipe_aplikasi == "1") /*tipe-aplikasi == 1, aplikasi topos*/
        background-color: #2ac326;
        @else
        background-color: #01573e;
        @endif
    }
    @font-face {
      font-family: "San Francisco";
      font-weight: 200;
      src: url("//applesocial.s3.amazonaws.com/assets/styles/fonts/sanfrancisco/sanfranciscodisplay-thin-webfont.woff2");
  }

  .flexFont {
    @if(Agent::isMobile())
    height:3em;
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

</style>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8" />
    @if($setting_aplikasi->tipe_aplikasi == 0)
    <link rel="apple-touch-icon" sizes="76x76" href="img/favicon.png" />
    <link rel="icon" type="image/png" href="img/favicon.png" />
    @else
    <link rel="apple-touch-icon" sizes="76x76" href="img/icon_topos.jpg" />
    <link rel="icon" type="image/png" href="img/icon_topos.jpg" />
    @endif
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />

    @if($setting_aplikasi->tipe_aplikasi == 0)
    <title>War-Mart.id</title>
    @else
    <title>To-Pos.id</title>
    @endif


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

<body class="product-page">

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
                <a class="navbar-brand" href="{{ url('/')}}">War-Mart.id</a>
                @else
                <a class="navbar-brand" href="{{ url('/')}}"><img class="navbar-brand" src="{{asset('/assets/img/examples/topos_logo.png')}}"/></a>
                @endif
            </div>

        </div>
    </nav>

    @if($setting_aplikasi->tipe_aplikasi == 0)
      {!! Html::image(asset('image/background2.jpg')) !!} 
    @else
    <div class="page-header  header-small" data-parallax="true"" style="background-color: #2ac326">
   @endif
   @if (Agent::isMobile()) <!--JIKA DAKSES VIA HP/TAB-->
   <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="brand">
                        @if($setting_aplikasi->tipe_aplikasi == 0)
                        <h3 class="title"  align="center">PASAR MUSLIM INDONESIA</h3>
                        @else
                        <h3 class="title"  align="center">TOKO ONLINE DAN POS</h3>
                        @endif
                    </div>
                </div>
            </div>
        </div>
       @else <!--JIKA DIAKSES VIA KOMPUTER-->
                 <div class="container">
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <div class="brand">
                            @if($setting_aplikasi->tipe_aplikasi == 0)
                            <h1 class="title">PASAR MUSLIM INDONESIA</h1>
                            @else
                            <h1 class="title">TOKO ONLINE DAN POS</h1>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

  <div class="section section-gray">
   <div class="container">
    <div class="main main-raised main-product">
      <div class="row">
        <div class="col-md-6 col-sm-6">
         @if(isset($lihat_deskripsi_produk->foto))
         {!! Html::image(asset('foto_produk/'.$lihat_deskripsi_produk->foto)) !!}
         @else
         {!! Html::image(asset('image/foto_default.png')) !!}
         @endif
       </div>
       <div class="col-md-6 col-sm-6">
        <h2 class="title"> {{ $nama_produk }} </h2>
        <h3 class="main-price">Rp. {{ number_format($lihat_deskripsi_produk->harga_jual,0,',','.') }}</h3>
        {!! substr($lihat_deskripsi_produk->deskripsi_produk, 0, 300) !!}...
        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
         <h4 class="panel-title">
           <b> Baca Selengkapnya... </b><i class="material-icons">keyboard_arrow_down</i>
         </h4>
       </a>
     </div>
     <div class="col-sm-12 col-md-12">
      <div id="acordeon">
        <div class="panel-group" id="accordion">
         <div class="panel panel-border panel-default">
           <div id="collapseOne" class="panel-collapse collapse">
             <div class="panel-body"><hr style="border-width: 1px; border-color: black">
              <h3>Detail Produk Dari {{$nama_produk}}</h3>
              {!!$lihat_deskripsi_produk->deskripsi_produk!!}
            </div>
          </div>
        </div>

      </div>
    </div><!--  end acordeon -->

    <div class="row text-right">
      <a href="{{url('/dashboard#/produk')}}" class="btn btn-rose btn-round">Kembali &nbsp;<i class="material-icons">reply</i></a>
    </div>

  </div>
</div>
</div>
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

<script src="{{ asset('js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('js/demo.js') }}"></script>
<script src="{{ asset('js/material-kit.js?v=1.2.0')}}" type="text/javascript"></script>
<script type="text/javascript">
      $().ready(function() {
        demo.checkFullPageBackgroundImage();

        
    });
</script>
</html>
