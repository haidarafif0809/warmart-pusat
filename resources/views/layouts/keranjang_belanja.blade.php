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


<body class="ecommerce-page">
    <nav class="navbar navbar-default navbar-transparent navbar-fixed-top navbar-color-on-scroll" color-on-scroll="100" id="sectionsNav">
        <div class="container">
            <div class="card">
                <div class="card-header card-header-icon" data-background-color="rose">
                    <i class="material-icons">assignment</i>
                </div>
                <div class="card-content">
                    <h4 class="card-title">Shopping Cart Table</h4>
                    <div class="table-responsive">
                        <table class="table table-shopping">
                            <thead>
                                <tr>
                                    <th class="text-center"></th>
                                    <th>Product</th> 
                                    <th class="text-right">Price</th>
                                    <th class="text-right">Qty</th>
                                    <th class="text-right">Amount</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="img-container">
                                            <img src="{{ asset('image/foto_default.png')}}" alt="...">
                                        </div>
                                    </td>
                                    <td class="td-name">
                                        <a href="#jacket">Spring Jacket</a>
                                        <br />
                                        <small>by Dolce&Gabbana</small>
                                    </td> 
                                    <td class="td-number text-right">
                                        <small>&euro;</small>549
                                    </td>
                                    <td class="td-number">
                                        1
                                        <div class="btn-group">
                                            <button class="btn btn-round btn-info btn-xs"> <i class="material-icons">remove</i> </button>
                                            <button class="btn btn-round btn-info btn-xs"> <i class="material-icons">add</i> </button>
                                        </div>
                                    </td>
                                    <td class="td-number">
                                        <small>&euro;</small>549
                                    </td>
                                    <td class="td-actions">
                                        <button type="button" rel="tooltip" data-placement="left" title="Remove item" class="btn btn-simple">
                                            <i class="material-icons">close</i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="img-container">
                                            <img src="{{ asset('image/foto_default.png')}}" alt="..." />
                                        </div>
                                    </td>
                                    <td class="td-name">
                                        <a href="#pants">Short Pants</a>
                                        <br />
                                        <small>by Pucci</small>
                                    </td> 
                                    <td class="td-number">
                                        <small>&euro;</small>499
                                    </td>
                                    <td class="td-number">
                                        2
                                        <div class="btn-group">
                                            <button class="btn btn-round btn-info btn-xs"> <i class="material-icons">remove</i> </button>
                                            <button class="btn btn-round btn-info btn-xs"> <i class="material-icons">add</i> </button>
                                        </div>
                                    </td>
                                    <td class="td-number">
                                        <small>&euro;</small>998
                                    </td>
                                    <td class="td-actions">
                                        <button type="button" rel="tooltip" data-placement="left" title="Remove item" class="btn btn-simple">
                                            <i class="material-icons">close</i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="img-container">
                                            <img src="{{ asset('image/foto_default.png')}}" alt="...">
                                        </div>
                                    </td>
                                    <td class="td-name">
                                        <a href="#nothing">Pencil Skirt</a>
                                        <br />
                                        <small>by Valentino</small>
                                    </td> 
                                    <td class="td-number">
                                        <small>&euro;</small>799
                                    </td>
                                    <td class="td-number">
                                        1
                                        <div class="btn-group">
                                            <button class="btn btn-round btn-info btn-xs"> <i class="material-icons">remove</i> </button>
                                            <button class="btn btn-round btn-info btn-xs"> <i class="material-icons">add</i> </button>
                                        </div>
                                    </td>
                                    <td class="td-number">
                                        <small>&euro;</small>799
                                    </td>
                                    <td class="td-actions">
                                        <button type="button" rel="tooltip" data-placement="left" title="Remove item" class="btn btn-simple">
                                            <i class="material-icons">close</i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="5"></td>
                                    <td class="td-total">
                                        Total
                                    </td>
                                    <td colspan="1" class="td-price">
                                        <small>&euro;</small>2,346
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan="6"></td>
                                    <td colspan="2" class="text-right">
                                        <button type="button" class="btn btn-info btn-round">Complete Purchase <i class="material-icons">keyboard_arrow_right</i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</div> <!-- end-main-raised -->

<div class="section section-blog">
</div><!-- section -->

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

<script>
    $(document).on('click','.btn-wishlist',function(){
        var data_toggle = $(this).attr('data-toogle');
        var id = $(this).attr('data-id');

        if (data_toggle == 0) {
            $(this).attr("data-toogle", 1);
            $(this).attr("data-original-title", "Hapus Dari Wishlist");
            $("#icon_wishlist-"+id+"").text("favorite");
        }
        else{
            $(this).attr("data-toogle", 0);
            $(this).attr("data-original-title", "Tambah Ke Wishlist");                
            $("#icon_wishlist-"+id+"").text("favorite_border");
        }            
    }); 
    $("#form_filter_kategori").submit(function(){
        return false;
    });
</script>


</html>