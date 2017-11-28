<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>War-Mart.id</title>
  <meta charset="utf-8" />
  <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('img/favicon.png') }}" />
  <link rel="icon" type="image/png" href="{{ asset('img/favicon.png') }}" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
  <meta name="viewport" content="width=device-width" />
  <!-- Bootstrap core CSS     -->
  <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet" />


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

</style>
<body class="product-page"> 
  <nav class="navbar navbar-default navbar-transparent navbar-fixed-top navbar-color-on-scroll" color-on-scroll=" " id="sectionsNav">
    <div class="container">
      <!-- Brand and toggle get grouped for better mobile display -->
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a href="{{ url('/') }}"><img class="navbar-brand"   src="{{asset('/assets/img/examples/warmart_logo.png')}}" /></a>
        @if(Agent::isMobile() && Auth::check() && Auth::user()->tipe_user == 3)
        <a href="{{ url('/keranjang-belanja') }}" class="navbar-brand pull-right" >
          <i class="material-icons">shopping_cart</i> <b style="font-size: 15px">| {{ $cek_belanjaan }}</b>
        </a>
        @endif

      </div>

      <div class="collapse navbar-collapse">
        <ul class="nav navbar-nav navbar-right">
          @if(Auth::check())
          <li class="dropdown button-container ">
            <a href="#" class="dropdown-toggle btn btn-rose btn-round" data-toggle="dropdown">
              <i class="material-icons">account_circle</i> {{ Auth::user()->name}}
              <b class="caret"></b>
            </a>
            <ul class="dropdown-menu dropdown-with-icons">

              @if(Auth::user()->tipe_user == 3)
              <li style="color:black">
                <a href="{{ url('/ubah-profil-pelanggan') }}">
                  <i class="material-icons">settings</i> Ubah Profil
                </a>
              </li>
              <li style="color:black">
                <a href="{{ url('/ubah-password-pelanggan') }}">
                  <i class="material-icons">lock_outline</i> Ubah Password
                </a>
              </li>
              @endif
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
          @if(Auth::user()->tipe_user == 3)
          <li class="button-container">
            <a href="{{ url('/pesanan') }}">
              <i class="material-icons">archive</i> Pesanan
            </a>
            @endif

          </li>
          @endif
          <li>
            <a href="https://info.war-mart.id">
              <i class="material-icons">info</i>INFO Warmart
            </a>
          </li>   
          <li>
            <a href="{{ url('/tentang-warmart')}}">
              <i class="material-icons">info</i>Tentang Warmart
            </a>
          </li>
          @if(Auth::check() && Auth::user()->tipe_user == 3)
          <li class="button-container">
            <a href="{{ url('/keranjang-belanja') }}" class="btn btn-round btn-rose" >
              <i class="material-icons">shopping_cart</i> Keranjang Belanja <b style="font-size: 15px">| {{ $cek_belanjaan }}</b>
            </a>
          </li>
          @endif




          @if(!Auth::check())
          <li class="button-container">
            <a href="{{ url('/login')}}" class="btn btn-rose btn-round">
              <i class="material-icons">lock_outline
              </i> Masuk
            </a>
          </li>
          @endif
        </ul>
      </div>
    </div>
  </nav>

  <div class="page-header header-filter header-small" data-parallax="false"" style="background-image: url('../image/background2.jpg');"> 
    <div class="container">
      <div class="row">
        <div class="col-md-8 col-md-offset-2">
          <div class="brand">
            <h2 class="title text-center">PASAR MUSLIM INDONESIA</h2>
            @if(!Agent::isMobile())
            <h4 class="title text-center"> Segala Kemudahan Untuk Umat Muslim Berbelanja.</h4>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="section section-gray">
    <div class="container"> 
      <div class="main main-raised main-product">
        <div class="row page-1">
          <div class="col-md-6 col-sm-6">
            @if($barang->foto != NULL)
            <img class="img-produk" src="../foto_produk/{{$barang->foto}}"/>
            @else 
            <img class="img-produk" src="../image/foto_default.png"/>
            @endif
          </div>
          <div class="col-md-6 col-sm-6"> 
            <h2 class="title"> {{ $barang->nama }} </h2>
            <h3 class="main-price h3">Rp. {{ number_format($barang->harga_jual,0,',','.') }}</h3> 
            <a style="font-size: 20px;" class="description"><i style="font-size: 30px;" class="material-icons">store</i>  {{ $barang->warung->name }}</a>
            {!! substr($barang->deskripsi_produk, 0, 300) !!}
            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
              <h4 class="panel-title">
                <b> Baca Selengkapnya... </b><i class="material-icons">keyboard_arrow_down</i>
              </h4>
            </a>
          </div>
          @if (Agent::isMobile()) <!--JIKA DAKSES VIA HP/TAB-->
          <div class="row text-center">
            @if ($cek_produk == 0)
            <a  rel="tooltip" title="Stok Tidak Ada" disabled="" class="btn btn-round"  style="background-color: #01573e">Beli Sekarang &nbsp;<i class="material-icons">shopping_cart</i></a> 
            @else
            <a  rel="tooltip" title="Tambah Ke Keranjang Belanja" href="{{ url('/keranjang-belanja/tambah-produk-keranjang-belanja/'.$barang->id.'') }}" id="btnBeliSekarang" class="btn btn-round"  style="background-color: #01573e">Beli Sekarang &nbsp;<i class="material-icons">shopping_cart</i></a> 
            @endif
          </div>
          @else 

          <div class="row text-right">
            @if ($cek_produk == 0)
            <a  rel="tooltip" title="Stok Tidak Ada" disabled="" class="btn btn-round"  style="background-color: #01573e">Beli Sekarang &nbsp;<i class="material-icons">shopping_cart</i></a> 
            @else
            <a  rel="tooltip" title="Tambah Ke Keranjang Belanja" href="{{ url('/keranjang-belanja/tambah-produk-keranjang-belanja/'.$barang->id.'') }}" id="btnBeliSekarang" class="btn btn-round"  style="background-color: #01573e">Beli Sekarang &nbsp;<i class="material-icons">shopping_cart</i></a> 
            @endif
          </div>         
          @endif
          <div class="col-sm-12 col-md-12">                     
            <div id="acordeon">
              <div class="panel-group" id="accordion">
                <div class="panel panel-border panel-default">
                  <div id="collapseOne" class="panel-collapse collapse">
                    <div class="panel-body">
                     <div class="panel-body"><hr style="border-width: 1px; border-color: black">
                       <h3 class="h3">Detail Produk Dari {{$barang->nama}}</h3><br>
                       {!!$barang->deskripsi_produk!!}
                     </div>
                   </div>
                 </div>
               </div>
             </div>
           </div><!--  end acordeon -->
         </div>
       </div>  
       <div class="related-products">
        <h3 class="text-center ">Produk Sekategori</h3>
        <div class="row">
          {!! $daftar_produk_sama !!}  
        </div>
      </div>
      <hr>
      <div class="related-products">
        <h3 class="text-center ">Produk {{ title_case($barang->warung->name) }}</h3>
        <div class="row"> 
          {!! $daftar_produk_warung !!}   
        </div>
      </div>
    </div>
  </div>
</div>
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
{{-- lazy load image --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-lazyload/10.3.5/lazyload.min.js"></script>

<script type="text/javascript">
  var myLazyLoad = new LazyLoad();
</script>
<script type="text/javascript"> 
 $(document).on('click', '#btnBeliSekarang', function(){      
  swal({
    title: "Produk Berhasil Di Tambah", 
    showConfirmButton :  false,
    type: "success",
  });
});
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