<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <link rel="apple-touch-icon" sizes="76x76" href="img/apple-icon.png" />
    <link rel="icon" type="image/png" href="img/favicon.png" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>WarMart.id</title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
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

    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
    {!! Twitter::generate() !!}

      <!-- MINIFIED -->
    {!! SEO::generate(true) !!}
    

        <!-- LUMEN -->
    {!! app('seotools')->generate() !!}

</head>

    <body class="landing-page">
    <nav class="navbar navbar-default navbar-transparent navbar-fixed-top navbar-color-on-scroll" color-on-scroll=" " id="sectionsNav">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            @if (Auth::check())
            <a href="{{ route('home.dashboard') }}" style="color:#ffc34d;" class="btn  btn-round"><i class="material-icons">dashboard</i> Dashboard</a>
            @else
            <a href="{{ url('/login') }}" style="color:#ffc34d;" class="btn  btn-round"><i class="material-icons">fingerprint</i> Login</a>
            @endif
        </div>
    </nav>

            <center class="back"><div class="container">
                                <h2 style="color:white;font-weight:bold;">ANDA MUSLIM ?</h2>
                            <h2 style="color:white;font-weight:bold;">SUDAHKAH ANDA BERBELANJA KE SAUDARA MUSLIM ?</h2>
                            <h2 style="color:#ffc34d;">Jadilah pejuang untuk kemajuan perekonomian Islam</h2>
                        <h5 style="color:white;">WarMart adalah marketplace muslim pertama di Indonesia yang menerapkan sistem transaksi online dan offline bagi warung muslim se-Indonesia</h5>
            </div></center>


        <div class="team-4 section-image" style="background-image: url('assets/img/examples/office2.jpg');">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <div class="iframe-container">  
                            <iframe src="https://www.youtube.com/embed/wQ1MyRDCD-I?modestbranding=1&autohide=1&showinfo=0" frameborder="0" allowfullscreen height="250"></iframe>
                        </div>
                    </div>
                    <div class="col-md-5 col-md-offset-1">
                       <h2 style="color:#ffc34d;font-weight:bold;">Tentang</h2>
                        <h3 style="color:#ffffff;">WarMart adalah marketplace untuk warung muslim pertama di Indonesia yang memfasilitasi warung-warung milik saudara muslim untuk menjual produknya secara online maupun offline. Penjualan secara online melalui aplikasi android dan penjualan secara offline menggunakan aplikasi web base yang kami sediakan</h3>
                    </div>
                </div>
            </div>
        </div>
<style type="text/css">
.peran{
width:100%;
height:100%;
}
</style>
        <div class="container">
            <div class="section text-center ">
                  <img  class="img img-raised" src="assets/img/examples/warmart_logo.png" />
                  <h2 style="color:red;font-weight:bold;">PASAR MUSLIM <a style="color:black;">INDONESIA</a></h2>

            </div>
        </div>
 <!--     *********    PERAN ANDA     *********      -->

    <div class="section" style="background-color:#ffc34d;">
        <div class="container" >
            <div class="row">


                <div class="col-md-12">
                    <h2 class="text-center">Peran Anda</h2>
                    <br />
                    <div class="row">
                        <div class="col-md-3">
                            <div class="card card-blog">
                                <div class="card-image">
                                    <a href="#pablo">
                                        <img class="img img-raised" src="assets/img/flat/pelanggan3.jpg" />
                                    </a>
                                </div>

                                <div class="card-content">
                                    <h4 class="category text-danger">PELANGGAN</h4>
                                    <p class="card-description" style="color:black;">
                                        Berbelanja di WarMart, berarti Anda telah membantu memajukan perekonomian saudara muslim. Memajukan perekonomian Islam adalah salah satu wujud dari jihad ekonomi yang insya Allah akan mengalirkan pahala kepada Anda. Mari berbelanja sambil beramal.<center><a href="{{ url('/register-customer') }}" class="btn btn-danger"> DAFTAR </a></center>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="card card-blog">
                                <div class="card-image">
                                    <a href="#pablo">
                                        <img class="img img-raised" src="assets/img/flat/warung3.jpg"/>
                                    </a>
                                </div>
                                <div class="card-content">
                                    <h4 class="category text-danger">WARUNG</h4>
                                    <p class="card-description" style="color:black;">
                                        Warung Anda akan mendapatkan support teknologi yang terupdate secara terus menerus tanpa mengeluarkan banyak biaya. Aplikasi WarMart mudah digunakan sehingga tidak ada halangan bagi Anda yang belum terbiasa menggunakan teknologi untuk pengelolaan warung.<center><a href="{{ url('/register-warung') }}" class="btn btn-danger"> DAFTAR </a></center>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="card card-blog">
                                <div class="card-image">
                                    <a href="#pablo">
                                        <img class="img img-raised" src="assets/img/flat/komunitas3.jpg"/>
                                    </a>
                                </div>
                                <div class="card-content">
                                    <h4 class="category text-danger">KOMUNITAS</h4>
                                    <p class="card-description" style="color:black;">
                                       Komunitas menjadi roda penggerak kesadaran umat untuk membela dan memperjuangkan kejayaan perekonomian Islam. Komunitas yang mendaftarkan anggotanya menjadi palanggan Warmart, akan mendapatkan share bagi hasil setiap anggota komunitas berbelanja di Warmart.<br><center><a href="{{ url('/register') }}" class="btn btn-danger"> DAFTAR </a></center>
                                    </p>
                                </div>
                            </div>
                        </div>


                        <div class="col-md-3">
                            <div class="card card-blog">
                                <div class="card-image">
                                    <a href="#pablo">
                                        <img class="img img-raised" src="assets/img/flat/ajak_ajak3.jpg"/>
                                    </a>
                                </div>
                                <div class="card-content">
                                    <h4 class="category text-danger">AJAK TEMAN</h4>
                                    <p class="card-description" style="color:black;">
                                        Anda yang sudah menjadi pelanggan WarMart dapat mengajak teman untuk menjadi pelanggan WarMart seperti Anda. Setiap teman Anda tersebut berbelanja ke Warmart di warung manapun, Anda akan mendapatkan share bagi hasil dari keuntungan warung secara otomatis. <center><a href="{{ url('/register-customer') }}" class="btn btn-danger" > DAFTAR </a></center>
                                    </p>
                                </div>
                            </div>
                        </div>


                    </div>

                </div>

            </div>
        </div>
    </div>

 <!--     *********    MANFAAT ALASAN BERGABUNG     *********      -->

                <!--     *********    MORPHING CARDS     *********      -->

                <div class="cards main main-raised" id="morphing"  >

                    <div class="container">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="rotating-card-container">
                                    <div class="card card-rotate card-background">
                                        <div class="front front-background" style="background-image: url('assets/img/flat/pelanggan_2.jpg');">
                                            <div class="card-content">
                                                <h3 class="category text-info" style="color:orange;font-weight:bold;" >Manfaat Pelanggan <i class="material-icons">refresh</i></h3>
                                                <p class="card-description">
                                               
                                                </p>
                                            </div>
                                        </div>

                                        <div class="back back-background" style="background-image: url('assets/img/flat/pelanggan_2.jpg');">
                                            <div class="card-content">
                                                <p >
                                                     <h4 style="color:white;font-weight:bold;">
                                                <ul align="left">
                                                        <li>Berkontribusi menumbuhkan Usaha muslim (jihad ekonomi) </li>
                                                        <li>Belanja bernilai ibadah </li>
                                                        <li>Mudah berbelanja di warung muslim </li>
                                                        <li>Mudah mencari barang, harga dan lokasi warung muslim terdekat </li>
                                                        <li>Barang lengkap sesuai kebutuhan </li>
                                                        <li>Potensi Income dengan mendaftarkan saudara Muslim </li>
                                                </ul>
                                                 </h4>
                                                </p>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="rotating-card-container">
                                    <div class="card card-rotate card-background">
                                        <div class="front front-background" style="background-image: url('assets/img/flat/warung_2.jpg');">
                                            <div class="card-content">
                                                <h3 class="category text-info" style="color:orange;font-weight:bold;">Manfaat Warung <i class="material-icons">refresh</i></h3>
                                                <p class="card-description">
                                               
                                                </p>
                                            </div>
                                        </div>

                                        <div class="back back-background" style="background-image: url('assets/img/flat/warung_2.jpg');">
                                            <div class="card-content">
                                                <p >
                                                     <h4 style="color:white;font-weight:bold;">
                                                    <ul align="left">
                                                            <li>Terhubung dengan Pelanggan WarMart se Indonesia </li> 
                                                            <li>Mendapat support teknologi manajemen warung </li>
                                                            <li>Mudah ditemukan konsumen </li>
                                                            <li>Penjualan online / offline </li>
                                                            <li>Mudah terakses oleh supplier </li>
                                                            <li>Terhubung dengan investor muslim </li>
                                                        </ul>
                                                    </h4>
                                                </p>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- PEMISAH ROW 1 - 2-->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="rotating-card-container">
                                    <div class="card card-rotate card-background">
                                        <div class="front front-background" style="background-image: url('assets/img/flat/komunitas_2.jpg');">
                                            <div class="card-content">
                                                <h3 class="category text-info" style="color:orange;font-weight:bold;">Manfaat Komunitas <i class="material-icons">refresh</i></h3>
                                                <p class="card-description">
                                               
                                                </p>
                                            </div>
                                        </div>

                                        <div class="back back-background" style="background-image: url('assets/img/flat/komunitas_2.jpg');">
                                            <div class="card-content">
                                                <p >
                                                   <h4 style="color:white;font-weight:bold;">
                                                    <ul align="left">       
                                                        <li>Terhubung Dengan Pergerakan Ekonomi Umat</li>
                                                        <li>Bersinergi dengan beragam komunitas</li>
                                                        <li>Berkontribusi dalam kebangkitan ekonomi Islam</li>
                                                        <li>Mendapatkan potensi income dari belanja komunitas</li>
                                                        <li>Menumbuhkan kesadaran  bela ekonomi Islam</li>
                                                    </ul>
                                                </h4>
                                                </p>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="rotating-card-container">
                                    <div class="card card-rotate card-background">
                                        <div class="front front-background" style="background-image: url('assets/img/flat/ajak_ajak_2.jpg');">
                                            <div class="card-content">
                                                <h3 class="category text-info" style="color:orange;font-weight:bold;">Manfaat Ajak Teman <i class="material-icons">refresh</i></h3>
                                                <p class="card-description">
                                               
                                                </p>
                                            </div>
                                        </div>

                                        <div class="back back-background" style="background-image: url('assets/img/flat/ajak_ajak_2.jpg');">
                                            <div class="card-content">
                                                <p class="card-description">
                                                     <h4 style="color:white;font-weight:bold;">
                                                     <ul align="left">
                                                        <li>Bebas biaya pendaftaran alias GRATIS!!! </li> 
                                                        <li>Punya penghasilan tambahan </li> 
                                                        <li>GRATIS!!! Web replika dengan nama sendiri </li> 
                                                        <li>Bergabung dengan komunitas positif </li> 
                                                        <li>Share bagi hasil dari transaksi teman </li>
                                                     </ul>
                                                 </h4>
                                                </p>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
<br><br>
                </div>


   
    <!--     *********    TESTIMONIALS 2     *********      -->

    <div class="testimonials-2 section-dark">

        <div class="container">
            <h2 class="text-center" style="color:white;font-weight:bold;">Kata Mereka</h2>
            <div class="row">

                <div id="carousel-testimonial" class="carousel slide" data-ride="carousel">

                    <div class="carousel-inner" role="listbox">
                        
                        <div class="item active">
                            <div class="card card-testimonial card-plain">
                                <div class="card-avatar">
                                    <a href="#pablo">
                                        <img class="img" src="assets/img/faces/buya_yahya.jpg" />
                                    </a>
                                </div>

                                <div class="card-content">
                                    <h5 class="card-description">
                                        “Seandainya Umat Islam sepakat semuanya, tidak akan membeli produk kecuali milik saudaranya, beres semuanya. Dan kalau Islam jaya, orang di luar Islam itu akan tertolong juga, nggak akan terhinakan.” 
                                    </h5>
                                    <br> 
                                     <h4 style="color:orange;font-weight:bold;" >Buya Yahya</h4>
                                    <h6 class="category text-muted">Pendiri Pondok Pesantren Al-Bahjah</h6>
                                </div>
                            </div>
                        </div>

                        <div class="item">
                            <div class="card card-testimonial card-plain">
                                <div class="card-avatar">
                                    <a href="#pablo">
                                        <img class="img" src="assets/img/faces/ust_zulkifli_ali.jpg" />
                                    </a>
                                </div>

                                <div class="card-content">
                                    <h5 class="card-description">
                                        “Apapun yang berkaitan dengan masalah produk, apapun yang berkaitan dengan masalah fasilitas dan segala macamnya, selagi ada muslim yang punya, agak mahal nggak apa-apa, kualitasnya agak dibawah nggak apa-apa..Allah akan tolong kamu.”
                                    </h5>
                                   <h4 style="color:orange;font-weight:bold;" >Ust. Zulkifli M Ali, Lc, MA</h4>
                                     <h6 class="category text-muted">Da'i / Pendakwah</h6>
                                </div>
                            </div>
                        </div>

                         <div class="item">
                            <div class="card card-testimonial card-plain">
                                <div class="card-avatar">
                                    <a href="#pablo">
                                        <img class="img" src="assets/img/faces/rendy_saputra.jpg" />
                                    </a>
                                </div>

                                <div class="card-content">
                                    <h5 class="card-description">
                                        "Manusia adalah pasar, suka nggak suka, saya jualan nggak jualan, setiap orang belanja, setiap orang ngeluarin duit. Pertanyaanya, setiap orang pasti belanja, tapi tidak setiap orang muslim jualan, ini masalah. Trilyunan rupiah kita keluar, karena kita nggak jualan, itu masalahnya."
                                    </h5>
                                   <h4 style="color:orange;font-weight:bold;" >Rendy Saputra</h4>
                                     <h6 class="category text-muted">Pengusaha Muda</h6>
                                </div>
                            </div>
                        </div>

                    </div>

                    <a class="left carousel-control" href="#carousel-testimonial" role="button" data-slide="prev">
                        <i class="material-icons" aria-hidden="true">chevron_left</i>
                    </a>
                    <a class="right carousel-control" href="#carousel-testimonial" role="button" data-slide="next">
                        <i class="material-icons" aria-hidden="true">chevron_right</i>
                    </a>
                </div>

            </div>

        </div>
    </div>

    <!--     *********    END TESTIMONIALS 2      *********      -->



    <!--     *********    CALL TO ACTION       *********      -->

 <div class="section-image" style="background-image: url('assets/img/open_shop.jpg');">
        <div class="container">
            <div class="section text-center">
              
                    <center><h3 class="card-description"><a style="color:orange;font-weight:bold;">MARI MENJADI BAGIAN DALAM </a> <a style="color:white;font-weight:bold;">KEBANGKITAN PEREKONOMIAN ISLAM DI INDONESIA.</h3><h4 style="color:white;font-style:italic;" class="card-description"> Islam berjaya, akan menjadi Rahmat bagi seluruh Alam.</h4><h5 style="color:white;font-style:italic;" class="card-description"> Ambil Bagian Anda sekarang juga</h5></a></center><br>
                    <center><a href="#" class="btn btn-danger swal-pendaftaran"> DAFTAR </a></center>
                 
            </div>

            <div class="section text-center">
             
            </div>
        </div>
</div>




    <footer class="footer">
        <div class="container">

             <h4><a style="font-weight:bold;">INFO :</a><a style="color:red;font-weight:italic;"> Soft launcing Aplikasi WartMart insya Allah akan dilaksanakan pada tanggal 20 November 2017</a></h4> 

            <div class="copyright pull-right">
                &copy; <script>document.write(new Date().getFullYear())</script> PT Andaglos Global Teknologi, made with love for a better web
            </div>
        </div>
    </footer>



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
$('.swal-pendaftaran').click(function(){
    swal({
        title: 'Daftar Sebagai ?',
        html:
            '<li class="" style="list-style-type:none"><a href="{{ url('/register-customer') }}"  class="btn btn-info"><i class="material-icons">person_add</i> Pelanggan</a></li><li class="" style="list-style-type:none"><a href="{{ url('/register') }}"  class="btn btn-success"><i class="material-icons">people</i> Komunitas</a></li><li class=""  style="list-style-type:none"><a href="{{ url('/register-warung') }}"  class="btn btn-warning"><i class="material-icons">store</i> Warung </a></li> ',
        showConfirmButton :  false,
    });
});
</script>

</html>