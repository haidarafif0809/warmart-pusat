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

</head>

    <body class="landing-page">
    <nav class="navbar navbar-default navbar-transparent navbar-fixed-top navbar-color-on-scroll" color-on-scroll=" " id="sectionsNav">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            @if (Auth::check())
            <a href="{{ route('home.dashboard') }}" style="color:#ffc34d;" class="btn  btn-round"><i class="material-icons">dashboard</i> Dashboard</a>
            @else
            <a href="{{ url('/login') }}" style="color:#ffc34d;" class="btn  btn-round"><i class="material-icons">fingerprint</i> Login WarMart</a>
            @endif
        </div>
    </nav>

            <center class="back"><div class="container">
                        <b><h2 style="color:white;font-weight:bold;">ANDA MUSLIM ?</h2>
                            <h2 style="color:white;font-weight:bold;">SUDAHKAH ANDA BERBELANJA KE SAUDARA MUSLIM ?</h2></b>
                            <h2 style="color:#ffc34d;">Jadilah penjuang untuk kemajuan perekonomian Islam</h2>
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
                                        <img class="img img-raised" src="assets/img/flat/pelanggan.jpg" />
                                    </a>
                                </div>

                                <div class="card-content">
                                    <h4 class="category text-danger">PELANGGAN</h4>
                                    <p class="card-description" style="color:black;">
                                        Berbelanja di WarMart, berarti Anda telah membantu memajukan perekonomian saudara-saudara muslim. Memajukan perekonomian Islam adalah salah satu wujud dari jihad ekonomi yang insya Allah akan mengalirkan pahala kepada Anda. Mari berbelanja sambil beramal.<center><a href="{{ url('/register-customer') }}" class="btn btn-danger"> DAFTAR </a></center>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="card card-blog">
                                <div class="card-image">
                                    <a href="#pablo">
                                        <img class="img img-raised" src="assets/img/warung.jpg"/>
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
                                        <img class="img img-raised" src="assets/img/flat/komunitas.jpg"/>
                                    </a>
                                </div>

                                <div class="card-content">
                                    <h4 class="category text-danger">
                                        PENGGIAT KOMUNITAS
                                    </h4>
                                    <p class="card-description" style="color:black;">
                                       Bagi Anda penggiat komunitas atau orang yang memiliki wewenang dalam sebuat komunitas, dapat mengarahkan anggota komunitas Anda untuk berbelanja di WarMart. Anda dapat mendaftarkan anggota komunitas melalui aplikasi WarMart Anda maupun melalui link url affiliate.<br><center><a href="{{ url('/register') }}" class="btn btn-danger"> DAFTAR </a></center>
                                    </p>
                                </div>
                            </div>
                        </div>


                        <div class="col-md-3">
                            <div class="card card-blog">
                                <div class="card-image">
                                    <a href="#pablo">
                                        <img class="img img-raised" src="assets/img/flat/ajak_ajak.jpg"/>
                                        <br>
                                    </a>
                                </div>

                                <div class="card-content">
                                    <h4 class="category text-danger">
                                         AJAK TEMAN
                                    </h4>

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

    <div class="main main-raised">
        <div class="profile-content">
            <div class="container">
                    <div class="description">
            
            <div class="row">

                    <div class="col-md-6">
                        <div class="card card-raised card-background">
                            <div class="card-content">
                                <a href="#pablo">
                                    <h3 class="card-title">Manfaat Pelanggan</h3>
                                </a>
                                <p class="card-description">
                                    <h4 style="color:white;">
                                    <ul>
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

                    <div class="col-md-6">
                        <div class="card card-raised card-background">
                            <div class="card-content">
                                <h3 class="card-title">Manfaat Warung</h3>
                                <p class="card-description">
                                   <h4 style="color:white;">
                                   <ul>
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
            </div><!-- div row-->

              <div class="row">

                    <div class="col-md-6">
                        <div class="card card-raised card-background" >
                            <div class="card-content">
                                <h3 class="card-title">Manfaat Penggiat Komunitas</h3>
                                <p class="card-description">
                              <h4 style="color:white;">
                                <ul>       
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
                
                    <div class="col-md-6">
                        <div class="card card-raised card-background" >
                            <div class="card-content">
                                <h3 class="card-title">Manfaat Ajak Teman</h3>
                                <p class="card-description">
                               <h4 style="color:white;">
                                <ul>
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
            </div><!-- div row-->

            </div>
        </div>
    </div>
</div>


    <!--     *********    TESTIMONIALS     *********      -->

<div class="section" >
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-md-offset-3 text-center">
                    <h2 class="text-center">Kata Mereka</h2>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="card card-testimonial">
                        <div class="icon">
                        </div>
                        <div class="card-content">
                            <h5 class="card-description" style="color:black;">
                                 “Seandainya Umat Islam sepakat semuanya, tidak akan membeli produk kecuali milik saudaranya, beres semuanya. Dan kalau Islam jaya, orang di luar Islam itu akan tertolong juga, nggak akan terhinakan.”<br><br>
                            </h5>
                        </div>

                        <div class="footer">
                            <h4 class="card-title" >Buya Yahya</h4>
                            <h6 >Pendiri Pondok Pesantren Al-Bahjah</h6>
                            <div class="card-avatar">
                                <a href="#pablo">
                                    <img class="img" src="assets/img/faces/buya_yahya.png" />
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card card-testimonial">
                        <div class="icon">
                        </div>
                        <div class="card-content">
                            <h5 class="card-description" style="color:black;">
                               “Apapun yang berkaitan dengan masalah produk, apapun yang berkaitan dengan masalah fasilitas dan segala macamnya, selagi ada muslim yang punya, agak mahal nggak apa-apa, kualitasnya agak dibawah nggak apa-apa....Allah akan tolong kamu.”<br>
                            </h5>
                        </div>

                        <div class="footer">
                            <h4 class="card-title" >Ust. Zulkifli M Ali, Lc, MA</h4>
                            <h6 >Da'i / Pendakwah</h6>
                            <div class="card-avatar">
                                <a href="#pablo">
                                    <img class="img" src="assets/img/faces/ust_zulkifli.jpg" />
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card card-testimonial">
                        <div class="icon">
                        </div>
                        <div class="card-content" >
                            <h5 class="card-description" style="color:black;">
                                "Manusia adalah pasar, suka nggak suka, saya jualan nggak jualan, setiap orang belanja, setiap orang ngeluarin duit. Pertanyaanya, setiap orang pasti belanja, tapi tidak setiap orang muslim jualan, ini masalah. Trilyunan rupiah kita keluar, karena kita nggak jualan, itu masalahnya."
                            </h5>
                        </div>

                        <div class="footer">
                            <h4 class="card-title">Rendy Saputra</h4>
                            <h6 >Pengusaha Muda</h6>
                            <div class="card-avatar">
                                <a href="#pablo">
                                    <img class="img" src="assets/img/faces/rendy_saputra.jpg" />
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <br>
            <br>
        </div>
    </div>

    <!--     *********    CALL TO ACTION       *********      -->

 <div class="main main-raised section-image" style="background-image: url('assets/img/open_shop.jpg');">
        <div class="container">
            <div class="section text-center">
              
                    <center><h3 style="color:white;font-style:italic;" class="card-description">Mari menjadi bagian dalam kebangkitan perekonomian Islam di Indonesia.</h3><h4 style="color:white;font-style:italic;" class="card-description"> Islam berjaya, akan menjadi Rahmat bagi seluruh Alam.</h4><h5 style="color:white;font-style:italic;" class="card-description"> Ambil Bagian Anda sekarang juga</h5></center><br>
                    <center><a href="#" class="btn btn-danger swal-pendaftaran"> DAFTAR </a></center>
                 
            </div>

            <div class="section text-center">
             
            </div>
        </div>
</div>




    <footer class="footer">
        <div class="container">
           
            <div class="copyright pull-right">
                &copy; <script>document.write(new Date().getFullYear())</script>, Created by PT Andaglos Global Teknologi
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
        title: 'Daftar Sebagai?',
        html:
            '<li class="" style="list-style-type:none"><a href="{{ url('/register-customer') }}"  class="btn btn-info"><i class="material-icons">person_add</i> Pelanggan</a></li><li class="" style="list-style-type:none"><a href="{{ url('/register') }}"  class="btn btn-success"><i class="material-icons">people</i> Komunitas</a></li><li class=""  style="list-style-type:none"><a href="{{ url('/register-warung') }}"  class="btn btn-warning"><i class="material-icons">store</i> Warung</a></li> ',
        showConfirmButton :  false,
    });
});
</script>

</html>